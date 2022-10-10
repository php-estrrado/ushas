<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class SellerTelecom extends Model{
    use HasFactory;
    protected $table        =   'usr_seller_telecom';
    protected $fillable     =   ['seller_id','type_id', 'value'];
    
    static function ValidateUnique($field,$value,$id) {
      //  DB::enableQueryLog();
        $query              =   SellerTelecom::join('usr_seller_mst as M','usr_seller_telecom.id','=','M.'.$field)
                                ->join('usr_seller_info as I','M.id','=','I.seller_id')
                                ->where('I.is_deleted',0)->where('usr_seller_telecom.value',$value);
     //   dd(DB::getQueryLog());
        if($query->count()  >   0){
            if($query->first(['M.id'])->id   !=  $id){ return 'This '.$field.' already has been taken'; }else{ return false; }
        }else{ return false; }
    }
    
}
