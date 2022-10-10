<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserRole extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'usr_role_lk';

    protected $fillable = ['org_id', 'usr_role_name', 'usr_role_desc','is_active','created_by','is_deleted'];
    
}
