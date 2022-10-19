<?php

namespace App\Models\crm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\customer\CustomerAddressType;
use App\Models\SalesOrderAddress;
class CrmProduct extends Model
{
    use HasFactory;
    protected $table = 'crm_product';
    protected $guarded=[];
    
    public function childProducts(){ return $this->hasMany(CrmChildProductsMaster ::class, 'ProductID'); } 
    public function productAssortmentMaster(){ return $this->hasMany(CrmPartAssortmentMaster ::class, 'ProductID'); } 
    public function prdPrice(){ return $this->hasOne(CrmSalesPriceList ::class, 'Part_id')->where('DelStatus',0); }         
}
