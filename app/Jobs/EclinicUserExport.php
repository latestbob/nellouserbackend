<?php

namespace App\Jobs;


use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\EclinicClient;
use GuzzleHttp\Psr7; 
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class EclinicUserExport implements ShouldQueue
{
    use EclinicClient;
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
        $data = [
            "patient_id" => $this->user->id,
            "surname" => $this->user->lastname,
            "forename" => $this->user->firstname,
            "gender" => $this->user->gender,
            "dob" => $this->user->dob,
            "middle_name" => $this->user->middlename,
            "occupation" => $this->user->cwork,
            "education" => "",
            "phone" => $this->user->phone,
            "email" => $this->user->email,
            "ethnicity" => "",
            "marital_status" => "",
            "clinic_token" => 'FAMA-LAG-HQ'
        ];

        //try {
            $this->httpPost('/patient-api', $data);
        /*} catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
            }
        } catch(ClientException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
            }
        }*/
    }
}
