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
class DailyReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily appointment reminder to users';

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
        //////

        $appointments = Appointment::with(['user', 'center', 'doctor'])->where("date",Carbon::now()->addDay()->format("Y-m-d"))->get();

        foreach($appointments as $appointment){

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
   
           //elseif($appointment)
   
           //return "sent";
   

        }

        Log::debug("daily reminder sent");




        ////
    }
}
