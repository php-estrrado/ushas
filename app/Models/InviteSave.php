<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class InviteSave extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'usr_invitations';

    protected $fillable = [ 'org_id', 'user_id','coupon_code','count','is_valid','is_active','is_deleted','created_by','updated_by'];



}
