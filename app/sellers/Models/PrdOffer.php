<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class PrdOffer extends Model{
    use HasFactory;
     protected $table = 'prd_special_offer';
    protected $fillable = ['org_id','prd_id','discount_value','discount_type','quantity_limit','valid_from','valid_to','created_by','is_active','is_deleted','updated_by'];
    public function product(){ return $this->belongsTo(Product ::class, 'prd_id'); }
    
    }
