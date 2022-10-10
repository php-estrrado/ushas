<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class StripePayments extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'stripe_payments';

    protected $fillable = [ 'job','sale_id', 'response','exception'];



}
