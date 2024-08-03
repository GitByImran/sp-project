<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function addUserData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $this->userRepository->createUser($request->all());

        $users = $this->userRepository->getAllUsers();

        return response()->json([
            'success' => 'User added successfully!',
            'users' => view('partials.user-list', compact('users'))->render()
        ]);
    }

    public function showForm()
    {
        $users = $this->userRepository->getAllUsers();
        return view('create', ['users' => $users]);
    }

    public function getUserData($id)
    {
        $user = $this->userRepository->getUserById($id);
        return response()->json($user);
    }

    public function updateUserData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $this->userRepository->updateUser($request->user_id, $request->all());

        $users = $this->userRepository->getAllUsers();

        return response()->json([
            'success' => 'User updated successfully!',
            'users' => view('partials.user-list', compact('users'))->render()
        ]);
    }

    public function deleteUser($id)
    {
        if ($this->userRepository->deleteUser($id)) {
            $users = $this->userRepository->getAllUsers();
            return response()->json([
                'success' => 'User deleted successfully!',
                'users' => view('partials.user-list', compact('users'))->render()
            ]);
        }

        return response()->json(['error' => 'User not found'], 404);
    }
}
