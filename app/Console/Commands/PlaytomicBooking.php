<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\User;
use App\Services\Playtomic\PlaytomicBookingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PlaytomicBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playtomic:booking-on-date {user : Email del usuario}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta las reservas Playtomic cuando llega la fecha deseada';

    /**
     * Ejecuta el comando.
     */
    public function handle(): void
    {
        $email = $this->argument('user');

        $this->line('⏳ Iniciando proceso de reserva para: ' . $email);

        $user = User::byEmail($email)->first();

        if (!$user) {
            Log::warning("[Abort] No user found for email: {$email}");
            $this->error('❌ Usuario no encontrado');
            return;
        }

        $bookings = Booking::onDate()
            ->byPlayer($user->email)
            ->orderByDesc('started_at')
            ->get();

        if ($bookings->isEmpty()) {
            $this->warn("⚠ No hay reservas pendientes para {$email}");
            return;
        }

        $bookingService = new PlaytomicBookingService($user);
        $bookingService->processBookingsForUser($bookings);

        $this->info('✅ Proceso de reservas completado');
    }
}
