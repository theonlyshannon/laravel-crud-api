<?php

namespace App\Http\Controllers;

use App\Interfaces\RoleRepositoryInterface;
use App\Helpers\ResponseHelper;
use App\Http\Resources\RoleResource;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;

        $this->middleware('permission:role-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:role-create', ['only' => ['store']]);
        $this->middleware('permission:role-edit', ['only' => ['update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->roleRepository->getAll();

        return ResponseHelper::jsonResponse(true, 'Role berhasil dimuat', RoleResource::collection($roles), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();

        try {
            $role = $this->roleRepository->create($data);

            return ResponseHelper::jsonResponse(true, 'Role berhasil disimpan', new RoleResource($role), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Role gagal disimpan', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = $this->roleRepository->getById($id);

        return ResponseHelper::jsonResponse(true, 'Role berhasil dimuat', new RoleResource($role), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $role = $this->roleRepository->update($data, $id);

            return ResponseHelper::jsonResponse(true, 'Role berhasil diperbarui', new RoleResource($role), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Role gagal diperbarui', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->roleRepository->delete($id);

            return ResponseHelper::jsonResponse(true, 'Role berhasil dihapus', null, 204);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Role gagal dihapus', $e->getMessage(), 500);
        }
    }
}
