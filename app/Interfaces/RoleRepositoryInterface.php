<?php

namespace App\Interfaces;

interface RoleRepositoryInterface
{
    public function getAll();
    public function getById(string $id);
    public function create(array $data);
    public function update(array $data, string $id);
    public function delete(string $id);
}