@extends('layouts.layout')
 
@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
<div class="row">
<div class="col-md-12">
<div class="card ctm-border-radius shadow-sm grow">
<div class="card-header">
<h4 class="card-title mb-0">Add Menu</h4>
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
  
    <form action="{{ url('menu_update/'.$menu->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
			<div class="col-sm-6 leave-col">
			<div class="form-group">
			<div class="form-group">
				<label>Departement Name</label>
				<input type="text" class="form-control" name="name" value="{{$menu->name}}"/>
			</div>
			</div>
			@if ($errors->has('name'))
			 <p class="text-danger">{{ $errors->first('name') }}</p>
			 @endif	
			</div>
			<div class="col-sm-6 leave-col">
			<div class="form-group">
			<label>Class Name</label>
				<input type="text" class="form-control" name="class_name" value="{{$menu->class_name}}" />
			</div>
			</div>	
			</div>
			<div class="row">
			<div class="col-sm-6 leave-col">
				<div class="form-group">
					<label>Routes Name</label>
					<input type="text" class="form-control" name="routes_name" value="{{$menu->routes_name}}" />
				</div>
				@if ($errors->has('routes_name'))
				<p class="text-danger">{{ $errors->first('routes_name') }}</p>
				@endif 
			</div>
			<div class="col-sm-6">
			<div class="form-group">
				<label>Status<span class="text-danger">*</span></label>
				<select class="form-control select" name="status">
					<option value="">Select</option>
					<option value="1" {{ $menu->status == 1 ? 'selected' : '' }}>Active</option>
					<option value="0" {{ $menu->status == 0 ? 'selected' : '' }}>Deactivate</option>
					<option value="2" {{ $menu->status == 2 ? 'selected' : '' }}>No Include In Menu</option>
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