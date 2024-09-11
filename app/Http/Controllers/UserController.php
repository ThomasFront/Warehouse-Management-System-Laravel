<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Responses\ApiResponse;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $params = $this->getPaginationAndFilterParams($request);

        $categories = $this->userService->getUsers(
            $params['pageSize'],
            $params['sortField'],
            $params['sortOrder'],
            $params['filterField'],
            $params['filterValue'],
            $params['filterOperator']
        );

        return response()->json(new UserCollection($categories));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return $this->userService->deleteUser($user);
    }
}
