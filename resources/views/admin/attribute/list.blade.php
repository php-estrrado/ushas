
@php $row = 0; @endphp
<div id="list_form"> @include('admin.attribute.list.content')</div>
<div id="dtl_form">  </div>
@section('js') 
     <script src="{{URL::asset('admin/assets/js/datatable/tables/attribute-datatable.js')}}"></script>

    <script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    $(document).ready(function(){ 
        
        
        
        
        $('body').on('click','#addData',function(){
            $.ajax({
                type: "GET",
                url: '{{url("admin/attribute/detail/0")}}',
                data: {active: $('#active_filter').val()},
                success: function (data) {
                    $('#dtl_form').html(data); $('#dtl_form').fadeIn(700); $('#list_form').hide(); 
                } 
            }); return false;
        });
        
        $('body').on('click', '#cancel_btn', function(){ $('#dtl_form').hide(); $('#list_form').fadeIn(700); });
        $('body').on('click', '#bc_list', function(){ $('#dtl_form').hide(); $('#list_form').fadeIn(700); return false; });
        
        $('body').on('click','.editForm',function(){ 
            var id      =   this.id.replace('editForm-','');  $('#adminForm .error').text(''); 
            $.ajax({
                type: "GET",
                url: '{{url("admin/attribute/detail")}}/'+id,
                data: {active: $('#active_filter').val(), viewType: 'ajax' },
                success: function (data) {
                    $('#dtl_form').html(data); $('#dtl_form').fadeIn(700); $('#list_form').hide();
                } 
            });
        });
        
        $('body').on('click','.viewDtl',function(){ 
            var id      =   this.id.replace('dtlBtn-','');
            $.ajax({
                type: "GET",
                url: '{{url("admin/attribute/detail")}}/'+id+'/view',
                success: function (data) {
                    $('#dtl_form').html(data); $('#dtl_form').fadeIn(700); $('#list_form').hide();
                } 
            });
        });
        
        $('body').on('submit','#adminForm',function(e){ 
            $('#adminForm .error').html(''); var tb1; var tb2; var tb3;
            if($('#adminForm #can_submit').val() > 0){ return true; }
            else{ 
                e.preventDefault();    
                var formData = new FormData(this);
                $('#adminForm #save_btn').attr('disabled',true); $('#adminForm #save_btn').text('Validating...'); 
                $.ajax({
                    type: "POST",
                    url: '{{url("admin/attribute/validate")}}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if(data == 'success'){ 
                            $('#tab2 .tLabel').each(function(){ 
                                if(this.value == ''){ $(this).closest('div').find('.error').text('This field is required'); tb2 = false; }
                            });
                            $('body #tab3 .vLabel').each(function(){ 
                                if(this.value == ''){ $(this).closest('div').find('.error').text('This field is required'); tb3 = false; }
                            });
                            if(tb2 == false){ 
                                $('#nav_tab_2').trigger('click'); 
                                $('#adminForm #save_btn').attr('disabled',false); $('#adminForm #save_btn').text('Save'); return false;
                            }
                            
                            else if(tb3 == false){ 
                                $('#nav_tab_3').trigger('click'); 
                                $('#adminForm #save_btn').attr('disabled',false); $('#adminForm #save_btn').text('Save'); return false;
                            }
                            $('#adminForm #save_btn').text('Saving...'); submitForm(formData); return false;
                          //   else{ $('#adminForm #can_submit').val(1); $('#adminForm').submit(); } return false;
                        }else{
                            var errKey = ''; var n = 0; $('#nav_tab_1').trigger('click'); 
                            $.each(data, function(key,value) {  if(n == 0){ errKey = key; n++; }
                                $('#adminForm #tab1 #'+key).closest('div').find('.error').html(value);
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
        
        $("body").on("change", ".status-btn", function () {
            var id          =   this.id.replace('status-','');
            var bId         =   this.id;
            var sts         =   $(this).prop("checked");
            var url         =   '{{url("admin/attribute/updateStatus")}}';
            var smsg        =   'Attrinute activated successfully!';
            if (sts == true){ var status = 1; }else if (sts == false){var status = 0; smsg = 'Attrinute deactivated successfully!'; }
            if($('#active_filter').val() != ''){ $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur');  }
            updateStatus(id,bId,status,url,'dtrow-','is_active',smsg);
        });
        $('body').on('click','.delBtn',function(){ //alert('sss');
            var id          =   this.id.replace('delBtn-',''); 
            var status      =   1;
            var url         =   '{{url("admin/attribute/updateStatus")}}';
            var smsg        =   'Attrinute deleted successfully!';
            
            swal({
                title: "Delete Confirmation",
                text: "Are you sure you want to delete this Attribute?",
                // type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: 'Yes'
            },function(inputValue){
                if (inputValue == true) { 
                    updateStatus(id,'',status,url,'attribute','is_deleted',smsg);
                }
            });
        });
        $('body').on('change','#active_filter',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("admin/attributes")}}',
                data: {active: this.value,viewType: 'ajax'},
                success: function (data) {
                    $('#pg_content').html(data); 
                    $('#table_body').remove($('#loader').html()); $('#attribute').removeClass('blur');
                } 
            });
        });
        
        var row      =   parseInt('{{$row}}'); 
        $('body').on('change','#adminForm #type',function(){ 
            $('#adminForm #data_type').val(''); 
            if(this.value == 'text' || this.value == 'textarea'){ 
                $('#adminForm #data_type_div').show(); $('#adminForm #filter_div').hide(); $('#adminForm #filter').val(0); $('#adminForm #config_div').hide(); $('#adminForm #configur').val(0);
                $('#adminForm .panel-tabs #nav_tab_3').hide(); 
            }else{ 
                $('#adminForm #data_type_div').hide(); $('#adminForm #filter_div').show(); $('#adminForm #filter').val(0); $('#adminForm #config_div').show(); $('#adminForm #configur').val(0);
                $('#adminForm .panel-tabs #nav_tab_3').show(); 
            }
        });
        
        $('body').on('click','#add_val',function(){ 
            var htmlContent             =   $('#adnl_rows').html();
            htmlContent                 =   htmlContent.replace('attr_val_row_id','attr-val-row-'+row);
            htmlContent                 =   htmlContent.replace('value_id_id','value_id_'+row);
            htmlContent                 =   htmlContent.replace('attr_val_id','val'+row);
            htmlContent                 =   htmlContent.replace('val_error_id','val_error_'+row);
            htmlContent                 =   htmlContent.replace('del_val_id','del_val_'+row);
            $('#adminForm #attr-val-content').append(htmlContent); row++;
        });
        
        $('body').on('click','#adminForm .del_val.del',function(){
            var id      =   this.id.replace('del_val_',''); deleteValue(id);
        });
        
        $('body').on('click','#adminForm .del_val.del',function(){
            var id      =   this.id.replace('del_val_',''); deleteValue(id);
        });
        @if(Session::has('success')) toastr.success("{{ Session::get('success')}}"); @endif
        @if(Session::has('error')) toastr.error("{{ Session::get('error')}}"); @endif
    });
    
//    function confirmDelete(id,row){
//        var cnf      =   confirm("Are you sure?!");
//        if(cnf){ 
//            $.ajax({
//                type: "POST",
//                url: "{{url('attribute/value/delete')}}",
//                data: { "_token": "{{csrf_token()}}", id: id},
//                success: function (data) { deleteValue(row); }
//            });
//        }else{ return false; }
//    }
    function deleteValue(id){ $('#adminForm #attr-val-row-'+id).remove();}
    
    function updateStatus(id,rowId,status,url,row,field,smsg){  
        $.ajax({
            type: "POST",
            url: url,
            data: {id: id, value: status,field: field,page: row},
            success: function (data) {  
                if(field == 'is_deleted'){ 
                    $('#active_filter').trigger('change'); toastr.success(smsg);
                }else{ 
                    if($('#active_filter').val() != ''){ $('#active_filter').trigger('change'); }
                    if (data.type == 'warning' || data.type == 'error'){ toastr.error(smsg); }else{ toastr.success(smsg); }
                } 
            }
        }); return false;
    }
    function submitForm(postValues){
        $.ajax({
            type: "POST",
            url: '{{url("admin/attribute/save")}}',
            data: postValues,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) { 
              $('#adminForm #save_btn').attr('disabled',false); $('#adminForm #save_btn').text('Save');
              if($('#adminForm #id').val() > 0){ var msg = 'Atribute updated successfully!'; }else{ msg = 'Atribute created successfully!'; }
              $('#pg_content').html(data); $('#dtl_form').hide(); $('#pg_content').fadeIn(700); // $('#list_form').html(data); 
              toastr.success(msg);
  return false;
            //  setTimeout(function(){ $('#allert_success').fadeOut(); }, 3000);
            } 
        }); return false;
    }
    function getStateDropdown(cId,selected){ 
        $.ajax({
            type: "POST",
            url: '{{url("admin/getDropdown/states/")}}',
            data: {field: 'country_id', value:cId, label:'name',selected: selected, placeholder:'Select State','_token': '{{ csrf_token()}}'},
            success: function (data) {
                $('#adminForm #state').html(data);
            }
        });
    }
</script>
@endsection
