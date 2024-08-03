<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $authRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authRepository = new AuthRepository();
        $this->seed(\Database\Seeders\SuperAdminSeeder::class); // Run the seeder
    }

    /**
     * A test for successful login.
     */
    public function test_super_admin_login_successful()
    {
        // Find the user created by the seeder
        $user = User::where('email', 'superadmin@app.com')->first();
        $password = 'password';

        // Attempt to login
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Login Success',
                 ]);

        // Check if the user is authenticated
        $this->assertTrue(Auth::check());
        $this->assertAuthenticatedAs($user);
    }

    /**
     * A test for failed login.
     */
    public function test_super_admin_login_failed()
    {
        // Find the user created by the seeder
        $user = User::where('email', 'superadmin@app.com')->first();

        // Attempt to login with wrong password
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Unauthorized',
                 ]);

        // Check if the user is not authenticated
        $this->assertFalse(Auth::check());
    }
}
