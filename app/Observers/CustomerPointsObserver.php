<?php

namespace App\Observers;

use App\Jobs\SendPointMail;
use App\Models\CustomerPoint;

class CustomerPointsObserver
{

    public function creating(CustomerPoint $customerPoints)
    {
    }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\CustomerPoint  $customerPoints
     * @return void
     */
    public function created(CustomerPoint $customerPoints)
    {
        SendPointMail::dispatch($customerPoints);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\CustomerPoint  $customerPoints
     * @return void
     */
    public function updated(CustomerPoint $customerPoints)
    {
        SendPointMail::dispatch($customerPoints);
    }

    /**
     * Handle the user "saved" event.
     *
     * @param  \App\Models\CustomerPoint  $customerPoints
     * @return void
     */
    public function saved(CustomerPoint $customerPoints)
    {
//        SendPointMail::dispatch($customerPoints);
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\CustomerPoint  $customerPoints
     * @return void
     */
    public function deleted(CustomerPoint $customerPoints)
    {
    }
    /**
     * Handle the user "restored" event.
     *
     * @param  \App\CustomerPoints  $customerPoints
     * @return void
     */
    public function restored(CustomerPoint $customerPoints)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\CustomerPoints  $customerPoints
     * @return void
     */
    public function forceDeleted(CustomerPoint $customerPoints)
    {
        //
    }
}
