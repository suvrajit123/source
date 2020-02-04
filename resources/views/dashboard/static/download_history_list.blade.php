@extends('dashboard.layout.layout')
@section('page_title', 'Download History')
@section('content')

                  Download History
               </h1>
            </div>
         </div>
      </div>
   </section>
   <div class="myResourceSearch">
      <div class="container">
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="searchSecCus">
                  <!-- <input type="search" placeholder="Find a specific subscriber" id="search_box" value="{{ (isset($allInputs['query']) && !blank($allInputs['query'])) ? $allInputs['query'] : ''  }}"> -->
                  <select name="" id="rem-dwl-history" class="form-control selectDropdown">
                    <option value="">Remove History</option>
                    <option value="1">Last hour</option>
                    <option value="2">Last 24 hours</option>
                    <option value="3">Last 7 days</option>
                    <option value="4">Last 4 weeks</option>
                    <option value="5">All time</option>
                  </select>
                  <button id="del_btn" class="delBtn">Delete</button>
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
                @if(Session::has('success'))
                  <div class="row">
                    <div class="col-md-12">
                      <div class="alert alert-success">
                        {{Session::get('success') }}
                      </div>
                    </div>
                  </div>
                @endif
                  <div class="myResourceProSub">
                   <table class="table" id="resources-table">
                      <thead>
                         <tr class="table-active">
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Action</th>
                         </tr>
                      </thead>
                      <tbody>
                         @forelse($downloadHistory as $sDownloadHistory)
                            <tr data-row-id="{{ $sDownloadHistory->id }}">
                               <td><a href="{{ route('theme.singleresource',$sDownloadHistory->resource_id) }}" target="_blank"><img src="{{ !blank($sDownloadHistory->preview_attachment) ? asset('irh_assets/uploads/resource_preview/' . $sDownloadHistory->preview_attachment) : asset('irh_assets/images/resource_thum.jpg') }}" width="142" height="88" alt=""/></a></td>
                               <td>{{ date('d/m/Y', strtotime($sDownloadHistory->created_at)) }}</td>
                               <td><a href="javascript:void(0)" class="link del-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                            </tr>
                         @empty
                            <p>No Download History</p>
                         @endforelse
                      </tbody>
                   </table>
                </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</main>
@endsection
@section('page_script')
  <script>
    $(".del-link").click(function(){
       var eThis = $(this);
       $.ajax({
          url: ("{{ url('/user/download-history/del/') }}" + "/" + $(this).closest('tr').attr('data-row-id')),
          method: "GET",
          success:function(msg){
             eThis.closest('tr').remove();
          },
          error:function(jqXhr){
             
           }
       });
    });


    $("#del_btn").click(function(){
      var selval = $("#rem-dwl-history").val();
      if (selval == "") {
        alert("Select value!");
        return false;
      }
      var eThis = $(this);
       $.ajax({
          url: ("{{ url('/user/download-history/batch-del/') }}" + "/" + selval),
          method: "GET",
          success:function(msg){
             window.location.reload();
          },
          error:function(jqXhr){
             
           }
       });
    });
  </script>
@endsection