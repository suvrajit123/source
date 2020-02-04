@extends('layouts.app')
@section('page_styles')
<link rel="stylesheet" href="{{ asset('irh_assets/vendor/summernote/summernote-lite.css') }}">
<style type="text/css">
	#single-resource-header
	{
		margin-top: 0%;
	}
	@media screen and (max-width: 767px)
	{
		#single-resource-header
		{
			margin-top: 0;
		}
	}
</style>



@endsection
@section('content')
<header id="single-resource-header" class="">
	<div class="resource_single_header_blue p-5">
		<div class="resource_single_header">
			<div class="col-md-12 text-left">
				<h3 class="text-white mb-0 resource_title">{{ ucwords($resource->title) }}</h3>
				<h5 class="text-white mb-0 userName"><a href="{{ route('theme.resources.authorprofile',$resource->user->id) }}" style="color: #fff;">{{ ucwords($resource->user->full_name) }}</a></h5>
				<div class="py-2">
					{!! $resource->commulativeRating($resource->reviews->count()) !!}
					<span class="pl-2">{{ $resource->reviews->count() }} {{ str_plural('Review',$resource->reviews->count()) }}</span>
				</div>
				<div class="py-2" style="padding: 0px !important; font-size: 12px;">
					<span>Published On {{ date("d.m.Y", strtotime($resource->updated_at)) }}</span>
				</div>
<!--
				<div class="py-2">
					<div class="float-left">

						<h5 class="text-white mb-0 mt-2 resource-publish-date">Published on {{ date('d-M-Y',strtotime($resource->created_at)) }}</h5>
					</div>
					<div class="float-right">
						<span class="px-2"><a href="" target="_blank" class="text-white"><i class="fab fa-instagram"></i></a></span>
						<span class="px-2"><a href="https://www.facebook.com/sharer/sharer.php?u={{ \Request::url() }}" target="_blank" class="text-white"><i class="fab fa-facebook"></i></a></span>

						<span class="px-2"><a href="whatsapp://send?text=IslamicResourceHub" data-action="share/whatsapp/share"  target="_blank" class="text-white"><i class="fab fa-whatsapp"></i></a></span>
						<span class="px-2"><a href="https://twitter.com/home?status={{ \Request::url() }}" target="_blank" class="text-white"><i class="fab fa-twitter"></i></a></span>
					</div>
				</div>
-->
			</div>
<!--
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<a href="{{ route('theme.downloadresource',$resource) }}" onclick="return showSupportPopup();" class="btn bg-yellow btn-block"><i class="fas fa-download"></i> Download</a>
						<button class="btn bg-yellow btn-block openPopup"><i class="fas fa-download"></i> Download</button>

					</div>
					<div class="card-footer">
						<div class="my-2" style="display: grid;">
							<div style="grid-column: 1;" id="saveResourceContainer">
								@auth
								@if(!$resource->isResourceSavedByLoggedInUser())
								<a href="javascript:void(0);" onclick='saveResource("{{ $resource->id }}",true);'>
									<img src="{{ asset('irh_assets/images/singlesave.png') }}" alt="" width="30px"> <span class="text-muted pl-3">Save for later</span>
								</a>
								@else
								<img src="{{ asset('irh_assets/images/savedlogo.png') }}" alt="" width="30px"> Saved
								@endif
								@endauth

							</div>
							<div style="grid-column: 2;" id="likeContainer">
								@auth
								@if(!$resource->isResourceLikedByLoggedInUser())
								<a href="javascript:void(0);" class="btn bg-yellow" onclick='likeResource("{{ $resource->id }}");'><i class="far fa-thumbs-up"></i> Like</a>
								@else
								<a href="javascript:void(0);" class="btn bg-yellow"><i class="fas fa-thumbs-up"></i> Liked</a>
								@endif
								@endauth
							</div>
						</div>
					</div>
				</div>
			</div>
-->
		</div>
	</div>
</header>
<section id="single-resource" class="resource_single resource_singleNew">
	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 float-left">
		@if(Session::has('success'))
		<div class="alert alert-success">
			{{ Session::get('success') }}
		</div>
		@endif
			<h5 class="resource-section-heading">RESOURCE DESCRIPTION</h5>
			<p>{!! $resource->description !!}</p>

		<div class="files py-4 previewFile">
			<h5 class="resource-section-heading">PREVIEW FILES INCLUDED</h5>
			<ul class="review">
				@if(!blank($resource->resource_attachment))
					<li>
					<figure class="figure">
						<a href="{{ route('theme.downloadresource',$resource) }}">
							<img src="{{ $resource->cover_attachment_path }}" alt="" class="img-thumbnail" width="200px" height="200px"></a>
						 <figcaption class="figure-caption ml-3">
							 <a href="{{ route('theme.downloadresource',$resource) }}">{!! $resource->resource_attachment !!}</a></figcaption>
					</figure></li>
					<li>
				@endif
			</ul>
		</div>
		@if(!blank($resource->embed_link))
		<hr>
		<div class="embed py-4">
			<h4 class="heading">Embeded Video:</h4>
			<div id="embed_container" class="embed-responsive embed-responsive-16by9">
				{{-- From JS --}}
			</div>
		</div>
		@endif
		<div class="reviewMain reviewMainSingle">
			<h5 class="resource-section-heading">REVIEWS</h5>
			@auth
				@if(!$resource->loggedInUserHasReview())
				<a href="#" data-toggle="modal" data-target="#addReviewModal" class="btn bg-yellow btn-sm">Add a Review</a>
				@endif
			@endauth
			</h4>
			<div class="reviews">
				@foreach($resource->reviews as $rv)
					<div class="review py-4 reviewLoop">
						<div class="reviewThum">
							<div class="icon flag"><img src="{!! blank($resource->user->profile_picture) ? asset('irh_assets/images/user_icon.png') : asset('irh_assets/uploads/profile_pictures/' . $resource->user->profile_picture) !!}" alt="" class="img-circle" height="64" width="64">
							</div>
						</div>
						<div class="reviewDescrip">
							<h3 class="title">{{ $rv->user->full_name }}</h3>
							<div class="rating">
								<span>{!! $rv->resourceStarsRatings() !!}</span>
								@if($rv->status == 1)
									@if($rv->user_id == Auth::id())
										&nbsp;&nbsp;<a href="javascript:void(0);" data-toggle="modal" data-target="#editReviewModal{{ $rv->id }}" class="text-muted"><i class="fa fa-pencil"></i></a>
									@endif
									@if(Auth::check() && (Auth::user()->roles[0]->name == 'admin' || Auth::user()->roles[0]->name == 'moderator'))
										<a href="{{ route('theme.deletereviewfromresource',$rv) }}" class="text-muted ml-2" onclick="return confirm('Are you sure you want to delete this?');"><i class="fa fa-times"></i></a>
									@endif
									</h6>
									<p>{{ $rv->review }}</p>
								@else
									</h6>
									<p><em>This review has been removed by a moderator.</em></p>
								@endif
							</div>
						</div>
					</div>
					<div class="modal fade" id="editReviewModal{{ $rv->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Edit Review</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<form action="{{ route('theme.updatereviewfromresource',$rv) }}" method="POST">
										@csrf
										<div class="form-group">
											<label for="">Review</label>
											<textarea name="review" rows="2" class="form-control" placeholder="Review ..">{{ $rv->review }}</textarea>
										</div>
										<div class="form-group">
											<label for="">Rating</label>
											<select name="stars" id="" class="form-control">
												<option value="1" {{ ($rv->stars == 1)?'selected':'' }}>1 Star</option>
												<option value="2" {{ ($rv->stars == 2)?'selected':'' }}>2 Star</option>
												<option value="3" {{ ($rv->stars == 3)?'selected':'' }}>3 Star</option>
												<option value="4" {{ ($rv->stars == 4)?'selected':'' }}>4 Star</option>
												<option value="5" {{ ($rv->stars == 5)?'selected':'' }}>5 Star</option>
											</select>
										</div>
										<div class="form-group">
											<input type="submit" class="btn bg-yellow" value="Update Review">
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>

	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left">
		<div class="resource_card_first newBoxShadow">
		   <div class="card-body">
		      	<div class="socialMedia">
			        <h3>
			        Quick Action</h5>
			        <ul>
			            <li>
			               <a href="#" data-toggle="modal" data-target="#downloadPopup">
			                  <div class="icon" id="openPopup"><img src="{{ asset('irh_assets/images/down_a.png') }}" alt=""></div>
			               </a>
			               <span>Download</span>
			            </li>
			            <li>
			               <div class="icon green" @if(Auth::check())
			               onclick='likeResource("{{ $resource->id }}");'
			               @endif><img src="{{ asset('irh_assets/images/like_a.png') }}" alt="">
			      			</div>
			      			<span>Like</span></li>
		      			<li>
							<div class="icon blue"
							@if(Auth::check())
							@if(!$resource->isResourceSavedByLoggedInUser())
							onclick='saveResource("{{ $resource->id }}",false)';
							@endif
							@endauth
		      				><img src="{{ asset('irh_assets/images/book_a.png') }}" alt="">
						   </div>
						   <span>Bookmark</span>
					   	</li>
			   			<li>
			   				<div class="icon share" data-toggle="modal" data-target="#spreadWordModal"><img src="{{ asset('irh_assets/images/share_a.png') }}" alt=""></div>
						   	<span>Share</span>
					   	</li>
					   	<li>
						   	<div class="icon flag"
								@if(Auth::check())
								data-toggle="modal" data-target="#flagResourceModal"
								@endif
								>
								<img src="{{ asset('irh_assets/images/flag_a.png') }}" alt="">
							</div>
							<span>Report</span>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="resource_card newBoxShadow">
			<div class="card-body">
				<div class="userDetailsBox">
					<h3>Follow The Author</h5>
					<div class="userThum">
						<div class="icon flag">
							<a href="{{ route('theme.resources.authorprofile', $resource->user->id) }}">
							<img src="{!! blank($resource->user->profile_picture) ? asset('irh_assets/images/user_icon.png') : asset('irh_assets/uploads/profile_pictures/' . $resource->user->profile_picture) !!}" alt="" class="img-circle" height="64" width="64">
							</a>
						</div>
						<span>{{ ucwords($resource->user->full_name) }}</span>
					</div>
				</div>
			</div>
		</div>

		<div class="resource_card newBoxShadow">
			<div class="card-body">
				<div class="copyrightBox">
				<h3>Copyright Licence</h5>
				<p>{!! $resource->license_type !!}</p>

				<h3>Tag</h5>
				<ul class="tag">
					@foreach($resource->tags as $tag)
						<li>
							<a href="{{ route('theme.resourcesbytag',$tag) }}">
								{{ $tag->name }}</a>
						</li>
					@endforeach
				</ul>

				<h3>Category</h5>
				<ul class="cata">
					<li><a href="{{ route('theme.resourcesbycategory',$resource->category->id) }}"><img src="{{ asset('irh_assets/images/cata.png') }}" alt=""></a>
						<span>{!! $resource->category->title !!}</span></li>
				</ul>


				<ul class="view_link">
					<li>
						<small class="icon_cl"><img src="{{ asset('irh_assets/images/view_i.png') }}" alt="" width="18px"></small>  <span> View</span><span class="sml">{!! $resource->views !!}</span>
					</li>
					<li>
						<small class="icon_cl"><img src="{{ asset('irh_assets/images/like_i.png') }}" alt="" width="18px"></small><span> Lile</span><span class="sml">{!! $resource->likes->count() !!}</span>
					</li>
					<li>
						<small class="icon_cl"><img src="{{ asset('irh_assets/images/down_i.png') }}" alt="" width="18px"></small> <span> Download</span> <span class="sml">{!! $resource->downloads !!}</span>
					</li>
				</ul>

				<br clear="all">
				</div>
			</div>
		</div>
	</div>
	<br clear="all">

	<br clear="all">
</section>

<div class="container resource_single_list">
	<div class="">
	<div class=""><h5 class="resource-section-heading">Other RESOURCES By [{{ ucwords($resource->user->full_name) }}]</h5></div>
		<div class="relatedResources py-4 text-center">
			<div class="">
				<div class="row">
					@forelse($resources as $singleRresource)
					<div class="col-md-3 col-sm-6 mb-4">
						<div class="resourcebox">
							<div class="card">
								<div class="thum_resource_wrap">
								<a href="{{ route('theme.singleresource',$singleRresource->id) }}" class="thum_resource">
								<h3><span>{!! str_limit($singleRresource->title, 12) !!}</span></h3>
								<img class="card-img-top" src="{{ $singleRresource->preview_attachment_path }}" alt="Card image cap" style="position: relative;"></a>

								 <span class="proTagSave" id="saveResourceContainer_{{ $singleRresource->id }}">
								  	@auth
								  	@if(!$singleRresource->isResourceSavedByLoggedInUser())
								  	<a href="javascript:void(0);"  onclick='saveResource("{{ $singleRresource->id }}",false);'>
								  	<img src="{{ asset('irh_assets/images/savelogo.png') }}" alt="" width="25px" data-toggle="tooltip" data-placement="top" title="save for later">
								  	</a>
								  	@else
								  	<img src="{{ asset('irh_assets/images/savedlogo.png') }}" alt="" width="25px">
								  	@endif
								  	@endauth
								  </span>
									</div>
								<div class="card-body">
									<div class="pb-4 author_profile"><img src="{{ asset('irh_assets/images/avatar.png') }}" alt="" width="30px" class="rounded-circle"><span class="ml-3 author_name">{{ $singleRresource->user->full_name }}</span></div>
									<a href="{{ route('theme.singleresource',$singleRresource->id) }}" class="text-muted"><h5 class="card-title">{{ $singleRresource->title }}</h5></a>
								</div>
								<div class="card-footer">
									<div style="display: grid;">
										<ul>
											<li>
												<small class="icon_cl"><img src="{{ asset('irh_assets/images/view_i.png') }}" alt="" width="18px"></small><span>{{ $singleRresource->views }}</span>
											</li>
											<li>
												<small class="icon_cl"><img src="{{ asset('irh_assets/images/like_i.png') }}" alt="" width="18px"></small><span>{{ $singleRresource->likes->count() }}</span>
											</li>
											<li>
												<small class="icon_cl"><img src="{{ asset('irh_assets/images/down_i.png') }}" alt="" width="18px"></small><span>{{ $singleRresource->downloads }}</span>
											</li>
										</ul>

									</div>
								</div>
							</div>
						</div>
					</div>
					@empty
					<div class="col-md-4 offset-md-4">
						<h3>No Resource Found</h3>
					</div>
					@endforelse
				</div>
			</div>
		</div>
	</div>
	<div class="">
	<div class=""><h5 class="resource-section-heading">RELATED RESOURCES</h5></div>
		<div class="relatedResources py-4 text-center">
			<div class="">
				<div class="row">
					@forelse($related as $rel)
					<div class="col-md-3 col-sm-6 mb-4">
						<div class="resourcebox">
							<div class="card">
								<div class="thum_resource_wrap">
									<a href="{{ route('theme.singleresource',$rel) }}" class="thum_resource">
									<h3><span>{!! str_limit($rel->title, 12) !!}</span></h3>
									<img class="card-img-top" src="{{ $rel->preview_attachment_path }}" alt="Card image cap" style="position: relative;"></a>

								 	<span class="proTagSave" id="saveResourceContainer_{{ $resource->id }}">
								  	@auth
								  	@if(!$resource->isResourceSavedByLoggedInUser())
								  		<a href="javascript:void(0);"  onclick='saveResource("{{ $resource->id }}",false);'>
								  		<img src="{{ asset('irh_assets/images/savelogo.png') }}" alt="" width="25px" data-toggle="tooltip" data-placement="top" title="save for later">
								  		</a>
								  	@else
								  		<img src="{{ asset('irh_assets/images/savedlogo.png') }}" alt="" width="25px">
								  	@endif
								  	@endauth
								  	</span>
								</div>
								<div class="card-body">
									<div class="pb-4 author_profile">
										<img src="{{ asset('irh_assets/images/avatar.png') }}" alt="" width="30px" class="rounded-circle"><span class="ml-3 author_name">{{ $rel->user->full_name }}</span>
									</div>
									<a href="{{ route('theme.singleresource',$rel) }}" class="text-muted"><h5 class="card-title">{{ $rel->title }}</h5></a>
								</div>
								<div class="card-footer">
										<ul>
											<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/view_i.png') }}" alt="" width="18px"></small><span>{{ $rel->views }}</span></li>
											<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/like_i.png') }}" alt="" width="18px"></small><span>{{ $rel->likes->count() }}</span></li>
											<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/down_i.png') }}" alt="" width="18px"></small><span>{{ $rel->downloads }}</span></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					
					@empty
					<div class="col-md-4 offset-md-4">
						<h3>No Related Resource</h3>
					</div>
					@endforelse
				</div>
				</div>
			</div>
		</div>
	
</div>

<br clear="all">

<div class="modal fade" id="downloadPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	   	<div class="modal-content">
	      	<div class="popupSld">
		        <div class="modal-header">
		            <h5 class="modal-title" id="exampleModalLabel">Like what you see?
		               <span>Donate and help sustain IRH</span>
		            </h5>
		            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		            <span aria-hidden="true">&times;</span>
		            </button>
		        </div>
		        <div class="modal-body">
		            <div class="popupText">
		               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
		               <p>Lorem Ipsum is <br>
		                  simply dummy text.
		               </p>
		               <div class="btnSec">
		                  <button type="button" class="nextBtnDonat">Donate</button>
		               </div>
		            </div>
		        </div>
		        <div class="modal-footer">

		        </div>
	    	</div>
	      	<div class="popupSld">
	         	<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Monthly Donation
					<span>The Prophet said, "The most beloved of deeds to Allah are those the are most consistent, even if it is small".</span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
	         	</div>
				<div class="modal-body">
					<form action="{{ route('theme.supportus.donationmonthly') }}" method="post">
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
</div>


<div class="modal fade" id="oneTimeDonationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

		</div>
	</div>
</div>






<div class="modal fade" id="addReviewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add a Review</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ route('theme.addreviewtoresource') }}" method="POST">
					@csrf
					<div class="form-group">
						<label for="">Review</label>
						<textarea name="review" rows="2" class="form-control" placeholder="Review .."></textarea>
					</div>
					<div class="form-group">
						<label for="">Rating</label>
						<select name="stars" id="" class="form-control">
							<option value="1">1 Star</option>
							<option value="2">2 Star</option>
							<option value="3">3 Star</option>
							<option value="4">4 Star</option>
							<option value="5">5 Star</option>
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" name="resource_id" value="{{ $resource->id }}">
						<input type="submit" class="btn bg-yellow" value="Add Review">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="flagResourceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Flag this Resource</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ route('theme.flagresource') }}" method="POST">
					@csrf
					<div class="form-group">
						<label for="">Reason</label>
						<textarea name="reason" rows="2" class="form-control" placeholder="Explain your problem .."></textarea>
					</div>
					<div class="form-group">
						<input type="hidden" name="resource_id" value="{{ $resource->id }}">
						<input type="submit" class="btn bg-yellow" value="Report">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="spreadWordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Spread the word
					<span>Give us a shout out on your social media!</span>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="social-icons">
					<li>
					   <a href="#" target="_blank">
					   <img src="{{ asset('irh_assets/images/email.png') }}" alt="">
					   </a>
					</li>
					<li>
					   <a href="{{ App\Option::getOption('SOCIAL_YT') }}" target="_blank">
					   <img src="{{ asset('irh_assets/images/you_icon.png') }}" alt="">
					   </a>
					   </span>
					<li>
					   <a href="#" target="_blank">
					   <img src="{{ asset('irh_assets/images/gree_icon.png') }}" alt="">
					   </a>
					</li>
					<li>
					   <a href="{{ App\Option::getOption('SOCIAL_TT_SH') . urlencode(Request::fullUrl()) }}" target="_blank">
					   <img src="{{ asset('irh_assets/images/twit_icon.png') }}" alt="">
					   </a>
					</li>
					<li>
					   <a href="{{ App\Option::getOption('SOCIAL_FB_SH') . urlencode(Request::fullUrl()) }}" target="_blank">
					   <img src="{{ asset('irh_assets/images/fb_icon.png') }}" alt="">
					   </a>
					</li>
					<li>
					   <a href="{{ App\Option::getOption('SOCIAL_INS') }}" target="_blank">
					   <img src="{{ asset('irh_assets/images/insta_icon.png') }}" alt="">
					   </a>
					</li>
					<li>
					   <a href="https://www.whatsup.com" target="_blank">
					   <img src="{{ asset('irh_assets/images/whatsUp.png') }}" alt="">
					   </a>
					</li>
				</ul>
			<br clear="all">
			</div>
			<div class="modal-footer">
				<!--        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
			</div>
		</div>
	</div>
</div>
@stop
@section('page_scripts')

<script src="{{ asset('irh_assets/vendor/summernote/summernote-lite.js') }}"></script>
<script>

	$(document).ready(function(){
		$('.summernote').summernote();
	});
	function likeResource(res)
	{
		$.ajax({
	 	  	headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			method: 'POST',
			url: "{{ route('theme.likeresource.ajax') }}",
			data: {resource:res},
	        success: function (data){
				if(data.success)
				{
					var html = '<a href="javascript:void(0);" class="btn bg-yellow"><i class="fas fa-thumbs-up"></i> Liked</a>';
						$('#likeContainer').html(html);
				}
	        }
	 	});
	}

	function getId(url) {
	    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
	    var match = url.match(regExp);

	    if (match && match[2].length == 11) {
	        return match[2];
	    } else {
	        return 'error';
	    }
	}

	var videoId = getId("{{ $resource->embed_link }}");

	var iframeMarkup = '<iframe  class="embed-responsive-item" width="560" height="315" src="//www.youtube.com/embed/'
	    + videoId + '" frameborder="0" allowfullscreen></iframe>';
	$('#embed_container').html(iframeMarkup);



	$("#downloadPopup").on('hide.bs.modal', function(){
	    window.location = "{{ route('theme.downloadresource',$resource) }}";
  	});
  	$('#downloadPopup').on('show.bs.modal', function() {
  		$("#downloadPopup").find(".modal-dialog").find(".modal-content").find(".popupSld").eq(0).css("display", "block");
  		$("#downloadPopup").find(".modal-dialog").find(".modal-content").find(".popupSld").eq(1).css("display", "none");
  	});

  	$(".nextBtnDonat").click(function(){
  		$("#downloadPopup").find(".modal-dialog").find(".modal-content").find(".popupSld").eq(0).css("display", "none");
  		$("#downloadPopup").find(".modal-dialog").find(".modal-content").find(".popupSld").eq(1).css("display", "block");
  	});

  	$(".otd").click(function(){
		$("#otd_amount").val($(this).attr("data-amt"));
	});
</script>
@endsection
