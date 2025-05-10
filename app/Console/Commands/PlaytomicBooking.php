<?php

namespace App\Console\Commands;

use App\Jobs\LaunchPrebookingJob;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use App\Services\Playtomic\PlaytomicBookingService;
use App\Services\Playtomic\PlaytomicHttpService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        if (!$bookings) {
            $this->warn("⚠ No hay reservas pendientes para {$email}");
            return;
        }

        if($this->loginPlaytomic($user)) {
            foreach ($bookings as $booking) {
                $this->enqueuePrebookingJobs($booking, $user);
            }
        }


        $this->info('✅ Proceso de reservas completado');
    }

    private function loginPlaytomic($user): bool
    {
        try {
            $this->displayMessage('Login attempt', 'info');
            $bookingService = new PlaytomicHttpService($user->id);
            $login_response = $bookingService->login();
            if (!$login_response) {
                $this->displayMessage('NOT Logged');
                return false;
            }
            $this->displayMessage('Logged', 'info', $login_response);
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }
        return true;
    }

    protected function enqueuePrebookingJobs(Booking $booking, $user): void
    {
        $timetables = Timetable::whereIn('id', explode(",", $booking->timetables))
            ->orderByRaw(DB::raw("FIELD(id, {$booking->timetables})"))
            ->get();

        $resources = Resource::whereIn('id', explode(",", $booking->resources))
            ->orderByRaw(DB::raw("FIELD(id, {$booking->resources})"))
            ->get();

        $pref = $booking->booking_preference;
        $primaryItems = $pref === 'timetable' ? $timetables : $resources;
        $secondaryItems = $pref === 'timetable' ? $resources : $timetables;

        // Log reset
        $booking->log = null; $booking->save();
        foreach ($primaryItems as $p1) {
            foreach ($secondaryItems as $p2) {
                [$resource, $timetable] = $pref === 'timetable' ? [$p2, $p1] : [$p1, $p2];

                $this->info('Create Job');
                LaunchPrebookingJob::dispatch(
                    $user->id,
                    $booking->id,
                    $resource->id,
                    $timetable->id
                );
            }
        }
    }

    private function displayMessage($message, $type = 'error', $detail_log = []): void
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
