@extends('dashboard.layout.layout')
@section('page_title', 'Dashboard')
@section('content')

	               Account Overview
	            </h1>
	         </div>
	      </div>
   </section>
   <style>
   	.dasBoardPopup .popupColupsPanel .titlePanel a.actionBtn {
    float: right;
    border: none;
    border-radius: 3px;
    display: inline-block;
    color: #fff !important;
    font-size: 13px;
    padding: 7px 22px;
    background: var(--newYello-color) !important;
    line-height: 14px;
    cursor: pointer;
    transition: .3 ease-in-out;
}

.dasBoardPopup .popupColupsPanel_top .titlePanel a.actionBtn {
    float: right;
    border: none;
    border-radius: 3px;
    display: inline-block;
    color: #fff !important;
    font-size: 13px;
    padding: 7px 22px;
    background: var(--newYello-color) !important;
    line-height: 14px;
    cursor: pointer;
    transition: .3 ease-in-out;
}
   </style>
   <section class="adminDasboartBody blueGradeint">
	      <div class="container">
	         <div class="row adminProfile">
	         	@if(Session::has('success'))
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-success">
								{{Session::get('success') }}
							</div>
						</div>
					</div>
				@endif
	            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left profile_left">
	               <div class="author_thum_details">
	                  <img src="{{ !blank(Auth::user()->profile_picture) ?  asset('irh_assets/uploads/profile_pictures/' . Auth::user()->profile_picture) : asset('irh_assets/images/account_profile_default.png') }}" class="img-thumbnail" alt="">
	                  <div class="author_thum_title">
	                     <h3>{!! Auth::user()->first_name . " " .  Auth::user()->last_name !!}
	                        <span>{!! ucfirst(Auth::user()->roles[0]->name) !!}</span>
	                     </h3>
	                  </div>
	               </div>
	               
						   @if(Auth::user()->roles[0]->name == 'admin')
						   <div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
								<div class="overviewUserBox">
									<p>Total User</p>
									<h3>{!! count($users) !!}</h3>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
								<div class="overviewUserBox">
									<p>Active User</p>
									<h3>{!! count(collect($users)->where('validate', 1)) !!}</h3>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
								<div class="overviewUserBox">
									<p>Total Donation</p>
									<h3>{!! '£' . collect($donations)->sum('amount') !!}</h3>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
								<div class="overviewUserBox">
									<p>Total Resource</p>
									<h3>{!! count($resources) !!}</h3>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
								<a href="javascript:void(0)" data-toggle="modal" data-target="#followers_modal">
								<div class="overviewUserBox">
									<p>Followers</p>
									<h3>{{ !blank(Auth::user()->followers) ? count(explode(',', Auth::user()->followers)) : '0' }}</h3>
								</div></a>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
								<a href="javascript:void(0)" data-toggle="modal" data-target="#following_modal">
								<div class="overviewUserBox">
									<p>Following</p>
									<h3>{{ !blank(Auth::user()->following) ? count(explode(',', Auth::user()->following)) : '0' }}</h3>
								</div></a>
							</div>
							</div>
						@endif
						@if(Auth::user()->roles[0]->name == 'moderator')
							<div class="profile_right margin_top">
							<div class="pro_right_sec">
			                  	<h3>Insights - {!! Auth::user()->first_name . " " .  Auth::user()->last_name !!}</h3>
			                  	<ul class="insight">
									<li><span> {!! count($reportedUsers) !!}</span><span class="sml"> Reported Users</span></li>
									<li><span> {!! count(collect($resources)->where('approved_by', '=', Auth::user()->id)) !!}</span> <span class="sml">Resources Approved</span></li>
									<li><span> {!! count($flagedResources) !!}</span> <span class="sml">Resources Flagged</span></li>
			                  	</ul>
							</div>
							</div>
						@endif
	               
	            </div>
	            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left profile_right">
	            	@if(Auth::user()->roles[0]->name != 'admin')
	            	<a href="javascript:void(0)" data-toggle="modal" data-target="#following_modal">
						<div class="pro_right_sec_6">
							<p>Following</p>
							<h3>{{ !blank(Auth::user()->following) ? count(explode(',', Auth::user()->following)) : '0' }}</h3>
					   	</div>
				   	</a>
				   	<a href="javascript:void(0)" data-toggle="modal" data-target="#followers_modal">
						<div class="pro_right_sec_6_right">
							<p>Followers</p>
							<h3>{{ !blank(Auth::user()->followers) ? count(explode(',', Auth::user()->followers)) : '0' }}</h3>
						</div>
					</a>
					<div class="pro_right_sec_6">
						<p>My Resources</p>
						<h3>{{ count($myResources) }}</h3>
					</div>
					<div class="pro_right_sec_6_right">
						<p>Visits to profile page</p>
						<h3>{{ \App\ProfileView::getProfileViewed(Auth::user()->id) }}</h3>
					</div>
					<br clear="all">
				   	@endif
	            	@if(Auth::user()->roles[0]->name == 'admin')
	               	<div class="pro_right_sec">
	                  	<h3>Today's Insights</h3>
	                  	<ul class="insight">
							<li><span> {!! collect($resourceDownload)->where('created_at', '>=', \Illuminate\Support\Carbon::today())->sum('downloads') !!}</span><span class="sml">Download</span></li>
							<li><span> {!! count(collect($visitors)->where('created_at', '>=', \Illuminate\Support\Carbon::today())) !!}</span><span class="sml">Visitors</span></li>
							<li><span> {!! collect($donations)->where('created_at', '>', \Illuminate\Support\Carbon::today())->sum('amount') !!}</span> <span class="sml">Donation</span></li>
							<li><span> {!! count(collect($subscribers)->where('created_at', '>', \Illuminate\Support\Carbon::today())) !!}</span> <span class="sml">Subscribers</span></li>
	                  	</ul>
	               	</div>
	               	<div class="pro_right_sec">
	                  	<h3>Monthly Insights</h3>
	                  	<ul class="insight">
							<li><span> {!! collect($resourceDownload)->sum('downloads') !!}</span><span class="sml">Download</span></li>
							<li><span> {!! count(collect($visitors)->where('created_at', '>', \Illuminate\Support\Carbon::now()->subDays(30))) !!}</span><span class="sml">Visitors</span></li>
							<li><span> {!! collect($donations)->where('created_at', '>', \Illuminate\Support\Carbon::now()->subDays(30))->sum('amount') !!}</span> <span class="sml">Donation</span></li>
							<li><span> {!! count(collect($subscribers)->where('created_at', '>', \Illuminate\Support\Carbon::now()->subDays(30))) !!}</span> <span class="sml">Subscribers</span></li>
	                  	</ul>
	               	</div>
	               	@endif
	               	<div class="pro_right_sec">

	                  	<h3>Quick Actions</h3>
	                  	<ul class="acOverQuicAc">
							<li>
								<a href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.create.resource') : route('user.create.resource') }}">
									<div class="icon_main">
										<div class="icon"><img src="{{ asset('irh_assets/images/upload_a.png') }}" alt=""></div>
									</div>
								<span>Upload a resource</span></a>
							</li>
							<li>
								<a href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.myResources.list') : route('user.myResources.list') }}">
									<div class="icon_main">
										<div class="icon"><img src="{{ asset('irh_assets/images/book_a.png') }}" alt=""></div>
									</div>
									<span>View my resources</span>
								</a>
							</li>
							<li>
								<a href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.own.profile.form') : route('user.own.profile.form') }}">
									<div class="icon_main">
										<div class="icon"><img src="{{ asset('irh_assets/images/userNew.png') }}" alt=""></div>
									</div>
									<span>Edit Profile</span>
								</a>
							</li>
							<li>
								<a href="{{ route('theme.resources.authorprofile', Auth::user()->id) }}" target="_blank">
								<div class="icon_main">
									<div class="icon"><img src="{{ asset('irh_assets/images/public_icon.png') }}" alt=""></div>
								</div>
								<span>View public profile page</span></a>
							</li>
							<li><a href="javascript:void(0)" data-toggle="modal" data-target="#oneTimeDonationModal">
								<div class="icon_main">
									<div class="icon"><img src="{{ asset('irh_assets/images/donat.png') }}" alt=""></div>
								</div>
								<span>Donate to IRH</span></a>
							</li>
						</ul>

						
						@if(Auth::user()->roles[0]->name == 'admin' || Auth::user()->roles[0]->name == 'moderator')
						<h3>Admin Actions</h3>
						<ul class="acOverQuicAc">
							<li>
								<a href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.publishedResources.list') : route('user.publishedResources.list')}}">
									<div class="icon blue"><img src="{{ asset('irh_assets/images/copy.png') }}" alt=""></div>
								
								<span>View published resources</span></a>
							</li>
							@endif
							@if(Auth::user()->roles[0]->name == 'admin')
							<li>
								<a href="{{ route('admin.subscribers.list') }}"><div class="icon blue"><img src="{{ asset('irh_assets/images/subscrib.png') }}" alt=""></div>
								<span>View all subscriber</span></a>
							</li>
							<li>
								<a href="{{ route('admin.users.list') }}">
								<div class="icon blue"><img src="{{ asset('irh_assets/images/userIcon.png') }}" alt=""></div>
								<span>View all user</span></a>
							</li>
							@endif
							@if(Auth::user()->roles[0]->name == 'admin' || Auth::user()->roles[0]->name == 'moderator')
							<li>
								<a href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.flagedResources.list') : route('user.flagedResources.list') }}">
									<div class="icon blue"><img src="{{ asset('irh_assets/images/flag_a.png') }}" alt=""></div>
									<span>View flagged resources</span>
								</a>
							</li>
							<li>
								<a href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.pendingResources.list') : route('user.pendingResources.list') }}">
								<div class="icon blue"><img src="{{ asset('irh_assets/images/time.png') }}" alt=""></div>
								<span>View pendding resources</span></a>
							</li>
							<li>
								<a href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.view.reportedUsers.list') : route('user.view.reportedUsers.list') }}">
								<div class="icon blue"><img src="{{ asset('irh_assets/images/trangle.png') }}" alt=""></div>
								<span>View reported user</span></a>
							</li>
							@endif
							@if(Auth::user()->roles[0]->name == 'admin')
							<li>
								<a href="{{ route('admin.donations.list') }}">
								<div class="icon blue"><img src="{{ asset('irh_assets/images/donat.png') }}" alt=""></div>
								<span>View donors</span></a>
							</li>
							<li>
								<a href="{{ url('admin/users/get/admin') }}">
								<div class="icon blue"><img src="{{ asset('irh_assets/images/userIcon.png') }}" alt=""></div>
								<span>View Admins</span></a>
							</li>
							@endif
						</ul>

						<!-- <br clear="all"> -->
						@if(Auth::user()->roles[0]->name == 'admin')
						<ul class="acOverQuicAc">
							<li>
								<a href="{{ route('admin.headerImage.list') }}">
									<div class="icon green"><img src="{{ asset('irh_assets/images/picture.png') }}" alt=""></div>
								<span>Edit header images</span></a>
							</li>
							<li>
								<a href="{{ route('admin.privacy.form') }}">
								<div class="icon green"><img src="{{ asset('irh_assets/images/edit.png') }}" alt=""></div>
								<span>Edit polices</span></a>
							</li>
							<li>
								<a href="{{ route('admin.terms.form') }}">
								<div class="icon green"><img src="{{ asset('irh_assets/images/edit.png') }}" alt=""></div>
								<span>Edit Terms and Conditions</span></a>
							</li>
							<li>
								<a href="{{ route('admin.tagCategories.list') }}">
								<div class="icon green"><img src="{{ asset('irh_assets/images/colag.png') }}" alt=""></div>
								<span>Edit tags & categories</span></a>
							</li>
							<li>
								<a href="{{ route('admin.faq.list') }}">
								<div class="icon green"><img src="{{ asset('irh_assets/images/question.png') }}" alt=""></div>
								<span>Edit FAQs</span></a>
							</li>
							<li>
								<a href="{{ route('admin.testimonial.list') }}">
								<div class="icon green"><img src="{{ asset('irh_assets/images/testimonial.png') }}" alt=""></div>
								<span>Edit testimonial</span></a>
							</li>
						</ul>
						@endif
						<!-- <br clear="all"> -->
	               	</div>
				</div>
				



				
	            <div class="dasBoardPopup">
	            	@if(Auth::user()->roles[0]->name == 'admin')
	               		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
	                  		<div class="popupColupsPanel_top">
	                     		<button type="button" class="clsopnBtn"  data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
	                     			<span aria-hidden="true"><span aria-hidden="true">×</span></span>
						 		</button>
	                     		<div class="titlePanel">
	                        		<h3>Admins</h3>
	                        		<a class="actionBtn" href="{{ url('admin/users/get/moderator') }}">Add an admin</a>
	                     		</div>
					  		</div>
							<div class="popupColupsPanel_bot collapse show" id="collapseExample">
							<div class="dasboard_scrol_width">
							<div class="tab-content">
							<div class="tab-pane active">	
								@if(count($admins))
				                    <ul class="userList">
				                     	@foreach($admins as $admin)
		 		                        <li data-row-id="{{ $admin->id }}">
		 		                           <div class="iconMain">
		 		                              <div class="icon assColor"><img src="{{ asset('irh_assets/images/userNew.png') }}" alt=""></div>
		 		                           </div>
		 		                           <div class="user_details">{{ $admin->first_name . " " . $admin->last_name}}</div>
		 		                           <button class="closeBtn admin_close_btn"><img src="{{ asset('irh_assets/images/crossIcon.png') }}" alt=""></button>
		 		                        </li>
				                     	@endforeach
				                    </ul>
								@else
									<div class="defaultImgSection">
									 	<div class="defaultImgThum">
									 		<img src="https://projexin.com/isl/public/irh_assets/images/admins_default.png" alt="">
										</div>
									</div>
								@endif
							</div></div></div>
							</div>
	               		</div>
		               	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
		                  	<div class="popupColupsPanel_top">
		                     	<button type="button" class="clsopnBtn" data-toggle="collapse" data-target="#collapse2" aria-expanded="false">
		                     		<span aria-hidden="true">×</span>
		                     	</button>
		                     	<div class="titlePanel">
		                        	<h3>Moderators</h3>
		                        	<a class="actionBtn" href="{{ url('admin/users/get/user') }}">Add a moderator</a>
		                     	</div>
						  	</div>
						  	<div class="popupColupsPanel_bot collapse show" id="collapse2">
							<div class="dasboard_scrol_width">
							<div class="tab-content">
							<div class="tab-pane active">
						  		@if(count($moderators))
			                     	<ul class="userList">
				                        @foreach($moderators as $moderator)
		 		                        <li data-row-id="{{ $moderator->id }}">
		 		                           <div class="iconMain">
		 		                              <div class="icon assColor"><img src="{{ asset('irh_assets/images/userNew.png') }}" alt=""></div>
		 		                           </div>
		 		                           <div class="user_details">{{ $moderator->first_name . " " . $moderator->last_name}}</div>
		 		                           <button class="closeBtn mod_close_btn"><img src="{{ asset('irh_assets/images/crossIcon.png') }}" alt=""></button>
		 		                        </li>
				                     	@endforeach
			                     	</ul>
						  		@else
							  		<div class="defaultImgSection">
								 		<div class="defaultImgThum">
									 		<img src="https://projexin.com/isl/public/irh_assets/images/modaretor_default.png" alt="">
										</div>
										<p>Lorem Ipsum is simply dummy text</p>
									</div>
								  @endif
							</div></div></div> 
							</div>  
		               	</div>
		               	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
		                  	<div class="popupColupsPanel_top">
		                     	<button type="button" class="clsopnBtn" data-toggle="collapse" data-target="#collapse3" aria-expanded="false">
		                     		<span aria-hidden="true">×</span>
		                     	</button>
		                     	<div class="titlePanel">
		                        	<h3>Verified Users</h3>
		                        	<a class="actionBtn" href="{{ url('admin/users/get/unverified_user') }}">Verify user</a>
							 	</div>
							</div>
							<div class="popupColupsPanel_bot collapse show" id="collapse3">
							<div class="dasboard_scrol_width">
							<div class="tab-content">
							<div class="tab-pane active">
								@if(count($verifiedUsers))
			                     	<ul class="userList">
				                     	@foreach($verifiedUsers as $verifiedUser)
				                        <li data-row-id="{{ $verifiedUser->id }}">
			                           		<div class="iconMain">
				                              	<div class="icon assColor"><img src="{{ asset('irh_assets/images/userNew.png') }}" alt=""></div>
				                           	</div>
				                           	<div class="user_details">{{ $verifiedUser->first_name . " " . $verifiedUser->last_name}}</div>
				                           	<button class="closeBtn"><img src="{{ asset('irh_assets/images/crossIcon.png') }}" alt=""></button>
				                        </li>
				                        @endforeach
			                     	</ul>
		                     	@else
								 	<div class="defaultImgSection">
								 		<div class="defaultImgThum">
									 		<img src="https://projexin.com/isl/public/irh_assets/images/verifaiedUser_default.png" alt="">
										</div>
										<p>Lorem Ipsum is simply dummy text</p>
									</div>
								@endif
							</div></div></div>
		                  	</div>
		               	</div>
					   @endif
	               	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
						<div class="popupColupsPanel_top">
							<button type="button" class="clsopnBtn" data-toggle="collapse" data-target="#collapse4" aria-expanded="false">
							<span aria-hidden="true">×</span>
							</button>
							<div class="titlePanel">
								<h3>Notification Centre</h3>
								<a class="actionBtn" href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.all.notifications') : route('user.all.notifications') }}">Viell All</a>
							</div>
						</div>
						<div class="popupColupsPanel_bot collapse show" id="collapse4">
						<div class="dasboard_scrol_width">
						<div class="tab-content">
						<div class="tab-pane active">
							@if(count($userNotifications))
								<ul class="userList">
									@foreach($userNotifications as $userNotification)
									<li data-row-id="{{ $userNotification->id }}">
										<div class="user_details">{!! $userNotification->message !!}</div>
										<button class="closeBtn"><img src="{{ asset('irh_assets/images/crossIcon.png') }}" alt=""></button>
									</li>
									@endforeach
								</ul>
							@else
								<div class="defaultImgSection">
									<div class="defaultImgThum">
										<img src="https://projexin.com/isl/public/irh_assets/images/notification_default.png" alt="">
									</div>
									<p>Your notification will show here.</p>
								</div>
							@endif
						</div>
						</div>
						</div>
							
						</div>
					</div>
	               	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
						<div class="popupColupsPanel_top">
							<button type="button" class="clsopnBtn" data-toggle="collapse" data-target="#collapse5" aria-expanded="false">
							<span aria-hidden="true">×</span>
							</button>
							<div class="titlePanel">
								<h3>Download History</h3>
								<a class="actionBtn" href="{{ Auth::user()->roles[0]->name == 'admin' ?  route('admin.download.history.list') : route('user.download.history.list') }}">Viell All</a>
							</div>
						</div>
						<div class="popupColupsPanel_bot collapse show" id="collapse5">
						<div class="dasboard_scrol_width">
						<div class="tab-content">
						<div class="tab-pane active">
							@if(count($downloadHistory))
								<table class="table" id="resources-table">
									<tbody>
										@foreach($downloadHistory as $sDownloadHistory)
										<tr data-row-id="{{ $sDownloadHistory->dhid }}">
											<td><a href="{{ route('theme.singleresource',$sDownloadHistory->id) }}" target="_blank"><img src="{{ !blank($sDownloadHistory->preview_attachment) ? asset('irh_assets/uploads/resource_preview/' . $sDownloadHistory->preview_attachment) : asset('irh_assets/images/resource_thum.jpg') }}" width="142" height="88" alt=""/></a></td>
											<td>{!! str_limit($sDownloadHistory->title, 8) !!}</td>
											<td>{!! date('d-m-Y', strtotime($sDownloadHistory->ddate)) !!}</td>
											<td><a href="javascript:void(0)" class="link del-down-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
										</tr>
										@endforeach
									</tbody>
								</table>
							@else
								<div class="defaultImgSection">
									<div class="defaultImgThum">
										<img src="https://projexin.com/isl/public/irh_assets/images/download_default.png" alt="">
									</div>
									<p>Your history will show here.</p>
								</div>
							@endif
							</div></div></div>
						</div>
					</div>
	               	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
						<div class="popupColupsPanel_top">
							<button type="button" class="clsopnBtn" data-toggle="collapse" data-target="#collapse6" aria-expanded="false">
							<span aria-hidden="true">×</span>
							</button>
							<div class="titlePanel">
								<h3>My Resources</h3>
								<a class="actionBtn" href="{{ Auth::user()->roles[0]->name == 'admin' ?  route('admin.myResources.list') : route('user.myResources.list') }}">Viell All</a>
							</div>
						</div>
						<div class="popupColupsPanel_bot collapse show" id="collapse6">
						<div class="dasboard_scrol_width">
						<div class="tab-content">
						<div class="tab-pane active">
							@if(count($myResources))
								<table class="table" id="resources-table">
									<tbody>
										@foreach($myResources as $myResource)
										<tr data-row-id="{{ $myResource->id }}">
											<td><a href="{{ route('theme.singleresource',$myResource->id) }}" target="_blank"><img src="{{ !blank($myResource->preview_attachment) ? asset('irh_assets/uploads/resource_preview/' . $myResource->preview_attachment) : asset('irh_assets/images/resource_thum.jpg') }}" width="142" height="88" alt=""/></a></td>
											<td>{!! str_limit($myResource->title, 8) !!}</td>
											<td>{!! date('d-m-Y', strtotime($myResource->created_at)) !!}</td>
											<td><a href="{{ url((Auth::user()->roles[0]->name == 'admin' ? 'admin' : 'user') . '/resource/edit/' . $myResource->id) }}" class="link" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a><a href="javascript:void(0)" class="link del-my_res-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
										</tr>
										@endforeach
									</tbody>
								</table>
							@else
								<div class="defaultImgSection">
									<div class="defaultImgThum">
										<img src="https://projexin.com/isl/public/irh_assets/images/myResource_default.png" alt="">
									</div>
									<p>Your uploaded resources will show here. <span>Click here to upload a resourec</span></p>
								</div>
							@endif
						</div></div></div>
						</div>
					</div>
	               	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
						<div class="popupColupsPanel_top">
							<button type="button" class="clsopnBtn" data-toggle="collapse" data-target="#collapse7" aria-expanded="false">
							<span aria-hidden="true">×</span>
							</button>
							<div class="titlePanel">
								<h3>Viewing History</h3>
								<a class="actionBtn" href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.viewing.history.list') : route('user.viewing.history.list') }}">Viell All</a>
							</div>
						</div>
						<div class="popupColupsPanel_bot collapse show" id="collapse7">
						<div class="dasboard_scrol_width">
						<div class="tab-content">
						<div class="tab-pane active">
							@if(count($userResourceVisit))
								<table class="table" id="resources-table">
									<tbody>
										@foreach($userResourceVisit as $myResource)
										<tr data-row-id="{{ $myResource->rvid }}">
											<td><a href="{{ route('theme.singleresource',$myResource->id) }}" target="_blank"><img src="{{ !blank($myResource->preview_attachment) ? asset('irh_assets/uploads/resource_preview/' . $myResource->preview_attachment) : asset('irh_assets/images/resource_thum.jpg') }}" width="142" height="88" alt=""/></a></td>
											<td>{!! str_limit($myResource->title, 8) !!}</td>
											<td>{!! date('d-m-Y', strtotime($myResource->created_at)) !!}</td>
											<td>@if(!blank($myResource->resource_attachment))<a href="{{ url('/resource/'.  $myResource->id . '/download') }}" class="link" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>@endif<a href="javascript:void(0)" class="link del-visit_res-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
										</tr>
										@endforeach
									</tbody>
								</table>
							@else
								<div class="defaultImgSection">
									<div class="defaultImgThum">
										<img src="https://projexin.com/isl/public/irh_assets/images/history_default.png" alt="">
									</div>
									<p>Your history will show here.</p>
								</div>
							@endif
						</div></div></div>
						</div>
					</div>
	               	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
						<div class="popupColupsPanel_top">
							<button type="button" class="clsopnBtn" data-toggle="collapse" data-target="#collapse8" aria-expanded="false">
							<span aria-hidden="true">×</span>
							</button>
							<div class="titlePanel">
								<h3>Saved Resource</h3>
								<a class="actionBtn" href="{{ Auth::user()->roles[0]->name == 'admin' ?  route('admin.view.savedResource.list') : route('user.view.savedResource.list') }}">Viell All</a>
							</div>
						</div>
						<div class="popupColupsPanel_bot collapse show" id="collapse8">
						<div class="dasboard_scrol_width">
						<div class="tab-content">
						<div class="tab-pane active">

							@if(count($savedResource))
								<table class="table" id="resources-table">
									<tbody>
										@foreach($savedResource as $myResource)
										<tr data-row-id="{{ $myResource->id }}">
											<td><a href="{{ route('theme.singleresource',$myResource->id) }}" target="_blank"><img src="{{ !blank($myResource->preview_attachment) ? asset('irh_assets/uploads/resource_preview/' . $myResource->preview_attachment) : asset('irh_assets/images/resource_thum.jpg') }}" width="142" height="88" alt=""/></a></td>
											<td>{!! str_limit($myResource->title, 8) !!}</td>
											<td>{!! date('d-m-Y', strtotime($myResource->created_at)) !!}</td>
											<td>@if(!blank($myResource->resource_attachment))<a href="{{ url('/resource/'.  $myResource->id . '/download') }}" class="link" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>@endif<a href="javascript:void(0)" class="link del-saved_res-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
										</tr>
										@endforeach
									</tbody>
								</table>
							@else
								<div class="defaultImgSection">
									<div class="defaultImgThum">
										<img src="https://projexin.com/isl/public/irh_assets/images/save_default.png" alt="">
									</div>
									<p>Your resource will show here.</p>
								</div>
							@endif
							</div></div></div>
						</div>
					</div>
	               	@if(Auth::user()->roles[0]->name == 'admin' || Auth::user()->roles[0]->name == 'moderator')
	               	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
						<div class="popupColupsPanel_top">
							<button type="button" class="clsopnBtn" data-toggle="collapse" data-target="#collapse9" aria-expanded="false">
							<span aria-hidden="true">×</span>
							</button>
							<div class="titlePanel">
								<h3>Pending Resource</h3>
								<a class="actionBtn" href="{{ Auth::user()->roles[0]->name == 'admin' ?   route('admin.pendingResources.list') : route('user.pendingResources.list') }}">Viell All</a>
							</div>
						</div>
						<div class="popupColupsPanel_bot collapse show" id="collapse9">
						<div class="dasboard_scrol_width">
						<div class="tab-content">
						<div class="tab-pane active">
							@if(count($pendingResource))
								<table class="table" id="resources-table">
									<tbody>
										@foreach($pendingResource as $myResource)
										<tr data-row-id="{{ $myResource->id }}">
											<td><a href="{{ route('theme.singleresource',$myResource->id) }}" target="_blank"><img src="{{ !blank($myResource->preview_attachment) ? asset('irh_assets/uploads/resource_preview/' . $myResource->preview_attachment) : asset('irh_assets/images/resource_thum.jpg') }}" width="142" height="88" alt=""/></a></td>
											<td>{!! str_limit($myResource->title, 8) !!}</td>
											<td>{!! date('d-m-Y', strtotime($myResource->created_at)) !!}</td>
											<td><a href="javascript:void(0)" class="link approve-link"><i class="fa fa-check" aria-hidden="true"></i></a></td>
										</tr>
										@endforeach
									</tbody>
								</table>
							@else
								<div class="defaultImgSection">
									<div class="defaultImgThum">
										<img src="https://projexin.com/isl/public/irh_assets/images/pendingResource_default.png" alt="">
									</div>
									<p>Your pending resource will show here.</p>
								</div>
							@endif
							</div></div></div>
						</div>
					</div>
	               	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left">
						<div class="popupColupsPanel_top">
							<button type="button" class="clsopnBtn" data-toggle="collapse" data-target="#collapse10" aria-expanded="false">
							<span aria-hidden="true">×</span>
							</button>
							<div class="titlePanel">
								<h3>Flaged Resource</h3>
								<a class="actionBtn" href="{{ Auth::user()->roles[0]->name == 'admin' ? route('admin.flagedResources.list') : route('user.flagedResources.list') }}">Viell All</a>
							</div>
						</div>
						<div class="popupColupsPanel_bot collapse show" id="collapse10">
						<div class="dasboard_scrol_width">
						<div class="tab-content">
						<div class="tab-pane active">
							@if(count($flagedResources))
								<table class="table" id="resources-table">
									<tbody>
										@foreach($flagedResources as $myResource)
										<tr data-row-id="{{ $myResource->id }}">
											<td><a href="{{ route('theme.singleresource',$myResource->id) }}" target="_blank"><img src="{{ !blank($myResource->preview_attachment) ? asset('irh_assets/uploads/resource_preview/' . $myResource->preview_attachment) : asset('irh_assets/images/resource_thum.jpg') }}" width="142" height="88" alt=""/></a></td>
											<td>{!! str_limit($myResource->title, 8) !!}</td>
											<td>{!! $myResource->flagsUsername !!}</td>
											<td>
												<a href="javascript:void(0)" class="link remove-flag"><i class="fa fa-check-circle" aria-hidden="true"></i></a> 
												<a href="javascript:void(0)" class="link admin-notice"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a> 
												<a href="javascript:void(0)" class="link del-flag-res-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							@else
								<div class="defaultImgSection">
									<div class="defaultImgThum">
										<img src="https://projexin.com/isl/public/irh_assets/images/flagedResource_default.png" alt="">
									</div>
									<p>Your flaged resource will show here.</p>
								</div>
							@endif
						</div></div></div>
						</div>
					</div>
	               	@endif
	            </div>
	         </div>
	      </div>
   </section>
</main>

<div class="modal fade" id="oneTimeDonationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Give Now

					<span>It was narrated from Abu Hurairah (r.a) that the Messenger of Allah said: "Allah said: 'Spend, Oson of Adam, and i shall spend on you.</span>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ route('theme.supportus.donationonetime') }}" method="post">
				@csrf

				<div class="form_left">
					<div class="formFieldBorder">
						<i class="number">1</i>
						<div class="col-sm-6 float-left">
							<label>First Name</label>
							<input type="text" name="fname"/>
						</div>
						<div class="col-sm-6 float-left">
							<label>Last Name</label>
							<input type="text" name="lname"/>
						</div>
						<div class="col-sm-12 float-left">
							<label>Email Address</label>
							<input type="email" name="email"/>
						</div>
					</div>
					<div class="formFieldBorder">
						<i class="number">3</i>
						<div class="col-sm-12 float-left">
							<label>Select Payment Processor</label>
							<div class="form-check form-check-inline form-checkCus">
								<input class="form-check-input" type="radio" name="otd_gateway_sel" id="otd_paypal_radio" value="paypal">
								<label class="form-check-label" for="otd_paypal_radio">
								<img src="{{ asset('irh_assets/images/paypal.png') }}" alt="">
								</label>
							</div>
							<div class="form-check form-check-inline form-checkCus">
								<input class="form-check-input" type="radio" name="otd_gateway_sel" id="otd_stripe_radio" value="stripe" checked>
								<label class="form-check-label" for="otd_stripe_radio">
									<img src="{{ asset('irh_assets/images/byCard.png') }}" alt="">
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="form_right">
					<div class="formFieldBorder">
						<i class="number">2</i>
						<div class="col-sm-12">
							<label>Select A Giving Level</label>
							<ul class="level">
								@foreach(explode(",",App\Option::getOption('OTD_AMT')) as $amt)
									<li>
										<a href="javascript:void(0)" class="otd" data-amt="{{ $amt }}">€ {{ $amt }}</a>
									</li>
								@endforeach
							</ul>
							<label>Or Enter A Specific Amount</label>
							<input type="text" name="amount" class="specific" id="otd_amount" />
						</div>
					</div>
					<div class="btnSec">
    					<script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
						            data-key="{{ App\Option::getOption('STRIPE_PK_KEY') }}"
						            data-name="Islamic Resource Hub"
									data-description="OneTime Donation"
									data-locale="auto"
									data-label="Pay"
									data-currency="GBP">
						          	></script>
					</div>
				</div>
				</form>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>


<div class="modal fade" id="following_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Followings
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table">
					@forelse($following as $sFollwing)
						<tr data-row-id="{{ $sFollwing->id }}">
							<td>{{ $sFollwing->username }}</td>
							<td><a href="javascript:void(0)" class="rem_following"><i class="fa fa-remove" style="font-size:24px; color: red;"></i></a></td>
						</tr>
					@empty
						<p>No following!</p>
					@endforelse
				</table>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>

<div class="modal fade" id="followers_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Followers
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table">
					@forelse($follower as $sFollwer)
						<tr data-row-id="{{ $sFollwer->id }}">
							<td>{{ $sFollwer->username }}</td>
						</tr>
					@empty
						<p>No follower!</p>
					@endforelse
				</table>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>


@endsection
@section('page_script')
	<script>
	  	AOS.init({
			easing: 'ease-in-out-sine'
	  	});


	  	$(".rem_following").click(function(){
	  		var eThis = $(this);
	  		$.ajax({
	  			url:"{{ url((Auth::user()->roles[0]->name == 'admin' ? 'admin' : 'user') . '/remove-following/') }}" + "/" + eThis.closest('tr').attr('data-row-id'),
	  			method:"GET",
	  			success:function(data){
	  				eThis.closest('tr').remove();
	  			}
	  		});
	  	});


	  	$(".del-my_res-link").click(function(){
	  		var eThis = $(this);
	  		$.ajax({
	  			url:"{{ url( (Auth::user()->roles[0]->name == 'admin' ? 'admin' : 'user') . '/my-resources/del') }}" + "/" + eThis.closest('tr').attr('data-row-id'),
	  			method:"GET",
	  			success:function(data){
	  				eThis.closest('tr').remove();
	  			}
	  		});
	  	});


	  	$(".del-saved_res-link").click(function(){
	  		var eThis = $(this);
	  		$.ajax({
	  			url:"{{ url( (Auth::user()->roles[0]->name == 'admin' ? 'admin' : 'user') . '/saved-resource/del') }}" + "/" + eThis.closest('tr').attr('data-row-id'),
	  			method:"GET",
	  			success:function(data){
	  				eThis.closest('tr').remove();
	  			}
	  		});
	  	});

	  	$(".del-visit_res-link").click(function(){
	  		var eThis = $(this);
	  		$.ajax({
	  			url:"{{ url( (Auth::user()->roles[0]->name == 'admin' ? 'admin' : 'user') . '/viewing-history/del') }}" + "/" + eThis.closest('tr').attr('data-row-id'),
	  			method:"GET",
	  			success:function(data){
	  				eThis.closest('tr').remove();
	  			}
	  		});
	  	});

	  	$(".del-down-link").click(function(){
	  		var eThis = $(this);
	  		$.ajax({
	  			url:"{{ url( (Auth::user()->roles[0]->name == 'admin' ? 'admin' : 'user') . '/download-history/del') }}" + "/" + eThis.closest('tr').attr('data-row-id'),
	  			method:"GET",
	  			success:function(data){
	  				eThis.closest('tr').remove();
	  			}
	  		});
	  	});


	  	$(document).on("click",".approve-link", function(){
			var eThis = $(this);
			$.ajax({
				url: ("{{ url( (Auth::user()->roles[0]->name == 'admin' ? 'admin' : 'user') . '/pending-resource/app') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
				method: "GET",
				success:function(msg){
				   	alert(msg.data);
				   	window.location.reload();
					//eThis.closest('tr').remove();
					//var tbodyLength = eThis.closest('tbody').find('tr').length;
					//console.log(tbodyLength);
				},
				error:function(jqXhr){
				   /*var errors = jqXhr.responseJSON;
				   $.each( errors.errors , function( key, value ) {
				       alert(value);
				       return false;
				   });*/
				 }
			});
		});



		$(document).on("click", ".del-flag-res-link", function(){
         	var eThis = $(this);
         	$.ajax({
	            url: ("{{ url((Auth::user()->roles[0]->name == 'admin' ? 'admin' : 'user') . '/published-resource/del') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
	            method: "GET",
	            success:function(msg){
	               console.log(msg);
	               //window.location.reload();
	               eThis.closest('tr').remove();
	               //var tbodyLength = eThis.closest('tbody').find('tr').length;
	               //console.log(tbodyLength);
	            },
	            error:function(jqXhr){
	               /*var errors = jqXhr.responseJSON;
	               $.each( errors.errors , function( key, value ) {
	                   alert(value);
	                   return false;
	               });*/
	            }
         	});
      	});


      	$(document).on("click", ".admin-notice", function(){
			var eThis = $(this);
			$.ajax({
	            url: ("{{ url((Auth::user()->roles[0]->name == 'admin' ? 'admin' : 'user') . '/flaged-resource/notice') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
	            method: "GET",
	            success:function(msg){
	               /*if(msg.prev == '1'){
	                  eThis.find('.fa').removeClass('fa-star').addClass('fa-star-o');
	               }
	               else{
	                  eThis.find('.fa').removeClass('fa-star-o').addClass('fa-star');
	               }*/
	               alert("Notice sent successfuly!");
	            },
	            error:function(jqXhr){
	               alert(jqXhr);
	               return false;
	            }
         	});
      	});


      	$(document).on('click', '.remove-flag', function(){
         	var eThis = $(this);
         	$.ajax({
	            url: ("{{ url((Auth::user()->roles[0]->name == 'admin' ? 'admin' : 'user') .'/flaged-resource/rem-flag/') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
	            method: "GET",
	            success:function(msg){
	               alert('Resource deleted!');
	               eThis.closest('tr').remove();
	            },
	            error:function(jqXhr){
	               alert(jqXhr);
	               return false;
	            }
         	});
      	});

      	$(document).on('click', '.admin_close_btn', function(){
         	var eThis = $(this);
         	$.ajax({
	            url: ("{{ url('/admin/user/u_setad') }}" + "/" + $(this).closest('li').attr('data-row-id')),
	            method: "GET",
	            success:function(msg){
	               console.log(msg);
	               eThis.closest('li').remove();
	            },
	            error:function(jqXhr){
	               var errors = jqXhr.responseJSON;
	               $.each( errors.errors , function( key, value ) {
	                   alert(value);
	                   return false;
	               });
	            }
         	});
      	});

      	$(document).on('click', '.mod_close_btn', function(){
         	var eThis = $(this);

         	$.ajax({
	            url: ("{{ url('/admin/user/u_setm') }}" + "/" + $(this).closest('li').attr('data-row-id')),
	            method: "GET",
	            success:function(msg){
	               console.log(msg);
	               eThis.closest('li').remove();
	            },
	            error:function(jqXhr){
	               var errors = jqXhr.responseJSON;
	               $.each( errors.errors , function( key, value ) {
	                   alert(value);
	                   return false;
	               });
	            }
	        });
		  });
		  
		  (function($){
			$(window).on("load",function(){
				
				
				$(".tab-pane").mCustomScrollbar({
					setHeight:280,
					theme:"inset-2-dark"
				});
				
			});
		})(jQuery);
	  	
	</script>
@endsection