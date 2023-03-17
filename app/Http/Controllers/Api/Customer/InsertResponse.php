<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Modules;
use App\Models\UserRoles;
use App\Models\Admin;
use App\Models\Auction;
use App\Models\AuctionHist;
use App\Models\UserRole;
use App\Models\Category;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\CartHistory;
use App\Models\Subcategory;
use App\Models\Store;
use App\Models\SellerReview;
use App\Models\SaleOrder;
use App\Models\SaleorderItems;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductDaily;
use App\Models\PrdAssignedTag;
use App\Models\PrdReview;
use App\Models\PrdShock_Sale;
use App\Models\PrdPrice;
use App\Models\RelatedProduct;
use App\Models\AssignedAttribute;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\AssignedFields;
use App\Models\UsrNotification;
use App\Models\AssociatProduct;
use Carbon\Carbon;
use App\Rules\Name;
use Validator;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use App\Models\crm\{CrmAssortmentMaster, CrmChildProductsMaster, CrmCustomerType,CrmPartAssortmentDetails,
CrmPartAssortmentMaster,CrmProduct,CrmSalesPriceList,CrmSalesPriceType,CrmSize,CrmBranch,CrmCompany};

class InsertResponse extends Controller
{
    public function insert_seller_review(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        $validator=  Validator::make($request->all(),[
            'seller_id' => ['required','numeric'],
            'title' => ['required','string','max:255'],
            'comment'=> ['required','string','min:5','max:255'],
            'rating'=>['required','numeric','max:5'],
            'image'=> ['nullable','image','mimes:jpeg,png,jpg'],
        ]);
        $input = $request->all();
        // echo $input['title'];
        // die;

    if ($validator->fails()) 
    {    
      return response()->json(['httpcode'=>400,'status'=>'error','errors'=>$validator->messages()]);
    }

    else
    {
        $count  =  SaleOrder::where('seller_id',$input['seller_id'])->where('cust_id',$user_id)->count();
        if($count>0)
        {
        $insert =  SellerReview::create(['seller_id' => $input['seller_id'],
                'user_id' => $user_id,
                'comment' => $input['comment'],
                'title'=> $input['title'],
                'rating'  => $input['rating'],
                'is_active'=>1,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
        return response()->json(['httpcode'=>200,'status'=>'success','response'=>"Successfully inserted"]);
        }
        else
        {
         return response()->json(['httpcode'=>400,'status'=>'Not available','message'=>'The customer not purchased any product from this store']);
        }
    }

    }

    public function add_bid(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        if($request->access_token=='' && $request->auction_id=='' && $request->bid_amount=='')
        {
           return response()->json(['httpcode'=>400,'status'=>'error','response'=>"Enter valid data"]); 
        }
        else
        {   
            $current_date=Carbon::now();
            $auction=Auction::where('id',$request->auction_id)->where('is_active',1)->where('is_deleted',0)->where('bid_allocated_to',0)->whereDate('auct_end','>=',$current_date)->whereDate('auct_start','<=',$current_date)->first();
            if($auction)
            {
               $max_value=AuctionHist::where('auction_id',$request->auction_id)->max('bid_price');

               if($request->bid_amount > $max_value && $request->bid_amount > $auction->min_bid_price)
                   {
                   $create= AuctionHist::create([
                'auction_id' => $request->auction_id,
                'user_id'=>$user_id,
                'bid_price' => $request->bid_amount,
                'is_active'=>1,
                'is_deleted'=>0,
                'created_by'=>$user_id,
                'modified_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);

                 return response()->json(['httpcode'=>200,'status'=>'success','response'=>"Success"]);  
               }
               else
               {
                return response()->json(['httpcode'=>400,'status'=>'error','response'=>"Amount not sufficient for bidding"]);
                
               }
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','response'=>"Invalid"]);
            }
        }
    }
    
    
    public function insert_product_review(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];

        $validator=  Validator::make($request->all(),[
            'product_id' => ['required','numeric'],
            'sale_id' => ['required','numeric'],
            'comment'=> ['required','string','min:5','max:255'],
            'rating'=>['required','numeric','max:5'],
            'image'=> ['nullable','image','mimes:jpeg,png,jpg'],
        ]);
        $input = $request->all();

    if ($validator->fails()) 
    {    
      return response()->json(['httpcode'=>400,'status'=>'error','data'=>['errors'=>$validator->messages()]]);
    }

    else
    {
        $sale   =  SaleOrder::where('cust_id',$user_id)->where('order_status','delivered')->get(['id']);
        if(count($sale)>0)
        {
        //$data_img=[];    
        $count  =  SaleorderItems::where('prd_id',$input['product_id'])->whereIn('sales_id',$sale)->count();
        $sale_rev = PrdReview::where('is_deleted',0)->where('is_active',1)->where('prd_id',$request->product_id)->where('sale_id',$request->sale_id)->first();
        if($sale_rev)
        {
           return ['httpcode'=>400,'status'=>'error','message'=>' Review already submitted']; 
        }
        
        else{
        if($count>0)
        {
            if($request->hasFile('image'))
                    {
                       
                    $file=$request->file('image');
                    $extention=$file->getClientOriginalExtension();
                    $filename=date('Ymd').time().rand(100,999).'.'.$extention;
                    $file->move(('uploads/storage/app/public/product_review/'),$filename);
                    $filenames='/app/public/product_review/'.$filename;
            
        }
                    else
                    {
                        $filenames='';
                    }
        //Checking if it is a config or simple prdct            
        $product_is_visible = Product::where('id',$input['product_id'])->where('visible',1)->first();
        if($product_is_visible)
        { 
            $prd_id = $input['product_id'];
        }
        else
        {
            $prd_id = AssociatProduct::where('ass_prd_id',$input['product_id'])->first()->prd_id;
        }
                    
        $insert =  PrdReview::create(['prd_id' => $prd_id,
                'user_id' => $user_id,
                'sale_id'=>$input['sale_id'],
                //'headline'=>$input['title'],
                'comment' => $input['comment'],
                'rating'  => $input['rating'],
                'image'  =>$filenames,
                'is_active'=>1,
                'is_deleted'=>0,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
        return response()->json(['httpcode'=>200,'status'=>'success','response'=>"Review submitted successfully",'message'=>'Review submitted successfully']);
        }
        else
        {
            return response()->json(['httpcode'=>400,'status'=>'Unavaliable to submit review','message'=>'The customer not purchased this product']);
        }
        }
    }
        else
        {
         return response()->json(['httpcode'=>400,'status'=>'Not available','message'=>'The customer not purchased any product']);
        }
    }

    }
    
    public function insert_cart(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        
        $validator=  Validator::make($request->all(),[
            'product_id' => ['required','numeric'],
            'prd_assign_id'=>['nullable','regex:/^\d+(((,\d+)?,\d+)?,\d+)?$/'],
            'quantity'=> ['required','numeric'],
            'cart_type'=>['required','string','max:5']
        ]);
        $input = $request->all();

    if ($validator->fails()) 
    {    
      return response()->json(['httpcode'=>400,'status'=>'error','data'=>['errors'=>$validator->messages()]]);
    }

    else
    {
        $product_in =Product::where('id',$input['product_id'])->where('is_active',1)->where('is_deleted',0)->first();
        $crm_product_id = $product_in->crmProduct->id;
        // dd($crm_product_id->id);
		if(($product_in) && ($crm_product_id)){

            $custom_order = $input['custom'];
            if($custom_order >0)
            {
                $sizes = $input['children'];
                if(isset($sizes) && count($sizes)>0)
                {
                    foreach($sizes as $sk=>$sv)
                    {
                        
                        if($product_in->out_of_stock_selling==0){
                            $stock=CrmChildProductsMaster::ChildPrdStock($sv['child_product_id'],$input['product_id']);
                            if($stock <= 0){
                            return ['httpcode'=>400,'status'=>'error','message'=>"Out of stock",'data'=>['child_product_id'=>$sv['child_product_id']]];    
                            die;
                            }
                        }
                    }

                }else{
                    return ['httpcode'=>400,'status'=>'error','message'=>"Please select size."];    
                    die;
                }

            }
            // dd($custom_order);
		    
        if($custom_order >0)
            {
                $sizes = $input['children'];
                if(isset($sizes) && count($sizes)>0)
                {   
                    $cart_init = 0;
                    foreach($sizes as $sk=>$sv)
                    {
                     
                      $cart_init = $this->cart_modify($input['product_id'],$sv['child_product_id'],$sv['child_quantity'],$user_id,$input,$cart_init,$crm_product_id,1);  

                    }

                    return ['httpcode'=>200,'status'=>'success','message'=>"Added to cart"];

                }else{
                    return ['httpcode'=>400,'status'=>'error','message'=>"Please select size."];    
                    die;
                }

            }else
            {
                $assortment_id = $input['assortment_id'];
                $assortment_qty = $input['quantity'];

                $prd_assort = CrmPartAssortmentMaster::where('productID',$crm_product_id)->where('AssortmentID',$assortment_id)->where('is_deleted',0)->get();

                 if(count($prd_assort)>0)
                            {
                            foreach($prd_assort as $rows)
                            {
                                
                                if($rows->Assortments)
                                {
                                    $assortment = $rows->Assortments;
                                    if($rows->AssortmentsDetail)
                                    {
                                        $cart_init = 0;
                                        foreach($rows->AssortmentsDetail as $child_prod_k=>$child_val)
                                        {
                                            
                                           //$child_qty = $child_val->ChildQuantity*$assortment_qty; 
                                           $child_qty = $child_val->ChildQuantity; 
                                            $cart_init = $this->cart_modify($input['product_id'],$child_val->ChildProductID,$child_qty,$user_id,$input,$cart_init,$crm_product_id,0);  

                                        }
                                  
                                        return ['httpcode'=>200,'status'=>'success','message'=>"Added to cart"];
                                    }
                                    
                                }

                            }
                     }else
        {
            return ['httpcode'=>404,'status'=>'error','message'=>"Product unavailable"];
        }


            }

        }
        else
        {
            return ['httpcode'=>404,'status'=>'error','message'=>"Product unavailable"];
        }
       
      }
    }


    public function cart_modify($product_id,$child_product_id,$child_quantity,$user_id,$input,$cart_init=0,$crm_product_id,$custom=0)
    {

        $custom_order = $input['custom'];
        if($custom_order >0)
        {
          $assortment_id = 0;     
        }else
        {
         $assortment_id = $input['assortment_id'];    
        }

         $in_cart = Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')
        ->where('usr_cart_item.is_active',1)->where('usr_cart_item.is_deleted',0)
        ->where('usr_cart.is_active',1)->where('usr_cart.is_deleted',0)
        ->where('usr_cart_item.product_id',$product_id)
        ->where('usr_cart_item.prd_assign_id',$child_product_id)
        ->where('usr_cart_item.assortment_id',$assortment_id)
        ->where('usr_cart.user_id',$user_id)
        ->first();
    

           
        if($in_cart)
        {   
            $quantity_update = 0;
            //$quantity_update = $in_cart->quantity + $child_quantity;
           if($custom ==1){  $quantity_update = $in_cart->quantity + $child_quantity; }else{ $quantity_update = $in_cart->quantity ; }
            if($assortment_id>0){ $assort_qty =$in_cart->assortment_qty + $input['quantity'];  }else{ $assort_qty =0; }
            Cart::where('id',$in_cart->cart_id)->update([
            'updated_at'=>date("Y-m-d H:i:s")]);

            CartItem::where('cart_id',$in_cart->cart_id)->where('prd_assign_id',$child_product_id)->where('assortment_id',$assortment_id)->update([
            'quantity'=>$quantity_update,   
            'assortment_qty'  => $assort_qty,
            'updated_at'=>date("Y-m-d H:i:s")]);

            $insert_cart_hist =  CartHistory::create(['org_id' => 1,
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity'  => $child_quantity,
                'prd_assign_id'=>$child_product_id,
                'assortment_id'=>$assortment_id,
                'crm_product_id'=>$crm_product_id,
                'action'=>'insert',
                'is_active'=>1,
                'is_deleted'=>0,
                'created_by'=>$user_id,
                'updated_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
        }
        else
        {

            if($cart_init>0)
            {
                $cart_id = $cart_init;
            }else{
                $insert_cart =  Cart::create(['org_id' => 1,
                'user_id' => $user_id,
                'cart_name' => $input['cart_type'],
                'cart_desc'  => $input['cart_type'],
                'assortment_id'=>$assortment_id,
                'is_active'=>1,
                'is_deleted'=>0,
                'created_by'=>$user_id,
                'updated_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
                $cart_id = $insert_cart->id;  
            }

            if($assortment_id>0){ $assort_qty =$input['quantity'];  }else{ $assort_qty =0; }

        $insert_cart_item =  CartItem::create(['org_id' => 1,
                'cart_id' => $cart_id,
                'product_id' => $product_id,
                'prd_assign_id'=>$child_product_id,
                'quantity'  => $child_quantity,
                'assortment_qty'  => $assort_qty,
                'assortment_id'=>$assortment_id,
                'crm_product_id'=>$crm_product_id,
                'is_active'=>1,
                'is_deleted'=>0,
                'created_by'=>$user_id,
                'updated_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);

        $insert_cart_hist =  CartHistory::create(['org_id' => 1,
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity'  => $child_quantity,
                'prd_assign_id'=>$child_product_id,
                'assortment_id'=>$assortment_id,
                'crm_product_id'=>$crm_product_id,
                'action'=>'insert',
                'is_active'=>1,
                'is_deleted'=>0,
                'created_by'=>$user_id,
                'updated_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);

                return $cart_id;

            }
    }
    
    public function change_cart_qty(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        
        $validator=  Validator::make($request->all(),[
            'cart_id' => ['required','numeric'],
            'custom'=> ['required','numeric'],
            'assortment_id' => ['required_if:custom,=,0'],
            'quantity'=> ['required_if:assortment_id,!=,""','numeric'],
            'children' => ['required_if:custom,=,1'],
            'cart_type'=>['required','string','max:5']
        ]);
        $input = $request->all();

    if ($validator->fails()) 
    {    
      return response()->json(['httpcode'=>400,'status'=>'error','data'=>['errors'=>$validator->messages()]]);
    }

    else
    {
        
        
        $in_cart = Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')
        ->where('usr_cart_item.is_active',1)->where('usr_cart_item.is_deleted',0)
        ->where('usr_cart.is_active',1)->where('usr_cart.is_deleted',0)
        ->where('usr_cart_item.cart_id',$input['cart_id'])
        ->where('usr_cart.user_id',$user_id)
        ->first();
        //dd($in_cart);
        if($in_cart)
        {
            
            if($input['custom'] == 1){
                
                Cart::where('id',$in_cart->cart_id)->update([
                'updated_at'=>date("Y-m-d H:i:s")]);
               
                $children = $input['children'];
                if($children)
                {
                    foreach($children as $ck=>$cv)
                    {
                        
                        CartItem::where('cart_id',$in_cart->cart_id)->where('prd_assign_id',$cv['child_product_id'])->update([
                        'quantity'=>$cv['child_quantity'],    
                        'updated_at'=>date("Y-m-d H:i:s")]);
                        
                    }
                }
                
                 $insert_cart_hist =  CartHistory::create(['org_id' => 1,
                'user_id' => $user_id,
                'product_id' => $in_cart->product_id,
                'quantity'  => $input['quantity'],
                'action'=>'insert',
                //'prd_assign_id'=>$in_cart->product_idprd_assign_id,
                'is_active'=>1,
                'is_deleted'=>0,
                'created_by'=>$user_id,
                'updated_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]); 
                 
            }else{
                    
                $quantity_update = $input['quantity'];
                Cart::where('id',$in_cart->cart_id)->update([
                'updated_at'=>date("Y-m-d H:i:s")]);
                
                CartItem::where('cart_id',$in_cart->cart_id)->update([
                'assortment_qty'=>$quantity_update,    
                'updated_at'=>date("Y-m-d H:i:s")]);
                
                $insert_cart_hist =  CartHistory::create(['org_id' => 1,
                'user_id' => $user_id,
                'product_id' => $in_cart->product_id,
                'quantity'  => $input['quantity'],
                'action'=>'insert',
                //'prd_assign_id'=>$in_cart->product_idprd_assign_id,
                'is_active'=>1,
                'is_deleted'=>0,
                'created_by'=>$user_id,
                'updated_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);       
                
            }
		

        }
        else
        {
        return ['httpcode'=>404,'status'=>'error','message'=>'Not found','data'=>['errors'=>'No product found']];
        }
        return response()->json(['httpcode'=>200,'status'=>'success','response'=>"Successfully updated"]);
        
    
       
      }
    }
    
    public function cart_qty_by_product_id(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        
        $validator=  Validator::make($request->all(),[
            'product_id' => ['required','numeric'],
            'quantity'=> ['required','numeric'],
            'cart_type'=>['required','string','max:5']
        ]);
        $input = $request->all();

    if ($validator->fails()) 
    {    
      return response()->json(['httpcode'=>400,'status'=>'error','data'=>['errors'=>$validator->messages()]]);
    }

    else
    {
        $in_cart = Cart::join('usr_cart_item','usr_cart.id','=','usr_cart_item.cart_id')
        ->where('usr_cart_item.is_active',1)->where('usr_cart_item.is_deleted',0)
        ->where('usr_cart.is_active',1)->where('usr_cart.is_deleted',0)
        ->where('usr_cart_item.product_id',$input['product_id'])
        ->where('usr_cart.user_id',$user_id)
        ->first();
        //dd($in_cart);
        if($in_cart)
        {
		$product_in =Product::where('id',$in_cart->product_id)->where('is_active',1)->where('is_deleted',0)->first();
		if($product_in){
			if($product_in->bulk_order!=0){
			  if($input['quantity'] % $product_in->bulk_order != 0){
				return ['httpcode'=>400,'status'=>'error','message'=>"bulk order quantity is ".$product_in->bulk_order." or Multiple of ".$product_in->bulk_order];
                die;
			  }
			}
		    if($product_in->min_order!=0){
			  if($input['quantity'] < $product_in->min_order){
				return ['httpcode'=>400,'status'=>'error','message'=>"Minimum quantity of the order is ".$product_in->min_order];
                die;
			  }
			}
		}
            $quantity_update = $input['quantity'];
            Cart::where('id',$in_cart->cart_id)->update([
            'updated_at'=>date("Y-m-d H:i:s")]);

            CartItem::where('cart_id',$in_cart->cart_id)->update([
            'quantity'=>$quantity_update,    
            'updated_at'=>date("Y-m-d H:i:s")]);

            $insert_cart_hist =  CartHistory::create(['org_id' => 1,
                'user_id' => $user_id,
                'product_id' => $in_cart->product_id,
                'quantity'  => $input['quantity'],
                'action'=>'insert',
                //'prd_assign_id'=>$in_cart->product_idprd_assign_id,
                'is_active'=>1,
                'is_deleted'=>0,
                'created_by'=>$user_id,
                'updated_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
                
        return response()->json(['httpcode'=>200,'status'=>'success','response'=>"Successfully updated"]);
        }
        else
        {
       return ['httpcode'=>404,'status'=>'error','message'=>'Not found','data'=>['errors'=>'No product found in cart']];
        }
        
        
    
       
      }
    }
    
    public function insert_wishlist(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        
        $validator=  Validator::make($request->all(),[
            'product_id' => ['required','numeric'],
            'type'=>['required','string','max:5']
        ]);
        $input = $request->all();

    if ($validator->fails()) 
    {    
      return response()->json(['httpcode'=>400,'status'=>'error','data'=>['errors'=>$validator->messages()]]);
    }

    else
    {
        $in_wish = Wishlist::join('usr_wishlist_prod','usr_wishlist_lk.id','=','usr_wishlist_prod.usr_wishlist_id')
        ->where('usr_wishlist_lk.is_active',1)->where('usr_wishlist_lk.is_deleted',0)
        ->where('usr_wishlist_prod.is_active',1)->where('usr_wishlist_prod.is_deleted',0)
        ->where('usr_wishlist_prod.product_id',$input['product_id'])
        ->where('usr_wishlist_prod.user_id',$user_id)
        ->first();
        
        if($in_wish)
        {
             return response()->json(['httpcode'=>200,'status'=>'Already exist','response'=>"Product is already in the wishlist"]);
        }
        else
        {
        $insert_wish =  Wishlist::create(['org_id' => 1,
                'usr_wishlist_name' => $input['type'],
                'usr_wishlist_desc'  => $input['type'],
                'is_active'=>1,
                'is_deleted'=>0,
                'created_by'=>$user_id,
                'updated_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);
        $cart_id = $insert_wish->id;

        $insert_cart_item =  WishlistItem::create(['org_id' => 1,
                'user_id' => $user_id,
                'usr_wishlist_id' => $cart_id,
                'product_id' => $input['product_id'],
                'is_active'=>1,
                'is_deleted'=>0,
                'created_by'=>$user_id,
                'updated_by'=>$user_id,
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s")]);

        return response()->json(['httpcode'=>200,'status'=>'success','response'=>"Successfully inserted"]);

            }
       
      }
    }
    
    
    //notification view update
    public function notify_view_update(Request $request)
    {
        if(!$user = validateToken($request->post('access_token'))){ return invalidToken(); }
        $user_id = $user['user_id'];
        if($request->access_token=='' && $request->notification_id=='')
        {
           return response()->json(['httpcode'=>400,'status'=>'error','response'=>"Enter valid data"]); 
        }
        else
        {   
            
            $notify=UsrNotification::where('id',$request->notification_id)->first();
            if($notify)
            {
               
                   $create= UsrNotification::where('id',$notify->id)->update([
                'viewed' => 1,
                'updated_at'=>date("Y-m-d H:i:s")]);

                 return response()->json(['httpcode'=>200,'status'=>'success','response'=>"Success"]);  
              
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','response'=>"Invalid"]);
            }
        }
    }
    
    public function minimum_quantity(Request $request) {
            $validator=  Validator::make($request->all(),[
                'product_id' => ['required','numeric'],
                'cart_type'=>['required','string','max:5']
            ]);
            $input = $request->all();

            if ($validator->fails()) 
            {    
              return response()->json(['httpcode'=>400,'status'=>'error','data'=>['errors'=>$validator->messages()]]);
            }else {
            $product_in =Product::where('id',$input['product_id'])->where('is_active',1)->where('is_deleted',0)->first();
           //dd($product_in);
           
    		if($product_in){
    		    if($product_in->visible == 1 && $product_in->product_type==1 || $product_in->visible == 0 && $product_in->product_type==2 ){
    			    if($product_in->min_order){
    			     $data['minimum_quantity'] = $product_in->min_order;
        		    }else{
        		         $data['minimum_quantity'] = 0;
        		    }
        		    if($product_in->bulk_order){
        			     $data['bulk_order_quantity'] = $product_in->bulk_order;
        		    }else{
        		         $data['bulk_order_quantity'] = 0;
        		    }
    			    return ['httpcode'=>200,'status'=>'success','message'=>'success','data'=>$data];
    		    }else{
    	        return ['httpcode'=>200,'status'=>'error','message'=>"Product Not Found "];
    		    }  
    		}else{
    	        return ['httpcode'=>200,'status'=>'error','message'=>"Product Not Found "];
    		}      
         }
        }
}
