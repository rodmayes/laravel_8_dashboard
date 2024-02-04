<?php

namespace App\Http\Livewire\Playtomic\Booking;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\Booking;
use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;
    use Actions;

    public $perPage = 10;
    public $perClub = -1;
    public $orderable;
    public $search = '';
    public $selected = [];
    public $selected_booking;
    public $paginationOptions;

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
        'perPage',
        'perClub'
    ];

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function mount()
    {
        $this->selected_booking  = new Booking(); $this->selected_booking->id = 0;
        $this->sortBy            = 'started_at';
        $this->sortDirection     = 'desc';
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new User())->orderable;
    }

    public function render()
    {
        $query = Booking::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ])->byUser(Auth::user()->id)
            ->when((int)$this->perClub > -1, function($q){
            return $q->byClub($this->perClub);
        });

        if(!isset($this->selected_booking->id)){ $this->selected_booking = new Booking(); $this->selected_booking->id = 0; }
        $clubs = Club::all();
        $allBookings = $query->get();
        $bookings = $query->paginate($this->perPage);

        return view('livewire.playtomic.booking.index', compact('query', 'bookings', 'clubs', 'allBookings'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Booking::whereIn('id', $this->selected)->delete();
        $this->resetSelected();
    }

    public function confirmDelete(Booking $booking)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // use a full syntax
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'question',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'delete',
                'params' => $booking,
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render',
            ],
        ]);
    }

    public function delete(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('playtomic.bookings.index');
    }

    public function setClosed(Booking $booking){
        $booking->setStatusClosed();
    }

    public function setOnTime(Booking $booking){
        $booking->setStatusOnTime();
    }

    public function setTimeOut(Booking $booking){
        $booking->setStatusTimeOut();
    }

    public function truncateResources(){
        try{
            DB::table('playtomic_booking')->truncate();
            $this->notification()->success('Action','Bookings truncated!');
        }catch (\Exception $e){
            $this->notification()->error('Error !!!', $e->getMessage());
        }
    }

    public function showItem(Booking $booking){
        $this->selected_booking = $booking;
    }
}
