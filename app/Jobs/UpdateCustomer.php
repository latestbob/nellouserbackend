<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\GuzzleClient;
use App\Models\Vendor;

class UpdateCustomer implements ShouldQueue
{
    use GuzzleClient;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $vendor = Vendor::find($this->data['vendor_id']);
        $frags = explode('-', $this->data['dob']);
        $this->data['year'] = $frags[0];
        $this->data['month'] = $frags[1];
        $this->data['year'] = $frags[2];
        $this->httpPost($vendor, '/nello/profile/update', $this->data);
    }
}
