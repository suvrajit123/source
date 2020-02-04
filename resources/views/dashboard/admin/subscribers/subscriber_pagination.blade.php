<div class="myResourceProSub">
   <table class="table" id="resources-table">
      <thead>
         <tr class="table-active">
            <th scope="col">#</th>
            <th scope="col">Email</th>
            <th scope="col">Active</th>
            <th scope="col">Action</th>
         </tr>
      </thead>
      <tbody>
         @forelse($subscribers as $subscriber)
            <tr data-row-id="{{ $subscriber->id }}">
               <td scope="row">{{ $loop->iteration }}</td>
               <td>{{ $subscriber->email }}</td>
               <td>
                  {{ $subscriber->status == '0' ? 'Inactive' : 'Active' }}
               </td>
               <td>
                  <a href="javascript:void(0)" class="link ban-link" data-ban-ace="{{ $subscriber->status }}"><i class="fa fa-ban" aria-hidden="true" style="{{ $subscriber->status == '0' ? 'color:red;' : '' }}"></i></a>
                  <a href="javascript:void(0)" class="link del-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
               </td>
            </tr>
         @empty
            <p>No subscribers</p>
         @endforelse
      </tbody>
   </table>
</div>
<div style="text-align: center;">{{ $subscribers->appends(request()->except('page'))->links() }}</div>