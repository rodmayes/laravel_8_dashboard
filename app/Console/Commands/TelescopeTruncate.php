<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TelescopeTruncate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telescope:truncate-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate data of Telescope';

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
     * @return void|null
     */
    public function handle()
    {
        $this->displayMessage('Begin truncate telescope', 'info');

        try{
            DB::select(DB::raw('TRUNCATE TABLE rodmayes.telescope_entries_tags;'));
            DB::select(DB::raw('SET FOREIGN_KEY_CHECKS = 0;'));
            DB::select(DB::raw('TRUNCATE TABLE rodmayes.telescope_entries;'));
            DB::select(DB::raw('SET FOREIGN_KEY_CHECKS = 1;'));

            $this->displayMessage('End truncate', 'info');
        }catch(\Exception $e){
            $this->displayMessage('Error truncate Telescope: '.$e->getMessage());
        }
    }

    public function displayMessage($message, $type = 'error', $detail_log = []){
        $this->log[] = $message;
        if($type === 'error') {
            $this->error($message);
            Log::error($message, $detail_log);
        }else {
            $this->line($message);
            Log::info($message, $detail_log);
        }
    }
}
