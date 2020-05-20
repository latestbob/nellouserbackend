<?php

namespace App\Observers;

use App\Jobs\SendPointMail;
use App\Models\CustomerPoints;

class CustomerPointsObserver
{

    public function creating(CustomerPoints $customerPoints)
    {
    }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\CustomerPoints  $customerPoints
     * @return void
     */
    public function created(CustomerPoints $customerPoints)
    {
        SendPointMail::dispatch($customerPoints);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\CustomerPoints  $customerPoints
     * @return void
     */
    public function updated(CustomerPoints $customerPoints)
    {
        SendPointMail::dispatch($customerPoints);
    }

    /**
     * Handle the user "saved" event.
     *
     * @param  \App\Models\CustomerPoints  $customerPoints
     * @return void
     */
    public function saved(CustomerPoints $customerPoints)
    {
//        SendPointMail::dispatch($customerPoints);
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\CustomerPoints  $customerPoints
     * @return void
     */
    public function deleted(CustomerPoints $customerPoints)
    {
    }
    /**
     * Handle the user "restored" event.
     *
     * @param  \App\CustomerPoints  $customerPoints
     * @return void
     */
    public function restored(CustomerPoints $customerPoints)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\CustomerPoints  $customerPoints
     * @return void
     */
    public function forceDeleted(CustomerPoints $customerPoints)
    {
        //
    }
}
