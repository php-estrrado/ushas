<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Delivery extends Model
{
    use HasFactory;
    protected $table = 'delivery';
    protected $fillable = ['org_id','delivery_type_name','delivery_description','is_deleted','created_by','updated_by','created_at','updated_at'];

    static function getDeliveryTypes()
    {
        $types_list = Delivery::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        if($types_list)
        {
            $data = [];
            foreach($types_list as $row)
            {
                $data[$row->id]['id']                       = $row->id;
                $data[$row->id]['delivery_type_name']       = $row->delivery_type_name;
                $data[$row->id]['delivery_description']     = Delivery::get_content($row->delivery_description);
                // $data[$row->id]['delivery_description']  = $row->delivery_description;
                $data[$row->id]['is_active']                = $row->is_active;
                $data[$row->id]['is_deleted']               = $row->is_deleted;
                $data[$row->id]['created_at']               = $row->created_at; 
            }

            return $data;
        }
        else
        {
            return false;
        }
    }

    static function get_content($field_id)
    {
        $language = DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table = DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();

        if($content_table)
        {
            $return_cont = $content_table->content;
            return $return_cont;
        }
        else
        {
            return false;
        }
    }

    static function getDeliveryData($delivery_id)
    {
        $delivery_list = Delivery::where("id",$delivery_id)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();

        if($delivery_list)
        {
            $data = [];

            foreach($delivery_list as $row)
            {
                $data['id']                     =   $row->id;
                $data['cnt_id']                 =   $row->delivery_description;
                $data['delivery_type_name']     =   $row->delivery_type_name;
                $data['delivery_description']   =   Delivery::get_content($row->delivery_description);
                $data['delivery_charges']       =   $row->delivery_charges;
                $data['is_active']              =   $row->is_active;
                $data['is_deleted']             =   $row->is_deleted;
                $data['created_at']             =   $row->created_at;
            }

            return $data;
        }
        else
        {
            return false;
        }
    }

}
