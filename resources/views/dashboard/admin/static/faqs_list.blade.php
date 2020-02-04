@extends('dashboard.layout.layout')
@section('page_title', 'FAQ\'s')
@section('content')

                  FAQ's
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
            {{Session::get('success') }}
          </div>
        </div>
      </div>
    @endif

      <div class="row su_div" style="display: none;">
        <div class="col-md-12">
          <div class="alert alert-success">
            <label id="success_message"></label>
          </div>
        </div>
      </div>

    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 float-left profile_left">
        
        <div class="myResourcePro">
          <div class="myResourceProSub">
            <a href="javascript:void(0)" class="btn plusPopup faq_main_link_a"><i class="fa fa-plus" aria-hidden="true"></i></a>
            <table class="table" id="faq_main_table">
              <thead>
                <tr class="table-active">
                  <th scope="col">FAQ Group</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($faq_main as $s_faq)
                <tr data-row-id="{{ $s_faq->id }}">
                  <td><a href="javascript:void(0)" class="get_questions">{{ $s_faq->name }}</a></td>
                  <td><a href="javascript:void(0)" class="link faq_main_link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 float-left profile_left">
        <div class="myResourcePro">
          <div class="myResourceProSub">
            <a href="javascript:void(0)" class="btn plusPopup faq_sub_link_a"><i class="fa fa-plus" aria-hidden="true"></i></a>
            <table class="table" id="faq_sub_table">
              <thead>
                <tr class="table-active">
                  <th scope="col">Questions</th>
                </tr>
              </thead>
              <tbody>
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</main>


<div class="modal fade" id="create_faq_group" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><label id="create_faq_group_label"></label> Faq Group
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <form action="{{ route('admin.faq.update') }}" method="POST">
        <div class="modal-body">
          @csrf
          <div class="form-group">
            <label for="faq_group">FAQ Group</label>
            <input id="faq_group" type="text" class="form-control" name="faq_group" required="" autofocus="">
          </div>
          <input type="hidden" name="faq_group_id" id="faq_group_id">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="create_faq_group_btn">Create</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="create_faq_sub_question" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><label id="create_faq_sub_label"></label> Faq Question
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <form action="{{ route('admin.faq.update') }}" method="POST">
        <div class="modal-body">
          @csrf
          <div class="form-group conf">
            <label for="faq_main_group">Select Faq Group</label>
            <select id="faq_main_group" class="form-control" name="faq_main_group" required="">
            </select>
          </div>
          <div class="form-group">
            <label for="faq_sub_header">Question</label>
            <input id="faq_sub_header" type="text" class="form-control" name="faq_sub_header" required="" autofocus="">
          </div>
          <div class="form-group">
            <label for="faq_sub_header_ans">Answer</label>
            <textarea id="faq_sub_header_ans" class="form-control" name="faq_sub_header_ans" required="" cols="30" rows="10" style="height: 200px;"></textarea>
          </div>
          <input type="hidden" name="faq_sub_id" value="" id="faq_sub_id">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="create_faq_sub_btn">Create</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('page_script')
   <script>
      
      $(".faq_main_link_a").click(function(){
        var eThis = $(this);
        $("#faq_group").val("");
        $("#faq_group_id").val("");
        $("#create_faq_group_label").html('Create');
        $("#create_faq_group_btn").html('Create');
        $("#create_faq_group").modal("show");
      });
      $(".faq_main_link").click(function(){
        var eThis = $(this);
        $("#faq_group").val(eThis.closest('tr').find('td').eq(0).find('a').html().trim());
        $("#faq_group_id").val(eThis.closest('tr').attr('data-row-id'));
        $("#create_faq_group_label").html('Update');
        $("#create_faq_group_btn").html('Update');
        $("#create_faq_group").modal("show");
      });

      $(".faq_sub_link_a").click(function(){
        var eThis = $(this);
        $("#faq_sub_header").val("");
        $("#faq_sub_header_ans").val("");
        $("#faq_sub_id").val("");
        $("#create_faq_sub_label").html('Create');
        $("#create_faq_sub_btn").html('Create');
        $(".conf").css('display', 'block');
        $('#faq_main_group').empty();
        $("#faq_main_table tbody").find('tr').each(function(){
          var eThis = $(this);
          $('#faq_main_group').append(`<option value="${eThis.attr('data-row-id')}"> 
                                       ${eThis.find('td:first').find('a').html()} 
                                  </option>`); 
        });
        $("#create_faq_sub_question").modal("show");
      });


      $(document).on("click", ".faq_sub_link", function(){
        var eThis = $(this);
        console.log(eThis.closest('.row').find('.qst').html());
        $("#faq_sub_header").val(eThis.closest('.row').find('.qst').html().trim());
        $("#faq_sub_header_ans").val(eThis.closest('.card').find('.qans').find('.card-body').html().trim());
        $("#faq_sub_id").val(eThis.closest('tr').attr('data-row-id'));
        $("#create_faq_sub_label").html('Update');
        $("#create_faq_sub_btn").html('Update');
        $(".conf").css('display', 'none');
        $('#faq_main_group').empty();
        var dParent = eThis.closest('tr').attr('data-parent-id');
        $("#faq_main_table tbody").find('tr').each(function(){
          var eThis = $(this);
          $('#faq_main_group').append(`<option value="${eThis.attr('data-row-id')}" ${ dParent == eThis.attr('data-row-id') ? 'selected' : ''}> 
                                       ${eThis.find('td:first').find('a').html()} 
                                  </option>`); 
        });
        $("#create_faq_sub_question").modal("show");
      });


      $(".get_questions").click(function(){
        var eThis = $(this);
        console.log(eThis.closest('tr').attr('data-row-id'));
        $.ajax({
          url:("{{ url('/admin/faq/getquestions') }}" + "/" + eThis.closest('tr').attr('data-row-id')),
          method:"GET",
          success:function(data){
            $("#faq_sub_table tbody").empty();
            $("#faq_sub_table tbody").append(data);
          }
        });
      });


      $(document).on('click', '.del-link', function(){
        var eThis = $(this);
        var r = confirm("Are you sure, you want to delete the questions!");
        if (r == true) {
          $.ajax({
            url:("{{ url('/admin/faq/question/del') }}" + "/" + eThis.closest('tr').attr('data-row-id')),
            method:"GET",
            success:function(data){
              eThis.closest('tr').remove();
              if(data.success == '1'){
                $('.su_div').show();
                $("#success_message").html(data.message);
              }
            }
          });
        }
      })


      @if (\Session::has('FAQMainId'))
          $(document).ready(function(){
            $("#faq_main_table tbody").find('tr').each(function(){
              if($(this).attr('data-row-id') == {{\Session::get('FAQMainId')}}){
                $(this).css('background', 'rgb(254, 229, 157)');
              }
            });

            $.ajax({
              url:("{{ url('/admin/faq/getquestions') }}" + "/" + {{\Session::get('FAQMainId')}}),
              method:"GET",
              success:function(data){
                $("#faq_sub_table tbody").empty();
                $("#faq_sub_table tbody").append(data);
              }
            });
          });
      @else
          $(document).ready(function(){
              var faqGr = $("#faq_main_table tbody").find('tr').eq(0).attr('data-row-id');
              if(faqGr != undefined){
                $("#faq_main_table tbody").find('tr').each(function(){
                  if($(this).attr('data-row-id') == faqGr){
                    $(this).css('background', 'rgb(254, 229, 157)');
                  }
                });

                $.ajax({
                  url:("{{ url('/admin/faq/getquestions') }}" + "/" + faqGr),
                  method:"GET",
                  success:function(data){
                    $("#faq_sub_table tbody").empty();
                    $("#faq_sub_table tbody").append(data);
                  }
                });
              }
          });
      @endif
   </script>
@endsection