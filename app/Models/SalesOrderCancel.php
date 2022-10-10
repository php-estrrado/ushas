<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SalesOrderCancel extends Authenticatable{
    use Notifiable;
    protected $fillable = ['sales_id','seller_id', 'created_by','customer_id','role_id','status',];
    
    public function order(){ return $this->belongsTo(SalesOrder ::class, 'sales_id'); }
    public function notes(){ return $this->hasMany(SalesOrderCancelNote ::class, 'cancel_id'); }
}
