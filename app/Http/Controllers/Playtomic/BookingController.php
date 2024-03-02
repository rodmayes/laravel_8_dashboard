<?php

namespace App\Http\Controllers\Playtomic;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingCalendarResource;
use App\Mail\PlaytomicBookingConfirmation;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use App\Services\PlaytomicHttpService;
use Gate;
use Illuminate\Http\Response;
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
        abort_if(Gate::denies('playtomic.bookings_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.index');
    }

    public function viewCalendar()
    {
        abort_if(Gate::denies('playtomic.bookings_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $bookings = BookingCalendarResource::collection(Booking::all())->resolve(); //notCLosed()->get()();
        return view('livewire.playtomic.booking.playtomic-calendar', compact('bookings'));
    }

    public function create($start_date = null)
    {
        abort_if(Gate::denies('playtomic.bookings_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.create', compact('start_date'));
    }

    public function edit(Booking $booking)
    {
        abort_if(Gate::denies('playtomic.bookings_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.edit', compact('booking'));
    }

    public function show(Booking $booking)
    {
        abort_if(Gate::denies('playtomic.bookings_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.show', compact('booking'));
    }

    public function generateLinks(Booking $booking)
    {
        abort_if(Gate::denies('playtomic.bookings_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.generate-links', compact('booking'));
    }

    public function prebooking(){
        abort_if(Gate::denies('playtomic.bookings_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.booking.pre-booking');
    }

    public function startBooking(Booking $booking, Resource $resource, Timetable $timetable, User $user = null){
        $this->log = ['status'=> ['success' => ''], 'data' => [] ];
        $this->user = $user ?: Auth::user();
        if(!$this->user) {
            Log::error('No user found');
            return $this->log['status'] = ['fail' => 'No user found'];
        }
        $this->service = new PlaytomicHttpService($this->user);

        try{
            if(!$user) {
                $this->log['data'][] = 'Login attempt';
                $login_response = $this->service->login();
                if (!$login_response) {
                    Log::error('Not logged');
                    return $this->log['status'] = ['fail' => 'Not logged'];
                }
                $this->log['data'][] = 'Logged';
            }
            $this->log['data'][] = 'Booking start: ' . $booking->club->name . ' ' . $resource->name.' '.$booking->started_at->format('d-m-Y') . ' ' . $timetable->name;
            $prebooking = $this->booking($booking,  $resource, $timetable);
            $this->log['data'][] = 'Booking scheduled finish';
            if(isset($prebooking['error'])) throw new \Exception('Prebooking error '.$prebooking['error']);
            $booking->name = $booking->club->name . ' ' . $resource->name.' '.$booking->started_at->format('d-m-Y') . ' ' . $timetable->name;
            //$booking->resources = $resource->id;
            //$booking->timetables = $timetable->id;
            $booking->created_by = $this->user->id;
            $booking->log = json_encode($this->log);
            $booking->save();
        }catch(\Exception $e){
            $this->log['status'] = ['fail' => $e->getMessage()];
            $this->log['data'][] = $e->getMessage();
            Log::error($e->getMessage(), $this->log);
        }
        return $this->log;
    }

    public function booking(Booking $booking, Resource  $resource, Timetable $timetable){
        $this->log['data'][] = 'Prebooking '.$timetable->name;
        $prebooking = $this->getPreBooking($booking, $resource, $timetable);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Prebooking error '.$prebooking['message']];
        $this->log['data'][] = 'Payment method '.$timetable->name;
        $prebooking = $this->paymentMethodSelection($prebooking);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Payment method selection error '.$prebooking['message']];
        $this->log['data'][] = 'Confirmation '.$timetable->name;
        $prebooking = $this->confirmation($prebooking);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Confirmation error '.$prebooking['message']];
        $this->log['data'][] = 'Confirmation Match '.$timetable->name;
        $prebooking = $this->confirmationMatch($prebooking);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Confirmation match error '.$prebooking['message']];
        $this->log['data'][] = '  Booking end: ' . $booking->name . ' ' . $resource->name.' '.$booking->started_at->format('d-m-Y') . ' ' . $timetable->name . ' Do it!';
        if (!isset($prebooking['error'])) {
            Mail::to($this->user)->send(new PlaytomicBookingConfirmation($booking, $resource, $timetable, $prebooking));
            $this->log['data'][] = 'Mail sent to ' . $this->user->email;
        }
        return $prebooking;
    }

    public function getPreBooking(Booking $booking, Resource $resource, Timetable $timetable)
    {
        if($this->service->login()){
            try{
                $response = $this->service->preBooking($booking, $resource, $timetable);
                if(isset($response['status']) && $response['status'] === 'fail') {
                    $this->log['data'][] = 'Prebooking error: '. $response['message'];
                    Log::error('Prebooking error: '.$timetable->name.' '.$response['message']);
                    return ['status' => 'fail', 'message' => 'Prebooking error: '.$timetable->name.' '.$response['message']];
                }
                $this->log['data'][] = 'Prebooking Ok';
                return $response;
            }catch(\Exception $e){
                $this->log['data'][] = 'Prebooking error: '. $timetable->name.' '.$e->getMessage();
                Log::error('Prebooking error: '.$timetable->name.' '.$e->getMessage());
                return ['status' => 'fail', 'message' => 'Prebooking error: '.$timetable->name.' '.$e->getMessage()];
            }
        }else return ['status' => 'fail', 'message' => 'No logged'];
    }

    public function paymentMethodSelection($prebooking)
    {
        try{
            $response = $this->service->paymentMethodSelection($prebooking);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->log['data'][] = 'Payment method error '. $response['message'];
                Log::error('Payment method error '.$response['message']);
                return ['status' => 'fail', 'message' => 'Payment method error ' . $response['message']];
            }
            $this->log['data'][] = 'Payment method Ok';
            return $response;
        }catch(\Exception $e){
            $this->log['data'][] = 'Payment method error '. $e->getMessage();
            Log::error('Payment method error '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Payment method error '.$e->getMessage()];
        }
    }

    public function confirmation($prebooking)
    {
        try{
            $response = $this->service->confirmation($prebooking['payment_intent_id']);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->log['data'][] = 'Confirmation error'. $response['message'];
                Log::error('Confirmation error ' . $response['message']);
                return ['status' => 'fail', 'message' => 'Confirmation error ' . $response['message']];
            }
            $this->log['data'][] = 'Confirmation Ok';
            return $response;
        }catch (\Exception $e) {
            $this->log['data'][] = 'Confirmation error'. $e->getMessage();
            Log::error('Confirmation error '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Confirmation error '.$e->getMessage()];
        }
    }

    public function confirmationMatch($prebooking)
    {
        try{
            $response = $this->service->confirmationMatch($prebooking['cart']['item']['cart_item_data']['match_id']);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->log['data'][] = 'Confirmation match error'. $response['message'];
                Log::error('Confirmation match error ' . $response['message']);
                return ['status' => 'fail', 'message' => 'Confirmation match error ' . $response['message']];
            }
            $this->log['data'][] = 'Confirmation match Ok';
            return $response;
        }catch (\Exception $e) {
            $this->log['data'][] = 'Confirmation match error'. $e->getMessage();
            Log::error('Confirmation match error '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Confirmation match error '.$e->getMessage()];
        }
    }
}
