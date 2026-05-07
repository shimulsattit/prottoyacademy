<?php

namespace App\Repositories\Interface;

interface CategoryRepositoryInterface
{
    public function all();
    public function onlyTrashed();
    public function getById(int $id);

    public function store($request);
    public function update($request, $id);
    public function delete(int $id);

    public function getAllCategoryIds(int $categoryId);

    public function getDeletedItemByUUID(string $uuid);
    public function restoreDeletedItemByUUID($model);
    public function forceDeleteItemByUUID($model);

}
