<?php

namespace App\Repositories\Interface;

interface HomeCarouselRepositoryInterface
{
    public function all();
    public function getById(int $id);

    public function store($request);
    public function update($request, $id);
    public function delete(int $id);
}