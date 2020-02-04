@extends('dashboard.layout.layout')
@section('page_title', 'My Resources')
@section('content')
                  My Resource
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
                  <input type="search" placeholder="Find a specific resource" id="search_box" value="{{ (isset($allInputs['query']) && !blank($allInputs['query'])) ? $allInputs['query'] : ''  }}">
               </div>
            </div>
         </div>
      </div>
   </div>
   <section class="editProfileDasboartBody light_ass">
      <div class="container">
         <div class="row adminProfile">
            <div class="myResourcePro">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left" id="myres">
                  @include('dashboard.admin.resources.my_resources_pagination')
               </div>
            </div>
         </div>
      </div>
   </section>
</main>
@endsection

@section('page_script')
   <script>

      $(document).on("click", ".del-link", function(){
         var eThis = $(this);
         $.ajax({
            url: ("{{ url('/admin/my-resources/del') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
            method: "GET",
            success:function(msg){
               console.log(msg);
               //window.location.reload();
               eThis.closest('tr').remove();
               //var tbodyLength = eThis.closest('tbody').find('tr').length;
               //console.log(tbodyLength);
            },
            error:function(jqXhr){
               /*var errors = jqXhr.responseJSON;
               $.each( errors.errors , function( key, value ) {
                   alert(value);
                   return false;
               });*/
             }
         });
      });
      
      $("#search_box").keyup(function(event){
            $.ajax({
             url: "{{ (route('admin.myResources.list.pajax') . '?query=') }}" + $("#search_box").val(),
             method:"GET",
             success:function(data){
               $("#myres").html(data);
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
                  $("#myres").html(data);
               }
            });
      });
   </script>
@endsection