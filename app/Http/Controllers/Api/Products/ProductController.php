<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\CmsContent;
use App\Models\Product;
use App\Models\PrdPrice;
use App\Models\PrdImage;
use App\Models\PrdStock;
use App\Models\ProdDimension;
use App\Models\PrdAttribute;
use App\Models\PrdAttributeValue;
use App\Models\AssignedAttribute;
use App\Models\AssociatProduct;
use App\Models\crm\CrmProduct;
use App\Models\crm\CrmChildProductsMaster;
use App\Models\crm\CrmAssortmentMaster;
use App\Models\crm\CrmPartAssortmentMaster;
use App\Models\crm\CrmPartAssortmentDetails;
use App\Models\crm\CrmProductGroup;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function insert_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unique_id'=>['required','numeric'],
            'seller_id'=>['required','numeric'],
            'product_type'=> ['required','in:1,2'],
            'category_id'=>['nullable','numeric'],
            'sub_category_id'=>['nullable','numeric'],
            'name' => ['required','max:255'],
            'platform'=>['required','in:ecom'],
        ]);

        $input = $request->all();

        if ($validator->passes())
        {
            // $images =   $request->file('image'); 
            
           // $videos = $request->file('video');

            $lang_id=1;
            $prd['name']              = $request->name;
            $prd['unique_id']         = $request->unique_id;
            $prd['seller_id']         = $request->BranchID;
            $prd['product_type']      = $request->product_type;
            // $prd['category_id']       = $request->category_id;
            // $prd['sub_category_id']   = $request->sub_category_id;
            
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
                // {"ops":[{"insert":"test sp\n"}]}
                $spec_cont = array('ops'=>[array("insert"=>$request->specification)]);
                $spec_cont = json_encode($spec_cont);
                $prd['spec_cnt_id']       = $this->addCmsContent(NULL,1,$spec_cont);    
            }
            $prd['brand_id']          = $request->brand_id;
            $prd['weight']            = $request->weight;
            $prd['fixed_price']       = $request->fixed_price;
            $prd['is_out_of_stock']   = 0;
            $prd['min_stock_alert']   = 0;
            $prd['commission']        = 0;
            $prd['commi_type']        = '%';
            $prd['tax_id']            = $request->tax_id;
            $prd['tag_id']            = $request->tag_id;
            $prd['daily_deals']       = $request->daily_deals;
            $prd['is_featured']       = $request->is_featured;
            $prd['out_of_stock_selling'] = $request->out_of_stock_selling;
            $prd['min_order']         = $request->min_order;
            $prd['bulk_order']        = $request->bulk_order;
            $prd['sku']               = $request->sku;
            $prd['is_active']         = 1;
            $prd['created_by']        = $request->created_by;
            $prd['updated_by']        = $request->created_by;
            $prd['is_approved']       = 1;
            $prd['platform']          = 'ecom';
            $prd['is_deleted']        = 0;
            $prd['created_at']        = date("Y-m-d H:i:s");
            
            $prdId = Product::create($prd)->id;
            
            Product::where('id',$prdId)->update(['name_cnt_id'=>$this->addCmsContent(NULL,1,$request->name)]);

            // if($images)
            // {
            //     foreach($images as $k=>$image)
            //     {
            //         $imgName            =   time().'.'.$image->extension();
            //         $path               =   '/app/public/products/'.$prdId;
            //         $destinationPath    =   storage_path($path.'/thumb');
            //         $img                =   Image::make($image->path()); 
                
            //         if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
                    
            //         $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
            //         $destinationPath    =   storage_path($path); 
            //         $image->move($destinationPath.'/', $imgName);
            //         $imgUpload          =   uploadFile($path,$imgName);
            //         $thumbUpload        =   uploadFile($path.'/thumb',$imgName);
            //         if($imgUpload)
            //         {
            //             PrdImage::create(['prd_id'=>$prdId,'image'=>$path.'/'.$imgName,'thumb'=>$path.'/thumb/'.$imgName,'created_by'=>1]);
            //         }
            //     }
            // }
            
            // PrdPrice::create(['prd_id'=>$prdId,'price'=>$request->price,'sale_price'=>$request->sale_price,'sale_start_date'=>$request->sale_start_date,'sale_end_date'=>$request->sale_end_date,'is_active'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'created_by'=>1,'platform'=>'ecom']);

            ProdDimension::create(['prd_id'=>$prdId,'weight'=>$request->weight,'length'=>$request->length,'width'=>$request->width,'height'=>$request->height,'is_active'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'created_by'=>1]);

            PrdStock::create(['seller_id'=>$request->BranchID,'type'=>'add','prd_id'=>$prdId,'qty'=>$request->stock,'rate'=>$request->price,'platform'=>'ecom']);

            // $master =  Product::create([
            //     'unique_id' => $request->unique_id,
            //     'seller_id' => $request->seller_id,
            //     'sku' => $request->sku,
            //     'product_type' => $request->product_type,
            //     'category_id' => $request->category_id,
            //     'sub_category_id' => $request->sub_category_id,
            //     'brand_id' => $request->brand_id,
            //     'tax_id' => $request->tax_id,
            //     'tag_id' => $request->tag_id,
            //     'name' => $request->name,
            //     'name_cnt_id ' => $request->name_cnt_id,
            //     'short_desc_cnt_id ' => $request->short_desc_cnt_id,
            //     'desc_cnt_id' => $request->desc_cnt_id,
            //     'content_cnt_id' => $request->content_cnt_id,
            //     'spec_cnt_id' => $request->spec_cnt_id,
            //     'weight' => $request->weight,
            //     'fixed_price' => $request->fixed_price,
            //     'is_out_of_stock' => $request->is_out_of_stock,
            //     'out_of_stock_selling' => $request->out_of_stock_selling,
            //     'min_stock_alert' => $request->min_stock_alert,
            //     'commission'=> $request->commission,
            //     'commi_type' => $request->commi_type,
            //     'visible' => $request->visible,
            //     'admin_prd_id' => $request->admin_prd_id,
            //     'is_approved' => $request->is_approved,
            //     'approved_at' => $request->approved_at,
            //     'daily_deals' => $request->daily_deals,
            //     'is_featured'=> $request->is_featured,
            //     'min_order' => $request->min_order,
            //     'bulk_order' => $request->bulk_order,
            //     'is_comingsoon' => $request->is_comingsoon,
            //     'occasion_id' => $request->occasion_id,
            //     'is_active' => $request->is_active,
            //     'created_by' => $request->created_by,
            //     'updated_by' => $request->updated_by,
            //     'created_at' => date("Y-m-d H:i:s"),
            //     'is_deleted' => $request->is_deleted,
            //     'platform' => $request->platform,
            // ]);

            // $masterId = $master->id;

            $crmmaster =  CrmProduct::create([
                'prd_id'=>$prdId,
                'PartNumber' => $request->PartNumber,
                'PartDescription' => $request->PartDescription,
                'HSNCode' => $request->HSNCode,
                'PartCategory' => $request->PartCategory,
                'GSTId' => $request->GSTId,
                'NDP' => $request->NDP,
                'Division' => $request->Division,
                'PurchasePrice' => $request->price,
                'SellingPrice' => $request->sale_price,
                'JobId' => $request->JobId,
                'EstimatePrice' => $request->EstimatePrice,
                'MRP' => $request->MRP,
                'MiniStockQty' => $request->MiniStockQty,
                'UnitId' => $request->UnitId,
                'WarrantyId' => $request->WarrantyId,
                'IsSerialNoAcceptable' => $request->IsSerialNoAcceptable,
                'PartSerialNumber' => $request->PartSerialNumber,
                'Discount' => $request->Discount,
                'CreatedDate' => date("Y-m-d H:i:s"),
                'UpdatedDate' => date("Y-m-d H:i:s"),
                'CreatedBy'=> $request->created_by,
                'UpdatedBy' => $request->created_by,
                'Del_Status' => 0,
                'FileName' => $request->FileName,
                'Status' => $request->Status,
                'IsStockIncluded' => $request->IsStockIncluded,
                'IsUshas' => $request->IsUshas,
                'CompanyID'=> $request->CompanyID,
                'BrandID' => $request->brand_id,
                'ColourID' => $request->ColourID,
                'ArticleNo' => $request->ArticleNo,
                'SizeRangeID' => $request->SizeRangeID,
                'ProductBatch' => $request->ProductBatch,
                'BranchID' => $request->BranchID,
                'VendorID' => $request->VendorID,
                'SizeID' => $request->SizeID,
                'ProductStatus' => $request->ProductStatus,
                'ProductGroupId' => $request->ProductGroupId,
                'ProductSubCategoryId' => $request->ProductSubCategoryId,
                'SubBrandId' => $request->SubBrandId,
                'updated_at' => date("Y-m-d H:i:s"),
                'created_at' => date("Y-m-d H:i:s")
            ]);

            $updateProduct = Product::where('id',$prdId)->update(['unique_id' => $request->unique_id]);

            return response()->json(['httpcode'=>200,'success'=>'Successfully registered!','primary_key'=>$prdId]);
        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function update_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unique_id'=>['required','numeric'],
            'product_id'=>['required','numeric'],
            'seller_id'=>['required','numeric'],
            'product_type'=> ['required','in:1,2'],
            'category_id'=>['nullable','numeric'],
            'sub_category_id'=>['nullable','numeric'],
            'name' => ['required','max:255'],
            'platform'=>['required','in:ecom'],
            // 'combination'=>['required_if:product_type,2','array'],
        ]);

        $input = $request->all();

        if ($validator->passes())
        {
            $lang_id = 1;

            // $images = $request->file('image'); 
            
            // $videos = $request->file('video');
        
            $prdId = $request->product_id;

            $products = Product::where('id',$prdId)->where('platform','ecom')->first();

            if($products)            
            {
                $prd['name']              = $request->name;
                $prd['unique_id']         = $request->unique_id;
                $prd['seller_id']         = $request->BranchID;
                $prd['product_type']      = $request->product_type;
                // $prd['category_id']       = $request->category_id;
                // $prd['sub_category_id']   = $request->sub_category_id;
                
                $prd['short_desc_cnt_id'] = $this->addCmsContent($products->short_desc_cnt_id,1,$request->short_desc);
                if($request->long_desc)
                {
                    $prd['desc_cnt_id']       = $this->addCmsContent($products->desc_cnt_id,1,$request->long_desc);    
                }
                if($request->content)
                {
                    $prd['content_cnt_id']    = $this->addCmsContent($products->content_cnt_id,1,$request->content);    
                }
                if($request->specification)
                {
                    // {"ops":[{"insert":"test sp\n"}]}
                    $spec_cont = array('ops'=>[array("insert"=>$request->specification)]);
                    $spec_cont = json_encode($spec_cont);
                    $prd['spec_cnt_id']       = $this->addCmsContent($products->spec_cnt_id,1,$spec_cont);    
                }
                $prd['brand_id']          = $request->brand_id;
                $prd['tax_id']            = $request->tax_id;
                $prd['tag_id']            = $request->tag_id;
                $prd['daily_deals']       = $request->daily_deals;
                $prd['is_featured']       = $request->is_featured;
                $prd['out_of_stock_selling'] = $request->out_of_stock_selling;
                $prd['min_order']         =$request->min_order;
                $prd['bulk_order']        =$request->bulk_order;
                $prd['sku']               = $request->sku;
                $prd['is_active']         = 1;
                $prd['created_by']        = $request->created_by;
                $prd['updated_by']        = $request->created_by;
                $prd['is_approved']       = 1;
                $prd['platform']          = 'ecom';
                $prd['is_deleted']        = 0;
                $prd['created_at']        = date("Y-m-d H:i:s");
                
                Product::where('id',$prdId)->update($prd);

                // if($images)
                // {
                //     foreach($images as $k=>$image)
                //     {
                //         $imgName            =   time().'.'.$image->extension();
                //         $path               =   '/app/public/products/'.$prdId;
                //         $destinationPath    =   storage_path($path.'/thumb');
                //         $img                =   Image::make($image->path()); 
                    
                //         if(!file_exists($destinationPath)) { mkdir($destinationPath, 755, true);}
                        
                //         $img->resize(250, 250, function($constraint){ $constraint->aspectRatio(); })->save($destinationPath.'/'.$imgName);
                //         $destinationPath    =   storage_path($path); 
                //         $image->move($destinationPath.'/', $imgName);
                //         $imgUpload          =   uploadFile($path,$imgName);
                //         $thumbUpload        =   uploadFile($path.'/thumb',$imgName);
                //         if($imgUpload)
                //         {
                //             PrdImage::create(['prd_id'=>$prdId,'image'=>$path.'/'.$imgName,'thumb'=>$path.'/thumb/'.$imgName,'created_by'=>1]);
                //         }
                //     }
                // }
                
                // PrdPrice::where('prd_id',$prdId)->update(['price'=>$request->price,'sale_price'=>$request->sale_price,'sale_start_date'=>$request->sale_start_date,'sale_end_date'=>$request->sale_end_date,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'created_by'=>1,'platform'=>'ecom']);

                ProdDimension::where('prd_id',$prdId)->update(['weight'=>$request->weight,'length'=>$request->length,'width'=>$request->width,'height'=>$request->height,'is_active'=>1,'is_deleted'=>0,'created_at'=>date("Y-m-d H:i:s"),'created_by'=>1]);

                PrdStock::where('prd_id',$prdId)->update(['seller_id'=>$request->BranchID,'type'=>'add','qty'=>$request->stock,'rate'=>$request->price,'platform'=>'ecom']);
            }

            $crmproducts = CrmProduct::where('prd_id',$prdId)->first();
            $crmId = $crmproducts->id;

            if($crmproducts)
            {
                $crmmaster =  CrmProduct::where('id',$crmId)->update([
                    'PartNumber' => $request->PartNumber,
                    'PartDescription' => $request->PartDescription,
                    'HSNCode' => $request->HSNCode,
                    'PartCategory' => $request->PartCategory,
                    'GSTId' => $request->GSTId,
                    'NDP' => $request->NDP,
                    'Division' => $request->Division,
                    'PurchasePrice' => $request->price,
                    'SellingPrice' => $request->sale_price,
                    'JobId' => $request->JobId,
                    'EstimatePrice' => $request->EstimatePrice,
                    'MRP' => $request->MRP,
                    'MiniStockQty' => $request->MiniStockQty,
                    'UnitId' => $request->UnitId,
                    'WarrantyId' => $request->WarrantyId,
                    'IsSerialNoAcceptable' => $request->IsSerialNoAcceptable,
                    'PartSerialNumber' => $request->PartSerialNumber,
                    'Discount' => $request->Discount,
                    'CreatedDate' => date("Y-m-d H:i:s"),
                    'UpdatedDate' => date("Y-m-d H:i:s"),
                    'CreatedBy'=> $request->created_by,
                    'UpdatedBy' => $request->created_by,
                    'Del_Status' => 0,
                    'FileName' => $request->FileName,
                    'Status' => $request->Status,
                    'IsStockIncluded' => $request->IsStockIncluded,
                    'IsUshas' => $request->IsUshas,
                    'CompanyID'=> $request->CompanyID,
                    'BrandID' => $request->brand_id,
                    'ColourID' => $request->ColourID,
                    'ArticleNo' => $request->ArticleNo,
                    'SizeRangeID' => $request->SizeRangeID,
                    'ProductBatch' => $request->ProductBatch,
                    'BranchID' => $request->BranchID,
                    'VendorID' => $request->VendorID,
                    'SizeID' => $request->SizeID,
                    'ProductStatus' => $request->ProductStatus,
                    'ProductGroupId' => $request->ProductGroupId,
                    'ProductSubCategoryId' => $request->ProductSubCategoryId,
                    'SubBrandId' => $request->SubBrandId,
                    'updated_at' => date("Y-m-d H:i:s"),
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }

            // if($request->product_type == 2)
            // {
            //     //Combination array
            //     foreach($request->combination as $rowc)
            //     {
            //         $crmchild['prd_id'] = $prdId;
            //         $crmchild['ProductID'] = $crmId;
            //         $crmchild['ChildProductCode'] = $rowc['ChildProductCode'];
            //         $crmchild['ChildProductName'] = $rowc['ChildProductName'];
            //         $crmchild['SizeID'] = $rowc['SizeID'];
            //         $crmchild['ColourID'] = $rowc['ColourID'];
            //         $crmchild['PurchasePrice'] = $rowc['PurchasePrice'];
            //         $crmchild['SalesPrice'] = $rowc['SalesPrice'];
            //         $crmchild['MRP'] = $rowc['MRP'];
            //         $crmchild['ChildProductStatus'] = 1;
            //         $crmchild['CreatedBy'] = 1;
            //         $crmchild['CreatedOn'] = date("Y-m-d H:i:s");
            //         $crmchild['ModifiedBy'] = 1;
            //         $crmchild['ModifiedOn'] = date("Y-m-d H:i:s");
            //         $crmchild['EANCode'] = $rowc['EANCode'];
            //         $crmchild['IsUshasCode'] = $rowc['IsUshasCode'];
            //         $crmchild['UshasCode'] = $rowc['UshasCode'];
            //         $crmchild['UshasCode'] = $rowc['UshasCode'];
            //         $crmchild['updated_at'] = date("Y-m-d H:i:s");
            //         $crmchild['created_at'] = date("Y-m-d H:i:s");

            //         $crm_child_id = CrmChildProductsMaster::create($crmchild)->id;

            //         PrdStock::where('prd_id',$prdId)->update(['type'=>'add','child_id'=>$crm_child_id,'product_type'=>'child','qty'=>$request->stock,'rate'=>$request->price,'platform'=>'ecom']);
            //     }
            // }

            return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$prdId]);
        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function insert_child(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ecom_prd_id'=>['required','numeric'],
            'ProductID'=>['required','numeric'],
            'ChildProductCode'=>['required','max:255'],
            'ChildProductName'=>['required','max:255'],
            'SizeID'=> ['nullable','numeric'],
            'ColourID'=>['nullable','numeric'],
            'PurchasePrice'=>['nullable','numeric'],
            'SalesPrice'=>['nullable','numeric'],
            'MRP'=>['nullable','numeric'],
            'ChildProductStatus'=>['nullable'],
            'CreatedBy'=>['nullable'],
            'EANCode'=>['nullable'],
            'IsUshasCode'=>['nullable'],
            'UshasCode'=>['nullable']
        ]);

        if($validator->passes())
        {
            $prdId = $request->ecom_prd_id;
            $prd_products_data = Product::where('is_active',1)->where('is_deleted',0)->where('id',$prdId)->first();
            $crm_product_id = $prd_products_data->CrmProduct->id;

            $crmchild['prd_id'] = $request->ecom_prd_id;
            $crmchild['ProductID'] = $crm_product_id;
            $crmchild['ChildProductCode'] = $request->ChildProductCode;
            $crmchild['ChildProductName'] = $request->ChildProductName;
            $crmchild['SizeID'] = $request->SizeID;
            $crmchild['ColourID'] = $request->ColourID;
            $crmchild['PurchasePrice'] = $request->PurchasePrice;
            $crmchild['SalesPrice'] = $request->SalesPrice;
            $crmchild['MRP'] = $request->MRP;
            $crmchild['ChildProductStatus'] = $request->ChildProductStatus;
            $crmchild['CreatedBy'] = $request->CreatedBy;
            $crmchild['CreatedOn'] = date("Y-m-d H:i:s");
            $crmchild['ModifiedBy'] = $request->CreatedBy;
            $crmchild['ModifiedOn'] = date("Y-m-d H:i:s");
            $crmchild['EANCode'] = $request->EANCode;
            $crmchild['IsUshasCode'] = $request->IsUshasCode;
            $crmchild['UshasCode'] = $request->UshasCode;
            $crmchild['updated_at'] = date("Y-m-d H:i:s");
            $crmchild['created_at'] = date("Y-m-d H:i:s");

            $crm_child_id = CrmChildProductsMaster::create($crmchild)->id;

            // PrdStock::where('prd_id',$prdId)->update(['type'=>'add','child_id'=>$crm_child_id,'product_type'=>'child','qty'=>$request->stock,'rate'=>$request->SalesPrice,'platform'=>'ecom']);
            PrdStock::create(['prd_id'=>$prdId,'type'=>'add','child_id'=>$crm_child_id,'product_type'=>'child','qty'=>$request->stock,'rate'=>$request->SalesPrice,'platform'=>'ecom']);

            return response()->json(['httpcode'=>200,'success'=>'Successfully registered!','primary_key'=>$crm_child_id]);
        }

        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function update_child(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ChildProductID'=>['required','numeric'],
            'ecom_prd_id'=>['required','numeric'],
            'ProductID'=>['required','numeric'],
            'ChildProductCode'=>['required','max:255'],
            'ChildProductName'=>['required','max:255'],
            'SizeID'=> ['nullable','numeric'],
            'ColourID'=>['nullable','numeric'],
            'PurchasePrice'=>['nullable','numeric'],
            'SalesPrice'=>['nullable','numeric'],
            'MRP'=>['nullable','numeric'],
            'ChildProductStatus'=>['nullable'],
            'CreatedBy'=>['nullable'],
            'EANCode'=>['nullable'],
            'IsUshasCode'=>['nullable'],
            'UshasCode'=>['nullable']
        ]);

        if($validator->passes())
        {
            $ChildProductID = $request->ChildProductID;

            $prdId = $request->ecom_prd_id;
            $prd_products_data = Product::where('is_active',1)->where('is_deleted',0)->where('id',$prdId)->first();
            $crm_product_id = $prd_products_data->CrmProduct->id;

            $validate_check = CrmChildProductsMaster::where('ChildProductID',$ChildProductID)->first();
            
            if($validate_check)
            {
                $crmchild['prd_id'] = $request->ecom_prd_id;
                $crmchild['ProductID'] = $crm_product_id;
                $crmchild['ChildProductCode'] = $request->ChildProductCode;
                $crmchild['ChildProductName'] = $request->ChildProductName;
                $crmchild['SizeID'] = $request->SizeID;
                $crmchild['ColourID'] = $request->ColourID;
                $crmchild['PurchasePrice'] = $request->PurchasePrice;
                $crmchild['SalesPrice'] = $request->SalesPrice;
                $crmchild['MRP'] = $request->MRP;
                $crmchild['ChildProductStatus'] = $request->ChildProductStatus;
                $crmchild['CreatedBy'] = $request->CreatedBy;
                $crmchild['CreatedOn'] = date("Y-m-d H:i:s");
                $crmchild['ModifiedBy'] = $request->CreatedBy;
                $crmchild['ModifiedOn'] = date("Y-m-d H:i:s");
                $crmchild['EANCode'] = $request->EANCode;
                $crmchild['IsUshasCode'] = $request->IsUshasCode;
                $crmchild['UshasCode'] = $request->UshasCode;
                $crmchild['updated_at'] = date("Y-m-d H:i:s");
                $crmchild['created_at'] = date("Y-m-d H:i:s");

                CrmChildProductsMaster::where('ChildProductID',$ChildProductID)->update($crmchild);

                PrdStock::where('prd_id',$prdId)->where('child_id',$ChildProductID)->update(['type'=>'add','product_type'=>'child','qty'=>$request->stock,'rate'=>$request->SalesPrice,'platform'=>'ecom']);

                return response()->json(['httpcode'=>200,'success'=>'Successfully registered!','primary_key'=>$ChildProductID]);
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Child Product Doesnot Exists!']);
            }
            
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function insert_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prd_id'=>['required','numeric'],
            'ProductID'=>['required','numeric'],
            'image'=>['required'],
            'thumb'=>['nullable'],
            'created_by'=> ['nullable'],
            'FileName'=>['nullable'],
            'ProductPhoto'=>['nullable'],
            'ProductPhotoFileType'=>['nullable'],
            'ProductPhotoFileSize'=>['nullable']
        ]);

        if($validator->passes())
        {
            $prd_id = $request->prd_id;
            $prd_products_data = Product::where('is_active',1)->where('is_deleted',0)->where('id',$prd_id)->first();
            $crm_product_id = $prd_products_data->CrmProduct->id;

            $ProductID = $request->ProductID;
            $created_by = $request->created_by;
            $FileName = $request->FileName;
            $ProductPhoto = $request->ProductPhoto;
            $ProductPhotoFileType = $request->ProductPhotoFileType;
            $ProductPhotoFileSize = $request->ProductPhotoFileSize;
            $created_by = $request->created_by;
            

            $image_64 = $request->image; //your base64 encoded data

            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($image_64, 0, strpos($image_64, ',')+1);

            // find substring fro replace here eg: data:image/png;base64,

            $image = str_replace($replace, '', $image_64);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(10).'.'.$extension;

            if($request->thumb)
            {
                $thumb_image_64 = $request->thumb; //your base64 encoded data

                $thumb_extension = explode('/', explode(':', substr($thumb_image_64, 0, strpos($thumb_image_64, ';')))[1])[1];   // .jpg .png .pdf
                $thumb_replace = substr($thumb_image_64, 0, strpos($thumb_image_64, ',')+1);

                // find substring fro replace here eg: data:image/png;base64,

                $thumb_image = str_replace($thumb_replace, '', $thumb_image_64);
                $thumb_image = str_replace(' ', '+', $thumb_image);
                $thumb_imageName = Str::random(10).'.'.$thumb_extension;
            }
            

            $old_path  = '/app/public/products/'.$prd_id;
            $new_path = '/'.$prd_id.'/'.$imageName;

            $product_return_value = Storage::disk('product')->put($new_path, base64_decode($image));

            if($product_return_value)
            {
                if(isset($thumb_imageName))
                {
                    $new_thumb_path = '/'.$prd_id.'/thumb/'.$thumb_imageName;
                    $thumb_path = '/app/public/products/'.$prd_id.'/thumb/';
                    $product_return_value = Storage::disk('product')->put($new_thumb_path, base64_decode($thumb_image));

                    $thumb_upload_file = uploadFile($thumb_path,$thumb_imageName);
                }
                else
                {
                    $thumb_imageName = '';
                }
                $upload_file = uploadFile($old_path,$imageName);                

                $prdimage_id = PrdImage::create(['prd_id'=>$prd_id,'ProductID'=>$crm_product_id,'image'=>'/app/public/products'.$new_path,'thumb'=>'/app/public/products'.$new_thumb_path,'created_by'=>$created_by,'FileName'=>$FileName,'ProductPhoto'=>$ProductPhoto,'ProductPhotoFileType'=>$ProductPhotoFileType,'ProductPhotoFileSize'=>$ProductPhotoFileSize])->id;

                return response()->json(['httpcode'=>200,'success'=>'Successfully Inserted!','Primary_key'=>$prdimage_id,'Image_name'=>$imageName,'Thumb Image_name'=>$thumb_imageName]);
            }          
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    function addCmsContent($cntId,$l, $cnt)
    {
        if($cnt)
        {
            if($cntId)
            {
                $qry = CmsContent::where('cnt_id',$cntId)->where('is_deleted',0)->first();
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
            else
            {
                $cms = CmsContent::orderBy('cnt_id','desc')->first();
                if($cms)
                {
                    $cntId = ($cms->cnt_id+1); }else{ $cntId = 1;
                }
                $cmscont = CmsContent::create(['cnt_id'=>$cntId,'lang_id'=>$l,'content'=>$cnt,'created_by'=>1])->id;
                $insertId = $cntId;
            }
        }
        else
        {
            $insertId =NULL;
        }
        
        return $insertId;
    }

    public function add_product_assortment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ProductID'=>['required','numeric'],
            'AssortmentID'=>['required','numeric'],
            'Type'=>['nullable','max:255'],
            'TotalQty'=> ['required','numeric'],
            'OrganisationID'=>['required','numeric'],
        ]);

        if($validator->passes())
        {
            $ProductID = $request->ProductID;
            $prd_products_data = Product::where('is_active',1)->where('is_deleted',0)->where('unique_id',$ProductID)->first();
            $crm_product_id = $prd_products_data->CrmProduct->id;

            // $part_assortment_master = CrmPartAssortmentMaster::where('ProductID',$crm_product_id)->where('AssortmentID',$request->AssortmentID)->first();

            // if($part_assortment_master)
            // {
            //     return response()->json(['httpcode'=>'400','status'=>'error','error'=>'Product Assortment Already Exists!']);
            // }
            // else
            // {
            //     $part_assortment['ProductID'] = $crm_product_id;
            //     $part_assortment['AssortmentID'] = $request->AssortmentID    ;
            //     $part_assortment['Type'] = $request->Type;
            //     $part_assortment['TotalQty'] = $request->TotalQty;
            //     $part_assortment['OrganisationID'] = $request->OrganisationID;
            //     $part_assortment['MapAssortment'] = $request->MapAssortment;
            //     $part_assortment['CreatedBy'] = $request->CreatedBy;
            //     $part_assortment['ModifiedBy'] = $request->CreatedBy;
            //     $part_assortment['CreatedOn'] = date('Y-m-d H:s:i');
            //     $part_assortment['ModifiedOn'] = date('Y-m-d H:s:i');
            //     $part_assortment['updated_at'] = date('Y-m-d H:s:i');
            //     $part_assortment['created_at'] = date('Y-m-d H:s:i');

            //     $part_assortment_id = CrmPartAssortmentMaster::create($part_assortment)->id;

            //     // if(!empty($request->ChildProductID))
            //     if(!empty($part_assortment_id))
            //     {
            //         $part_assortment_details['ProductAssortmentID'] = $part_assortment_id;
            //         $part_assortment_details['ChildProductID'] = $request->ChildProductID;
            //         $part_assortment_details['ChildQuantity'] = $request->ChildQuantity;
            //         $part_assortment_details['CreatedBy'] = $request->CreatedBy;
            //         $part_assortment_details['ModifiedBy'] = $request->CreatedBy;
            //         $part_assortment_details['CreatedOn'] = date('Y-m-d H:s:i');
            //         $part_assortment_details['ModifiedOn'] = date('Y-m-d H:s:i');
            //         $part_assortment_details['updated_at'] = date('Y-m-d H:s:i');
            //         $part_assortment_details['created_at'] = date('Y-m-d H:s:i');

            //         $part_assortment_details_id = CrmPartAssortmentDetails::create($part_assortment_details)->id;
            //     }
            //     if($part_assortment_details_id != '')
            //     {
            //         return response()->json(['httpcode'=>200,'success'=>'Successfully Inserted!','primary_key'=>$part_assortment_id]);    
            //     }
            //     else
            //     {
            //         return response()->json(['httpcode'=>400,'error'=>'Assortment Details Not Inserted!']);
            //     }                
            // }

            $part_assortment['ProductID'] = $crm_product_id;
            $part_assortment['AssortmentID'] = $request->AssortmentID    ;
            $part_assortment['Type'] = $request->Type;
            $part_assortment['TotalQty'] = $request->TotalQty;
            $part_assortment['OrganisationID'] = $request->OrganisationID;
            $part_assortment['MapAssortment'] = $request->MapAssortment;
            $part_assortment['CreatedBy'] = $request->CreatedBy;
            $part_assortment['ModifiedBy'] = $request->CreatedBy;
            $part_assortment['CreatedOn'] = date('Y-m-d H:s:i');
            $part_assortment['ModifiedOn'] = date('Y-m-d H:s:i');
            $part_assortment['updated_at'] = date('Y-m-d H:s:i');
            $part_assortment['created_at'] = date('Y-m-d H:s:i');

            $part_assortment_id = CrmPartAssortmentMaster::create($part_assortment)->id;

            // if(!empty($request->ChildProductID))
            if(!empty($part_assortment_id))
            {
                $part_assortment_details['ProductAssortmentID'] = $part_assortment_id;
                $part_assortment_details['ChildProductID'] = $request->ChildProductID;
                $part_assortment_details['ChildQuantity'] = $request->ChildQuantity;
                $part_assortment_details['CreatedBy'] = $request->CreatedBy;
                $part_assortment_details['ModifiedBy'] = $request->CreatedBy;
                $part_assortment_details['CreatedOn'] = date('Y-m-d H:s:i');
                $part_assortment_details['ModifiedOn'] = date('Y-m-d H:s:i');
                $part_assortment_details['updated_at'] = date('Y-m-d H:s:i');
                $part_assortment_details['created_at'] = date('Y-m-d H:s:i');

                $part_assortment_details_id = CrmPartAssortmentDetails::create($part_assortment_details)->id;
            }
            if($part_assortment_details_id != '')
            {
                return response()->json(['httpcode'=>200,'success'=>'Successfully Inserted!','primary_key'=>$part_assortment_id]);    
            }
            else
            {
                return response()->json(['httpcode'=>400,'error'=>'Assortment Details Not Inserted!']);
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }        
    }

    public function update_product_assortment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ProductAssortmentID'=>['required','numeric'],
            'ProductID'=>['required','numeric'],
            'AssortmentID'=>['required','numeric'],
            'Type'=>['nullable','max:255'],
            'TotalQty'=> ['required','numeric'],
            'OrganisationID'=>['required','numeric'],
        ]);

        if($validator->passes())
        {
            $part_assortment_id = $request->ProductAssortmentID;
            
            $ProductID = $request->ProductID;
            $prd_products_data = Product::where('is_active',1)->where('is_deleted',0)->where('unique_id',$ProductID)->first();
            $crm_product_id = $prd_products_data->CrmProduct->id;

            $part_assortment_master = CrmPartAssortmentMaster::where('ProductAssortmentID',$part_assortment_id)->first();

            if($part_assortment_master)
            {
                // CrmPartAssortmentMaster::where('ProductAssortmentID',$part_assortment_id)->update(['is_active'=>0,'is_deleted'=>1]);

                $part_assortment['ProductID'] = $crm_product_id;
                $part_assortment['AssortmentID'] = $request->AssortmentID    ;
                $part_assortment['Type'] = $request->Type;
                $part_assortment['TotalQty'] = $request->TotalQty;
                $part_assortment['OrganisationID'] = $request->OrganisationID;
                $part_assortment['MapAssortment'] = $request->MapAssortment;
                $part_assortment['CreatedBy'] = $request->CreatedBy;
                $part_assortment['ModifiedBy'] = $request->CreatedBy;
                $part_assortment['CreatedOn'] = date('Y-m-d H:s:i');
                $part_assortment['ModifiedOn'] = date('Y-m-d H:s:i');
                $part_assortment['updated_at'] = date('Y-m-d H:s:i');
                $part_assortment['created_at'] = date('Y-m-d H:s:i');

                CrmPartAssortmentMaster::where('ProductAssortmentID',$part_assortment_id)->update($part_assortment);

                // if(!empty($request->ChildProductID))
                if(!empty($part_assortment_id))
                {
                    CrmPartAssortmentDetails::where('ProductAssortmentID',$part_assortment_id)->update(['is_active'=>0,'is_deleted'=>1]);

                    $part_assortment_details['ProductAssortmentID'] = $part_assortment_id;
                    $part_assortment_details['ChildProductID'] = $request->ChildProductID;
                    $part_assortment_details['ChildQuantity'] = $request->ChildQuantity;
                    $part_assortment_details['CreatedBy'] = $request->CreatedBy;
                    $part_assortment_details['ModifiedBy'] = $request->CreatedBy;
                    $part_assortment_details['CreatedOn'] = date('Y-m-d H:s:i');
                    $part_assortment_details['ModifiedOn'] = date('Y-m-d H:s:i');
                    $part_assortment_details['updated_at'] = date('Y-m-d H:s:i');
                    $part_assortment_details['created_at'] = date('Y-m-d H:s:i');

                    $partassortmentdetails_id = CrmPartAssortmentDetails::create($part_assortment_details)->id;
                }

                // return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','partassortmentmaster_id'=>$part_assortment_id,'partassortmentdetails_id'=>$partassortmentdetails_id]);

                if($partassortmentdetails_id != '')
                {
                    return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','partassortmentmaster_id'=>$part_assortment_id,'partassortmentdetails_id'=>$partassortmentdetails_id]);    
                }
                else
                {
                    return response()->json(['httpcode'=>400,'error'=>'Assortment Details Not Inserted!']);
                }   
            }
            else
            {
                return response()->json(['httpcode'=>'400','status'=>'error','error'=>'Product Assortment Doesnot Exists!']);
            }

            //Below code not needed.

            // $part_assortment_master_validate = CrmPartAssortmentMaster::where('ProductID',$request->ProductID)->where('AssortmentID',$request->AssortmentID)->where('ProductAssortmentID', '!=', $part_assortment_id)->first();

            // if($part_assortment_master_validate)
            // {
            //     return response()->json(['httpcode'=>'400','status'=>'error','error'=>'Product Assortment Already Exists!']);
            // }
            // else
            // {
            //     $part_assortment_master = CrmPartAssortmentMaster::where('ProductAssortmentID',$part_assortment_id)->first();

            //     if($part_assortment_master)
            //     {
            //         $part_assortment['ProductID'] = $request->ProductID;
            //         $part_assortment['AssortmentID'] = $request->AssortmentID    ;
            //         $part_assortment['Type'] = $request->Type;
            //         $part_assortment['TotalQty'] = $request->TotalQty;
            //         $part_assortment['OrganisationID'] = $request->OrganisationID;
            //         $part_assortment['MapAssortment'] = $request->MapAssortment;
            //         $part_assortment['CreatedBy'] = 1;
            //         $part_assortment['ModifiedBy'] = 1;
            //         $part_assortment['CreatedOn'] = date('Y-m-d H:s:i');
            //         $part_assortment['ModifiedOn'] = date('Y-m-d H:s:i');
            //         $part_assortment['updated_at'] = date('Y-m-d H:s:i');
            //         $part_assortment['created_at'] = date('Y-m-d H:s:i');

            //         CrmPartAssortmentMaster::where('ProductAssortmentID',$part_assortment_id)->update($part_assortment);

            //         if(!empty($request->ChildProductID))
            //         {
            //             $part_assortment_details['ProductAssortmentID'] = $part_assortment_id;
            //             $part_assortment_details['ChildProductID'] = $request->ChildProductID;
            //             $part_assortment_details['ChildQuantity'] = $request->ChildQuantity;
            //             $part_assortment_details['CreatedBy'] = 1;
            //             $part_assortment_details['ModifiedBy'] = 1;
            //             $part_assortment_details['CreatedOn'] = date('Y-m-d H:s:i');
            //             $part_assortment_details['ModifiedOn'] = date('Y-m-d H:s:i');
            //             $part_assortment_details['updated_at'] = date('Y-m-d H:s:i');
            //             $part_assortment_details['created_at'] = date('Y-m-d H:s:i');

            //             CrmPartAssortmentDetails::where('ProductAssortmentID',$part_assortment_id)->update($part_assortment_details);
            //         }

            //         return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$part_assortment_id]);
            //     }

            // }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function insert_part_assortment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ProductID'=>['required','numeric'],
            'AssortmentID'=>['required','numeric'],
            'Type'=>['nullable','max:255'],
            'TotalQty'=> ['required','numeric'],
            'OrganisationID'=>['required','numeric'],
            'CreatedBy'=>['nullable'],
            'MapAssortment'=>['nullable']
        ]);

        if($validator->passes())
        {
            $ProductID = $request->ProductID;
            $prd_products_data = Product::where('is_active',1)->where('is_deleted',0)->where('unique_id',$ProductID)->first();
            $crm_product_id = $prd_products_data->CrmProduct->id;

            $part_assortment_master = CrmPartAssortmentMaster::where('ProductID',$crm_product_id)->where('AssortmentID',$request->AssortmentID)->first();

            if($part_assortment_master)
            {
                return response()->json(['httpcode'=>'400','status'=>'error','error'=>'Product Assortment Already Exists!']);
            }
            else
            {
                $part_assortment['ProductID'] = $crm_product_id;
                $part_assortment['AssortmentID'] = $request->AssortmentID    ;
                $part_assortment['Type'] = $request->Type;
                $part_assortment['TotalQty'] = $request->TotalQty;
                $part_assortment['OrganisationID'] = $request->OrganisationID;
                $part_assortment['MapAssortment'] = $request->MapAssortment;
                $part_assortment['CreatedBy'] = $request->CreatedBy;
                $part_assortment['ModifiedBy'] = $request->CreatedBy;
                $part_assortment['CreatedOn'] = date('Y-m-d H:s:i');
                $part_assortment['ModifiedOn'] = date('Y-m-d H:s:i');
                $part_assortment['updated_at'] = date('Y-m-d H:s:i');
                $part_assortment['created_at'] = date('Y-m-d H:s:i');
                $part_assortment['is_active'] = 1;
                $part_assortment['is_deleted'] = 0;

                $part_assortment_id = CrmPartAssortmentMaster::create($part_assortment)->id;

                return response()->json(['httpcode'=>200,'success'=>'Successfully Inserted!','primary_key'=>$part_assortment_id]);
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }
    
    public function update_product_assortment_master(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ProductAssortmentID'=>['required','numeric'],
            'ProductID'=>['required','numeric'],
            'AssortmentID'=>['required','numeric'],
            'Type'=>['nullable','max:255'],
            'TotalQty'=> ['nullable','numeric'],
            'OrganisationID'=>['required','numeric'],
            'CreatedBy'=>['nullable'],
            'MapAssortment'=>['nullable']
        ]);

        if($validator->passes())
        {
            $ProductAssortmentID = $request->ProductAssortmentID;
            $ProductID = $request->ProductID;
            $prd_products_data = Product::where('is_active',1)->where('is_deleted',0)->where('unique_id',$ProductID)->first();
            $crm_product_id = $prd_products_data->CrmProduct->id;

            // $part_assortment_master = CrmPartAssortmentMaster::where('ProductID',$crm_product_id)->where('AssortmentID',$request->AssortmentID)->first();

            $part_assortment_master_check = CrmPartAssortmentMaster::where('ProductAssortmentID',$ProductAssortmentID)->first();

            if($part_assortment_master_check)
            {
                $part_assortment['ProductID'] = $crm_product_id;
                $part_assortment['AssortmentID'] = $request->AssortmentID    ;
                $part_assortment['Type'] = $request->Type;
                $part_assortment['TotalQty'] = $request->TotalQty;
                $part_assortment['OrganisationID'] = $request->OrganisationID;
                $part_assortment['MapAssortment'] = $request->MapAssortment;
                $part_assortment['CreatedBy'] = $request->CreatedBy;
                $part_assortment['ModifiedBy'] = $request->CreatedBy;
                $part_assortment['CreatedOn'] = date('Y-m-d H:s:i');
                $part_assortment['ModifiedOn'] = date('Y-m-d H:s:i');
                $part_assortment['updated_at'] = date('Y-m-d H:s:i');
                $part_assortment['created_at'] = date('Y-m-d H:s:i');
                $part_assortment['is_active'] = 1;
                $part_assortment['is_deleted'] = 0;

                CrmPartAssortmentMaster::where('ProductAssortmentID',$ProductAssortmentID)->update($part_assortment);

                return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$ProductAssortmentID]);
            }
            else
            {
                return response()->json(['httpcode'=>'400','status'=>'error','error'=>'Product Assortment Doesnot Exists!']);    
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function delete_product_assortment_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ProductAssortmentID'=>['required','numeric'],
            'ProductID'=>['required','numeric'],
            'ChildProductID'=>['required','numeric'],
            'CreatedBy'=>['nullable']
        ]);

        if($validator->passes())
        {
            $ProductAssortmentID = $request->ProductAssortmentID;
            $ProductID = $request->ProductID;
            $prd_products_data = Product::where('is_active',1)->where('is_deleted',0)->where('unique_id',$ProductID)->first();
            $crm_product_id = $prd_products_data->CrmProduct->id;

            $part_assortment_details_check = CrmPartAssortmentDetails::where('ProductAssortmentID',$ProductAssortmentID)->first();

            if($part_assortment_details_check)
            {
                CrmPartAssortmentDetails::where('ProductAssortmentID',$ProductAssortmentID)->update(['is_active'=>0,'is_deleted'=>1]);

                return response()->json(['httpcode'=>200,'success'=>'Successfully Deleted!','primary_key'=>$ProductAssortmentID]);
            }
            else
            {
                return response()->json(['httpcode'=>'400','status'=>'error','error'=>'Product Assortment Details Doesnot Exists!']);    
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function insert_product_assortment_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ProductAssortmentID'=>['required','numeric'],
            'ProductID'=>['required','numeric'],
            'ChildProductID'=>['required','numeric'],
            'ChildQuantity'=>['required','numeric'],
            'CreatedBy'=>['nullable']
        ]);

        if($validator->passes())
        {
            $ProductAssortmentID = $request->ProductAssortmentID;
            $ProductID = $request->ProductID;
            $prd_products_data = Product::where('is_active',1)->where('is_deleted',0)->where('unique_id',$ProductID)->first();
            $crm_product_id = $prd_products_data->CrmProduct->id;

            $part_assortment_details['ProductAssortmentID'] = $ProductAssortmentID;
            $part_assortment_details['ChildProductID'] = $request->ChildProductID;
            $part_assortment_details['ChildQuantity'] = $request->ChildQuantity;
            $part_assortment_details['is_active'] = 1;
            $part_assortment_details['is_deleted'] = 0;            
            $part_assortment_details['CreatedBy'] = $request->CreatedBy;
            $part_assortment_details['ModifiedBy'] = $request->CreatedBy;
            $part_assortment_details['CreatedOn'] = date('Y-m-d H:s:i');
            $part_assortment_details['ModifiedOn'] = date('Y-m-d H:s:i');
            $part_assortment_details['updated_at'] = date('Y-m-d H:s:i');
            $part_assortment_details['created_at'] = date('Y-m-d H:s:i');

            $partassortmentdetails_id = CrmPartAssortmentDetails::create($part_assortment_details)->id;

            return response()->json(['httpcode'=>200,'success'=>'Successfully Inserted!','Part Assortment Master ID'=>$ProductAssortmentID,'Part Assortment Details ID'=>$partassortmentdetails_id]);            
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function insert_assortment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Assortment'=>['required','max:255'],
            'FromSizeID'=>['required','numeric'],
            'ToSizeID'=>['required','numeric'],
            'SizeRangeID'=> ['required','numeric'],
            'CreatedBy'=>['required','numeric'],
            'OrganisationID'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $assortment_name = $request->Assortment;
            $assortments = CrmAssortmentMaster::where('Assortment',$assortment_name)->first();

            if($assortments)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Assortment Already Exists!']);
            }
            else
            {
                $assorts['Assortment']       = $request->Assortment;
                $assorts['FromSizeID']       = $request->FromSizeID;
                $assorts['ToSizeID']         = $request->ToSizeID;
                $assorts['SizeRangeID']      = $request->SizeRangeID;
                $assorts['CreatedBy']        = $request->CreatedBy;
                $assorts['ModifiedBy']       = $request->CreatedBy;
                $assorts['OrganisationID']   = $request->OrganisationID;
                $assorts['CreatedDate']      = date("Y-m-d H:i:s");
                $assorts['ModifiedDate']     = date("Y-m-d H:i:s");

                $assortmentId = CrmAssortmentMaster::create($assorts)->id;

                return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$assortmentId]);
            }
        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function update_assortment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'AssortmentID'=>['required','numeric'],
            'Assortment'=>['required','max:255'],
            'FromSizeID'=>['required','numeric'],
            'ToSizeID'=>['required','numeric'],
            'SizeRangeID'=> ['required','in:1,2'],
            'CreatedBy'=>['required','numeric'],
            'OrganisationID'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $assortment_id = $request->AssortmentID;
            $assortment_name = $request->Assortment;

            $assortmets_validate = CrmAssortmentMaster::where('Assortment',$assortment_name)->where('AssortmentID','!=',$assortment_id)->first();

            if($assortmets_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Assortment Already Exists!']);
            }
            else
            {
                $assortmets = CrmAssortmentMaster::where('AssortmentID',$assortment_id)->first();

                if($assortmets)
                {
                    $assorts['Assortment']       = $request->Assortment;
                    $assorts['FromSizeID']       = $request->FromSizeID;
                    $assorts['ToSizeID']         = $request->ToSizeID;
                    $assorts['SizeRangeID']      = $request->SizeRangeID;
                    $assorts['CreatedBy']        = $request->CreatedBy;
                    $assorts['ModifiedBy']       = $request->CreatedBy;
                    $assorts['OrganisationID']   = $request->OrganisationID;
                    $assorts['CreatedDate']      = date("Y-m-d H:i:s");
                    $assorts['ModifiedDate']     = date("Y-m-d H:i:s");

                    CrmAssortmentMaster::where('AssortmentID',$assortment_id)->update($assorts);

                    return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$assortment_id]);
                }
                else
                {
                    return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Assortment Doesnot Exists!']);
                }                
            }
        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function delete_assortment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'AssortmentID'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $assortment_id = $request->AssortmentID;
            
            $assortments = CrmAssortmentMaster::where('AssortmentID',$assortment_id)->first();

            if($assortments)
            {
                CrmAssortmentMaster::where('AssortmentID',$assortment_id)->update(['is_deleted'=>1]);
                
                return response()->json(['httpcode'=>200,'success'=>'Successfully Deleted!','primary_key'=>$assortment_id]);
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Assortment Doesnot Exists!']);
            }
        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function assortment_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'OrganisationID'=>['required','numeric'],
        ]);

        if($validator->passes())
        {
            $OrganisationID = $request->OrganisationID;
            
            $assortments = CrmAssortmentMaster::where('OrganisationID',$OrganisationID)->where('is_deleted',0)->orderBy('AssortmentID','DESC')->get();
            
            $assortment_list = [];

            if(count($assortments) > 0)
            {
                foreach($assortments as $assortment)
                {
                    $list['AssortmentID'] = $assortment->AssortmentID;
                    $list['Assortment'] = $assortment->Assortment;
                    $list['FromSizeID'] = $assortment->FromSizeID;
                    $list['ToSizeID'] = $assortment->ToSizeID;
                    $list['SizeRangeID'] = $assortment->SizeRangeID;
                    $list['OrganisationID'] = $assortment->OrganisationID;

                    $assortment_list[] = $list;
                }
                return ['httpcode'=>200,'status'=>'success','message'=>'Customers List','data'=>$assortment_list];
            }
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>'No Data Available']);
        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function update_stock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'=>['required','numeric'],
            'child_id'=>['nullable','numeric'],
            'product_type'=>['required','in:parent,child'],
            'platform'=>['required','in:ecom,crm'],
            'stock_count'=>['required','numeric'],
            'price'=>['nullable','numeric']
        ]);

        if($validator->passes())
        {
            $product_id = $request->product_id;

            if($request->platform=='ecom' || $request->platform=='crm')
            {
                $stocks = PrdStock::where('prd_id',$product_id)->where('platform',$request->platform)->first();
            }
        
            if(!$stocks)
            {
                return ['httpcode'=>404,'status'=>'error','message'=>'Not found'];  
            }
            else
            {
                if($request->product_type == 'parent')
                {
                    if(!empty($request->price))
                    {
                        PrdStock::where('prd_id',$request->product_id)->where('type','add')->update(['type'=>'add','qty'=>$request->stock_count,'rate'=>$request->price,'platform'=>$request->platform]);
                    }
                    else
                    {
                        PrdStock::where('prd_id',$request->product_id)->where('type','add')->update(['type'=>'add','qty'=>$request->stock_count,'platform'=>$request->platform]);
                    }
                    
                    return response()->json(['httpcode'=>200,'success'=>'Successfully updated!','product_id'=>$request->product_id]);
                }
                if($request->product_type == 'child')
                {
                    if(!empty($request->price))
                    {
                        PrdStock::where('prd_id',$request->product_id)->where('child_id',$request->child_id)->where('type','add')->update(['type'=>'add','qty'=>$request->stock_count,'rate'=>$request->price,'platform'=>$request->platform]);
                    }
                    else
                    {
                        PrdStock::where('prd_id',$request->product_id)->where('child_id',$request->child_id)->where('type','add')->update(['type'=>'add','qty'=>$request->stock_count,'platform'=>$request->platform]);
                    }
                    
                    return response()->json(['httpcode'=>200,'success'=>'Successfully updated!','product_id'=>$request->product_id,'child_id'=>$request->child_id]);
                }
            }
        }

        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function insert_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unique_id'=>['required','numeric'],
            'cat_name'=>['required','max:255'],
            'local_name'=>['required','max:255'],
            'is_rating'=>['required','numeric'],
            'slug'=>['required','max:255'],
            'sort_order'=>['required','numeric'],
            'is_active'=>['required','numeric'],
            'platform'=>['required','max:255']
        ]);

        if($validator->passes())
        {
            $unique_id = $request->unique_id;
            $cat_name = $request->cat_name;

            // $images = $request->file('image');
            
            $category_validate = Category::where('cat_name',$cat_name)->first();

            if($category_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Product Category Already Exists!']);
            }
            else
            {
                $category['unique_id'] = $request->unique_id;
                $category['cat_name'] = $request->cat_name;
                $category['local_name'] = $request->local_name;

                $category['cat_name_cid'] = $this->addCmsContent(NULL,1,$request->cat_name);

                if($request->cat_description)
                {
                    $category['cat_desc_cid'] = $this->addCmsContent(NULL,1,$request->cat_description);
                }

                $category['is_rating'] = $request->is_rating;
                $category['slug'] = $request->slug;
                $category['image'] = $request->image;
                $category['sort_order'] = $request->sort_order;
                $category['is_active'] = $request->is_active;
                $category['platform'] = $request->platform;
                $category['created_by'] = $request->created_by;
                $category['modified_by'] = $request->created_by;
                $category['created_at'] = date("Y-m-d H:i:s");
                $category['updated_at'] = date("Y-m-d H:i:s");

                $category_id = Category::create($category)->category_id;

                return response()->json(['httpcode'=>200,'success'=>'Successfully Inserted!','primary_key'=>$category_id]);
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function update_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'=>['required','numeric'],
            'unique_id'=>['required','numeric'],
            'cat_name'=>['required','max:255'],
            'local_name'=>['required','max:255'],
            'is_rating'=>['required','numeric'],
            'slug'=>['required','max:255'],
            'sort_order'=>['required','numeric'],
            'is_active'=>['required','numeric'],
            'platform'=>['required','max:255']
        ]);

        if($validator->passes())
        {
            $category_id = $request->category_id;
            $unique_id = $request->unique_id;
            $cat_name = $request->cat_name;

            // $images = $request->file('image');

            $category_validate = Category::where('cat_name',$cat_name)->where('category_id','!=',$category_id)->first();
            
            // $category_validate = Category::where('unique_id',$unique_id)->where('category_id','!=',$category_id)->first();

            if($category_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Product Category Already Exists!']);
            }
            else
            {
                $category_check = Category::where('unique_id',$unique_id)->where('category_id',$category_id)->first();

                if($category_check)
                {
                    $category['unique_id'] = $request->unique_id;
                    $category['cat_name'] = $request->cat_name;
                    $category['local_name'] = $request->local_name;

                    $category['cat_name_cid'] = $this->addCmsContent(NULL,1,$request->cat_name);

                    if($request->cat_description)
                    {
                        $category['cat_desc_cid'] = $this->addCmsContent(NULL,1,$request->cat_description);
                    }

                    $category['is_rating'] = $request->is_rating;
                    $category['slug'] = $request->slug;
                    $category['image'] = $request->image;
                    $category['sort_order'] = $request->sort_order;
                    $category['is_active'] = $request->is_active;
                    $category['platform'] = $request->platform;
                    $category['created_by'] = $request->created_by;
                    $category['modified_by'] = $request->created_by;
                    $category['created_at'] = date("Y-m-d H:i:s");
                    $category['updated_at'] = date("Y-m-d H:i:s");

                    Category::where('category_id',$category_id)->update($category);

                    return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$category_id]);
                }
                else
                {
                    return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Product Category Doesnot Exists!']);
                }
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function delete_category(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $category_id = $request->category_id;
            $category_check = Category::where('category_id',$category_id)->first();

            if($category_check)
            {
                Category::where('category_id',$category_id)->update(['is_deleted'=>1]);
                return response()->json(['httpcode'=>200,'success'=>'Successfully Deleted!','primary_key'=>$category_id]);
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Product Category Doesnot Exists!']);
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function category_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'platform'=>['required','max:255']
        ]);

        if($validator->passes())
        {
            $platform = $request->platform;
            $categories = Category::where('platform',$platform)->where('is_deleted',0)->get();

            if(count($categories) > 0)
            {
                $category_list = [];
                foreach($categories as $category)
                {
                    $list['category_id'] = $category['category_id'];
                    $list['unique_id'] = $category['unique_id'];
                    $list['cat_name'] = $category['cat_name'];
                    $list['local_name'] = $category['local_name'];
                    $list['is_rating'] = $category['is_rating'];
                    $list['slug'] = $category['slug'];
                    $list['image'] = $category['image'];
                    $list['platform'] = $category['platform'];

                    $category_list[] = $list;
                }
                return ['httpcode'=>200,'status'=>'success','message'=>'Customers List','data'=>$category_list];
            }
            else
            {
                return response()->json(['httpcode'=>'400','status'=>'error','error'=>'No Data Available']);
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }
}
