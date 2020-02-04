<div class="myResourceProSub">
   <table class="table" id="resources-table">
      <thead>
         <tr class="table-active">
            <th scope="col">#</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Email</th>
            <th scope="col">Amount</th>
            <th scope="col">Date</th>
            <th scope="col">Type</th>
            <th scope="col">Transaction #</th>
         </tr>
      </thead>
      <tbody>
         @forelse($donations as $donation)
            <tr data-row-id="{{ $donation->id }}">
               <td scope="row">{{ $loop->iteration }}</td>
               <td><a 
                  <?php
                     if(\App\User::isUserExist($donation->email) == '0'){
                        ?>
                           href="javascript:void(0)"
                        <?php
                     }
                     else{
                        ?>
                           href="{{ route('theme.resources.authorprofile', \App\User::isUserExist($donation->email)) }}" target="_blank"
                        <?php
                     }
                  ?>
                  ><div class="tdUser"><i class="fa fa-user-circle" aria-hidden="true"></i></div>{{ $donation->fname }}</a></td>
               <td>{{ $donation->lname }}</td>
               <td>{{ $donation->email }}</td>
               <td>{{ '£' . $donation->amount }}</td>
               <td>{{ $donation->created_at }}</td>
               <td>{{ $donation->type }}
               <td>{{ $donation->transaction_id }}
               </td>
            </tr>
         @empty
            <p class="donationEmty">No donations</p>
         @endforelse
      </tbody>
   </table>
</div>
<div style="text-align: center;">{{ $donations->appends(request()->except('page'))->links() }}</div>