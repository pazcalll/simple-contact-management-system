<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasCorrectUpline implements ValidationRule
{
    public function __construct(
        private int|string|null $uplineId
    ){}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        if ($value == null) return;

        $upline = User::find($this->uplineId);
        $currentUser = User::find($value);

        if ($upline->id != $currentUser->upline_id) $fail('Incorrect upline');
    }
}
