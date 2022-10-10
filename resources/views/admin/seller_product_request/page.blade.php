@extends('layouts.admin')
@section('title', 'Seller Product List')
@section('css')
<link href="{{URL::asset('admin/assets/plugins/wysiwyag/richtext.css')}}" rel="stylesheet" />
<!---combo tree-->
<link href="{{URL::asset('admin/assets/css/combo-tree.css')}}" rel="stylesheet" />
@endsection
@section('content')
<div id="pg_content">
    @include('admin.seller_product_request.list')
</div>
<div id="loader" class="d-none"><div class="spinner1"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>
@endsection