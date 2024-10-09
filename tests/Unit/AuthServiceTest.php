<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Mockery;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    public function setUp(): void
    {
        parent::setUp();

        $this->authService = new AuthService();
    }

    public function test_register_creates_user()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'role' => UserRole::USER,
            'password' => 'password123',
        ];

        $request = Mockery::mock(RegisterUserRequest::class);
        $request->shouldReceive('validated')->andReturn($userData);

        $this->authService->register($request);

        $user = User::where('email', $userData['email'])->first();

        $this->assertNotNull($user);
        $this->assertEquals($userData['first_name'], $user->first_name);
        $this->assertEquals($userData['last_name'], $user->last_name);
        $this->assertEquals($userData['role'], $user->role);
        $this->assertTrue(Hash::check($userData['password'], $user->password));
    }

    public function test_register_creates_user_with_avatar()
    {
        $userData = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'role' => UserRole::USER,
            'password' => 'password123',
            'avatarUrl' => 'path/to/avatar.png',
        ];

        $request = Mockery::mock(RegisterUserRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($userData);

        Storage::fake('public');

        $this->authService->register($request);

        $user = User::where('email', $userData['email'])->first();
        $this->assertNotNull($user);
        $this->assertEquals(Storage::url($userData['avatarUrl']), $user->avatar);
    }

    public function test_login_returns_token_on_success()
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        Auth::shouldReceive('attempt')
            ->with($credentials)
            ->once()
            ->andReturn(['token' => 'mocked_jwt_token']);

        $result = $this->authService->login($credentials);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals('mocked_jwt_token', $result['token']['token']);
    }

    public function test_login_returns_null_on_failure()
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'wrong_password',
        ];

        Auth::shouldReceive('attempt')
            ->with($credentials)
            ->once()
            ->andReturn(null);

        $result = $this->authService->login($credentials);

        $this->assertNull($result);
    }

    public function test_me_returns_authenticated_user()
    {
        $user = User::factory()->create();

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        $authenticatedUser = $this->authService->me();

        $this->assertInstanceOf(User::class, $authenticatedUser);
        $this->assertEquals($user->id, $authenticatedUser->id);
    }

    public function test_logout_calls_auth_logout()
    {
        Auth::shouldReceive('logout')
            ->once();

        $this->authService->logout();
    }

    public function test_refresh_token_returns_user_and_new_token()
    {
        $user = User::factory()->create();
        $newToken = 'new.jwt.token';

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        Auth::shouldReceive('refresh')
            ->once()
            ->andReturn($newToken);

        $result = $this->authService->refreshToken();

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user, $result['user']);
        $this->assertEquals($newToken, $result['token']);
    }
}
