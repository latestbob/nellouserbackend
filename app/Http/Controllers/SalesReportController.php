<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use DB;
use App\SalesReport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SalesReportController extends Controller
{
    //sales report create,, note this is not a test,, 

    public function test(Request $request){

        $validator = Validator::make($request->all(), [
            'customer' => 'required',
            'cart_uuid' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }


        $cart = Cart::where("cart_uuid",$request->cart_uuid)->get();

        // $table->string("customer");
        //     $table->string("product_name");
        //     $table->float('unit_price');
        //     $table->string("vendor")->nullable();
        //     $table->integer("initial_quantity")->nullable();
        //     $table->integer("purchased_quantity");
        //     $table->float('total_amount');
        //     $table->string("cart_uuid");
        //     $table->string("month");

        //return $cart;

       // $cartArray = [];

        foreach($cart as $items){
            // $data = [
            //     "customer" => $request->customer,
            //     "product_name" => $items->drug->name,
            //     "unit_price" => $items->drug->price,
            //     "vendor" => $items->drug->vendor,
            //     "initial_quantity" => $items->drug->quantity,
            //     "purchased_quantity" => $items->quantity,
            //     "total_amount" => $items->price,
            //     "cart_uuid" => $request->cart_uuid,
            //     "month" => "March"
            // ];

           
          $salesreport = new SalesReport;

            //   "customer" => $request->customer,
            //     "product_name" => $items->drug->name,
            //     "unit_price" => $items->drug->price,
            //     "vendor" => $items->drug->vendor,
            //     "initial_quantity" => $items->drug->quantity,
            //     "purchased_quantity" => $items->quantity,
            //     "total_amount" => $items->price,
            //     "cart_uuid" => $request->cart_uuid,


                $salesreport->customer = $request->customer;
                $salesreport->product_name = $items->drug->name;
                $salesreport->unit_price = $items->drug->price;
                $salesreport->vendor = $items->drug->vendor;
                $salesreport->initial_quantity = $items->drug->quantity;
                $salesreport->purchased_quantity = $items->quantity;
                $salesreport->total_amount = $items->price;
                $salesreport->cart_uuid = $request->cart_uuid;
                $salesreport->month = "March";

                $salesreport->save();
            
        }

        //return $cartArray;

        return "done";

       
        
    }


    // get all sales report

    public function getall(){

        $salesreport = SalesReport::all();

        return $salesreport;
    }

    //delete sales report

    public function delete(Request $request){

        $salesreport = SalesReport::truncate();

        return "deleted";
    }


    //cart link

    public function cartlink(Request $request, $cart_uuid){
        $cart = Cart::where("cart_uuid",$cart_uuid)->get();

        $totalprice = Cart::where("cart_uuid",$cart_uuid)->sum("price");

       
        return view("cartlink",compact("cart",'totalprice'));
    }


    //cart pay

    public function cartpay(Request $request){
      
      $user = User::where("email",$request->user_email)->exists();

      if(!$user){
        return back()->with("error","Email not registered on Nello, kindly go to - https://asknello.com/signup to register");
      }


      $amount = $request->amount;

      

      if($request->coupon){
        $coupon = DB::table("coupons")->where("code",$request->coupon)->exists();
       

        if(!$coupon){
          return back()->with("error","Invalid Coupon Code");
        }

        $coupon = DB::table("coupons")->where("code",$request->coupon)->value("value");
        
        $discount = ($coupon / 100) * $amount;

        $amount = $amount - $discount;

      }

      

     

     


      $customer_id = User::where("email",$request->user_email)->value("id");
      $firstname = User::where("email",$request->user_email)->value("firstname");
      
      

        $url = "https://api.paystack.co/transaction/initialize";

        $fields = [
          'email' => $request->user_email,
          'amount' => $amount,

          'metadata' => [
            'customer_id' => $customer_id,
            'cart_uuid'  => $request->cartid,
            'firstname' => $firstname,
            'email' => $request->user_email,
            
          ]
      
        ];

   
      
        $fields_string = http_build_query($fields);
      
        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Bearer sk_live_3e7df128855a0e79a3fbbc97f4250066524cfd4e",
          "Cache-Control: no-cache",
        ));
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($ch);
        //echo $result;

        $tranx = json_decode($result, true);
        if(!$tranx['status']){
            // there was an error from the API
            print_r('API returned error: ' . $tranx['message']);
          }
          
          // comment out this line if you want to redirect the user to the payment page
          print_r($tranx);
          // redirect to page so User can pay
          // uncomment this line to allow the user redirect to the payment page
          header('Location: ' . $tranx['data']['authorization_url']);




        ////////


    }

    ///payment verification callback 

    public function paymentcallback(Request $request){

        $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/$request->reference",
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
   

    $tranx = json_decode($response, true);

    //dd($tranx);

    //dd($tranx['data']['metadata']['email']);


    $responsed = Http::withoutVerifying()->post('https://mw.asknello.com/api/unauth/checkout',[
     
    'firstname' => $tranx['data']['metadata']['firstname'],
    'email' => $tranx['data']['metadata']['email'],
    'cart_uuid' => $tranx['data']['metadata']['cart_uuid'],
    "delivery_method" => "pickup",
    'pickup_location_id' => 13,
    "payment_method" => "card",
    "payment_reference" => $request->reference,
    "customer_id" => $tranx['data']['metadata']['customer_id'],

  ]);

  if($responsed){
    return redirect()->route('thankyou');
  }
 
    // 'firstname' => 'nullable|string',
    //         'lastname' => 'nullable|string',
    //         'company' => 'nullable|string',
    //         'email' => 'nullable|email',
    //         'phone' => 'nullable|digits_between:11,16',
    //         'cart_uuid' => 'required|string|exists:carts,cart_uuid',
    //         'delivery_method' => 'required|string|in:shipping,pickup',
    //         'delivery_type' => 'required_if:delivery_method,shipping|string|in:standard,same_day,next_day',
    //         'shipping_address' => 'required_if:delivery_method,shipping|string',
    //         'location_id' => 'required_if:delivery_method,shipping|numeric|exists:locations,id',
    //         'pickup_location_id' => 'required_if:delivery_method,pickup|numeric|exists:pharmacies,id',
    //         //'city' => 'required_if:delivery_method,shipping|string',
    //         'payment_method' => 'required|string|in:card,point',
    //         'payment_reference' => 'required_if:payment_method,card|string',
    //         'coupon_code' => 'nullable|exists:coupons,code',
    //         'add_prescription_charge' => 'nullable|in:yes,no',
    //         'customer_id' => 'nullable',


  }

    }



    //order complete

    public function ordercomplete(){
      return view("thankyou");
    }
}
