<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;
class Disabled implements Rule
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
        $today      =   date('Ymd');
        if($res){ 
            $expiry =   date('Ymd',strtotime($res->expire_date));
            if($today > $expiry){ return false; }else{ return true; } 
        }else{ return true; }
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your account has been expired. Please contact admin to renew your account.';
    }
}
