<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedSlot extends Model{
    use HasFactory;
    protected $table = 'dly_assigned_delivery_slots';
    protected $fillable = ['seller_id','store_id','slot_type','slot_id'];
    
}
