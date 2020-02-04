<html>
<head>
	@include('dashboard.includes.head')
</head>
<body>
	@include('dashboard.includes.header')
	<main class="main_section">
		<section class="adminDasboartTop">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<h1><span><a href="{{ Auth::user()->roles[0]->name == 'admin' ? url('/admin/dashboard') : url('/user/dashboard') }}">{!! ucfirst(Auth::user()->roles[0]->name) !!} Dasboard</a></span>
	@yield('content')
	@include('dashboard.includes.footer')
	<script src="{{ asset('irh_assets/vendor/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('irh_assets/vendor/popper/popper.min.js') }}" ></script>
	<script src="{{ asset('irh_assets/vendor/bootstrap/bootstrap.min.js') }}"></script>

	<script src="{{ asset('irh_assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>

	<script src="{{ asset('irh_assets/js/custom_js.js') }}"></script>
	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	@yield('page_script')
</body>
</html>