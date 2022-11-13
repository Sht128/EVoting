<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Voter;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Database\Factories;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions; 
use Database\Seeders\VoterSeeder;
use Database\Seeders\VotersSeeder;

class RegistrationLoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test Login View
     */
    public function test_login_page(){
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test Register View
     */
    public function test_register_page(){
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * Test Login User
     */
    public function test_voter_login(){
        $voter = Voter::factory()->create();

        $response = $this->actingAs($voter)->get('/login');
        $response->assertStatus(302);
        $response->assertRedirect('home');
    }

    /**
     * Test Login With Incorrect Credentials
     * @dataProvider invalidLoginDataProvider
     */
    public function test_fail_login($ic, $password) {
        $this->seed(VoterSeeder::class);

        $response = $this->from('login')->post('/login', [
            'email' => $ic,
            'password' => $password,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /**
     * Test Register User
     * @dataProvider invalidRegisterDataProvider
     */
    public function test_register_fail($ic,$name,$state,$postcode,$mobileno,$email,$password){

        $response = $this->from('register')->post('/register', [
            'ic' => $ic,
            'name' => $name,
            'state' => $state,
            'postcode' => $postcode,
            'mobileno' => $mobileno,
            'email' => $email,
            'password' => $password,
            'password-confirm' => $password,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    public function validRegisterDataProvider(){
        return array(
            array('123456789123','Teoh','Penang', '11900','+012345678891','hello@example.com','QWER@qwer123'),
        );
    }

    public function invalidRegisterDataProvider(){
        return array(
            array('1234567w89123','aQ','Penang', '11900','+012345678891','hello@example.com','QWERwer123'),
        );
    }

    public function invalidLoginDataProvider() {
        return array(
            array("123123089678", "hellothere"),
            array("icnumber", "ABCD@qw123"),
        );
    }
}
