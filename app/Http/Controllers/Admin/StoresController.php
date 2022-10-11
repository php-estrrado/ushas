<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Session;
use DB;
use App\Models\crm\CrmStores;

use App\Models\Admin;


use App\Rules\Name;
use Validator;

class StoresController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
   
    
    // user roles and modules
    
    public function stores()
        { 
        $data['title']              =   'Stores';
        $data['menu']               =   'stores';
        $data['stores']              =   CrmStores::where('DelStatus',0)->get();
        // dd($data);
        return view('admin.stores.list',$data);
        }

        public function createBrand()
        { 
        $data['title']              =   'Create Brand';
        $data['menu']               =   'create-brand';
        $data['language']      =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        // dd($data);
        return view('admin.brands.create',$data);
        }

        public function editBrand($brand_id)
        { 
        $data['title']              =   'Edit Brand';
        $data['menu']               =   'edit-brand';
        $data['brand']              =  Brand::getBrand($brand_id);
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
       //  dd($data['brand'] );
        return view('admin.brands.edit',$data);
        }
         public function viewBrand($brand_id)
        { 
        $data['title']              =   'View Brand';
        $data['menu']               =   'view-brand';
        $data['brand']              =  Brand::getBrand($brand_id);
        $data['language']           =    DB::table('glo_lang_lk')->where('is_active', 1)->get();
        // dd($data);
        return view('admin.brands.view',$data);
        }

      
    

   
}
