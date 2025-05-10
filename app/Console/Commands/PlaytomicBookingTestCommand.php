<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use App\Services\Playtomic\PlaytomicHttpService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlaytomicBookingTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playtomic:booking-test {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $service;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): void
    {
        $email = $this->argument('user');

        $this->line('⏳ Iniciando proceso de reserva para: ' . $email);
        $user = User::byEmail($email)->first();
        if (!$user) {
            Log::warning("[Abort] No user found for email: {$email}");
            $this->line('No user found');
            return;
        }

        $bookings = Booking::ontime()
            ->byPlayer($user->email)
            ->orderByDesc('started_at')
            ->get();

        $this->service = new PlaytomicHttpService($user->id, [], true);

        if($bookings){ // && $this->loginPlaytomic($user)) {
            foreach($bookings as $booking) {
                $this->booking($booking);
                //$response = $this->testBooking($user, $booking);
            }
        }

        $this->info('✅ Proceso finalizado');
    }

    private function booking($booking){
        $timetables = Timetable::whereIn('id', explode(",", $booking->timetables))
            ->orderByRaw(DB::raw("FIELD(id, {$booking->timetables})"))
            ->get();

        $resources = Resource::whereIn('id', explode(",", $booking->resources))
            ->orderByRaw(DB::raw("FIELD(id, {$booking->resources})"))
            ->get();

        $pref = $booking->booking_preference;
        $primaryItems = $pref === 'timetable' ? $timetables : $resources;
        $secondaryItems = $pref === 'timetable' ? $resources : $timetables;

        // Log reset
        foreach ($primaryItems as $p1) {
            foreach ($secondaryItems as $p2) {
                [$resource, $timetable] = $pref === 'timetable' ? [$p2, $p1] : [$p1, $p2];

                try {
                    $this->info('Prebooking');
                    $response = $this->service->preBooking($booking, $resource, $timetable);

                    if (isset($response['status']) && $response['status'] === 'fail') {
                        $this->error('Prebooking failed: ' . $response['message']);
                        return;
                    }
                } catch (\Throwable $e) {
                    $this->info('prebooking error: ' . $e->getMessage());
                }
            }
        }
    }

    private function loginPlaytomic($user): bool
    {
        try {
            $this->info('Login attempt', 'info');
            $login_response = $this->service->login();
            if (!$login_response) {
                $this->error('NOT Logged');
                return false;
            }
            $this->info('Logged', 'info', $login_response);
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
        return true;
    }

    private function testBooking(User $user, $booking)
    {
        $url = 'https://playtomic.com/api/v1/payment_intents';

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $user->playtomic_token,
            'User-Agent' => 'application/postscript',
        ];

        $body = [
            "allowed_payment_method_types" => [
                "OFFER", "CASH", "MERCHANT_WALLET", "DIRECT", "SWISH", "IDEAL",
                "BANCONTACT", "PAYTRAIL", "CREDIT_CARD", "QUICK_PAY"
            ],
            "user_id" => $user->playtomic_id,
            "cart" => [
                "requested_item" => [
                    "cart_item_type" => "CUSTOMER_MATCH",
                    "cart_item_voucher_id" => null,
                    "cart_item_data" => [
                        "supports_split_payment" => true,
                        "number_of_players" => "4",
                        "tenant_id" => $booking->club->playtomic_id,
                        "resource_id" => "49c2ffaa-57bf-42d5-b23f-fc9eb801ffff",
                        "start" => "2025-05-13T13:00:00",
                        "duration" => "90",
                        "match_registrations" => [
                            [
                                "user_id" => $user->playtomic_id,
                                "pay_now" => true
                            ]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withHeaders($headers)
                ->withOptions(['verify' => false])
                ->post($url, $body);

            if ($response->successful()) {
                Log::info('✅ Prebooking successful', $response->json());
                return $response->json();
            }

            Log::warning('❌ Prebooking failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \Exception("API Error: {$response->status()} - {$response->body()}");

        } catch (\Throwable $e) {
            Log::error('❌ Request exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

}
