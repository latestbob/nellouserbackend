<?php

namespace App\Jobs;

use App\Mail\AdminForgotPasswordMailer;
use App\Models\Admin;
use App\Models\PasswordReset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminForgotPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public $timeout = 60;

    private $admin;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $code = Str::random(6);

        $tokens = PasswordReset::where(['email' => $this->admin->email]);

        if ($tokens) {
            $tokens->delete();
        }

        $token = PasswordReset::create(['email' => $this->admin->email, 'token' => $code]);

        if ($token) {
            Mail::to($this->admin->email)->queue(new AdminForgotPasswordMailer($this->admin, $code));
        }
    }
}
