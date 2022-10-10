@extends('layouts.admin')
@section('title', 'Seller List')

@section('content')
<div id="content_list">@include('admin.new_seller.list.content')</div>
<div id="content_detail" class="row no-disp"></div>
@section('js') 
 <script src="{{asset('admin/assets/js/datatable/tables/new_seller-datatable.js')}}"></script>

    <script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    $(document).ready(function(){ 
        $('#adminForm #can_submit').val(0); 
        @if(Session::has('success')) toastr.success("{{ Session::get('success')}}"); 
        @elseif(Session::has('error')) toastr.error("{{ Session::get('error')}}");  @endif
        $("body").on('change','#adminForm #logo',function(){ readURL(this); });
        $("body").on('change','#adminForm #banner',function(){ readURL(this); });
        $('body').on('click','#cancel_btn',function(){ $('#content_list').fadeIn(700); $('#content_detail').hide();  });
        $('body').on('click','#bc_list',function(){ $('#content_list').fadeIn(700); $('#content_detail').hide();  });
        
        $('body').on('click','.editBtn',function(){
            var id      =   this.id.replace('editBtn-','');  
            $.ajax({
                type: "GET",
                url: '{{url("admin/seller")}}/'+id+'/new',
                success: function (data) {
                    $('#content_detail').html(data); $('#content_detail').fadeIn(700); $('#content_list').hide(); 
                  //  $('#content_detail #country_id').trigger("chosen:updated");
                } 
            }); return false;
        });
        
        $('body').on('click','#save_new',function(e){
          
            
            swal({
                title: "Approve Confirmation",
                text: "Are you sure you want to approve this Seller?",
                // type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: 'Yes'
            },function(inputValue){
                if (inputValue == true) {
                    $('#adminForm').trigger('submit');
                }
            });
            
            
        });
        $('body').on('change','#active_filter',function(){ 
            
            
             $.ajax({
            type: "POST",
            url: '{{url("admin/new-sellers")}}',
            data: { active:this.value,'_token': '{{ csrf_token()}}'},
            success: function (data) { 
            console.log(data);
             $('#content_list').html(data);  $('#content_list').fadeIn(700); $('#content_detail').hide(); ;
                return false;
            
            } 
        });
        
        });
        $('body').on('submit','#adminForm',function(e){ 
            
                    if($('#adminForm #can_submit').val() > 0){ return true; }
                    else{ 
                        e.preventDefault();    
                        var formData = new FormData(this); $('body #adminForm .error').html('');
                
                        $('#adminForm #save_btn').attr('disabled',true); $('#adminForm #save_btn').text('Validating...'); 
                        $.ajax({
                            type: "POST",
                            url: '{{url("admin/seller/validate")}}',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                if(data == 'success'){  $('#js_sec').remove();
                                    $('#adminForm #save_btn').text('Saving...');
                                 //    submitForm(formData); return false;
                                     $('#adminForm #can_submit').val(1); $('#adminForm').submit();
                                     toastr.success('Seller approved successfully');
                                }else{
                                    var errKey = ''; var n = 0;
                                    $.each(data, function(key,value) { if(n == 0){ errKey = key; n++; }
                                        $('#adminForm #'+key).closest('div').find('.error').html(value);
                                        if(key == 'error' && value == 'info'){ $('#nav_tab_1').trigger('click'); }
                                        else if(key == 'error' && value == 'store'){ $('#nav_tab_2').trigger('click'); }
                                        else if(key == 'error' && value == 'storeSet'){ $('#nav_tab_3').trigger('click'); }
                                    }); 
                                    $('#'+errKey).focus();
                                    $('#adminForm #save_btn').attr('disabled',false); $('#adminForm #save_btn').text('Save'); return false;
                                }
                                return false;
                            }
                        });
                    }
                
          return false; 
        });
        $('body').on('click','button.close',function(){ $('#allert_success').fadeOut(); });

        $("body").on("change", ".status-btn", function () {
            var id          =   this.id.replace('status-','');
            var bId         =   this.id;
            var sts         =   $(this).prop("checked");
            var url         =   '{{url("admin/seller/updateStatus")}}';
            var smsg        =   'Seller activated successfully!';
            if (sts == true){ var status = 1; }else if (sts == false){var status = 0; smsg = 'Seller deactivated successfully!'; }
            updateStatus(id,bId,status,url,'dtrow-','is_active',smsg);
        });
        $('body').on('click','.delBtn',function(){  // alert('sss');
            var id          =   this.id.replace('delBtn-',''); 
            var status      =   1;
            var url         =   '{{url("admin/seller/updateStatus")}}';
            var smsg        =   'New seller deleted successfully!';
            swal({
                title: "Delete Confirmation",
                text: "Are you sure you want to delete this Seller?",
                // type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: 'Yes'
            },function(inputValue){
                if (inputValue == true) {
                    updateStatus(id,'',status,url,'seller','is_deleted',smsg);
                }
            });
        });
        
        $('body').on('click','#adminForm #deny_btn',function(){
            var id          =   $(this).data('id'); 
            var status      =   2;
            var url         =   '{{url("admin/seller/updateStatus")}}';
            var smsg        =   'Seller denied successfully!';
            swal({
                title: "Deny Confirmation", text: "Are you sure you want to deny this Seller?",
                showCancelButton: true, closeOnConfirm: true, confirmButtonText: 'Yes'
            },function(inputValue){ if (inputValue == true) { updateStatus(id,'',status,url,'new_seller','is_approved',smsg); } });
        });
        
        $('body').on('change','#country_id',function(){
            $.ajax({
                type: "POST",
                url: '{{url("admin/getDropdown")}}',
                data: {table: 'states',field: 'country_id', value:this.value, label:'state_name',selected: '', placeholder:'Select State','_token': '{{ csrf_token()}}'},
                success: function (data) {
                    $('#state_id').html(data);
                }
            });
        });
        $('body').on('change','#state_id',function(){
            $.ajax({
                type: "POST",
                url: '{{url("admin/getDropdown")}}',
                data: {table: 'cities',field: 'state_id', value:this.value, label:'city_name',selected: '', placeholder:'Select City','_token': '{{ csrf_token()}}'},
                success: function (data) {
                    $('#city_id').html(data);
                }
            });
        });

    });

    function updateStatus(id,rowId,status,url,row,field,smsg){
        $.ajax({
            type: "POST",
            url: url,
            data: { "_token": "{{csrf_token()}}", id: id, value: status,field: field, field, page: row,msg: smsg},
            success: function (data) {
                if(field == 'is_deleted' || field == 'is_approved'){ $('#active_filter').trigger('change'); toastr.success(smsg);
                  //  $('#content_list').html(data); toastr.success(smsg);
                }else{ 
                    if (data.type == 'warning' || data.type == 'error'){ toastr.error(smsg); }else{ toastr.success(smsg); }
                } 
            }
        });
    }
    function submitForm(postValues){
        $.ajax({
            type: "POST",
            url: '{{url("admin/seller/save")}}',
            data: postValues,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) { 
              $('#adminForm #save_btn').attr('disabled',false); $('#adminForm #save_btn').text('Save');
              if($('#adminForm #id').val() > 0){ var msg = 'Seller updated successfully!'; }else{ msg = 'Seller added successfully!'; }
              $('#content_list').html(data);  $('#content_list').fadeIn(700); $('#content_detail').hide(); ;
              toastr.success(msg);  return false;
            } 
        });
    }
    function getStateDropdown(cId,selected){ 
        $.ajax({
            type: "POST",
            url: '{{url("admin/getDropdown/states/")}}',
            data: {field: 'country_id', value:cId, label:'name',selected: selected, placeholder:'Select State','_token': '{{ csrf_token()}}'},
            success: function (data) {
                $('#userForm #state').html(data);
            }
        });
    }
    
    function readURL(input) { 
        if (input.files && input.files[0]) { 
            var reader = new FileReader();
            reader.onload = function (e) { $('#adminForm #'+input.id+'Img').attr('src', e.target.result); $('#adminForm #'+input.id+'Img').show(); }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
@endsection