<?php

namespace App\Http\Controllers\Playtomic;

use App\Http\Controllers\Controller;
use App\Mail\PlaytomicBookingConfirmation;
use App\Models\Booking;
use App\Models\Timetable;
use App\Services\PlaytomicHttpService;
use Gate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    private $service;

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

    public function makeBooking(Booking $booking)
    {
        try {
            $this->service = new PlaytomicHttpService(Auth::user());
            $timetable_id = $booking->timetable_id;
            $timetable = Timetable::find($timetable_id);
            $url = $this->booking($booking);
            if(!isset($url['error'])) {
                Mail::to(Auth::user())->send(new PlaytomicBookingConfirmation($booking, $url));
                return ['status' => 'success'];
            }
            $url = $this->booking($booking, Timetable::before($timetable)->first());
            if(!isset($url['error'])) {
                Mail::to(Auth::user())->send(new PlaytomicBookingConfirmation($booking, $url));
                return ['status' => 'success'];
            }
            $url = $this->booking($booking, Timetable::after($timetable)->first());
            if(!isset($url['error'])) {
                Mail::to(Auth::user())->send(new PlaytomicBookingConfirmation($booking, $url));
                return ['status' => 'success'];
            }
            Log::info('Booking: ' . $booking->name . ' ' . $booking->started_at->format('d-m-Y') . ' ' . $booking->timetable->name . ' Do it!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function booking(Booking  $booking, Timetable $timetable = null){
        $prebooking = $this->bookingService($booking, $timetable);
        if(isset($prebooking['status']) && $prebooking['message'] === 'fail') return ['error' => 'Prebooking error'];
        $prebooking = $this->paymentMethodSelection($prebooking);
        if(isset($prebooking['status']) && $prebooking['message'] === 'fail') return ['error' => 'Payment method selection error'];
        $prebooking = $this->confirmation($prebooking);
        if(isset($prebooking['status']) && $prebooking['message'] === 'fail') return ['error' => 'Confirmation error'];
        return $this->service->confirmationMatch($prebooking['cart']['item']['cart_item_data']['match_id']);
    }

    public function bookingService(Booking $booking, Timetable $timetable = null)
    {
        $selected_timetable = $timetable ?: $booking->timetable;
        if($this->service->login()){
            try{
                $prebooking = $this->service->preBooking($booking, $selected_timetable);
                if(isset($prebooking['status']) && $prebooking['status'] === 'fail')
                    Log::error('prebooking '.$prebooking['message']);
                Log::info($selected_timetable->name, $prebooking);
                return $prebooking;
            }catch(\Exception $e){
                Log::error('prebooking '.$e->getMessage());
            }
        }else return ['status' => 'fail', 'message' => 'No logged'];
    }

    public function paymentMethodSelection($prebooking){
        try{
            $prebooking = $this->service->paymentMethodSelection($prebooking["payment_intent_id"]);
            Log::info('Payment method ', $prebooking);
            return $prebooking;
        }catch(\Exception $e){
            Log::error('Payment method '.$e->getMessage());
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    public function confirmation($prebooking)
    {
        try{
            $prebooking = $this->service->confirmation($prebooking['payment_intent_id']);
            Log::info('Confirmation ', $prebooking);
            return $prebooking;
        }catch (\Exception $e) {
            Log::error('confirmation'.$e->getMessage());
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }
}
