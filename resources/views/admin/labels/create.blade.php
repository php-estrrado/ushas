@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/css/combo-tree.css')}}" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
		<link href="{{URL::asset('admin/assets/plugins/quill/quill.snow.css')}}" rel="stylesheet">
        <link href="{{URL::asset('admin/assets/plugins/quill/quill.bubble.css')}}" rel="stylesheet">
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>
									<li class="breadcrumb-item " aria-current="page"><a href="{{url('/admin/tags')}}">Tags</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
								<!-- <div class="btn btn-list">
									<a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a>
									<a href="#"  data-target="#user-form-modal" data-toggle="modal" class="btn btn-danger addmodule"><i class="fe fe-shopping-cart mr-1"></i> Add New</a>
								</div> -->
							</div>
						</div>
                        <!--End Page header-->
@endsection
@section('content')
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">

							<!-- 	@if(Session::has('message'))

								<div class="alert alert-{{session('message')['type']}}" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{session('message')['text']}}</div>
								@endif
								@if ($errors->any())
								@foreach ($errors->all() as $error)

								<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{$error}}</div>
								@endforeach
								@endif -->
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsiv table-lg mt-3">
														
					<form action="" method="POST" id="LabelForm" enctype="multipart/form-data">
                     @csrf
					  <?php if(isset($labels)){
						  
						$label_for=$labels->label_for;
						$isactive=$labels->is_active;
						$label_id=$labels->id;
						$label_cid=$labels->label_cid;
						$identifier=$labels->identifier;
						
						 }else{ 
						$isactive="1";
						$label_id="";
						$label_cid="";
						$label_for="";
						$identifier="";
						} ?>
					 <input type="hidden" value="{{ $label_id }}" name="label_id" id="label_id">
					
					<input type="hidden" value="{{ $label_cid }}" name="label_content_id" id="label_content_id">
						
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label">Label For <span class="text-red">*</span></label>
									<select class="form-control custom-select select2" name="label_for" id="label_for" required>
									<option value="web" <?php if($label_for=="web"){ echo "selected";} ?>>Web</option>
									<option value="app" <?php if($label_for=="app"){ echo "selected";} ?>>App</option>
									</select>
									<span class="error"></span>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
								<label class="form-label">Status <span class="text-red">*</span></label>
								<select class="form-control " name="status">
									<option value="1" <?php if($isactive==1){ echo "selected";}?>>Active</option>
									<option value="0" <?php if($isactive==0){ echo "selected";}?>>Inactive</option>
								</select>
								<span class="error"></span>
							</div>
							</div>
							<div class="col-md-4">
							<div class="form-group">
							{{Form::label('','Identifier',['class'=>''])}} <span class="text-red">*</span>
								
							<span class="error"></span>
							<input type="text" name="identifier" class="form-control" readonly="readonly" id="identifier" value="{{$identifier}}">
							<input type="hidden"  class="form-control" readonly="readonly" id="identifier-hidden" value="{{$identifier}}">
							

						</div>
							
						</div>
						</div>
						<div class="row" id="lang_content">
						@include('admin.labels.includes.contents')											
                        </div>
                        <div class="row">
						
						

                        
                     </div>
                     <div class="col d-flex justify-content-end">
                        <a href="{{ route('labels.list')}}" class="mr-2 mt-4 mb-0 btn btn-secondary" >Cancel</a>
                        <button type="submit" id="saveBtn" class="btn btn-primary mt-4 mb-0" >Submit</button>
                     </div>
                  </form>

													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->


								

					</div>
				</div><!-- end app-content-->
            </div>
@endsection
@section('js')
		<!-- INTERNAl Data tables -->
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/jszip.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/datatables.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/comboTreePlugin.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/jquery.validate.min.js')}}"></script>       
	   <script src="{{URL::asset('admin/assets/plugins/quill/quill.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/form-editor2.js')}}"></script>

<script type="text/javascript">
var base_path = "{{url('/')}}";
$(document).ready(function(){
	
 $("#LabelForm").validate();

 $('body').on('submit','#LabelForm',function(e){ 
	e.preventDefault(); 
	
    //var myEditor = document.querySelector('#label_content')
	//var html = myEditor.children[0].innerHTML
	//$("#labelcontent").val(html);
	//if(html=='<p><br></p>')
			//{
				//$('#LabelForm #label_content').parents('.form-group').find('.error').html("Please enter label");
				//return false;
			//}	
	//if ($("#catForm").valid() === true){           
        var formData = new FormData(this);
        $('#LabelForm #saveBtn').attr('disabled',true);
        $('#LabelForm #saveBtn').text('Validating...'); 
        $request= $.ajax({
            type: "POST",
            url: '{{url("admin/label/validate")}}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
            if(data == 'success'){ 
                $('#LabelForm #saveBtn').text('Saving...');
                $('#LabelForm #saveBtn').attr('disabled',true);
                submitLabelForm(formData,'LabelForm','saveBtn'); return false;
                return false;
            }else {
                var errKey = ''; var n = 0;
                $.each(data, function(key,value) { if(n == 0){ errKey = key; n++; }
					if(key == "tp_image") { $(".image.error").html(value); }else{
                        $('#LabelForm #'+key).parents('.form-group').find('.error').html(value);
                    }
				});
				$('#LabelForm #'+errKey).focus();
                $('#LabelForm #saveBtn').attr('disabled',false);
                $('#LabelForm #saveBtn').text('Save'); return false;
                
                    return false;
            }
			}
        });
	//	}
		return false; 
    });
});
	
	function submitLabelForm(postValues,form,button){
		// alert();
        $.ajax({
            type: "POST",
            url: '{{url("admin/label/save")}}',
            data: postValues,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) { 
              $('#LabelForm #saveBtn').attr('disabled',false);
              $('#LabelForm #saveBtn').text('Save');
			 
				if(data == 1){
                var msg = 'Label added successfully'; 
                toastr.success(msg);
				}else if(data == 2){
                var msg = 'Label updated Successfully!'; 
                toastr.success(msg);
				}
				else{
                var msg = 'Label add failed!'; 
                toastr.error(msg);
				}
				window.location.replace(base_path+"/admin/labels");
            } 
        });
    }
	
	
$('body').on('change','#language',function(){	
	var defaultidentifier = $('#identifier-hidden').val();
	$('#identifier').val(defaultidentifier); 
	var lang_id=$(this).val();
	var label_cnt_id=$("#label_content_id").val();
	var label_id=$("#label_id").val();
	$.ajax({
                type: "POST",
                url: '{{ url("admin/label/content") }}',
                data: { lang_id:lang_id,label_cnt_id:label_cnt_id,label_id:label_id,'_token': '{{ csrf_token()}}'},
                success: function (data) {
                    $("#lang_content").empty().html(data);
							var toolbarOptions = [
							[{
								'header': [1, 2, 3, 4, 5, 6, false]
							}],
							['bold', 'italic', 'underline', 'strike'],
							[{
								'list': 'ordered'
							}, {
								'list': 'bullet'
							}],
							['link', 'image', 'video']
							];
							var quill = new Quill('#label_content', {
							modules: {
								toolbar: toolbarOptions
							},
							theme: 'snow'
							});
					
				
			}
	});	
});

$('body').on('keyup change','#labelcontent:input',function(e){

    var selected = $('#language').find('option:selected');
	console.log(selected);
	var defaultlang = selected.data('defaultlang'); 
	$('#is_default').val(defaultlang);
	
	if(defaultlang==1){
	var label=$(this).val();
	var identifier=label.toLowerCase()
             .replace(/ /g, '_')
             .replace(/[^\w-]+/g, '');
	 $('#identifier').val(identifier); 
	}
})
</script>
@endsection