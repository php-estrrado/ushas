<?php

 if(isset($lang_id)) {
	$default_langs =DB::table('glo_lang_lk')->where('id', $lang_id)->first();
	$lang_name=	$default_langs->glo_lang_name;
	$lang_id=$lang_id;
	
}
else{
$default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
$lang_id=$default_lang->id;
$lang_name=$default_lang->glo_lang_name;
}

$category_name=DB::table('cms_content')->where('cnt_id', $category->cat_name_cid)->where('lang_id', $lang_id)->first();
$category_desc=DB::table('cms_content')->where('cnt_id', $category->cat_desc_cid)->where('lang_id', $lang_id)->first(); 
if($category_name){
$cat_name=$category_name->content;
}else{
$cat_name="";
}
if($category_desc){
$category_desc=$category_desc->content;
}else{
$category_desc="";
}

?>
<div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ $lang_name }} Category Name <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control @error('category_name') is-invalid @enderror" placeholder="Category" name="category_name" value="{{ $cat_name }}">
                                                            @error('category_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ $lang_name }} Category Description <span class="text-red">*</span></label>
                                                                 <textarea class="form-control @error('category_description') is-invalid @enderror" placeholder="Description"  name="category_description">{{ $category_desc }}</textarea>
                                                           @error('category_description')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>