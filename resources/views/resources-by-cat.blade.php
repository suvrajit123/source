@extends('layouts.app')
@section('content')

<header id="resources-header" style="background:linear-gradient(rgba(30, 169, 231, 0.5),rgba(51, 57, 61, 0.5)), url({{ asset('irh_assets/images/' . \App\Option::getOption('HEADER_IMG_RESOURCES')) }});height: 800px;background-size: cover;background-attachment: fixed;background-position:top center;">
	<div class="header-content">
		<div class="subHeaderSec">
			<div class="col-md-4">
				<div class="card card-body searchPanelCustom">
				<div class="searchFormTop">
					<h2>Search for resources</h2>
					<form action="{{ route('theme.resources.filtered') }}" method="GET">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Search for resources" name="keyword" value="">
						</div>


						<div class="form-group">
						<div class="input-group input-group-sm mb-2 customeDropdown2">
						<select id="fielterbyage" multiple="multiple">
						@foreach($age_groups as $ag)
						<option value="{{ $ag->id }}">{{ $ag->name }}</option>
						@endforeach
						</select>
						</div>
						</div>

						<div class="form-group">
						<div class="input-group input-group-sm mb-2 customeDropdown2">
						<select id="fielterbyresource" multiple="multiple">
						@foreach($resource_types as $rt)
						<option value="{{ $rt->id }}">{{ $rt->name }}</option>
						@endforeach
						</select>
						</div>

						
						</div>
						<div class="form-group">
							<input type="submit" class="btn bg-yellow btn-block">
						</div>
					</form>
					</div>
					<div class="d-flex flex-row justify-content-between">
						<div class="flex-column">
							&nbsp;
						</div>
						<div class="flex-column">
							&nbsp;
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
								@endif
							" class="btn bg-yellow">Upload a resource</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>

<section id="resources" class="text-center py-5">
	<div class="container">
		<h1 class="pb-5 heading">{{ isset($category) ? $category : $tag }} Resources</h1>
		<div class="row resblock">
			@include('resources_cat_pagination')
		</div>
	</div>
</section>
@endsection
@section('page_scripts')
	<script>
		$(document).on("click", '.loadmore', function(){
			var eThis = $(this);
			
			$.ajax({
				@if(isset($category))
					url:"{{ url('/resources/category/' . $category_id ) }}" + "/" + eThis.attr('data-last-id')
				@else
					url:"{{ url('/resources/tag/' . $tag_id ) }}" + "/" + eThis.attr('data-last-id')
				@endif, 
				method:"GET",
				success:function(data)
				{
					eThis.remove();
					$('.resblock').append(data);
				}
			});
		});
	</script>
@endsection
