<?php

namespace App\Http\Controllers\Api\Odoo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Tax;
use App\Models\AdminProduct;
use App\Models\ProductType;
use App\Models\PrdPrice;
use App\Models\PrdImage;
use App\Models\PrdStock;
use App\Models\Language;
use App\Models\CmsContent;
use App\Models\PrdAttribute;
use App\Models\PrdAttributeValue;
use App\Models\AssignedAttribute;
use App\Models\AssociatProduct;
use App\Models\PrdAssignedTag;
use App\Models\RelatedProduct;
use App\Models\AssConfigAttribute;
use App\Models\PrdOffer;
use App\Models\PrdReview;
use App\Models\VariableProdHist;
use App\Models\ProductVideo;
use App\Models\ProdDimension;
use App\Models\Tag;
use App\Models\AdminProductImage;
use App\Models\ProductImage;
use App\Models\PrdAdminImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Validator;
use DB;

class OdooProductController extends Controller
{
    public function product_creation(Request $request){
    $validator = Validator::make($request->all(), [
        'unique_id'=>['required','numeric','unique:prd_products,odoo_id,1,is_deleted'],
        'name'=>['required','unique:prd_products,name,1,is_deleted'],
        'product_type'=> ['required','in:1,2'],
        'sku'   =>  ['required_if:product_type,1'],
        'category_id'   =>  ['required','numeric'],
        'sub_category_id' => ['required','numeric'],
        'brand_id'=>['nullable','numeric'],
        'tax_id'=>['required','numeric'],
        'tag_id'=>['required','numeric'],
        'short_desc'   =>  ['required'],
        'long_desc'   =>  ['nullable'],
        'content'   =>  ['nullable'],
        'specification'   =>  ['nullable'],
        'daily_deals'=> ['required','in:0,1'],
        'is_featured'=> ['required','in:0,1'],
        'out_of_stock_selling'=> ['required','in:0,1'],
        'price'=>['required_if:product_type,1','numeric','min:0'],
        'sale_price'=>['nullable','numeric','min:0'],
        'sale_start_date' => ['nullable', 'date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
        'sale_end_date' => ['nullable', 'date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
        'stock'=>['required_if:product_type,1','numeric','min:0'],
        'min_order'=>['required','numeric','min:0'],
        'bulk_order'=>['required','numeric','min:0'],
        'weight'=>['required_if:product_type,1','numeric','min:0'],
        'length'=>['required_if:product_type,1','numeric','min:0'],
        'width'=>['required_if:product_type,1','numeric','min:0'],
        'height'=>['required_if:product_type,1','numeric','min:0'],
        'image'=>['required','mimes:jpeg,jpg,png'],
        'video'=>['nullable'],
        'is_active'=> ['required','in:0,1'],
        'attrs'=>['required_if:product_type,2','array'],
        'combination'=>['required_if:product_type,2']
        ]);
       
        if ($validator->passes()){

            $images =   $request->file('image'); 
            $videos = $request->file('video');

            $lang_id=1;
            $prd['name']              = $request->name;
            $prd['odoo_id']           = $request->unique_id;
            $prd['product_type']      = $request->product_type;
            $prd['category_id']       = $request->category_id;
            $prd['sub_category_id']   = $request->sub_category_id;
            
            $prd['short_desc_cnt_id'] = $this->addCmsContent(NULL,1,$request->short_desc);
            if($request->long_desc)
            {
            $prd['desc_cnt_id']       = $this->addCmsContent(NULL,1,$request->long_desc);    
            }
            if($request->content)
            {
            $prd['content_cnt_id']    = $this->addCmsContent(NULL,1,$request->content);    
            }
            if($request->specification)
            {
            $prd['spec_cnt_id']       = $this->addCmsContent(NULL,1,$request->specification);    
            }
            $prd['brand_id']          = $request->brand_id;
            $prd['tax_id']            = $request->tax_id;
            $prd['tag_id']            = $request->tag_id;
            $prd['daily_deals']       = $request->daily_deals;
            $prd['is_featured']       = $request->is_featured;
            $prd['out_of_stock_selling'] = $request->out_of_stock_selling;
            $prd['min_order']         = $request->min_order;
            $prd['bulk_order']        = $request->bulk_order;
            $prd['sku']               = $request->sku;
            $prd['is_active']         = $request->is_active;
            $prd['created_by']        = 1;
            $prd['is_approved']       = 1;
            $prd['platform']          = 'odoo';
            $prd['is_deleted']        = 0;
            $prd['created_at']        = date("Y-m-d H:i:s");
            //return $prd;die;
            
            $prdId = Product::create($prd)->id;
            Product::where('id',$prdId)->update(['name_cnt_id'=>$this->addCmsContent(NULL,1,$request->name)]);

            if($images){ foreach($images as $k=>$image){
            $imgName            =   time().'.'.$image->extension();
            $path               =   '/app/public/products/'.$prdId;
            $destinationPath    =   storage_path($path.'/thumb');
            $img                =   Image::make($image->path()); 
            if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
            $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
            $destinationPath    =   storage_path($path); 
            $image->move($destinationPath.'/', $imgName);
            $imgUpload          =   uploadFile($path,$imgName);
            $thumbUpload        =   uploadFile($path.'/thumb',$imgName);
            if($imgUpload){
                PrdImage::create(['prd_id'=>$prdId,'image'=>$path.'/'.$imgName,'thumb'=>$path.'/thumb/'.$imgName,'created_by'=>auth()->user()->id]);
            }
        } }
        
     
        
        if($videos){
            $vidName            =   time().'.'.$videos->extension();
            $path               =   '/app/public/products/'.$prdId; 
            $destinationPath    =   storage_path($path);
            $videos->move($destinationPath, $vidName);

            $vidUpload          =   uploadFile($path,$vidName);
         
            if($vidUpload){
                ProductVideo::create(['prd_id'=>$prdId,'video'=>$path.'/'.$vidName,'created_by'=>auth()->user()->id]);
            }
         }  

            if($request->product_type==1)
            {
                PrdPrice::create(['prd_id'=>$prdId,'price'=>$request->price,'sale_price'=>$request->sale_price,'sale_start_date'=>$request->sale_start_date,'sale_end_date'=>$request->sale_end_date,'is_active'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'created_by'=>1,'platform'=>'odoo']);
                ProdDimension::create(['prd_id'=>$prdId,'weight'=>$request->weight,'height'=>$request->height,'length'=>$request->length,'width'=>$request->width,'is_active'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'created_by'=>1]);
                PrdStock::create(['type'=>'add','prd_id'=>$prdId,'qty'=>$request->stock,'rate'=>$request->price,'platform'=>'odoo']);
            }
            else
            {
    
     foreach($request->attrs as $row)
    {
        $attr_arr['name'] = $row['attr'];
            $attr_arr['name_cnt_id'] = $this->addCmsContent(NULL,1,$row['attr']);
            $attr_arr['type'] = "text";
            $attr_arr['is_active'] = 1;
            $attr_arr['created_by'] = 1;
            $attr_arr['updated_by'] = 1;
            $attr_arr['is_deleted'] = 0;
            $attr_arr['created_at'] = date("Y-m-d H:i:s");
           
            $attr_1_id           =   PrdAttribute::create($attr_arr)->id;
        foreach($row['attribute_option'] as $rows)
        {
            $attr_1_img = "";
                        if(isset($rows['option_img']))
                        {
                            
                           $image = $imgName = "";
                            
                            $image = $rows['option_img']; 
                            $imgName            =   time().'.'.$image->extension();
                            $path               =   '/app/public/products/attributes/'.$attr_1_id;
                            $img                =   Image::make($image->path()); 
                            $destinationPath    =   storage_path($path); 
                            $image->move($destinationPath.'/', $imgName);
                            $imgUpload          =   uploadFile($path,$imgName);
                            $attr_1_img = $path.'/'.$imgName;
                            
                            
                        }
           $attr_val_id=PrdAttributeValue::create(['attr_id'=>$attr_1_id,'name'=>$rows['option'],'image'=>$attr_1_img,'created_by'=>1])->id;              
        }
      }

      //Combination array
      foreach($request->combination as $rowc)
    {
        $prd_name = $request->name." ".$rowc['Attr1']."-".$rowc['Attr2'];
        $prd1['name']              = $prd_name;
            $prd1['product_type']      = $request->product_type;
            $prd1['category_id']       = $request->category_id;
            $prd1['sub_category_id']   = $request->sub_category_id;
            //$prd1['name_cnt_id']       = $this->addCmsContent(NULL,1,$prd_name);
            $prd1['short_desc_cnt_id'] = $this->addCmsContent(NULL,1,$request->short_desc);
            if($request->long_desc)
            {
            $prd1['desc_cnt_id']       = $this->addCmsContent(NULL,1,$request->long_desc);    
            }
            if($request->content)
            {
            $prd1['content_cnt_id']    = $this->addCmsContent(NULL,1,$request->content);    
            }
            if($request->specification)
            {
            $prd1['spec_cnt_id']       = $this->addCmsContent(NULL,1,$request->specification);    
            }
            $prd1['brand_id']          = $request->brand_id;
            $prd1['out_of_stock_selling'] = $request->out_of_stock_selling;
            $prd1['min_order']         = $request->min_order;
            $prd1['bulk_order']        = $request->bulk_order;
            $prd1['sku']               = $rowc['sku'];
            $prd1['is_active']         = $request->is_active;
            $prd1['created_by']        = 1;
            $prd1['is_approved']       = 0;
            $prd1['visible']           = 0;
            $prd1['platform']          = 'odoo';
            $prd1['is_deleted']        = 0;
            $prd1['created_at']        = date("Y-m-d H:i:s");
            //return $prd;die;
            
            $var_prdId = Product::create($prd1)->id;
            Product::where('id',$var_prdId)->update(['name_cnt_id'=>$this->addCmsContent(NULL,1,$prd_name)]);

            //Price and dimension
            PrdPrice::create(['prd_id'=>$var_prdId,'price'=>$rowc['price'],'sale_price'=>$rowc['sale_price'],'sale_start_date'=>$rowc['sale_start_date'],'sale_end_date'=>$rowc['sale_end_date'],'is_active'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'created_by'=>1,'platform'=>'odoo']);
            ProdDimension::create(['prd_id'=>$var_prdId,'weight'=>$rowc['weight'],'height'=>$rowc['height'],'length'=>$rowc['length'],'width'=>$rowc['width'],'is_active'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'created_by'=>1]);
            PrdStock::create(['type'=>'add','prd_id'=>$var_prdId,'qty'=>$rowc['stock'],'rate'=>$rowc['price'],'platform'=>'odoo']);
            //end price and dimension

            if(isset($rowc['Attr1']))
            {
                $attrval=PrdAttributeValue::where('name',$rowc['Attr1'])->orderBy('id','DESC')->first();
                if($attrval)
                {
                  $attr_id = $attrval->attr_id;  
                  $attr_id_val = $attrval->id;
                  $create = AssignedAttribute::create(['prd_id'=>$var_prdId,'attr_id'=>$attr_id,'attr_val_id'=>$attr_id_val,'attr_value'=>$rowc['Attr1'],'created_by'=>1,'created_at'=>date("Y-m-d H:i:s")]);

                }

            }

            if(isset($rowc['Attr2']))
            {
                $attrval=PrdAttributeValue::where('name',$rowc['Attr2'])->orderBy('id','DESC')->first();
                if($attrval)
                {
                  $attr_id = $attrval->attr_id;  
                  $attr_id_val = $attrval->id;
                  $create = AssignedAttribute::create(['prd_id'=>$var_prdId,'attr_id'=>$attr_id,'attr_val_id'=>$attr_id_val,'attr_value'=>$rowc['Attr2'],'created_by'=>1,'created_at'=>date("Y-m-d H:i:s")]);

                }

            }

            $associate_prd = AssociatProduct::create(['prd_id'=>$prdId,'ass_prd_id'=>$var_prdId,'platform'=>'odoo','created_at'=>date("Y-m-d H:i:s")]);
              }
            }//end of config product

            return ['httpcode'=>'200','status'=>'success','message'=>'Product Created Successfully','data'=>['unique_id'=>$prdId]];
        }
        else
        {
         return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];   
        }
    }

    public function product_edit(Request $request){
    $validator = Validator::make($request->all(), [
        'product_id'   =>  ['required','numeric'],
        'unique_id'=>['required','unique:prd_products,odoo_id,'.$request->product_id.',id'],
        'name'=>['required','unique:prd_products,name,'.$request->product_id.',id'],
        'product_type'=> ['required','in:1,2'],
        'sku'   =>  ['required_if:product_type,1'],
        'category_id'   =>  ['required','numeric'],
        'sub_category_id' => ['required','numeric'],
        'brand_id'=>['nullable','numeric'],
        'tax_id'=>['required','numeric'],
        'tag_id'=>['required','numeric'],
        'short_desc'   =>  ['required'],
        'long_desc'   =>  ['nullable'],
        'content'   =>  ['nullable'],
        'specification'   =>  ['nullable'],
        'daily_deals'=> ['required','in:0,1'],
        'is_featured'=> ['required','in:0,1'],
        'out_of_stock_selling'=> ['required','in:0,1'],
        'price'=>['required_if:product_type,1','numeric','min:0'],
        'sale_price'=>['nullable','numeric','min:0'],
        'sale_start_date' => ['nullable', 'date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
        'sale_end_date' => ['nullable', 'date_format:Y-m-d','after_or_equal:'.date('Y-m-d')],
        'stock'=>['required_if:product_type,1','numeric','min:0'],
        'min_order'=>['required','numeric','min:0'],
        'bulk_order'=>['required','numeric','min:0'],
        'weight'=>['required_if:product_type,1','numeric','min:0'],
        'length'=>['required_if:product_type,1','numeric','min:0'],
        'width'=>['required_if:product_type,1','numeric','min:0'],
        'height'=>['required_if:product_type,1','numeric','min:0'],
        'image'=>['nullable','mimes:jpeg,jpg,png'],
        'video'=>['nullable'],
        'is_active'=> ['required','in:0,1'],
        'attrs'=>['required_if:product_type,2','array'],
        'combination'=>['required_if:product_type,2']
        ]);
        if ($validator->passes()){
            $prdId = $request->product_id;
            $product = Product::where('id',$prdId)->first();
            if(!$product)
            {
                return ['httpcode'=>'404','status'=>'error','message'=>'No product found','error'=>'No product found']; 
            }

            $images =   $request->file('image'); 
            $videos = $request->file('video');

            $lang_id=1;
            $prd['name']              = $request->name;
            $prd['odoo_id']           = $request->unique_id;
            $prd['product_type']      = $request->product_type;
            $prd['category_id']       = $request->category_id;
            $prd['sub_category_id']   = $request->sub_category_id;
            $prd['name_cnt_id']       = $this->addCmsContent($product->name_cnt_id,1,$request->name);
            $prd['short_desc_cnt_id'] = $this->addCmsContent($product->short_desc_cnt_id,1,$request->short_desc);
            
            $prd['desc_cnt_id']       = $this->addCmsContent($product->desc_cnt_id,1,$request->long_desc);    
            
            $prd['content_cnt_id']    = $this->addCmsContent($product->content_cnt_id,1,$request->content); 
            
            $prd['spec_cnt_id']       = $this->addCmsContent($product->spec_cnt_id,1,$request->specification);  
            $prd['brand_id']          = $request->brand_id;
            $prd['tax_id']            = $request->tax_id;
            $prd['tag_id']            = $request->tag_id;
            $prd['daily_deals']       = $request->daily_deals;
            $prd['is_featured']       = $request->is_featured;
            $prd['out_of_stock_selling'] = $request->out_of_stock_selling;
            $prd['min_order']         = $request->min_order;
            $prd['bulk_order']        = $request->bulk_order;
            $prd['sku']               = $request->sku;
            $prd['is_active']         = $request->is_active;
            $prd['created_by']        = 1;
            $prd['is_approved']       = 1;
            $prd['is_deleted']        = 0;
            $prd['created_at']        = date("Y-m-d H:i:s");
            //return $prd;die;
            
            $update = Product::where('id',$prdId)->update($prd);

            if($images){ foreach($images as $k=>$image){
            $imgName            =   time().'.'.$image->extension();
            $path               =   '/app/public/products/'.$prdId;
            $destinationPath    =   storage_path($path.'/thumb');
            $img                =   Image::make($image->path()); 
            if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
            $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
            $destinationPath    =   storage_path($path); 
            $image->move($destinationPath.'/', $imgName);
            $imgUpload          =   uploadFile($path,$imgName);
            $thumbUpload        =   uploadFile($path.'/thumb',$imgName);
            if($imgUpload){
                PrdImage::create(['prd_id'=>$prdId,'image'=>$path.'/'.$imgName,'thumb'=>$path.'/thumb/'.$imgName,'created_by'=>auth()->user()->id]);
            }
        } }
        
     
        
        if($videos){
            $vidName            =   time().'.'.$videos->extension();
            $path               =   '/app/public/products/'.$prdId; 
            $destinationPath    =   storage_path($path);
            $videos->move($destinationPath, $vidName);

            $vidUpload          =   uploadFile($path,$vidName);
         
            if($vidUpload){
                ProductVideo::create(['prd_id'=>$prdId,'video'=>$path.'/'.$vidName,'created_by'=>auth()->user()->id]);
            }
         }  

            if($request->product_type==1)
            {
                PrdPrice::where(['prd_id'=>$prdId,'is_deleted'=>0])->update(['price'=>$request->price,'sale_price'=>$request->sale_price,'sale_start_date'=>$request->sale_start_date,'sale_end_date'=>$request->sale_end_date,'is_deleted'=>0,'updated_at'=>date("Y-m-d H:i:s"),'created_by'=>1]);
                ProdDimension::where(['prd_id'=>$prdId,'is_deleted'=>0])->update(['weight'=>$request->weight,'height'=>$request->height,'length'=>$request->length,'width'=>$request->width,'is_deleted'=>0,'updated_at'=>date("Y-m-d H:i:s"),'created_by'=>1]);
                PrdStock::where(['prd_id'=>$prdId,'is_deleted'=>0,'type'=>'add','platform'=>'odoo'])->update(['type'=>'add','prd_id'=>$prdId,'qty'=>$request->stock,'rate'=>$request->price,'platform'=>'odoo']);
            }
            else
            {
    
     foreach($request->attrs as $row)
    {
        $attr_arr['name'] = $row['attr'];
            $attr_arr['name_cnt_id'] = $this->addCmsContent(NULL,1,$row['attr']);
            $attr_arr['type'] = "text";
            $attr_arr['is_active'] = 1;
            $attr_arr['created_by'] = 1;
            $attr_arr['updated_by'] = 1;
            $attr_arr['is_deleted'] = 0;
            $attr_arr['created_at'] = date("Y-m-d H:i:s");
           
            $attr_1_id           =   PrdAttribute::create($attr_arr)->id;
        foreach($row['attribute_option'] as $rows)
        {
            $attr_1_img = "";
                        if(isset($rows['option_img']))
                        {
                            
                           $image = $imgName = "";
                            
                            $image = $rows['option_img']; 
                            $imgName            =   time().'.'.$image->extension();
                            $path               =   '/app/public/products/attributes/'.$attr_1_id;
                            $img                =   Image::make($image->path()); 
                            $destinationPath    =   storage_path($path); 
                            $image->move($destinationPath.'/', $imgName);
                            $imgUpload          =   uploadFile($path,$imgName);
                            $attr_1_img = $path.'/'.$imgName;
                            
                            
                        }
           $attr_val_id=PrdAttributeValue::create(['attr_id'=>$attr_1_id,'name'=>$rows['option'],'image'=>$attr_1_img,'created_by'=>1])->id;              
        }
      }

      //Combination array
      foreach($request->combination as $rowc)
    {
        $prd_name = $request->name." ".$rowc['Attr1']."-".$rowc['Attr2'];
        $prd1['name']              = $prd_name;
            $prd1['product_type']      = $request->product_type;
            $prd1['category_id']       = $request->category_id;
            $prd1['sub_category_id']   = $request->sub_category_id;
            //$prd1['name_cnt_id']       = $this->addCmsContent(NULL,1,$prd_name);
            $prd1['short_desc_cnt_id'] = $this->addCmsContent(NULL,1,$request->short_desc);
            if($request->long_desc)
            {
            $prd1['desc_cnt_id']       = $this->addCmsContent(NULL,1,$request->long_desc);    
            }
            if($request->content)
            {
            $prd1['content_cnt_id']    = $this->addCmsContent(NULL,1,$request->content);    
            }
            if($request->specification)
            {
            $prd1['spec_cnt_id']       = $this->addCmsContent(NULL,1,$request->specification);    
            }
            $prd1['brand_id']          = $request->brand_id;
            $prd1['out_of_stock_selling'] = $request->out_of_stock_selling;
            $prd1['min_order']         = $request->min_order;
            $prd1['bulk_order']        = $request->bulk_order;
            $prd1['sku']               = $rowc['sku'];
            $prd1['is_active']         = $request->is_active;
            $prd1['created_by']        = 1;
            $prd1['is_approved']       = 0;
            $prd1['visible']           = 0;
            $prd1['platform']          = 'odoo';
            $prd1['is_deleted']        = 0;
            $prd1['created_at']        = date("Y-m-d H:i:s");
            //return $prd;die;
            
            $var_prdId = Product::create($prd1)->id;
            Product::where('id',$var_prdId)->update(['name_cnt_id'=>$this->addCmsContent(NULL,1,$prd_name)]);

            //Price and dimension
            PrdPrice::create(['prd_id'=>$var_prdId,'price'=>$rowc['price'],'sale_price'=>$rowc['sale_price'],'sale_start_date'=>$rowc['sale_start_date'],'sale_end_date'=>$rowc['sale_end_date'],'is_active'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'created_by'=>1]);
            ProdDimension::create(['prd_id'=>$var_prdId,'weight'=>$rowc['weight'],'height'=>$rowc['height'],'length'=>$rowc['length'],'width'=>$rowc['width'],'is_active'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'created_by'=>1]);
            PrdStock::create(['type'=>'add','prd_id'=>$var_prdId,'qty'=>$rowc['stock'],'rate'=>$rowc['price'],'platform'=>'odoo']);
            //end price and dimension

            if(isset($rowc['Attr1']))
            {
                $attrval=PrdAttributeValue::where('name',$rowc['Attr1'])->orderBy('id','DESC')->first();
                if($attrval)
                {
                  $attr_id = $attrval->attr_id;  
                  $attr_id_val = $attrval->id;
                  $create = AssignedAttribute::create(['prd_id'=>$var_prdId,'attr_id'=>$attr_id,'attr_val_id'=>$attr_id_val,'attr_value'=>$rowc['Attr1'],'created_by'=>1,'created_at'=>date("Y-m-d H:i:s")]);

                }

            }

            if(isset($rowc['Attr2']))
            {
                $attrval=PrdAttributeValue::where('name',$rowc['Attr2'])->orderBy('id','DESC')->first();
                if($attrval)
                {
                  $attr_id = $attrval->attr_id;  
                  $attr_id_val = $attrval->id;
                  $create = AssignedAttribute::create(['prd_id'=>$var_prdId,'attr_id'=>$attr_id,'attr_val_id'=>$attr_id_val,'attr_value'=>$rowc['Attr2'],'created_by'=>1,'created_at'=>date("Y-m-d H:i:s")]);

                }

            }
            $delete_ass_prd = AssociatProduct::where('prd_id',$prdId)->update(['is_deleted'=>1]);
            $associate_prd = AssociatProduct::create(['prd_id'=>$prdId,'ass_prd_id'=>$var_prdId,'platform'=>'odoo','created_at'=>date("Y-m-d H:i:s")]);
              }
            }//end of config product

            return ['httpcode'=>'200','status'=>'success','message'=>'Product Edited Successfully'];
        }
        else
        {
         return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];   
        }
    }

    public function product_delete(Request $request){
    $validator = Validator::make($request->all(), [
        'product_id'   =>  ['required','numeric']]);
        if ($validator->passes()){
            $prdId = $request->product_id;
            $product = Product::where('id',$prdId)->first();
            if(!$product)
            {
                return ['httpcode'=>'404','status'=>'error','message'=>'No product found','error'=>'No product found']; 
            }
            else
            {
                $product->is_deleted=1;
                $product->save();
                return ['httpcode'=>'200','status'=>'success','message'=>'Product Deleted Successfully'];
            }
        }
        else
        {
         return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];   
        }
    }

    public function product_image_delete(Request $request){
    $validator = Validator::make($request->all(), [
        'image_id'   =>  ['required','numeric']]);
        if ($validator->passes()){

            $image =PrdImage::where('id',$request->image_id)->first();
            if($image)
            {
                $image->is_deleted=1;
                $image->save();
                return ['httpcode'=>'200','status'=>'success','message'=>'Image Deleted Successfully'];
            }
            else
            {
               return ['httpcode'=>'404','status'=>'error','message'=>'Not found','error'=>'Not found'];  
            }
        }
        else
        {
         return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];   
        }
    }

    function addCmsContent($cntId,$l, $cnt){
        if($cnt){
        if($cntId){
        $qry                =   CmsContent::where('cnt_id',$cntId)->where('is_deleted',0)->first();
        if($qry)
        {
            CmsContent::where('cnt_id',$cntId)->update(['content'=>$cnt,'updated_by'=>1]);
            $insertId = $qry->cnt_id;
        }
        else
        {
            $insertId=CmsContent::create(['cnt_id'=>$cntId,'lang_id'=>$l,'content'=>$cnt,'created_by'=>1])->id;
            $insertId = CmsContent::where('id',$insertId)->first()->cnt_id;
        }
       }
       else{
            $cms            =   CmsContent::orderBy('cnt_id','desc')->first(); if($cms){ $cntId = ($cms->cnt_id+1); }else{ $cntId = 1; }
            $cmscont        =   CmsContent::create(['cnt_id'=>$cntId,'lang_id'=>$l,'content'=>$cnt,'created_by'=>1])->id;
            $insertId = $cntId;
        }
        }
        else
        {
            $insertId =NULL;
        }
        
        return $insertId;

    }

    //product Detail
     public function product_detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'product_id'   =>  ['required','numeric']]);
        if ($validator->passes()){
            $prod_data= Product::where('is_deleted',0)->where('is_approved',1)->where('visible',1)->where('id',$request->product_id)->first();
            
            if(!empty($prod_data))
            {
                $lang=1;
                    $prd_list['product_id']=$prod_data->id;
                    $prd_list['product_name']=$prod_data->name;//$this->get_content($prod_data->name_cnt_id,$lang);
                    if($prod_data->product_type==1)
                    {
                    $prd_list['product_type']=1;  
                    if($prod_data->min_order){
                    $prd_list['min_order']=$prod_data->min_order;
                    }else{
                    $prd_list['min_order']=0;
                    }
                    if($prod_data->bulk_order){
                    $prd_list['bulk_order']=$prod_data->bulk_order;
                    }else{
                    $prd_list['bulk_order']=0;
                    }                       
                        // $price=$this->get_price($prod_data->id,$type=1,$login);
                        // foreach ($price as $item) {
                        //     foreach ($item as $key => $value) {
                        //         $prd_list[$key] = $value;
                        //     } 
                        // }
                    $prd_list['price']=$prod_data->prdPrice->price;
                    $prd_list['sale_price']=$prod_data->prdPrice->sale_price;
                    $prd_list['sale_start_date']=$prod_data->prdPrice->sale_start_date;
                    $prd_list['sale_end_date']=$prod_data->prdPrice->sale_end_date;

                    //Dimension
                    $prd_list['weigth']=$prod_data->prdimension->weigth;
                    $prd_list['length']=$prod_data->prdimension->length;
                    $prd_list['width']=$prod_data->prdimension->width;
                    $prd_list['height']=$prod_data->prdimension->height;
                    }
                    else
                    {
                     $prd_list['product_type']=2; 
                     
                    // $minprice_prd_id=$this->min_price_product($prod_data->id);
                    // $price=$this->get_price($minprice_prd_id,$type=2,$login);
                    // foreach ($price as $item) {
                    //         foreach ($item as $key => $value) {
                    //             $prd_list[$key] = $value;
                    //         } 
                    //     }
                        
                    }
                    $prd_list['sku']=$prod_data->sku;
                    $prd_list['category_id']=$prod_data->category_id;
                    $prd_list['category_name']=$this->get_content($prod_data->category->cat_name_cid,$lang);
                    $prd_list['subcategory_id']=$prod_data->sub_category_id;
                    $prd_list['subcategory_name']=$this->get_content($prod_data->subCategory->sub_name_cid,$lang);
                    if($prod_data->brand_id)
                    {
                    $prd_list['brand_id']=$prod_data->brand_id;
                    $prd_list['brand_name']=$this->get_content($prod_data->brand->brand_name_cid,$lang);
                    }
                    else
                    {
                    $prd_list['brand_id']='';
                    $prd_list['brand_name']='';
                    }
                    $prd_list['is_featured']=$prod_data->is_featured;
                    $prd_list['short_description']=$this->get_content($prod_data->short_desc_cnt_id,$lang);
                    $prd_list['long_description']=$this->get_content($prod_data->desc_cnt_id,$lang);
                    $prd_list['content']=$this->get_content($prod_data->content_cnt_id,$lang);
                    $prd_list['specification']=$this->get_content($prod_data->spec_cnt_id,$lang);
                    if($prod_data->spec_cnt_id)
                    {
                        $result='';
                        $spec =$this->get_content($prod_data->spec_cnt_id,$lang);
                        
                        if($spec!=false){
                            try {
                            $quill = new \DBlackborough\Quill\Render($spec);
                            $result = $quill->render();
                        }
                        catch(\Exception $e){
                            //echo $e->getMessage();
                        }
                        }
                        else
                        {
                            $result=false;
                        }
                        
                       $prd_list['quill_specification'] = $result;  
                    }
                    else
                    {
                        $prd_list['quill_specification']    = false;
                    }
                    if($prod_data->product_type == 1)
                    {   
                    $prd_list['stock']=$prod_data->prdStock($prod_data->id);
                    $prd_list['is_out_of_stock']=$prod_data->is_out_of_stock;
                    $prd_list['out_of_stock_selling']=$prod_data->out_of_stock_selling;
                    }
                    
                       
                    $prd_list['tag']=$this->get_product_tag($prod_data->id,$lang); 
                    $prd_list['image']=$this->get_product_image($prod_data->id);
                     
                    $products=$prd_list;
                    $associative_prd=$listddar=[];
                    
                     //ASSOCIATIVE products
                    if($prod_data->product_type == 2)
                    {  
                        // $prices = $this->config_product_price($prod_data->id); 
                        // $special_ofr_available=$this->get_special_ofr_value($prices,$prod_data->id); 
                        $prd_ass = AssociatProduct::where('prd_id',$prod_data->id)->where('is_deleted',0)->get();
                        if(count($prd_ass)>0)
                        {
                            foreach($prd_ass as $rows)
                            {
                            $product_visibility= Product::where('id',$rows->ass_prd_id)->where('is_active',1)->where('is_deleted',0)->first();
                            if($product_visibility){
                            //$associative_prd[]=$this->ass_related_product($rows->ass_prd_id,$lang);
                            
                                $associative_prd[]=$this->ass_related_product1($product_visibility->id,$lang);
                            
                              $assignedAttr       =   AssignedAttribute::where('is_deleted',0)->where('prd_id',$product_visibility->id)->orderBy('attr_id','DESC')->groupBy('attr_id')->get();
                              foreach($assignedAttr as $assrt)
                                  {
                                    $ddatr['attr'] = $assrt->PrdAttr->name;
                                    $assignedAttrval       =   AssignedAttribute::where('is_deleted',0)->where('prd_id',$product_visibility->id)->where('attr_id',$assrt->attr_id)->get();
                                    $ddatr['options']=[];
                                    foreach($assignedAttrval as $asval)
                                    {
                                    $ddatr['options'][] = $asval->attrValue->name;
                                    }

                                    $listddar[] =  $ddatr;
                                  }
                             }
                            }
                        }
                        else
                        {
                            $associative_prd=[];
                        }


                  }
                  else
                  {
                    $associative_prd=[];
                  }
                  $variants_list=[];
                  //Variants List   
                if($associative_prd){
                    //dd($associative_prd);
                    $variants_list = array();
                        foreach($associative_prd as $asso_prod){
                        $attr_value=$asso_prod['attr_value'];
                        $attr_name=$asso_prod['attr_name'];
                        $data['pro_id']=$asso_prod['product_id'];
                        if($asso_prod['sub_attributes']){
                        foreach($asso_prod['sub_attributes'] as $row1){
                        
                        if($attr_value){
                        $data['combination']=$attr_value." ".$attr_name ." - ".$row1['attr_value']." ".$row1['attr_name'];
                        }else{
                        $data['variants']=$row1['attr_value']." ".$row1['attr_name'];
                        }
                        $data['price']=$row1['price'];
                        $data['sale_price']=$row1['sale_price'];
                        $data['sale_start_date']=$row1['sale_start_date'];
                        $data['sale_end_date']=$row1['sale_end_date'];
                        $data['sku']=$row1['sku'];
                        $data['stock']=$row1['stock'];
                        $data['is_out_of_stock']=$row1['is_out_of_stock'];
                        $data['out_of_stock_selling']=$row1['out_of_stock_selling'];
                        $data['min_order_qty']=$asso_prod['min_order'];
                        $data['bulk_order_qty']=$asso_prod['bulk_order'];
                        $data['image']=$row1['image'];

                        $data['weigth']=$row1['weigth'];
                        $data['length']=$row1['length'];
                        $data['width']=$row1['width'];
                        $data['height']=$row1['height'];
                        // $price=$this->get_price($data['pro_id'],$type=2,$login);
                        // foreach ($price as $item) {
                        //     foreach ($item as $key => $value) {
                        //         $data[$key] = $value;
                        //     } 
                        // }   
                        
                        }
                        }else{
                            $data['combination']=$attr_value." ".$attr_name;
                            $data['price']=$asso_prod['price'];
                            $data['sale_price']=$asso_prod['sale_price'];
                            $data['sale_start_date']=$asso_prod['sale_start_date'];
                            $data['sale_end_date']=$asso_prod['sale_end_date'];
                            $data['sku']=$asso_prod['sku'];
                            $data['stock']=$asso_prod['stock'];
                            $data['is_out_of_stock']=$asso_prod['is_out_of_stock'];
                            $data['out_of_stock_selling']=$asso_prod['out_of_stock_selling'];
                            $data['min_order_qty']=$asso_prod['min_order'];
                            $data['bulk_order_qty']=$asso_prod['bulk_order'];
                            $data['image']=$asso_prod['image'];

                            $data['weigth']=$asso_prod['weigth'];
                            $data['length']=$asso_prod['length'];
                            $data['width']=$asso_prod['width'];
                            $data['height']=$asso_prod['height'];
                            // $price=$this->get_price($data['pro_id'],$type=2,$login);
                            // foreach ($price as $item) {
                            //     foreach ($item as $key => $value) {
                            //         $data[$key] = $value;
                            //     } 
                            // }
                            
                        }
                    
                    $variants_list[] = $data;
                    }
                } 


                return response()->json(['httpcode'=>200,'status'=>'success','data'=>['product'=>$products,'attribute_list'=>$listddar,'varaiants_list'=>$variants_list,'currency'=>getCurrency()->name]]);
            }
            else
            {
               return response()->json(['httpcode'=>404,'status'=>'error','message'=>'Product not found']); 
            }

        }
        else
        {
            return ['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()];
        }
    }

    function ass_related_product1($prd_id,$lang){
        $data     =   [];
       // dd($login);
        $prod_data       =   AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->orderBy('attr_id','DESC')->groupBy('attr_id')->get();
       //dd( $prod_data  ) ;
           if(count($prod_data)>0)   { 
                foreach($prod_data as $row)  {
                    //dd($row);
                    //$attr_list['id']=$row->id;
                   $attr_list['attr_id']=$row->attr_id;
                   $attr_list['product_id']=$row->prd_id;
                   if($row->Product->min_order){
                   $attr_list['min_order']=$row->Product->min_order;
                   }else{
                    $attr_list['min_order']=0;
                   }
                   if($row->Product->bulk_order){
                   $attr_list['bulk_order']=$row->Product->bulk_order;
                   }else{
                    $attr_list['bulk_order']=0;
                   }
                   
                    $attr_list['attr_name']=$row->PrdAttr->name;  
                    $attr_list['attr_value']=$row->attr_value;
                    
                    if(isset($row->attrValue->image)){
                    $attr_list['attribute_image']=config('app.storage_url').$row->attrValue->image;
                    }
                    $attr_list['image']=$this->get_product_image($row->prd_id); 
                    
                    //$attr_list['image']=config('app.storage_url').$row->attrValue->image;
                    $arrt_vals['list'][] =$this->inner_attribute($prd_id,$row->attr_id,$row->id,$lang);
                    

                    if(empty($arrt_vals['list'][0]))
                    {
                        
                    $attr_list['sub_attributes'] = [];
                        
                    $attr_list['price']=$row->prdPrice->price;
                    $attr_list['sale_price']=$row->prdPrice->sale_price;
                    $attr_list['sale_start_date']=$row->prdPrice->sale_start_date;
                    $attr_list['sale_end_date']=$row->prdPrice->sale_end_date;
                    $attr_list['stock']=$row->prdStock($row->prd_id);
                    $attr_list['sku']=$row->Product->sku;
                    $attr_list['is_out_of_stock']=$row->Product->is_out_of_stock;
                    $attr_list['out_of_stock_selling']=$row->Product->out_of_stock_selling;
                    
                    //Dimension
                    $attr_list['weigth']=$row->prdimension->weigth;
                    $attr_list['length']=$row->prdimension->length;
                    $attr_list['width']=$row->prdimension->width;
                    $attr_list['height']=$row->prdimension->height;    
                    }
                    else
                    {
                        $attr_list['sub_attributes'] =$this->inner_attribute($prd_id,$row->attr_id,$row->id,$lang);
                    }
                    $data             =   $attr_list;
                }
             }
            else{ $data     =   []; } return $data;
        
    }

    function inner_attribute($prd_id,$attr_id,$rowId,$lang)
    {
       // dd($login);
        $data=[];
        $rowss = AssignedAttribute::where('is_deleted',0)->where('prd_id',$prd_id)->where('attr_id','!=',$attr_id)->where('id','!=',$rowId)->first();
        //$rows1 = AssignedAttribute::where('is_deleted',0)->where('attr_id',$attr_id)->whereNotIn('id',[$rowId])->get();
        //dd($rowId);
                     if($rowss && $rowss->id!=$rowId)
                    {
                        
                        //$atr_inn['id']=$rowss->id;
                        $atr_inn['attr_id']=$rowss->attr_id;
                        $atr_inn['product_id']=$rowss->prd_id;
                        $atr_inn['attr_name']=$rowss->PrdAttr->name; //$this->get_content($rowss->PrdAttr->name_cnt_id,$lang);
                        $atr_inn['attr_value']= $rowss->attr_value;
                        
                        $atr_inn['image']=$this->getProductimages($prd_id);
                        $pricedata = PrdPrice::where('prd_id',$prd_id)->where('is_deleted',0)->orderBy('id','DESC')->first();
                        $price=$sale_price=$sale_start_date=$sale_end_date=NULL;
                        if($pricedata){
                            $price = $pricedata->price;
                            $sale_price = $pricedata->sale_price;
                            $sale_start_date = $pricedata->sale_start_date;
                            $sale_end_date = $pricedata->sale_end_date;
                        }
                        
                         $atr_inn['price']=$price;
                         $atr_inn['sale_price']=$sale_price;
                         $atr_inn['sale_start_date']=$sale_start_date;
                         $atr_inn['sale_end_date']=$sale_end_date;
                         $atr_inn['sku']=$rowss->Product->sku;
                         $atr_inn['stock']=$rowss->prdStock($rowss->prd_id);
                         $atr_inn['sku']=$rowss->Product->sku;
                         $atr_inn['is_out_of_stock']=$rowss->Product->is_out_of_stock;
                         $atr_inn['out_of_stock_selling']=$rowss->Product->out_of_stock_selling;

                         //Dimension
                         $weight=$length=$width=$height=NULL;
                         $dimen = ProdDimension::where('prd_id',$prd_id)->where('is_deleted',0)->orderBy('id','DESC')->first();
                         if($dimen)
                         {
                             $weight=$dimen->weight;
                             $length=$dimen->length;
                             $length=$dimen->width;
                             $height=$dimen->height;
                         }
                        $atr_inn['weigth']=$weight;
                        $atr_inn['length']=$length;
                        $atr_inn['width']=$length;
                        $atr_inn['height']=$height;
                        
                        //$atr_inn['subattr']=$this->inner_attribute_12($rowss->prd_id,$rowss->attr_id,$rowss->id,$lang);
                        $data[]             =   $atr_inn;
                        
                    
                    }return $data;
    }

    function getProductimages($prd_id){
    $rowss = PrdImage::where('is_deleted',0)->where('prd_id',$prd_id)->get();
    $data=[];
    if($rowss){
        foreach($rowss as $row){
        $atr_inn['image'] = config('app.storage_url').$row->image;
        $atr_inn['thumb'] = config('app.storage_url').$row->thumb;
        
          $data[]             =   $atr_inn;
        }
        return $data;
    }
    }

    function get_content($field_id,$lang){ 
     
        if($lang==''){
        $language =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
        $lang=$language->id;    
        }
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $lang)->first();
        if(!empty($content_table)){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }else {
            $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
            $language_id=$language->id;
            $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language_id)->first();
            if(!empty($content_table)){ 
            $return_cont = $content_table->content;
            return $return_cont;
            }else{
                return false;
            }
        }
     }

     function get_product_tag($prd_id,$lang){
        $data     =   [];
        
        $product       =   Product::where('id',$prd_id)->first();
            if($product->tag_id)   {    
                  $val                =   $this->get_content($product->tag->tag_name_cid,$lang);
                  $data[]               =   $val;
            }
            else{ $data     =   []; } return $data;
        
    }

    function get_product_image($prd_id){
        $data     =   [];
        
        //$admin_pro=Product::where('id',$prd_id)->first();
        
        
        $product_seller       =   ProductImage::where('prd_id',$prd_id)->where('is_deleted',0)->get();
        $product_admin       =   PrdAdminImage::where('prd_id',$prd_id)->where('is_deleted',0)->get();
        if(!empty($product_seller))
        {
            foreach($product_seller as $k=>$row){ 
                if($row->image)
                {
                $val['image']       =   config('app.storage_url').$row->image;
                }
                if($row->thumb)
                {
                $val['thumbnail']   =   config('app.storage_url').$row->thumb;
                }
                $data[]             =   $val;
            }
        }
        else if(!empty($product_admin))
        {
            foreach($product_admin as $k=>$row){ 
                if($row->image)
                {
                $val['image']       =   config('app.storage_url').$row->image;
                }
                if($row->thumbnail)
                {
                $val['thumbnail']   =   config('app.storage_url').$row->thumbnail;
                }
                $data[]             =   $val;
            }
        } 
        
        else{ $data     =   []; } return $data;
        
    }
}
