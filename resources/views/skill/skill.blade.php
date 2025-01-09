@extends('layouts.layout')
 
@section('content')

<div class="col-xl-9 col-lg-8 col-md-12">
<div class="row">
<div class="col-md-12">
<div class="card ctm-border-radius shadow-sm grow">
<div class="card-header">
<h4 class="card-title mb-0">Add Skill</h4>
</div>
<div class="card-body">
@if (Session::has('error'))
<p class="text-danger">{{ Session::get('error') }}</p>
@endif
@if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
<form action="{{ route('skillsave') }}" method="POST">
@csrf	
<div class="row">
<div class="col-sm-6 leave-col">
<div class="form-group">
<label>Skill Name</label>
<input type="text" class="form-control" name="skill_name">
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
<option value="1">Active</option>
<option value="0">Deactivate</option>
</select>
</div>
@if ($errors->has('status'))
 <p class="text-danger">{{ $errors->first('status') }}</p>
 @endif 
</div>
</div>
<div class="text-center">
<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
</div>
</form>
</div>
<div class="col-md-12">
<div class="card ctm-border-radius shadow-sm grow">
<div class="card-header">
<h4 class="card-title mb-0">Skill List</h4>
</div>
<div class="card-body">
<div class="employee-office-table">
<div class="table-responsive">
    <table class="table custom-table mb-0">
        <tr>
            <th>No</th>
            <th>Skill Name</th>
            <th>Status</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($skills as $skill)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $skill->skill_name }}</td>
            <td>
                @if($skill->status == 1)
             <button type="button" class="btn btn-success">Active</button>
             @else
            <button type="button" class="btn btn-danger">Deactivate</button>
             @endif
            </td>
            <td>
                <a class="btn btn-primary" href="{{ url('skilledit/'.$skill->id.'/edit') }}">Edit</a>
                <a class="btn btn-sm btn-outline-danger" href="skilldelete/{{$skill->id}}/delete"><span class="lnr lnr-trash"></span>Delete</a>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $skills->links() !!}
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<style type="text/css">
    .justify-between {
    width: 75px;
}
</style>

@endsection