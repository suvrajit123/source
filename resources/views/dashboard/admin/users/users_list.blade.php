@extends('dashboard.layout.layout')
@section('page_title')
{{ isset($rp) ? 'All Reported Users' : 'All Users' }}
@endsection
@section('content')

                  {{ isset($rp) ? 'All Reported Users' : 'All Users'}}
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
                  <input type="search" placeholder="Find a specific user" id="search_box" value="{{ (isset($allInputs['query']) && !blank($allInputs['query'])) ? $allInputs['query'] : ''  }}">
               </div>
            </div>
         </div>
      </div>
   </div>
   <section class="editProfileDasboartBody light_ass">
      <div class="container">
         <div class="row adminProfile">
            <div class="myResourcePro">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 float-left" id="userdiv">
                  @include('dashboard.admin.users.user_pagination')
               </div>
            </div>
         </div>
      </div>
   </section>
</main>


<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
   aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold">Write to user</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body mx-3">
            <div class="md-form">
               <label data-error="wrong" data-success="right" for="contact-text">Message</label>
               <textarea type="text" id="contact-text" class="md-textarea form-control" rows="4"></textarea>
               <input type="hidden" id="contact-hidden" value="">
            </div>
         </div>
         <div class="modal-footer d-flex justify-content-center">
            <button class="btn btn-unique" id="contact-ok-btn">Send</button>
         </div>
      </div>
   </div>
</div>

@endsection

@section('page_script')
   <script>
      $(document).on("click", ".del-link", function(){
        var r = confirm("Are you sure, you want to delete the user!");
          if (r == true) {
           var eThis = $(this);
           $.ajax({
              url: ("{{ url('/admin/user/del') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
              method: "GET",
              success:function(msg){
                 console.log(msg);
                 eThis.closest('tr').remove();
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

      $(document).on("click",".block-link", function(){
         var dataAceId = $(this).attr('data-block-ace');
         
         var alertMsg = "";
         if (dataAceId == 1) {
          alertMsg = "Are you sure, you want to block the user!";
         }
         else{
          alertMsg = "Are you sure, you want to unblock the user!";
         }
         var r = confirm(alertMsg);
          if (r == true) {
             var eThis = $(this);
             var id = eThis.closest('tr').attr('data-row-id');
             $.ajax({
                url: ("{{ url('/admin/user/block/') }}" + "/" + id),
                method: "GET",
                success:function(msg){
                   if(msg.prev == '1'){
                      $("#resources-table tbody").find('tr').each(function(){
                         if($(this).attr('data-row-id') == id){
                            $(this).find('td:last').find('a.block-link').attr('data-block-ace', '0');
                            $(this).find('td:last').find('a.block-link').find('.fa-ban').css('color', 'red');
                         }
                      });
                   }
                   else{
                      $("#resources-table tbody").find('tr').each(function(){
                         if($(this).attr('data-row-id') == id){
                            $(this).find('td:last').find('a.block-link').attr('data-block-ace', '1');
                            $(this).find('td:last').find('a.block-link').find('.fa-ban').css('color', '#37b1e5');
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
      });


      $(document).on("click", ".admin-notice", function(){
        var eThis = $(this);
        
        var r = confirm("Are you sure, you want to send notice to the user!");
        if (r == true) {
         
         $.ajax({
            url: ("{{ url('/admin/user/notice') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
            method: "GET",
            success:function(msg){
              var fd = 0;
              eThis.closest('td').prev('td').find(".notice").find('li').each(function(){
                if($(this).css('background-color') == 'rgb(204, 204, 204)'){
                  if(fd == 0){
                    $(this).css({'background-color':'rgb(255, 0, 0)', 'border':'2px solid #F00'});
                    fd = 1;
                  }
                }
              });
              alert("Notice sent successfuly!");
            },
            error:function(jqXhr){
               alert(jqXhr);
               return false;
             }
         });
        }
      });



      $('#contact-modal').on('show.bs.modal', function(event) {
         $("#contact-text").val("");
         var user_id = $(event.relatedTarget).data('user-id');
         $("#contact-hidden").val(user_id);
      });



      $('#create_user').on('show.bs.modal', function(event) {
         $('#first_name').val();
         $('#last_name').val();
         $('#email').val();
         $('#username').val();
         $('#password').val();
      });


      $("#create_user_btn").click(function(){
         $.ajax({
            url: "{{ url('/admin/user/create/') }}",
            method: "POST",
            data:$('#register_form').serialize(),
            success:function(msg){
               alert("User created successfuly!");
               $('#create_user').modal('hide'); 
               window.location.reload();
            },
            error:function(jqXhr){
               var errors = jqXhr.responseJSON;
               $.each( errors.errors , function( key, value ) {
                   alert(value);
                   return false;
               });
             }
         });
      });


      $("#contact-ok-btn").click(function(){
         $.ajax({
            url: ("{{ url('/admin/user/contact/') }}" + "/" + $("#contact-hidden").val()),
            method: "POST",
            data:{message:$("#contact-text").val()},
            success:function(msg){
               alert("Message sent successfuly!");
               $('#contact-modal').modal('hide'); 
            },
            error:function(jqXhr){
               var errors = jqXhr.responseJSON;
               $.each( errors.errors , function( key, value ) {
                   alert(value);
                   return false;
               });
             }
         });
      });


      $("#search_box").keyup(function(event){
            $.ajax({
            @if(Auth::user()->roles[0]->name == 'admin' )
             url: "{{ !isset($rp) ? (route('admin.users.list.pajax') . '?query=') : (route('admin.view.reportedUsers.list.pajax') . '?query=') }}" + $("#search_box").val(),
             @else
             url: "{{ !isset($rp) ? (route('user.users.list.pajax') . '?query=') : (route('user.view.reportedUsers.list.pajax') . '?query=') }}" + $("#search_box").val(),
             @endif
             method:"GET",
             success:function(data){
               $("#userdiv").html(data);
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
                  $("#userdiv").html(data);
               }
            });
      });

   </script>
@endsection