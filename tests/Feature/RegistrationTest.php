<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Superadmin\App\Models\Tenant;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function it_registers_a_tenant_and_user_and_sends_verification_email()
    {
        Mail::fake();

        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123456789',
            'password' => 'secret',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(200)
            ->assertJson(['message' => 'A verification email has been sent to your email address.']);

        $this->assertDatabaseHas('tenants', [
            'owner_name' => $payload['name'],
            'owner_email' => $payload['email'],
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'tenant_id' => Tenant::where('owner_email', $payload['email'])->first()->id,
        ]);

        $user = User::where('email', $payload['email'])->first();

        Mail::assertSent(VerifyEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /** @test */
    public function it_returns_error_if_tenant_with_email_already_exists()
    {
        $existingTenant = factory(Tenant::class)->create();

        $payload = [
            'name' => 'John Doe',
            'email' => $existingTenant->owner_email,
            'phone' => '123456789',
            'password' => 'secret',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
            ->assertJson(['error' => 'Tenant with this email already exists.']);
    }

    /** @test */
    public function it_returns_error_if_user_with_email_already_exists()
    {
        $existingUser = factory(User::class)->create();

        $payload = [
            'name' => 'John Doe',
            'email' => $existingUser->email,
            'phone' => '123456789',
            'password' => 'secret',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
            ->assertJson(['error' => 'User with this email already exists.']);
    }

    /** @test */
    public function it_returns_error_if_user_with_phone_already_exists()
    {
        $existingUser = factory(User::class)->create();

        $payload = [
            'name' => 'John Doe',
            'email' => 'newuser@example.com',
            'phone' => $existingUser->phone,
            'password' => 'secret',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
            ->assertJson(['error' => 'User with this phone already exists.']);
    }
}
