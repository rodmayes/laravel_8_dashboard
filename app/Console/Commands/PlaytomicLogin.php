<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Playtomic\PlaytomicHttpService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PlaytomicLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playtomic:login {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Login to PLaytomic and set Token to database';
    private $service;
    private $user;
    protected $bookingService;


    /**
     * @return void|null
     */
    public function handle(): void
    {
        $this->user = User::byEmail($this->argument('user'))->first();
        if(!$this->user){
            $this->displayMessage('No user found');
        }

        try {
            $this->bookingService = new PlaytomicHttpService($this->user);
            $this->displayMessage('Login attempt', 'info');
            $login_response = $this->bookingService->login();
            if (!$login_response) {
                $this->displayMessage('NOT Logged');
            }
            $this->displayMessage('Logged', 'info', $login_response);
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }
    }

    public function displayMessage($message, $type = 'error', $detail_log = []): void
    {
        if($type === 'error') {
            $this->error($message);
            Log::error($message, $detail_log);
        }else {
            $this->line($message);
            Log::info($message, $detail_log);
        }
    }
}
