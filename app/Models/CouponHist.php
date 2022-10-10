<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use App\Models\customer\CustomerInfo;
class CouponHist extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'coupon_usage_hist';
    protected $fillable = ['org_id','coupon_id','user_id', 'order_id', 'ofr_value','ofr_value_type','ofr_type','created_by','updated_by','created_at','updated_at'];
    
    public function coupon(){ return $this->belongsTo(Coupon::class, 'coupon_id'); }
     
  static function getLog($cpn){ 
        $log_list = CouponHist::where('coupon_id',$cpn)->orderBy('id', 'DESC')->get();
            if($log_list){ 
            $data               =   [];
            foreach($log_list    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['coupon_id']         =   $row->coupon_id;
            $data[$row->id]['user_id']         =   $row->user_id;
            $user_name = CustomerInfo::where('user_id', $row->user_id)->first();
            if($user_name){
                $user_name = $user_name->first_name." ". $user_name->last_name;
            }else{
              $user_name = "";  
            }
            $data[$row->id]['user_name']         =  $user_name;  
            $data[$row->id]['order_id']         =   $row->order_id;
            $data[$row->id]['order_date']         =   $row->created_at;
            $data[$row->id]['ofr_value']         =   $row->ofr_value;
            $data[$row->id]['order_value']         =   '1000';
            $data[$row->id]['ofr_type']         =   $row->ofr_type;
            $data[$row->id]['is_active']       =   $row->is_active; 
            $data[$row->id]['is_deleted']       =   $row->is_deleted;
            $data[$row->id]['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }
        
        public function Store($seller_id){ return DB::table('usr_stores')->where('seller_id', $seller_id)->first(); }
        
        static function getCpnContent($field_id){ 

        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();
        if($content_table){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }else{ return false; }
        }
      
}
