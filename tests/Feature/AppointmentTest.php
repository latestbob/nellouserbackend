<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\HealthCenter;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Jobs\ExportUser;


class AppointmentTest extends TestCase
{

    //use RefreshDatabase;
    
   /* private $user = [
        "firstname" => "Achilles",
        "lastname" => "Achilles",
        "email" => "achilles2@gmail.com",
        "dob" => "1999-09-30",
        "phone" =>  "08012345198",
        "password" => "password",
        "password_confirmation" => "password"
    ];

    private $appointment = [
        'reason' => 'Personal reasons',
        'time'   => '14:14'
    ];

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /*public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testSignup()
    {
        $response = $this->postJson('/api/auth/register', $this->user);
        //$response->dump();
        $response->assertStatus(200);
    }

    public function testBook()
    {
        $oneWeek = Carbon::today()->addWeek();
        $response = $this->postJson('/api/auth/login', [
            'email' => $this->user['email'],
            'password' => $this->user['password']
        ]);

        $this->appointment['date'] = $oneWeek->toDateString();
        $center = factory(HealthCenter::class)->create();
        $this->appointment['medical_center'] = $center->uuid;
        $data = $response->getData(true);
        $token = 'Bearer ' . $data['token'];

        $response = $this->withHeaders([
            'Authorization' => $token
        ])->postJson('/api/appointments/book', $this->appointment);
        //$response->dump();
        $data = $response->getData(true);
        $response->assertSuccessful();
        HealthCenter::where('uuid', $center->uuid)->delete();
        Appointment::where('uuid', $data['uuid'])->delete();
    }


    public function testUpdate()
    {
        $oneWeek = Carbon::today()->addWeek();
        $response = $this->postJson('/api/auth/login', [
            'email' => $this->user['email'],
            'password' => $this->user['password']
        ]);

        $this->appointment['date'] = $oneWeek->toDateString();
        $center = factory(\App\Models\HealthCenter::class)->create();
        $this->appointment['medical_center'] = $center->uuid;
        $data = $response->getData(true);
        $token = 'Bearer ' . $data['token'];

        $response = $this->withHeaders([
            'Authorization' => $token
        ])->postJson('/api/appointments/book', $this->appointment);
        //$response->dump();
        $data = $response->getData(true);
        $response->assertSuccessful();
        
        $newTime= '15:00';
        $newDate = $oneWeek->addDays(3)->toDateString();
        $this->appointment['uuid'] = $data['uuid'];
        $this->appointment['time'] = $newTime;
        $this->appointment['date'] = $newDate;

        $response = $this->withHeaders([
            'Authorization' => $token
        ])->postJson('/api/appointments/update', $this->appointment);
        //$response->dump();
        //$data = $response->getData(true);
        $response->assertSuccessful();
        $response->assertJson([
            'time' => $newTime,
            'date' => $newDate
        ]);
        HealthCenter::where('uuid', $center->uuid)->delete();
        Appointment::where('uuid', $data['uuid'])->delete();
    }

    public function testCancel()
    {
        $oneWeek = Carbon::today()->addWeek();
        $response = $this->postJson('/api/auth/login', [
            'email' => $this->user['email'],
            'password' => $this->user['password']
        ]);

        $this->appointment['date'] = $oneWeek->toDateString();
        $center = factory(\App\Models\HealthCenter::class)->create();
        $this->appointment['medical_center'] = $center->uuid;
        $data = $response->getData(true);
        $token = 'Bearer ' . $data['token'];

        $response = $this->withHeaders([
            'Authorization' => $token
        ])->postJson('/api/appointments/book', $this->appointment);
        //$response->dump();
        $data = $response->getData(true);
        $response->assertSuccessful();
        
        $response = $this->withHeaders([
            'Authorization' => $token
        ])->postJson('/api/appointments/cancel', ['uuid' => $data['uuid']]);
        //$response->dump();
        $response->assertSuccessful();
        $response->assertJson([
            'status' => 'cancelled'
        ]);
        HealthCenter::where('uuid', $center->uuid)->delete();
        Appointment::where('uuid', $data['uuid'])->delete();
    }


    public function testDelete()
    {
        $user = User::where('email', $this->user['email'])->first();
        //User::destroy($user->id);
        ExportUser::dispatchNow($user, 'delete');
        User::where('email', $this->user['email'])->delete();
        $this->assertDatabaseMissing('users', ['email' => $this->user['email']]);
    }*/
}
