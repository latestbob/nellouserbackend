<?php

namespace App\Observers;

use App\Models\User;
use App\Jobs\ExportUser;
use Illuminate\Support\Str;

class UserObserver
{
    
    public function creating(User $user)
    {
        if (empty($user->uuid)) {
            $user->uuid = Str::uuid()->toString();
        }
    }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if (!$user->local_saved) {
            ExportUser::dispatch($user, 'create');
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if (!$user->local_saved) {
            ExportUser::dispatch($user, 'update');
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        ExportUser::dispatch($user, 'delete');
    }
    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
