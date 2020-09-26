<?php

namespace App\Jobs;

use App\Mail\ForgotPasswordMailer;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ForgotPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public $timeout = 60;

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * ForgotPasswordJob constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle()
    {
        $code = random_int(100000, 999999);
        $code = "$code";

        $tokens = PasswordReset::where(['email' => $this->user->email, 'account_type' => 'user']);

        if ($tokens) {
            $tokens->delete();
        }

        $token = PasswordReset::create(['email' => $this->user->email, 'account_type' => 'user', 'token' => $code]);

        if ($token) {
            Mail::to($this->user->email)->queue(new ForgotPasswordMailer($this->user, $code));
        }
    }
}
