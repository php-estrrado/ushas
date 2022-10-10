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
use App\Models\Coupon;
use App\Models\CouponHist;
use App\Models\Category;
use App\Models\AdminNotification;
use App\Models\Admin;



class NotificationController extends Controller{
    public function __construct(){ 
	$this->middleware('auth:admin');
	}
    
    public function list(Request $request){ 
	$post                       =   (object)$request->post(); $usrIds = []; 
        $data['title']              =   'Notification';
        $data['menuGroup']          =   '';
        $data['menu']               =   '';
        $data['vType']              =   '';
		AdminNotification::where('viewed', '=', 0)->update(array('viewed' => 1));
        if(isset($post->vType)      ==  'ajax'){
			$data['vType']          =   $post->vType;
			return AdminNotification::getListData($post);
		}else{

		return view('admin.notification.list',$data);

		}
		
    }
    
  
}
    

