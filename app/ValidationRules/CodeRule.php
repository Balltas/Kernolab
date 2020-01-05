<?php
namespace App\ValidationRules;

use Illuminate\Contracts\Validation\Rule;

class CodeRule implements Rule
{
    public function passes($attribute, $value)
    {
        return $value == '111';
    }

    public function message()
    {
        return 'Incorrect confirmation code';
    }
}