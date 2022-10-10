<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model{
    use HasFactory;
    protected $table = 'usr_stores';
    protected $fillable = [
        'seller_id','business_name','store_name','licence', 'address', 'address2','latitude','longitude','country_id','state_id','city_id','zip_code','post_office','certificate','certificate_end_date','logo',
        'banner','commission','incharge_name','incharge_phone','incharge_isd_code','ship_method','pack_option','pickup_option','is_pickup_chrge','pickup_chrge',
        'discount','limit_type','purchase_limit','tracking_link','is_active','service_status'
    ];
    public function storeCategories(){ return $this->hasMany(StoreCategory ::class, 'store_id'); }
    public function country(){ return $this->belongsTo(Country ::class, 'country_id'); }
    public function state(){ return $this->belongsTo(State ::class, 'state_id'); }
    public function city(){ return $this->belongsTo(City ::class, 'city_id'); }

    
}
