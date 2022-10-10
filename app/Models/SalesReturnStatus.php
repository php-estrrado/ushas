<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnSatus extends Model
{
    use HasFactory;
    protected $fillable     =   ['identifier','status','desc','short',];
    
}

