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
                    @if (Session::has('error'))
                    <p class="text-danger">{{ Session::get('error') }}</p>
                    @endif
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <form action="{{ route('rolesave') }}" method="POST">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-6 leave-col">
                                <div class="form-group">
                                    <label>Department</label>
                                    <select class="form-control select" name="department">
                                        @foreach($deparement as $deparementData)
                                        <option value="{{$deparementData->id}}">{{$deparementData->department_name}}</option>
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
                                @foreach($menuData as $menu)
                                <p><input type="checkbox" name="access[]" value="{{$menu->id}}">{{$menu->name}}</p>
                                @endforeach
                                @if ($errors->has('access'))
                                <p class="text-danger">{{ $errors->first('access') }}</p>
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
                            <h4 class="card-title mb-0">Role List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th width="280px">Action</th>
                                        </tr>
                                        @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $role->department_name }}</td>
                                            <td>
                                                @if($role->status == 1)
                                                <button type="button" class="btn btn-success">Active</button>
                                                @else
                                                <button type="button" class="btn btn-danger">Deactivate</button>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ url('roleedit/'.$role->id.'/edit') }}">Edit</a>
                                                <!-- <a class="btn btn-sm btn-outline-danger" href="roledelete/{{$role->id}}/delete"><span class="lnr lnr-trash"></span>Delete</a> -->
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                   
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