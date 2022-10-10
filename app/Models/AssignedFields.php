<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedFields extends Model{
    use HasFactory;
    protected $table = 'prd_assigned_fields';
    protected $fillable = ['prd_id','field_id','field_val_id','field_value','created_by',];
    
    public function fieldValue(){ return $this->belongsTo(PrdFieldsValue ::class, 'field_val_id'); }
    
    /***API***/
    public function PrdField(){ return $this->belongsTo(PrdFields::class, 'field_id'); }
    public function PrdField_value(){ return $this->belongsTo(PrdFieldsValue::class, 'field_val_id'); }
    public function PrdField_in($id){ return PrdFields::where('id', $id)->first(); }
    public function prdPrice(){ return $this->belongsTo(PrdPrice ::class, 'prd_id')->latest(); }
    public function Product(){ return $this->belongsTo(Product::class, 'prd_id'); }
    public function prdStock($prdId){ 
        $in             =   (int)PrdStock ::where('prd_id',$prdId)->where('type','add')->where('is_deleted',0)->sum('qty'); 
        $out            =   (int)PrdStock ::where('prd_id',$prdId)->where('type','destroy')->where('is_deleted',0)->sum('qty'); 
        return ($in-$out);
    }
    public function PrdField_value_name(){ return $this->belongsTo(PrdFieldsValue::class, 'field_val_id'); }
}
