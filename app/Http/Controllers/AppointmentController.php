<?php

namespace App\Http\Controllers;

use App\Jobs\SendAppointmentEmail;
use App\Models\Appointment;
use App\Models\HealthCenter;
use App\Notifications\AppointmentBookedNotification;
use App\Notifications\AppointmentCancelledNotification;
use App\Notifications\AppointmentUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Traits\GuzzleClient;
use Carbon\Carbon;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Mail\AppointmentCustomer;
use App\Mail\AdminOnlineAppointment;
use App\Mail\AdminPhysicalAppointment;
use App\Mail\AppointmentDoctor;
use App\Mail\OWCMail;
use App\Mail\OWCADMIN;
use App\Mail\OWCDOCTOR;
use App\Mail\AppointmentMedical;
use App\Mail\Medreminder;
use App\Mail\Docreminder;
use App\Models\TransactionLog;
use App\Mail\SwitchUser;
use App\Mail\SwitchDoctor;
use Mail;
class AppointmentController extends Controller
{
    use GuzzleClient;

    public function index(Request $request)
    {
        $user = $request->user();
        $appointments = Appointment::whereHas('user', function ($query) use ($user) {
            $query->where('vendor_id', $user->vendor_id);
        })->paginate();
        return $appointments;
    }

    private function find(string $uuid): Appointment
    {
        $appointment = Appointment::where('uuid', $uuid)
            ->where('user_uuid', Auth::user()->uuid)
            ->first();
        return $appointment;
    }


    /**
     * Book appointment
     *
     * @bodyParam medical_center uuid required a health center uuid
     * @bodyParam reason string required the purpose of the appointment
     * @bodyParam date date format yyyy-mm-dd
     * @bodyParam time time format HH:mm
     */
    public function bookAppointment(Request $request)
    {
        $input = $request->all();

        if (isset($input['time'])) $input['time'] = date('Y-m-d H:i', strtotime("{$input['date']} {$input['time']}"));

        $data = $request->validate([
            'reason' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'date' => 'required',
            //'time' => 'required|date_format:Y-m-d H:i|after:' . date('Y-m-d H:i', strtotime("+30 minutes")),
            'time' => [
                'required',
                'date_format:H:i:s',
                function ($attr, $value, $fail) use($request) {
                    $frags1 = explode('-', $request->date);
                    $frags = explode(':', $value);
                    $now = Carbon::now()->addMinutes(30);
                    $time = Carbon::now();
                    $time->setDate($frags1[0], $frags1[1], $frags1[2])
                        ->setHour($frags[0])
                        ->setMinute($frags[1])
                        ->setSecond(0);

                    // if ($now->gte($time)) {
                    //     $fail("Time must be at least 30 minutes after the current time.");
                    // }
                }
            ],
            'medical_center' => [
                Rule::requiredIf(empty($request->doctor_id)),
                'exists:health_centers,uuid'
            ],
            'doctor_id' => [
                Rule::requiredIf(empty($request->medical_center)),
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('user_type', 'doctor');
                })
            ]
        ]);

        $user = $request->user();

        //if (isset($data['time'])) $data['time'] = \DateTime::createFromFormat(
        //    "Y-m-d H:i", $data['time'])->format("H:i");

        $data['uuid'] = Str::uuid()->toString();
        $data['status'] = 'pending';
        $data['user_uuid'] = $user->uuid;
        // if ($request->medical_center) {
        //     $data['center_uuid'] = $request->medical_center;

        //uncomment this
        // }

        $check = Appointment::with(['doctor'])->where([
            'doctor_id' => $request->doctor_id,
            'date' => $data['date'], 'time' => $data['time'], ['status', '!=', 'cancelled']
        ])->first();

        /*$check = Appointment::with(['center'])->where(['center_uuid' => $request->medical_center,
            'date' => $data['date'], 'time' => $data['time'], ['status', '!=', 'cancelled']])->first();
        */

        if (!empty($check)) {
            return response([
                'status' => false,
                'message' => [
                    ["Sorry, an appointment has already been booked at Dr. {$check->doctor->firstname} with your selected time. Select another time and try again."]
                ]
            ]);
        }


        return [
            'noerror' => true,
            'message' => "There Was No Error",
           
        ];


        // $appointment = Appointment::create($data);
        // $user->notify(new AppointmentBookedNotification($appointment));
        // SendAppointmentEmail::dispatch($appointment);
        // return response([
        //     'status' => true,
        //     'message' => "Appointment booked successfully",
        //     'appointment' => $appointment
        // ]);

        /**DO NOT DELETE */
        $user->load('vendor');

        try {

            $response = $this->httpPost($user->vendor, '/api/appointments/book', $data);

            if ($response->getReasonPhrase() === 'OK') {
                return $response->getBody();
            }
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
                'msg' => 'Error while booking appointment.'
            ]);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while booking appointment.'
            ]);
        }

        return response([
            'msg' => 'Error while booking appointment.'
        ]);


    }


    //public function Verify paystack

    public function verifyappointmentpayment($reference){
        //this will receive
        $curl = curl_init();
  
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer sk_live_3e7df128855a0e79a3fbbc97f4250066524cfd4e",
            "Cache-Control: no-cache",
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }



    }


    //Add Appointment to the appointmet database

    public function completebooking(Request $request){
        

        $appointment = new Appointment();

        $appointment->user_uuid = $request->user_uuid;
        $appointment->status = "pending";
        $appointment->date =$request->date;
        $appointment->time = $request->time;
        $appointment->location = "Online Scheduled Meeting";
        $appointment->ref_no = $request->ref_no;
        $appointment->uuid = Str::uuid()->toString();
        $appointment->doctor_id = $request->doctor_id;
        $appointment->type =$request->type;
        $appointment->doctor_name = $request->doctor_name;
        
        $appointment->doctor_aos = $request->doctor_aos;
        $appointment->link = $request->link;
       $appointment->save();
        
       $customerdetails = [
           'doctor' => $request->doctor_name,
           'time' => $request->time,
           'date' => $request->date,
           'doctoraos' => $request->doctor_aos,
           "link"=>$request->link,
           'username' => $request->username,
        
       ];
       
       TransactionLog::create([
        'gateway_reference' => $request->ref_no,
        'system_reference' => $request->ref_no,
        'reason' => 'Doctor Appointment',
        'amount' => $request->amount,
        'email' => $request->user_email,
    ]);
    

    Mail::to($request->user_email)->send(new AppointmentCustomer($customerdetails));
       
    Mail::to($request->doctor_email)->send(new AppointmentDoctor($customerdetails));

    Mail::to("support@asknello.com")->send(new AdminOnlineAppointment($customerdetails));
       

        //$user->notify(new AppointmentBookedNotification($appointment));
        //SendAppointmentEmail::dispatch($appointment);
        //SendAppointmentEmail::dispatch();
        return [

            'status'=>true,
            "message"=>"Appointment Booked Successfully",
            "user_uuid"=>$request->user_uuid,
            "date"=>$request->date,
            "time"=>$request->time,
            "ref_no"=>$request->ref_no,
            "uuid"=>Str::uuid()->toString(),
            "doctor_id"=>$request->doctor_id,
            "doctor_name"=>$request->doctor_name,
            "type"=>$request->type,
            "doctor_aos"=>$request->doctor_aos,
            "link"=>$request->link,
            "user_email"=>$request->user_email,
            "doctor_email"=>$request->doctor_email,
            "username"=>$request->username,

        ];
       

    }

    public function bookHospitalAppointment(Request $request){

        $input = $request->all();

        if (isset($input['time'])) $input['time'] = date('Y-m-d H:i', strtotime("{$input['date']} {$input['time']}"));

        $data = $request->validate([
            'reason' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'date' => 'required',
            //'time' => 'required|date_format:Y-m-d H:i|after:' . date('Y-m-d H:i', strtotime("+30 minutes")),
            'time' => [
                'required',
                'date_format:H:i:s',
                function ($attr, $value, $fail) use($request) {
                    $frags1 = explode('-', $request->date);
                    $frags = explode(':', $value);
                    $now = Carbon::now()->addMinutes(30);
                    $time = Carbon::now();
                    $time->setDate($frags1[0], $frags1[1], $frags1[2])
                        ->setHour($frags[0])
                        ->setMinute($frags[1])
                        ->setSecond(0);

                    // if ($now->gte($time)) {
                    //     $fail("Time must be at least 30 minutes after the current time.");
                    // }
                }
            ],
            // 'medical_center' => [
            //     Rule::requiredIf(empty($request->doctor_id)),
            //     'exists:health_centers,uuid'
            // ],
            // 'doctor_id' => [
            //     Rule::requiredIf(empty($request->medical_center)),
            //     Rule::exists('users', 'id')->where(function ($query) {
            //         $query->where('user_type', 'doctor');
            //     })
            // ]
        ]);

        $user = $request->user();

        //if (isset($data['time'])) $data['time'] = \DateTime::createFromFormat(
        //    "Y-m-d H:i", $data['time'])->format("H:i");

        $data['uuid'] = Str::uuid()->toString();
        $data['status'] = 'pending';
        $data['user_uuid'] = $user->uuid;
        // if ($request->medical_center) {
        //     $data['center_uuid'] = $request->medical_center;

        //uncomment this
        // }

        // $check = Appointment::with(['doctor'])->where([
        //     'doctor_id' => $request->doctor_id,
        //     'date' => $data['date'], 'time' => $data['time'], ['status', '!=', 'cancelled']
        // ])->first();

        /*$check = Appointment::with(['center'])->where(['center_uuid' => $request->medical_center,
            'date' => $data['date'], 'time' => $data['time'], ['status', '!=', 'cancelled']])->first();
        */

        // if (!empty($check)) {
        //     return response([
        //         'status' => false,
        //         'message' => [
        //             ["Sorry, an appointment has already been booked at Dr. {$check->doctor->firstname} with your selected time. Select another time and try again."]
        //         ]
        //     ]);
        // }


        return [
            'noerror' => true,
            'message' => "There Was No Error",
            "reason" => $request->reason,
           
        ];


      

        /**DO NOT DELETE */
        $user->load('vendor');

        try {

            $response = $this->httpPost($user->vendor, '/api/appointments/hospital/book', $data);

            if ($response->getReasonPhrase() === 'OK') {
                return $response->getBody();
            }
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
                'msg' => 'Error while booking appointment.'
            ]);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while booking appointment.'
            ]);
        }

        return response([
            'msg' => 'Error while booking appointment.'
        ]);

    }

    public function hospitalappointmentbooking(Request $request){
        $appointment = new Appointment();

        $appointment->user_uuid = $request->user_uuid;
        $appointment->status = "pending";
        $appointment->date =$request->date;
        $appointment->time = $request->time;
        $appointment->location = $request->center_address;
        $appointment->ref_no = $request->ref_no;
        $appointment->uuid = Str::uuid()->toString();
      
        $appointment->type =$request->type;
        $appointment->reason=$request->reason;
        $appointment->center_uuid=$request->center_uuid;
        $appointment->center_name=$request->center_name;

        $appointment->save();

        //$centertype = HealthCenter::where('uuid',$request->center_uuid)->value('center_type');
        //$username = User::where('uuid',$request->user_uuid)->value('firstname');

        $medical = [
            'centername' => $request->center_name,
            'time' => $request->time,
            'date' => $request->date,
           
            "link"=> "https://admin.asknello.com/visitation/".$request->ref_no,
            
         
        ];

        Mail::to($request->useremail)->send(new AppointmentMedical($medical));
        Mail::to("support@asknello.com")->send(new AdminPhysicalAppointment($medical));



        TransactionLog::create([
            'gateway_reference' => $request->ref_no,
            'system_reference' => $request->ref_no,
            'reason' => 'Medical Center Appointment',
            'amount' => $request->amount,
            'email' => $request->useremail,
        ]);

        return [

            'status'=>true,
            "message"=>"Appointment Booked Successfully",
            "user_uuid"=>$request->user_uuid,
            "date"=>$request->date,
            "time"=>$request->time,
            "ref_no"=>$request->ref_no,
            "uuid"=>Str::uuid()->toString(),
            "location"=>$request->center_address,
            "type"=>$request->type,
            "reason"=>$request->reason,
            "center_uuid"=>$request->center_uuid,
            "center_name"=>$request->center_name,

        ];

      
        //$appointment->save();
    }



    public function pending(Request $request)
    {
        $appointment = Appointment::with(['center'])->where([
            'user_uuid' => $request->user()->uuid,
            'status' => 'pending'
        ])->orderBy('created_at', 'desc')->first();

        return [
            'appointment' => $appointment
        ];

        /**DO NOT DELETE */
        $user = $request->user();
        $user->load('vendor');

        try {

            $response = $this->httpGet($user->vendor, '/api/appointments/pending', ['user_uuid' => $user->uuid]);

            //if ($response->getReasonPhrase() === 'OK') {
            //    return $response->getBody();
            //}
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
            ]);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while fetching pending appointment.'
            ]);
        }

        return response([
            'msg' => 'Error while fetching pending appointment.'
        ]);
    }



    //Appointment

    /**
     * Update appointment
     *
     * Update the details of an appointment
     *
     * @bodyParam uuid uuid required the uuid of the appointment
     * @bodyParam medical_center uuid required a health center uuid
     * @bodyParam reason string required the purpose of the appointment
     * @bodyParam date date format yyyy-mm-dd
     * @bodyParam time time format HH:mm
     */
    public function update(Request $request)
    {
        $input = $request->all();

        if (isset($input['time'])) $input['time'] = date('Y-m-d H:i', strtotime("{$input['date']} {$input['time']}"));

        $validator = Validator::make($input, [
            'uuid' => 'required|string',
            'reason' => 'required|string',
            'type' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:Y-m-d H:i|after:' . date('Y-m-d H:i', strtotime("+30 minutes"))
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $user = $request->user();
        $appointment = Appointment::with(['center'])->where('uuid', $request->uuid)->first();
        $data = $validator->validated();

        if (isset($data['time'])) $data['time'] = \DateTime::createFromFormat(
            "Y-m-d H:i",
            $data['time']
        )->format("H:i");

        $check = Appointment::with(['center'])->where([
            'center_uuid' => $appointment->center_uuid,
            'date' => $data['date'], 'time' => $data['time'], ['id', '!=', $appointment->id],
            ['status', '!=', 'cancelled']
        ])->first();

        if (!empty($check)) {
            return response([
                'status' => false,
                'message' => [
                    ["Sorry, an appointment has already been booked at {$check->center->name} with your selected time. Select another time and try again."]
                ]
            ]);
        }

        $appointment->update($data);

        $user->notify(new AppointmentUpdatedNotification($appointment));

        return [
            'status' => true,
            'message' => 'Appointment updated successfully',
            'appointment' => $appointment
        ];

        /**DO NOT DELETE */
        $user->load('vendor');

        try {

            $response = $this->httpPost($user->vendor, '/api/appointments/update', $data);

            //if ($response->getReasonPhrase() === 'OK') {
            //    return $response->getBody();
            //}

            //return $response->getBody();
            return response([
                'status' => true,
                'message' => 'Appointment updated successfully'
            ]);
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            } else {
                print_r($e);
                //$str = json_encode($e, true);
            }
            return response([
                'status' => false,
                'message' => 'Error while updating appointment.'
            ]);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'status' => false,
                'message' => 'Error while updating appointment.'
            ]);
        }
    }

    /**
     * Cancel appointment
     *
     * @urlParam uuid uuid required the uuid of the appointment
     */
    public function cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $user = $request->user();
        $appointment = Appointment::with(['center'])->where('uuid', $request->uuid)->first();
        $appointment->status = 'cancelled';
        $appointment->save();
        $user->notify(new AppointmentCancelledNotification($appointment));

        return response([
            'status' => true,
            'message' => 'Appointment cancelled successfully',
            'current' => $appointment,
            'appointment' => $this->pending($request)['appointment']
        ]);

        /**DO NOT DELETE */
        $user->load('vendor');
        $data = $validator->validated();

        try {

            $response = $this->httpPost($user->vendor, '/api/appointments/cancel', $data);

            //if ($response->getReasonPhrase() === 'OK') {
            //    return $response->getBody();
            //}
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
                'msg' => 'Error while updating appointment.'
            ]);
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            return response([
                'msg' => 'Error while updating appointment.'
            ]);
        }

        return response([
            'msg' => 'Error while updating appointment.'
        ]);
    }


    /**
     * View Appointment
     *
     * View details of an appointment
     *
     * @urlParam uuid uuid required the uuid of the appointment
     */
    public function viewAppointment(Request $request)
    {
        if (empty($request->uuid)) {
            return response(['error' => 'Query parameter uuid is missing']);
        }
        $appointment = $this->find($request->uuid);
        return $appointment;
    }


    public function confirmref($ref){
        $appointment = Appointment::where('ref_no',$ref)->exists();

        if(!$appointment){
            return 'Invalid Reference';
        }

        else{
            $appointment = Appointment::where('ref_no',$ref)->first();

            return $appointment;
        }

       
    }

    public function checkExistedDoctorAppointment(Request $request){

        
        $appointment = Appointment::where('type','doctor_appointment')->where('doctor_id', $request->doctor_id)->where('date', $request->date)->where('time', $request->time)->exists();

        if($appointment){
            return response()->json('true');
        }
        else {
            return response()->json('false');
        }
    }


    public function checkExistedMedAppointment(Request $request){
        $appointmentss = Appointment::where('center_name',$request->name)->where('date', $request->date)->where('time', $request->time)->exists();

        if($appointmentss){
            return response()->json('true');
        }
        else {
            return response()->json('false');
        }

    }



    //OWC mail

    public function OWC(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'title' => 'required',
            'date' => 'required',
            'time' => 'required',
            'ref' => 'required',
            'user_firstname' => 'required',
            'caretype' => 'required'

        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
        }

        if(!$request->type){

            //if no request type

            $owc = [
                "title" => $request->title,
                "date" => $request->date,
                "time" => $request->time,
                "ref" => $request->ref,
                "user_firstname" =>$request->user_firstname,
                "caretype" => $request->caretype,
                "type"=>"Visitation",
                "doctor" => "none",
                "link" => "none",
    
            ];
    
            $owcadmin = [
                "title" => $request->title,
                "date" => $request->date,
                "time" => $request->time,
                "ref" => $request->ref,
                "user_firstname" =>$request->user_firstname,
                "caretype" => $request->caretype,
                "type"=>"Visitation",
                "doctor" => "none",
                "link" => "none",
    
            ];
    
            Mail::to($request->email)->send(new OWCMail($owc));
            //Mail::to('appointments@onewellness.clinic')->send(new OWCADMIN($owcadmin));
            Mail::to('appointments@onewellness.clinic')->send(new OWCADMIN($owcadmin));
            Mail::to('info@onewellness.clinic')->send(new OWCADMIN($owcadmin));
            return "working";

        }

        elseif($request->type){

        //     $appointment->type = $request->type;
        // $appointment->doctor = $request->doctor;
        // $appointment->link = $request->link;

            $owc = [
                "title" => $request->title,
                "date" => $request->date,
                "time" => $request->time,
                "ref" => $request->ref,
                "user_firstname" =>$request->user_firstname,
                "caretype" => $request->caretype,
                "type" =>$request->type,
                "doctor" => $request->doctor,
                "link" => $request->link,
    
            ];
    
            $owcadmin = [
                "title" => $request->title,
                "date" => $request->date,
                "time" => $request->time,
                "ref" => $request->ref,
                "user_firstname" =>$request->user_firstname,
                "caretype" => $request->caretype,
                "type" =>$request->type,
                "doctor" => $request->doctor,
                "link" => $request->link,
    
            ];

            //doctor email = $doctor_email $request->doctor_email
    
            Mail::to($request->email)->send(new OWCMail($owc));
            Mail::to('appointments@onewellness.clinic')->send(new OWCADMIN($owcadmin));
            Mail::to('info@onewellness.clinic')->send(new OWCADMIN($owcadmin));
            Mail::to($request->doctor_email)->send(new OWCDOCTOR($owcadmin));
         
            return "working";

        }

       


    }


    //cronjob reminder for appointment controller

    public function cronjobreminder(Request $request){

        // $time = Carbon::now()->addHour()->format('H:i:s');

        // return $time;

        
      $appointments = Appointment::with(['user', 'center', 'doctor'])->where("date",Carbon::now()->format("Y-m-d"))->get();

      
    

    // return  $appointments;

         foreach($appointments as $appointment){

            if(Carbon::parse($appointment->time)->format("H") == Carbon::now()->addHour()->format('H')){
                //return $appointment->time;

                if($appointment->doctor){
            
                    $docreminder = [
                        "username" => $appointment->user->firstname,
                        "specialist" => $appointment->doctor->title.". ".$appointment->doctor->firstname,
                        "date" => Carbon::parse($appointment->date)->format('F dS, Y'),
                        "time" => Carbon::parse($appointment->time)->format('h:ia'),
                        "link" => $appointment->link,
                    ];
    
                    Mail::to("edidiongbobson@gmail.com")->send(new Docreminder($docreminder));
    
                }

                ///center 

                elseif($appointment->center){
                
    
                    $medreminder = [
                        "firstname" => $appointment->user->firstname,
                        "centername" => $appointment->center->name,
                        "date" => Carbon::parse($appointment->date)->format('F dS, Y'),
                        "time" => Carbon::parse($appointment->time)->format('h:ia'),
                        //"link" => $appointment->link,
                        "link"=> "https://admin.asknello.com/visitation/".$appointment->ref_no,
                    ];
        
                    Mail::to($appointment->user->email)->send(new Medreminder($medreminder));
                }
        

                return "sent";
            }

           
            
    
           
            //elseif($appointment)
    
            //return "sent";
    

         }

        

        
       

    }

    public function appointmentswichmails(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'user_email' => 'required',
            'date' => 'required',
            'time' => 'required',
            'old_doctor_title' => 'required',
            'old_doctor_firstname' => 'required',
            'new_doctor_title' => 'required',
            'new_doctor_firstname' => 'required',
            'new_doctor_email' => 'required',
            'specialization' => 'required',
            'link' => 'required'
          

        ]);

        

        

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
        }


        $switchuser = [
            'username' => $request->username,

       
            'user_email' => $request->user_email,
            'date' => $request->date,
            'time' => $request->time,
            'old_doctor_title' => $request->old_doctor_title,
            'old_doctor_firstname' => $request->old_doctor_firstname,
            'new_doctor_title' => $request->new_doctor_title,
            'new_doctor_firstname' => $request->new_doctor_firstname,
            'new_doctor_email' => $request->new_doctor_email,
            'specialization' => $request->specialization,
            'link' => $request->link,

        ];

        Mail::to($request->user_email)->send(new SwitchUser($switchuser));
        Mail::to($request->new_doctor_email)->send(new SwitchDoctor($switchuser));
        Mail::to("nurses@famacare.com")->send(new SwitchDoctor($switchuser));

        return response()->json([
            'status' => "success"
        ]);

        






    }


    //genrate outlook ics file

    public function generateicsfile($start, $time, $care){
         // Convert start and end to DateTime objects or use them directly in your logic


         $dateString = $start;
         $timeString = $time;
         
         // Combine date and time
         $startCombinedString = $dateString . ' ' . $timeString;
         
         // Create DateTime object for start datetime
         $startDateTime = new DateTime($startCombinedString);
         
         // Add 30 minutes for end datetime
         $endDateTime = clone $startDateTime; // Clone to avoid modifying the start datetime
         $endDateTime->modify('+30 minutes');
         
         // Format as ISO 8601
         $startDateTimed = $startDateTime->format('Ymd\THis');
         $endDateTimed = $endDateTime->format('Ymd\THis');
         
        


   

    $calendarContent = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nBEGIN:VEVENT\r\nDTSTART:{$startDateTimed}\r\nDTEND:{$endDateTimed}\r\nSUMMARY:OWC Appointment\r\nDESCRIPTION:Your appointment details here\r\nLOCATION:1 Akin Adesola Street, Victoria Island, Lagos Nigeria\r\nEND:VEVENT\r\nEND:VCALENDAR";

    // Set headers for the response
    $headers = [
        'Content-Type' => 'text/calendar; charset=utf-8',
        'Content-Disposition' => 'attachment; filename=appointment.ics',
    ];

    return response($calendarContent, 200, $headers);
    }





    public function downloadics($start,$end,$care){
       // Convert start and end to DateTime objects or use them directly in your logic

       $startDateTime = $start;
       $endDateTime = $end;
   
       $calendarContent = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nBEGIN:VEVENT\r\nDTSTART:{$startDateTime}\r\nDTEND:{$endDateTime}\r\nSUMMARY:OWC Appointment\r\nDESCRIPTION:You have successfully scheduled an appointment with a {$care}\r\nLOCATION:1 Akin Adesola Street, Victoria Island, Lagos Nigeria\r\nEND:VEVENT\r\nEND:VCALENDAR";
   
       // Set headers for the response
       $headers = [
           'Content-Type' => 'text/calendar; charset=utf-8',
           'Content-Disposition' => 'attachment; filename=appointment.ics',
       ];
   
       return response($calendarContent, 200, $headers);
   
    }


}
