<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSecurity extends Model
{
    use HasFactory;
    protected $table = 'usr_security';
    protected $guarded=[];
}
