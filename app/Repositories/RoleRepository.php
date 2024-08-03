<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function getAll()
    {
        return Role::all();
    }

    public function getById(string $id)
    {
        return Role::findOrFail($id);
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $role = Role::create($data);

            $role->syncPermissions($data['permissions']);

            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function update(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);

            $role->update($data);

            $role->syncPermissions($data['permissions']);

            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);

            $role->delete();

            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }
}