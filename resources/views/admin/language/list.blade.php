@php $currency = getCurrency()->name; @endphp
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                    <table id="languages" class="languages  table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p">Select</th>
                                            <th class="wd-15p">Language</th>
                                            <th class="wd-15p">Code</th>
                                           <!--  <th class="wd-15p">Created at</th> -->
                                            <th class="wd-15p">Status</th>
                                            <th class="wd-15p">Action</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @if($list && count($list) > 0) @php $n = 0; @endphp
                                            @foreach($list as $row) @php $n++; @endphp 
                                    <tr>    
                                        <td class="align-middle select-checkbox">
										 <span class=""></span>
										</td>
                                        @php $view = $row['id']; @endphp
                                        <td>{{$row['glo_lang_name']}}
                                        @if($row['is_default']==1)
                                        <span class="glyphicon glyphicon-flag text-success"></span>
                                        @endif
                                        </td>
                                        <td>{{$row['glo_lang_code']}}</td>
                                        <!-- <td class="text-nowrap align-middle"><span>{{date('d M Y',strtotime($row->created_at))}}</span></td> -->
                                        <td class="text-nowrap align-middle" data-search="@if($row->is_active==1){{ "Active" }}@else{{ "Inactive" }}@endif">
																	    <div class="switch">
                                                                            <input class="switch-input status-btn ser_status" data-selid="{{$row->id}}" id="status-{{$row->id}}"  data-id="{{ $row->id }}" name="status" type="checkbox"  @if($row->is_active==1) {{ "checked" }} @endif >
                                                                            <label class="switch-paddle" for="status-{{$row->id}}">
                                                                                <span class="switch-active" aria-hidden="true">Active</span>
                                                                                <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                                                            </label>
                                                                        </div>
																	</td>
                                        

                                        <td class="align-middle">
                                        @if($row->is_default!=1)    
                                        <div class="btn-group align-top">

                                         <a href="{{ url('admin/language/edit/') }}/{{$row->id}}"   class="btn btn-sm btn-info mr-2"><i class="fe fe-edit mr-2"></i> Edit</a>
                                         <button  class="btn btn-sm btn-secondary deletecurrency" type="button" onclick="delete_cat({{$row->id}})" ><i class="fe fe-trash-2"></i>Delete</button>
                                          </div>
                                          @endif
                                         </td>
                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                </table>
                                {{ csrf_field() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            
        <!-- End Page -->

       


 

 @section('js')
 <script src="{{URL::asset('admin/assets/js/datatable/tables/languages-datatable.js')}}"></script>
<script>

 function delete_cat(id){
	    
	   $('body').removeClass('timer-alert');
        swal({
            title: "Delete Confirmation",
            text: "Are you sure you want to delete this language?",
            // type: "input",
            showCancelButton: true,
            closeOnConfirm: true,
            confirmButtonText: 'Yes'
        },function(inputValue){
    if (inputValue == true) {
        $.ajax({
            type: "POST",
            url: '{{url("/admin/language/delete/")}}',
            data: { "_token": "{{csrf_token()}}", id: id},
            success: function (data) {
                location.reload();

            }
        });
        }
    });
    }
    
     jQuery(document).ready(function(){
        

        $(".ser_status").on("click", function(e){
        
        var selid = jQuery(this).data("selid");
        
        var sestatus='0';
        if($(this).prop('checked') == true)
        {
        sestatus='1';
        }
        
        $.ajax({
        type: "POST",
        url: '{{url("/admin/language/status")}}',
        data: { "_token": "{{csrf_token()}}", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
        if(sestatus ==1) {
        jQuery('#status-'+selid).closest("td").attr("data-search","Active");
        toastr.success("Language activated successfully.");   
        }else {
        jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
        toastr.success("Language deactivated successfully.");  
        }
        var table = $.fn.dataTable.tables( { api: true } );
        table.rows().invalidate().draw();
        
        }else {
        toastr.error("Failed to update status."); 	
        }
        
        
        }
        });
        });
     });
</script>

<script type="text/javascript">
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
 