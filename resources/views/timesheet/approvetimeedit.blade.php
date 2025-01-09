@extends('layouts.layout')

@section('content')

<style>
	.cke_notifications_area {
        display: none;
    }
</style>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<div class="col-xl-9 col-lg-8 col-md-12">
	<div class="row">
		<div class="col-md-12">
			<div class="card ctm-border-radius shadow-sm grow">
				<div class="card-header">
					<h4 class="card-title mb-0">Approve Time Sheet</h4>
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
					
					<form action="{{ url('approvetimeupdate/'.$timesheet->id) }}" method="POST">
						@csrf
						@method('PUT')
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Project Name</label>
									@foreach ($projects as $project)
										@if ($timesheet->project_id == $project->id)
										<input type="text" id="select_date" class="form-control" name="select_date" value="{{ $project->project_name }}" disabled>
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-sm-6 leave-col">
								<div class="form-group">
									<label>Developer Name</label>
									@foreach ($users as $user)
										@if ($timesheet->user_id == $user->id)
										<input type="text" id="times" class="form-control" name="times" value="{{ $user->name }}" disabled>
										@endif
									@endforeach
								</div>
								@if ($errors->has('times'))
								<p class="text-danger">{{ $errors->first('times') }}</p>
								@endif 
							</div>	
						</div>
                        <div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Select Date</label>
									<input type="text" id="select_date" class="form-control" name="select_date" value="<?php echo date("d/m/Y",strtotime($timesheet->select_date)); ?>" disabled>
								</div>
								@if ($errors->has('select_date'))
								<p class="text-danger">{{ $errors->first('select_date') }}</p>
								@endif
							</div>
							<div class="col-sm-6 leave-col">
								<div class="form-group">
									<label>Time (In Hours)</label>
									<input type="text" id="times" class="form-control" name="times" value="{{ $timesheet->hours }}h {{ $timesheet->minutes }}m" disabled>
								</div>
								@if ($errors->has('times'))
								<p class="text-danger">{{ $errors->first('times') }}</p>
								@endif 
							</div>	
						</div>
                        <div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Description</label>
									<textarea id="description" class="form-control" name="description" disabled>{{ $timesheet->description }}</textarea>
								</div>
								@if ($errors->has('description'))
								<p class="text-danger">{{ $errors->first('description') }}</p>
								@endif
							</div>
							<div class="col-sm-6 leave-col">
								<div class="form-group">
									<label>Status</label>
                                    <select class="form-control select" name="status">
                                        <option value="">Select</option>
                                        <option value="Approved" {{ $timesheet->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="ReferBack" {{ $timesheet->status == 'ReferBack' ? 'selected' : '' }}>ReferBack</option>
                                        <option value="Reject" {{ $timesheet->status == 'Reject' ? 'selected' : '' }}>Reject</option>
                                    </select>
								</div>
								@if ($errors->has('status'))
								<p class="text-danger">{{ $errors->first('status') }}</p>
								@endif 
							</div>	
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Manager Comment</label>
                                    <textarea class="form-control" id="manager_comment" name="manager_comment">{{ $timesheet->manager_comment }}</textarea>
								</div>
								@if ($errors->has('manager_comment'))
								<p class="text-danger">{{ $errors->first('manager_comment') }}</p>
								@endif
							</div>
							<?php if(count($commentHistory)){ ?>
							<div class="col-sm-6">
								<div class="form-group comment_history_dv">
									<label>Comment History</label>
									@foreach ($commentHistory as $comment)
									<ul>
										<li>
											<b style="font-weight:800;"><span>Comment:</span></b> <b>{{ $comment->comment_history }} </b>
											<b style="font-weight:800;"><span>Status:</span></b> <b>{{ $comment->status }} </b>
											<b style="font-weight:800;"><span>Updated:</span></b> <b>{{ $comment->updated_at }}</b>
										</li>
									</ul>
									@endforeach
								</div>
							</div>
							<?php } ?>
						</div>
						<input type="hidden" class="form-control" name="updated_at" value="{{ date('Y-m-d H:i:s') }}">
						<div class="text-center">
							<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
							<a href="{{ route('approvetime') }}"  class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        CKEDITOR.replace('description');
    });
</script>

@endsection