<?php

namespace App\Traits;

use App\Models\Vendor;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Psr7\Response;


trait GuzzleClient
{

    private function getVendorToken(Vendor $vendor)
    {
        $today = Carbon::today()->toDateString();
        $key = $today . '_vendor_token_' . $vendor->api_key;
        if (!Cache::has($key)) {
            $token = $vendor->api_key . $vendor->api_secret . $today;
            $token = bcrypt($token);
            Cache::put($key, $token, 60 * 60 * 24);
        }
        return Cache::get($key);
    }

    public function httpPost(Vendor $vendor, string $path, array $data): Response
    {
        $client = new Client([
            'base_uri' => $vendor->server_url,
            'timeout'  => '15.0'
        ]);
        $options = [
            'headers' => [
                'Authorization' => $this->getVendorToken($vendor)
            ],
            'json' => $data
        ];

        $response = $client->post($path, $options);
        return $response;
    }

    public function httpPut(Vendor $vendor, string $path, array $data): Response
    {
        $client = new Client([
            'base_uri' => $vendor->server_url,
            'timeout'  => '15.0'
        ]);
        $options = [
            'headers' => [
                'Authorization' => $this->getVendorToken($vendor)
            ],
            'json' => $data
        ];

        $response = $client->put($path, $options);
        return $response;
    }

    public function httpGet(Vendor $vendor, string $path, array $data): Response
    {
        $client = new Client([
            'base_uri' => $vendor->server_url,
            'timeout'  => '2.0'
        ]);
        $options = [
            'headers' => [
                'Authorization' => $this->getVendorToken($vendor)
            ],
            'query' => $data
        ];

        $response = $client->get($path, $options);
        return $response;        
    }

    public function httpDelete(Vendor $vendor, string $path, array $data): Response
    {
        $client = new Client([
            'base_uri' => $vendor->server_url,
            'timeout'  => '2.0'
        ]);
        $options = [
            'headers' => [
                'Authorization' => $this->getVendorToken($vendor)
            ],
            'query' => $data
        ];

        $response = $client->delete($path, $options);
        return $response;        
    }
}
