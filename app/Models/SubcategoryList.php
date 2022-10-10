<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcategoryList extends Model
{
    use HasFactory;
    protected $table = 'subcategory_list';
    protected $fillable = ['name','code','is_active'];
    
  

}

