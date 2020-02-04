@extends('dashboard.layout.layout')
@section('page_title', 'Donations')
@section('content')

                  Donations
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
                  <input type="search" placeholder="Find a specific subscriber" id="search_box" value="{{ (isset($allInputs['query']) && !blank($allInputs['query'])) ? $allInputs['query'] : ''  }}">
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
                  @include('dashboard.admin.donations.donation_pagination')
               </div>
            </div>
         </div>
      </div>
   </section>
</main>

@endsection

@section('page_script')
   <script>
      $("#search_box").keyup(function(event){
            $.ajax({
             url: "{{ (route('admin.donations.list.pajax') . '?query=') }}" + $("#search_box").val(),
             method:"GET",
             success:function(data){
               $("#dondiv").html(data);
             }
           });
      });

      $(document).on('click', '.pagination a', function(e){
            e.preventDefault();
            var page = $(this).attr('href');
            if ($("#search_box").val() != "") {
               page = page + "&query=" + $("#search_box").val();
            }

            $.ajax({
               url: page,
               method:"GET",
               success:function(data){
                  $("#dondiv").html(data);
               }
            });
      });
   </script>
@endsection