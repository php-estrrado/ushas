<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $fillable = ['country_id','currency_name','currency_code', 'is_deleted','is_default','is_active', 'created_at','updated_at',];

    public function country(){ return $this->belongsTo(Country::class, 'country_id'); }
}
