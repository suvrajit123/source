<div class="myResourceProSub">
   <table class="table" id="resources-table">
      <thead>
         <tr class="table-active">
            <th scope="col">#</th>
            <th scope="col">Content</th>
            <th scope="col">Testimonial by</th>
            <th scope="col">Date</th>
            <th scope="col">Action</th>
         </tr>
      </thead>
      <tbody>
         @forelse($testimonials as $testimonial)
            <tr data-row-id="{{ $testimonial->id }}">
               <td scope="row">{{ $loop->iteration }}</td>
               <td>{!! $testimonial->testimonial_text !!}</td>
               <td>{!! $testimonial->testimonial_by !!}</td>
               <td>{!! Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $testimonial->created_at)->format('d-m-Y') !!}</td>
               <td>
                  <a href="javascript:void(0)" class="link edit-link" data-edit-ace="{{ $testimonial->id }}" data-toggle="modal" data-target="#edit_testimnial"><i class="fa fa-pencil-square-o" aria-hidden="true" style="{{ $testimonial->status == '0' ? 'color:red;' : '' }}"></i></a>
                  <a href="javascript:void(0)" class="link del-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
               </td>
            </tr>
         @empty
            <p>No testimonials</p>
         @endforelse
      </tbody>
   </table>
</div>
<div style="text-align: center;">{{ $testimonials->appends(request()->except('page'))->links() }}</div>