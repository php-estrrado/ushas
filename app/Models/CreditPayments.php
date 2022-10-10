<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class CreditPayments extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'credit_payments';

    protected $fillable = ['sale_id', 'transaction_id'];



}
