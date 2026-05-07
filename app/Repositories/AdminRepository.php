<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Repositories\Interface\AdminRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminRepository implements AdminRepositoryInterface
{
    public function create($data, $avatar)
    {
        return Admin::create([
            'uuid' => (string) Str::uuid(),
            'surname' => $data['surname'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'avatar' => $avatar,
            'email' => $data['email'],
            'password' => Hash::make($data['new_password']),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'status' => $data['status']
        ]);
    }

    public function getAll()
    {
        return Admin::all();
    }

    public function getById($id)
    {
        return Admin::find($id);
    }

    public function update($id, $data)
    {
        $user = Admin::find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }

    public function delete($id)
    {
        $user = Admin::find($id);
        if ($user) {
            $user->delete();
            return true;
        }
        return false;
    }
}
