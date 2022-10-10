<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class TaxValue extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'prd_tax_value';

    protected $fillable = ['org_id', 'tax_id', 'percentage','valid_from', 'valid_to','country_id','state_id','is_active','is_deleted'];

           
        static function getTaxVal($tax_id,$field){ 

        $TaxValue =TaxValue::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->where('tax_id', $tax_id)->first();
        if($TaxValue){ 
        $return_cont = $TaxValue->$field;
        return $return_cont;
        }else{ return false; }
        }
         
     

}
