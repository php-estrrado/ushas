<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrdPrice extends Model{
    use HasFactory;
    protected $fillable = ['prd_id','price','sale_price','sale_start_date','sale_end_date','created_by'];
    
}
