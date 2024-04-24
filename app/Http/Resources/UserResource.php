<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'roles' => $this->whenLoaded('roles', function () {
                if ($this->roles->isNotEmpty()) {
                    return AclRoleResource::collection($this->roles);
                }
                return null;
            }),
            'permissions' => $this->whenLoaded('permissions', function () {
                if ($this->permissions->isNotEmpty()) {
                    return AclPermissionResource::collection($this->permissions);
                }
                return null;
            }),
        ];
    }
}
