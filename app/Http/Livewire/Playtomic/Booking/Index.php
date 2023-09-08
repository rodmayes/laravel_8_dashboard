<?php

namespace App\Http\Livewire\Playtomic\Booking;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\Booking;
use App\Models\Club;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    public $perPage;
    public $perClub;
    public $orderable;
    public $search = '';
    public $selected = [];
    public $paginationOptions;

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
        $this->sortBy            = 'started_at';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->perClub           = -1;
        $this->paginationOptions = config('project.pagination.options');
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

        $clubs = Club::all();
        $bookings = $query->paginate($this->perPage);

        return view('livewire.playtomic.booking.index', compact('query', 'bookings', 'clubs'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Booking::whereIn('id', $this->selected)->delete();
        $this->resetSelected();
    }

    public function delete(Booking $booking)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $booking->delete();
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
        DB::table('playtomic_booking')->truncate();
        $this->addError('action', 'Truncated');
    }
}
