<?php

namespace App\Enum;

enum RolesEnum : string
{
    case admin = "ROLE_ADMIN";
    case user = "ROLE_USER";
    case manager = "ROLE_MANAGER";
}