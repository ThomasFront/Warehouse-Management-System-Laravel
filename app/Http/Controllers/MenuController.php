<?php

namespace App\Http\Controllers;

use App\Http\Services\MenuService;
class MenuController extends Controller
{
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }
    public function index()
    {
        $menu = $this->menuService->getMenuBasedOnRole();
        return response()->json($menu);
    }
}
