<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\HealthCenter;
use App\Models\User;
use App\Mail\Medreminder;
use App\Mail\Docreminder;
use Mail;
use Carbon\Carbon;

class HourReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hour:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to customer to remind them hourly';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
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
    
                    Mail::to($appointment->user->email)->send(new Docreminder($docreminder));
    
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
        

                //return "sent";
            }

           
            
    
           
            //elseif($appointment)
    
            //return "sent";
    

         }

        

        
       

    }
}
