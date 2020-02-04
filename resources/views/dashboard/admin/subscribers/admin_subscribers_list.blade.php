@extends('dashboard.layout.layout')
@section('page_title', 'Subscribers')
@section('content')

                  Subscribers
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
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left" id="subsdiv">
                  @include('dashboard.admin.subscribers.subscriber_pagination')
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
            url: ("{{ url('/admin/subscriber/del') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
            method: "GET",
            success:function(msg){
               console.log(msg);
               window.location.reload();
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

      $(document).on("click",".ban-link", function(){
          var eThis = $(this);
          if (eThis.attr('data-ban-ace') == '1') {
  	        $.ajax({
  	            url: ("{{ url('/admin/subscriber/ban') }}" + "/" + eThis.closest('tr').attr('data-row-id')),
  	            method: "GET",
  	            success:function(msg){
  	               	if(msg.prev == '1'){
  	                	eThis.closest('tr').find('td:last').find('a.ban-link').attr('data-ban-ace', '0');
  	                	eThis.closest('tr').find('td:last').find('a.ban-link').find('.fa-ban').css('color', 'red');
  	                	eThis.attr('data-ban-ace', '0');
  	               	}
  	            },
  	            error:function(jqXhr){
  	               var errors = jqXhr.responseJSON;
  	               $.each( errors.errors , function( key, value ) {
  	                   alert(value);
  	                   return false;
  	               });
  	            }
  	        });
          }
      });

      $("#search_box").keyup(function(event){
    		$.ajax({
          url: "{{ (route('admin.subscribers.list.pajax') . '?query=') }}" + $("#search_box").val(),
          method:"GET",
          success:function(data){
            $("#subsdiv").html(data);
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
            $("#subsdiv").html(data);
          }
        });
      });
   </script>
@endsection