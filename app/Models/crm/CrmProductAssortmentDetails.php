<?php

namespace App\Models\crm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\customer\CustomerAddressType;
use App\Models\SalesOrderAddress;
class CrmProductAssortmentDetails extends Model
{
    use HasFactory;
    protected $table = 'crm_product_assortment_details';
    protected $guarded=[];
    
    
           
}
