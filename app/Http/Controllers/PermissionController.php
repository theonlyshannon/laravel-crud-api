<?php

namespace App\Http\Controllers;

use App\Interfaces\PermissionRepositoryInterface;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;

class PermissionController extends Controller
{
    private $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;

        $this->middleware('permission:permission-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:permission-create', ['only' => ['store']]);
        $this->middleware('permission:permission-edit', ['only' => ['update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = $this->permissionRepository->getAll();

        return ResponseHelper::jsonResponse(true, 'Hak akses berhasil dimuat', $permissions, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $data = $request->validated();

        try {
            $permission = $this->permissionRepository->create($data);

            return ResponseHelper::jsonResponse(true, 'Hak akses berhasil disimpan', $permission, 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Hak akses gagal disimpan', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = $this->permissionRepository->getById($id);

        return ResponseHelper::jsonResponse(true, 'Hak akses berhasil dimuat', $permission, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $permission = $this->permissionRepository->update($data, $id);

            return ResponseHelper::jsonResponse(true, 'Hak akses berhasil diperbarui', $permission, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Hak akses gagal diperbarui', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->permissionRepository->delete($id);

            return ResponseHelper::jsonResponse(true, 'Hak akses berhasil dihapus', null, 204);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Hak akses gagal dihapus', $e->getMessage(), 500);
        }        
    }
}
