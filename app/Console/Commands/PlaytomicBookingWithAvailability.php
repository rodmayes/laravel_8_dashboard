<?php

namespace App\Console\Commands;

use App\Services\Playtomic\PlaytomicBookingService;
use Illuminate\Console\Command;

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
    public function __construct(PlaytomicBookingService $bookingService)
    {
        parent::__construct();
        $this->bookingService = $bookingService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $email = $this->argument('user');
        $this->line('⏳ Iniciando proceso de reserva para: ' . $email);
        $this->bookingService->processBookingsForUser($email);
        $this->info('✅ Proceso finalizado');
    }
}
