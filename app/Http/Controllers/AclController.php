<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\AclRoleResource;
use App\Http\Resources\UserResource;
use App\Models\AclModelHasPermission;
use App\Models\AclModelHasRole;
use App\Models\AclRoleHasPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AclPermission;
use App\Models\AclRole;

class AclController extends Controller
{
    public function permissions()
    {
        $permissions = AclPermission::select('id', 'name')->get();
        return response()->json([
            'message' => __('acl.list_permission'),
            'data' => $permissions
        ], Response::HTTP_OK);
    }

    public function roles()
    {
        $roles = AclRole::select('id', 'name')->get();
        return response()->json([
            'message' => __('acl.list_role'),
            'data' => [
                $roles
            ]
        ], Response::HTTP_OK);
    }

    public function createRole(RoleRequest $request)
    {
        $msg = __('role.fail_register_role');
        $res = Response::HTTP_NOT_IMPLEMENTED;

        if ($role = AclRole::create(['name' => $request->all()['name']])) {
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
        $msg = __('role.fail_edit_role');
        $res = Response::HTTP_NOT_IMPLEMENTED;

        $role = AclRole::where('id', $id)->first();
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
        $msg = __('role.fail_delete_role');
        $res = Response::HTTP_NOT_FOUND;

        $role = AclRole::where('id', $id)->first();
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
        $msg = __('msg.not_found');
        $res = Response::HTTP_NOT_FOUND;

        $role = AclRole::select('id', 'name')
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
        $msg = __('acl.bad_request');
        $res = Response::HTTP_NOT_FOUND;

        $user = User::where('id', $userId)
            ->first();

        if ($user) {
            $permissions = AclPermission::whereIn('id', $request->all()['permissions'] ?? [])->get() ?? null;
            if ($user->syncPermissions($permissions)) {
                activity()->performedOn(new AclModelHasPermission())
                    ->causedBy(auth()->user())
                    ->withProperties(['attributes' => $permissions->select('id', 'name')->put('user_id', $userId)])
                    ->log('change permissions of user');
            }
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
        $msg = __('acl.bad_request');
        $res = Response::HTTP_NOT_FOUND;

        $role = AclRole::where('id', $roleId)
            ->first();

        if ($role) {
            $permissions = AclPermission::whereIn('id', $request->all()['permissions'] ?? [])->get() ?? null;
            if ($role->syncPermissions($permissions)) {
                activity()->performedOn(new AclRoleHasPermission())
                    ->causedBy(auth()->user())
                    ->withProperties(['attributes' => $permissions->select('id', 'name')->put('role_id', $roleId)])
                    ->log('change permissions of role');
            }
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
        $msg = __('acl.bad_request');
        $res = Response::HTTP_NOT_FOUND;

        $user = User::where('id', $userId)
            ->first();

        if ($user) {
            $roles = AclRole::whereIn('id', $request->all()['roles'] ?? [])->get() ?? null;
            if ($user->syncRoles($roles)) {
                activity()->performedOn(new AclModelHasRole())
                    ->causedBy(auth()->user())
                    ->withProperties(['attributes' => $roles->select('id', 'name')->put('user_id', $userId)])
                    ->log('change roles of user');
            }
            $msg = __('acl.success_edit_roles_to_user');
            $res = Response::HTTP_OK;
        }

        return response()->json([
            'message' => $msg,
            'data' => $user ? new UserResource($user->load('roles')) : null
        ], $res);
    }
}
