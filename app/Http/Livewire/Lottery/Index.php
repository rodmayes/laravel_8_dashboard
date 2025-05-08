<?php

namespace App\Http\Livewire\Lottery;

use App\Jobs\getLotteryNumbersJob;
use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\LotteryResults;
use App\Services\LotteryService;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;
    use Actions;
    use WithFileUploads;

    public $perPage;
    public $orderable;
    public $search = '';
    public $selected = [];
    public $paginationOptions;
    public $excel_file = null;
    public $magik_numbers = [];
    public $numbers = [];

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

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function search(): void
    {
        $this->resetPage();
    }

    public function resetSelected(): void
    {
        $this->selected = [];
    }

    public function mount()
    {
        $this->sortBy            = 'date_at';
        $this->sortDirection     = 'desc';
        $this->perPage           = 50;
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new LotteryResults)->orderable;
    }

    //#[On('item-updated')]
    public function render()
    {
        $query = LotteryResults::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $results = $query->paginate($this->perPage);
        return view('livewire.lottery.index', compact('query','results'));
    }

    public function confirmImportResults()
    {
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Import results from excel?',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, do it',
                'method' => 'importResults'
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render'
            ],
            'style' => 'center'
        ]);
    }

    public function importResults()
    {
        $service = new LotteryService();
        try{
            $service->importResults($this->excel_file);
            $this->notification()->success('Import', 'Results has been imported successfully');
        }catch(\Exception $e){
            $this->notification()->error('Error ',$e->getMessage());
        }
    }

    public function generateMagikNumbers(){
        try{
            getLotteryNumbersJob::dispatch();
            $this->notification()->success('Generated', 'Job to generate Magik numbers has been dispatched, sent mail with results');
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    public function toggleNumber($number){
        if(count($this->numbers) === 6) $this->notification()->error('Max number 6 selected, unselect first');

        if(in_array($number,$this->numbers)){
            $this->numbers = array_diff($this->numbers,[$number]);
        }else{
            $this->numbers[] = $number;
        }
    }
}
