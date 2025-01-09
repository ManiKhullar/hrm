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
                    @if (Session::has('error'))
                    <p class="text-danger">{{ Session::get('error') }}</p>
                    @endif
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <form action="{{ route('menu_save') }}" method="POST">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-6 leave-col">
                                <div class="form-group">
                                    <label>Departement Name</label>
                                    <input type="text" class="form-control" name="name" />
                                </div>
                                @if ($errors->has('name'))
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                                @endif	
                            </div>

                            <div class="col-sm-6 leave-col">
                                <div class="form-group">
                                    <label>Class Name(For Icon)</label>
                                    <input type="text" class="form-control" name="class_name" />
                                </div>
                            </div>	
                        </div>
                        <div class="row">
                        <div class="col-sm-6 leave-col">
                                <div class="form-group">
                                    <label>Routes Name</label>
                                    <input type="text" class="form-control" name="routes_name" />
                                </div>
                                @if ($errors->has('routes_name'))
                                <p class="text-danger">{{ $errors->first('routes_name') }}</p>
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
                                        <option value="2">No Include In Menu</option>
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
                            <h4 class="card-title mb-0">Menu List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table menu_add_tab">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Department</th>
                                            <th>Class Name</th>
                                            <th>Routes Name</th>
                                            <th>Status</th>
                                            <th width="280px">Action</th>
                                        </tr>
                                        @foreach ($menu as $menuData)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $menuData->name }}</td>
                                            <td>{{ $menuData->class_name }}</td>
                                            <td>{{ $menuData->routes_name }}</td>
                                            <td>
                                                @if($menuData->status == 1)
                                                <button type="button" class="btn btn-success">Active</button>
                                                @elseif($menuData->status == 2)
                                                <button type="button" class="btn btn-danger">No Include In menu</button>
                                                @else
                                                <button type="button" class="btn btn-danger">Deactivate</button>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ url('menu_edit/'.$menuData->id) }}">Edit</a>
                                                <!-- <a class="btn btn-sm btn-outline-danger" href="{{ url('menu_delete/'.$menuData->id) }}"><span class="lnr lnr-trash"></span>Delete</a> -->
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $menu->links() !!}
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
    span.relative.inline-flex.items-center.px-4.py-2.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.cursor-default.leading-5.rounded-md {
        display: none;
    }
    a.relative.inline-flex.items-center.px-4.py-2.ml-3.text-sm.font-medium.text-gray-700.bg-white.border.border-gray-300.leading-5.rounded-md.hover\:text-gray-500.focus\:outline-none.focus\:ring.ring-gray-300.focus\:border-blue-300.active\:bg-gray-100.active\:text-gray-700.transition.ease-in-out.duration-150 {
        display: none;
    }
    a.relative.inline-flex.items-center.px-4.py-2.text-sm.font-medium.text-gray-700.bg-white.border.border-gray-300.leading-5.rounded-md.hover\:text-gray-500.focus\:outline-none.focus\:ring.ring-gray-300.focus\:border-blue-300.active\:bg-gray-100.active\:text-gray-700.transition.ease-in-out.duration-150 {
        display: none;
    }
</style>

@endsection