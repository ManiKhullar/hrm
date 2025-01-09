@extends('layouts.layout')

@section('content')

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Category</h4>
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
                    <form action="{{ route('categorysave') }}" method="POST">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-6 leave-col">
                                <div class="form-group">
                                    <label>Category Name</label>
                                    <input type="text" class="form-control" name="category_name" id="category_name">
                                </div>
                                @if ($errors->has('category_name'))
                                <p class="text-danger">{{ $errors->first('category_name') }}</p>
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
                            <h4 class="card-title mb-0">Category List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Category Name</th>
                                            <th>Status</th>
                                            <th width="280px">Action</th>
                                        </tr>
                                        @foreach ($categorys as $category)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $category->category_name }}</td>
                                            <td>
                                                @if($category->status == 1)
                                                    Active
                                                @else
                                                    Deactivate
                                                @endif
                                            </td>
                                            <td>
                                                @if($category->status == 0)
                                                    <a onclick="return confirm('Are you want to Active?')" href="{{ url('categoryupdate/'.$category->id.'/1') }}">Active</a>
                                                @else
                                                    <a onclick="return confirm('Are you want to Deactive?')" href="{{ url('categoryupdate/'.$category->id.'/0') }}">Deactivate</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $categorys->links() !!}
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