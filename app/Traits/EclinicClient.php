<?php

namespace App\Traits;

use App\Models\Vendor;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Psr7\Response;


trait EclinicClient
{

    private $ECLINIC_API = 'https://api.famacare.eclathealthcare.com';


    public function httpPost(string $path, array $data): Response
    {
        $client = new Client([
            'base_uri' => $this->ECLINIC_API,
            'timeout'  => '15.0'
        ]);
        $options = [
            'json' => $data
        ];

        $response = $client->post($path, $options);
        return $response;
    }

    public function httpPut(string $path, array $data): Response
    {
        $client = new Client([
            'base_uri' => $this->ECLINIC_API,
            'timeout'  => '15.0'
        ]);
        $options = [
            'json' => $data
        ];

        $response = $client->put($path, $options);
        return $response;
    }

    public function httpGet(string $path, array $data): Response
    {
        $client = new Client([
            'base_uri' => $this->ECLINIC_API,
            'timeout'  => '2.0'
        ]);
        $options = [
            'query' => $data
        ];

        $response = $client->get($path, $options);
        return $response;        
    }

    public function httpDelete(string $path, array $data): Response
    {
        $client = new Client([
            'base_uri' => $this->ECLINIC_API,
            'timeout'  => '2.0'
        ]);
        $options = [
            'query' => $data
        ];

        $response = $client->delete($path, $options);
        return $response;        
    }
}
