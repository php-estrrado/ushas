
<div id="content_list">@include('admin.seller.list.content')</div>
<div id="content_detail"></div>
@section('js') 
 <script src="{{asset('admin/assets/js/datatable/tables/seller-datatable.js')}}"></script>
<script src="{{URL::asset('admin/assets/js/jquery.validate.min.js')}}"></script>
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    $(document).ready(function(){ 
        setTimeout(function(){ 
            $('body #seller .switch').each(function(){
          var srch = $(this).data('search');
            $(this).closest("td").attr("data-search",srch);
        }); }, 1000);
        $('#adminForm #can_submit').val(0); 
        @if(Session::has('success')) toastr.success("{{ Session::get('success')}}"); 
        @elseif(Session::has('error')) toastr.error("{{ Session::get('error')}}");  @endif
        $("body").on('change','#adminForm #logo',function(){ readURL(this); });
        $("body").on('change','#adminForm #banner',function(){ readURL(this); });
        $('body').on('click','#cancel_btn',function(){ $('#content_list').fadeIn(700); $('#content_detail').hide();  });
        $('body').on('click','#bc_list',function(){ $('#content_list').fadeIn(700); $('#content_detail').hide(); return false;  });
        $('body').on('click','#addNew',function(){ 
            $.ajax({
                type: "GET",
                data: {active: $('#active_filter').val(), viewType: 'ajax' },
                url: '{{url("admin/seller/0")}}',
                success: function (data) {
                    $('#content_detail').html(data); $('#content_detail').fadeIn(700); $('#content_list').hide(); 
                  //  $('#content_detail #country_id').trigger("chosen:updated");
                } 
            }); return false;
        });
        
        
        $('body').on('click','.viewDtl',function(){ 
            var id      =   this.id.replace('dtlBtn-','');
            $.ajax({
                type: "GET",
                url: '{{url("admin/seller")}}/'+id+'/view',
                success: function (data) {
                    $('#content_detail').html(data); $('#content_detail').fadeIn(700); $('#content_list').hide(); 
                } 
            });
        });
        function validate_bank(){

jQuery.validator.addMethod("lettersonly", function(value, element) {
  return this.optional(element) || /^[a-z]+$/i.test(value);
}, "Letters only please"); 
$("#bankForm").validate({
rules: {

acc_number : {
required: true,
number: true,
maxlength: 20
},

bank_name: {
required: true,
lettersonly: true,
minlength: 3
},
ifsc: {
required: true,
maxlength: 10
},

branch_name: {
required: true,
lettersonly: true,
minlength: 3
},
acc_holder: {
required: true,
lettersonly: true,
minlength: 3
}

},

messages : {
acc_number: {
required: "Account Number is required.",
maxlength: "Account Number must be less than 20 digits"
},
bank_name: {
required: "Bank Name is required.",
minlength:"Enter valid Bank name"
},
ifsc: {
required: "IFSC Code is required.",
maxlength: "IFSC Code must be less than 10 digits"
},
branch_name: {
required: "Branch Name is required.",
minlength:"Enter valid branch name"
},
acc_holder: {
required: "Account Holder is required.",
minlength:"Enter valid name"
},


}
});
}
        $('body').on('click','.editBtn',function(){
            var id      =   this.id.replace('editBtn-','');  
            $.ajax({
                type: "GET",
                url: '{{url("admin/seller")}}/'+id,
                data: {active: $('#active_filter').val(), viewType: 'ajax' },
                success: function (data) {
                    $('#content_detail').html(data); $('#content_detail').fadeIn(700); $('#content_list').hide(); 
                    validate_bank(); 
                  //  $('#content_detail #country_id').trigger("chosen:updated");
                } 
            }); return false;
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
                             submitForm(formData); return false;
                           //  $('#adminForm #can_submit').val(1); $('#adminForm').submit();
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
            $('#seller').DataTable().ajax.reload();
        });
        $("body").on("change", ".service-status-btn", function () {
            var id          =   this.id.replace('service-status-','');
            var bId         =   this.id;
            var sts         =   $(this).prop("checked");
            var url         =   '{{url("admin/seller/updateServiceStatus")}}';
            var smsg        =   'Service status activated successfully!';
            if (sts == true){ var status = 1; }else if (sts == false){var status = 0; smsg = 'Service status deactivated successfully!'; }
            updateStatus(id,bId,status,url,'dtrow-','service_status',smsg);
        });
        $('body').on('click','.delBtn',function(){  
            var id          =   this.id.replace('delBtn-',''); 
            var status      =   1;
            var url         =   '{{url("admin/seller/updateStatus")}}';
            var smsg        =   'Seller deleted successfully!';
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
        
         $('body').on('change','#active_filter',function(){ 
            $('#seller').DataTable().ajax.url("{{url('/admin/sellers')}}?active="+this.value).load();
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
            data: { "_token": "{{csrf_token()}}", id: id, value: status,field: field, field, page: row},
            success: function (data) {
                if(field == 'is_deleted'){ 
                  $('#active_filter').trigger('change'); toastr.success(smsg);
                }else{ 
                    if($('#active_filter').val() != ''){ $('#active_filter').trigger('change'); }
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
              $('#content_list').fadeIn(700); $('#content_detail').hide();
              $('#active_filter').trigger('change'); toastr.success(msg);  return false;
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
