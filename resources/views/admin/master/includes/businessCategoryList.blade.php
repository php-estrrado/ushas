   
                                        @if($category_sort && count($category_sort) > 0)
                                      <input type = "hidden" name="row_order" id="row_order" /> 
                                      <ul id="sortable-row">
									  
                    											@foreach($category_sort as $row)
                                                                <?php  $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
                                                                       $category_name=DB::table('cms_content')->where('cnt_id', $row->cat_name_cid)->where('lang_id', $default_lang->id)->first();
                                                                       $category_desc=DB::table('cms_content')->where('cnt_id', $row->cat_desc_cid)->where('lang_id', $default_lang->id)->first(); ?>
                                            <li id={{$row->category_id}}>{{$category_name->content}}</li>
                                          @endforeach
											@if(isset($category_not_in) && count($category_not_in) > 0)
												@foreach($category_not_in as $row1)
											<?php  $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
                                                                       $cat_name=DB::table('cms_content')->where('cnt_id', $row1->cat_name_cid)->where('lang_id', $default_lang->id)->first();
                                                                       $cat_desc=DB::table('cms_content')->where('cnt_id', $row1->cat_desc_cid)->where('lang_id', $default_lang->id)->first(); ?>
												<li id={{$row1->category_id}}>{{$cat_name->content}}</li>
												@endforeach
											@endif
                                      </ul>
                                      @endif
                                   