<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;
use GuzzleHttp\Psr7; 
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\GuzzleClient;


class ExportUser implements ShouldQueue
{
    use GuzzleClient;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $action;
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, string $action)
    {
        $this->user = $user;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            
            if ($this->action == 'create') {
                //echo 'sending post request';
                $this->user->load('vendor');
                $response = $this->httpPost($this->user->vendor, '/api/import/users', $this->user->toArray());
                //echo $response->getBody();
                $this->user->local_saved = true;
                $this->user->save();
            }
            if ($this->action == 'update') {
                //echo 'sending put request';
                $this->user->load('vendor');
                $response = $this->httpPut($this->user->vendor, '/api/import/users', $this->user->toArray());
                //echo $response->getBody();
                $this->user->local_saved = true;
                $this->user->save();
            }
            if ($this->action == 'delete') {
                //echo 'sending delete request';
                $this->user->load('vendor');
                $response = $this->httpDelete($this->user->vendor, '/api/import/users', [
                    'uuid' => $this->user->uuid
                ]);
                //echo $response->getBody();
            }
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
            }
        }
    }
}
