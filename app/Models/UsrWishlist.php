<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsrWishlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','prd_id',];
    
     public function product(){ return $this->belongsTo(Product::class, 'prd_id'); }
        
        
}
