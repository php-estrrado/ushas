<?php

namespace App\Models\crm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\customer\CustomerAddressType;
use App\Models\SalesOrderAddress;
class CrmPartAssortmentMaster extends Model
{
    use HasFactory;
    protected $table = 'crm_part_assortment_master';
    protected $guarded=[];
    
    public function Assortments(){ return $this->belongsTo(CrmAssortmentMaster ::class, 'AssortmentID', 'AssortmentID'); } 
    public function AssortmentsDetail(){ return $this->hasMany(CrmPartAssortmentDetails ::class, 'ProductAssortmentID','ProductAssortmentID')->where('is_deleted',0); } 
           
}
