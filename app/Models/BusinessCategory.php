<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BusinessCategory extends Model
{
    use HasFactory;
    protected $table = 'crm_business_category';
    protected $fillable = ['crm_id','name'];
    protected $guarded=[];
    
    
}
