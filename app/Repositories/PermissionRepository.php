<?php

namespace App\Repositories;

use App\Interfaces\PermissionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function getAll()
    {
        return Permission::all();
    }

    public function getById(string $id)
    {
        return Permission::findOrFail($id);
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $permission = Permission::create($data);

            DB::commit();

            return $permission;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function update(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            $permission = Permission::findOrFail($id);

            $permission->update($data);

            DB::commit();

            return $permission;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            Permission::findOrFail($id)->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }
}
