<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LoyaltyRedeemed extends Model
{
    use HasFactory;
    protected $table = 'loyalty_redeemed_rewards';
    protected $fillable = ['user_id','reward_id','redeemed_quantity','redemption_date','status'];
    protected $guarded=[];
	
	public function reward(){ return $this->belongsTo(LoyaltyRewards ::class, 'reward_id'); }
	public function userInfo(){ return $this->belongsTo(customer\CustomerInfo ::class, 'user_id'); }
	
	
    
}
