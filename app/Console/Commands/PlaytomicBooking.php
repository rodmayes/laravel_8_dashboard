<?php

namespace App\Console\Commands;

use App\Mail\PlaytomicBookingConfirmation;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use App\Services\Playtomic\PlaytomicBookingService;
use App\Services\Playtomic\PlaytomicHttpService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PlaytomicBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playtomic:booking-on-date {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Booking opens onDate';

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
    public function handle(): void
    {
        $email = $this->argument('user');

        $this->line('⏳ Iniciando proceso de reserva para: ' . $email);

        $user = User::byEmail($email)->first();
        if (!$user) {
            Log::warning("[Abort] No user found for email: {$email}");
            $this->line('No user found');
            return;
        }

        $bookings = Booking::onDate()
            ->byPlayer($user->email)
            ->orderByDesc('started_at')
            ->get();

        $bookingService = new PlaytomicBookingService($user);
        $bookingService->processBookingsForUser($bookings);

        $this->info('✅ Proceso finalizado');
    }
}
