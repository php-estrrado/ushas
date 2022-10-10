<?php
function getMenus(){ 
    $parent                 =   getMenusByParentId(0); 
    $data                   =   [];
    if($parent){
        foreach($parent     as $k=>$row){
          //  $row->haveChild                 =   haveChild($k,auth()->user()->role_id);
            $data[$k]['parent']             =   $row;
            $child                          =   getMenusByParentId($k);
            $data[$k]['child']              =   $child;
         //   if($child){ foreach($child      as  $j=>$ch){ $data[$j]['coChild'] = getMenusByParentId($j); } }else{ $coChild = false; }
        } return $data;
    }else{ return false; }
}

function getMenusByParentId($parent){ 
    $modules                =   DB::table('modules')->where('parent',$parent)->where('status',1)->orderBy('sort','asc')->get(); 
    if($modules){ 
        $data               =   [];
        foreach($modules    as  $row){
            $data[$row->id]['modId']        =   $row->id;
            $data[$row->id]['name']         =   $row->name;
            $data[$row->id]['parent']       =   $row->parent; 
            $data[$row->id]['haveChild']    =   haveChild($row->id,auth()->user()->role_id); 
            $data[$row->id]['link']         =   $row->link; 
            $data[$row->id]['class']        =   $row->class;
            if($row->active != '' && $row->active != NULL){
                $data[$row->id]['active']   =   $row->active; 
            }else{ $data[$row->id]['active']=   '#'; }
            $data[$row->id]['haveAccess']   =   isMenuAccess($row->id,auth()->user()->role_id,$row->parent); 
            if($data[$row->id]['haveChild']){
                $data[$row->id]['cChild']   =   getMenusByParentId($row->id);
            }
          //  if($data[$row->id]['haveAccesss']){ $data[$row->id]['access'] = 'true'; }else{ $data[$row->id]['access'] = 'false'; }
        }
        return $data;
    }else{ return false; }
}

function isMenuAccess($modId,$roleId,$parent){ 
    if(auth()->user()->role_id == 1){ return true; }
    if(DB::table('access_privileges')->where('role_id',$roleId)->where('module_id',$modId)->where('status',1)->count() > 0){ return true; }
    else if($parent == 0){ 
        $query              =   DB::table('modules')->where('parent',$modId)->where('status',1)->get();
        $mIds               =   [0];
        if($query){ foreach($query as $row){ $mIds[] = $row->id; }
            if(DB::table('access_privileges')->where('role_id',$roleId)->whereIn('module_id',$mIds)->where('status',1)->count() > 0){ return true; }
        }
    }
    return false;   
}
function haveChild($parent,$roleId){ 
    if(auth()->user()->role_id == 1){
        if(DB::table('modules')->where('parent',$parent)->where('status',1)->count() > 0){ return true; } else{ return false; }
    }else{
        if(DB::table('modules as M')->join('access_privileges as A','M.id','=','A.module_id')
            ->where('A.role_id',$roleId)->where('M.parent',$parent)->where('M.status',1)->count() > 0){ return true; }else{ return false; }
    }
}

if (!function_exists('permission')) {
    function permission($mod='',$role='') {
        if($role        ==  1){ return true; }
        $query          =   DB::table('access_privileges')->where('role_id',$role)->where('module_id',$mod)->where('status',1)->count(); 
        if($query       >   0){ return true; }else{ return false; }
    }

}