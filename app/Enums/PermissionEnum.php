<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case VIEW_ROLES = 'view_roles';
    case VIEW_ROLE = 'view_role';
    case CREATE_ROLE = 'create_roles';
    case UPDATE_ROLE = 'update_roles';
    case DELETE_ROLE = 'delete_roles';

    case VIEW_USERS = 'view_users';
    case VIEW_USER = 'view_user';
    case CHANFE_USER_ROLE = 'change_user_role';

    //Page permissions
    case Teacher = 'teacher';
    case Student = 'student';
    case Admin = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
