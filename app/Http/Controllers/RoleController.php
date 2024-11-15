<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:view role', ['only' => ['index']]);
    //     $this->middleware('permission:create role', ['only' => ['create', 'store', 'addPermissionToRole', 'givePermissionToRole']]);
    //     $this->middleware('permission:update role', ['only' => ['update', 'edit']]);
    //     $this->middleware('permission:delete role', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::select("SELECT id, name FROM roles");
            return DataTables::of($data)
                ->addIndexColumn()
                ->toJson();
        }

        return view('role-permission.role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = collect(DB::select("SELECT id, name FROM permissions"));
        return view('role-permission.role.create', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,name'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 403,
                    'data' => [
                        'message' => $validator->errors()
                    ]
                ]);
            }
            $data = $validator->validated();
            $permissions = $data['permissions'];
            $role = Role::create([
                'name' => $data['name']
            ]);
            $role->syncPermissions($permissions);
            return response()->json([
                'code' => 200,
                'data' => [
                    'message' => "Berhasil menambah data"
                ]
            ]);
        };
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permissions = Permission::get();
        $role = Role::findOrFail($id);
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('role-permission.role.detail', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json([
            'code' => 200,
            'data' => [
                'message' => 'Successfully Delete data'
            ]
        ]);
    }
}
