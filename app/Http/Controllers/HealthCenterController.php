<?php

namespace App\Http\Controllers;

use App\Models\HealthCenter;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use App\Traits\GuzzleClient;
use DB;

class HealthCenterController extends Controller
{

    use GuzzleClient;


    /**
     * Health centers
     *
     * Fetch paged list of health centers
     *
     * @urlParam page int optional defaults to 1
     */
    public function index(Request $request) {
        //->paginate();
        return HealthCenter::orderBy('name')->where('is_active', true)->get();

        /**DO NOT DELETE */
        $user = $request->user();
        $user->load('vendor');

        try {

            $response = $this->httpGet($user->vendor, '/api/health-centers', []);

            return $response->getBody();
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
                //$str = json_encode($e, true);
            }
            return response([
                'msg' => 'Error while fetching pending appointment.'
            ], 400);

        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while fetching pending appointment.'
            ], 400);
        }

        return response([
            'msg' => 'Error while fetching pending appointment.'
        ], 400);
    }

    //get medical center

    public function fetchMedicalCenter(Request $request){
        $center = HealthCenter::where(['is_active' => true])
        //            ->whereNotNull('hospital')
                  ->when($request->specialization, function($query, $spec){
                        $query->where('center_type', 'LIKE', "%{$spec}%");
                    })->paginate(30);

                    return $center;
        
    }
}
