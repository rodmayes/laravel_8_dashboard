<?php

namespace App\Console\Commands;

use App\Models\Availibility;
use App\Models\Club;
use App\Models\Slot;
use App\Models\User;
use App\Services\PlaytomicHttpService;
use Illuminate\Console\Command;

class PlaytomicAvailabilityBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playtomic:avalibility {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $clubs = Club::all();
        foreach($clubs as $club){
            $this->user = User::byEmail($this->argument('user'))->first();
            if(!$this->user) return $this->error('No user found');

            $service = new PlaytomicHttpService($this->user);
            $information_club = $service->getInformationClub($club);
            $availibility = $service->getAvailabilityClub($information_club);
            foreach ($availibility as $data){
                if($item = Availibility::byResource($data['resource_id'])->byStartDate($data['start_date'])->first()) {
                    Slot::where('availibility_id', $item->id)->forceDelete();
                    $item->forceDelete();
                }

                $availibility = Availibility::create([
                    'playtomic_resource_id' => $data['resource_id'],
                    'start_date' => $data['start_date']
                ]);

                foreach($data['slots'] as $slot) {
                    Slot::create([
                        'availibility_id' => $availibility->id,
                        'start_time' => $slot['start_time'],
                        'duration' => $slot['duration'],
                        'price' => $slot['price']

                    ]);
                }
            }
        }
    }
}
