@extends('dashboard.layout.layout')
@section('page_title', 'Bookmarked Resource')
@section('content')

                  Bookmarked Resource
               </h1>
            </div>
         </div>
      </div>
   </section>
   <section class="editProfileDasboartBody light_ass">
      <div class="container">
        <div class="row adminProfile">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="searchPanel">
              <div class="searchSec">
                <input type="search" placeholder="Find a specific resource" id="search_box" value="{{ isset($allParam['search']) && !blank($allParam['search']) ? $allParam['search'] : '' }}">
              </div>
              <div class="searchPanelSub">
                <div class="fielterSec">
                  <h3>Filter</h3>
                  <div class="fieldSec">
                    <div class="boxSearchFieldCus">
                      <select name="resource_type" id="filter_drop1" class="form-control">
                        <option value="">Filter By Resource Type</option>
                        @foreach($filters as $filter)
                        @if($filter->tag_group == 'Resource Type')
                        <option value="{{ $filter->id }}" {{ isset($allParam['res']) && $allParam['res'] == $filter->id ? 'selected' : '' }}>{{ $filter->name }}</option>
                        @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="fieldSec">
                    <div class="boxSearchFieldCus">
                      <select name="resource_type" id="filter_drop2" class="form-control">
                        <option value="">Filter By Age Group</option>
                        @foreach($filters as $filter)
                        @if($filter->tag_group == 'Age Group')
                        <option value="{{ $filter->id }}" {{ isset($allParam['age']) && $allParam['age'] == $filter->id ? 'selected' : '' }}>{{ $filter->name }}</option>
                        @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="shortBy">
                  <h3>Short By</h3>
                  <div class="fieldSec">
                    <div class="boxSearchFieldCus">
                      <select name="resource_type" id="filter_drop3" class="form-control">
                      <option value="relevance" {{ isset($allParam['sort']) && $allParam['sort'] == 'relevance' ? 'selected' : '' }}>Relevance</option>
                      <option value="newest" {{ isset($allParam['sort']) && $allParam['sort'] == 'newest' ? 'selected' : '' }}>Newest</option>
                      <option value="mdl" {{ isset($allParam['sort']) && $allParam['sort'] == 'mdl' ? 'selected' : '' }}>Most downloaded</option>
                      <option value="hr" {{ isset($allParam['sort']) && $allParam['sort'] == 'hr' ? 'selected' : '' }}>Highest rated</option>
                      </select>
                    </div>
                  </div>
                  <div class="fieldSecBtn">
                    <input type="button" style="width: 100%;
                      cursor: pointer;
                      display: inline-block;
                      color: #fff !important;
                      padding: 6px 12px;
                      font-size: 15px;
                      background: var(--newYello-color) !important;
                      border: none;
                      border-radius: 5px;
                      margin-bottom: 10px;" value="Refine" id="refine_btn"> <input type="reset" value="Clear All">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <br clear="all">
        <div class="container">
          <div class="row feture_resources">
            @forelse($resources as $res)
            <div class="col-md-3 col-sm-6 mb-4">
              <div class="resourcebox hvr-glow">
                <div class="card">
                  <div class="thum_resource_wrap">
                    <a href="{{ route('theme.singleresource',$res->id) }}" class="thum_resource">
                      <h3><span>{!! str_limit($res->title, 12) !!}</span></h3>
                      <img class="card-img-top" src="{{ asset($res->prev) }}" alt="Card image cap" style="position: relative;">
                    </a>
                    <span class="proTagSave" id="saveResourceContainer_{{ $res->id }}">
                    @auth
                    @if(\App\Resource::isResourceSavedByLoggedInUserInAuth($res->id))
                    <a href="javascript:void(0);"  onclick='saveResource("{{ $res->id }}",false);'>
                    <img src="{{ asset('irh_assets/images/savelogo.png') }}" alt="" width="25px" data-toggle="tooltip" data-placement="top" title="save for later">
                    </a>
                    @else
                    <img src="{{ asset('irh_assets/images/savedlogo.png') }}" alt="" width="25px">
                    @endif
                    @endauth
                    </span>
                  </div>
                  <div class="card-body">
                    <div class="pb-4 author_profile">
                      <img src="{{ asset('irh_assets/images/avatar.png') }}" alt="" width="30px" class="rounded-circle">
                      <span class="ml-3 author_name">{{ \App\User::getFullName($res->id) }}</span>
                    </div>
                    <a href="{{ route('theme.singleresource',$res->id) }}" class="text-muted">
                      <h5 class="card-title">{{ $res->title }}</h5>
                    </a>
                  </div>
                  <div class="card-footer">
                    <div style="display: grid;">
                      <ul>
                        <li><small class="icon_cl"><img src="{{ asset('irh_assets/images/view_i.png') }}" alt="" width="18px"></small><span>{{ $res->views }}</span></li>
                        <li><small class="icon_cl"><img src="{{ asset('irh_assets/images/like_i.png') }}" alt="" width="18px"></small><span>{{ $res->likes }}</span></li>
                        <li><small class="icon_cl"><img src="{{ asset('irh_assets/images/down_i.png') }}" alt="" width="18px"></small><span>{{ $res->downloads }}</span></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @empty
            <div class="col-md-4 offset-md-4">
              <h3>No Resource</h3>
            </div>
            @endforelse
          </div>
        </div>
      </div>
   </section>
</main>
@endsection
@section('page_script')
  <script>
    $("#search_box").keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            window.location = "{{ route('user.view.savedResource.list') }}" + "?search=" + $("#search_box").val();
        }
    });

    $("#refine_btn").click(function(){
      window.location = "{{ route('user.view.savedResource.list') }}" + "?res=" + $("#filter_drop1").val() + "&age=" + $("#filter_drop2").val() + "&sort=" + $("#filter_drop3").val();
    });
  </script>
@endsection