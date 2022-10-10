<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddressType extends Model
{
    use HasFactory;
    protected $table = 'usr_addr_typ_lk';
    protected $guarded=[];
}
