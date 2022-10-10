<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BannerType extends Model
{
    use HasFactory;
    protected $table = 'banners';

     static function getBannerTypes(){ 
        $types_list = BannerType::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();

        if($types_list){ 
        $data               =   [];
        foreach($types_list    as  $row){
        $data[$row->id]['id']        =   $row->id;
        $data[$row->id]['identifier']       = $row->identifier;
        $data[$row->id]['title']       =    $row->title;
        $data[$row->id]['desc']       =    $row->desc;
        $data[$row->id]['is_active']       =   $row->is_active; 
        $data[$row->id]['is_deleted']       =   $row->is_deleted;
        $data[$row->id]['created_at']       =   $row->created_at; 
        }

        return $data;
        }else{ return false; }

        }
        static function bannerType($sl_id){
            $type = BannerType::where("id",$sl_id)->first();
            if($type){
                return $type->title;
            }else {
                return "";
            }

        }
    
       
}
