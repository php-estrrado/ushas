<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;
class Active implements Rule
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
        if($res){ if(DB::table('users')->where($attribute, $value)->first()->active == 0){ return false; }else{ return true; } }else{ return true; }
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your account is inactive. Please contact admin.';
    }
}
