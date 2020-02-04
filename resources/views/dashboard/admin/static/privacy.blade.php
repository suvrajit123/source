@extends('dashboard.layout.layout')
@section('page_title', 'Privacy Policy')
@section('content')

               Edit policies
               </h1>
            </div>
         </div>
      </div>
   </section>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
   <div class="myResourceSearch">
      <div class="container">
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="searchSec">
                  <!-- <input type="search" placeholder="Find a specific subscriber" id="search_box" value="{{ (isset($allInputs['query']) && !blank($allInputs['query'])) ? $allInputs['query'] : ''  }}"> -->
               </div>
            </div>
         </div>
      </div>
   </div>
   <section class="editProfileDasboartBody light_ass">
      <div class="container">
         <div class="row adminProfile">
            <div class="myResourcePro">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left" id="dondiv">
                  @if (Session::has('message'))
                     <div class="alert alert-info">{{ Session::get('message') }}</div>
                  @endif
                  <form action="{{ route('admin.privacy.update') }}" method="post">
                     @csrf
                  <textarea name="content" id="summernote" cols="30" rows="10" style="height: 500px;">{{ $privacy->content }}</textarea>
                  <input type="submit" value="save" class="btn btn-unique savePolice">
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
</main>
@endsection

@section('page_script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({height: 350});
    });
</script>
@endsection