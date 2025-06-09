<?php

namespace Core\Models\Enums;

enum UserStatus: string {
    case ACTIVE = "ACTIVE";
    case INACTIVE = "INACTIVE";
}