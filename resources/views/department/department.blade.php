@extends('layouts.layout')

@section('content')

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add User Type</h4>
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
                    <form action="{{ route('departmentsave') }}" method="POST">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-6 leave-col">
                                <div class="form-group">
                                    <label>User Type</label>
                                    <input type="text" class="form-control" name="department_name" id="department_name">
                                </div>
                                @if ($errors->has('department_name'))
                                <p class="text-danger">{{ $errors->first('department_name') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Status<span class="text-danger">*</span></label>
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
                            <h4 class="card-title mb-0">User Type List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>User Type</th>
                                            <th>Status</th>
                                            <th width="280px">Action</th>
                                        </tr>
                                        @foreach ($departments as $department)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $department->department_name }}</td>
                                            <td>
                                                @if($department->status == 1)
                                                    Active
                                                @else
                                                    Deactivate
                                                @endif
                                            </td>
                                            <td>
                                                @if($department->status == 0)
                                                    <a onclick="return confirm('Are you want to Active?')" href="{{ url('departmentupdate/'.$department->id.'/1') }}">Active</a>
                                                @else
                                                    <a onclick="return confirm('Are you want to Deactive?')" href="{{ url('departmentupdate/'.$department->id.'/0') }}">Deactivate</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $departments->links() !!}
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