<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Modules extends Model
{

    public $table = 'module';
    


    public $fillable = [
       
        'org_id',
        'module_name',
        'class',
        'link',
        'parent',
        'sort',
        'is_active',
        'is_deleted'
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
    
   static function getModules(){ 
            $parent                 =   Modules::getModulesByParentId(0); 
            $data                   =   [];
            if($parent){
                foreach($parent     as $k=>$row){
                    $data[$k]['parent']             =   $row;
                    $data[$k]['child']              =   Modules::getModulesByParentId($k);
                } return $data;
            }else{ return false; }
        }

    static function getModulesByParentId($parent){ 
        $modules                =   Modules::where('parent',$parent)->where('is_active',1)->where(function ($query) {
    $query->where('is_deleted', '=', NULL)
          ->orWhere('is_deleted', '=', 0);})->orderBy('sort','asc')->get(); 
        if($modules){ 
            $data               =   [];
            foreach($modules    as  $row){
                $data[$row->id]['id']        =   $row->id;
                $data[$row->id]['name']         =   $row->module_name;
                $data[$row->id]['parent']       =   $row->parent; 
            }
            return $data;
        }else{ return false; }
    }

        static function getAllModules(){ 
            $parent                 =   Modules::getAllModulesByParentId(0); 
            $data                   =   [];
            if($parent){
                foreach($parent     as $k=>$row){
                    $data[$k]['parent']             =   $row;
                    $data[$k]['child']              =   Modules::getAllModulesByParentId($k);
                } return $data;
            }else{ return false; }
        }

    static function getAllModulesByParentId($parent){ 
        $modules                =   Modules::where('parent',$parent)->where(function ($query) {
    $query->where('is_deleted', '=', NULL)
          ->orWhere('is_deleted', '=', 0);})->orderBy('sort','asc')->get(); 
        if($modules){ 
            $data               =   [];
            foreach($modules    as  $row){
                $data[$row->id]['id']        =   $row->id;
                $data[$row->id]['module_name']         =   $row->module_name;
                $data[$row->id]['link']         =   $row->link;
                $data[$row->id]['class']         =   $row->class;
                $data[$row->id]['sort']         =   $row->sort;
                $data[$row->id]['is_active']         =   $row->is_active;
                $data[$row->id]['created_at']         =   $row->created_at;
                $data[$row->id]['parent']       =   $row->parent; 
            }
            return $data;
        }else{ return false; }
    }
}
