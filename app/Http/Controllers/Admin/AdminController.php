<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManagerStatic as Image;
use Mail;
use Session;
use DB;
use App\Models\Modules;
// use App\Models\UserRoles;
use App\Models\Country;
use App\Models\Admin;
use App\Models\UserRole;
use App\Models\SalesOrder;
use App\Models\Product;
use App\Models\customer\CustomerMaster;
use App\Models\SellerInfo;
use App\Models\UserVisit;
use App\Rules\Name;
use Validator;

class AdminController extends Controller
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
    public function index()
    { 
        $data['title']              =   'User';
        $data['menu']               =   'admin-list';
        $data['orders'] = SalesOrder::where('order_status', '!=', "initiated")->where('org_id',1)->orderBy('id','desc')->get(); 
        if($data['orders']){ $data['orders_c'] = count($data['orders']); }else { $data['orders_c'] =0; }

        $seller_active_ids          =   [];

        
        $data['products'] = Product::where('is_approved',1)->where('is_active',1)->where('visible',1)->where('is_deleted',0)->orderBy('id','desc')->get();
         if($data['products']){ $data['products_c'] = count($data['products']); }else { $data['products_c'] =0; }
        $data['customer']           =    CustomerMaster::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->orderBy('id','DESC')->get();
        if($data['customer']){ $data['customer_c'] = count($data['customer']); }else { $data['customer_c'] =0; }

         $data['sellers'] = [];
         if($data['sellers']){ $data['sellers_c'] = count($data['sellers']); }else { $data['sellers_c'] =0; }
         $data['customer_today']           =    CustomerMaster::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->whereDate('created_at', DB::raw('CURDATE()'))->orderBy('id','DESC')->get();
         if($data['customer_today']){ $data['customer_today_c'] = count($data['customer_today']); }else { $data['customer_today_c'] =0; }
         $data['sellers_today'] = [];
         if($data['sellers_today']){ $data['sellers_today_c'] = count($data['sellers_today']); }else { $data['sellers_today_c'] =0; }

         
        //  $data['product_req_today'] = Product::whereDate('created_at', DB::raw('CURDATE()'))->where('is_approved',0)->where('is_deleted',0)->orderBy('id','desc')->get();
        $data['product_req_today'] = Product::where('is_approved',0)->where('is_deleted',0)->where('visible',1)->orderBy('id','desc')->get();
         if($data['product_req_today']){ $data['product_req_today_c'] = count($data['product_req_today']); }else { $data['product_req_today_c'] =0; }

        //Pending
        $data['ord_pending'] = SalesOrder::where('org_id',1)->where(function ($query) { $query->where('order_status', '=', "pending")->orWhere('order_status', '=', "accepted");})->get();
        if($data['ord_pending']){ $data['ord_pending_c'] = count($data['ord_pending']); }else { $data['ord_pending_c'] =0; }
        //tot.sales
        $data['tot_sales'] = SalesOrder::where('org_id',1)->whereNotIn('order_status',['initiated'])->sum('g_total'); 
        if(! $data['tot_sales']){ $data['tot_sales'] =0;  }

         //ecom counts
        $data['revenue'] = SalesOrder::where('org_id',1)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->whereNotIn('order_status',['initiated'])->sum('g_total'); 
        if(! $data['revenue']){ $data['revenue'] =0;  }
        $data['ord_processing'] = SalesOrder::where('org_id',1)->whereMonth('created_at', date('m'))
        ->whereYear('created_at', date('Y'))->where(function ($query) { $query->where('order_status', '=', "pending")->orWhere('order_status', '=', "accepted")->orWhere('order_status', '=', "pickup")->orWhere('order_status', '=', "cancel_initiated");})->get();
        if($data['ord_processing']){ $data['ord_processing_c'] = count($data['ord_processing']); }else { $data['ord_processing_c'] =0; }
        $data['ord_completed'] = SalesOrder::where('org_id',1)->whereMonth('created_at', date('m'))
        ->whereYear('created_at', date('Y'))->where('order_status',"delivered")->get(); 
        if($data['ord_completed']){ $data['ord_completed_c'] = count($data['ord_completed']); }else { $data['ord_completed_c'] =0; }

        $prd_out_stock_soon = array();
        foreach($data['products'] as $row){
        if(($row->prdStock($row->id) <10) && ($row->prdStock($row->id) >= 0)){
        $prd_out_stock_soon[] = $row;    
        }
        }
        $data['prd_out_stock_soon'] = $prd_out_stock_soon;
        if($data['prd_out_stock_soon']){ $data['prd_out_stock_soon_c'] = count($data['prd_out_stock_soon']); }else { $data['prd_out_stock_soon_c'] =0; }
        
        // visitor log chart
        $visit_start_date = date('Y-m-d 00:00:00');
        $visit_end_date = date('Y-m-d 11:59:00');
        $web_traffic = UserVisit::where('org_id',1)->whereBetween('visited_on', [$visit_start_date, $visit_end_date])->orderBy('id','desc')->get(); 
        $web_traffic_init = UserVisit::where('org_id',1)->orderBy('visited_on','asc')->first()->visited_on; 
        $web_traffic_till = UserVisit::where('org_id',1)->orderBy('visited_on','desc')->first()->visited_on; 
        $data['web_traffic_init'] = $web_traffic_init;
        $data['web_traffic_till'] = $web_traffic_till;
        $traffic_arr = array(); $tosend_arr = array();
        foreach($web_traffic as $row){
        if(! in_array(strtotime($row->visited_on),$tosend_arr)){
        // $traffic_arr[] = array(strtotime($row->visited_on),UserVisit::getCount($row->visited_on));
        $timekey = (strtotime($row->visited_on) * 1000);
        // $traffic_arr[strtotime($row->visited_on)* 1000] =$ret_cnt;
        $traffic_arr[] = array($timekey,UserVisit::getCount($row->visited_on));
        $tosend_arr[] =strtotime($row->visited_on);
        }
        }

        $data['traffic_arr'] = $traffic_arr;
    
        // dummy to show more data  - else enable  $traffic_arr 

        // $dummy_dates = $this->getDatesFromRange('2019-10-01 10:10:00', '2021-10-02 12:10:00');
        // $k = 20; $l = 0;
        // foreach($dummy_dates as $row){
        
        
        // $int_1 = strtotime('2020-03-01 10:10:00');
        // $int_2 = strtotime('2020-05-01 10:10:00');
        // $int_3 = strtotime('2020-08-01 10:10:00');
        // $int_4 = strtotime('2020-10-01 10:10:00');
        // $int_5 = strtotime('2021-02-01 10:10:00');
        // if(strtotime($row) <= $int_1) {
        //     $k = $k+1;
        //  }else if(strtotime($row) <= $int_2) {
        //     $k = 550; $k = $k+1;
        //  }
        //  else if(strtotime($row) <= $int_3) {
        //     $k = 553; $k = $k+1;
        //  }
        //  else if(strtotime($row) <= $int_4) {
        //     $k = 555; $k = $k+1;
        //  }
        //  else if(strtotime($row) <= $int_5) {
        //     $k = 558; $k = $k+1;
        //  }
          
        // // $traffic_arr[] = array(strtotime($row), $k);
        // $traffic_arr[$row] = $k;
       
        // }

        // $data['traffic_arr'] = $traffic_arr;

        $sales_graph = SalesOrder::where('org_id',1)->where('order_status', '!=', "cancelled")->where('order_status', '!=', "initiated")->whereMonth('created_at', date('m'))
->whereYear('created_at', date('Y'))->orderBy('id','desc')->get(); 
         $sales_arr = array(); $sales_arr_c = array();

        foreach($sales_graph as $row){
            if($row->created_at) {
        if(! in_array(strtotime($row->created_at),$sales_arr_c)){
        // $sales_arr[date('Y-m-d',strtotime($row->created_at))] =$this->sale_ord_cnt($row->created_at);
        $sale_timekey = (strtotime(date('Y-m-d',strtotime($row->created_at))) * 1000);
        $sales_arr[] = array($sale_timekey,$this->sale_ord_cnt($row->created_at));
        $sales_arr_c[] =strtotime($row->created_at);
        }

            }
        
        }
        $data['sales_arr'] = $sales_arr;
       
        // dd($sales_arr);
        return view('admin.admin',$data);
        }

        function sale_ord_cnt($date){
            $cnt = 0;
            $orders = SalesOrder::where('org_id',1)->where('order_status', '!=', "cancelled")->where('order_status', '!=', "initiated")->whereDate('created_at', '=', date('Y-m-d',strtotime($date)))->sum('g_total');
            if($orders){
                $cnt = $orders;
            }
            return $cnt;
        }
    
        function getDatesFromRange($sStartDate, $sEndDate, $format = 'Y-m-d') {
     $sStartDate = gmdate("Y-m-d H:i:s", strtotime($sStartDate));  
      $sEndDate = gmdate("Y-m-d H:i:s", strtotime($sEndDate));  
 
     $aDays[] = $sStartDate;  
 
     $sCurrentDate = $sStartDate;  

     while($sCurrentDate < $sEndDate){  
       $sCurrentDate = gmdate("Y-m-d H:i:s", strtotime("+1 hour", strtotime($sCurrentDate)));  

       $aDays[] = $sCurrentDate;  
     }  
     return $aDays; 
   
    }
    
    function profile(){
        return view('admin.profile');
    }

    function validateUser(Request $request){
        $profile                =   $request->post('profile');
        $rules                  =   [
                                        'fname'                 =>  ['required', 'string','max:100', new Name],
                                        'email'                 =>  'required|string|email|max:100',
                                        'phone'                 =>  'required|numeric|digits_between:7,12',
                                    ];
        if($profile['lname']   !=  ''){ $rules['lname']        =   ['required', 'string','max:100', new Name]; }
        $validator              =   Validator::make($profile,$rules);
        $validEmail             =   Admin::ValidateUnique('email',(object)$profile,auth()->user()->id);
        $validPhone             =   Admin::ValidatePhone('phone',(object)$profile,auth()->user()->id);
        if ($validator->fails()) {
           foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
        }
        if($validEmail){ $error['email']    =   $validEmail; }
        if($validPhone){ $error['phone']    =   $validPhone; }
        if(isset($error)) { return $error; }else{ return 'success'; }
    }
    
    function saveProfile(Request $request){
        $profile                =   Admin::where('id',auth()->user()->id)->update($request->post('profile'));
        if($request->file('avatar') && $request->file('avatar') != ''){
            $image = $request->file('avatar');
            $input['imagename'] = 'avatar.'.$image->extension();
            $path               =   '/app/public/user/'.auth()->user()->id;
            $destinationPath = storage_path($path.'/thumbnail');
            $img = Image::make($image->path());
            if (!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
            $img->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
            $destinationPath = storage_path($path);
            $image->move($destinationPath, $input['imagename']);
            $imgUpload          =   uploadFile($path,$input['imagename']);
            Admin::where('id',auth()->user()->id)->update(['avatar'=>$path.'/'.$input['imagename']]); 
            }
        if($profile){   return      back()->with('success',' Profile updated successfully! '); }else{ return back(); }
    }
    
    function validatePassword(Request $request){
        $post                   =   (object)$request->post();
        $validator              =   Validator::make($request->post(),['curr_password' => 'required|string|regex:/^\S*$/u','password' => 'required|string|min:6|regex:/^\S*$/u|confirmed']);
        $user                   =   Admin::where('id',auth()->user()->id)->first();
        if ($validator->fails()) {
           foreach($validator->messages()->getMessages() as $k=>$row){ $error[$k] = $row[0]; }
        }
        if (Hash::check($request->curr_password, $user->getOriginal('password'))) {
        }else{ $error['curr_password'] = 'Invalid current password'; }
        if(isset($error)) { return $error; }else{ return 'success';  }
    }
    
    function savePassword(Request $request){
        $res        =   Admin::where('id',auth()->user()->id)->update(['password' => Hash::make($request->post('password'))]);
        if($res){ return 'success'; }else{ return 'error'; }
    }
    
    function adminLogout(){ 
        Auth::logout(); return redirect('admin/login')->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0');
    }
    
    
    
        
    
        
        //admins list
        public function admins()
        { 
        $data['title']              =   'User';
        $data['menu']               =   'admin-list';
        $data['admins']              =   Admin::where(function ($query) {
    $query->where('is_deleted', '=', NULL)
          ->orWhere('is_deleted', '=', 0);})->orderBy('id', 'DESC')->get();
        // dd($data);
        return view('admin.admins.list',$data);
        }
  public function createAdmin()
        { 
        $data['title']              =   'Create User';
        $data['menu']               =   'create-admin';
        $permanent = array(1,3,4,5,7);
        $data['modules']              =   Admin::where('is_active',1)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $data['roles']              =   UserRole::where('is_active',1)->whereNotIn('id', $permanent)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $data['c_code']              =   getDropdownData(Country::where('is_deleted',0)->get(),'id','phonecode');
        return view('admin.admins.create',$data);
        }

        public function editAdmin($role_id)
        { 
        $data['title']              =   'Edit User';
        $data['menu']               =   'edit-admin';
        $data['admin']              =   Admin::where('id',$role_id)->first();
        $permanent = array(1,3,4,5,7);
        $data['roles']              =   UserRole::where('is_active',1)->whereNotIn('id', $permanent)->where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->get();
        $data['c_code']              =   getDropdownData(Country::where('is_deleted',0)->get(),'id','phonecode');
        // dd($data);
        return view('admin.admins.edit',$data);
        }
    
        public function viewAdmin($role_id)
        { 
        $data['title']              =   'View User';
        $data['menu']               =   'view-admin';
        $data['admin']              =   Admin::where('id',$role_id)->first();

        return view('admin.admins.view',$data);
        }

        public function adminSave(Request $request){
        $post           =   (object)$request->post();
        // dd($post);
        $user           =   $post->user; 
        if($post->id    >   0){

           $rules                  =   [
        
        'email'                 =>  'unique:admins,email,' .$post->id,
        'phone'                 =>  'unique:admins,phone,' .$post->id,
        'role_id'                 =>  'required',
        ];
        $validator              =   Validator::make($user,$rules);
        if ($validator->fails()) {
        foreach($validator->messages()->getMessages() as $k=>$row){  $error[$k] = $row[0];
        Session::flash('message', ['text'=>$row[0],'type'=>'danger']); }
        
       return back()->withErrors($validator)->withInput($request->all());
        }

        if($post->user['password']      ==  ''){ unset($post->user['password']); }
        else{ $post->user['password']   =   Hash::make($post->user['password']); }
        $post->user['updated_at']       =   date('Y-m-d H:i:s');
        $insId      =   $post->id; Admin::where('id',$post->id)->update($post->user);   
        $msg        =   'Admin details updated successfully!';
        
        }else{
        
        $rules                  =   [
        
        'email'                 =>  'required|unique:admins,email|email|max:100',
        'phone'                 =>  'required|numeric|unique:admins',
        'role_id'                 =>  'required',
        ];
        $validator              =   Validator::make($user,$rules);
        if ($validator->fails()) {
        foreach($validator->messages()->getMessages() as $k=>$row){  $error[$k] = $row[0];
        Session::flash('message', ['text'=>$row[0],'type'=>'danger']); }
        
       return back()->withErrors($validator)->withInput($request->all());
        }
        
        $post->user['password']         =   Hash::make($post->user['password']);
        $post->user['created_at']       =   date('Y-m-d H:i:s');
        $insId      =   Admin::create($post->user)->id;
        $msg        =   'Admin details added successfully!';
        }
        if($insId){
        
        if($request->file('avatar') && $request->file('avatar') != ''){
        $image = $request->file('avatar');
        $input['imagename'] = rand(100,999).'avatar.'.$image->extension();
        $path               =   '/app/public/user/'.$insId;
        $destinationPath = storage_path($path.'/thumbnail');
        $img = Image::make($image->path());
        if (!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
        $img->resize(150, 150, function ($constraint) {
        $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);
        $destinationPath = storage_path($path);
        $image->move($destinationPath, $input['imagename']);
        $imgUpload          =   uploadFile($path,$input['imagename']);
        Admin::where('id',$insId)->update(['avatar'=>$path.'/'.$input['imagename']]); 
        }
        $data['title']              =   'User';
        $data['menu']               =   'admin-list';
        $data['admins']              =   Admin::where('role_id',2)->where(function ($query) {
        $query->where('is_deleted', '=', NULL)
        ->orWhere('is_deleted', '=', 0);})->get();
        // dd($data);
        Session::flash('message', ['text'=>$msg,'type'=>'success']);
        return redirect(route('admin.admins'));
        
        }else{ 
        
        Session::flash('message', ['text'=>"Failed to save details.",'type'=>'danger']);
        
        return redirect(route('admin.admins'));
        }
        }
    
         
        public function adminStatus(Request $request)
        {
        $input = $request->all();
        
        if($input['id']>0) {
        $deleted =  Admin::where('id',$input['id'])->update(array('is_active'=>$input['status']));
        
        return '1';
        }else {
        
        return '0';
        }
        
        }
        
          public function adminDelete(Request $request)
        {
        $input = $request->all();

        if($input['id']>0) {
        $deleted =  Admin::where('id',$input['id'])->update(array('is_deleted'=>1,'is_active'=>0));
        Session::flash('message', ['text'=>'Admin deleted successfully.','type'=>'success']);
        return true;
        }else {
        Session::flash('message', ['text'=>'Admin failed to delete.','type'=>'danger']);
        return false;
        }

        }
        
        public function visitlog(Request $request)
        {
        $input = $request->all();
        $visit_start_date = date('Y-m-d 00:00:00',strtotime($input['startDate']));
        $visit_end_date = date('Y-m-d 11:59:00',strtotime($input['endDate']));
        $web_traffic = UserVisit::where('org_id',1)->whereBetween('visited_on', [$visit_start_date, $visit_end_date])->orderBy('id','desc')->get(); 
        $web_traffic_init = UserVisit::where('org_id',1)->orderBy('visited_on','asc')->first()->visited_on; 
        $web_traffic_till = UserVisit::where('org_id',1)->orderBy('visited_on','desc')->first()->visited_on; 
        $data['web_traffic_init'] = $web_traffic_init;
        $data['web_traffic_till'] = $web_traffic_till;
        $traffic_arr = array(); $tosend_arr = array();
        foreach($web_traffic as $row){
        if(! in_array(strtotime($row->visited_on),$tosend_arr)){
        // $traffic_arr[] = array(strtotime($row->visited_on),UserVisit::getCount($row->visited_on));
        $ret_cnt = 0;
        $cnt             =   UserVisit::where('visited_on',$row->visited_on)->get(); 
        if(count($cnt) >0) {
        $ret_cnt = count($cnt);   
        }
        $timekey = (strtotime($row->visited_on) * 1000);
        $traffic_arr[] = array($timekey,$ret_cnt);
        $tosend_arr[] =strtotime($row->visited_on);
        }
        }

        return json_encode($traffic_arr);

        }

        public function salelog(Request $request)
        {
        $input = $request->all();
        $sale_start_date = date('Y-m-d 00:00:00',strtotime($input['startDate']));
        $sale_end_date = date('Y-m-d 23:59:00',strtotime($input['endDate']));
        $sales_graph = SalesOrder::where('org_id',1)->whereBetween('created_at', [$sale_start_date, $sale_end_date])->where('order_status', '!=', "cancelled")->where('order_status', '!=', "initiated")->orderBy('id','desc')->get(); 
        $sales_arr = array(); $sales_arr_c = array();
       
        foreach($sales_graph as $row){
        if($row->created_at) {
        if(! in_array(strtotime($row->created_at),$sales_arr_c)){
        // $sales_arr[date('Y-m-d',strtotime($row->created_at))] =$this->sale_ord_cnt($row->created_at);

        $sale_timekey = (strtotime(date('Y-m-d',strtotime($row->created_at))) * 1000);
        // $traffic_arr[strtotime($row->visited_on)* 1000] =$ret_cnt;
        $sales_arr[] = array($sale_timekey,$this->sale_ord_cnt($row->created_at));

        $sales_arr_c[] =strtotime($row->created_at);
        }
        }
        }
        

        return json_encode($sales_arr);

        }
		
        
        public function sendmail(){
        $data = array("content"=>"Test");
        echo $mail= mail("sujeesh@estrrado.com","Test","Testing");
        echo $mail= mail("s.sujeesh1993@yahoo.com","Test","Testing");
        echo $var = Mail::send('emails.seller_approved', $data, function($message) use($data) {
        $message->to('s.sujeesh1993@yahoo.com');
        $message->sender('myjewelleryshopper@gmail.com',ucfirst(geSiteName()));
        $message->subject('New email!!!');
        });
        dd($var);
        }
    
}
