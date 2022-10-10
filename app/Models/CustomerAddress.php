<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;
    protected $table = 'usr_address';
    protected $guarded=[];
    
    protected $fillable = ['org_id', 'user_id', 'usr_addr_typ_id', 'name', 'email','country_code', 'phone', 'country_id', 'state_id', 'city_id', 'address_1', 'address_2', 'pincode', 'latitude', 'longitude','street','house','neighborhood', 'is_default', 'is_active', 'is_deleted', 'created_by', 'updated_by', 'created_at', 'updated_at'];
    
    public function country(){ return $this->belongsTo(Country ::class, 'country_id'); }
    public function state(){ return $this->belongsTo(State ::class, 'state_id'); }
    public function city(){ return $this->belongsTo(City ::class, 'city_id'); }
    public function type(){ return $this->belongsTo(CustomerAddressType ::class, 'usr_addr_typ_id'); }
}
