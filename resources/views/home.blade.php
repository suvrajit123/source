@extends('layouts.app')

@section('page_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('irh_assets/vendor/slick/slick.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('irh_assets/vendor/slick/slick-theme.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('irh_assets/css/slick-custom.css') }}"/>
@stop

@section('content')
<header id="main-header" class="responsive" style="background:linear-gradient(to bottom, rgba(6,117,181,0.78) 0%, rgba(255,255,255,0.14) 56%, rgba(0,0,0,0.01) 67%, rgba(0,0,0,0) 68%, rgba(0,0,0,0) 100%), url({{ asset('irh_assets/images/' . \App\Option::getOption('HEADER_IMG_HOME')) }});height: 800px;background-size: cover;background-attachment: fixed;">
	<div class="header-content">
		<h1 class="signika">Resources made by you. Free. Forever.</h1>
		<form action="{{ route('theme.resources.filtered') }}" method="GET" id="search-form">
			<div class="input-group">
				<input type="text" class="form-control" placeholder="Search for powerpoints, worksheets, posters, visual aids and much more" name="keyword">
				<div class="input-group-prepend">
		          <span class="input-group-text bg-yellow" id="inputGroupPrepend" onclick="event.preventDefault();
              document.getElementById('search-form').submit();"><i class="fa fa-search p-2"></i></span>
		        </div>
			</div>
		</form>
	</div>
</header>



<section id="intro_video">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="video_text">
				<h2 id="video_heading">
					Assalamu’alaikum,
				</h2>
				<p id="video_heading_text">
					To all teachers, parents, imams and students – welcome to IRH; your one-stop platform for sharing Islamic learning materials. Share your own resources or download resources made by others. You’ll find a wide variety of learning material that ranges from PowerPoint presentations to quizzes. What’s better yet is that it’s all for free. Watch the video to learn more about what we do.
				</p>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="video">
				<iframe class="video" src="{{ url('https://www.youtube.com/embed/CfwPlvWexYU') }}" width="100%" height="315px" frameborder="0" allowfullscreen></iframe>
			</div>
		</div>
	</div>
</section>

<section class="infographicMainFull">
<section class="infographicMain">
	<div class="col-sm-12">
	<h1 class="heading">Join our community and benefit</h1>
	<p style="font-family: Roboto; ">Connect with other teachers and share your resources in four easy steps!</p>
	</div>
	<div class="infographic responsive">
		<div class="circleSec">
			<div class="circleSec_sub circle1"><span class="number">1</span>
				<i><img src="{{ asset('irh_assets/images/icon_info_a.png') }}" alt=""></i>
				<h5>Sign Up</h5>
			</div>
		</div>
		<div class="circleSec">
			<div class="circleSec_sub circle2"><span class="number">2</span>
			<i><img src="{{ asset('irh_assets/images/icon_info_b.png') }}" alt=""></i>
			<h5>Upload</h5>
			</div>
		</div>
		<div class="circleSec">
			<div class="circleSec_sub circle3"><span class="number">3</span>
			<i><img src="{{ asset('irh_assets/images/icon_info_c.png') }}" alt=""></i>
			<h5>Share</h5>
			</div>
		</div>
		<div class="circleSec">
			<div class="circleSec_sub circle4"><span class="number">4</span>
			<i><img src="{{ asset('irh_assets/images/icon_info_d.png') }}" alt=""></i>
			<h5>Benefit</h5>
			</div>
		</div>
		<br clear="all">
	</div>
</section>
</section>
<section id="categories" class="text-center">
	<div class="container">
		<h1 class="text-white signika">Categories
			<span>12 comprehensive fields; resources organised!</span>
</h1>
		<div class="row">
			@foreach($categories as $category)
			<div class="col-md-2 col-sm-6 categorybox p-3 catagori_demo" data-aos="">
				<div class="category">
					<a href="{{ url('/resources/filtered/' . '?category=' . $category->id) }}">
					<img src="{{ asset('irh_assets/images/categories/'.strtolower($category->title).'.png') }}" alt="" class="hvr-grow">
					</a>
					<a href="{{ url('/resources/filtered/' . '?category=' . $category->id) }}"><h5 class="mb-0 pt-4 text-white">{{ $category->title }}</h5></a>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</section>




<section id="featured_resources" class="text-center py-5">
	<div class="container">
		<h1 class="heading ">Featured Resources</h1>
		<p class="resource_heading">The most popular resources - selected by us.</p>
		<div class="feture_resources">
			<div class="slider_area">
			<img class="left_arro prev_fr" src="{{ asset('irh_assets/images/aro_left.png') }}">
			<img class="right_arro next_fr" src="{{ asset('irh_assets/images/aro_right.png') }}">
			<div class="slider_overflow">
			<div class="featured_resources_slick">
			@forelse($featured as $fr)
			<div class="featured_resource_box feature_box_cus">
				<div class="resourcebox hvr-glow">
					<div class="card">
					<div class="thum_resource_wrap">
					  <a href="{{ route('theme.singleresource',$fr) }}" class="thum_resource">
						  <h3><span>{!! str_limit($fr->title, 12) !!}</span></h3>
					  <img class="card-img-top" src="{{ $fr->preview_attachment_path }}" alt="Card image cap" style="position: relative;"></a>
					  <span class="proTagSave" id="saveResourceContainer_{{ $fr->id }}">
					  	@auth
					  	@if(!$fr->isResourceSavedByLoggedInUser())
					  	<a href="javascript:void(0);"  onclick='saveResource("{{ $fr->id }}",false);'>
					  	<img src="{{ asset('irh_assets/images/savelogo.png') }}" alt="" width="29px" data-toggle="tooltip" data-placement="top" title="save for later">
					  	</a>
					  	@else
					  	<img src="{{ asset('irh_assets/images/savedlogo.png') }}" alt="" width="25px">
					  	@endif
					  	@endauth
					  </span>
						</div>
					  <div class="card-body">
					  	<div class="pb-4 author_profile"><img src="{{ asset('irh_assets/images/avatar.png') }}" alt="" width="28px" class="rounded-circle" style="display: inline-block;"><a href="{{ route('theme.resources.authorprofile',$fr->user) }}" class="ml-3 author_name">{{ $fr->user->full_name }}</a></div>
					    <a href="{{ route('theme.singleresource',$fr) }}" class="text-muted"><h5 class="card-title">{{ $fr->title }}</h5></a>
					  </div>
					  <div class="card-footer">
					  	<div style="display: grid;">
							<ul>
							<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/view_i.png') }}" alt="" width="18px"></small><span>{{ $fr->views }}</span></li>
							<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/like_i.png') }}" alt="" width="18px"></small><span>{{ $fr->likes->count() }}</span></li>
							<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/down_i.png') }}" alt="" width="18px"></small><span>{{ $fr->downloads }}</span></li>
							</ul>
					  	</div>
					  </div>
					</div>
				</div>
			</div>
			@empty
			</div>
			</div>
			</div>
			<div class="col-md-4 offset-md-4">
				<h3>No Featured Resources</h3>
			</div>
			@endforelse
		</div>
	</div>
</section>

<section id="new_resources" class="text-center py-5">
	<div class="container">
		<h1 class="heading">New Resources</h1>
		<p class="resource_heading">Check out these new amazing resources.</p>
	

		<div class="new_resources">
			<div class="slider_area">
			<img class="left_arro prev_Nr" src="{{ asset('irh_assets/images/aro_left.png') }}">
			<img class="right_arro next_Nr" src="{{ asset('irh_assets/images/aro_right.png') }}">
			<div class="slider_overflow">
			<div class="new_resources_slick">

			
			@forelse($new_resources as $nr)
			<div class="featured_resource_box feature_box_cus mb-4">
				<div class="resourcebox hvr-glow">
					<div class="card">
						<div class="thum_resource_wrap">
						<a href="{{ route('theme.singleresource',$nr) }}" class="thum_resource">
						<h3><span>{!! str_limit($nr->title, 12) !!}</span></h3>
					  <img class="card-img-top" src="{{ $nr->preview_attachment_path }}" alt="Card image cap" style="position: relative;"></a>
						<span class="proTagSave" id="saveResourceContainer_{{ $nr->id }}">
					  	@auth
					  	@if(!$nr->isResourceSavedByLoggedInUser())
					  	<a href="javascript:void(0);"  onclick='saveResource("{{ $nr->id }}",false);'>
					  	<img src="{{ asset('irh_assets/images/savelogo.png') }}" alt="" width="29px" data-toggle="tooltip" data-placement="top" title="save for later">
					  	</a>
					  	@else
					  	<img src="{{ asset('irh_assets/images/savedlogo.png') }}" alt="" width="25px">
					  	@endif
					  	@endauth
					  </span>
						</div>
					  
					  <div class="card-body">
					  	<div class="pb-4 author_profile"><img src="{{ asset('irh_assets/images/avatar.png') }}" alt="" width="28px" class="rounded-circle" style="display: inline-block;"><a href="{{ route('theme.resources.authorprofile',$nr->user) }}" class="ml-3 author_name">{{ $nr->user->full_name }}</a></div>
					    <a href="{{ route('theme.singleresource',$nr) }}" class="text-muted"><h5 class="card-title">{{ $nr->title }}</h5></a>
					  </div>
					  <div class="card-footer">
					  	<div style="display: grid;">
							<ul>
							<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/view_i.png') }}" alt="" width="18px"></small><span>{{ $nr->views }}</span></li>
							<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/like_i.png') }}" alt="" width="18px"></small><span>{{ $nr->downloads }}</span></li>
							<li><small class="icon_cl"><img src="{{ asset('irh_assets/images/down_i.png') }}" alt="" width="18px"></small><span>{{ $nr->likes->count() }}</span></li>
							</ul>
					  	</div>
					  </div>
					</div>
				</div>
			</div>
			@empty
			
			</div>
			</div>
			</div>
			<br clear="all">

			<div class="col-md-4 offset-md-4">
				<h3>No Latest Resources</h3>
			</div>
			@endforelse
		</div>
		<br clear="all">

		<div class="row pt-5">
			<div class="col-md-4 offset-md-4">
				<a href="{{ route('theme.resources.filtered') }}" class="btn btn-lg bg-yellow discoverBtn" style="border-radius: 6px;">Discover more Resources</a>
			</div>
		</div>
	</div>
</section>

<section>

</section>

<section id="testimonials" class="text-center py-5 bg-blue" style="border: none !important;">
	<div class="container">
		<h1 class="pb-2 signika" style="position: relative;">Testimonials</h1>
		<p class="" style="position: relative;">Have a look at what reputable scholars say about IRH</p>
		<div class="testi_slider_area testiSldThree">
			<img class="left_arro prev_testi" src="{{ asset('irh_assets/images/aro_testi_left.png') }}">
			<img class="right_arro next_testi" src="{{ asset('irh_assets/images/aro_testi_right.png') }}">
			<div class="testi_slider_overflow">
			<div class="testimonial_slick">
				
			@forelse($testimonials as $testimonial)
			<div class="item testi_feature_box_cus">
				<div class="card card-body">
					<i class="icon_testi"><img src="{{ asset('irh_assets/images/testimonial_icon.png') }}"></i>
					<div class="py-5 blue-color title_testi">
						<p>
							{{ $testimonial->testimonial_text }}
						</p>
					</div>
					<div class="py-2 testi_client">
						<span class="blue-color">{{ ucwords($testimonial->testimonial_by) }}</span>
					</div>
				</div>
			</div>
			@empty
			<div class="item">
				<div class="card card-body" style="min-height: 300px;">
					<div class="py-5 blue-color">
						<p>
							No Testimonials Found
						</p>
					</div>
				</div>
			</div>
			@endforelse
			</div>
			</div>
		</div>
	</div>
</section>

<section id="media_footer" class="footerMain">
	<!-- <div class="container"> -->
		<div class="footer">
			<div class="col-md-7 footer_subscription float-left" id="nesletter_subscription">
				<h1 class="text-white signika">Subscribe to our mailing list</h1>
				<p class="tagline text-white">Subscribe & receive updates on new resources shared</p>
				<form class="" action="{{ route('theme.newslettersubscription') }}" method="POST" id="subsc_form">
					@csrf
					<div id="form-container">
						<div class="subscribe_sec" style="grid-column: 1; padding-bottom: 16px;">
							<input type="text" class="form-control" id="subscribeInput" placeholder="Type your e-mail address" name="email" style="background: grey;">
							<button class="btn" id="subscribeBtn" type="submit">Subscribe</button>
						</div>
					</div>
				</form>
				<div class="alert alert-success" id="mailinglistdiv_success" style="display: none;">
	            </div>
	            <div class="alert alert-danger" id="mailinglistdiv_error" style="display: none;">
	            </div>
			</div>
			<div class="col-md-5 footer_social float-left" id="social_media">
				<h1 class="text-white signika">Social media</h1>
				<p class="tagline text-white">Follow us on social media and stay updated</p>
				<div class="social-btns">
					<a href="{{ App\Option::getOption('SOCIAL_YT') }}" target="_blank"> <img class="social-icons" src="{{ asset("irh_assets/images/youtube.png") }}" alt=""> </a>
					<a href="#"> <img class="social-icons pl-3" src="{{ asset("irh_assets/images/ooli.png") }}" alt=""> </a>
					<a href="{{ App\Option::getOption('SOCIAL_TT') }}"> <img class="social-icons pl-3" src="{{ asset("irh_assets/images/twitter-2.png") }}" alt=""> </a>
					<a href="{{ App\Option::getOption('SOCIAL_FB') }}"> <img class="social-icons pl-3" src="{{ asset("irh_assets/images/facebook-2.png") }}" alt=""> </a>
					<a href="{{ App\Option::getOption('SOCIAL_INS') }}"> <img class="social-icons pl-3" src="{{ asset("irh_assets/images/instagram-2.png") }}" alt=""> </a>
					
					
				</div>
			</div>

			
		</div>
	<!-- </div> -->
</section>


@endsection

@section('page_scripts')
<script src="{{ asset('irh_assets/vendor/slick/slick.min.js') }}" id="slick_js_path"></script>
<script src="{{ asset('irh_assets/js/slick-custom.js') }}?v={{ microtime() }}"></script>

<script type="text/javascript">
$('.new_resources_slick').slick({
  dots: false,
  infinite: false,
  speed: 300,
  slidesToShow: 4,
  slidesToScroll: 1,
  prevArrow: $('.prev_Nr'),
  nextArrow: $('.next_Nr'),
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

$('.featured_resources_slick').slick({
  dots: false,
  infinite: false,
  speed: 300,
  slidesToShow: 4,
  slidesToScroll: 1,
  prevArrow: $('.prev_fr'),
  nextArrow: $('.next_fr'),
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

$('.testimonial_slick').slick({
  dots: false,
  infinite: false,
  speed: 300,
  slidesToShow: 3,
  slidesToScroll: 1,
  prevArrow: $('.prev_testi'),
  nextArrow: $('.next_testi'),
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

	$("#subscribeBtn").click(function(e){
		$("#mailinglistdiv_success").css("display", "none");
		$("#mailinglistdiv_success").html("");
		$("#mailinglistdiv_error").css("display", "none");
		$("#mailinglistdiv_error").html("");
		e.preventDefault();
		var formdata = document.getElementById("subsc_form");
		$.ajax({
			url:"{{ route('theme.newslettersubscription') }}",
			method:"POST",
			data:$("#subsc_form").serialize(),
			success:function(data){
				$("#mailinglistdiv_success").css("display", "block");
				$("#mailinglistdiv_success").html(data.message);
				$("#subscribeInput").val("");
			},
			error:function(xhr){
				var errors = xhr.responseJSON;
	            $.each( errors.errors , function( key, value ) {
	                $("#mailinglistdiv_error").css("display", "block");
					$("#mailinglistdiv_error").html(value);
	                return false;
	            });
			}
		});
	});
</script>
@stop
