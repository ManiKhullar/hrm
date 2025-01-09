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
					<h4 class="card-title mb-0">Edit Time Sheet</h4>
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
					
					<form action="{{ url('timesheetupdate/'.$timesheet->id) }}" method="POST">
						@csrf
						@method('PUT')
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Select Date</label>
									<?php $date = date("d/m/Y", strtotime($timesheet->select_date)); ?>
									<p>{{ $date }}</p>
								</div>
								@if ($errors->has('select_date'))
								<p class="text-danger">{{ $errors->first('select_date') }}</p>
								@endif
							</div>
							<div class="col-sm-3 leave-col">
								<div class="form-group">
									<label>Time (In Hours)</label>
									<select class="form-control select" name="hours">
										<?php 
										foreach ($hours as $key => $hour) { ?>
											<option value="{{ $key }}" {{ $timesheet->hours == $key ? 'selected' : '' }}>{{ $hour }}</option>
										<?php } ?>
									</select>
								</div>
								@if ($errors->has('hours'))
								<p class="text-danger">{{ $errors->first('hours') }}</p>
								@endif
							</div>
							<div class="col-sm-3 leave-col">
								<div class="form-group">
								<label>Time (In Minute)</label>
								<select class="form-control select" name="minutes">
										<?php 
										foreach ($minutes as $key => $minute) { ?>
											<option value="{{ $key }}" {{ $timesheet->minutes == $key ? 'selected' : '' }}>{{ $minute }}</option>
										<?php } ?>
									</select>
								</div>
								@if ($errors->has('minutes'))
								<p class="text-danger">{{ $errors->first('minutes') }}</p>
								@endif
							</div>	
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Description</label>
									<textarea class="form-control" id="description" name="description">{{ $timesheet->description }}</textarea>
								</div>
								@if ($errors->has('description'))
								<p class="text-danger">{{ $errors->first('description') }}</p>
								@endif
							</div>
						</div>	
						<input type="hidden" class="form-control" name="updated_at" value="{{ date('Y-m-d H:i:s') }}">
						<div class="text-center">
							<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Update</button>
							<a href="{{ route('timesheet') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
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