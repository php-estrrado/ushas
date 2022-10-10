<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotDelySlot extends Model{
    use HasFactory;
    protected $table = 'dly_spot_delivery_slots';
    protected $fillable = ['slot_name','name_cnt_id','start_time','end_time','is_active','created_by','updated_by'];
    
}
