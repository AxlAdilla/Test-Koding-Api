<?php

namespace Tests\Feature\Http\Controller\Api;

use App\User;
use Tests\TestCase;
use App\Models\Employee;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
    public function cannotAccessWithoutToken()
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

        $employee  = factory(Employee::class)->create();
        $show = $this->json('get','/api/v1/employee/'.$employee->id);

        $show->assertJsonStructure([
            'status','data'
        ])->assertJson([
            'status'=>'failed',
            'data'=>[
                'message'=>'Unauthenticated.'
            ]
        ])->assertStatus(401);

        $faker = Faker::create();

        $store = $this->json('POST','api/v1/create',[
            'name' => $name = $faker->name,
            'salary' =>$salary = $faker->numberBetween($min = 1000000,$max=5000000),
            'age'=>$age = $faker->numberBetween($min = 0,$max=100),
        ]);

        $store->assertJsonStructure([
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

    /**
     * @test
     */

    public function return404EmployeeShowModelNotFound()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user,'api')->json('get',"/api/v1/employee/-1");
        $response->assertJsonStructure([
            'status','data'=>[
                'message'
            ]
        ])->assertJson([
            'status'=>'failed'
        ])->assertStatus(404);
    }

    /**
     * @test
     */
    public function return400IfEmployeeStoreEmptyField()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user,'api')->json('POST','api/v1/create');
        
        $response->assertJsonStructure([
            'status','data'=>[
                'message'
            ]
        ])->assertJson([
            'status'=>'failed',
        ])->assertStatus(400);
    }

    /**
     * @test
     */
    public function return400IfEmployeeStoreProfileImagesNotImageFile()
    {
        Storage::fake('avatars');

        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $faker = Faker::create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user,'api')->json('POST','api/v1/create',[
            'name' => $name = $faker->name,
            'salary' =>$salary = $faker->numberBetween($min = 1000000,$max=5000000),
            'age'=>$age = $faker->numberBetween($min = 0,$max=100),
            'profile_image'=>$file
        ]);

        $response->assertJsonStructure([
            'status','data'=>[
                'message'
            ]
        ])->assertJson([
            'status'=>'failed',
        ])->assertStatus(400);
    }

    /**
     * @test
     */
    public function canCreateEmployeeWithoutProfileImage()
    {
        $faker = Faker::create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user,'api')->json('POST','api/v1/create',[
            'name' => $name = $faker->name,
            'salary' =>$salary = $faker->numberBetween($min = 1000000,$max=5000000),
            'age'=>$age = $faker->numberBetween($min = 0,$max=100),
        ]);

        $response->assertJsonStructure([
            'status','data'=>[
                'name','salary','age','id'
            ]
        ])->assertJson([
            'status'=>'success',
            'data'=>[
                'name'=>$name,
                'salary'=>$salary,
                'age'=>$age,
            ]
        ])->assertStatus(201);
    }

    /**
     * @test
     */
    public function canCreateEmployeeWithProfileImage()
    {
        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $faker = Faker::create();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user,'api')->json('POST','api/v1/create',[
            'name' => $name = $faker->name,
            'salary' =>$salary = $faker->numberBetween($min = 1000000,$max=5000000),
            'age'=>$age = $faker->numberBetween($min = 0,$max=100),
            'profile_image'=>$file
        ]);

        $response->assertJsonStructure([
            'status','data'=>[
                'name','salary','age','id','profile_image'
            ]
        ])->assertJson([
            'status'=>'success',
            'data'=>[
                'name'=>$name,
                'salary'=>$salary,
                'age'=>$age,
            ]
        ])->assertStatus(201);
    }
}
