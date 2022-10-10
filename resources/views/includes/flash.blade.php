@if(Session::has('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
    {{ Session::get('success')}}
</div>
@endif
@if(Session::has('warning'))
<div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
    {{ Session::get('warning')}}
</div>
@endif
@if(Session::has('error'))
<div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
    {{ Session::get('error')}}
</div>

@endif
<div id="allert_success" class="alert alert-success alert-dismissible" role="alert" style="display: none"><button type="button" class="close"><span aria-hidden="true">x</span></button><span id="msg"></span></div>

<script type="text/javascript">
    $(document).ready(function () {
        setTimeout(function () {
            $('.alert:not(.noanim)').hide('slow');
        }, 3000);

    });
</script>