<?php

namespace App\Http\Controllers\Playtomic;

use App\Http\Controllers\Controller;
use App\Mail\PlaytomicBookingConfirmation;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Services\PlaytomicHttpService;
use Gate;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    private $service;
    private $user;
    private $log;

    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.index');
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.create');
    }

    public function edit(Booking $booking)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.edit', compact('booking'));
    }

    public function show(Booking $booking)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.show', compact('booking'));
    }

    public function generateLinks(Booking $booking)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.generate-links', compact('booking'));
    }

    public function prebooking(){
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.pre-booking');
    }

    public function startBooking(Booking $booking){
        $this->user = Auth::user();
        if(!$this->user) {
            Log::error('No user found');
            return $this->error('No user found');
        }

        $this->service = new PlaytomicHttpService($this->user);
        $this->log[] = 'Login attempt';

        $day_to_date = (Carbon::now('Europe/Andorra'))->addDays((int)$booking->club->days_min_booking);
        if($booking->started_at->format('d-m-Y') === $day_to_date->format('d-m-Y')){
            try{
                $this->booking($booking);
            }catch(\Exception $e){
                Log::error($e->getMessage());
            }
        }

        $this->log[] = 'Booking scheduled finish';
        return $this->log;
    }

    public function booking(Booking $booking){
        $booked = false;
        $timetables = [$booking->timetable, Timetable::before($booking->timetable)->first(), Timetable::after($booking->timetable)->first()];
        $resources_ids = explode(",",$booking->resources);
        foreach ($resources_ids as $id)
            $resources[] = Resource::find($id);
        $booking_preference = ["timetable" => $timetables, "resource" => $resources];

        foreach ($booking_preference[$booking->booking_preference] as $preference){
            if($booked) break;
            foreach ($booking_preference[$booking->booking_preference === 'timetable' ? 'resource' : 'timetable'] as $preference2) {
                if ($booking->booking_preference === 'timetable') {
                    $resource = $preference2;
                    $timetable = $preference;
                } else {
                    $resource = $preference;
                    $timetable = $preference2;
                }
                $this->log[] = 'Booking start: ' . $booking->name . ' ' . $resource->name.' '.$booking->started_at->format('d-m-Y') . ' ' . $timetable->name;
                $response = $this->makeBooking($booking, $resource, $timetable);
                $this->log[] = '  Booking end: ' . $booking->name . ' ' . $resource->name.' '.$booking->started_at->format('d-m-Y') . ' ' . $timetable->name . ' Do it!';
                if (!isset($response['error'])) {
                    Mail::to($this->user)->send(new PlaytomicBookingConfirmation($booking, $resource, $timetable, $response));
                    $this->log[] = 'Mail sent to ' . $this->user->email;
                    $booked = true;
                    break;
                }
            }
        }
        $this->log[] = 'Booking processed';
        return $this->log;
    }

    public function makeBooking(Booking  $booking, Resource $resource, Timetable $timetable){
        $this->log[] = 'Prebooking '.$timetable->name;
        $prebooking = $this->getPreBooking($booking, $resource, $timetable);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Prebooking error '.$prebooking['message']];
        $this->log[] = 'Payment method '.$timetable->name;
        $prebooking = $this->paymentMethodSelection($prebooking);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Payment method selection error '.$prebooking['message']];
        $this->log[] = 'Confirmation '.$timetable->name;
        $prebooking = $this->confirmation($prebooking);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Confirmation error '.$prebooking['message']];
        $this->log[] = 'Confirmation Match '.$timetable->name;
        $prebooking = $this->confirmationMatch($prebooking);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Confirmation match error '.$prebooking['message']];
        return $prebooking;
    }

    public function getPreBooking(Booking $booking, Resource $resource, Timetable $timetable)
    {
        if($this->service->login()){
            try{
                $response = $this->service->preBooking($booking, $resource, $timetable);
                if(isset($response['status']) && $response['status'] === 'fail') {
                    $this->log[] = 'Prebooking error '. $response['message'];
                    Log::error('Prebooking error '.$response['message']);
                    return ['status' => 'fail', 'message' => 'Prebooking '.$timetable->name.' '.$response['message']];
                }
                $this->line('Prebooking Ok');
                return $response;
            }catch(\Exception $e){
                $this->log[] = 'Prebooking error '. $timetable->name.' '.$e->getMessage();
                Log::error('Prebooking error '.$e->getMessage());
                return ['status' => 'fail', 'message' => 'Prebooking '.$timetable->name.' '.$e->getMessage()];
            }
        }else return ['status' => 'fail', 'message' => 'No logged'];
    }

    public function paymentMethodSelection($prebooking)
    {
        try{
            $response = $this->service->paymentMethodSelection($prebooking["payment_intent_id"]);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->log[] = 'Payment method error '. $response['message'];
                Log::error('Payment method error '.$response['message']);
                return ['status' => 'fail', 'message' => 'Payment method error ' . $response['message']];
            }
            $this->line('Payment method Ok');
            return $response;
        }catch(\Exception $e){
            $this->log[] = 'Payment method error '. $e->getMessage();
            Log::error('Payment method error '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Payment method error '.$e->getMessage()];
        }
    }

    public function confirmation($prebooking)
    {
        try{
            $response = $this->service->confirmation($prebooking['payment_intent_id']);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->log[] = 'Confirmation error'. $response['message'];
                Log::error('Confirmation error ' . $response['message']);
                return ['status' => 'fail', 'message' => 'Confirmation error ' . $response['message']];
            }
            $this->line('Confirmation Ok');
            return $response;
        }catch (\Exception $e) {
            $this->log[] = 'Confirmation error'. $e->getMessage();
            Log::error('Confirmation error '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Confirmation error '.$e->getMessage()];
        }
    }

    public function confirmationMatch($prebooking)
    {
        try{
            $response = $this->service->confirmationMatch($prebooking['cart']['item']['cart_item_data']['match_id']);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->log[] = 'Confirmation match error'. $response['message'];
                Log::error('Confirmation match error ' . $response['message']);
                return ['status' => 'fail', 'message' => 'Confirmation match error ' . $response['message']];
            }
            $this->line('Confirmation match Ok');
            return $response;
        }catch (\Exception $e) {
            $this->log[] = 'Confirmation match error'. $e->getMessage();
            Log::error('Confirmation match error '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Confirmation match error '.$e->getMessage()];
        }
    }
}
