@extends('layouts.app')
@section('content')
<header id="author-profile-header" class="bg-blue py-5">
<!--
   <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="author-image mb-2">
          <img src="{{ ($author->profile_picture_path) ? asset($author->profile_picture_path) : asset('irh_assets/images/avatar.png') }}" alt="" class="img-thumbnail" width="120px">
        </div>
        <div class="author-name">
          <h4>{{ $author->full_name }} | <a href="#" data-toggle="modal" data-target="#contactAuthorModal" class="btn btn-danger btn-sm">Contact Author</a></h4>
        </div>
      </div>
    </div>
  </div>
-->
</header>
@if(Session::has('success'))
  <div class="alert alert-success text-center">
    {{ Session::get('success') }}
  </div>
  @endif
<section id="author-details" class="py-5 adminProfile_main">
	<div class="container">
	<div class="row adminProfile">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left profile_left authorPro">
		
		<div class="author_thum_details author_thum_details2">
	<img src="{{ !blank($author->profile_picture) ?  asset('irh_assets/uploads/profile_pictures/' . $author->profile_picture) : asset('irh_assets/images/account_profile_default.png') }}" class="img-thumbnail" alt="">
	
		<div class="author_thum_title"> 
		<h3>{{ $author->first_name ." " . $author->last_name }}
			<span>{{ strtoupper($rolename) }}</span></h3>
			@if(Auth::user())
				@if($author->id != Auth::user()->id)
					<?php 
					$following = explode(",", Auth::user()->following);
					if(blank($following)){
						?>
							<a href="javascript::void(0)" class="follow_user">
								<img src="{{ asset('irh_assets/images/plus_icon.png') }}" alt="">
							</a>
						<?php
					}
					elseif(!in_array($author->id, $following)){
						?>
							<a href="javascript::void(0)" class="follow_user">
								<img src="{{ asset('irh_assets/images/plus_icon.png') }}" alt="">
							</a>
						<?php
					}
					else{
						?>
							<a href="javascript::void(0)" style="color: green;">
							<img src="{{ asset('irh_assets/images/tick_icon.png') }}" alt="">
								<!-- <i class="fa fa-check" aria-hidden="true"></i> -->
							</a>
						<?php
					}
					?>
					
				@endif
			@endif
			
		</div>
			
		</div>
	</div>	
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left profile_right">
	<div class="pro_right_sec">
		<h3>Insights</h3>
		<ul class="insight">
		<li><span> {{ count($author->resources) }}</span><span class="sml">Resources</span></li>
		<li><span> {{ collect($author->resources)->sum('views') }}</span><span class="sml">Views</span></li>
		<li><span> {{ collect($author->resources)->sum('downloads') }}</span> <span class="sml">Download</span></li>		
		</ul>
	</div>	
		
	<div class="pro_right_sec">
		<h3>Quick Facts About Me</h3>
		<ul class="aboutMe">
		<li>  <span> Position</span><h3>{{ $author->position }}</h3></li>
		<li>  <span> Work Setting</span><h3>{{ $author->working_in }}</h3></li>
		<li>  <span> Country</span><h3>{{ $author->country }}</h3></li>	
		
		<li>  <span> Specialist subjects</span><h3>{{ $author->subjects }}</h3></li>
		<li>  <span> Hobbies</span><h3>{{ $author->hobbies }}</h3></li>
		<li>  <span> Other</span><h3>{{ 'NA' }}</h3></li>
		</ul>
	</div>
	
	<div class="pro_right_sec">
		<h3>Follow Me On Social Media</h3>
		<ul class="social_icon">
			@if(!blank($author->fb))
			<li><a href="{{ $author->fb }}"><img src="{{ asset('irh_assets/images/social/fb.png') }}" alt=""></a></li>
			@endif
			@if(!blank($author->twiter))
			<li><a href="{{ $author->twiter }}"><img src="{{ asset('irh_assets/images/social/twit.png') }}" alt=""></a></li>
			@endif
			@if(!blank($author->linkedin))
			<li><a href="{{ $author->linkedin }}"><img src="{{ asset('irh_assets/images/social/linkdin.png') }}" alt=""></a></li>
			@endif
			@if(!blank($author->instagram))
			<li><a href="{{ $author->instagram }}"><img src="{{ asset('irh_assets/images/social/insta.png') }}" alt=""></a></li>
			@endif
			@if(!blank($author->new_social))
			<li><a href="{{ $author->new_social }}"><img src="{{ asset('irh_assets/images/social/icon.png') }}" alt=""></a></li>
			@endif
		</ul>
	</div>
	
	</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="description_profile">
				<p>{!! trim($author->about_me) !!}</p>
			</div>
		</div>
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="searchPanel">
				
				<form action="{{ route('theme.resources.authorprofile', $author->id) }}" method="GET">
				<h2>My Resource</h2> <div class="searchSec">
					<input type="search" placeholder="Find a specific resource" id="search_box" value="{{ isset($allParam['search']) && !blank($allParam['search']) ? $allParam['search'] : '' }}">
				</div>
					
				<div class="searchPanelSub">
					<div class="fielterSec">
						<h3>Filter</h3>
						<div class="fieldSec">
							

							<div class="input-group input-group-sm mb-2 customeDropdown2">
							<select class="form-control form-control-sm" id="myResourceFielter1" name="res[]" multiple="multiple">
							@foreach($filters as $filter)
							@if($filter->tag_group == 'Resource Type')
							<option value="{{ $filter->id }}" {{ isset($allParam['res']) && $allParam['res'] == $filter->id ? 'selected' : '' }}>{{ $filter->name }}</option>
							@endif
							@endforeach
							</select>
							</div>

							<!-- <select name="res[]" multiple="multiple" id="filter_drop1" class="form-control">
							<option value="">Filter By Resource Type</option>
							@foreach($filters as $filter)
							@if($filter->tag_group == 'Resource Type')
							<option value="{{ $filter->id }}" {{ isset($allParam['res']) && $allParam['res'] == $filter->id ? 'selected' : '' }}>{{ $filter->name }}</option>
							@endif
							@endforeach
							</select> -->
							
						</div>
						
						<div class="fieldSec">
								<div class="input-group input-group-sm mb-2 customeDropdown2">
								<select name="age[]" multiple="multiple" id="myResourceFielter2" class="form-control">
									
									@foreach($filters as $filter)
										@if($filter->tag_group == 'Age Group')
											<option value="{{ $filter->id }}" {{ isset($allParam['age']) && $allParam['age'] == $filter->id ? 'selected' : '' }}>{{ $filter->name }}</option>
										@endif
									@endforeach
								</select>
								</div>


							<!-- <div class="boxSearchFieldCus">
								<select name="age[]" multiple="multiple" id="filter_drop2" class="form-control">
									<option value="">Filter By Age Group</option>
									@foreach($filters as $filter)
										@if($filter->tag_group == 'Age Group')
											<option value="{{ $filter->id }}" {{ isset($allParam['age']) && $allParam['age'] == $filter->id ? 'selected' : '' }}>{{ $filter->name }}</option>
										@endif
									@endforeach
								</select>
							</div> -->
						</div>
					</div>

					<div class="shortBy">
						<h3>Short By</h3>
						<div class="fieldSec">
							
							<div class="boxSearchFieldCus">
								<select name="sort" id="filter_drop3" class="form-control">
									<option value="relevance" {{ isset($allParam['sort']) && $allParam['sort'] == 'relevance' ? 'selected' : '' }}>Relevance</option>

									<option value="newest" {{ isset($allParam['sort']) && $allParam['sort'] == 'newest' ? 'selected' : '' }}>Newest</option>

									<option value="mdl" {{ isset($allParam['sort']) && $allParam['sort'] == 'mdl' ? 'selected' : '' }}>Most downloaded</option>

									<option value="hr" {{ isset($allParam['sort']) && $allParam['sort'] == 'hr' ? 'selected' : '' }}>Highest rated</option>
								</select>
							</div>


						</div>
						<div class="fieldSecBtn">
							<input type="submit"  value="Refine" id="refine_btn"> <input type="reset" value="Clear All">
    </form>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	</div>
	<br clear="all">
  	<div class="relatedResources py-4 text-center">
	    <div class="container">
	      	<div class="row feture_resources">
		        @forelse($resources as $res)
			        <div class="col-md-3 mb-4">
			          	<div class="resourcebox hvr-glow">
				            <div class="card">
								<div class="thum_resource_wrap">
				              		<a href="{{ route('theme.singleresource',$res->id) }}" class="thum_resource">
								  		<h3><span>{!! str_limit($res->title, 12) !!}</span></h3>
				              			<img class="card-img-top" src="{{ asset($res->prev) }}" alt="Card image cap" style="position: relative;">
				              		</a>
			               			<span style="position: absolute;top: -1;right: 10px;" id="saveResourceContainer_{{ $res->id }}">
										@auth
										@if(\App\Resource::isResourceSavedByLoggedInUserInAuth($res->id))
										<a href="javascript:void(0);"  onclick='saveResource("{{ $res->id }}",false);'>
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
				                		<img src="{{ asset('irh_assets/images/avatar.png') }}" alt="" width="30px" class="rounded-circle">
				                		<span class="ml-3 author_name">{{ $author->full_name }}</span>
				                	</div>
				                	<a href="{{ route('theme.singleresource',$res->id) }}" class="text-muted"><h5 class="card-title">{{ $res->title }}</h5></a>
				              	</div>
				              	<div class="card-footer">
				                	<div style="display: grid;">			
										<ul>
											<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/view_i.png') }}" alt="" width="18px"></small><span>{{ $res->views }}</span></li>
											<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/like_i.png') }}" alt="" width="18px"></small><span>{{ $res->likes }}</span></li>
											<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/down_i.png') }}" alt="" width="18px"></small><span>{{ $res->downloads }}</span></li>
										</ul>
				                	</div>
				              	</div>
				            </div>
			          	</div>
			        </div>
		        @empty
			        <div class="col-md-4 offset-md-4">
			          <h3>No Resource</h3>
			        </div>
		        @endforelse
	      </div>
	    </div>
  	</div>
</section>

<div class="modal fade" id="contactAuthorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Contact Author</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('theme.resources.authorprofile.mail') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="">Your Full Name: *</label>
            <input type="text" class="form-control" name="full_name" value="{{ (Auth::user())?Auth::user()->full_name : '' }}" required>
          </div>
          <div class="form-group">
            <label for="">Your Email: *</label>
            <input type="email" class="form-control" name="email" value="{{ (Auth::user())?Auth::user()->email : '' }}" required>
          </div>
          <div class="form-group">
            <label for="">Subject: *</label>
            <input type="text" class="form-control" name="subject" required>
          </div>
          <div class="form-group">
            <label for="">Message: *</label>
            <textarea name="message" rows="3" class="form-control" required></textarea>
          </div>          
          <div class="form-group">
            <input type="hidden" name="author_id" value="{{ $author->id }}">
            <input type="submit" class="btn bg-blue" value="Send Message">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@stop

@section('page_scripts')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>

	<script>
		$("#search_box").keypress(function(event){
		    var keycode = (event.keyCode ? event.keyCode : event.which);
		    if(keycode == '13'){
		        window.location = "{{ route('theme.resources.authorprofile', $author->id) }}" + "?search=" + $("#search_box").val();
		    }
		});

		$("#refine_btn").click(function(){

			window.location = "{{ route('theme.resources.authorprofile', $author->id) }}" + "?res=" + $("#filter_drop1").val() + "&age=" + $("#filter_drop2").val() + "&sort=" + $("#filter_drop3").val();
		});

		$(".follow_user").click(function(){
			@if(Auth::user())
				$.ajax({
					url:"{{ url('/author/'. $author->id . '/follow')}}",
					method:"GET",
					success:function(data){
						alert("Your following list has been updated!");
					},
					error:function(){
						alert("Error!");
					}
				})
			@endif
		});

		$('#myResourceFielter1').multiselect({
		includeSelectAllOption: true
		});

		$('#myResourceFielter2').multiselect({
		includeSelectAllOption: true
		});

	</script>
@endsection