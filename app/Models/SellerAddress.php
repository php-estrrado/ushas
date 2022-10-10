<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerAddress extends Model{
    use HasFactory;
    protected $table = 'usr_seller_address';
    protected $fillable = ['seller_id','addr_type_id', 'address', 'address2','city_id','state_id','country_id','zip_code','land_mark','latitude','longitude','is_active','is_deleted'];
    public function sellerMst(){ return $this->belongsTo(Seller ::class, 'seller_id'); }
    public function store($sellerId){ return Store::where('seller_id',$sellerId)->first(); }
    public function country(){ return $this->belongsTo(Country ::class, 'country_id'); }
    public function state(){ return $this->belongsTo(State ::class, 'state_id'); }
    public function city(){ return $this->belongsTo(City ::class, 'city_id'); }
}
