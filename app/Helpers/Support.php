<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Support
{
    public static function isSuper(): bool
    {
        return Auth::user()->is_super == 1;
    }

    public static function disableRoleIds(): array
    {
        return [1, 2, 3];
    }

    public static function checkPermission(string $permission): bool
    {
        return Auth::user()->is_super == 1 || Auth::user()->hasPermissionTo($permission);
    }

    static function getIdArray($array = [], $keyOption = 'children', $id = [])
    {
        foreach ($array as $key => $value) {
            $id[] = $value['id'];
            if ($value[$keyOption] != '') {
                return self::getIdArray($value[$keyOption], $keyOption, $id);
            }
        }
        return $id;
    }

    public static function makeSafe($data)
    {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return addslashes($data);
    }
}