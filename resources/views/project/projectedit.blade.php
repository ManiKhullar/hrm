@extends('layouts.layout')
 
@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
<div class="row">
<div class="col-md-12">
<div class="card ctm-border-radius shadow-sm grow">
<div class="card-header">
<h4 class="card-title mb-0">Add Project</h4>
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
  
    <form action="{{ url('projectupdate/'.$project->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
			<div class="col-sm-6 leave-col">
			<div class="form-group">
			<label>Project Name</label>
			<input type="text" class="form-control" name="project_name" value="{{ $project->project_name }}">
			</div>
			@if ($errors->has('project_name'))
			 <p class="text-danger">{{ $errors->first('project_name') }}</p>
			 @endif	
			</div>
			<div class="col-sm-6 leave-col">
			<div class="form-group">
			<label>Vendor Name</label>
			<input type="text" class="form-control" name="vendor_name" value="{{ $project->vendor_name }}">
			</div>
			@if ($errors->has('vendor_name'))
			 <p class="text-danger">{{ $errors->first('vendor_name') }}</p>
			 @endif	
			</div>	
			</div>
			<div class="row">
			<div class="col-sm-6">
			<div class="form-group">
			<label>Project Strat Date</label>
			<input type="text" class="form-control datetimepicker" name="project_startdate" value="{{ $project->project_startdate }}">
			</div>
			@if ($errors->has('project_startdate'))
			 <p class="text-danger">{{ $errors->first('project_startdate') }}</p>
			 @endif
			</div>
			<div class="col-sm-6 leave-col">
			<div class="form-group">
			<label>Project End Date</label>
			<input type="text" class="form-control datetimepicker" name="project_enddate" value="{{ $project->project_enddate }}">
			</div>
			@if ($errors->has('project_enddate'))
			 <p class="text-danger">{{ $errors->first('project_enddate') }}</p>
			 @endif 
			</div>
			</div>
			<div class="row">
			<div class="col-sm-6">
			<div class="form-group">
			<label>
			Status
			<span class="text-danger">*</span>
			</label>
			<select class="form-control select" name="status">
			<option value="">Select</option>
			<option value="1" {{ $project->status == 1 ? 'selected' : '' }}>Active</option>
			<option value="0" {{ $project->status == 0 ? 'selected' : '' }}>Deactivate</option>
			</select>
			</div>
			@if ($errors->has('status'))
			 <p class="text-danger">{{ $errors->first('status') }}</p>
			 @endif	
			</div>	
			</div>	
			<input type="hidden" class="form-control" name="updated_at" value="{{ date('Y-m-d H:i:s') }}">
			<div class="text-center">
			<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
			<a href="{{ route('project') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
			</div>
    </form>
</div>
</div>
</div>
</div>
</div>
@endsection