@extends('dashboard.layout.layout')
@section('page_title', 'Tags & Categories')
@section('content')

                  Tags & Categories
               </h1>
            </div>
         </div>
      </div>
   </section>
<section class="editProfileDasboartBody light_ass">
  <div class="container">
    @if(Session::has('success'))
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-success">
            {{ Session::get('success') }}
          </div>
        </div>
      </div>
    @endif
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left profile_left">
        
        <div class="myResourcePro">
        <a href="javascript:void(0)" class="btn plusPopup tag_link_a"><i class="fa fa-plus" aria-hidden="true"></i></a>
          <div class="myResourceProSub">
            
            <table class="table" id="resources-table">
              <thead>
                <tr class="table-active">
                  <th scope="col">Tag Group</th>
                  <th scope="col">Name</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($tags as $tag)
                <tr data-row-id="{{ $tag->id }}">
                  <td>{{ $tag->tag_group }}</td>
                  <td>{{ $tag->name }}</td>
                  <td><a href="javascript:void(0)" class="link tag_link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a><a href="javascript:void(0)" class="link del-link-t"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 float-left profile_left">
        <div class="myResourcePro">
          <div class="myResourceProSub">
            <a href="javascript:void(0)" class="btn plusPopup cat_link_a"><i class="fa fa-plus" aria-hidden="true"></i></a>
            <table class="table" id="resources-table">
              <thead>
                <tr class="table-active">
                  <th scope="col">Category Ttile</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($resourceCategories as $resourceCategory)
                <tr data-row-id="{{ $resourceCategory->id }}">
                  <td>{{ $resourceCategory->title }}</td>
                  <td><a href="javascript:void(0)" class="link cat_link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a><a href="javascript:void(0)" class="link del-link-c"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</main>


<div class="modal fade" id="create_tags" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><label id="create_tags_label"></label> Tag
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <form action="{{ route('admin.tagCategories.save') }}" method="POST">
        <div class="modal-body">
          @csrf
          <div class="form-group">
            <label for="tag_group">Tag Group</label>
            <input id="tag_group" type="text" class="form-control" name="tag_group" required="" autofocus="">
          </div>
          <div class="form-group">
            <label for="tag_name">Tag Name</label>
            <input id="tag_name" type="text" class="form-control" name="tag_name" required="" autofocus="">
          </div>
          <input type="hidden" name="tag_id" id="tag_id">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="create_tag_btn">Create</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="create_categories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><label id="create_categories_label"></label> Category
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <form action="{{ route('admin.tagCategories.save') }}" method="POST">
        <div class="modal-body">
          @csrf
          <div class="form-group">
            <label for="category">Category</label>
            <input id="category" type="text" class="form-control" name="category" required="" autofocus="">
          </div>
          <input type="hidden" name="category_id" value="" id="category_id">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="create_category_btn">Create</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('page_script')
   <script>
      
      $(".tag_link_a").click(function(){
        var eThis = $(this);
        $("#tag_group").val("");
        $("#tag_name").val("");
        $("#tag_id").val("");
        $("#create_tags_label").html('Create');
        $("#create_tag_btn").html('Create');
        $("#create_tags").modal("show");
      });
      $(".tag_link").click(function(){
        var eThis = $(this);
        $("#tag_group").val(eThis.closest('tr').find('td').eq(0).html());
        $("#tag_name").val(eThis.closest('tr').find('td').eq(1).html());
        $("#tag_id").val(eThis.closest('tr').attr('data-row-id'));
        $("#create_tags_label").html('Update');
        $("#create_tag_btn").html('Update');
        $("#create_tags").modal("show");
      });

      $(".cat_link_a").click(function(){
        var eThis = $(this);
        $("#category").val("");
        $("#category_id").val("");
        $("#create_categories_label").html('Create');
        $("#create_category_btn").html('Create');
        $("#create_categories").modal("show");
      });
      $(".cat_link").click(function(){
        var eThis = $(this);
        $("#category").val(eThis.closest('tr').find('td').eq(0).html());
        $("#category_id").val(eThis.closest('tr').attr('data-row-id'));
        $("#create_categories_label").html('Update');
        $("#create_category_btn").html('Update');
        $("#create_categories").modal("show");
      });

      $(".del-link-c").click(function(){
        var eThis = $(this);
        var r = confirm("Are you sure, you want to delete the category!");
          if (r == true) {
           var eThis = $(this);
           $.ajax({
              url: ("{{ url('/admin/resource/tags-categories/del/c') }}" + "/" + eThis.closest('tr').attr('data-row-id')),
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

      $(".del-link-t").click(function(){
        var eThis = $(this);
        var r = confirm("Are you sure, you want to delete the tag!");
          if (r == true) {
           var eThis = $(this);
           $.ajax({
              url: ("{{ url('/admin/resource/tags-categories/del/t') }}" + "/" + eThis.closest('tr').attr('data-row-id')),
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