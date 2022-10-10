<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'usr_mst';
    protected $guarded=[];
    
    public function info(){ return $this->hasOne(CustomerInfo ::class, 'user_id'); }
    public function teleEmail(){ return $this->belongsTo(CustomerTelecom ::class, 'email'); }
    public function telePhone(){ return $this->belongsTo(CustomerTelecom ::class, 'phone'); }
    public function custEmail($id){ $email  =   CustomerTelecom::where('id',$id)->first(); if($email){ return $email->usr_telecom_value; }else{ return ""; } }
    public function custPhone($id){ $phone  =   CustomerTelecom::where('id',$id)->first(); if($phone){ return $phone->usr_telecom_value; }else{ return ""; } }

}
