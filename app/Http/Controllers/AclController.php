<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\AclRoleResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AclController extends Controller
{
    public function permissions()
    {
        if (!auth()->user()->can(config('permission_names.acl.view'))) {
            return response()->json([
                'message' => __('msg.forbidden'),
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }

        $permissions = Permission::select('id', 'name')->get();
        return response()->json([
            'message' => __('acl.list_permission'),
            'data' => $permissions
        ], Response::HTTP_OK);
    }

    public function roles()
    {
        if (!auth()->user()->can(config('permission_names.acl.view'))) {
            return response()->json([
                'message' => __('msg.forbidden'),
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }

        $roles = Role::select('id', 'name')->get();
        return response()->json([
            'message' => __('acl.list_role'),
            'data' => [
                $roles
            ]
        ], Response::HTTP_OK);
    }

    public function createRole(RoleRequest $request)
    {
        if (!auth()->user()->can(config('permission_names.acl.add'))) {
            return response()->json([
                'message' => __('msg.forbidden'),
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }

        $msg = __('role.fail_register_role');
        $res = Response::HTTP_NOT_IMPLEMENTED;

        if ($role = Role::create(['name' => $request->all()['name']])) {
            $msg = __('acl.success_register_role');
            $res = Response::HTTP_CREATED;
        }

        return response()->json([
            'message' => $msg,
            'data' => [
                'id' => $role->id ?? null,
                'name' => $role->name ?? null,
                'created_at' => $role->created_at ?? null,
            ]
        ], $res);
    }

    public function editRole(RoleRequest $request, $id)
    {
        if (!auth()->user()->can(config('permission_names.acl.edit'))) {
            return response()->json([
                'message' => __('msg.forbidden'),
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }

        $msg = __('role.fail_edit_role');
        $res = Response::HTTP_NOT_IMPLEMENTED;

        $role = Role::where('id', $id)->first();
        if ($role && $role->update($request->all())) {
            $msg = __('acl.success_edit_role');
            $res = Response::HTTP_OK;
        }

        return response()->json([
            'message' => $msg,
            'data' => [
                'id' => $role->id ?? null,
                'name' => $role->name ?? null,
                'created_at' => $role->created_at ?? null,
            ]
        ], $res);
    }

    public function deleteRole($id)
    {
        if (!auth()->user()->can(config('permission_names.acl.delete'))) {
            return response()->json([
                'message' => __('msg.forbidden'),
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }

        $msg = __('role.fail_delete_role');
        $res = Response::HTTP_NOT_FOUND;

        $role = Role::where('id', $id)->first();
        if ($role && $role->delete()) {
            $msg = __('acl.success_delete_role');
            $res = Response::HTTP_OK;
        }

        return response()->json([
            'message' => $msg,
            'data' => null
        ], $res);
    }

    public function showRole($id)
    {
        if (!auth()->user()->can(config('permission_names.acl.view'))) {
            return response()->json([
                'message' => __('msg.forbidden'),
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }

        $msg = __('msg.not_found');
        $res = Response::HTTP_NOT_FOUND;

        $role = Role::select('id', 'name')
            ->where('id', $id)
            ->first();

        if ($role) {
            $msg = __('acl.role_data');
            $res = Response::HTTP_OK;
        }

        return response()->json([
            'message' => $msg,
            'data' => $role ? new AclRoleResource($role->load('permissions')) : null
        ], $res);
    }

    public function permissionsToUser(Request $request, $userId)
    {
        if (!auth()->user()->can(config('permission_names.acl.permission_to_user'))) {
            return response()->json([
                'message' => __('msg.forbidden'),
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }

        $msg = __('acl.bad_request');
        $res = Response::HTTP_NOT_FOUND;

        $user = User::where('id', $userId)
            ->first();

        if ($user) {
            $permissions = Permission::whereIn('id', $request->all()['permissions'] ?? [])->get() ?? null;
            $user->syncPermissions($permissions);
            $msg = __('acl.success_edit_permissions_to_user');
            $res = Response::HTTP_OK;
        }

        return response()->json([
            'message' => $msg,
            'data' => $user ? new UserResource($user->load('permissions')) : null
        ], $res);
    }

    public function permissionsToRole(Request $request, $roleId)
    {
        if (!auth()->user()->can(config('permission_names.acl.permission_to_role'))) {
            return response()->json([
                'message' => __('msg.forbidden'),
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }

        $msg = __('acl.bad_request');
        $res = Response::HTTP_NOT_FOUND;

        $role = Role::where('id', $roleId)
            ->first();

        if ($role) {
            $permissions = Permission::whereIn('id', $request->all()['permissions'] ?? [])->get() ?? null;
            $role->syncPermissions($permissions);
            $msg = __('acl.success_edit_permissions_to_role');
            $res = Response::HTTP_OK;
        }

        return response()->json([
            'message' => $msg,
            'data' => $role ? new AclRoleResource($role->load('permissions')) : null
        ], $res);
    }

    public function rolesToUser(Request $request, $userId)
    {
        if (!auth()->user()->can(config('permission_names.acl.role_to_user'))) {
            return response()->json([
                'message' => __('msg.forbidden'),
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }

        $msg = __('acl.bad_request');
        $res = Response::HTTP_NOT_FOUND;

        $user = User::where('id', $userId)
            ->first();

        if ($user) {
            $roles = Role::whereIn('id', $request->all()['roles'] ?? [])->get() ?? null;
            $user->syncRoles($roles);
            $msg = __('acl.success_edit_roles_to_user');
            $res = Response::HTTP_OK;
        }

        return response()->json([
            'message' => $msg,
            'data' => $user ? new UserResource($user->load('roles')) : null
        ], $res);
    }
}
