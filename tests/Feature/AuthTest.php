<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Jobs\ExportUser;

class AuthTest extends TestCase
{
    private $user = [
        "firstname" => "Achilles",
        "lastname" => "Achilles",
        "email" => "achilles2@gmail.com",
        "dob" => "1999-09-30",
        "phone" =>  "08012345198",
        "password" => "password",
        "password_confirmation" => "password"
    ];


    public function testSignup()
    {
        $response = $this->postJson('/api/auth/register', $this->user);
        $response->dump();
        $response->assertStatus(200);
    }

   /* public function testLogin()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => $this->user['email'],
            'password' => $this->user['password']
        ]);
        //$response->dump();
        $response->assertStatus(200);
        $data = $response->getData(true);
        $this->token = $data['token'];
        //echo 'TOKEN ' . $this->token;
        $response->assertJsonStructure(['user', 'token']);
    }


    public function testUser()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => $this->user['email'],
            'password' => $this->user['password']
        ]);
        $data = $response->getData(true);
        $token = 'Bearer ' . $data['token'];
        //echo $token;
        $response = $this->withHeaders([
            'Authorization' => $token
        ])->getJson('/api/auth/user');
        //$response->dump();
        $response->assertJsonStructure(['user']);
    }

    public function testUpdate()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => $this->user['email'],
            'password' => $this->user['password']
        ]);
        $data = $response->getData(true);
        $token = 'Bearer ' . $data['token'];
        //echo $token;
        $newData = $this->user;
        $newData['middlename'] = 'Mikial';
        $newData['sponsor'] = 'Trokovich';
        $response = $this->withHeaders([
            'Authorization' => $token
        ])->postJson('/api/profile/update', $newData);
        //$response->dump();
        $response->assertJson([
            'middlename' => 'Mikial',
            'sponsor'    => 'Trokovich'
        ]);
    }

    public function testPasswordChange()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => $this->user['email'],
            'password' => $this->user['password']
        ]);

        $newPass = 'new-password';
        $data = $response->getData(true);
        $token = 'Bearer ' . $data['token'];
        //echo $token;
        $response = $this->withHeaders([
            'Authorization' => $token
        ])->postJson('/api/password/change', [
            'current_password' => $this->user['password'],
            'new_password' => $newPass
        ]);
        //$response->dump();
        $response->assertJson(['msg' => 'Password changed']);
        $response = $this->postJson('/api/auth/login', [
            'email' => $this->user['email'],
            'password' => $newPass
        ]);
        //$response->dump();
        $response->assertStatus(200);
        $data = $response->getData(true);
        $this->token = $data['token'];
        //echo 'TOKEN ' . $this->token;
        $response->assertJsonStructure(['user', 'token']);
    }*/

    /*public function testDelete()
    {
        $user = User::where('email', $this->user['email'])->first();
        //User::destroy($user->id);
        ExportUser::dispatchNow($user, 'delete');
        User::where('email', $this->user['email'])->delete();
        $this->assertDatabaseMissing('users', ['email' => $this->user['email']]);
    }*/

    /*protected function tearDown() : void
    {
        parent::tearDown();

        //User::where('email', $this->user['email'])->delete();
    }*/
}
