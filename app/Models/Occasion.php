<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class Occasion extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'occasion_wear';

    protected $fillable = ['occasion', 'occasion_name_cid','image','is_active'];

        static function getOccasions(){ 
        $occasion_list = Occasion::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id','desc')->get();
     
        if($occasion_list){ 
        $data               =   [];
        foreach($occasion_list    as  $row){
        $data[$row->id]['id']        =   $row->id;
        $data[$row->id]['occasion']         =   Occasion::getOccasionsContent($row->occasion_name_cid);
        $data[$row->id]['is_active']       =   $row->is_active; 
        $data[$row->id]['is_deleted']       =   $row->is_deleted;
        $data[$row->id]['created_at']       =   $row->created_at; 
        }

        return $data;
        }else{ return false; }

        }
    
        static function getOccasionsContent($field_id){ 

        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();  
        if($content_table){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }else{ return false; }
        }


        static function getOccasion($occasion_id){ 
        $occasion_list = Occasion::where("id",$occasion_id)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();

        if($occasion_list){ 
        $data               =   [];
        foreach($occasion_list    as  $row){
        $data['id']        =   $row->id;
        $data['occasion_name_cid']         =   $row->occasion_name_cid;
        $data['occasion']         =   Occasion::getOccasionsContent($row->occasion_name_cid);
        $data['image']       =    $row->image;
        $data['is_active']       =   $row->is_active; 
        $data['is_deleted']       =   $row->is_deleted;
        $data['created_at']       =   $row->created_at; 
        $data['language']       =   $row->created_at; 
        }

        return $data;
        }else{ return false; }

        }
        
        static function getOccasionsApi(){ 
        $occasion_list = Occasion::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id','desc')->get();

        if($occasion_list){ 
        $data               =   [];
        foreach($occasion_list    as  $row){
        $data['id']        =   $row->id;
        $data['odoo_id']        =   $row->odoo_id;
        $data['occasion']         =   Occasion::getOccasionsContent($row->occasion_name_cid);
        $data['image']           =   config('app.storage_url').'/app/public/occasions/'.$row->image;
        $data['is_active']       =   $row->is_active; 
        $data['is_deleted']       =   $row->is_deleted;
        $data['created_at']       =   $row->created_at; 
        $list[]=$data;
        }
        return ['httpcode'=>'200','status'=>'success','message'=>'Occasion List','data'=>['occasions'=>$list]];
        }else{ return  ['httpcode'=>'404','status'=>'success','message'=>'Occasion List not found','data'=>['occasions'=>[]]]; }

        }

        static function getOccasionsbyId($id){ 
        $occasion_list = Occasion::where('id',$id)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->first();

        if($occasion_list){ 
        $data['id']        =   $occasion_list->id;
        $data['odoo_id']        =   $occasion_list->odoo_id;
        $data['occasion']         =   Occasion::getOccasionsContent($row->occasion_name_cid);
        $data['is_active']       =   $occasion_list->is_active; 
        $data['image']           =   config('app.storage_url').'/app/public/occasions/'.$occasion_list->image;
        $data['is_deleted']       =   $occasion_list->is_deleted;
        $data['created_at']       =   $occasion_list->created_at; 
        

        return ['httpcode'=>'200','status'=>'success','message'=>'Occasion View','data'=>['occasions'=>$data]];
        }else{ return ['httpcode'=>'404','status'=>'success','message'=>'Occasion not found','data'=>['occasions'=>[]]]; }

        }
}
