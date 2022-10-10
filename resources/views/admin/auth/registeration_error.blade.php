
@extends('layouts.master2')
<link href='https://fonts.googleapis.com/css?family=Lato:300,400|Montserrat:700' rel='stylesheet' type='text/css'>
@section('css')
<style>
	@import url(//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css);
	@import url(//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);
</style>
	<link rel="stylesheet" href="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/default_thank_you.css">
	<script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/jquery-1.9.1.min.js"></script>
	<script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/html5shiv.js"></script>
@endsection

@section('content')
	<header class="site-header" id="header">
		<h1 class="site-header__title" data-lead-id="site-header-title">Expired!</h1>
	</header>

	<div class="main-content">
		<i class="fa fa-times-circle main-content__checkmark" id="checkmark" style="color:#d10c0c;"></i>
		<p class="main-content__body" data-lead-id="main-content-body">Authentication link expired. Please retry..</p>
	</div>
	
@endsection
@section('js')
@endsection
