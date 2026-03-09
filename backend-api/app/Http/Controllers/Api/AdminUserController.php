<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class AdminUserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::latest()->paginate(10);

        return ApiResponse::success(
            UserResource::collection($users),
            'Daftar user'
        );
    }

     public function show(User $user)
    {
        return ApiResponse::success(
            new UserResource($user)
        );
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $user = $this->userService->createCourier(
            $request->validated()
        );

        return ApiResponse::success(
            new UserResource($user),
            'User berhasil dibuat'
        );
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $updated = $this->userService->update(
            $user,
            $request->validated()
        );

        return ApiResponse::success(
            new UserResource($updated),
            'User berhasil diperbarui'
        );
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $this->userService->destroy($user);

        return ApiResponse::success(
            null,
            'User berhasil dihapus'
        );
    }
}
