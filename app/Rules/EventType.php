<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class EventType implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = preg_match('/^[A-Z0-9_]+$/', strval($value)) === 1;

        if (!$valid) {
            $fail('The :attribute must have a valid naming UPPER_SNAKE_CASE.');
        }
    }
}
