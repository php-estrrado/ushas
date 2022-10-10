<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCategory extends Model{
    use HasFactory;
    protected $table = 'usr_store_categories';
    protected $fillable = ['seller_id','store_id','category_id', 'is_active',];
    public function category(){ return $this->belongsTo(Category ::class, 'category_id'); }
}
