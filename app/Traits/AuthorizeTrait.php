<?php

namespace App\Traits;

use App\Constants\RoleConstants;

trait AuthorizeTrait {
    public function AuthorizeAdmin() {
        foreach (auth()->user()->roles as $role) {
            if ($role->name === RoleConstants::ADMIN_ROLE) {
                return true;
            }
        }
        return false;
    }

    public function AuthorizeUser() {
        foreach (auth()->user()->roles as $role) {
            if ($role->name === RoleConstants::USER_ROLE) {
                return true;
            }
        }
        return false;
    }
}
