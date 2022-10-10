<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LoyaltyRewards extends Model
{
    use HasFactory;
    protected $table = 'loyalty_rewards';
    protected $fillable = ['product_id','required_points','quantity','is_active','is_deleted'];
    protected $guarded=[];
	

	public function rewardInfo(){ return $this->belongsTo(Product ::class, 'product_id'); }

	static function getListData($post) {     $res = []; 
        $LoyaltyRewardsModel = new LoyaltyRewards; 
		$productModel = new Product;
        $LoyaltyRewards  =   $LoyaltyRewardsModel->from($LoyaltyRewardsModel->getTable().' as P')->join($productModel->getTable().' as T','P.product_id','=','T.id')->where('P.is_deleted',0);
        $sort                   =   $post->order[0];
        if($sort['column']      ==  0){ $sortField = 'id'; }else if($sort['column'] == 1){ $sortField = 'P.title'; }else { $sortField = 'P.id'; }
        if($sort['column']      ==  0){ $sortField = 'P.id'; }else if($sort['column'] == 1){ $sortField = 'T.name'; }else { $sortField = 'P.id'; }
        $start                  =   (isset($post->start))? $post->start : 0; 
        $length                 =   (isset($post->length))? $post->length : 0; 
        $draw                   =   (isset($post->draw))? $post->draw : ''; 
        $search                 =   (isset($post->search['value']))? $post->search['value'] : ''; 
        $totCount               =   $LoyaltyRewards->count(); $filtCount  =   $LoyaltyRewards->count();
       
	    if(isset(request()->status)  &&  request()->status != ''){ 
		$LoyaltyRewards = $LoyaltyRewards->where('P.is_active',request()->status);
		$filtCount  =   $LoyaltyRewards->count();
		}
		
		   if($LoyaltyRewards != ''){
		   $LoyaltyRewards              =   $LoyaltyRewards->where(function($qry) use ($search){
										   $qry->where('T.name', 'LIKE', '%'.$search.'%');
										   
										});
			   $filtCount          =   $LoyaltyRewards->count();
			} 	
        if($length > 0){$LoyaltyRewards  =   $LoyaltyRewards->orderBy($sortField,$sort['dir'])->offset($start)->limit($length); }            
        $LoyaltyRewards                  =   $LoyaltyRewards->get(['P.*','T.name']);

		if($LoyaltyRewards){ foreach($LoyaltyRewards as $row){ $action = '';
				
		
		  if($row->is_active   ==  1){ $checked    = 'checked="checked"'; $act = 'Active'; }else{ $checked = '';  $act = 'Inactive'; }
           $val['id']           =   '';                                
           $val['product']     =  $row->name ;
           $val['points_required']     =  $row->required_points ;
           $val['quantity']     =  $row->quantity ;
		   $val['created_at']   =   date('d-m-Y H:i:s',strtotime($row->created_at));
          /* $val['status']       =   '<div class="switch" data-search="'.$act.'">
                                           <input class="switch-input status-btn active_status" id="status-'.$row->id.'" type="checkbox" '.$checked.' name="status" data-selid="'.$row->id.'" >
                                            <label class="switch-paddle" for="status-'.$row->id.'">
                                                <span class="switch-active" aria-hidden="true">Active</span><span class="switch-inactive" aria-hidden="true">Inactive</span>
                                            </label>
                                        </div>';*/
                
                $action         .=   '<a href="'.url('admin/loyalty-reward/edit/'.$row->id).'" data-placement="top" data-toggle="tooltip" title="Edit" class="mr-2 btn btn-info btn-sm"><i class="fe fe-edit"></i></a>';

                $action         .=   '<button  class="btn btn-sm btn-secondary deleteproduct" data-placement="top" data-toggle="tooltip" title="Delete" type="button" onclick="deleteReward('.$row->id.')" ><i class="fe fe-trash-2"></i></button>';

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

    public function product(){ return $this->belongsTo(Product::class, 'product_id'); }
    
    
}
