@extends('dashboard.layout.layout')
@section('page_title', 'Pending Resource')
@section('content')

                  Pending resources
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
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left" id="pendiv">
                  @include('dashboard.admin.resources.pending_resource_pagination')
               </div>
            </div>
         </div>
      </div>
   </section>
</main>
@endsection

@section('page_script')
   <script>
      $(document).on("click",".approve-link", function(){
         var eThis = $(this);
         $.ajax({
            url: ("{{ url('/admin/pending-resource/app') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
            method: "GET",
            success:function(msg){
               alert(msg.data);
               window.location.reload();
               //eThis.closest('tr').remove();
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
             url: "{{ (route('admin.pendingResources.list.pajax') . '?query=') }}" + $("#search_box").val(),
             method:"GET",
             success:function(data){
               $("#pendiv").html(data);
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
                  $("#pendiv").html(data);
               }
            });
      });
   </script>
@endsection