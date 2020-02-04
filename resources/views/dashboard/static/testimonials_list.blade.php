@extends('dashboard.layout.layout')
@section('page_title', 'Testimonials')
@section('content')

                  Testimonials
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
                  @include('dashboard.admin.static.testimonials_pagination')
               </div>
            </div>
         </div>
      </div>
   </section>
</main>

<div class="modal fade" id="edit_testimnial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
   aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold">Edit Testimonial</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body mx-3">
            <div class="md-form">
               <label data-error="wrong" data-success="right" for="testi-text">Content</label>
               <textarea type="text" id="testi-text" class="md-textarea form-control" rows="4"></textarea>
               <input type="hidden" id="testi-hidden" value="">
            </div>
         </div>
         <div class="modal-footer d-flex justify-content-center">
            <button class="btn btn-unique" id="testi-ok-btn">Update</button>
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
            url: ("{{ url('/admin/testimonial/del') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
            method: "GET",
            success:function(msg){
               eThis.closest('tr').remove();
            },
            error:function(jqXhr){
               
             }
         });
      });




      $('#edit_testimnial').on('show.bs.modal', function(event) {
        console.log("gf");
        var row_id = $(event.relatedTarget).data('edit-ace');
        $.ajax({
            url: ("{{ url('/admin/testimonial/edit/') }}" + "/" + row_id),
            method:"GET",
            success:function(data){
              console.log(data);
              $("#testi-text").val(data.data.testimonial_text);
              $("#testi-hidden").val(row_id);
            }
        });
      });

      $("#testi-ok-btn").click(function(){
        var eThis = $(this);
         $.ajax({
            url: ("{{ url('/admin/testimonial/update') }}" + "/" + $("#testi-hidden").val()),
            method: "POST",
            data:{message:$("#testi-text").val()},
            success:function(msg){
               alert('Testimonial updated!');
               $('#edit_testimnial').modal('hide'); 
            },
            error:function(jqXhr){
               
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
            $("#testm").html(data);
          }
        });
      });
   </script>
@endsection