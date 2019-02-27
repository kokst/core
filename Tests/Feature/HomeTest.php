<?php

namespace Kokst\Core\Tests\Feature;

use Kokst\Core\Http\User;
use Kokst\Core\Tests\ModuleTestCase as TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    protected function homeRoute()
    {
        return route('home');
    }

    protected function loginGetRoute()
    {
        return route('login');
    }

    protected function loginPostRoute()
    {
        return route('login');
    }
    
    public function testRedirectToLoginIfNotAuthenticated()
    {
        $response = $this->get($this->homeRoute());
        $response->assertRedirect($this->loginGetRoute());
    }

    public function testUserCanViewHomeIfAuthenticated()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'i-love-laravel'),
        ]);

        $response = $this->actingAs($user)->post($this->loginPostRoute());

        $response->assertRedirect($this->homeRoute());
        $this->assertAuthenticatedAs($user);

        $response = $this->get($this->homeRoute());
        $response->assertStatus(200);
    }
}