<?php

const PERMISSIONS = [
    'user' => [
        'add' => 'add user',
        'edit' => 'edit user',
        'view' => 'view user',
        'delete' => 'delete user',
    ],
    'acl' => [
        'add' => 'add role',
        'edit' => 'edit role',
        'view' => 'view role/permission',
        'delete' => 'delete role',
        'permission_to_user' => 'assign/revoke permission to user',
        'permission_to_role' => 'assign/revoke permission to role',
        'role_to_user' => 'assign/revoke role to user',
    ],
    'todo_list' => [
        'add' => 'add todo',
        'edit' => 'edit todo',
        'view' => 'view todo',
        'delete' => 'delete todo',
        'change_state' => 'change state',
    ],
];
