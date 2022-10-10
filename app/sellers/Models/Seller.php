<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Seller extends Authenticatable{
    use Notifiable;
    protected $table = 'usr_seller_mst';
    protected $fillable = ['org_id','username', 'ref_code', 'password',];
    protected $hidden = ['password',];
    public function sellerInfo(){ return $this->hasOne(SellerInfo ::class, 'seller_id'); }
    public function store(){ return $this->hasOne(Store ::class, 'seller_id'); }
    public function security(){ return $this->hasOne(SellerSecurity ::class, 'seller_id'); }
    public function teleEmail(){ return $this->belongsTo(SellerTelecom ::class, 'email'); }
    public function telePhone(){ return $this->belongsTo(SellerTelecom ::class, 'phone'); }
}
