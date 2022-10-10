<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetalRates extends Model
{
    use HasFactory;
    protected $table = 'metal_rates';
    protected $fillable = ['metal_rates','carat_rates','created_at'];
    
  

}

