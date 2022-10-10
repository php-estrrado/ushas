<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\UserRoleAction;

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
        'menu_icon',
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

    static function getAllModulesByParentId($parent,$role=''){ 
        if($role !="") {

            $modules                =   Modules::where('parent',$parent)->whereIn('id',function($query) use ($role) {

   $query->select('module_id')->from('usr_role_action')->where('usr_role_id',$role)->where('is_active',1)->where('view',1);

})->where(function ($query) {
    $query->where('is_deleted', '=', NULL)
          ->orWhere('is_deleted', '=', 0);})->orderBy('sort','asc')->get(); 
// dd($modules);
        }else {
            $modules                =   Modules::where('parent',$parent)->where(function ($query) {
    $query->where('is_deleted', '=', NULL)
          ->orWhere('is_deleted', '=', 0);})->orderBy('sort','asc')->get(); 
        }


        
        if($modules){ 
            $data               =   [];
            foreach($modules    as  $row){
                $data[$row->id]['id']        =   $row->id;
                $data[$row->id]['module_name']         =   $row->module_name;
                $data[$row->id]['link']         =   $row->link;
                $data[$row->id]['class']         =   $row->class;
                $data[$row->id]['sort']         =   $row->sort;
                $data[$row->id]['menu_icon']         =   $row->menu_icon;
                $data[$row->id]['is_active']         =   $row->is_active;
                $data[$row->id]['created_at']         =   $row->created_at;
                $data[$row->id]['parent']       =   $row->parent; 
            }
            return $data;
        }else{ return false; }
    }

    static function visibleModules($role){ 
        if($role ==1) {
            $role = '';
        }
            $parent                 =   Modules::getAllModulesByParentId(0,$role); 
            
			$data                   =   [];
            if($parent){
                foreach($parent     as $k=>$row){
                    $data[$k]['parent']             =   $row;
                    $data[$k]['child']              =   Modules::getAllModulesByParentId($k,$role);
                } //dd($data );
				return $data;
				
            }else{ return false; }
        }

        static function checkPermission($slug,$role,$act){ 
        
            $slug = rtrim($slug, "/");
            $slug = ltrim($slug, "/");


        $modules                =   Modules::whereIn('id',function($query) use ($role,$act) {

        $query->select('module_id')->from('usr_role_action')->where('usr_role_id',$role)->where('is_active',1)->where("$act",1);

        })->where(function ($query) use ($slug) {
        $query->where('link',$slug)
        ->orWhere('link', "/".$slug)->orWhere('link', $slug."/")->orWhere('link', "/".$slug."/");})->exists(); 
        return $modules;
        }

}
