<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrdFields extends Model
{
    use HasFactory;
    protected $fillable = ['name','name_cnt_id','type','data_type','required','filter','configur','variable_rate','is_active','created_by','seller_id','prd_id'];
    
    public function assfield($prdId, $fieldId){
        return   AssignedFields::where('prd_id',$prdId)->where('field_id',$fieldId)->where('is_deleted',0)->first();
    }


    static function ValidateUnique($field,$post,$id) {
        $query      =   PrdFields::where($field,$post->$field)->where('is_deleted',0)->first();
        if($query)  {   if($query->id   !=  $id){ return 'This '.$field.' already has been taken'; } }else{ return false; }
    }
}

