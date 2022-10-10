<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use App\Models\UserRole;
use App\Models\Currency;
use App\Models\Country;
use App\Rules\Name;
use Validator;
use Session;
use DB;

class CurrencyController extends Controller
{
    public function __construct(){ $this->middleware('auth:admin'); }
    public function list(Request $request)
    {
        $data['title']              =   'Currency List';
        $data['menuGroup']          =   'Settings';
        $data['menu']               =   'Currency';
        $data['list']           =   Currency::where('is_deleted',0)->orderBy('is_default','DESC')->get();
        return view('admin.currency.page',$data);
    }
    public function addnew(Request $request)
    {
        $data['title']              =   'Currency';
        $data['menuGroup']          =   'Settings';
        $data['menu']               =   'Currency';
        $data['menutype']           =   'create';
        $data['currency']           =   '';
        //$data['list']           =   Currency::where('is_deleted',0)->orderBy('is_default','DESC')->get();
        $data['country']            =  Country::all();
        return view('admin.currency.create',$data);
    }

    public function insert(Request $request,$id=0)
    {
        if($id>0)
            {
            $validate= $request->validate([
            'currency_name' => ['required', 'string'],
            'currency_code' => ['required', 'string','max:4'],
            'country'=> ['required','gt:0'],
            'is_default'=> ['required'],
            'status'=> ['required'],
            'flag'=>['nullable','image','mimes:jpeg,png,jpg']
            ]);  
            $create = ['country_id'=>$request->country,
                       'currency_name'=>$request->currency_name,
                       'currency_code'=>strtoupper($request->currency_code),
                       'is_default'=>$request->is_default,
                       'is_active'=>$request->status,
                       'is_deleted'=>0,
                       'created_at'=>date("Y-m-d H:i:s"),
                       'updated_at'=>date("Y-m-d H:i:s")];  
                       
                       if($request->is_default==1)
            {
                $update = Currency::where('is_default',1)->first();
                if($update){
                $update->is_default=0;
                $update->save();
                }
            }
            $insert_id = Currency::where('id',$id)->update($create);
            
            if ($request->hasFile('flag')) {
                $image = $request->file('flag');
            $imgName            =   time().'.'.$image->extension();
                $path               =   '/app/public/currency-flag/'.$id;
                $destinationPath    =   storage_path($path.'/thumb');
                $img                =   Image::make($image->path()); // echo storage_path().'  '. $destinationPath; die;
                if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
                $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
                $destinationPath    =   storage_path($path);
                $image->move($destinationPath, $imgName);
                $imgUpload          =   uploadFile('/'.$path,$imgName);
               
                if($imgUpload){
                    Currency::where('id',$id)->update(['image'=>$path.'/'.$imgName]);
                }
            }
            Session::flash('message', ['text'=>'Currency updated successfully','type'=>'success']);
            return redirect(url('admin/currency'));  
            }
            else
            {
           $validate= $request->validate([
            'currency_name' => ['required', 'string'],
            'currency_code' => ['required', 'string','max:4'],
            'country'=> ['required','gt:0'],
            'is_default'=> ['required'],
            'status'=> ['required'],
            'flag'=>['required','image','mimes:jpeg,png,jpg']]);
        if (Currency::where('currency_code', '=', $validate['currency_code'])->where('country_id',$validate['country'])->where('is_deleted', '=',0)->exists()) {
            Session::flash('message', ['text'=>'Currency Already Exist','type'=>'warning']);
            //return redirect(route('admin.newcurrency'));
            return redirect(route('admin.newcurrency'))->withInput();
        }
        else
        {
            $create = ['country_id'=>$request->country,
                       'currency_name'=>$request->currency_name,
                       'currency_code'=>strtoupper($request->currency_code),
                       'is_default'=>$request->is_default,
                       'is_active'=>$request->status,
                       'is_deleted'=>0,
                       'created_at'=>date("Y-m-d H:i:s"),
                       'updated_at'=>date("Y-m-d H:i:s")];
            if($request->is_default==1)
            {
                $update = Currency::where('is_default',1)->first();
                if($update){
                $update->is_default=0;
                $update->save();
                }
            }
            
            $insert_id = Currency::create($create)->id;
            
            if ($request->hasFile('flag')) {
                $image = $request->file('flag');
            $imgName            =   time().'.'.$image->extension();
                $path               =   '/app/public/currency-flag/'.$insert_id;
                $destinationPath    =   storage_path($path.'/thumb');
                $img                =   Image::make($image->path()); // echo storage_path().'  '. $destinationPath; die;
                if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
                $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
                $destinationPath    =   storage_path($path);
                $image->move($destinationPath, $imgName);
                $imgUpload          =   uploadFile('/'.$path,$imgName);
               
                if($imgUpload){
                    Currency::where('id',$insert_id)->update(['image'=>$path.'/'.$imgName]);
                }
            }
            Session::flash('message', ['text'=>'Currency created successfully','type'=>'success']);
            return redirect(url('admin/currency'));  
                    
        }
      }
    }

    public function edit($id)
    {
        $data['title']              =   'Currency';
        $data['menuGroup']          =   'Settings';
        $data['menu']               =   'Currency';
        $data['menutype']           =   'Edit';
        $data['currency']           =   Currency::where('is_deleted',0)->where('id',$id)->first();
        $data['country']            =  Country::all();
        return view('admin.currency.create',$data);
    }

    public function delete(Request $request)
    {
       //return $request->id;die;
       // $array=['is_deleted'=>1];
        $id=$request->id;
        $currency =  Currency::where('id',$id)->first();
        $currency->is_deleted=1;
        $currency->save();
        //$update = Currency::where('id',$id)->udpate($array);
        Session::flash('message', ['text'=>'Deleted successfully','type'=>'success']);
    }
    
        public function statusUpdate(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Currency::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }
}
