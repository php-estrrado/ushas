<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class UserProductVisit extends Model{
    use HasFactory;
     protected $table = 'usr_product_visitor';
    protected $fillable = ['prd_id','user_id','os','device_id','created_at','updated_at'];
     
    
}
