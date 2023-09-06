<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Club;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PlaytomicHttpService extends ApiHttpServiceRequest
{
    private $user;
    private $playtomic_token;
    private $headers;
    private $data_cart;

    public function __construct(User $user, $headers = [])
    {
        $this->user = $user;
        $this->playtomic_token = $user->playtomic_token;
        $this->headers = array_merge(['Authorization' => 'Bearer '.$this->playtomic_token], $headers);
        parent::__construct(env('PLAYTOMIC_URL','https://playtomic.io/api/'), $this->headers);
    }

    public function login(){
        $params = ['email' => $this->user->email, 'password' => Crypt::decrypt($this->user->playtomic_password)];
        $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
        $response = $client->post(env('PLAYTOMIC_URL','https://playtomic.io/api/').'v3/auth/login', [
            RequestOptions::JSON => $params
        ]);

        if($response->getStatusCode() === 200) {
            $response_login = json_decode($response->getBody()->getContents(),true);
            $this->user->playtomic_id = $response_login['user_id'];
            $this->user->playtomic_token = $response_login['access_token'];
            $this->user->playtomic_refresh_token = $response_login['refresh_token'];
            $this->user->save();

            return $response_login;
        }
        return null;
    }

    public function refreshToken($token){
        return $this->sendPost(['refresh_token' => $token], 'v3/auth/token' );
    }

    public function getMeInformation(){
        return $this->sendGet('/v2/users/me');
    }

    public function getInformationClub(Club $club){
        $information_club = $this->sendGet('v1/tenants/'. $club->playtomic_id);
        return $information_club;
    }

    public function getAvailabilityClub($information_club){
        $params = '';
        $today = now()->format('Y-m-d');
        $data = [
            'user_id' => 'me',
            'tenant_id' => $information_club['tenant_id'],
            'sport_id' => 'PADEL',
            'local_start_min' => $today.'T00%3A00%3A00',
            'local_start_max' => $today.'T23%3A59%3A59'
        ];
        foreach($data as $key => $value) $params .= $key."=".$value."&";
        return $this->sendGet('v1/availability?'.$params);
    }

    public function preBooking(Booking $booking, Resource $resource, Timetable $timetable = null){
        $selected_timetable = $timetable ?: $booking->timetable;
        $time = str_replace("%3A",":",$selected_timetable->playtomic_id);
        $data = [
            "allowed_payment_method_types" => ["OFFER", "CASH", "MERCHANT_WALLET", "DIRECT", "SWISH", "IDEAL", "BANCONTACT", "PAYTRAIL", "CREDIT_CARD", "QUICK_PAY"],
            'user_id' => $this->user->playtomic_id,
            "cart" => [
                "requested_item" => [
                    "cart_item_type"=> "CUSTOMER_MATCH",
                    "cart_item_voucher_id" => null,
                    "cart_item_data" => [
                        "supports_split_payment" => true,
                        "number_of_players" => "4",
                        "tenant_id" => $booking->club->playtomic_id,
                        "resource_id" => $resource->playtomic_id,
                        "start" =>  $booking->started_at->format('Y-m-d').$time.":00",
                        "duration"=> "90",
                        "match_registrations" => [
                            [
                                "user_id" => $this->user->playtomic_id,
                                "pay_now" => true
                            ]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $this->sendPost($data, 'v1/payment_intents');
            return $this->response($response);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    public function paymentMethodSelection($payment_intent_id){
        $data = [
            "selected_payment_method_id" =>  "DIRECT",
            "selected_payment_method_data" => null
        ];

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer ".$this->user->playtomic_token
            ]
        ]);
        $response = $client->patch(env('PLAYTOMIC_URL','https://playtomic.io/api/').'v1/payment_intents/'. $payment_intent_id, [
            RequestOptions::JSON => $data
        ]);

        if($response->getStatusCode() === 200) return json_decode($response->getBody()->getContents(),true);
        throw new \Exception('Error Service paymentMethodSelection');
    }

    public function confirmation($payment_intent_id){
        $response = $this->sendPost([], 'v1/payment_intents/'.$payment_intent_id.'/confirmation');
        return $this->response($response);
    }

    public function confirmationMatch($match_id){
        $response = $this->sendGet('v1/matches/'.$match_id);
        Log::info($response);
        //if($response['status'] === 'RESOURCE_NO_AVAILABLE') return null;
        return env('PLAYTOMIC_URL','https://playtomic.io/api/').'v1/matches/'.$match_id;
    }

    public function response($response){
        if (isset($response['status']) && $response['status'] === 'RESOURCE_NO_AVAILABLE') return ['status' => 'fail', 'message' => $response['localized_message']];
        if (isset($response['status']) && $response['status'] === 'RESERVATION_NOT_PROCESSABLE') return ['status' => 'fail', 'message' => $response['localized_message']];
        if (isset($response['status']) && $response['status'] == 500) return ['status' => 'fail', 'message' => $response['error']];
        return $response;
    }
}
