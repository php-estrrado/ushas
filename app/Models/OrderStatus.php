<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class OrderStatus extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'sales_order_status';

    protected $fillable = ['org_id', 'sale_id', 'status','created_by', 'updated_by'];

           
    public function statusVal(){ return $this->belongsTo(SalesOrderStatusList ::class, 'status'); }
     

}
