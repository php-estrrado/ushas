<?php
$default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
 if(isset($lang_id)) {
$selected_lang =	 DB::table('glo_lang_lk')->where('id', $lang_id)->first();
$lang_name=$selected_lang->glo_lang_name;
	$getlang_id=$lang_id;
	$lang_id=$lang_id;
}
else{
$lang_name=$default_lang->glo_lang_name;
$lang_id=$default_lang->id;
}
$subcategory_name=DB::table('cms_content')->where('cnt_id', $subcategory->sub_name_cid)->where('lang_id', $lang_id)->first();
if($subcategory_name){
$subname=$subcategory_name->content;
}else{
	$subname='';
}
if($subcategory->desc_cid){
$subcategory_desc=DB::table('cms_content')->where('cnt_id', $subcategory->desc_cid)->where('lang_id', $lang_id)->first();
if($subcategory_desc){
$desc=$subcategory_desc->content;
}else{
	$desc='';
}
}else{$desc='';} 
if(isset($getlang_id)){ 

if($default_lang->id==$getlang_id){

?>
<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">{{$lang_name}} Subcategory Name <span class="text-red">*</span></label>
        {{Form::select('sub_category_name',$subcategories_list,$subcategory->sabcatlist_id,['id'=>'sub_category_name','class'=>'form-control','placeholder'=>'Select Subcategory'])}}
        <input type="hidden" name="id" id="curent_subid" value="{{ $subcategory->parent }}" />
        <input type="hidden" name="id" id="curent_subid1" value="0" />
        <input type="hidden" name="lang_sub_category_name" id="lang_sub_category_name" value="{{$subname}}" />
        @error('sub_category_name')
        <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
        </span>
        @enderror
	</div>
</div>
<?php } else{ 
?>
<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">Subcategory Name (Default) <span class="text-red">*</span></label>
        {{Form::select('sub_category_name',$subcategories_list,$subcategory->sabcatlist_id,['id'=>'sub_category_name','class'=>'form-control','placeholder'=>'Select Subcategory','disabled' => 'disabled'])}}
        <input type="hidden" name="sub_category_name" id="sub_category_name" value="{{ $subcategory->sabcatlist_id }}" />
        <input type="hidden" name="id" id="curent_subid" value="{{ $subcategory->parent }}" />
        <input type="hidden" name="id" id="curent_subid1" value="0" />
        <input type="hidden" name="lang_sub_category_name" id="lang_sub_category_name" value="{{$subname}}" />
		@error('sub_category_name')
        <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
        </span>
        @enderror
	</div>
</div>
<div class="col-md-12">
    <div class="form-group">
    <label class="form-label">{{$lang_name}} Subcategory Name <span class="text-red">*</span></label>
    <input type="text" class="form-control @error('sub_category_name') is-invalid @enderror" placeholder="Sub category" name="lang_sub_category_name" value="{{ $subname }}">
         @error('sub_category_name')
                   <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                </span>
                 @enderror
            </div>
    </div>
<?php } }else{ ?>
<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">{{$lang_name}} Subcategory Name <span class="text-red">*</span></label>
        {{Form::select('sub_category_name',$subcategories_list,$subcategory->sabcatlist_id,['id'=>'sub_category_name','class'=>'form-control','placeholder'=>'Select Subcategory'])}}
        <input type="hidden" name="id" id="curent_subid" value="{{ $subcategory->parent }}" />
        <input type="hidden" name="id" id="curent_subid1" value="0" />
        <input type="hidden" name="lang_sub_category_name" id="lang_sub_category_name" value="{{$subname}}" />
		@error('sub_category_name')
        <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
        </span>
        @enderror
	</div>
</div>
<?php } ?>

                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">{{$lang_name}} Subcategory Description <span class="text-red"></span></label>
                                                                <input type="text" class="form-control" placeholder="Description" name="subcategory_description" value="{{ $desc }}">
                                                            </div>
                                                        </div>
                                                        
                                                         <input type="hidden" value="{{ $subcategory->is_active }}" name="status">