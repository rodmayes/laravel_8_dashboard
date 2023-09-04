<?php

namespace App\Http\Livewire\Playtomic\Booking;

use App\Mail\PlaytomicBookingConfirmation;
use App\Models\Booking;
use App\Models\Club;
use App\Models\Resource;
use App\Models\Timetable;
use App\Services\PlaytomicHttpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class PreBooking extends Component
{
    public $booking;
    public $prebooking;
    private $playtomic_url_checkout;
    public $url_prebooking;
    public $url_checkout;
    public $execution_response;
    private $service;

    public $listsForFields = [];

    public function updatedClubId(){
        //$this->initListsForFields();
    }

    public function mount(Booking $booking)
    {
        $this->playtomic_url_checkout = env('PLAYTOMIC_URL_CHECKOUT','https://playtomic.io/checkout/booking');
        $this->booking = $booking;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.playtomic.booking.pre-booking');
    }

    public function generate()
    {
        $this->validate();
        try{
            $this->execution_response = null;
            $this->url_checkout = null;
            $this->url_prebooking = null;
            $this->booking->created_by = Auth::user()->id;
            $this->booking->public = $this->booking->public ?? false;
            $this->booking->name = $this->booking->club->name.' '.$this->booking->resource->name.' '.$this->booking->start_at;
            $this->booking->status = 'on-time';

            $this->url_prebooking = [
                'name' => 'Resource ' . $this->booking->resource->name . ' ' . $this->booking->timetable->name,
                'url' => $this->playtomic_url_checkout . "?s=" . $this->booking->club->playtomic_id . "~" . $this->booking->resource->playtomic_id . "~" . $this->booking->started_at->format('Y-m-d') . $this->booking->timetable->playtomic_id . "~90"
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->addError('action', 'Dramatic error you will die. '. $e->getMessage());
        }
    }

    public function preBooking(Timetable $timetable = null)
    {
        if(!$this->service) $this->service = new PlaytomicHttpService(Auth::user());
        $selected_timetable = $timetable ?: $this->booking->timetable;
        $this->addError('action','Logged');
        $this->execution_response .= 'Logged ';
        $this->url_checkout = null;
        $this->prebooking = null;
        if($this->service->login()){
            try{
                $this->execution_response .= ' - Prebooking '.$selected_timetable->name.' ';
                $this->prebooking = $this->service->preBooking($this->booking, $selected_timetable);
                Log::info($selected_timetable->name, $this->prebooking);
                if(isset($this->prebooking['status']) && $this->prebooking['status'] === 'fail') {
                    $this->addError('action', $this->prebooking['message']);
                    throw new \Exception($this->prebooking['message'] . ' ' . 'Resource ' . $this->booking->resource->name . ' ' . $selected_timetable->name);
                }else $this->url_checkout = [
                    'name' => 'Resource ' . $this->booking->resource->name . ' ' . $selected_timetable->name,
                    'url' => $this->playtomic_url_checkout . "?s=" . $this->booking->club->playtomic_id . "~" . $this->booking->resource->playtomic_id . "~" . $this->booking->started_at->format('Y-m-d') . $selected_timetable->playtomic_id . "~90"."~".$this->prebooking['payment_intent_id']
                ];
                $this->execution_response .= ' - Prebooking finish ';
                return true;
            }catch(\Exception $e){
                Log::error('prebooking '.$e->getMessage());
                $this->addError('action', $e->getMessage());
            }
        }else $this->addError('action','No logged!');
        return false;
    }

    public function paymentMethodSelection(){
        try{
            if(!$this->service) $this->service = new PlaytomicHttpService(Auth::user());
            $this->execution_response .= ' - Payment method selection ';
            $this->prebooking = $this->service->paymentMethodSelection($this->prebooking["payment_intent_id"]);
            Log::info('Payment method ', $this->prebooking);
            return true;
        }catch(\Exception $e){
            Log::error('Payment method '.$e->getMessage());
            $this->addError('action', $e->getMessage());
            $this->execution_response .= 'Error: '.$e->getMessage().' ';
            return false;
        }
    }

    public function confirmation()
    {
        try{
            if (!$this->service) $this->service = new PlaytomicHttpService(Auth::user());
            $this->execution_response .= ' - Confirmation init ';
            $this->prebooking = $this->service->confirmation($this->prebooking['payment_intent_id']);
            Log::info('Confirmation ', $this->prebooking);
            return true;
        }catch (\Exception $e) {
            Log::error('confirmation'.$e->getMessage());
            $this->addError('action', $e->getMessage());
            $this->execution_response .= 'Error: ' . $e->getMessage() . ' ';
            return false;
        }
    }

    public function booking(){
        $this->execution_response = '';
        $timetable_id = $this->booking->timetable_id;
        $timetable = Timetable::find($timetable_id);
        $url = $this->makeBooking();
        if(!isset($url['error'])) return $this->sendEmail($url);
        $this->addError('action', $url['error']);
        $url = $this->makeBooking(Timetable::before($timetable)->first());
        if(!isset($url['error'])) return $this->sendEmail($url);
        $this->addError('action', $url['error']);
        $url = $this->makeBooking(Timetable::after($timetable)->first());
        if(!isset($url['error'])) return $this->sendEmail($url);
        $this->addError('action', $url['error']);
    }

    protected function makeBooking(Timetable $timetable = null){
        if(!$this->preBooking($timetable)) return ['error' => 'Prebooking error'];
        if(!$this->paymentMethodSelection()) return ['error' => 'Payment method selection error'];
        if(!$this->confirmation()) return ['error' => 'Confirmation error'];
        $this->execution_response .= 'Confirmation do it!!!!';
        return $this->service->confirmationMatch($this->prebooking['cart']['item']['cart_item_data']['match_id']);
    }

    protected function sendEmail($url){
        Mail::to(Auth::user())->send(new PlaytomicBookingConfirmation($this->booking, $url));
        session()->flash('booking-action', 'Booking do it!! :)');
        $this->booking->created_by = Auth::user()->id;
        $this->booking->public = $this->booking->public ?? false;
        $this->booking->name = $this->booking->club->name.' '.$this->booking->resource->name.' '.$this->booking->start_at;
        $this->status = 'closed';
        $this->booking->log = 'Booking on Resource ' . $this->booking->resource->name . ' ' . $this->booking->timetable->name;
        $this->booking->save();
    }

    protected function rules(): array
    {
        return [
            'booking.started_at' => [
                'required',
                //'date_format:' . config('project.date_format'),
            ],
            'booking.timetable_id' => [
                'integer',
                'exists:playtomic_timetable,id',
                'required',
            ],
            'booking.club_id' => [
                'integer',
                'exists:playtomic_club,id',
                'required',
            ],
            'booking.resource_id' => [
                'integer',
                'exists:playtomic_resource,id',
                'required',
            ],
            'booking.public' => [
                'nullable'
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        /*
          when((int)$this->booking->resource_id > -1, function($q){
                return $q->byClub($this->booking->club_id);
            })->get()->map(function ($item) {
                return ['name' => $item->name.'-'.$item->club->name, 'id' => $item->id, 'club' => $item->club->name];
            })->pluck('name','id');
         */
        $this->listsForFields['club'] = Club::pluck('name','id');
        $this->listsForFields['resource'] = Resource::get()->map(function ($item) {
                return ['name' => $item->name.'-'.$item->club->name, 'id' => $item->id, 'club' => $item->club->name];
            })->pluck('name','id');
        $this->listsForFields['timetable'] = Timetable::pluck('name','id');
    }
}
