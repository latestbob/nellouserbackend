<?php

namespace App\Observers;

use App\Jobs\ExportAppointment;
use App\Models\Appointment;
use Illuminate\Support\Str;

class AppointmentObserver
{

    public function creating(Appointment $appointment)
    {
        if (empty($appointment->uuid)) {
            
            $appointment->uuid = Str::uuid()->toString();
        }
    }


    /**
     * Handle the appointment "created" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function created(Appointment $appointment)
    {
        ExportAppointment::dispatch($appointment, 'create');
    }

    /**
     * Handle the appointment "updated" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function updated(Appointment $appointment)
    {
        ExportAppointment::dispatch($appointment, 'update');
    }

    /**
     * Handle the appointment "deleted" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function deleted(Appointment $appointment)
    {
        ExportAppointment::dispatch($appointment, 'delete');
    }

    /**
     * Handle the appointment "restored" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function restored(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the appointment "force deleted" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function forceDeleted(Appointment $appointment)
    {
        //
    }
}
