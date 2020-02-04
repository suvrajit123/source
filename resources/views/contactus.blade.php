@extends('layouts.app')
@section('content')
<header id="contact-header"  style="background:linear-gradient(rgba(30, 169, 231, 0.5),rgba(51, 57, 61, 0.5)),url({{ asset('irh_assets/images/' . \App\Option::getOption('HEADER_IMG_CONTACT_US')) }});height: 750px;background-size: cover;background-position:center; ">
	<div class="header-content">
		
		<h1 class="signika">Need some help? Contact us.</h1>
		<h3>Help us support educators worldwide.</h3>

	</div>
</header>
<section id="contactus" class="text-center py-5">
	<div class="container" style="margin-top:-14%;">
		@if(Session::has('success'))
		<div class="alert alert-success">
			{{ Session::get('success') }}
		</div>
		@endif
		@if ($errors->any())
		    <div class="row">
				<div class="col-md-12">
					<div class="alert alert-danger">
						{!! $errors->all()[0] !!}
					</div>
				</div>
			</div>
		@endif
		<div class="row">
			<div class="con_body">
				
			<div class="col-md-8 px-0">
				<div class="card">
					<div class="card-body p-5 contactForm">
						<div class="col-sm-6"><h3 class="mb-3 text-left">Get in touch with us</h3></div>
						<form action="{{ route('theme.contactus.sendmail') }}" method="POST">
							@csrf
							
								<div class="col-sm-6 float-left">
									<div class="form-group">
									<label class="fieldName">First Name</label>
								<input type="text" class="form-control" name="first_name">
									</div>
								</div>
								
								<div class="col-sm-6 float-left">
									<div class="form-group">
										<label class="fieldName">Last Name</label>
								<input type="text" class="form-control" name="last_name">
										</div>
								</div>
								
							
							<div class="col-sm-12 emai12">
							<div class="form-group">
							<label class="fieldName">E-mail Address</label>
								<input type="email" class="form-control" name="email">
							</div>
							</div>
							
							<div class="col-sm-12">
							<div class="form-group">
							<label class="fieldName">Subject</label>	
								<input type="text" class="form-control" name="subject">
							</div>
							</div>
							<div class="col-sm-12">
							<div class="form-group">
							<label class="fieldName">Message</label>	
								<textarea name="message" id="" rows="3" class="form-control"></textarea>
							</div>
							</div>
							
<!--
							<div class="col-sm-12">
							<div class="form-group">
-->
								<input type="submit" class="btn bg-yellow submit_contact" value="Send Message">
<!--
							</div>
							</div>
-->
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-4 px-0 contact_img">
				<div class="card p-5 bg-blue text-white text-left contactFormRightPan">
<!--
					<h3 class="mb-0 pb-4">As-salamu 'alaykum</h3>
					<p style="color:#fff;font-size: 22px;">
							Have a question? Want to give feedback? Or maybe a specific resource in mind that you would like to be made? <br><br>
							Contact us using this form.
-->
					</p>
				</div>
			</div>
			
			</div>
		</div>
	</div>

<div class="page_tab">
	<div class="container">
		<h2 class="pb-5 heading">Frequently asked questions</h2>
		<div class="row">
			<div class="col-md-3 float-left faq_left">
			<nav class="menu tab">
				<ul>
					@foreach($faqMain as $sFaq)
						<li><a class="tablinks" onclick="openCity(event, '{{ $loop->iteration }}')" id="defaultOpen">{{ $sFaq->name }}</a></li>
					@endforeach
				</ul>
			</nav>
			</div>
			<div class="col-md-9 float-left faq_right">
				@foreach($faqMain as $sFaq)
					<div id="{{ $loop->iteration }}" class="tabcontent">
						@php
							$pacd = $loop->iteration;
						@endphp
						<div id="accordion{{ $loop->iteration }}">
							@if(count($sFaq->sub))
								@foreach($sFaq->sub as $qSub)
									<div class="card">
									    <div class="card-header bg-white">
									      <a class="card-link text-white" data-toggle="collapse" href="#collapseOne{{ $loop->iteration }}">
									        {!! $qSub->name !!}
									      </a>
									    </div>
									    <div id="collapseOne{{ $loop->iteration }}" class="collapse show" data-parent="#accordion{{ $pacd }}">
									      <div class="card-body">
									        {!! $qSub->value !!}
									      </div>
									    </div>
								  	</div>
								@endforeach
							@endif
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
	
</div>
</section>



<script>
function openCity(evt, catagori) {
	var faqRightPan = document.querySelector('.faq_right');
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
	//tabcontent[i].style.display = "none";
	tabcontent[i].style.opacity = "0";
	tabcontent[i].style.height = "0";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  //document.getElementById(catagori).style.display = "block";
  document.getElementById(catagori).style.opacity = "1";
  document.getElementById(catagori).style.height = "inherit";
  
  evt.currentTarget.className += " active";
  //faqRightPan.style.minHeight = ""+faqRightPan.offsetHeight +"px";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();



</script>
@stop
