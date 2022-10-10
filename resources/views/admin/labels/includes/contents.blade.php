<?php if(isset($labelContent) && !empty($labelContent)){ 
	
 	$label_lang_id=$labelContent->lang_id;
	$labelcont=$labelContent->content;
	
}else{ 
if(isset($lang_id)){$lang_ids=$lang_id;}else{$lang_ids=0;}
	$label_lang_id=$lang_ids;
	$labelcont="";
	
}	
?>

 <div class="col-md-12">
    <div class="form-group">
        <label class="form-label">Select Language <span class="text-red">*</span></label>
		@if(isset($labels))
			<select class="form-control custom-select select2" name="language" id="language" required>
			@if($language)
			@foreach ($language as $lang)
				<option value="{{ $lang->id }}" data-defaultlang="{{$lang->is_default}}" <?php if($label_lang_id==$lang->id){ echo "selected";}?>>{{ $lang->glo_lang_name }} <?php if(1==$lang->is_default){ echo "(Default)";}?></option>
			@endforeach
			@endif
			</select>
		@else
			<select class="form-control custom-select select2" name="language" id="language" required readonly="readonly">
			@if($language)
			@foreach ($language as $lang)
				<option value="{{ $lang->id }}" data-defaultlang="{{$lang->is_default}}" <?php if(1==$lang->is_default){ echo "selected";}?>>{{ $lang->glo_lang_name  }} <?php if(1==$lang->is_default){ echo "(Default)";}?> </option>
			@endforeach
			@endif
			</select>
        @endif
        <input type="hidden" name="is_default" id="is_default" >
		<span class="error"></span>
    </div>
</div>

<div class="col-md-12">
						<div class="form-group">
							{{Form::label('','Label',['class'=>''])}} <span class="text-red">*</span>
								{{--<div id="label_content" name="" class="form-control">
							{!!html_entity_decode($labelcont)!!} 
								</div>--}}
							<span class="error"></span>
							<input type="text" name="labelcontent" class="form-control" id="labelcontent" value="{{$labelcont}}">
							

						</div>
						
					</div>



