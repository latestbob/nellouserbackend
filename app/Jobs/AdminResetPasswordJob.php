<?php

namespace App\Jobs;

use App\Mail\AdminResetPasswordMailer;
use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AdminResetPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public $timeout = 60;

    private $admin;

    private $password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Admin $admin, string $password)
    {
        $this->admin = $admin;
        $this->password = $password;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->admin->email)->queue(new AdminResetPasswordMailer($this->admin, $this->password));
    }
}
