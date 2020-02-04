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
            @if($r_type == "unverified_user")
               <th scope="col">Verify</th>
            @elseif($r_type == "user")
               <th scope="col">Set Moderator</th>
               <!-- <th scope="col">Action</th> -->
            @elseif($r_type == "moderator")
               <th scope="col">Set Admin</th>
               <th scope="col">Action</th>
            @elseif($r_type == "admin")
               <th scope="col">Action</th>
            @endif
         </tr>
      </thead>
      <tbody>
         @forelse($users as $user)
            <tr data-row-id="{{ $user->id }}">
               <td scope="row">{{ $loop->iteration }}</td>
               <td>{{ $user->first_name }}</td>
               <td>{{ $user->last_name }}</td>
               <td>{{ $user->username }}</td>
               <td>{{ $user->email }}</td>
               <td>{{ $user->validate == '1' ? 'Active' : 'Unconfirmed' }}</td>
               <td>{{ ucfirst($user->role_name) }}</td>
               @if($r_type == "unverified_user")
                  <td>
                     <label class="iswitch">
                       <input type="checkbox" class="inpc u_verify">
                       <span class="islider round"></span>
                     </label>
                  </td>
               @elseif($r_type == "user")
                  <td>
                     <label class="iswitch">
                       <input type="checkbox" class="inpc setM">
                       <span class="islider round"></span>
                     </label>
                  </td>
               @elseif($r_type == "moderator")
                  <td>
                     <label class="iswitch">
                       <input type="checkbox" class="inpc setAd">
                       <span class="islider round"></span>
                     </label>
                  </td>
                  <td>
                     <a href="javascript:void(0)" class="link del-usetM"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                  </td>
               @elseif($r_type == "admin")
                  <td>
                     <a href="javascript:void(0)" class="link del-usetad"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                  </td>
               @endif
               
            </tr>
         @empty
            <p>No users</p>
         @endforelse
      </tbody>
   </table>
</div>
<div style="text-align: center;">{{ $users->appends(request()->except('page'))->links() }}</div>