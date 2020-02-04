@extends('layouts.app')
@section('content')
<header id="resources-header" class="resource_fielter" style="background:url({{ asset('irh_assets/images/resourcebg.jpg') }});height: 800px;background-size: cover;background-attachment: fixed;background-position:top center;">

<!--<header id="resources-header" style="background:linear-gradient(rgba(30, 169, 231, 0.5),rgba(51, 57, 61, 0.5)), url({{ asset('irh_assets/images/resourcebg.jpg') }});height: 800px;background-size: cover;background-attachment: fixed;background-position:top center;">-->
	
	<div class="header-content">
		<div class="subHeaderSec">
			<div class="col-md-4">
				<div class="card card-body searchPanelCustom">
					<div class="searchFormTop">
					<h2>Search for resources</h2>
					<form action="{{ route('theme.resources.filtered') }}" method="GET">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Type a keyword" name="keyword">
						</div>
						<div class="form-group">
							

							<!-- <div class="input-group input-group-sm mb-2 customeDropdown2">
							<select class="form-control form-control-sm" id="fielterAge"  name="age_group[]" id="" multiple>
							@foreach($age_groups as $ag)
								<option value="{{ $ag->id }}">{{ $ag->name }}</option>
								@endforeach
							</select>
							</div> -->

							<div class="input-group input-group-sm mb-2 customeDropdown2">
							<select id="fielterbyage" multiple="multiple">
							@foreach($age_groups as $ag)
							<option value="{{ $ag->id }}">{{ $ag->name }}</option>
							@endforeach
							</select>
							</div>

							<!-- <select name="age_group[]" id="" multiple>
								<option value="" selected disabled>Filter By Age Group</option>
								@foreach($age_groups as $ag)
								<option value="{{ $ag->id }}">{{ $ag->name }}</option>
								@endforeach
							</select> -->
							
						</div>
						<div class="form-group">
							

							<!-- <div class="input-group input-group-sm mb-2 customeDropdown2">
							<select class="form-control form-control-sm" id="fielterResource" name="resource_type[]" multiple>
							@foreach($resource_types as $rt)
							<option value="{{ $rt->id }}">{{ $rt->name }}</option>
							@endforeach
							</select>
							</div> -->

							<div class="form-group">
							<div class="input-group input-group-sm mb-2 customeDropdown2">
							<select id="fielterbyresource" multiple="multiple">
							@foreach($resource_types as $rt)
							<option value="{{ $rt->id }}">{{ $rt->name }}</option>
							@endforeach
							</select>
							</div>


							<!-- <select name="resource_type[]" id="" class="form-control" multiple>
								<option value="" selected disabled>Filter By Resource Type</option>
								@foreach($resource_types as $rt)
								<option value="{{ $rt->id }}">{{ $rt->name }}</option>
								@endforeach
							</select> -->
							
							
						</div>
						<div class="form-group">
							<div class="boxSearchFieldCus">
							<select name="category" id="" class="form-control">
								<option value="" selected disabled>Filter By Categoty</option>
								@foreach($category as $sCategory)
									<option value="{{ $sCategory->id }}">{{ $sCategory->title }}</option>
								@endforeach
							</select>
							</div>
						</div>
						<div class="form-group subPanel">
							<input type="submit" value="Search" class="btn bg-yellow btn-block submitBtn">
						</div>
					</form>
					</div>
					<div class="d-flex flex-row justify-content-between uploadBtnSec">
<!--
						<div class="flex-column">
							&nbsp;
						</div>
						<div class="flex-column">
							&nbsp;
						</div>
-->						
						<div class="flex-column uploadBtnTxt">
							Got your own resource to Share?
						</div>
						<div class="flex-column">
							<a href="
							@if(Auth::user())
									@if(Auth::user()->roles[0]->name == 'admin')
										{{ route('admin.create.resource') }}
									@else
										{{ route('user.create.resource') }}
									@endif
								@else
									{{ route('login') }}
								@endif" class="btn bg-yellow uploadBtn">Upload</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<section id="resources" class="text-center py-5">
	<div class="container">
		<div class="searchTitleSection">
		<h1 class="pb-5 heading heading_cus"><span>Showing search results for</span> Filtered Search Results</h1>
		<div class="boxSearchTitle">
							<select name="age_group" id="">
								<option value="" selected disabled>Relevance</option>
								
								<option value="select">Select</option>
							</select>
							</div>
		</div>
		<div class="row">
			@forelse($resources as $resource)
			<div class="col-md-3 col-sm-6 mb-4">
				<div class="resourcebox hvr-glow">
					<div class="card">
						<div class="thum_resource_wrap">
						<a href="{{ route('theme.singleresource',$resource) }}" class="thum_resource">
						<h3><span>{!! str_limit($resource->title, 12) !!}</span></h3>
					  <img class="card-img-top" src="{{ $resource->preview_attachment_path }}" alt="Card image cap" style="position: relative;"></a>
						
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
					  	<div class="pb-4 author_profile"><img src="{{ asset('irh_assets/images/avatar.png') }}" alt="" width="30px" class="rounded-circle">
							<a href="{{ route('theme.resources.authorprofile',$resource->user) }}" class="ml-3 author_name">{{ $resource->user->full_name }}</a></div>
					   <a href="{{ route('theme.singleresource',$resource) }}" class="text-muted"> <h5 class="card-title">{{ $resource->title }}</h5></a>
					  </div>
					  <div class="card-footer">
					  	<div style="display: grid;">
							<ul>
							<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/view_i.png') }}" alt="" width="18px"></small><span>{{ $resource->views }}</span></li>
							<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/like_i.png') }}" alt="" width="18px"></small><span>{{ $resource->likes->count() }}</span></li>
							<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/down_i.png') }}" alt="" width="18px"></small><span>{{ $resource->likes->count() }}</span></li>
							</ul>
							
<!--
					  		<div style="grid-column: 1;border-right: 1px solid #333;"><small>VIEWS</small><br>{{ $resource->views }}</div>
					  		<div style="grid-column: 2;border-right: 1px solid #333;"><small>DOWNLOADS</small><br>{{ $resource->downloads }}</div>
					  		<div style="grid-column: 3;"><small>LIKES</small><br>{{ $resource->likes->count() }}</div>
-->
					  	</div>
					  </div>
					</div>
				</div>
			</div>
			@empty
			<div class="col-md-4 offset-md-4">
				<h3>No Resources Found</h3>
			</div>
			@endforelse
		</div>
		<div class="row mt-4">
			<div class="col-md-4 offset-md-5">
				{{ $resources->appends(Request::except('page'))->links() }}
			</div>
		</div>
	</div>
</section>

@stop