<?php

namespace App\Console\Commands;

use App\Mail\PlaytomicBookingConfirmation;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use App\Services\PlaytomicHttpService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
    private $log;

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
        $this->user = User::byEmail($this->argument('user'))->first();
        if(!$this->user) return $this->displayMessage('No user found');

        $this->service = new PlaytomicHttpService($this->user);
        $this->displayMessage('Login attempt', 'info');
        $login_response = $this->service->login();
        if(!$login_response)
            return $this->displayMessage('NOT Logged');
        $this->displayMessage('Logged', 'info', $login_response);
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
