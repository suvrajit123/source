<div class="myResourceProSub">
   <table class="table" id="resources-table">
      <thead>
         <tr class="table-active">
            <th scope="col">#</th>
            <th scope="col">Resource Image</th>
            <th scope="col">Title</th>
            <th scope="col" class="td_center">Uploader</th>
            <th scope="col" class="td_center">Reson</th>
            <th scope="col" class="td_center">Flag By</th>
            <th scope="col">Action</th>
         </tr>
      </thead>
      <tbody>
         @forelse($flagedResources as $resource)
            <tr data-row-id="{{ $resource->id }}">
               <td scope="row">{{ $loop->iteration }}</td>
               <td><a href="{{ route('theme.singleresource',$resource->id) }}" target="_blank"><img src="{{ !blank($resource->preview_attachment) ? asset('irh_assets/uploads/resource_preview/' . $resource->preview_attachment) : asset('irh_assets/images/resource_thum.jpg') }}" width="142" height="88" alt=""/></a></td>
               <td>{!! str_limit($resource->title, 12) !!}</td>
               <td class="td_center">
                  <div class="tdUser"><i class="fa fa-user-circle" aria-hidden="true"></i></div>
                  <p>[ {!! $resource->uploader !!} ]</p>
               </td>
               <td>{{ $resource->reason }}</td>
               <td>{{ $resource->flagsUsername }}</td>
               <td>
                  <a href="javascript:void(0)" class="link remove-flag"><i class="fa fa-check-circle" aria-hidden="true"></i></a> 
                  <a href="javascript:void(0)" class="link admin-notice"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a> 
                  <a href="javascript:void(0)" class="link ban-link" data-ban-ace="{{ $resource->block }}"><i class="fa fa-ban" aria-hidden="true" style="{{ $resource->block == '0' ? 'color:red;' : '' }}"></i></a>
                  <a href="javascript:void(0)" class="link del-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
               </td>
            </tr>
         @empty
            <p>No resources</p>
         @endforelse
      </tbody>
   </table>
</div>
<div style="text-align: center;">{{ $flagedResources->appends(request()->except('page'))->links() }}</div>