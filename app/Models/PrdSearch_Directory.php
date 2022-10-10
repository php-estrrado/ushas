<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrdSearch_Directory extends Model
{
    use HasFactory;
    protected $table = 'prd_search_directory';
    protected $fillable = ['type_name','type_id','keyword','user_id','created_at','updated_at'];
}
