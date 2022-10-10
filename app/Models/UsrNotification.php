<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsrNotification extends Model
{
    use HasFactory;
    protected $fillable = ['notify_from','user_type','notify_to','notify_type','title','description','icon','ref_id','ref_link',];
    
}

