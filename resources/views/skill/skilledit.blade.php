@extends('layouts.layout')
 
@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
<div class="row">
<div class="col-md-12">
<div class="card ctm-border-radius shadow-sm grow">
<div class="card-header">
<h4 class="card-title mb-0">Update Skill</h4>
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
  
    <form action="{{ url('skillupdate/'.$skill->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
			<div class="col-sm-6 leave-col">
			<div class="form-group">
			<label>Skill Name</label>
			<input type="text" class="form-control" name="skill_name" value="{{ $skill->skill_name }}">
			</div>
			@if ($errors->has('skill_name'))
			 <p class="text-danger">{{ $errors->first('skill_name') }}</p>
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
			<option value="1" {{ $skill->status == 1 ? 'selected' : '' }}>Active</option>
			<option value="0" {{ $skill->status == 0 ? 'selected' : '' }}>Deactivate</option>
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
			<a href="{{ route('skill') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
			</div>
    </form>
</div>
</div>
</div>
</div>
</div>
@endsection