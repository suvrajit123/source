<style>
   ul.notice{
     list-style-type:none;
   }

   ul.notice li {
      // color: white;
      position:relative ;
      display:inline;
   }
   ul.notice li::before {
      content: '';
      width: 10px;
      height: 10px;
      background: white;
      position: absolute;
      top: 3px;
      padding-right: 3px;
   }
</style>
<div class="myResourceProSub">
   @if(Auth::user()->roles[0]->name == 'admin')
   @if(!isset($rp))
      <a href="#" class="btn plusPopup" data-toggle="modal" data-target="#create_user"><i class="fa fa-plus" aria-hidden="true"></i></a>
   @endif
   <div class="modal fade" id="create_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Create User
               </h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <form action="{{ route('register') }}" method="POST" id="register_form">
               <div class="modal-body">
                  @csrf
                  <div class="form-group">
                     <input id="first_name" type="text" class="form-control" name="first_name"  placeholder="First Name" required autofocus>
                  </div>
                  <div class="form-group">
                     <input id="last_name" type="text" class="form-control" name="last_name"  placeholder="Last Name" required autofocus>
                  </div>
                  <div class="form-group">
                     <input id="email" type="email" class="form-control" name="email" placeholder="Email" required>
                  </div>
                  <div class="form-group">
                     <input id="username" type="text" class="form-control" name="username" placeholder="Username" required>
                  </div>
                  <div class="form-group">
                     <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id="create_user_btn">Create</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   @endif
   <table class="table" id="resources-table">
      <thead>
         <tr class="table-active">
            <th scope="col">#</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Status</th>
            <th scope="col">Type</th>
            <th scope="col">Notice</th>
            <th scope="col">Action</th>
         </tr>
      </thead>
      <tbody>
         @forelse($users as $user)
            <tr data-row-id="{{ $user->id }}">
               <td scope="row">{{ $loop->iteration }}</td>
               <td><a href="{{ route('theme.resources.authorprofile', $user->id) }}" target="_blank"><div class="tdUser"><i class="fa fa-user-circle" aria-hidden="true"></i></div><span class="titleUser">{{ $user->first_name }}</span></a></td>
               <td>{{ $user->last_name }}</td>
               <td>{{ $user->username }}</td>
               <td>{{ $user->email }}</td>
               <td>{{ $user->validate == '1' ? 'Active' : 'Unconfirmed' }}</td>
               <td>{{ ucfirst($user->role_name) }}</td>
               <td class="td_center">
                  <ul class="notice">
                     @for($i=1; $i <= 3; $i++)
                        @if($user->notice >= $i)
                           <li style="background-color:#F00; border: 2px solid #F00;"></li>
                        @else
                           <li style="background-color:#CCC; border: 2px solid #CCC;"></li>
                        @endif
                     @endfor
                  </ul>
               </td>
               <td>
                  <a href="javascript:void(0)" class="link" data-toggle="modal" data-target="#contact-modal" data-user-id="{{ $user->id }}"><i class="fas fa-envelope"></i></a> 
                  <a href="javascript:void(0)" class="link admin-notice"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a> 
                  <a href="javascript:void(0)" class="link block-link" data-block-ace="{{ $user->block }}"><i class="fa fa-ban" aria-hidden="true" style=""></i></a>
                  <a href="javascript:void(0)" class="link del-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
               </td>
            </tr>
         @empty
            <p>No users</p>
         @endforelse
      </tbody>
   </table>
</div>
<div style="text-align: center;">{{ $users->appends(request()->except('page'))->links() }}</div>