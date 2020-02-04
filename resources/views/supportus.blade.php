@extends('layouts.app')
@section('content')
<style>
	.stripe-button-el{
		cursor: pointer !important; 
		width: auto !important;
		border: none !important;
		border-radius: .25rem !important;
		display: inline-block !important;
		color: #fff !important;
		font-size: 15px !important;
		padding: 6px 20px !important;
		background: var(--newYello-color) !important;
	}
</style>
<header id="main-header" class="responsive" style="background:linear-gradient(rgba(30, 169, 231, 0.5),rgba(51, 57, 61, 0.1)), url({{ asset('irh_assets/images/' . \App\Option::getOption('HEADER_IMG_SUPPORT_US')) }});height: 800px;background-size: cover;background-position:top center;">
	<div class="header-content supportHeader">
		<h1 class="signika">Teach. Educate. Learn. Rise.</h1>
		<h3>Help us support educators worldwide.</h3>
		<div id="bottomScrollerContainer">
			<a href="#" id="bottomScroller">
				<i class="fa fa-angle-down"></i>
			</a>
		</div>
	</div>
</header>
<section id="supportus-description" >
	<div class="container">
		<h2 id="section-title">Support us and help make our future brighter.</h2>
		<p class="section-body">At IRH, our mission from the onset has been to facilitate a platform where teachers, parents and imams can get together and share Islamic resources for the benefit of the Ummah. What’s more is that we envisioned that this would all be for free, forever; so important to us was this concept that it became our moto that we cherish and live by.</p>
		<p class="section-body">However, in order to sustain the project, we are in need of donations. The donations will help sustain the operational costs of the website, which includes paying for administration duties, marketing campaigns, subscriptions such as mail chimp, domain and hosting, as well as purchasing any required software or material needed for the project. In addition, we plan to introduce future phases including publications.</p>
		<p class="section-body">We humbly request that you donate whatever is in your capacity; no donation is too small. For something little as £2.50 a month, you can help sustain IRH for years to come. Don’t forget, that this is a form of Sadaqah Jaariyah – a reward that keeps on giving.</p>
	</div>
</section>
<section id="supportus" class="text-center py-5 supportus_main">
	<div class="container">
		<div class="row">
			<div class="col-md-4 supportus-card">
				<div class="supportus_card_sub">
				<img src="{{ asset('irh_assets/images/spread.png') }}" alt="" class="img_support">
				<div class="py-2">
					<h3 class="text-muted">Spread the word</h3>
					<div class="pt-3 pb-2">
						<p>Help us spread the word by following us on facebook, twitter and Instagram. Also share the website!</p>
					</div>
					<a href="#" data-toggle="modal" data-target="#spreadWordModal" class="btn btn-block bg-yellow supBtn">Spread the Word</a>
				</div>
				</div>
			</div>
			<div class="col-md-4 supportus-card">
			<div class="supportus_card_sub">
				<img src="{{ asset('irh_assets/images/monthly.png') }}" alt="" class="img_support">
				<div class="py-2">
					<h3 class="text-muted">Give Monthly</h3>
					<div class="pt-3 pb-2">
						<p>Support us by donating a small amount each month. The price of a cup of coffee is all we need!</p>
					</div>
					<a href="#" class="btn btn-block bg-yellow supBtn" data-toggle="modal" data-target="#monthlyDonationModal">Give Monthly</a>
				</div>
				</div>
			</div>
			<div class="col-md-4 supportus-card">
				<div class="supportus_card_sub">
				<img src="{{ asset('irh_assets/images/now.png') }}" alt="" class="img_support">
				<div class="py-2">
					<h3 class="text-muted">Give Now</h3>
					<div class="pt-3 pb-2">
						<p>Donate and help develop the platform to support teachers worldwide.</p>
						<br>
					</div>
						<a href="#" class="btn btn-block bg-yellow supBtn" data-toggle="modal" data-target="#oneTimeDonationModal">Give now</a>
				</div>
				</div>
			</div>
		</div>
		@if(Session::has('success'))
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-success">
					{{Session::get('success') }}
				</div>
			</div>
		</div>
		@endif
	</div>
</section>


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
										<a href="javascript:void(0)" class="otd" data-amt="{{ $amt }}">£ {{ $amt }}</a>
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
								
<div class="modal fade" id="monthlyDonationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
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
							<input type="text" name="fname" required="required" />
						</div>
						<div class="col-sm-6 float-left">
							<label>Last Name</label>
							<input type="text" name="lname" required="required"/>
						</div>
						<div class="col-sm-12 float-left">
							<label>Email Address</label>
							<input type="email" name="email" required="required"/>
						</div>
					</div>
					<div class="formFieldBorder">
						<i class="number">3</i>
						<div class="col-sm-12 float-left">
							<label>Select Payment Processor</label>
							<div class="form-check form-check-inline form-checkCus">
								<input class="form-check-input" type="radio" name="mod_gateway_sel" id="mod_paypal_radio" value="paypal">
								<label class="form-check-label" for="mod_paypal_radio">
									<img src="{{ asset('irh_assets/images/paypal.svg') }}" alt="" class="paypal">
								</label>
							</div>
							<div class="form-check form-check-inline form-checkCus">
								<input class="form-check-input" type="radio" name="mod_gateway_sel" id="mod_stripe_radio" value="stripe" checked>
								<label class="form-check-label" for="mod_stripe_radio">
										<img src="{{ asset('irh_assets/images/cardPay.svg') }}" alt="" class="cardPay">
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
										<a href="javascript:void(0)" class="mod" data-amt="{{ $amt }}">£ {{ $amt }}</a>
									</li>
								@endforeach
							</ul>
							<label>Or Enter A Specific Amount</label>
							<input type="text" name="amount" class="specific" required="required" id="mod_amount" />
						</div>
					</div>
					<div class="btnSec">
						<script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
						            data-key="{{ App\Option::getOption('STRIPE_PK_KEY') }}"
						            data-name="Islamic Resource Hub"
									data-description="Monthly Donation"
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
					   <a href="{{ App\Option::getOption('SOCIAL_TT') }}" target="_blank">
					   <img src="{{ asset('irh_assets/images/twit_icon.png') }}" alt="">
					   </a>
					</li>
					<li>
					   <a href="{{ App\Option::getOption('SOCIAL_FB') }}" target="_blank">
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
<script>
	$("#bottomScroller").bind('click',function() {
	    $('html, body').animate({
	        scrollTop: $("#supportus").offset().top
	    }, 2000);
	});

	$(".otd").click(function(){
		$("#otd_amount").val($(this).attr("data-amt"));
	});

	$(".mod").click(function(){
		$("#mod_amount").val($(this).attr("data-amt"));
	});
</script>
@stop
