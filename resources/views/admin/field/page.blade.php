@extends('layouts.admin')
@section('title', 'Attribute List')
@section('page-header')
@endsection
@section('content')
<div id="pg_content">
    @include('admin.field.list')
</div>
<div id="loader" class="d-none"><div class="spinner1 content-spin"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>
@endsection