<?php

namespace App\Repositories\Interface;

interface AdminRepositoryInterface
{
    public function getAll();
    public function getById(int $id);

    public function create(array $data, string $avatar);
    public function update(int $id, array $dataca);
    public function delete(int $id);
}
