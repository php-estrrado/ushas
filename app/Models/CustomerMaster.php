<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMaster extends Model
{
    use HasFactory;
    protected $table = 'usr_mst';

    protected $fillable = ['org_id','username', 'email', 'phone','ref_code','invited_by','is_active','is_deleted','crm_unique_id','odoo_id','usr_platform','created_by','updated_by','created_at','updated_at','parent_id','is_approved'];

    
    public function info(){ return $this->hasOne(CustomerInfo ::class, 'user_id'); }
    public function teleEmail(){ return $this->belongsTo(CustomerTelecom ::class, 'email'); }
    public function telePhone(){ return $this->belongsTo(CustomerTelecom ::class, 'phone'); }
    public function custEmail($id){ $email  =   CustomerTelecom::where('id',$id)->first(); if($email){ return $email->usr_telecom_value; }else{ return ""; } }
    public function custPhone($id){ $phone  =   CustomerTelecom::where('id',$id)->first(); if($phone){ return $phone->usr_telecom_value; }else{ return ""; } }
    public function custPhonecode($id){ $phone  =   CustomerTelecom::where('id',$id)->first(); if($phone){ return $phone->country_code; }else{ return ""; } }
}
