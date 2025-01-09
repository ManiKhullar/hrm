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
  
    <form action="{{ url('projectmanagerupdate/'.$projectmanager->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
			<div class="col-sm-6">
			<div class="form-group">
			<label>
			Project Name
			<span class="text-danger">*</span>
			</label>
			<select class="form-control select" name="project_id">
			<option value="">Select</option>
			@foreach ($project as $projects)
			<option value="{{ $projects->id }}" {{ $projectmanager->project_id == $projects->id ? 'selected' : '' }}>{{ $projects->project_name }}</option>
			 @endforeach
			</select>
			</div>
			@if ($errors->has('project_id'))
			 <p class="text-danger">{{ $errors->first('project_id') }}</p>
			 @endif	
			</div>	
			<div class="col-sm-6">
			<div class="form-group">
			<label>
			Manager Name
			<span class="text-danger">*</span>
			</label>
			<select class="form-control select" name="manager_id">
			<option value="">Select</option>
			@foreach ($manager as $managers)
			<option value="{{ $managers->id }}" {{ $projectmanager->manager_id == $managers->id ? 'selected' : '' }}>{{ $managers->manager_name }}</option>
			 @endforeach
			</select>
			</div>
			@if ($errors->has('manager_id'))
			 <p class="text-danger">{{ $errors->first('manager_id') }}</p>
			 @endif	
			</div>	
			</div>
			<div class="row">
			<div class="col-sm-6">
			<div class="form-group">
			<label>
			Developer Name
			<span class="text-danger">*</span>
			</label>
			<select class="form-control select" name="developer_id">
			<option value="">Select</option>
			<option value="11" {{ $projectmanager->developer_id == 11 ? 'selected' : '' }}>mukesh</option>
			<option value="22" {{ $projectmanager->developer_id == 22 ? 'selected' : '' }}>santosh</option>
			<option value="23" {{ $projectmanager->developer_id == 23 ? 'selected' : '' }}>vishal</option>
			</select>
			</div>
			@if ($errors->has('developer_id'))
			 <p class="text-danger">{{ $errors->first('developer_id') }}</p>
			 @endif	
			</div>	
			<div class="col-sm-6">
			<div class="form-group">
			<label>
			Status
			<span class="text-danger">*</span>
			</label>
			<select class="form-control select" name="status">
			<option value="">Select</option>
			<option value="1" {{ $projectmanager->status == 1 ? 'selected' : '' }}>Active</option>
			<option value="0" {{ $projectmanager->status == 0 ? 'selected' : '' }}>Deactivate</option>
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
			<a href="{{ route('projectmanager') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
			</div>
    </form>
</div>
</div>
</div>
</div>
</div>
@endsection