<?php

namespace App\Http\Services;

class MenuService
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function getMenuBasedOnRole()
    {
        $isAdmin = $this->authService->me()->isAdmin();

        $menu = $this->getDefaultMenu();

        if ($isAdmin) {
            $this->addAdminLinks($menu);
        }

        return $menu;
    }

    private function getDefaultMenu()
    {
        $userId = $this->authService->me()->id;

        return [
            [
                'id' => 1,
                'name' => 'Dashboard',
                'url' => '/dashboard'
            ],
            [
                'id' => 2,
                'name' => 'Products',
                'sublinks' => [
                    [
                        'id' => 3,
                        'name' => 'Product list',
                        'url' => '/product-list'
                    ],
                    [
                        'id' => 4,
                        'name' => 'Add product',
                        'url' => '/product-add'
                    ],
                ]
            ],
            [
                'id' => 5,
                'name' => 'Categories',
                'sublinks' => [
                    [
                        'id' => 6,
                        'name' => 'Category list',
                        'url' => '/category-list'
                    ],
                    [
                        'id' => 7,
                        'name' => 'Add category',
                        'url' => '/category-add'
                    ],
                ]
            ],
            [
                'id' => 8,
                'name' => 'Sales',
                'sublinks' => [
                    [
                        'id' => 9,
                        'name' => 'Sales history',
                        'url' => '/sales-history'
                    ],
                    [
                        'id' => 10,
                        'name' => 'Add sale',
                        'url' => '/sales-add'
                    ],
                ]
            ],
            [
                'id' => 11,
                'name' => 'My profile',
                'url' => "/user/{$userId}"
            ],
            [
                'id' => 12,
                'name' => 'Messages',
                'sublinks' => [
                    [
                        'id' => 13,
                        'name' => 'Message list',
                        'url' => '/message-list'
                    ],
                    [
                        'id' => 14,
                        'name' => 'Add message',
                        'url' => '/message-add'
                    ],
                ]
            ],
            [
                'id' => 15,
                'name' => 'Users',
                'sublinks' => [
                    [
                        'id' => 16,
                        'name' => 'User list',
                        'url' => '/user-list'
                    ],
                ]
            ]
        ];
    }

    private function addAdminLinks(array &$menu)
    {
        foreach ($menu as &$menuItem) {
            if ($menuItem['id'] === 15) {
                $menuItem['sublinks'][] = [
                    'id' => 17,
                    'name' => 'Add user',
                    'url' => '/user-add'
                ];
                break;
            }
        }
    }
}
