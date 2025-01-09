@extends('layouts.layout')

@section('content')
<style>
	table.table.custom-table.table_text tbody tr td {
		text-wrap: wrap;
		width:50%
	}
	table.table.custom-table.table_text tbody tr th{
		vertical-align: initial !important;
	}
	table.table.custom-table.table_text.mb-0 {
		width: 60%;
	}
</style>
<div class="col-xl-9 col-lg-8 col-md-12">
	<div class="row">
		<div class="col-md-12">
			<div class="card ctm-border-radius shadow-sm grow">
				<div class="card-header">
					<h4 class="card-title mb-0">View Time Sheet Details</h4>
				</div>
				<div class="card-body">
					@if ($errors->any())
					<div class="alert alert-danger">
						<strong>Error!</strong> <br>
						<ul>
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif

					<div class="table-responsive add_comment_hr">
						<table class="table custom-table table_text mb-0">
							<tr>
								<th>Project Name</th>
								<td>@foreach ($projects as $project)
									@if ($timesheet->project_id == $project->id)
									{{ $project->project_name }}
									@endif
								@endforeach</td>
							</tr>
							<tr>
								<th>Hours</th>
								<td>{{ $timesheet->hours }}h {{ $timesheet->minutes }}m</td>
							</tr>
							<tr>
								<th>Log Date</th>
								<td><?php echo date("d/m/Y",strtotime($timesheet->select_date)) ; ?></td>
							</tr>
							<tr>
								<th>Description</th>
								<td><?php echo($timesheet->description); ?></td>
							</tr>
							<tr>
								<th>Status</th>
								<td>{{ $timesheet->status }}</td>
							</tr>
							<tr>
								<th>Approval By</th>
								<td>@foreach ($users as $user)
									@if ($timesheet->manager_id == $user->id)
									{{ $user->name }}
									@endif
								@endforeach</td>
							</tr>
							<tr>
								<th>Manager Comment</th>
								<td>{{ $timesheet->manager_comment }}</td>
							</tr>
						</table>
						<div class="comment_history_dv">
							<label>Comment History</label>
							@foreach ($commentHistory as $comment)
								<ul>
									<li>
										<b style="font-weight:700;"><span class="common_cls_cmt">Comment:</span></b> <b>{{ $comment->comment_history }} </b>
										<b style="font-weight:700;"><span class="common_cls_cmt">Status:</span></b> <b>{{ $comment->status }} </b>
										<b style="font-weight:700;"><span class="common_cls_cmt">Updated:</span></b> <b>{{ $comment->updated_at }}</b>
									</li>
								</ul>
							@endforeach
						</div>
					</div>	
					<div class="text-center cancel_btn">
						<a href="{{ route('timesheet') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection