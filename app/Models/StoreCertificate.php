<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCertificate extends Model{
    use HasFactory;
    protected $table = 'usr_store_certificates';
    protected $fillable = ['seller_id','store_id','file_path', 'file_type', 'is_active',];
    
}
