@foreach($faqSubObj as $sFaqSubObj)
	<tr data-row-id="{{ $sFaqSubObj->id }}" data-parent-id="{{ $sFaqSubObj->faq_main_id }}">
		<td>
			<div class="card">
				<div class="card-header" id="headingOne">
					<div class="row">
						<div class="col-md-10 qst">{{ $sFaqSubObj->name }}</div>
						<div class="col-md-2" style="float: right; text-align: right;"><a href="javascript:void(0)" class="link faq_sub_link"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a><a href="javascript:void(0)" class="link del-link"><i class="fa fa-trash-o" aria-hidden="true"></i></a></div>
					</div>
				</div>

				<div class="collapse show qans" aria-labelledby="headingOne">
				  <div class="card-body">
				    {{ $sFaqSubObj->value }}
				  </div>
				</div>
			</div>
		</td>
	</tr>
@endforeach