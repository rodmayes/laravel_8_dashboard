<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\User;
use App\Services\Playtomic\PlaytomicBookingServiceOld;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PlaytomicBookingWithAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playtomic:booking-with-availability {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realiza reservas automáticas para un usuario en función de su disponibilidad';

    protected $bookingService;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
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

        $bookings = Booking::ontime()
            ->byPlayer($user->email)
            ->orderByDesc('started_at')
            ->get();

        $bookingService = new PlaytomicBookingServiceOld($user);
        $bookingService->processBookingsForUser($bookings);

        $this->info('✅ Proceso finalizado');
    }
}
