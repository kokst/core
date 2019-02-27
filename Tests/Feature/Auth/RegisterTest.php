<?php

namespace Tests\Feature\Auth;

use Kokst\Core\Tests\Stubs\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Kokst\Core\Tests\ModuleTestCase as TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function registerGetRoute()
    {
        return route('register');
    }

    protected function registerPostRoute()
    {
        return route('register');
    }

    protected function guestMiddlewareRoute()
    {
        return route('home');
    }

    public function testUserCanViewARegistrationForm()
    {
        $response = $this->get($this->registerGetRoute());

        $response->assertSuccessful();
        $response->assertViewIs('core::auth.register');
    }

    public function testUserCannotViewARegistrationFormWhenAuthenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->registerGetRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function testUserCannotRegisterWithoutName()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => '',
            'email' => 'john@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('name');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithoutEmail()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'John Doe',
            'email' => '',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithInvalidEmail()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithoutPassword()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithoutPasswordConfirmation()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => '',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testUserCannotRegisterWithPasswordsNotMatching()
    {
        $response = $this->from($this->registerGetRoute())->post($this->registerPostRoute(), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'i-love-laravel',
            'password_confirmation' => 'i-love-symfony',
        ]);

        $users = User::all();

        $this->assertCount(0, $users);
        $response->assertRedirect($this->registerGetRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
