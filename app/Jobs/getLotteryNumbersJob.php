<?php

namespace App\Jobs;

use App\Http\Controllers\Lottery\LotteryController;
use App\Mail\sendLotteryNumbersMailable;
use App\Models\LotteryResults;
use App\Services\LotteryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class getLotteryNumbersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    // Establece un límite de tiempo de ejecución alto
    public $timeout = 0; // Sin límite de tiempo
    // Establece un límite de memoria alto (en megabytes)
    public $memory = 1024; // Puedes ajustar esto según tus necesidades

    public function handle()
    {
        $service = new LotteryService();
        $combinations = $service->getNumbersCombinations();

        if (count($combinations) > 0) {
            $combinations = array_slice($combinations, 0,2);
            foreach ($combinations as $combination) {
                sort($combination);
                Log::info('Números seleccionados: ' . implode(', ', $combination));
                echo "Resultado \n\r" . implode(', ', $combination);
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
