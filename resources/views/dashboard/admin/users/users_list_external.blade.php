@extends('dashboard.layout.layout')
@section('page_title', "All " . $type . "s")
@section('content')

                  All {{ $type }}s
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
                  @include('dashboard.admin.users.user_pagination_external')
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
      });


      $(document).on("click", ".admin-notice", function(){
         var eThis = $(this);
         $.ajax({
            url: ("{{ url('/admin/user/notice') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
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


      $(document).on("click", "#create_user_btn", function(){
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


      $(document).on("click", "#contact-ok-btn", function(){
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


      $(document).on("keyup", "#search_box", function(event){
            $.ajax({
             url: "{{ (route('admin.users.list.pajax') . '?query=') }}" + $("#search_box").val(),
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


      $(document).on("change", ".u_verify", function(){
         var eThis = $(this);
         $.ajax({
            url: ("{{ url('/admin/user/verify') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
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
      });

      $(document).on("change", ".setM", function(){
         var eThis = $(this);
         $.ajax({
            url: ("{{ url('/admin/user/setm') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
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
      });


      $(document).on("change", ".setAd", function(){
         var eThis = $(this);
         $.ajax({
            url: ("{{ url('/admin/user/setad') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
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
      });

      

      $(document).on("click", ".del-usetM", function(){
         var r = confirm("Are you sure, you want to delete the user!");
          if (r == true) {
         var eThis = $(this);
         $.ajax({
            url: ("{{ url('/admin/user/u_setm') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
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

      $(document).on("click", ".del-usetad",function(){
        var r = confirm("Are you sure, you want to delete the user!");
          if (r == true) {
         var eThis = $(this);
         $.ajax({
            url: ("{{ url('/admin/user/u_setad') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
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

      
   </script>
@endsection