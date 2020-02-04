@extends('dashboard.layout.layout')
@section('page_title', 'Notifications')
@section('content')

                  Notifications
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
                         </tr>
                      </thead>
                      <tbody>
                         @forelse($notifications as $notification)
                            <tr>
                               <td>{!! $notification->message !!}</td>
                               <td>{{ date('d/m/Y', strtotime($notification->created_at)) }}</td>
                            </tr>
                         @empty
                            <p>No notification</p>
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