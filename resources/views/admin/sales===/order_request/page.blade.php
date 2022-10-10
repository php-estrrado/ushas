@extends('layouts.seller')
@section('title', 'Order Request List')
@section('content')
<div id="pg_content">
    @include('sales.order_request.list')
</div>
<div id="loader" class="d-none"><div class="spinner1 content-spin"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>
@endsection