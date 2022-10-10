<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telecom extends Model{
    use HasFactory;
    protected $table = 'telecom_type_lk';
    protected $fillable = ['name','desc', 'is_active'];
    
}
