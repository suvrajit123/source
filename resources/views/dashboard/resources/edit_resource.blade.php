@extends('dashboard.layout.layout')
@section('page_title', 'Edit Resource')
@section('content')

                  Edit Resource
               </h1>
            </div>
         </div>
      </div>
   	</section>
	<section class="uploadResource">
		<div class="container">
			<div class="row">
				<div class="col-md-3 float-left upload_left">
					<nav class="menu tab">
						<h3>Progress bar</h3>
						<!-- <li class="active">Account <i class="fa fa-check rightCus" aria-hidden="true"></i></li> -->
						<ul class="leftNav">
							<li class="active litab" id="link0">Add file & describe </li>
							<li class="litab" id="link1">Choose Categories </li>
							<li class="litab" id="link2">Choose Licence </li>
							<li class="litab" id="link3">Preview and Submit  </li>
						</ul>
					</nav>
				</div>
				<div class="col-md-9 float-left upload_right resid" {{ isset($resource) ? ('data-res-id=' . $resource->id . '') : '' }}>
					<div class="tabFormNxtMain">
						<div class="tabFormNxt" data-li-atr="link0" style="display: block;">
							<form name="link0" data-form-id="link0" method="post" action="{{ route('user.create.resource.save') }}">
								@csrf
								<input type="hidden" name="form_name" value="link0">
								<div class="uploadResource_right">
									<div class="form-group">
										<label for="resource_title">What's the title of your resource?</label>
										<input type="text" name="resource_title" class="form-control" id="resource_title" placeholder="e.q Fiqh of fasting" value="{{ isset($resource) ? $resource->title : ''}}">
									</div>
									<div class="form-row">
									

										<div class="form-group marginBotNone col-md-12">
											<label for="inputCity">Select file to upload</label>
										</div>
										<div class="form-group col-md-6">
											<div class="photoUpload">
												<div class="editProImg">
													<img src="{{ asset('irh_assets/images/cloud_down.png') }}" alt="" width="51" height="47">
													<p>Drag & drop files here or</p>
<div class="box">
	<input type="file" name="resource_attachment" id="resource_attachment" class="inputfile inputfile-2" data-multiple-caption="{count} files selected">
	<label for="resource_attachment"><span>Browse your computer</span></label>
	<input type="hidden" name="resource_attachment_hidden" value="{{ isset($resource) ? $resource->resource_attachment : '' }}">
</div>

												</div>
											</div>
										</div>
										<div class="form-group col-md-6">
											<div class="photoUpload embed_link">
												<div class="editProImg">
													<a href="#" data-toggle="modal" data-target="#popupUrl"><img src="{{ asset('irh_assets/images/play.png') }}" alt="" width="51" height="47">
													<p>Embed a video from YouTube or Vimeo</p>
													<label class="filePopup"><span>Paste video embed link here</span></label>
												</a>
													
													<input type="hidden" name="embed_url" id="embed_url" value="{{ isset($resource) ? $resource->embed_link : '' }}">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="inputAddress2">Describe your resource.<br>
										<span>In the box below, describe what your resource is about and which age group it's aimed at in a few sentencs. Try to also give tips on how you've used the resource in classroom settings.</span></label>
										<textarea class="form-control" name="description" id="description">{{ isset($resource) ? $resource->description : ''}}</textarea>
									</div>
									<div class="form-row">
										<div class="form-group marginBotNone col-md-12">
											<label for="inputCity">Showcase you resource.<br>
											<span>Upload a picture of your resource that will be seen by user when they search for your resource. Choose an image that base depicts your material - it can even be something simple as a screenshot.</span>
											</label>
										</div>
										<div class="form-group col-md-6">
											<div class="photoUpload" id="preview_attachment_div">
												<div class="editProImg">
													<img src="{{ asset('irh_assets/images/jpg.png') }}" alt="" width="51" height="47">
													<p class="blue">Upload a cover image.<br>
													<span>This image will be displayed in search result.</span>
													</p>
													<div class="box">
														<input type="file" name="preview_attachment" id="preview_attachmentCheck" class="inputfile inputfile-2" data-multiple-caption="{count} files selected">
														<label for="preview_attachment"><span>Upload an image</span></label>
														<input type="hidden" name="preview_attachment_hidden" id="preview_attachment_hidden" 
														value="{{ isset($resource) ? $resource->preview_attachment : '' }}">
													</div>
												</div>
											</div>
										</div>
										<div class="form-group col-md-6">
											<div class="photoUpload" id="preview_attachment_div_gen" style="border: none;">
												<div class="editProImg">
													<img src="{{ asset('irh_assets/images/jpg_eye.png') }}" alt="" width="51" height="47">
													<p class="blue">Don't have a cover image to hand?<br>
													<span>Don't worry we'll automaticaly generate one for you.</span>
													</p>
													<div class="boxCheck">
														<input type="checkbox" name="generate_preview_attachment" value="gpi" id="generate_preview_attachment">
														<label for="generate_preview_attachment"><span>Generate an image</span></label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="tabFormNxt" data-li-atr="link1" style="display: none;">
							<form name="link1" data-form-id="link1" method="post" action="{{ route('user.create.resource.save') }}">
								@csrf
								<input type="hidden" name="form_name" value="link1">
								<div class="uploadResource_right">
									<div class="form-group" style="margin-bottom: 0;">
										<label for="inputAddress">Tag your resource.(Select five)
										<span>Choose tags thet best describe your resource. Adding tags help othere user find your resource more easily.</span></label>
										<div class="form-row">
											<div class="col">
												<div class="border_box">
													<h3>Pick an age group.
														<span>If your resource can be used in multiple age group, then select more than one.</span>
													</h3>
													<select class="form-control uploadResourceSelectCat" multiple name="age_group[]">
								                        @foreach($filters as $filter)
								                        @if($filter->tag_group == 'Age Group')
								                        <option value="{{ $filter->id }}" <?php 
								                        	if(isset($resource_tag) && in_array($filter->id, $resource_tag)){
							                        			echo 'selected';
								                        	}
								                        ?>>{{ $filter->name }}</option>
								                        @endif
								                        @endforeach
													</select>
												</div>
											</div>
											<div class="col">
												<div class="border_box">
													<h3>Pick resource type. (Select one)
													</h3>
													<select class="form-control uploadNew" name="resource_type[]" size="8">
								                        @foreach($filters as $filter)
								                        @if($filter->tag_group == 'Resource Type')
								                        <option value="{{ $filter->id }}" <?php 
								                        	if(isset($resource_tag) && in_array($filter->id, $resource_tag)){
							                        			echo 'selected';
								                        	}
								                        ?>>{{ $filter->name }}</option>
								                        @endif
								                        @endforeach
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="uploadResource_right">
									<div class="form-group" style="margin-bottom: 0;">
										<label for="inputAddress">Choose a catagory. (Select one)
										<span>Pick a catagory that best fits your resource. E.g. a PowerPoint on hajj will fall under the category 'Fiqh'.</span></label>
										<input type="radio" class="custom-control-input" id="defaultChecked" name="defaultExampleRadios" checked>
										<ul class="select_catagory">
											@foreach($resourceCategory as $sResourceCategory)
											<li>
												<label><input type="radio" name="category_id" value="{{ $sResourceCategory->id }}" {{ isset($resource) && $resource->category_id == $sResourceCategory->id ? 'checked' : '' }}>
												<img src="{{ asset('irh_assets/images/categories/' . strtolower($sResourceCategory->title) . '.png') }}"></label>
												<h5>{{ $sResourceCategory->title }}</h5>
											</li>
											@endforeach
										</ul>
									</div>
								</div>
							</form>
						</div>
						<div class="tabFormNxt" data-li-atr="link2" style="display: none;">
							<form name="link2" data-form-id="link2" method="post" action="{{ route('user.create.resource.save') }}">
								@csrf
								<input type="hidden" name="form_name" value="link2">
								<div class="uploadResource_right">
									<div class="form-group" style="margin-bottom: 0;">
										<label for="inputAddress">Retain control over your resource.
										<span>Pick a licence from below the control how others will use and share your work.</span></label>
										<div class="row">
											<div class="col-md-6 float-left">
												<div class="licenceBox">
													<label>
														<input type="radio" name="licence" value="Attribution Licence" {{ isset($resource) && $resource->license_type == 'Attribution Licence' ? 'checked' : '' }}>
														<h4>Atribution Licence </h4>
													</label>
													<!-- <h4 class="activeLicenTitle">Attribution Licence</h4> -->
													<div class="description">
														<p>I want credit for my work and to freely share my resource with no restriction on what others can do with it.</p>
													</div>
												</div>
											</div>
											<div class="col-md-6 float-left">
												<div class="licenceBox">
													<label>
														<input type="radio" name="licence" value="ShareAlike Licence" {{ isset($resource) && $resource->license_type == 'ShareAlike Licence' ? 'checked' : '' }}>
														<h4>ShareAlike Licence</h4>
													</label>
													<!-- <h4>Attribution Licence</h4> -->
													<div class="description">
													<p>I want credit for my work and to freely share my resource as long as others also freely share anything they make using it.</p>
													</div>
												</div>
											</div>
											<div class="col-md-6 float-left">
												<div class="licenceBox">
													<label>
														<input type="radio" name="licence" value="NoDerivatives Licence" {{ isset($resource) && $resource->license_type == 'NoDerivatives Licence' ? 'checked' : '' }}>
														<h4>NoDerivatives Licence</h4>
													</label>
													<!-- <h4>Attribution Licence</h4> -->
													<div class="description">
														<p>I want credit for my work and to freely share my resource but insist that others don't share any changes they've made to it.</p>
													</div>
												</div>
											</div>
											<div class="col-md-6 float-left">
												<div class="licenceBox">
													<label>
														<input type="radio" name="licence" value="Something Else" {{ isset($resource) && $resource->license_type == 'Something Else' ? 'checked' : '' }}>
														<h4>Something else</h4>
													</label>
													<!-- <h4>Attribution Licence</h4> -->
													<div class="description">
														<!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
															Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
															when an unknown printer took.
														</p> -->
													</div>
												</div>
											</div>
										</div>
									</div>
									<br clear="all">
								</div>
							</form>
						</div>
						<div class="tabFormNxt" data-li-atr="link3" style="display: none;">
							<form name="link3" data-form-id="link3" method="post" action="{{ route('user.create.resource.save') }}">
								@csrf
								<input type="hidden" name="form_name" value="link3">
								<div class="uploadResource_right">
									<div class="form-group" style="margin-bottom: 0;">
										<label for="inputAddress">Terms and Conditions
										<span>Pickup a licence from that controls how others will use and share your work.</span></label>
										<div class="row">
											<div class="col-md-12 float-left">
												<div class="my-custom-scrollbar  scrollSec">
												<h1>Introduction</h1>

<p>These   terms and conditions apply between you, the User of this Website   (including any sub-domains, unless expressly excluded by their own terms   and conditions), and Islamic Resource Hub, the owner and operator of   this Website. Please read these terms and conditions carefully, as they   affect your legal rights. Your agreement to comply with and be bound by   these terms and conditions is deemed to occur upon your first use of the   Website. If you do not agree to be bound by these terms and conditions,   you should stop using the Website immediately.</p>
<p>In these terms and conditions, <strong>User</strong>or <strong>Users</strong>means   any third party that accesses the Website and is not either (i)   employed by Islamic Resource Hub and acting in the course of their   employment or (ii) engaged as a consultant or otherwise providing   services to Islamic Resource Hub and accessing the Website in connection   with the provision of such services.</p>
<p>You   must be at least 18 years of age to use this Website. By using the   Website and agreeing to these terms and conditions, you represent and   warrant that you are at least 18 years of age.</p>
<h2>Intellectual property and acceptable use</h2>
<ol>
  <li>All   Content included on the Website, unless uploaded by Users, is the   property of Islamic Resource Hub, our affiliates or other relevant third   parties. In these terms and conditions, Content means any text,   graphics, images, audio, video, software, data compilations, page   layout, underlying code and software and any other form of information   capable of being stored in a computer that appears on or forms part of   this Website, including any such content uploaded by Users. By   continuing to use the Website you acknowledge that such Content is   protected by copyright, trademarks, database rights and other   intellectual property rights. Nothing on this site shall be construed as   granting, by implication, estoppel, or otherwise, any license or right   to use any trademark, logo or service mark displayed on the site without   the owner's prior written permission</li>
  <li>You may, for your own personal, non-commercial use only, do the following: </li>
  <ol type="a">
    <li>retrieve, display and view the Content on a computer screen</li>
    <li>download   and store the Content in electronic form on a disk (but not on any   server or other storage device connected to a network)</li>
    <li>print one copy of the Content</li>
  </ol>
  <li> You   must not otherwise reproduce, modify, copy, distribute or use for   commercial purposes any Content without the written permission of   Islamic Resource Hub.</li>
  <li>You   acknowledge that you are responsible for any Content you may submit via   the Website, including the legality, reliability, appropriateness,   originality and copyright of any such Content. You may not upload to,   distribute or otherwise publish through the Website any Content that (i)   is confidential, proprietary, false, fraudulent, libellous, defamatory,   obscene, threatening, invasive of privacy or publicity rights,   infringing on intellectual property rights, abusive, illegal or   otherwise objectionable; (ii) may constitute or encourage a criminal   offence, violate the rights of any party or otherwise give rise to   liability or violate any law; or (iii) may contain software viruses,   political campaigning, chain letters, mass mailings, or any form of   "spam." You may not use a false email address or other identifying   information, impersonate any person or entity or otherwise mislead as to   the origin of any content. You may not upload commercial content onto   the Website.</li>
  <li>You   represent and warrant that you own or otherwise control all the rights   to the Content you post; that the Content is accurate; that use of the   Content you supply does not violate any provision of these terms and   conditions and will not cause injury to any person; and that you will   indemnify Islamic Resource Hub for all claims resulting from Content you   supply.</li>
</ol>
<h2>Prohibited use</h2>
<ol start="6">
  <li><span dir="LTR"> </span>You may not use the Website for any of the following purposes: </li>
  <ol type="a">
    <li>in   any way which causes, or may cause, damage to the Website or interferes   with any other person's use or enjoyment of the Website;</li>
    <li>in   any way which is harmful, unlawful, illegal, abusive, harassing,   threatening or otherwise objectionable or in breach of any applicable   law, regulation, governmental order;</li>
    <li>making, transmitting or storing electronic copies of Content protected by copyright without the permission of the owner.</li>
  </ol>
</ol>
<h2>Registration</h2>
<ol start="7">
  <li>You must ensure that the details provided by you on registration or at any time are correct and complete.</li>
  <li>You   must inform us immediately of any changes to the information that you   provide when registering by updating your personal details to ensure we   can communicate with you effectively.</li>
  <li>We   may suspend or cancel your registration with immediate effect for any   reasonable purposes or if you breach these terms and conditions.</li>
  <li>You   may cancel your registration at any time by informing us in writing to   the address at the end of these terms and conditions. If you do so, you   must immediately stop using the Website. Cancellation or suspension of   your registration does not affect any statutory rights.</li>
</ol>
<h2>Password and security</h2>
<ol start="11">
  <li>When   you register on this Website, you will be asked to create a password,   which you should keep confidential and not disclose or share with   anyone.</li>
  <li>If   we have reason to believe that there is or is likely to be any misuse   of the Website or breach of security, we may require you to change your   password or suspend your account.</li>
</ol>
<h2>Links to other websites</h2>
<ol start="13">
  <li>This   Website may contain links to other sites. Unless expressly stated,   these sites are not under the control of Islamic Resource Hub or that of   our affiliates.</li>
  <li>We   assume no responsibility for the content of such Websites and disclaim   liability for any and all forms of loss or damage arising out of the use   of them.</li>
  <li>The   inclusion of a link to another site on this Website does not imply any   endorsement of the sites themselves or of those in control of them.</li>
</ol>
<h2>Availability of the Website and disclaimers</h2>
<ol start="16">
  <li>Any online facilities, tools, services or information that Islamic Resource Hub makes available through the Website (the <strong>Service</strong>)   is provided "as is" and on an "as available" basis. We give no warranty   that the Service will be free of defects and/or faults. To the maximum   extent permitted by the law, we provide no warranties (express or   implied) of fitness for a particular purpose, accuracy of information,   compatibility and satisfactory quality. Islamic Resource Hub is under no   obligation to update information on the Website.</li>
  <li>Whilst   Islamic Resource Hub uses reasonable endeavours to ensure that the   Website is secure and free of errors, viruses and other malware, we give   no warranty or guaranty in that regard and all Users take   responsibility for their own security, that of their personal details   and their computers.</li>
  <li>Islamic Resource Hub accepts no liability for any disruption or non-availability of the Website.</li>
  <li>Islamic   Resource Hub reserves the right to alter, suspend or discontinue any   part (or the whole of) the Website including, but not limited to, any   products and/or services available. These terms and conditions shall   continue to apply to any modified version of the Website unless it is   expressly stated otherwise.</li>
</ol>
<h2>Limitation of liability</h2>
<ol start="20">
  <li>Nothing   in these terms and conditions will: (a) limit or exclude our or your   liability for death or personal injury resulting from our or your   negligence, as applicable; (b) limit or exclude our or your liability   for fraud or fraudulent misrepresentation; or (c) limit or exclude any   of our or your liabilities in any way that is not permitted under   applicable law.</li>
  <li>We will not be liable to you in respect of any losses arising out of events beyond our reasonable control.</li>
  <li>To the maximum extent permitted by law, Islamic Resource Hub accepts no liability for any of the following: </li>
  <ol type="a">
    <li>any   business losses, such as loss of profits, income, revenue, anticipated   savings, business, contracts, goodwill or commercial opportunities;</li>
    <li>loss or corruption of any data, database or software;</li>
    <li>any special, indirect or consequential loss or damage.</li>
  </ol>
</ol>
<h2>General</h2>
<ol start="23">
  <li>You   may not transfer any of your rights under these terms and conditions to   any other person. We may transfer our rights under these terms and   conditions where we reasonably believe your rights will not be affected.</li>
  <li>These   terms and conditions may be varied by us from time to time. Such   revised terms will apply to the Website from the date of publication.   Users should check the terms and conditions regularly to ensure   familiarity with the then current version.</li>
  <li> These   terms and conditions contain the whole agreement between the parties   relating to its subject matter and supersede all prior discussions,   arrangements or agreements that might have taken place in relation to   the terms and conditions.</li>
  <li>The   Contracts (Rights of Third Parties) Act 1999 shall not apply to these   terms and conditions and no third party will have any right to enforce   or rely on any provision of these terms and conditions.</li>
  <li>If   any court or competent authority finds that any provision of these   terms and conditions (or part of any provision) is invalid, illegal or   unenforceable, that provision or part-provision will, to the extent   required, be deemed to be deleted, and the validity and enforceability   of the other provisions of these terms and conditions will not be   affected.</li>
  <li>Unless   otherwise agreed, no delay, act or omission by a party in exercising   any right or remedy will be deemed a waiver of that, or any other, right   or remedy.</li>
  <li>This   Agreement shall be governed by and interpreted according to the law of   England and Wales and all disputes arising under the Agreement   (including non-contractual disputes or claims) shall be subject to the   exclusive jurisdiction of the English and Welsh courts.</li>
</ol>
<h2>Islamic Resource Hub details</h2>
<p>You can contact Islamic Resource Hub by email on admin@islamicresourcehub.com.</p>
<h2>Attribution</h2>
<ol start="31">
  <li>These terms and conditions were created using a document from Rocket Lawyer (https://www.rocketlawyer.co.uk).</li>
</ol>
												</div>
											</div>
										</div>
										<div class="form-check" style="margin-top: 15px;">
											<input class="form-check-input" name="terms" type="checkbox" value="1" id="defaultCheck2" {{ isset($resource) && $resource->resource_status != 'draft' ? 'checked' : '' }}>
											<label class="form-check-label" for="defaultCheck2">
											I agree to the Terms and Condition and privacy policy
											</label>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left">
					<button type="submit" class="btn btn-primary editProSaveBtn saveNxtBtn" id="saveNxtBtn">Save & Next</button>
				</div>
			</div>
		</div>
	</section>

	<div class="modal fade" id="popupUrl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Youtube URL
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
			            <label for="youtube_url" class="col-form-label">Enter youtube Url:</label>
			            <input type="text" class="form-control" id="youtube_url">
		          	</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        			<button type="button" class="btn btn-primary" id="yt_modal_btn">Add URL</button>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection

@section('page_script')
   <script>
      	$("#yt_modal_btn").click(function(){
			var video, results, url;
			url = $("#youtube_url").val();
			if (url === null) {
	            return '';
	        }
	        results = url.match('[\\?&]v=([^&#]*)');
	        video   = (results === null) ? url : results[1];
	        var iurl = 'http://img.youtube.com/vi/' + video + '/2.jpg';
	        $(".embed_link").css({'background-image': "url(" + iurl + ")", 'background-repeat': 'no-repeat', "background-size":"100% 100%"});
	        $("#embed_url").val(url);
	        $("#popupUrl").modal('hide');
      	});



      	$(".litab").click(function(){
      		$(".leftNav").find("li").each(function(){
      			$(this).removeClass('active');
      			$(".tabFormNxtMain").find('div[data-li-atr="' + $(this).attr('id') + '"]').css({'display':'none'});
      		});
      		$(".tabFormNxtMain").find('div[data-li-atr="' + $(this).attr('id') + '"]').css({'display':'block'});
      		$("#" + $(this).attr('id')).addClass('active');
      	});


      	$("#saveNxtBtn").click(function(){
      		var liID = "";
      		var nextLi = "";
      		$(".leftNav").find("li").each(function(){
      			if($(this).hasClass('active')){
      				liID = $(this).attr('id');
      				if(liID != 'link3'){
      					nextLi = $(this).next('li').attr('id');
      				}
      				else{
      					nextLi = "link3";
      				}
      			}
      			$(this).removeClass('active');
      			$(".tabFormNxtMain").find('div[data-li-atr="' + liID + '"]').css({'display':'none'});
      		});
      		sendData(liID, nextLi);
      	});



      	function sendData(formname, nextLi){
      		var form = $("form[data-form-id='" + formname + "']")[0];
		    var formData = new FormData(form);

		    var attr = $('.resid').attr('data-res-id');

			if(attr !== undefined) {
			    formData.append('resid', $('.resid').attr('data-res-id'));
			}
		    $.ajax({
		    	url:$("form[data-form-id='" + formname + "']").attr('action'),
		    	data:formData,
		    	method:"POST",
		    	cache: false,
		        contentType: false,
		        processData: false,
		    	success:function(data){
		    		if(data.success == '1'){
		    			if(data.hasOwnProperty('status')){
		    				alert("Resource data has been sent for approval!");
		    			}
		    			else if(data.hasOwnProperty('pos')){
		    				alert("Resource saved as draft!");
		    				$("#" + formname).addClass('whitBack');
		    			}
		    			else{
				    		$(".tabFormNxtMain").find('div[data-li-atr="' + nextLi + '"]').css({'display':'block'});
		      				$("#" + nextLi).addClass('active');
		      				if(data.hasOwnProperty('resobj')){
		      					$(".resid").attr('data-res-id', data.resobj.id);
		      				}
		    			}
		    			if(data.hasOwnProperty('addclass')){
		    				$("#" + formname).addClass('whitBack');
		    			}
		    		}
		    		else{

		    		}
		    	},
		    	error:function(){
		    		$(".tabFormNxtMain").find('div[data-li-atr="' + formname + '"]').css({'display':'block'});
      				$("#" + formname).addClass('active');
		    	}
		    });
      	}
  		$(document).ready(function() {
			$(window).keydown(function(event){
				if(event.keyCode == 116) {
					event.preventDefault();
					return false;
				}
			});
		});
   </script>
@endsection