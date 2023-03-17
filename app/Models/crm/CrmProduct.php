<?php

namespace App\Models\crm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\customer\CustomerAddressType;
use App\Models\SalesOrderAddress;
use App\Models\PrdStock;
class CrmProduct extends Model
{
    use HasFactory;
    protected $table = 'crm_product';
    protected $guarded=[];
    
    public function childProducts(){ return $this->hasMany(CrmChildProductsMaster ::class, 'ProductID'); } 
    public function productAssortmentMaster(){ return $this->hasMany(CrmPartAssortmentMaster ::class, 'ProductID'); } 
    public function prdPrice(){ return $this->hasOne(CrmSalesPriceList ::class, 'Part_id')->where('DelStatus',0); } 
    public function Colour(){ return $this->belongsTo(CrmColour ::class, 'ColourID', 'Colourid'); }
    public function prdBranch(){ return $this->hasOne(CrmProductBranches ::class, 'Part_id'); } 
    public function prdStock($prdId){ 
    $in             =   (int)  PrdStock ::where('prd_id',$prdId)->where('type','add')->where('is_deleted',0)->sum('qty'); 
    $out            =   (int)  PrdStock ::where('prd_id',$prdId)->where('type','destroy')->where('is_deleted',0)->sum('qty'); 
    return ($in-$out);
    } 

}
