<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

class RegistrationTest extends TestCase
{
    /**
     * @test
     */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        event(new Registered(create('App\User')));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /**
     * @test
     */
    public function user_can_fully_confirm_theis_email_address()
    {
        $this->post('/register', [
            'name'                  => 'John',
            'email'                 => 'john@example.com',
            'password'              => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

        $user = \App\User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $response = $this->get('/register/confirm?token=' . $user->confirmation_token);

        $this->assertTrue($user->fresh()->confirmed);

        $response->assertRedirect('/threads');
    }
}
