<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlaytomicBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    protected $booking;
    protected $resource;
    protected $timetable;
    protected $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, $resource, $timetable, $url)
    {
        $this->booking = $booking;
        $this->resource = $resource;
        $this->timetable = $timetable;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS', 'rodmayes@gmail.com'))
            ->markdown('emails.playtomic.booking-confirmation', ['booking' => $this->booking, 'resource' => $this->resource, 'timetable' => $this->timetable, 'url' => $this->url]);
    }
}
