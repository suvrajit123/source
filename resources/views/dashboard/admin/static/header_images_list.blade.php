@extends('dashboard.layout.layout')
@section('page_title', 'Header Images')
@section('content')

                  Header Images
               </h1>
            </div>
         </div>
      </div>
   </section>
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
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left" id="testm">
                @if(Session::has('success'))
                  <div class="row">
                    <div class="col-md-12">
                      <div class="alert alert-success">
                        {{Session::get('success') }}
                      </div>
                    </div>
                  </div>
                @endif
                  <div class="myResourceProSub">
                   <table class="table" id="resources-table">
                      <thead>
                         <tr class="table-active">
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Image</th>
                            <th scope="col">Upload</th>
                            <th scope="col">Action</th>
                         </tr>
                      </thead>
                      <tbody>
                         @forelse($headerImages as $headerImage)
                            <tr data-row-id="{{ $headerImage->id }}">
                               <td scope="row">{{ $loop->iteration }}</td>
                               <td>{{ $headerImage->name }}</td>
                               <td><img src="{{ !blank($headerImage->value) ? asset(('irh_assets/images/' . $headerImage->value)) : asset(('irh_assets/images/dummypreview.png')) }}" alt="" width="142" height="88"></td>
                               <form action="{{ route('admin.headerImage.upload') }}" method="post" enctype="multipart/form-data">
                                @csrf
                               <td>
                                  <!-- <input type="file" name="image" class="form-control uplctl">
                                  <input type="hidden" name="img_key" value="{{ $headerImage->name }}"> -->
    <div class="custom-file mb-3 uploadFileCus">
      <input type="file" class="custom-file-input uplctl" name="image">
      <input type="hidden" name="img_key" value="{{ $headerImage->name }}">
      <label class="custom-file-label" for="customFile">Choose file</label>
    </div>
                              </td>
                               <td>
                                  <input type="submit" class="btn btn-success uploadBtnCus" value="Upload">
                               </td>
                               </form>
                            </tr>
                         @empty
                            <p>No images</p>
                         @endforelse
                      </tbody>
                   </table>
                </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</main>
@endsection

@section('page_script')

   <script>

      // Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});


      function readURL(input) {
        var FileUploadPath = input.value;
        if (FileUploadPath != "") {
          var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf('.') + 1).toLowerCase();
          if (Extension == "gif" || Extension == "png" || Extension == "bmp" || Extension == "jpeg" || Extension == "jpg") {
          }
          else{
            alert("Photo only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
            input.value = null;
          }
        }
        else{
        }
      }

      $(".uplctl").change(function() {
        readURL(this);
      });   
   </script>
@endsection