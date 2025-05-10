<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use App\Services\Playtomic\PlaytomicHttpService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlaytomicBookingTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playtomic:booking-test {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $service;

    /**
     * Execute the console command.
     *
     * @return int
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
            ->first();

        $this->service = new PlaytomicHttpService($user->id);

        if($bookings && $this->loginPlaytomic($user)) {
            $this->booking($user, $bookings);
        }

        $this->info('✅ Proceso finalizado');
    }

    private function booking(User $user, $booking){
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
        foreach ($primaryItems as $p1) {
            foreach ($secondaryItems as $p2) {
                [$resource, $timetable] = $pref === 'timetable' ? [$p2, $p1] : [$p1, $p2];

                try {
                    $this->info('Prebooking');
                    $response = $this->service->preBooking($booking, $resource, $timetable);

                    if (isset($response['status']) && $response['status'] === 'fail') {
                        $this->error('Prebooking failed: ' . $response['message']);
                        return;
                    }
                } catch (\Throwable $e) {
                    $this->info('prebooking error: ' . $e->getMessage());
                }
            }
        }
    }

    private function loginPlaytomic($user): bool
    {
        try {
            $this->info('Login attempt', 'info');
            $login_response = $this->service->login();
            if (!$login_response) {
                $this->error('NOT Logged');
                return false;
            }
            $this->info('Logged', 'info', $login_response);
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
        return true;
    }
}
