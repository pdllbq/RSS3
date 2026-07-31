<?php

namespace Tests\Feature\Console;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_administrator_user(): void
    {
        $this->artisan('user:create-admin', [
            '--name' => 'Admin User',
            '--email' => 'admin@example.com',
            '--password' => 'secret-password',
        ])
            ->expectsOutput('Administrator [admin@example.com] created successfully.')
            ->assertExitCode(0);

        $user = User::query()->where('email', 'admin@example.com')->firstOrFail();

        $this->assertSame('Admin User', $user->name);
        $this->assertSame('admin', $user->role);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(Hash::check('secret-password', $user->password));
    }

    public function test_it_does_not_create_a_user_with_an_existing_email(): void
    {
        User::factory()->create(['email' => 'admin@example.com']);

        $this->artisan('user:create-admin', [
            '--name' => 'Admin User',
            '--email' => 'admin@example.com',
            '--password' => 'secret-password',
        ])
            ->expectsOutputToContain('email has already been taken')
            ->assertExitCode(1);

        $this->assertSame(1, User::query()->where('email', 'admin@example.com')->count());
    }
}