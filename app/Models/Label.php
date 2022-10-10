<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Label extends Authenticatable
{
    use HasFactory;
    protected $table = 'labels';
    protected $guarded = [];  

    static function ValidateUnique($name,$post_id=0) {
        $query                  =   OrganizationCategory::where('title',$name)->where('is_deleted',0)->first();
        if($query){
        if($query->id       !=  $post_id){ 
            return 'This Category name already has been taken'; 
        }else{ return false; }
        }else{ return false; }
        }
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
    static function get_count($table,$field,$value){ 

        $table_data=DB::table($table)->where($field, $value)->where('is_active',1)->where('is_deleted',0)->where('is_approved',1)->get();
        if($table_data){ 
        $return_count = count($table_data);
        return $return_count;
        }
        else{ return false; }
        }
		
		
	static function getListData($post) {     $res = []; 
        $labelModel = new Label; 
        $labels   =   $labelModel->from($labelModel->getTable().' as P')->where('P.is_deleted',0);
        $sort                   =   $post->order[0];
        if($sort['column']      ==  0){ $sortField = 'id'; }else if($sort['column'] == 1){ $sortField = 'P.title'; }else { $sortField = 'P.id'; }
        $start                  =   (isset($post->start))? $post->start : 0; 
        $length                 =   (isset($post->length))? $post->length : 0; 
        $draw                   =   (isset($post->draw))? $post->draw : ''; 
        $search                 =   (isset($post->search['value']))? $post->search['value'] : ''; 
        $totCount               =   $labels->count(); $filtCount  =   $labels->count();
       
	    if(isset(request()->status)  &&  request()->status != ''){ 
		$labels = $labels->where('P.is_active',request()->status);
		$filtCount  =   $labels->count();
		}
		if(isset(request()->labelfor)  &&  request()->labelfor != ''){ 
		$labels = $labels->where('P.label_for',request()->labelfor);
		$filtCount  =   $labels->count();
		}
		   if($search != ''){
		   $labels              =   $labels->where(function($qry) use ($search){
										   $qry->where('P.label', 'LIKE', '%'.$search.'%');
										   
										});
			   $filtCount          =   $labels->count();
			} 	
        if($length > 0){$labels  =   $labels->orderBy($sortField,$sort['dir'])->offset($start)->limit($length); }            
        $labels                  =   $labels->get(['P.*']);

		if($labels){ foreach($labels as $row){ $action = '';
				$labels=DB::table('cms_content')->where('cnt_id', $row->label_cid)->first();
		if(isset($labels->content)){		
		$label =Str::of($labels->content)->limit(20);
		}else{
		    $label="";
		}
		  if($row->is_active   ==  1){ $checked    = 'checked="checked"'; $act = 'Active'; }else{ $checked = '';  $act = 'Inactive'; }
           $val['id']           =   '';                                
           $val['label']     =  $label ;
		   $val['created_at']   =   date('D M Y',strtotime($row->created_at));
           $val['status']       =   '<div class="switch" data-search="'.$act.'">
                                           <input class="switch-input status-btn active_status" id="status-'.$row->id.'" type="checkbox" '.$checked.' name="status" data-selid="'.$row->id.'" >
                                            <label class="switch-paddle" for="status-'.$row->id.'">
                                                <span class="switch-active" aria-hidden="true">Active</span><span class="switch-inactive" aria-hidden="true">Inactive</span>
                                            </label>
                                        </div>';
                
                $action         .=   '<a href="'.url('/admin/label/edit/'.$row->id).'" data-placement="top" data-toggle="tooltip" title="Edit" class="mr-2 btn btn-info btn-sm"><i class="fe fe-edit"></i></a>';

                $action         .=   '<button  class="btn btn-sm btn-secondary deleteproduct" data-placement="top" data-toggle="tooltip" title="Delete" type="button" onclick="deleteLabel('.$row->id.')" ><i class="fe fe-trash-2"></i></button>';

           $val['action']       =   $action; $res[] = $val;  
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
