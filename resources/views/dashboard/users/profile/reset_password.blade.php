@extends('dashboard.layout.layout')
@section('page_title', 'Reset Password')
@section('content')
			          Reset Password
			       </h1>
			    </div>
		 	</div>
		</div>
   	</section>
	<section class="editProfileDasboartBody light_ass">
		<div class="container">
			<div class="row adminProfile">
				@if(Session::has('success'))
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-success">
								{{Session::get('success') }}
							</div>
						</div>
					</div>
				@endif
				@if(Session::has('error'))
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-error">
								{{Session::get('error') }}
							</div>
						</div>
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
				<div class="editProfile">
					<form action="{{ route('user.own.profile.password.save') }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left">
						  	<div class="editProfilePan_left">
							    <div class="form-row">
							        <div class="form-group col-md-6">
							           <label for="current_password">Old password</label>
							           <input type="password" class="form-control" id="current_password" name="current_password">
							        </div>
							    </div>
							    <div class="form-row">
							        <div class="form-group col-md-6">
							           <label for="new_password">New password</label>
							           <input type="password" class="form-control" id="new_password" name="new_password">
							        </div>
							    </div>
							    <div class="form-row">
							        <div class="form-group col-md-6">
							           <label for="password_confirmation">Confirm password</label>
							           <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
							        </div>
							    </div>
							    <div class="form-row">
							        <div class="form-group col-md-6">
							           <button type="submit" class="btn btn-primary editProSaveBtn" style="float: left !important;">Save</button>
							        </div>
							    </div>
						  	</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</main>
@endsection


@section('page_script')
	<script>
		var profile_img = "{{ asset('irh_assets/images/profile_thum.png') }}"
		function readURL(input) {
			var FileUploadPath = input.value;
			if (FileUploadPath != "") {
				var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf('.') + 1).toLowerCase();
				if (Extension == "gif" || Extension == "png" || Extension == "bmp" || Extension == "jpeg" || Extension == "jpg") {
					if (input.files && input.files[0]) {
						var reader = new FileReader();
						reader.onload = function(e) {
						  	$('#profile_img').attr('src', e.target.result);
						}
						reader.readAsDataURL(input.files[0]);
					}
				}
				else{
					alert("Photo only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
					$('#profile_img').attr('src', profile_img);
					input.value = null;
				}
			}
			else{
				$('#profile_img').attr('src', profile_img);
			}
		}

		$("#file-2").change(function() {
			readURL(this);
		});		
	</script>
@endsection