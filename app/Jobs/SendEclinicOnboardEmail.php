<?php

namespace App\Jobs;

use App\Mail\EclinicOnboardEmail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendEclinicOnboardEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $code = Str::random(6);

        $tokens = PasswordReset::where(['email' => $this->user->email]);

        if ($tokens) {
            $tokens->delete();
        }

        $token = PasswordReset::create(['email' => $this->user->email, 'token' => $code]);

        if ($token) {
            Mail::to($this->user->email)->queue(new EclinicOnboardEmail($this->user, $code));
        }
    }
}
