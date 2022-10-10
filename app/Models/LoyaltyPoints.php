<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LoyaltyPoints extends Model
{
    use HasFactory;
    protected $table = 'loyalty_points';
    protected $fillable = ['points','value', 'is_active'];
    protected $guarded=[];
    
    
}
