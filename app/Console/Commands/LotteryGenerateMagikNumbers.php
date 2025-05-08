<?php

namespace App\Console\Commands;

use App\Http\Controllers\Lottery\LotteryController;
use App\Mail\sendLotteryNumbersMailable;
use App\Services\LotteryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LotteryGenerateMagikNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lottery:generate-magiknumbers {--en=}';
    // example lottery:generate-magiknumbers --en=3,12,23

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new LotteryService();
        $this->info('Calculating in process');
        $controller->setExcludedNumbers($this->option('en') ? explode(",", $this->option('en')): [1,49]);
        //$controller->setExcludedNumbers([]);
        $combinations =  $controller->getNumbersCombinations();

        if (count($combinations) > 0) {
            foreach ($combinations as $combination) {
                sort($combination);
                $this->info('Números seleccionados: ' . implode(', ', $combination));
            }
            Mail::to('rodmayes@gmail.com')->send(new sendLotteryNumbersMailable($combinations));
            return;
        } else {
            echo "No valid combination found. \n\r";
            Log::error('No valid combination found.');
        }
        echo "Finalizado \n\r";
    }
}
