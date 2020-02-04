@extends('layouts.app')
@section('content')
<header id="author-profile-header" class="bg-blue py-5">
   <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="author-image mb-2">
          <img src="{{ ($author->profile_picture_path) ? asset($author->profile_picture_path) : asset('irh_assets/images/avatar.png') }}" alt="" class="img-thumbnail" width="120px">
        </div>
        <div class="author-name">
          <h4>{{ $author->full_name }} | <a href="#" data-toggle="modal" data-target="#contactAuthorModal" class="btn btn-danger btn-sm">Contact Author</a></h4>
        </div>
      </div>
    </div>
  </div>
</header>
@if(Session::has('success'))
  <div class="alert alert-success text-center">
    {{ Session::get('success') }}
  </div>
  @endif
<section id="author-details" class="py-5">
  <h4 class="heading text-center">About Author:</h4>
  <div class="py-4 text-center">
    <div class="container">
      <div class="row">
        <div class="col">
          <ul class="list-group">
            <li class="list-group-item">{{ $author->position ?? 'Works' }} at {{ $author->working_in ?? 'Private' }} </li>
            <li class="list-group-item">Specialized in {{ $author->subjects ?? 'N/A' }} </li>
            <li class="list-group-item">Lives in {{ $author->country ?? 'N/A' }} </li>
            <li class="list-group-item">
              {!! $author->about_me !!}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <hr>
 <h4 class="heading text-center">Author's Resources:</h4>
  <div class="relatedResources py-4 text-center">
    <div class="container">
      <div class="row">
        @forelse($author->resources as $res)
        <div class="col-md-3 mb-4">
          <div class="resourcebox hvr-glow">
            <div class="card">
              <a href="{{ route('theme.singleresource',$res) }}">
              <img class="card-img-top" src="{{ $res->preview_attachment_path }}" alt="Card image cap" style="position: relative;"></a>
               <span style="position: absolute;top: -1;right: 10px;" id="saveResourceContainer_{{ $res->id }}">
                  @auth
                  @if(!$res->isResourceSavedByLoggedInUser())
                  <a href="javascript:void(0);"  onclick='saveResource("{{ $res->id }}",false);'>
                  <img src="{{ asset('irh_assets/images/savelogo.png') }}" alt="" width="25px" data-toggle="tooltip" data-placement="top" title="save for later">
                  </a>
                  @else
                  <img src="{{ asset('irh_assets/images/savedlogo.png') }}" alt="" width="25px">
                  @endif
                  @endauth
                </span>
              <div class="card-body">
                <div class="pb-4"><img src="{{ asset('irh_assets/images/avatar.png') }}" alt="" width="30px" class="rounded-circle"><span class="ml-3">{{ $res->user->full_name }}</span></div>
                <a href="{{ route('theme.singleresource',$res) }}" class="text-muted"><h5 class="card-title">{{ $res->title }}</h5></a>
              </div>
              <div class="card-footer">
                <div style="display: grid;">
                  <div style="grid-column: 1;border-right: 1px solid #333;"><small>VIEWS</small><br>{{ $res->views }}</div>
                  <div style="grid-column: 2;border-right: 1px solid #333;"><small>DOWNLOADS</small><br>{{ $res->downloads }}</div>
                  <div style="grid-column: 3;"><small>LIKES</small><br>{{ $res->likes->count() }}</div>
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

<div class="modal fade" id="contactAuthorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Contact Author</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('theme.resources.authorprofile.mail') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="">Your Full Name: *</label>
            <input type="text" class="form-control" name="full_name" value="{{ (Auth::user())?Auth::user()->full_name : '' }}" required>
          </div>
          <div class="form-group">
            <label for="">Your Email: *</label>
            <input type="email" class="form-control" name="email" value="{{ (Auth::user())?Auth::user()->email : '' }}" required>
          </div>
          <div class="form-group">
            <label for="">Subject: *</label>
            <input type="text" class="form-control" name="subject" required>
          </div>
          <div class="form-group">
            <label for="">Message: *</label>
            <textarea name="message" rows="3" class="form-control" required></textarea>
          </div>          
          <div class="form-group">
            <input type="hidden" name="author_id" value="{{ $author->id }}">
            <input type="submit" class="btn bg-blue" value="Send Message">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@stop