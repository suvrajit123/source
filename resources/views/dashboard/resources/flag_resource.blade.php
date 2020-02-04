@extends('dashboard.layout.layout')
@section('page_title', 'Flag Resource')
@section('content')

                  Flag resources
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
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left" id="flagdiv">
                  @include('dashboard.resources.flag_resource_pagination')
                  
               </div>
            </div>
         </div>
      </div>
   </section>
</main>


<div class="modal fade" id="ban-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
   aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold">Write to uploader</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body mx-3">
            <div class="md-form">
               <label data-error="wrong" data-success="right" for="ban-text">Reason for Ban</label>
               <textarea type="text" id="ban-text" class="md-textarea form-control" rows="4"></textarea>
               <input type="hidden" id="ban-hidden" value="">
            </div>
         </div>
         <div class="modal-footer d-flex justify-content-center">
            <button class="btn btn-unique" id="ban-ok-btn">Send</button>
         </div>
      </div>
   </div>
</div>

@endsection

@section('page_script')
   <script>
      $(document).on("click", ".del-link", function(){
         var eThis = $(this);
         $.ajax({
            url: ("{{ url('/user/published-resource/del') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
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

      $(document).on("click",".ban-link", function(){
         var eThis = $(this);
         if(eThis.attr('data-ban-ace') == '1'){
            $('#ban-modal').modal('show');
            $("#ban-hidden").val(eThis.closest('tr').attr('data-row-id'));
         }
         else{
            ban({message:'NA', unban:'1'}, $(this).closest('tr').attr('data-row-id'));
         }
      });


      $(document).on("click", "#ban-ok-btn", function(){
         ban({message:$("#ban-text").val()}, $("#ban-hidden").val());
      });

      function ban(data, id){
         $.ajax({
            url: ("{{ url('/user/published-resource/ban') }}" + "/" + id),
            method: "POST",
            data:data,
            success:function(msg){
               if(msg.prev == '1'){
                  $("#resources-table tbody").find('tr').each(function(){
                     if($(this).attr('data-row-id') == id){
                        $(this).find('td:last').find('a.ban-link').attr('data-ban-ace', '0');
                        $(this).find('td:last').find('a.ban-link').find('.fa-ban').css('color', 'red');
                     }
                  });
                  $('#ban-modal').modal('hide'); 
               }
               else{
                  $("#resources-table tbody").find('tr').each(function(){
                     if($(this).attr('data-row-id') == id){
                        $(this).find('td:last').find('a.ban-link').attr('data-ban-ace', '1');
                        $(this).find('td:last').find('a.ban-link').find('.fa-ban').css('color', '#37b1e5');
                     }
                  });
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




      $(document).on("click", ".admin-notice", function(){
         var eThis = $(this);
         $.ajax({
            url: ("{{ url('/user/flaged-resource/notice') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
            method: "GET",
            success:function(msg){
               /*if(msg.prev == '1'){
                  eThis.find('.fa').removeClass('fa-star').addClass('fa-star-o');
               }
               else{
                  eThis.find('.fa').removeClass('fa-star-o').addClass('fa-star');
               }*/
               alert("Notice sent successfuly!");
            },
            error:function(jqXhr){
               alert(jqXhr);
               return false;
             }
         });
      });


      $(document).on('click', '.remove-flag', function(){
         var eThis = $(this);
         $.ajax({
            url: ("{{ url('/user/flaged-resource/rem-flag/') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
            method: "GET",
            success:function(msg){
               /*if(msg.prev == '1'){
                  eThis.find('.fa').removeClass('fa-star').addClass('fa-star-o');
               }
               else{
                  eThis.find('.fa').removeClass('fa-star-o').addClass('fa-star');
               }*/
               alert('Resource removed from flag list!');
               eThis.closest('tr').remove();
            },
            error:function(jqXhr){
               alert(jqXhr);
               return false;
             }
         });
      });

      $('#ban-modal').on('show.bs.modal', function(event) {
         $("#ban-text").val("");
      });


      $("#search_box").keyup(function(event){
            $.ajax({
             url: "{{ (route('user.flagedResources.list.pajax') . '?query=') }}" + $("#search_box").val(),
             method:"GET",
             success:function(data){
               $("#flagdiv").html(data);
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
                  $("#flagdiv").html(data);
               }
            });
      });


   </script>
@endsection