<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function createUser(array $data);
    public function getAllUsers();
    public function getUserById($id);
    public function updateUser($id, array $data);
    public function deleteUser($id);
}
