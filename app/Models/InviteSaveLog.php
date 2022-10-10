<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class InviteSaveLog extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'usr_invitations_log';

    protected $fillable = [ 'org_id', 'user_id','count','created_by','created_at'];



}
