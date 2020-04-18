<?php

namespace Tests\Feature\Http\Controller\Api;

use App\User;
use Tests\TestCase;
use App\Models\Employee;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp() :void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    /**
     * @test
     */
    public function cannotAccessWithoutToken(Type $var = null)
    {
        $index = $this->json('get','/api/v1/employees');

        $index->assertJsonStructure([
            'status','data'
        ])->assertJson([
            'status'=>'failed',
            'data'=>[
                'message'=>'Unauthenticated.'
            ]
        ])->assertStatus(401);
    }

    /**
     * @test
     */
    
    public function canAccessEmployeeIndexWithToken()
    {
        $user = factory(User::class)->create();
        $employee = factory(Employee::class,8)->create();
        
        $getToken = $this->json('POST','/api/v1/login',[
            'email'=>$user->email,
            'password'=>'secret'
        ]);

        
        $response = $this->withHeaders([
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer '.$getToken['data']['token']
        ])->json('get','/api/v1/employees');


        $response->assertJsonStructure([
            'status','data'=>[
                '*'=>['id','employee_name','employee_salary','employee_age','profile_image']
            ]
        ])->assertStatus(200);
    }

    /**
     * @test
     */

    public function canAccessEmployeeIndexWithActingAs()
    {
        $user = factory(User::class)->create();
        $employee = factory(Employee::class,8)->create();
        
        $response = $this->actingAs($user,'api')->json('get','/api/v1/employees');

        $response->assertJsonStructure([
            'status','data'=>[
                '*'=>['id','employee_name','employee_salary','employee_age','profile_image']
            ]
        ])->assertStatus(200);
    }

    /**
     * @test
     */

    public function canAccessEmployeeShow()
    {
        $user = factory(User::class)->create();
        $employee  = factory(Employee::class)->create();

        $response = $this->actingAs($user,'api')->json('get',"/api/v1/employee/$employee->id");

        $response->assertJsonStructure([
            'status','data'=>[
                'id','employee_name','employee_salary','employee_age','profile_image'
            ]
        ])->assertJson([
            'status'=>'success',
            'data'=>[
                'id'=>$employee->id,
                'employee_name'=>$employee->name,
                'employee_salary'=>$employee->salary,
                'employee_age'=>$employee->age,
                'profile_image'=>$employee->profile_image
            ]
        ])->assertStatus(200);
    }
}
