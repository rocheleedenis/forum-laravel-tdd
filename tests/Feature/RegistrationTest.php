<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends TestCase
{
    /**
     * @test
     */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name'                  => 'John',
            'email'                 => 'john@example.com',
            'password'              => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /**
     * @test
     */
    public function user_can_fully_confirm_theis_email_address()
    {
        $this->post(route('register'), [
            'name'                  => 'John',
            'email'                 => 'john@example.com',
            'password'              => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

        $user = \App\User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads'));

        $this->assertTrue($user->fresh()->confirmed);
    }

    /**
     * @test
     */
    public function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm', ['token' => 'invalid']))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Unknow token.');
    }
}
