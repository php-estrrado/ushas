<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SalesOrderCancelNote extends Authenticatable{
    use Notifiable;
    protected $fillable = ['cancel_id','created_by', 'role_id', 'title','note','response',];
    
}
