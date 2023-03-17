<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Intervention\Image\Facades\Image;

use App\Models\Language;
use App\Models\Banner;
use App\Models\BannerType;
use App\Models\Delivery;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use Validator;
use Session;

class DeliveryController extends Controller{
     public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function delivery()
    { 

        $data['title']    = 'Delivery';
        $data['menu']     = 'Delivery';
        $data['delivery'] = Delivery::getDeliveryTypes();
        
        // dd($data);
        return view('admin.delivery.list',$data);
    }
    
    function createDelivery()
    {
        $data['title'] = 'Create Delivery Type';
        $data['menu']  = 'create-delivery';
       
        // dd($data);
        return view('admin.delivery.create',$data);
    }

    function editDelivery($delivery_id)
    {
        $data['title']          = 'Edit Delivery Type';
        $data['menu']           = 'edit-delivery';
        $data['language']       = DB::table('glo_lang_lk')->where('is_active', 1)->get();
        $data['delivery_data']  = Delivery::getDeliveryData($delivery_id);
      
        // dd($data);
        return view('admin.delivery.edit',$data);
    }

    function saveDelivery(Request $request)
    {
        $input = $request->all();
        // dd($input); 
        $validator= $request->validate([
            'delivery_type' => ['required','max:255'],
            'delivery_description' => ['required','max:255'],
            'delivery_charges' => ['required','max:255']
        ],
        [],
        [
            'delivery_type' => 'Delivery Type',
            'delivery_description' => 'Delivery Description',
            'delivery_charges' => 'Delivery Charges'
            // 'prd_id' => 'Product',
        ]);
        
        if($input['id'] > 0) 
        {
            if (DB::table('cms_content')->where('cnt_id',$input['cnt_id'])->where('lang_id',1)->exists())
            {
                DB::table('cms_content')->where('cnt_id',$input['cnt_id'])->where('lang_id',1)
                ->update(['content' => $input['delivery_description']]);

                $descripton_cid = $input['cnt_id'];
            }
            else
            {
                $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
                $descripton_cid = ++$latest->cnt_id;

                $delivery_description_id = DB::table('cms_content')->insertGetId([
                    'org_id'    => 1, 
                    'lang_id'   => $input['glo_lang_cid'],
                    'cnt_id'    =>$descripton_cid,
                    'content'   => $input['delivery_description'],
                    'is_active' =>1,
                    'created_by'=>auth()->user()->id,
                    'updated_by'=>auth()->user()->id,
                    'is_deleted'=>0,
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s")
                ]);
            }

            $delivery_arr = [];

            $delivery_arr['org_id'] = 1;
            $delivery_arr['delivery_type_name'] = $input['delivery_type'];
            $delivery_arr['delivery_description'] = $descripton_cid;
            $delivery_arr['delivery_charges'] = $input['delivery_charges'];
            $delivery_arr['is_active'] = 1;
            $delivery_arr['is_deleted'] = 0;
            $delivery_arr['created_by'] = auth()->user()->id;
            $delivery_arr['updated_by'] = auth()->user()->id;
            $delivery_arr['created_at'] = date("Y-m-d H:i:s");
            $delivery_arr['updated_at'] = date("Y-m-d H:i:s");
 
            $delivery = Delivery::where('id',$input['id'])->update($delivery_arr);

            $msg = 'Delivery Type Updated successfully!';   
        
            if($delivery)
            {   
                Session::flash('message', ['text'=>$msg,'type'=>'success']);  
            }
            else
            {
                Session::flash('message', ['text'=>'Delivery Updation failed','type'=>'danger']);
            }
        }
        else
        {
            $latest = DB::table('cms_content')->orderBy('id', 'DESC')->first();
            $descripton_cid = ++$latest->cnt_id;

            $delivery_description_id = DB::table('cms_content')->insertGetId([
                'org_id' => 1, 
                'lang_id' => 1,
                'cnt_id'=>$descripton_cid,
                'content' => $input['delivery_description'],
                'is_active'=>1,
                'created_by'=>auth()->user()->id,
                'updated_by'=>auth()->user()->id,
                'is_deleted'=>0,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")
            ]);
        
            $delivery_arr = [];

            $delivery_arr['org_id'] = 1;
            $delivery_arr['delivery_type_name'] = $input['delivery_type'];
            $delivery_arr['delivery_description'] = $descripton_cid;
            $delivery_arr['delivery_charges'] = $input['delivery_charges'];
            $delivery_arr['is_active'] = 1;
            $delivery_arr['is_deleted'] = 0;
            $delivery_arr['created_by'] = auth()->user()->id;
            $delivery_arr['updated_by'] = auth()->user()->id;
            $delivery_arr['created_at'] = date("Y-m-d H:i:s");
            $delivery_arr['updated_at'] = date("Y-m-d H:i:s");
 
            $delivery_id = Delivery::create($delivery_arr)->id;

            $msg = 'Delivery Type added successfully!';   
        
            if($delivery_id)
            {   
                Session::flash('message', ['text'=>$msg,'type'=>'success']);  
            }
            else
            {
                Session::flash('message', ['text'=>'Delivery creation failed','type'=>'danger']);
            }
        }

        return redirect(route('admin.delivery'));
    }
   

    public function DeliveryStatus(Request $request)
    {
        $input = $request->all();
        
        if($input['id']>0) 
        {
            $deleted =  Delivery::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
            return '1';
        }
        else
        {
            return '0';
        }    
    }

    public function DeliveryDelete(Request $request)
    {
        $input = $request->all();
        
        if($input['id']>0)
        {
            $deleted = Delivery::where('id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));

            Session::flash('message', ['text'=>'Delivery type deleted successfully.','type'=>'success']);
            
            return true;
        }
        else
        {
            Session::flash('message', ['text'=>'Delivery type failed to delete.','type'=>'danger']);
            return false;
        }
    }    
}
