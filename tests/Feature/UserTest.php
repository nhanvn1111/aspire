<?php

namespace Tests\Feature;

use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void{
        parent::setUp();
        $this->artisan('passport:install');
    }
  
   /**
    * required fields for registration
    * status ok
    */
    public function testRequiredFieldsForRegistration()
    {
        
       
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
        ->assertJson([
            "message" => "Validation Error.",
            "data" => [
                "name" => ["The name field is required."],
                "email" => ["The email field is required."],
                "password" => ["The password field is required."],
                "c_password" => ["The c password field is required."],
            ]
        ]);
    }

    public function testSuccessfulRegistration()
    {
        $userData = [
            "name" => "Nhan",
            "email" => "nhan9@gmail.com",
            "password" => "123456",
            "c_password" => "123456"
        ];
      
        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    /**
     * required fields for registration → This test did not perform any assertions  D:\nhan_training\aspire\aspire_chanllenge\tests\Feature\UserTest.php:30
    *✓ successful login
    * status ok
     */
    public function testSuccessfulLogin()
    {
        $user = User::factory()->create([
           'email' => 'nhanvn12@gmail.com',
           'password' => bcrypt('123456'),
        ]);


        $loginData = ['email' => 'nhanvn12@gmail.com', 'password' => '123456'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200);

    }

   

    /**
    *✓ successful get data Loan
    * status ok
     */
    public function testIndexLoan()
    {
        $user = User::factory()->create([
           'email' => 'nhanvn123@gmail.com',
           'password' => bcrypt('123456'),
        ]);

        $token = $user->createToken('TestToken')->accessToken;
      
        $header = [];
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer '.$token;

        $response = $this->json('GET', '/api/loans', [], $header);

        $response->assertStatus(200);

        
    }

    /**
    *✓ successful create data Loan
    * status ok
     */
    public function testCreateLoan()
    {
        $user = User::factory()->create([
           'email' => 'nhanvn123@gmail.com',
           'password' => bcrypt('123456'),
        ]);

        $token = $user->createToken('TestToken')->accessToken;
      
        $header = [];
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer '.$token;

        $response = $this->json('POST', 'api/create/loans', [
            'user_id' => $user->id,
            'amount' => 10000,
            'repayment_frequency'=> 12,
            'interest_rate'=> 9,
            'arrangement_fee' => 1800000,
            'amount_repayment' => 12700000,
            'current_month_repayment' => 1058333,
            'status' => 'LOAN_STATUS_NONE',
            'deleted' => 0,
            'amount_need_repayment' => 12700000
        ], $header);
        
        $response->assertStatus(200);

    }

    /**
     * Create Repayment
     * issue Amount loan not enought
     */
    public function testIndexRepaymentsFail(){
        $user = User::factory()->create([
            'email' => 'nhanvn123@gmail.com',
            'password' => bcrypt('123456'),
         ]);
         
         $loan = Loan::factory(['user_id'=> $user->id])->create();

         $token = $user->createToken('TestToken')->accessToken;
       
         $header = [];
         $header['Accept'] = 'application/json';
         $header['Authorization'] = 'Bearer '.$token;
 
         $response = $this->json('POST', 'api/create/repayment', [
             'loan_id' => $loan->id,
             'amount' => 10000,
             'message'=> 12,
             'deleted'=> 9
         ], $header);
         $response->assertStatus(404);
    }
}
