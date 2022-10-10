<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productvisitor extends Model
{
    use HasFactory;
    protected $table = 'usr_product_visitor';
    protected $fillable = ['user_id','prd_id','device_id','os','created_at','updated_at'];
    
    public function product(){ return $this->belongsTo(Product ::class, 'prd_id'); }

    static function get_content($field_id){ 

        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();
        if($content_table){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }
        else
            { return false; }
        }
}
