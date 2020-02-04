@extends('dashboard.layout.layout')
@section('page_title', 'Profile')
@section('content')
			          Edit Profile
			       </h1>
			    </div>
		 	</div>
		</div>
   	</section>
	<section class="editProfileDasboartBody light_ass">
		<div class="container">
			<div class="row adminProfile">
				<div class="editProfile">
					<form action="{{ route('user.profile.update') }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 float-left">
						  <div class="editProfilePan_left">
						     <div class="form-row">
						        <div class="form-group col-md-6">
						           <label for="inputEmail4">First Name</label>
						           <input type="text" class="form-control" name="first_name" value="{{ Auth::user()->first_name }}">
						        </div>
						        <div class="form-group col-md-6">
						           <label for="inputPassword4">Last Name</label>
						           <input type="text" class="form-control" name="last_name" value="{{ Auth::user()->last_name }}">
						        </div>
						     </div>
						     <div class="form-group">
						        <label for="inputAddress">About Me</label>
						        <textarea class="form-control" name="about_me">{{ Auth::user()->about_me }}</textarea>
						     </div>
						     <div class="form-row">
						        <div class="form-group col-md-6">
						           <label for="user_position">Position</label>
						           <input type="text" class="form-control" id="user_position" name="user_position" value="{{ Auth::user()->position }}">
						        </div>
						        <div class="form-group col-md-6">
						           <label for="cur_work">Current work setting</label>
						           <input type="text" class="form-control" id="cur_work" name="cur_work" value="{{ Auth::user()->working_in }}">
						        </div>
						     </div>
						     <div class="form-row">
						        <div class="form-group col-md-6">
						           <label for="spl_subj">Specailist Subjects</label>
						           <input type="text" class="form-control" id="spl_subj" name="spl_subj" value="{{ Auth::user()->subjects }}">
						        </div>
						        <div class="form-group col-md-6">
						           <label for="country">Country of Residence</label>
						           <select name="country" id="country" class="form-control">
						           		@foreach($countries as $country)
						           			<option value="{{ $country->country_name }}" {{ $country->country_name == Auth::user()->country ? 'selected' : '' }}>{{ $country->country_name }}</option>
						           		@endforeach
						           </select>
						        </div>
						     </div>
						     <div class="form-row">
						        <div class="form-group col-md-6">
						           <label for="hobbies">Hobbies</label>
						           <input type="text" class="form-control" id="hobbies" name="hobbies" value="{{ Auth::user()->hobbies }}">
						        </div>
						        <div class="form-group col-md-6">
						           <label for="private_info">Privte Info</label>
						           <input type="text" class="form-control" name="private_info" id="private_info" value="{{ Auth::user()->private_info }}">
						        </div>
						     </div>
						  </div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 float-left">
							<div class="editProfilePan">
							 	<div class="photoUpload">
								    <div class="editProImg">
								       	<img src="{{ !blank(Auth::user()->profile_picture) ? asset('irh_assets/uploads/profile_pictures/' . Auth::user()->profile_picture) :  asset('irh_assets/images/profile_thum.png') }}" width="51" height="47" alt="" id="profile_img" />
								       	<p>Upload a profile image</p>
								       	<div class="box">
								          	<input type="file" name="profile_picture" id="file-2" class="inputfile inputfile-2" data-multiple-caption="{count} files selected" style="display: none;" />
								          	<label for="file-2"><span>Browse your computer</span></label>
								       	</div>
								    </div>
							 	</div>
							 	<div class="socialMedia">
						    		<h3>Connect Your Social Media</h3>
								    <dl>
										<dt><img src="{{ asset('irh_assets/images/social/linkdin.png') }}" width="45" height="40" alt=""/></dt>
										<dd><input type="text" name="linkedin" value="{{ Auth::user()->linkedin }}"></dd>
										<dt><img src="{{ asset('irh_assets/images/social/insta.png') }}" width="45" height="40" alt=""/></dt>
										<dd><input type="text" name="instagram" value="{{ Auth::user()->instagram }}"></dd>
										<dt><img src="{{ asset('irh_assets/images/social/twit.png') }}" width="45" height="40" alt=""/></dt>
										<dd><input type="text" name="twiter" value="{{ Auth::user()->twiter }}"></dd>
										<dt><img src="{{ asset('irh_assets/images/social/fb.png') }}" width="45" height="40" alt=""/></dt>
										<dd><input type="text" name="fb" value="{{ Auth::user()->fb }}"></dd>
										<dt><img src="{{ asset('irh_assets/images/social/icon.png') }}" width="45" height="40" alt=""/></dt>
										<dd><input type="text" name="new_social" value="{{ Auth::user()->new_social }}"></dd>
								    </dl>
							 	</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left">
						  	<button type="submit" class="btn btn-primary editProSaveBtn">Save</button>
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