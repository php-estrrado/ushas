  <?php 

$discount_value       =   (isset($offer))?    $offer->discount_value : ''; 
$discount_type       =   (isset($offer))?    $offer->discount_type : 'amount'; 
$quantity_limit       =   (isset($offer))?    $offer->quantity_limit : ''; 
$valid_from       =   (isset($offer))?    $offer->valid_from : ''; 
$valid_to       =   (isset($offer))?    $offer->valid_to : ''; 
$ofr_id       =   (isset($offer))?    $offer->id : '0'; 
$is_active       =   (isset($offer))?    $offer->is_active : '1'; 

   ?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Seller Management</a></li>
            <li class="breadcrumb-item"><a href="#" id="bc_list"><i class="fe fe-grid mr-2 fs-14"></i>Product List</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
</div>
<div class="col-lg-12 col-md-12">
    <div class="card">
        <div class="card-body pb-2">
            {{ Form::open(array('url' => "admin/seller/products/offer/save", 'id' => 'offerForm', 'name' => 'offerForm', 'class' => '','files'=>'true')) }}
                 {{Form::hidden('id',0,['id'=>'id'])}} 

             
                <div class="row panel-body tabs-menu-body">
                    <div class="tab-content col-12">
                        @include('admin.seller_product.offer.fields')
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-footer text-right">
                       
                        <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="offer_save_btn" type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
           {{Form::close()}}
        </div>
    </div>
</div>
<!-- INTERNAL WYSIWYG Editor js -->
<script src="{{URL::asset('admin/assets/js/form-editor.js')}}"></script>
		

