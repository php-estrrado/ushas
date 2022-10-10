<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BusinessCategoryOrder extends Model
{
    use HasFactory;
    protected $table = 'crm_category_order';
    protected $fillable = ['business_category_id','category_id','sort_order','is_active','is_deleted'];
    protected $guarded=[];
    
    
}
