<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LogLoyaltyPoints extends Model
{
    use HasFactory;
    protected $table = 'log_loyalty_points';
    protected $fillable = ['user_id','sales_id','credit','debit'];
    protected $guarded=[];
    
    public function getbalancePoints($user_id)
    {
        $bal =0;
        $balance = LogLoyaltyPoints::where('user_id',$user_id)->selectRaw('SUM(credit) - SUM(debit) as bal')->first()->bal;
        if($balance>0)
        {
            return $balance;
        }
        else
        {
            return $bal;
        }
    }

    public function order(){ return $this->belongsTo(SalesOrder::class, 'sales_id'); }

    public function getData($user_id)
    {
        $data = LogLoyaltyPoints::where('user_id',$user_id)->get();
        $pt = [];
        foreach($data as $row)
        {
           // $list['sale_id']  = $row->sales_id;
            if($row->sales_id){
            $list['narration'] = 'Order No.'.$row->order->order_id;
            }
            else
            {
            $list['narration'] = 'Redeemed Points';
            }
            $list['created_on'] = date('d-m-Y',strtotime($row->created_at));
            if($row->credit>0)
            {
                $list['point'] = '+'.$row->credit;
            }
            else
            {
                $list['point'] = '-'.$row->debit;
            }

            $pt[]  = $list;

        }
        return $pt;

    }
    public function getpointsData($user_id)
    {
        $data = LogLoyaltyPoints::where('user_id',$user_id)->get();
        $pt = [];
        $total_credit=0;
        $total_debit=0;
        foreach($data as $row)
        {
          if($row->credit>0)
            {
                
                $total_credit += $row->credit;
            }
            else
            {
               
                $total_debit += $row->debit;
            }
        
        }
            $list['total_credit']=$total_credit;
            $list['total_debit']=$total_debit;
            $list['balance']=$list['total_credit']-$list['total_debit'];
        return $list;

    }
	
	
	public function saleorder(){ return $this->belongsTo(SalesOrder ::class, 'sales_id'); }
	
	static function getListData($post) {     $res = []; 
        $LogLoyaltyPointsModel = new LogLoyaltyPoints; 
		$userModel = new customer\CustomerInfo;
		//$masterModel = new customer\CustomerMaster;
        $LoyaltyLog  =   $LogLoyaltyPointsModel->from($LogLoyaltyPointsModel->getTable().' as P')->join($userModel->getTable().' as T','P.user_id','=','T.id')->where('P.is_deleted',0)->
		selectRaw("SUM(P.credit) as credit,SUM(P.debit) as debit,T.first_name,P.user_id");
        $sort                   =   $post->order[0];
        if($sort['column']      ==  0){ $sortField = 'P.id'; }else if($sort['column'] == 1){ $sortField = 'T.first_name'; }else { $sortField = 'P.id'; }
        $start                  =   (isset($post->start))? $post->start : 0; 
        $length                 =   (isset($post->length))? $post->length : 0; 
        $draw                   =   (isset($post->draw))? $post->draw : ''; 
        $search                 =   (isset($post->search['value']))? $post->search['value'] : ''; 
        $totCount               =   $LoyaltyLog->count(); $filtCount  =   $LoyaltyLog->count();
       
	    
		
		   if($LoyaltyLog != ''){
		   $LoyaltyLog              =   $LoyaltyLog->where(function($qry) use ($search){
										   $qry->where('T.first_name', 'LIKE', '%'.$search.'%');
										   
										});
			   $filtCount          =   $LoyaltyLog->count();
			} 	
        if($length > 0){$LoyaltyLog  =   $LoyaltyLog->orderBy($sortField,$sort['dir'])->offset($start)->limit($length); }            
        $LoyaltyLog                  =   $LoyaltyLog->groupBY(['P.user_id']);
        $LoyaltyLog                  =   $LoyaltyLog->get(['P.*','T.first_name']);

		if($LoyaltyLog){ foreach($LoyaltyLog as $row){ $action = '';
			
		//LogLoyaltyPoints::getCredits($row->usr_addr_typ_id);
		  if($row->is_active   ==  1){ $checked    = 'checked="checked"'; $act = 'Active'; }else{ $checked = '';  $act = 'Inactive'; }
           $val['id']           =   '';                                
           $val['customer']     =  $row->first_name;
           $val['balance_points']     =  $row->credit - $row->debit ;
           $val['total_points']     =  $row->credit ;
          /* $val['status']       =   '<div class="switch" data-search="'.$act.'">
                                           <input class="switch-input status-btn active_status" id="status-'.$row->id.'" type="checkbox" '.$checked.' name="status" data-selid="'.$row->id.'" >
                                            <label class="switch-paddle" for="status-'.$row->id.'">
                                                <span class="switch-active" aria-hidden="true">Active</span><span class="switch-inactive" aria-hidden="true">Inactive</span>
                                            </label>
                                        </div>';*/
                
                $action         .=   '<a href="'.url('admin/loyalty-log/'.$row->user_id).'" data-placement="top" data-toggle="tooltip" title="Edit" class="mr-2 btn btn-info btn-sm"><i class="fe fe-list mr-1"></i> Log</a>';

                $action         .=   '<a href="'.url('admin/customer-loyalty-reward/'.$row->user_id).'"  class="btn btn-sm btn-secondary deleteproduct" data-placement="top" data-toggle="tooltip"  type="button"  >Rewards</a>';

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
