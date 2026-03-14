<?php

namespace Tests\Feature;

use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_otp_and_reset_password(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('old-password'),
            'active' => true,
        ]);

        $resp = $this->post(route('password.email', absolute: false), [
            'email' => $user->email,
        ]);
        $resp->assertRedirect(route('password.reset', ['email' => $user->email], absolute: false));

        $row = DB::table('password_reset_tokens')->where('email', $user->email)->first();
        $this->assertNotNull($row);

        $sentOtp = null;
        Mail::assertSent(PasswordResetOtpMail::class, function (PasswordResetOtpMail $mail) use (&$sentOtp, $user) {
            $sentOtp = $mail->otp;

            return $mail->hasTo($user->email);
        });
        $this->assertNotNull($sentOtp);

        $resp2 = $this->post(route('password.store', absolute: false), [
            'email' => $user->email,
            'otp' => $sentOtp,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);
        $resp2->assertRedirect(route('login', absolute: false));

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
        $this->assertNull(DB::table('password_reset_tokens')->where('email', $user->email)->first());
    }

    public function test_wrong_otp_does_not_reset_password(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('old-password'),
            'active' => true,
        ]);

        $this->post(route('password.email', absolute: false), [
            'email' => $user->email,
        ]);

        $resp = $this->post(route('password.store', absolute: false), [
            'email' => $user->email,
            'otp' => '000000',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);
        $resp->assertSessionHasErrors(['otp']);

        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }
}
