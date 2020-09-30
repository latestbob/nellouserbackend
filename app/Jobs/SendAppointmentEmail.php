<?php

namespace App\Jobs;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAppointmentEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $appointment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->appointment->load(['user', 'center']);
        $html = view('admin-appointment', [
            'appointment' => $this->appointment
        ]);

        Mail::send([], [], function($message) use ($html) {
            $message->to('hello@asknello.com');
            $message->cc('anu.bena@famacare.com');
            $message->setBody($html,'text/html');
            $message->subject('New Appointment Booking');
        });
    }
}
