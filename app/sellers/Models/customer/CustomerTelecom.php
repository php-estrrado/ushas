<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTelecom extends Model
{
    use HasFactory;
    protected $table = 'usr_telecom';
    protected $guarded=[];
}
