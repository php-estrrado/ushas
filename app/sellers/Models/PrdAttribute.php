<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrdAttribute extends Model
{
    use HasFactory;
    protected $fillable = ['name','name_cnt_id','type','data_type','required','filter','configur','is_active','created_by'];
    
    static function ValidateUnique($field,$post,$id) {
        $query      =   PrdAttribute::where($field,$post->$field)->where('is_deleted',0)->first();
        if($query)  {   if($query->id   !=  $id){ return 'This '.$field.' already has been taken'; } }else{ return false; }
    }
}

