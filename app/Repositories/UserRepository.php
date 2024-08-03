<?php

namespace App\Repositories;

use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function createUser(array $data)
    {
        $imagePath = null;
        if (isset($data['image'])) {
            $imagePath = $data['image']->store('images', 'public');
        }

        return UserModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'image' => $imagePath,
        ]);
    }

    public function getAllUsers()
    {
        return UserModel::all();
    }

    public function getUserById($id)
    {
        return UserModel::find($id);
    }

    public function updateUser($id, array $data)
    {
        $user = UserModel::find($id);

        if (isset($data['image'])) {
            $imagePath = $data['image']->store('images', 'public');
            $user->image = $imagePath;
        }

        if (isset($data['password']) && $data['password']) {
            $user->password = Hash::make($data['password']);
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        return $user;
    }

    public function deleteUser($id)
    {
        $user = UserModel::find($id);

        if ($user) {
            return $user->delete();
        }

        return false;
    }
}
