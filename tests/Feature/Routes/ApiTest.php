<?php

namespace Tests\Feature\Routes;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
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
    
    public function canGetAccessToken()
    {
        $user = factory(User::class,1)->create();

        $response = $this->json('POST','/api/v1/login',[
            'email'=>$user[0]->email,
            'password'=>'secret'
        ]);

        $response->assertJsonStructure([
            'status',
            'data'=>[
                'token'
            ]
        ])->assertJson([
            'status'=>'success'
        ])->assertStatus(200);
    }

    /**
     * @test
     */

    public function return404PasswordWrong()
    {
        $user = factory(User::class,1)->create();

        $response = $this->json('POST','/api/v1/login',[
            'email'=>$user[0]->email,
            'password'=>'dummy'
        ]);
            
        $response->assertJsonStructure([
            'status','data'
        ])->assertJson([
            'status'=>'failed'
        ])->assertStatus(404);
    }
    
    /**
     * @test
     */

    public function return400EmailNotFound()
    {
        $user = factory(User::class,1)->create();

        $response = $this->json('POST','/api/v1/login',[
            'email'=>'dummy',
            'password'=>'dummy'
        ]);
            
        $response->assertJsonStructure([
            'status','data'
        ])->assertJson([
            'status'=>'failed'
        ])->assertStatus(400);
    }

    /**
     * @test
     */

    public function return400LoginFieldEmpty()
    {
        $response = $this->json('POST','/api/v1/login',[
        ]);

        $response->assertJsonStructure([
            'status','data'
        ])->assertJson([
            'status'=>'failed'
        ])->assertStatus(400);
    }

    /**
     * @test
     */

    public function return404RouteNotFound()
    {
        $response = $this->json('POST','/api/-1',[
        ]);

        $response->assertJsonStructure([
            'status','data'
        ])->assertJson([
            'status'=>'failed',
            'data'=>[
                'message'=>'Route not found'
            ]
        ])->assertStatus(404);
    }

}
