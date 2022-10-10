<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prd_Recent_View extends Model
{
    use HasFactory;
    protected $table = 'prd_recent_views';
    protected $fillable = ['user_id','prd_id','created_at','updated_at'];
}
