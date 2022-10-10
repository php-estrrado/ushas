<div class="card">
							<div class="row no-gutters">
								<div class="col-lg-5 col-xl-4">
									<div class="overflow-hidden mb-0 mb-lg-0">
										<div class="card-body p-0">
											<div class="main-content-left main-content-left-chat">
												<div class="p-4 pb-0 border-bottom">
													<div class="input-group">
														<input class="form-control" placeholder="Search friends..." type="text" id="myInput">
														<span class="">
															<!--<button class="btn btn-primary br-tl-0 br-bl-0" type="button">-->
															<!--	<span class="input-group-btn"><i class="fa fa-search"></i></span>-->
															<!--</button>-->
														</span>
													</div>
												</div>
												
												<div class="main-chat-list" id="ChatList" style="min-height:300px;">
													@if(count($list)>0)
													@foreach($list as $row)
													@php $view=$row['chat_id']; @endphp
													<a href="#" id="viewBtn-@php echo $view @endphp" class="parent_a" onclick="loadChat({{$view}})">
														@if($row['chat_id']==$chat_id_latest)
														<input type="hidden" name="chat_categories" id="chat_categories" class="chat_categories" value="{{$chat_id_latest}}">
														<div class="media selected">
														@else
														<div class="media new" id="search_media">
														@endif	
														
															<div class="main-img-user">
																<img alt="" src="{{$row['profile_img']}}" class="avatar avatar-md brround">
																@if($row['unread_msg']>0)
																<span>
																{{$row['unread_msg']}}</span>
																@endif
															</div>
															<div class="media-body" id="media-body">
																<div class="media-contact-name" id="media-contact-name">
																	<span class=".sspan chatname">{{$row['cust_name']}}</span> 
																	<span>{{$row['last_msg_on']}}</span>
																</div>
																<p style="white-space: nowrap; width: 100px; overflow: hidden;text-overflow: ellipsis;">{{$row['latest_msg']}}</p>
															</div>
														</div>
													</a>
													@endforeach
													@else
													<div class="media new"><h4>No chat found</h4></div>
													@endif
													
												</div><!-- main-chat-list -->
											</div>
										</div>
									</div>
								</div>
								<div class="col-xl-8 col-lg-7">
									<div class="border-left chat_response" id="chat_response">
										
									</div>
								</div>
							</div>
						</div>
						<!-- /Row -->
						<div id="modal01" class="modal01" class="w3-modal" onclick="this.style.display='none'" data-backdrop="static">
  <span class="w3-button w3-hover-red w3-xlarge w3-display-topright">&times;</span>
  <div class="w3-modal-content w3-animate-zoom">
    <img id="img01" style="width:100%">
  </div>
</div>
