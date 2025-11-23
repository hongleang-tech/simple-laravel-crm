<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $input = (string) $value;

        $normalize = preg_replace('/[\s\-\(\)]/', '', $input);

        $isValid = preg_match('/^(\+61|0)4\d{8}$/', $normalize);

        if (! $isValid) {
            $fail('The :attribute must be a valid Australia mobile number.');
        }
    }
}
