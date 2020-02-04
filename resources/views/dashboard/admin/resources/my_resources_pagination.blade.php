<div class="myResourceProSub">
   <table class="table">
      <thead>
         <tr class="table-active">
            <th scope="col">#</th>
            <th scope="col">Resource Image</th>
            <th scope="col">Title</th>
            <th scope="col" class="td_center"><i class="fa fa-eye" aria-hidden="true"></i></th>
            <th scope="col" class="td_center"><i class="fa fa-download" aria-hidden="true"></i></th>
            <th scope="col" class="td_center"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></th>
            <th scope="col">Reviews</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
         </tr>
      </thead>
      <tbody>
         @forelse($resources as $resource)
            <tr data-row-id="{{ $resource->id }}">
               <th scope="row">{{ $loop->iteration }}</th>
               <td><a href="{{ route('theme.singleresource',$resource->id) }}" target="_blank"><img src="{{ !blank($resource->preview_attachment) ? asset('irh_assets/uploads/resource_preview/' . $resource->preview_attachment) : asset('irh_assets/images/resource_thum.jpg') }}" width="142" height="88" alt=""/></a></td>
               <td>{!! str_limit($resource->title, 12) !!}</td>
               <td class="td_center">{{ blank($resource->views) ? '0' : $resource->views }}</td>
               <td class="td_center">{{ blank($resource->downloads) ? '0' : $resource->downloads }}</td>
               <td class="td_center">{{ blank($resource->likes) ? '0' : $resource->likes }}</td>
               <td>
                  <div class="starRating">
                     <div class="starRatingActive start{{ blank($resource->stars) ? '0' : $resource->stars / $resource->reviews_count }}"></div>
                  </div>
                  <p class="rating">{{ blank($resource->reviews_count) ? '0' : $resource->reviews_count }} reviews</p>
               </td>
               <td>{{ ucfirst($resource->resource_status) }}</td>
               <td><a href="{{ route('admin.create.resource.edit', $resource->id) }}" class="link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
                  <a href="javascript:void(0)" class="link del-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
               </td>
            </tr>
         @empty
            <p>No resources</p>
         @endforelse
      </tbody>
   </table>
</div>
<div style="text-align: center;">{{ $resources->appends(request()->except('page'))->links() }}</div>