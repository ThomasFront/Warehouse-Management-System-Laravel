<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Http\Requests\EditUserProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Services\AuthService;
use App\Http\Services\FilterService;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Mockery;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected FilterService $filterService;

    protected AuthService $authService;

    public function setUp(): void
    {
        parent::setUp();

        $this->filterService = Mockery::mock(FilterService::class);
        $this->authService = Mockery::mock(AuthService::class);

        $this->userService = new UserService($this->filterService, $this->authService);
    }

    public function test_get_users()
    {
        User::factory()->count(5)->create();

        $this->filterService
            ->shouldReceive('applyFilters')
            ->once();

        $pageSize = 2;
        $sortField = 'name';
        $sortOrder = 'asc';
        $filterField = 'name';
        $filterValue = 'email';
        $filterOperator = 'equals';

        $result = $this->userService->getUsers($pageSize, $sortField, $sortOrder, $filterField, $filterValue, $filterOperator);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount($pageSize, $result);
        $this->assertEquals(5, $result->total());
    }

    public function test_count_users()
    {
        User::factory()->count(5)->create();

        $count = $this->userService->countUsers();

        $this->assertEquals(5, $count);
    }

    public function test_delete_own_account()
    {
        $user = User::factory()->create();

        $this->authService->shouldReceive('me')
            ->once()
            ->andReturn($user);

        $response = $this->userService->deleteUser($user);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('You cannot delete your own account', $response->getData()->error->message);
    }

    public function test_delete_other_user_account()
    {
        $currentUser = User::factory()->create();
        $userToDelete = User::factory()->create();

        $this->authService->shouldReceive('me')
            ->once()
            ->andReturn($currentUser);

        $response = $this->userService->deleteUser($userToDelete);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('User deleted successfully.', $response->getData()->data->message);
    }

    public function test_update_user()
    {
        $user = User::factory()->create();

        $userData = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'role' => UserRole::USER,
            'password' => 'password123',
            'avatarUrl' => 'path/to/avatar.png',
        ];

        $request = Mockery::mock(UpdateUserRequest::class);
        $request->shouldReceive('validated')
            ->andReturn($userData);

        $updatedUser = $this->userService->updateUser($request, $user);

        $this->assertEquals($userData['first_name'], $updatedUser->first_name);
        $this->assertEquals($userData['last_name'], $updatedUser->last_name);
    }

    public function test_edit_profile_updates_color_theme_and_avatar()
    {
        $user = User::factory()->create([
            'color_theme' => '#FFFFFF',
            'avatar' => null,
        ]);

        $request = Mockery::mock(EditUserProfileRequest::class);
        $request->shouldReceive('validated')
            ->andReturn([
                'color_theme' => '#000000',
                'avatarUrl' => 'path/to/new_avatar.png',
            ]);

        $this->userService->editProfile($request, $user);

        $this->assertEquals('#000000', $user->color_theme);

        $this->assertEquals(Storage::url('path/to/new_avatar.png'), $user->avatar);

        Storage::disk('local')->assertMissing($user->avatar);
    }

    public function test_edit_profile_without_avatar()
    {
        $user = User::factory()->create([
            'color_theme' => '#FFFFFF',
            'avatar' => null,
        ]);

        $request = Mockery::mock(EditUserProfileRequest::class);
        $request->shouldReceive('validated')
            ->andReturn([
                'color_theme' => '#000000',
            ]);

        $this->userService->editProfile($request, $user);

        $this->assertEquals('#000000', $user->color_theme);

        $this->assertNull($user->avatar);
    }
}
