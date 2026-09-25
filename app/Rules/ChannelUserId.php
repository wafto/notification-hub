<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ChannelUserId implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = preg_match('/^\+[1-9][0-9]{1,40}$/', strval($value)) === 1;

        if (!$valid) {
            $fail('The :attribute must be a valid numeric user id.');
        }
    }
}
