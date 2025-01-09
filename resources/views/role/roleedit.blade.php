@extends('layouts.layout')
 
@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
<div class="row">
<div class="col-md-12">
<div class="card ctm-border-radius shadow-sm grow">
<div class="card-header">
<h4 class="card-title mb-0">Add Role</h4>
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
  
    <form action="{{ url('roleupdate/'.$role[0]->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
			<div class="col-sm-6 leave-col">
			<div class="form-group">
			<label>Department<span class="text-danger">*</span></label>
			<select class="form-control select" name="department">
				<option value="">Select</option>
				@foreach($deparement as $deparementData)
				<option value="{{$deparementData->id}}" <?php if($deparementData->id == $role[0]->department){ echo "selected";}?>>{{$deparementData->department_name}}</option>
				@endforeach
			</select>
			</div>
			@if ($errors->has('department'))
			 <p class="text-danger">{{ $errors->first('department') }}</p>
			 @endif	
			</div>
			<div class="col-sm-12 leave-col">
			<div class="form-group">
			<label>Access</label>
			
			</div>
			@foreach($menu as $menuData)
		
			<label><input type="checkbox" name="access[]" class="role_edit_in" value="{{$menuData->id}}" <?php if(in_array($menuData->id, explode(",",$role[0]->access))){ echo "checked";}else{ ""; }?> >{{$menuData->name}}</label><br>
			@endforeach
			@if ($errors->has('access'))
			 <p class="text-danger">{{ $errors->first('access') }}</p>
			 @endif	
			</div>	
			</div>
			<div class="row">
			<div class="col-sm-6">
			<div class="form-group">
				<label>Status<span class="text-danger">*</span></label>
				<select class="form-control select" name="status">
					<option value="">Select</option>
					<option value="1" {{ $role[0]->status == 1 ? 'selected' : '' }}>Active</option>
					<option value="0" {{ $role[0]->status == 0 ? 'selected' : '' }}>Deactivate</option>
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
			<a href="{{ route('role') }}" class="btn btn-danger text-white ctm-border-radius mt-4 role_back">Back</a>
			</div>
    </form>
</div>
</div>
</div>
</div>
</div>
@endsection