<?php // echo '<pre>'; print_r($catIds); echo '</pre>'; ?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Product Management</a></li>
            <li class="breadcrumb-item"><a id="bc_list" href="">Admin Product List</a></li>
            <li class="breadcrumb-item"><a href="#" id="bc_list2" data-val="{{$product->id}}" class="bc_list2"></i>Admin Product</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
</div>
<div class="col-lg-12 col-md-12">
    <div class="card">
        <div class="card-body pb-2">
            {{ Form::open(array('url' => "/product/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true')) }}
                 {{Form::hidden('id',0,['id'=>'id'])}} {{Form::hidden('prd_type',1,['prd_type'=>'id'])}} 

                <div class="tabs-menu mb-4">
                    <ul class="nav panel-tabs">
                        <li><a href="#tab1" data-toggle="tab" id="nav_tab_1" class="active"><span>General Info.</span></a></li>
                        <li><a href="#tab2" data-toggle="tab" id="nav_tab_2"><span>Price & Tax</span></a></li>
                        <li><a href="#tab3" data-toggle="tab" id="nav_tab_3"><span>Images</span></a></li>
                        <li><a href="#tab4" data-toggle="tab" id="nav_tab_4"><span>Attributes</span></a></li><!--
                        <li><a href="#tab5" data-toggle="tab" id="nav_tab_5"><span>Bank Details</span></a></li>-->
                   </ul>
                </div>
                <div class="row panel-body tabs-menu-body">
                    <div class="tab-content col-12">
                        @include('admin_product.details.general')
                        @include('admin_product.details.price_tax')
                        @include('admin_product.details.image')
                        @include('admin_product.details.attribute')
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-footer text-right">
                        {{Form::hidden('can_submit',0,['id'=>'can_submit'])}}
                        <button id="cancel_btn2" type="button" class="btn btn-secondary bc_list2"  data-val="{{$product->id}}">Cancel</button>
                        <button id="save_btn" type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
           {{Form::close()}}
        </div>
    </div>
</div>
<!-- INTERNAL WYSIWYG Editor js -->
<script src="{{URL::asset('admin/assets/js/form-editor.js')}}"></script>
		

