<?php

namespace App\Rules;

use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasStaffRole implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        if ($value == null) return;

        $staff = User::role(Role::ROLE_STAFF)->find($value);
        if (!$staff) $fail('Staff data invalid');
    }
}
