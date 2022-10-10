        <link href="{{URL::asset('admin/assets/css/combo-tree.css')}}" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
        <link href="{{URL::asset('admin/assets/css/daterangepicker.css')}}" rel="stylesheet" />
        <link href="{{URL::asset('admin/assets/css/jquery-ui.css')}}" rel="stylesheet" />
        <link href="{{URL::asset('admin/assets/css/chosen.min.css')}}" rel="stylesheet"/>
@php $row = 0; @endphp
<div id="list_form"> @include('admin.bulk_settlement.list.content')</div>
<div id="dtl_form">  </div>
@section('js') 
        <script src="{{URL::asset('admin/assets/js/moment.min.js')}}"></script>
        <script src="{{URL::asset('admin/assets/js/daterangepicker.js')}}"></script>
    <!-- INTERNAL Popover js -->
        <script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>
        <script src="{{URL::asset('admin/assets/js/comboTreePlugin.js')}}"></script>
        <script src="{{URL::asset('admin/assets/js/jquery-ui.js')}}"></script>
<script src="{{URL::asset('admin/assets/js/datatable/tables/bulksettlement-datatable.js')}}"></script>
<script src="{{URL::asset('admin/assets/js/chosen.jquery.min.js')}}"></script>
    <script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 

    $(document).ready(function(){ 
        
      $(".process_selection").removeClass("select-checkbox");
        pageSet();
        
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
        $('body').on('click', '#back_btn', function(){ $(this).prop('disabled',true); $(this).text('Redirecting..'); $('#bc_list').trigger('click'); });
        $('body').on('click', '#bc_list', function(){ 
            var id      =   $(this).data('seller'); 
            $.ajax({
                type: "GET",
                url: '{{url("admin/seller/settlements")}}', 
                data: {type: 'ajax'},
                success: function (data) {
                    $('#list_form').html(data); // $('#dtl_form').fadeIn(700); $('#list_form').hide();
                } 
            });
        });
        
        $('body').on('click','.editForm',function(){ 
            var id      =   $(this).data('seller');
             $(this).prop('disabled',true); $(this).text('Loading..'); 
            $.ajax({
                type: "GET",
                url: '{{url("admin/seller/earnings")}}/'+id, 
                success: function (data) {
                    $('#list_form').html(data); // $('#dtl_form').fadeIn(700); $('#list_form').hide();
                } 
            });
        });
        
        $('body').on('click','.payBtn',function(){ 
            var id      =   $(this).data('seller'); 
            $.ajax({
                type: "GET",
                url: '{{url("admin/seller/payment")}}/'+id,
                data: {page: 'settlement'},
                success: function (data) {
                    $('.modal-content').html(data);
                } 
            });
        });
         $('body').on('change','#state_id',function(){
             
            $.ajax({
                type: "POST",
                url: '{{url("/admin/getDropdown")}}',
                data: {"_token": "{{csrf_token()}}", table: 'cities', field: 'state_id', value: this.value, label: 'city_name', placeholder: 'Select City', selected: '' },
                success: function (data) {
                    $('#city_id').html(data);
                } 
            }); return false;
        });
        $('body').on('submit','#adminForm',function(e){  
            var rAmt    =   parseInt($('#adminForm #remain_amt').val());
            var pAmt    =   parseInt($('#adminForm #pay_amt').val());
            $('#adminForm .error').text(''); 
            if(pAmt > rAmt){ $('#adminForm #pay_error').text('Pay Amount Should be less than or equal to Pending Settlement amount'); return false; }
            else if(pAmt == '' || pAmt == 0){ $('#adminForm #pay_error').text('Enter Amount'); return false; }
            else{ 
                e.preventDefault();    
                var formData = new FormData(this);
                $('#adminForm #save_btn').attr('disabled',true); $('#adminForm #save_btn').text('Saving...'); 
             //   $('#adminForm #tab-1').trigger('click');
                $.ajax({
                    type: "POST",
                    url: '{{url("admin/seller/settlement/save")}}',
                    data: formData, cache: false, contentType: false, processData: false, 
                    success: function (data) {
                        $('#pg_content').html(data); $('#small-modal').modal('hide'); toastr.success("Payment Added Successfully!");
                    }
                });
            } return false;
        });
              
        @if(Session::has('success')) toastr.success("{{ Session::get('success')}}"); @endif
        @if(Session::has('error')) toastr.error("{{ Session::get('error')}}"); @endif
        
        $('body').on('change','.number',function(){  
            var id  =   this.id; var val = this.value.replace(/[^0-9\.]/g,''); $(this).val(val); if($(this).val() == ''){ $(this).val(0); }
        });
        $('body').on('change','.numberonly',function(){  
            var id  =   this.id; var val = this.value.replace(/[^0-9]/g,''); $(this).val(val); if($(this).val() == ''){ $(this).val(0); }
        });
        

			$('body').on('click','#viewfilter',function(){
			 // 	alert("clicked");
			// $('#auctionslist tbody').append($('#loader').html()); 
			$('#settlement-table').addClass('blur'); 
			var startdate = $("#startdate").val();
			var enddate = $("#enddate").val();
			var state_id = $("#state_id").val();
			var city_id = $("#city_id").val();
			var filterSell = $('#filterSell option:selected').toArray().map(item => item.value).join();
			

			console.log(startdate+" "+enddate+" "+state_id+" "+city_id+" "+filterSell);


			$.ajax({
			type: "POST",
			url: '{{url("/admin/bulk-settlements/filter")}}',
			
			data: { "_token": "{{csrf_token()}}", startdate: startdate,enddate:enddate,state_id:state_id,city_id:city_id,filterSell:filterSell,type: 'ajax'},
			success: function (data) {
			 //   $('#list_form').html(data);
			
                var table = $.fn.dataTable.tables( { api: true } );
                if(data =="0") {
                // alert("no data");
                table.clear().draw();
                }else {
                $("#hiddentable tbody").html(data);
                // alert(data.length);
                html = data;
                i=0;
                var htmlFiltered = $(html).find("tr")
                console.log(html);
                table.clear().draw();
                $("#hiddentable tr").each(function(index, tr) { 
                console.log(index);
                console.log(tr);
                table.row.add($(tr)).columns.adjust().draw();
                });
                
                //   table.rows.add(data); // Add new data
                //   table.columns.adjust().draw();
                
                
                }
                $('#auctionslist').removeClass('blur');
                  $(".process_selection").removeClass("select-checkbox");
                   $("#process_total").val(0);

			}
			});

			});       
    $('body').on('click','.processitem',function(e){
    var item_total = 0;
    $("#process_total").val(item_total);
    $(".process_selection input[type=checkbox]:checked").each(function(){
    item_total = item_total+$(this).data("items");
    });
    $("#process_total").val(item_total);
    });
			 $('body').on('click','.paynow',function(e){

 var checked = $(".process_selection input[type=checkbox]:checked").length;

      if(!checked) {
  
        swal('Unable to process!', 'Please select atleast 1 seller to process.', 'error');
        return false;
      }else {
     
        // jQuery("#returnsform").submit();

        $('body').removeClass('timer-alert');
        swal({
            title: "Process Settlement",
            text: "Are you sure you want to process settlement to selected sellers?",
            // type: "input",
            showCancelButton: true,
            closeOnConfirm: true,
            confirmButtonText: 'Yes'
        },function(inputValue){

        if (inputValue == true) {
        jQuery("#bulkForm").submit();

            }
        });

      }

    
        
    });
        
  
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
              if($('#adminForm #id').val() > 0){ var msg = 'Atribute updated successfully!'; }else{ msg = 'Atribute added successfully!'; }
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
    function pageSet(){
        $(".process_selection").removeClass("select-checkbox");
$(".chosen-select").chosen({
no_results_text: "Oops, nothing found!"
})

$('.plus-minus-toggle').on('click', function() {
$(this).toggleClass('collapsed');
$('#filtersec').toggle('slow');
});
       $('#valid_from').daterangepicker
        (
          {
            locale: {
                      format: 'DD/MM/YYYY'
                    },
            ranges:
            {
              'Today'       : [moment(), moment()],
              'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Tomorrow'    : [moment().add(1, 'days'), moment().add(1, 'days')],
              'Next 7 Days' : [moment(),moment().add(6, 'days')],
              'Next 30 Days': [moment(),moment().add(29, 'days')],
              'This Month'  : [moment().startOf('month'), moment().endOf('month')],
              'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
          },
          function(start, end, label)
          {
            startDate = start.format('YYYY-MM-DD');
            endDate = end.format('YYYY-MM-DD');
            console.log('A date range was chosen: ' + startDate + ' to ' + endDate);
            $("#startdate").val(startDate);
            $("#enddate").val(endDate);
      
        
          }
        ); 
    }
        $(document).ready(function(){
            @if(Session::has('message'))
            @if(session('message')['type'] =="success")
            
            toastr.success("{{session('message')['text']}}"); 
            @else
            toastr.error("{{session('message')['text']}}"); 
            @endif
            @endif
            
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            toastr.error("{{$error}}"); 
            
            @endforeach
            @endif
    });
</script>
@endsection
