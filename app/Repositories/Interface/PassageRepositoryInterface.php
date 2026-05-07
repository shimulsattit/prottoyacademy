<?php

namespace App\Repositories\Interface;

interface PassageRepositoryInterface
{
    public function all();
    public function onlyTrashed();
    public function getById(int $id);
    public function getByUUId(string $uuid);

    public function store($request);
    public function update($request, $id);
    public function delete(int $id);

    public function getDeletedItemByUUID(string $uuid);
    public function restoreDeletedItemByUUID($model);
    public function forceDeleteItemByUUID($model);
}