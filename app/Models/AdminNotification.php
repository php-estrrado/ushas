<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;
    protected $fillable = ['notify_from','user_type','notify_to','notify_type','title','description','icon','ref_id','ref_link',];
    
    static function getListData($post) {     $res = []; 
        $notificationModel                  =   new AdminNotification; 
        //$userModel            =   new App\Models\customer\CustomerInfo; 
        $notifications          =   $notificationModel->from($notificationModel->getTable().' as P');
        //$sort                   =   $post->order[0];
        //if($sort['column']      ==  0){ $sortField = 'id'; }else if($sort['column'] == 1){ $sortField = 'P.title'; }else if($sort['column'] == 2){ $sortField = 'P.description'; }else if($sort['column'] == 4){ $sortField = 'P.created_at'; }else { $sortField = 'P.id'; }
        $start                  =   (isset($post->start))? $post->start : 0; 
        $length                 =   (isset($post->length))? $post->length : 0; 
        $draw                   =   (isset($post->draw))? $post->draw : ''; 
        $search                 =   (isset($post->search['value']))? $post->search['value'] : ''; 
        $totCount               =   $notifications->count(); $filtCount  =   $notifications->count();
        if($search != ''){
            $notifications              =   $notifications->where(function($qry) use ($search){
                                        $qry->where('P.title', 'LIKE', '%'.$search.'%');
                                        $qry->orWhere('P.notify_type', 'LIKE', '%'.$search.'%');
                                        
                                    });
            $filtCount          =   $notifications->count();
        } 	
        if($length > 0){$notifications  =   $notifications->orderBy('id','DESC')->offset($start)->limit($length); }            
        $notifications                  =   $notifications->get(['P.*']);

       if($notifications){ foreach   ($notifications as $row){ $action = '';
	       if($row->user_type==2){
			$user =   customer\CustomerInfo::where('user_id', $row->notify_from)->first();
			
		   }
		   if(isset($user) ){
			 $firstName=$user->first_name;  
			 $lastName=$user->last_name;  
			 $middleName=$user->middle_name;  
		   }else{
			$firstName="BB Admin";   
			$lastName="";   
			$middleName="";   
		   }
           if($row->is_active   ==  1){ $checked    = 'checked="checked"'; $act = 'Active'; }else{ $checked = '';  $act = 'Inactive'; }
			$val['id']           =   ''; 
			$val['title']        =   $row->title;
			$val['desc']        =   $row->description;
            $val['from']     =   $firstName." ".$lastName." ".$middleName;
			$val['typename']        =   $row->notify_type;

			
			//$val['created_by']   =   $row->fname;
			$val['created_at']   =   date('D M Y',strtotime($row->created_at));
//               $val['status']       =   '<div class="switch" data-search="'.$act.'">
//                                            <input class="switch-input status-btn active_status" id="status-'.$row->id.'" type="checkbox" '.$checked.' name="status" data-selid="'.$row->id.'" >
//                                            <label class="switch-paddle" for="status-'.$row->id.'">
//                                                <span class="switch-active" aria-hidden="true">Active</span><span class="switch-inactive" aria-hidden="true">Inactive</span>
//                                            </label>
//                                        </div>';
				
                $action         .=   '<button  class="btn btn-sm btn-secondary deleteproduct" data-placement="top" data-toggle="tooltip" title="Delete" type="button" onclick="deleteNotification('.$row->id.')" ><i class="fe fe-trash-2"></i></button>';
					
           //$val['action']       =   $action;
		   $res[] = $val;  
       } }
       $returnData = array(
                    "draw"            => $draw,   
                    "recordsTotal"    => $totCount,  
                    "recordsFiltered" => $filtCount,
                    "data"            => $res   // total data array
                    );
        return $returnData;
    }



}

