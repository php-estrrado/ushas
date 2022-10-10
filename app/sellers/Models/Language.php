<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Language extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'glo_lang_lk';

    protected $fillable = ['org_id', 'glo_lang_name', 'glo_lang_desc','glo_lang_code','orientation','is_active','created_by','updated_by'];
    
}
