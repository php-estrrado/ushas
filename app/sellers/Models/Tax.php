<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use App\Models\TaxValue;
class Tax extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'prd_tax';

    protected $fillable = ['org_id', 'tax_name_cid', 'tax_desc_cid','is_active','is_deleted'];

        static function getTax(){ 
        $tax_list = Tax::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id', 'DESC')->get();
            if($tax_list){ 
            $data               =   [];
            foreach($tax_list    as  $row){
            $data[$row->id]['id']        =   $row->id;
            $data[$row->id]['tax_name']         =   Tax::getTaxContent($row->tax_name_cid);
            $data[$row->id]['tax_desc']       =    Tax::getTaxContent($row->tax_desc_cid);
            $data[$row->id]['percentage']       =    TaxValue::getTaxVal($row->id,'percentage');
            $data[$row->id]['valid_from']       =    TaxValue::getTaxVal($row->id,'valid_from');
            $data[$row->id]['valid_to']       =    TaxValue::getTaxVal($row->id,'valid_to');
            $data[$row->id]['state']       =    Tax::getState(TaxValue::getTaxVal($row->id,'state_id'));
            $data[$row->id]['country']       =    Tax::getCountry(TaxValue::getTaxVal($row->id,'country_id'));
            $data[$row->id]['is_active']       =   $row->is_active; 
            $data[$row->id]['is_deleted']       =   $row->is_deleted;
            $data[$row->id]['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }
    
        static function getTaxContent($field_id){ 

        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();
        if($content_table){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }else{ return false; }
        }
         static function getState($field_id){ 


        $state_table=DB::table('states')->where('id', $field_id)->where('is_deleted', 0)->first();
        if($state_table){ 
        $return_cont = $state_table->state_name;
        return $return_cont;
        }else{ return false; }
        }
        static function getCountry($field_id){ 


        $country_table=DB::table('countries')->where('id', $field_id)->where('is_deleted', 0)->first();
        if($country_table){ 
        $return_cont = $country_table->country_name;
        return $return_cont;
        }else{ return false; }
        }

        static function getTaxData($tax_id){ 
        $tax_list = Tax::where("id",$tax_id)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();

            if($tax_list){ 
            $data               =   [];
            foreach($tax_list    as  $row){
            $data['id']        =   $row->id;
            $data['tax_name_cid']         =   $row->tax_name_cid;
            $data['tax_desc_cid']       =    $row->tax_desc_cid;
            $data['tax_name']         =   Tax::getTaxContent($row->tax_name_cid);
            $data['tax_desc']       =    Tax::getTaxContent($row->tax_desc_cid);
            $data['percentage']       =    TaxValue::getTaxVal($row->id,'percentage');
            $data['valid_from']       =    TaxValue::getTaxVal($row->id,'valid_from');
            $data['valid_to']       =    TaxValue::getTaxVal($row->id,'valid_to');
            $data['taxval_id']       =    TaxValue::getTaxVal($row->id,'id');
            $data['state']       =    Tax::getState(TaxValue::getTaxVal($row->id,'state_id'));
            $data['country']       =    Tax::getCountry(TaxValue::getTaxVal($row->id,'country_id'));
            $data['state_id']       =    TaxValue::getTaxVal($row->id,'state_id');
            $data['country_id']       =  TaxValue::getTaxVal($row->id,'country_id');
            $data['is_active']       =   $row->is_active; 
            $data['is_deleted']       =   $row->is_deleted;
            $data['created_at']       =   $row->created_at; 
            }

            return $data;
            }else{ return false; }

        }
}
