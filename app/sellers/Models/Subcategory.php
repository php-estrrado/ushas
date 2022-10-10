<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Subcategory extends Model
{
    use HasFactory;
    protected $table = 'subcategory';
    public $primaryKey = 'subcategory_id';
    protected $guarded=[];
    protected $fillable = ['category_id','sub_name_cid','subcategory_name','slug','desc_cid','parent','level','image','is_active','created_by'];
    
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
