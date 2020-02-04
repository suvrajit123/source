@forelse($resources as $resource)
<div class="col-md-3 mb-4 xx">
	<div class="resourcebox hvr-glow">
		<div class="card">
		<div class="thum_resource_wrap">
			<a href="{{ route('theme.singleresource',$resource->id) }}" class="thum_resource">
			<h3><span>{{ $resource->title }}</span></h3>
		  		<img class="card-img-top" src="{{ !blank($resource->preview_attachment) ? asset('irh_assets/uploads/resource_preview/' . $resource->preview_attachment) : asset('irh_assets/images/resource_thum.jpg') }}" alt="Card image cap" style="position: relative;">
		  	</a>
		 	<span class="proTagSave" id="saveResourceContainer_{{ $resource->id }}">
			  	@auth
				  	@if(!in_array($resource->id, $savedResources))
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
		  			<img src="{{ asset('irh_assets/images/avatar.png') }}" alt="" width="30px" class="rounded-circle">
					  <span class="ml-3 author_name">{{ $resource->full_name }}</span></div>
					 <a href="{{ route('theme.singleresource',$resource->id) }}"><h5 class="card-title text-muted">{{ $resource->title }}</h5></a>
	  		</div>
	  		<div class="card-footer">
		  		<div style="display: grid;">
		  			<!-- <div style="grid-column: 1;border-right: 1px solid #333;"><small>VIEWS</small><br>{{ $resource->views }}
		  			</div>
		  			<div style="grid-column: 2;border-right: 1px solid #333;"><small>DOWNLOADS</small><br>{{ $resource->downloads }}
		  			</div>
		  			<div style="grid-column: 3;"><small>LIKES</small><br>{{ !blank($resource->reslikes) ? $resource->reslikes : '0' }}</div> -->

					<ul>
					<li>
					<small class="icon_cl"><img src="{{ asset('irh_assets/images/view_i.png') }}" alt="" width="18px"></small><span>{{ $resource->views }}</span>
					</li>
					<li>
					<small class="icon_cl"><img src="{{ asset('irh_assets/images/like_i.png') }}" alt="" width="18px"></small><span>{{ !blank($resource->reslikes) ? $resource->reslikes : '0' }}</span>
					</li>
					<li>
					<small class="icon_cl"><img src="{{ asset('irh_assets/images/down_i.png') }}" alt="" width="18px"></small><span>{{ $resource->downloads }}</span>
					</li>
					</ul>



		  		</div>
		  	</div>
		</div>
	</div>
</div>
@empty
<div class="col-sm-12 dataNotFound">
	<h3>No Resources Found</h3>
</div>
@endforelse
@if(count($resources) >= 4)
<div class="col-sm-12"><a href="javascript:void(0)" class="btn btn-primary loadmore" data-last-id="{{ $lastid }}">Load More</a></div>
@else
<div class="col-sm-12 dataNotFound">
	<h3>No More Resources</h3>
</div>
@endif