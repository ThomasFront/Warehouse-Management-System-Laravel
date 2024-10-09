<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Http\Services\AuthService;
use App\Http\Services\MenuService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class MenuServiceTest extends TestCase
{
    use RefreshDatabase;

    protected MenuService $menuService;

    protected AuthService $authService;

    public function setUp(): void
    {
        parent::setUp();

        $this->authService = Mockery::mock(AuthService::class);
        $this->menuService = new MenuService($this->authService);
    }

    public function test_get_menu_for_admin()
    {
        $user = User::factory()->create([
            'role' => UserRole::ADMIN
        ]);

        $this->authService->shouldReceive('me')
            ->andReturn($user);

        $menu = $this->menuService->getMenuBasedOnRole();

        $this->assertCount(7, $menu);

        $usersSectionLinks = array_filter($menu, function ($item) {
            return $item['id'] === 15;
        });

        $links = reset($usersSectionLinks)['sublinks'];

        $this->assertCount(2, $links);
        $this->assertEquals('User list', $links[0]['name']);
        $this->assertEquals('Add user', $links[1]['name']);
    }

    public function test_get_menu_for_user()
    {
        $user = User::factory()->create([
            'role' => UserRole::USER
        ]);

        $this->authService->shouldReceive('me')
            ->andReturn($user);

        $menu = $this->menuService->getMenuBasedOnRole();

        $this->assertCount(7, $menu);

        $usersSectionLinks = array_filter($menu, function ($item) {
            return $item['id'] === 15;
        });

        $links = reset($usersSectionLinks)['sublinks'];

        $this->assertCount(1, $links);
        $this->assertEquals('User list', $links[0]['name']);
    }

}
