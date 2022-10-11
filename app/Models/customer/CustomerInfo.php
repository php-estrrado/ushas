<?php

namespace App\Models\customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInfo extends Model
{
    use HasFactory;
    protected $table = 'usr_info';
    protected $fillable = ['org_id','user_id', 'usr_role_id', 'first_name','middle_name','last_name','address','pincode','country_id','state_id','city_id','gender','birthday','profile_image','stripe_id','pan_number','pan_file','gst_number','gst_file','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];
}
