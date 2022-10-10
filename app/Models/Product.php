<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Product extends Model{
    use HasFactory;
    protected $table = 'prd_products';
    protected $fillable = [
        'seller_id','product_type','category_id','sub_category_id','brand_id','tax_id','tag_id','name','name_cnt_id ','short_desc_cnt_id','desc_cnt_id','content_cnt_id','spec_cnt_id','is_featured','daily_deals','min_order','bulk_order',
        'is_out_of_stock','out_of_stock_selling','min_stock_alert','commission','commi_type','is_approved','visible','admin_prd_id','is_active','created_by','platform','odoo_id','sku'
    ];
    public function prdType(){ return $this->belongsTo(ProductType ::class, 'product_type'); }
    public function category(){ return $this->belongsTo(Category ::class, 'category_id'); }
    public function seller(){ return $this->belongsTo(SellerInfo ::class, 'seller_id'); }
    public function tax(){ return $this->belongsTo(Tax ::class, 'tax_id'); }
    public function subCategory(){ return $this->belongsTo(Subcategory ::class, 'sub_category_id'); }
    public function brand(){ return $this->belongsTo(Brand ::class, 'brand_id'); }
    public function prdPrice(){ return $this->hasOne(PrdPrice ::class, 'prd_id')->latest(); }    
    public function prdTag(){ return $this->hasMany(PrdAssignedTag::class, 'prd_id'); } 
    public function tag(){ return $this->belongsTo(Tag::class, 'tag_id'); } 
	public function assAttrs(){ return $this->hasMany(AssignedAttribute ::class, 'prd_id')->latest(); } 
    public function prdImage(){ return $this->hasMany(ProductImage ::class, 'prd_id')->where('is_deleted',0); }
    public function stockLogs(){ return $this->hasMany(PrdStock ::class, 'prd_id')->where('is_deleted',0); }
    public function priceLogs(){ return $this->hasMany(PrdPrice ::class, 'prd_id')->where('is_deleted',0); }
    public function prdoffer(){ return $this->hasOne(PrdOffer ::class, 'prd_id')->where('is_deleted',0); }
    public function trending(){ return $this->hasMany(SaleorderItems ::class, 'prd_id')->where('is_deleted',0); }
    public function prdimension(){ return $this->hasOne(ProdDimension::class,'prd_id')->where('is_deleted',0); }
    
    public function prdStock($prdId){ 
        $in             =   (int)PrdStock ::where('prd_id',$prdId)->where('type','add')->where('is_deleted',0)->sum('qty'); 
        $out            =   (int)PrdStock ::where('prd_id',$prdId)->where('type','destroy')->where('is_deleted',0)->sum('qty'); 
        return ($in-$out);
    }    
    
    public function assignedAttrs($prdId) {
        $query          =   AssignedAttribute::where('prd_id',$prdId)->where('is_deleted',0)->get(); $data = array();
        if($query)      {   foreach($query as $row){ $data[$row->attr_id] = $row; } }else{ $data = []; } return $data;
    }
    
     public function assignedAttrsList($prdId) {
        $query          =   AssignedAttribute::where('prd_id',$prdId)->where('is_deleted',0)->get(); $data = array(); 
        if($query)      {   foreach($query as $row){ $data[$row->PrdAttr->name_cnt_id] = $this->AttrsValueList($row->attr_id); } }else{ $data = []; } return $data;
    }
    public function assignedAttrsListNames($prdId) {
        $query          =   AssignedAttribute::where('prd_id',$prdId)->where('is_deleted',0)->get(); $data = array(); 
        if($query)      {   foreach($query as $row){ $data[] = $row->PrdAttr->name_cnt_id; } }else{ $data = []; } return $data;
    }
    public function AttrsValueList($attr) {
        $query          =   PrdAttributeValue::where('attr_id',$attr)->where('is_deleted',0)->get(); $data = array(); 
        if($query)      {   foreach($query as $row){ $data[] = array('name'=>$row->name, 'image' =>$row->image); } }else{ $data = []; } return $data;
    }

    public function ConfigAssignedAttrs($prdId) {

         $query          =   AssociatProduct::where('prd_id',$prdId)->where('is_deleted',0)->latest()->take(1)->get(); $data = array(); 
        if($query)      {   foreach($query as $row){ $data = $this->assignedAttrsList($row->ass_prd_id); }  }else{ $data = []; } return $data;

    }
    public function ConfigAssignedAttrsNames($prdId) {

         $query          =   AssociatProduct::where('prd_id',$prdId)->where('is_deleted',0)->latest()->take(1)->get(); $data = array(); 
        if($query)      {   foreach($query as $row){ $data = $this->assignedAttrsListNames($row->ass_prd_id); }  }else{ $data = []; } return $data;

    }

    public function ConfigAssignedAttrsIds($prdId,$attr_data) {
        // dd($attr_data);

       $query          =  DB::table('prd_associative_products as ap')
         ->join('prd_assigned_attributes as at', 'ap.ass_prd_id', '=', 'at.prd_id')
         ->select('ap.*', 'at.attr_val_id','at.prd_id','at.attr_value')
         ->where('ap.prd_id',$prdId)->where('ap.is_deleted',0)
         ->groupBy('at.prd_id','at.attr_val_id')
         ->get(); 
        
         $data = array(); 
         if($query)      {   foreach($query as $row){ 

            $data[$row->ass_prd_id][] = $row->attr_value; 

        }  

        }else{ $data = []; }
        
        return $data;

    }

    public function variation_fields($prdId) {

        $data = [];
        $prd_price = PrdPrice::where('prd_id',$prdId)->latest()->first();
        if(isset($prd_price))
        {
        $data['price'] = $prd_price->price;
        $data['sale_price'] = $prd_price->sale_price;
        $data['sale_start_date'] = $prd_price->sale_start_date;
        $data['sale_end_date'] = $prd_price->sale_end_date;
        }else{
        $data['price'] = 0;
        $data['sale_price'] = 0;
        $data['sale_start_date'] = "";
        $data['sale_end_date'] = "";  
        }

        $data['stock'] = Product::prdStock($prdId);
        $prd_product = Product::where('id',$prdId)->latest()->first();
        if($prd_product){
        $data['sku'] = $prd_product->sku;
        $data['min_order'] = $prd_product->min_order;
        $data['bulk_order'] = $prd_product->bulk_order;     
        }else{
        $data['sku'] = "";
        $data['min_order'] = 0;
        $data['bulk_order'] = 0;
        }

        $prd_dimension= ProdDimension::where('prd_id',$prdId)->latest()->first();
        if(isset($prd_dimension)){
        $data['weight'] = $prd_dimension->weight;
        $data['length'] = $prd_dimension->length;
        $data['width'] = $prd_dimension->width;
        $data['height'] = $prd_dimension->height;
        }else{
        $data['weight'] = 0;
        $data['length'] = 0;
        $data['width'] = 0;
        $data['height'] = 0;          
        }

        if(PrdImage::where('prd_id',$prdId)->latest()->first()){
        $data['image'] = PrdImage::where('prd_id',$prdId)->latest()->first()->image; 
        }else{
        $data['image'] = "";
        }
        
        return $data;

    }

    public function ConfigAssignedProducts($prdId) {

         $query          =   AssociatProduct::where('prd_id',$prdId)->where('is_deleted',0)->get(); $data = array(); 
        if($query)      {   foreach($query as $row){ $data = $this->assignedAttrsList($row->ass_prd_id); }  }else{ $data = []; } return $data;

    }

    public function ConfigAssocProducts($prdId)
    {
        $query          =   AssociatProduct::where('prd_id',$prdId)->where('is_deleted',0)->get(); $data = array();
        if($query)      {   foreach($query as $row){ $data[$row->ass_prd_id] = $this->ConfigAssignedProducts($row->ass_prd_id); } }else{ $data = []; } return $data;

    }
    
    public function assignedFlds($prdId) {
        
        $query          =   AssignedFields::where('prd_id',$prdId)->where('is_deleted',0)->get(); $data = array();
        if($query)      {   foreach($query as $row){ $data[$row->field_id][] = $row->field_val_id; } }else{ $data = []; } 
        // dd($data);
        return $data;
    }
    
    static function ValidateUnique($field,$value,$id,$sellerId) {
        $query                      =   Product::where($field,$value)->where('seller_id',$sellerId)->where('is_deleted',0)->first();
        if($query){ if($query->id   !=  $id){ return 'This '.$field.' already has been taken'; }else{ return false; } }else{ return false; }
    }
    
    
    /*******************API**************/

    static function get_content($field_id){ 

        $language =DB::table('glo_lang_lk')->where('is_active', 1)->first();
        $content_table=DB::table('cms_content')->where('cnt_id', $field_id)->where('lang_id', $language->id)->first();
        if($content_table){ 
        $return_cont = $content_table->content;
        return $return_cont;
        }
        else
            { return false; }
        }
        
    public function Store($seller_id){ return DB::table('usr_stores')->where('seller_id', $seller_id)->first(); }  
    public function SellerName($seller_id){ return SellerInfo::where('seller_id', $seller_id)->first(); }  
    
    static function getTaxValue($tax_id){ 
        $current_date=Carbon::now();
        $TaxValue =TaxValue::where(function ($query) { $query->where('is_deleted', '=', NULL)->orWhere('is_deleted', '=', 0);})->where('tax_id', $tax_id)->whereDate('valid_from','<=',$current_date)->whereDate('valid_to','>=',$current_date)->first();
        if($TaxValue){ 
        $return_cont = $TaxValue->percentage;
        return $return_cont;
        }else{ return false; }
        }
        
        public function sold_count($id){
        $query = SalesOrderItem::join('sales_orders','sales_order_items.sales_id','=','sales_orders.id')->where('sales_order_items.prd_id',$id)->whereIn('sales_orders.order_status',['delivered'])->count();
        return $query;
    }

    public function variationImage(){
    return $this->hasOne(ProductImage ::class, 'prd_id')->where('is_deleted',0)->latest();
     }

	static function trendingProducts(){
		$query = DB::table('sales_order_items')
            ->Join('prd_products','sales_order_items.prd_id','=','prd_products.id')
            ->leftJoin('category','category.category_id','=','prd_products.category_id')
            ->leftJoin('subcategory','subcategory.subcategory_id','=','prd_products.sub_category_id')
            ->leftJoin('prd_brand','prd_brand.id','=','prd_products.brand_id')
            ->selectRaw('prd_products.id as product_id,prd_products.*,category.*,prd_brand.*,subcategory.*,sum(sales_order_items.qty) as total')
            ->groupBy('prd_products.id')
            ->orderBy('total','desc')
            ->take(6)
            ->get();
			
		return $query;
		
	}		

}
