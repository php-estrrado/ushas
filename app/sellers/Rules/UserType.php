<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;
class UserType implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value){ 
        $res        =   DB::table('users')->where($attribute, $value)->first();
        if($res){ if($res->user_role > 2){ return false; }else{ return true; } }else{ return true; }
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Have no login permission to this account.';
    }
}
