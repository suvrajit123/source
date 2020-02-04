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
	<div class="container bg-blue p-5">
		<div class="row">
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
			</div>
		</div>
	</div>
</header>
<section id="single-resource" class="resource_single">
	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 float-left">
		@if(Session::has('success'))
		<div class="alert alert-success">
			{{ Session::get('success') }}
		</div>
		@endif
		<div class="description">
			<h5 class="resource-section-heading">RESOURCE DESCRIPTION</h5>
			<p>{!! $resource->description !!}</p>
		</div>

		<div class="files py-4">
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
	</div>

	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left">
		<div class="resource_card_first">
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

		<div class="resource_card">
			<div class="card-body">
				<div class="userDetailsBox">
					<h3>Follow The Author</h5>
					<div class="userThum">
						<div class="icon flag">
							<a href="{{ route('theme.resources.authorprofile', $resource->user->id) }}">
							<img src="{!! blank($resource->user->profile_picture) ? asset('irh_assets/images/user_icon.png') : asset('irh_assets/uploads/' . $resource->user->profile_picture) !!}" alt="">
							</a>
						</div>
						<span>{{ ucwords($resource->user->full_name) }}</span>
					</div>
				</div>
			</div>
		</div>

		<div class="resource_card">
			<div class="card-body">
				<div class="copyrightBox">
				<h3>Copyright License</h5>
				<p>[{!! $resource->license_type !!}]</p>

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
@stop