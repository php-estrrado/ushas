<?php

namespace App\Http\Controllers\Api\Salesprice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\crm\CrmProduct;
use App\Models\crm\CrmSalesPriceType;
use App\Models\crm\CrmSalesPriceList;
use App\Models\Product;
use App\Models\PrdPrice;
use Illuminate\Support\Str;
use Validator;

class PriceController extends Controller
{
    public function salespricetype_insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'SalesPriceType'=>['required','max:255'],
            'OrganisationId'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $pricetype_validate = CrmSalesPriceType::where('SalesPriceType',$request->SalesPriceType)->first();
            if($pricetype_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Salesprice Type Already Exists!']);
            }
            else
            {
                $pricetype['SalesPriceType'] = $request->SalesPriceType;
                $pricetype['OrganisationId'] = $request->OrganisationId;
                $pricetype['CreatedOn'] = date('Y-m-d H:i:s');
                $pricetype['CreatedBy'] = 1;
                $pricetype['ModifiedOn'] = date('Y-m-d H:i:s');
                $pricetype['ModifiedBy'] = 1;
                $pricetype['created_at'] = date('Y-m-d H:i:s');
                $pricetype['updated_at'] = date('Y-m-d H:i:s');
                
                $pricetype_id = CrmSalesPriceType::create($pricetype)->id;

                return response()->json(['httpcode'=>200,'success'=>'Successfully Added!','primary_key'=>$pricetype_id]);
            }
        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function salespricetype_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'PriceTypeId'=>['required','numeric'],
            'SalesPriceType'=>['required','max:255'],
            'OrganisationId'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $pricetype_id = $request->PriceTypeId;
            $pricetype_name = $request->SalesPriceType;

            $pricetype_validate = CrmSalesPriceType::where('SalesPriceType',$pricetype_name)->where('PriceTypeId','!=',$pricetype_id)->first();

            if($pricetype_validate)
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Salesprice Type Already Exists!']);
            }
            else
            {
                $salespricetype = CrmSalesPriceType::where('PriceTypeId',$pricetype_id)->first();

                if($salespricetype)
                {
                    $pricetype['SalesPriceType'] = $request->SalesPriceType;
                    $pricetype['OrganisationId'] = $request->OrganisationId;
                    $pricetype['CreatedOn'] = date('Y-m-d H:i:s');
                    $pricetype['CreatedBy'] = 1;
                    $pricetype['ModifiedOn'] = date('Y-m-d H:i:s');
                    $pricetype['ModifiedBy'] = 1;
                    $pricetype['created_at'] = date('Y-m-d H:i:s');
                    $pricetype['updated_at'] = date('Y-m-d H:i:s');
                    
                    CrmSalesPriceType::where('PriceTypeId',$pricetype_id)->update($pricetype);

                    return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$pricetype_id]);
                }
                else
                {
                    return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Salesprice Type Doesnot Exists!']);
                }                
            }
        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function salespricetype_delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'PriceTypeId'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $pricetype_id = $request->PriceTypeId;

            $salespricetype = CrmSalesPriceType::where('PriceTypeId',$pricetype_id)->first();
            
            if($salespricetype)
            {
                CrmSalesPriceType::where('PriceTypeId',$pricetype_id)->update(['is_deleted'=>1]);

                return response()->json(['httpcode'=>200,'success'=>'Successfully Deleted!','primary_key'=>$pricetype_id]);
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Salesprice Type Doesnot Exists!']);
            }
        }
        return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
    }

    public function salespricetype_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'OrganisationId'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $organisation_id = $request->OrganisationId;

            $salespricetypes = CrmSalesPriceType::where('OrganisationId',$organisation_id)->where('is_deleted',0)->get();

            $salespricetype_list = [];
            if(count($salespricetypes) > 0)
            {
                foreach($salespricetypes as $salespricetype)
                {
                    $list['PriceTypeId'] = $salespricetype->PriceTypeId;
                    $list['SalesPriceType'] = $salespricetype->SalesPriceType;
                    $list['OrganisationId'] = $salespricetype->OrganisationId;
                    $list['CreatedOn'] = $salespricetype->CreatedOn;
                    $list['ModifiedOn'] = $salespricetype->ModifiedOn;
                    $list['CreatedBy'] = $salespricetype->CreatedBy;
                    $list['ModifiedBy'] = $salespricetype->ModifiedBy;

                    $salespricetype_list[] = $list;
                }
                return ['httpcode'=>200,'status'=>'success','message'=>'Customers List','data'=>$salespricetype_list];
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

    public function salespricelist_insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'DivisionId'=>['required','numeric'],
            'BranchId'=>['required','numeric'],
            'Part_Id'=>['required','numeric'],
            'CustomerTypeId'=>['required','numeric'],
            'DiscountPercentage'=>['nullable','numeric'],
            'Amount'=>['required','numeric'],
            'FromDate'=>['nullable','date_format:d/m/Y H:i:s'],
            'ToDate'=>['nullable','date_format:d/m/Y H:i:s']
        ]);

        if($validator->passes())
        {
            $prd_id = $request->prd_id;
            $prd_products_data = Product::where('is_active',1)->where('is_deleted',0)->where('id',$prd_id)->first();
            $crm_product_id = $prd_products_data->CrmProduct->id;

            $FromDate = str_replace('/','-',$request->FromDate);
            $ToDate = str_replace('/','-',$request->ToDate);

            $pricelist['DivisionId'] = $request->DivisionId;
            $pricelist['BranchId'] = $request->BranchId;
            $pricelist['Part_Id'] = $crm_product_id;
            $pricelist['PriceTypeId'] = 1;
            $pricelist['CustomerTypeId'] = $request->CustomerTypeId;
            // $pricelist['DiscountPercentage'] = $request->DiscountPercentage;
            $pricelist['DiscountPercentage'] = 0;
            $pricelist['Amount'] = $request->Amount;

            $pricelist['FromDate'] = date("Y-m-d H:i:s",strtotime($FromDate));
            $pricelist['ToDate'] = date("Y-m-d H:i:s",strtotime($ToDate));

            $pricelist['CreatedBy'] = $request->CreatedBy;
            $pricelist['UpdatedBy'] = $request->CreatedBy;
            $pricelist['DelStatus'] = 0;
            $pricelist['prd_id'] = $request->prd_id;
            $pricelist['CreatedDate'] = date('Y-m-d H:i:s');
            $pricelist['UpdatedDate'] = date('Y-m-d H:i:s');
            $pricelist['updated_at'] = date('Y-m-d H:i:s');
            $pricelist['updated_at'] = date('Y-m-d H:i:s');

            $pricelist_id = CrmSalesPriceList::create($pricelist)->id;

            $prd_prices['unique_id'] = $pricelist_id;
            $prd_prices['prd_id'] = $request->prd_id;
            $prd_prices['price'] = $request->Amount;
            $prd_prices['sale_price'] = $request->Amount;

            $prd_prices['sale_start_date'] = date("Y-m-d H:i:s",strtotime($FromDate));
            $prd_prices['sale_end_date'] = date("Y-m-d H:i:s",strtotime($ToDate));

            $prd_prices['created_by'] = $request->CreatedBy;
            $prd_prices['modified_by'] = $request->CreatedBy;
            $prd_prices['is_deleted'] = 0;
            $prd_prices['platform'] = 'ecom';
            $prd_prices['updated_at'] = date('Y-m-d H:i:s');
            $prd_prices['created_at'] = date('Y-m-d H:i:s');

            $prdprices_id = PrdPrice::create($prd_prices)->id;

            return response()->json(['httpcode'=>200,'success'=>'Successfully Added!','primary_key'=>$pricelist_id]);
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function salespricelist_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'SalesPriceListId'=>['required','numeric'],
            'DivisionId'=>['required','numeric'],
            'BranchId'=>['required','numeric'],
            'Part_Id'=>['required','numeric'],            
            'CustomerTypeId'=>['required','numeric'],
            'DiscountPercentage'=>['nullable','numeric'],
            'Amount'=>['required','numeric'],
            'FromDate'=>['nullable','date_format:d/m/Y H:i:s'],
            'ToDate'=>['nullable','date_format:d/m/Y H:i:s']
        ]);

        if($validator->passes())
        {
            $prd_id = $request->prd_id;
            $prd_products_data = Product::where('is_active',1)->where('is_deleted',0)->where('id',$prd_id)->first();
            $crm_product_id = $prd_products_data->CrmProduct->id;

            $FromDate = str_replace('/','-',$request->FromDate);
            $ToDate = str_replace('/','-',$request->ToDate);

            $SalesPriceListId = $request->SalesPriceListId;
            $salesprice_list = CrmSalesPriceList::where('SalesPriceListId',$SalesPriceListId)->first();

            if($salesprice_list)
            {
                $pricelist['DivisionId'] = $request->DivisionId;
                $pricelist['BranchId'] = $request->BranchId;
                $pricelist['Part_Id'] = $crm_product_id;
                $pricelist['PriceTypeId'] = 1;
                $pricelist['CustomerTypeId'] = $request->CustomerTypeId;
                // $pricelist['DiscountPercentage'] = $request->DiscountPercentage;
                $pricelist['DiscountPercentage'] = 0;
                $pricelist['Amount'] = $request->Amount;

                $pricelist['FromDate'] = date("Y-m-d H:i:s",strtotime($FromDate));
                $pricelist['ToDate'] = date("Y-m-d H:i:s",strtotime($ToDate));

                $pricelist['CreatedBy'] = $request->CreatedBy;
                $pricelist['UpdatedBy'] = $request->CreatedBy;
                $pricelist['DelStatus'] = 0;
                $pricelist['prd_id'] = $request->prd_id;
                $pricelist['CreatedDate'] = date('Y-m-d H:i:s');
                $pricelist['UpdatedDate'] = date('Y-m-d H:i:s');
                $pricelist['updated_at'] = date('Y-m-d H:i:s');
                $pricelist['created_at'] = date('Y-m-d H:i:s');

                CrmSalesPriceList::where('SalesPriceListId',$SalesPriceListId)->update($pricelist);

                $prd_prices['prd_id'] = $request->prd_id;
                $prd_prices['price'] = $request->Amount;
                $prd_prices['sale_price'] = $request->Amount;
                
                $prd_prices['sale_start_date'] = date("Y-m-d H:i:s",strtotime($FromDate));
                $prd_prices['sale_end_date'] = date("Y-m-d H:i:s",strtotime($ToDate));
                
                $prd_prices['created_by'] = $request->CreatedBy;
                $prd_prices['modified_by'] = $request->CreatedBy;
                $prd_prices['is_deleted'] = 0;
                $prd_prices['platform'] = 'ecom';
                $prd_prices['updated_at'] = date('Y-m-d H:i:s');
                $prd_prices['created_at'] = date('Y-m-d H:i:s');

                $prdprices_id = PrdPrice::where('unique_id',$SalesPriceListId)->update($prd_prices);

                return response()->json(['httpcode'=>200,'success'=>'Successfully Updated!','primary_key'=>$SalesPriceListId]);
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Salesprice List Doesnot Exists!']);
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function salespricelist_delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'SalesPriceListId'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $SalesPriceListId = $request->SalesPriceListId;

            $salesprice_list = CrmSalesPriceList::where('SalesPriceListId',$SalesPriceListId)->first();

            if($salesprice_list)
            {
                CrmSalesPriceList::where('SalesPriceListId', $SalesPriceListId)->update(['DelStatus'=>1]);
                PrdPrice::where('unique_id',$SalesPriceListId)->update(['is_deleted'=>1]); 
                
                return response()->json(['httpcode'=>200,'success'=>'Successfully Deleted!','primary_key'=>$SalesPriceListId]);
            }
            else
            {
                return response()->json(['httpcode'=>400,'status'=>'error','error'=>'Salesprice List Doesnot Exists!']);
            }
        }
        else
        {
            return response()->json(['httpcode'=>'400','status'=>'error','error'=>$validator->errors()->all()]);
        }
    }

    public function salespricelist_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'DivisionId'=>['required','numeric']
        ]);

        if($validator->passes())
        {
            $DivisionId = $request->DivisionId;
            $salesprice_list = CrmSalesPriceList::where('DivisionId',$DivisionId)->where('Delstatus',0)->get();

            if(count($salesprice_list) > 0)
            {
                $salepricelist_data = [];

                foreach($salesprice_list as $saleprice)
                {
                    $list['SalesPriceListId'] = $saleprice['SalesPriceListId'];
                    $list['DivisionId'] = $saleprice['DivisionId'];
                    $list['BranchId'] = $saleprice['BranchId'];
                    $list['Part_Id'] = $saleprice['Part_Id'];
                    $list['PriceTypeId'] = $saleprice['PriceTypeId'];
                    $list['prd_id'] = $saleprice['prd_id'];
                    $list['CustomerTypeId'] = $saleprice['CustomerTypeId'];
                    $list['DiscountPercentage'] = $saleprice['DiscountPercentage'];
                    $list['Amount'] = $saleprice['Amount'];
                    $list['FromDate'] = date('d-m-Y H:i:s', strtotime($saleprice['FromDate']));
                    $list['ToDate'] = date('d-m-Y H:i:s', strtotime($saleprice['ToDate']));
                    $list['CreatedBy'] = $saleprice['CreatedBy'];
                    $list['UpdatedBy'] = $saleprice['UpdatedBy'];
                    $list['CreatedDate'] = $saleprice['CreatedDate'];
                    $list['UpdatedDate'] = $saleprice['UpdatedDate'];

                    $salepricelist_data[] = $list;
                }
                return ['httpcode'=>200,'status'=>'success','message'=>'Customers List','data'=>$salepricelist_data];
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