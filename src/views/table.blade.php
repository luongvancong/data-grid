<table class="table table-hover table-stripped">
	<thead>
		<tr>
			@foreach($headings as $key => $heading)
				<th data-key="{{ $key }}">{!! $heading !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		{!! $ref->renderRows() !!}
	</tbody>
</table>
