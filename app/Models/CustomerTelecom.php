<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTelecom extends Model
{
    use HasFactory;
    protected $table = 'usr_telecom';
    protected $fillable = ['org_id','user_id', 'usr_telecom_typ_id', 'usr_telecom_value','country_code','is_active','is_deleted','created_by','updated_by','created_at','updated_at'];
}
