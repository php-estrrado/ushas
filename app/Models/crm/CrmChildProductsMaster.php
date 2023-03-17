<?php

namespace App\Models\crm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\customer\CustomerAddressType;
use App\Models\SalesOrderAddress;
use App\Models\PrdStock;
class CrmChildProductsMaster extends Model
{
    use HasFactory;
    protected $table = 'crm_childproductsmaster';
    protected $guarded=[];
    
  public function SizeInfo(){ return $this->belongsTo(CrmSize ::class, 'SizeID', 'SizeID'); } 
  public function ColorInfo(){ return $this->belongsTo(CrmColour ::class, 'ColourID', 'ColourID'); }   
  public function prdStock($prdId){ 
    $in             =   (int)  PrdStock ::where('prd_id',$prdId)->where('product_type','parent')->where('type','add')->where('is_deleted',0)->sum('qty'); 
    $out            =   (int)  PrdStock ::where('prd_id',$prdId)->where('product_type','parent')->where('type','destroy')->where('is_deleted',0)->sum('qty'); 
    return ($in-$out);
    }   

    public function ChildPrdStock($child,$prdId){ 
    $in             =   (int)  PrdStock ::where('child_id',$child)->where('product_type','child')->where('product_type','child')->where('type','add')->where('is_deleted',0)->sum('qty'); 
    $out            =   (int)  PrdStock ::where('child_id',$child)->where('product_type','child')->where('product_type','child')->where('type','destroy')->where('is_deleted',0)->sum('qty'); 
    return ($in-$out);
    }        
}
