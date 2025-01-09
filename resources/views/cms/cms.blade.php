@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Cms</h4>
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
                    <form action="{{ route('cmssave') }}" method="POST" enctype="multipart/form-data">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" id="title" class="form-control" name="title">
                                </div>
                                @if ($errors->has('title'))
                                <p class="text-danger">{{ $errors->first('title') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-8 leave-col">
                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea type="text" id="content" class="form-control" name="content"></textarea>
                                </div>
                                @if ($errors->has('content'))
                                <p class="text-danger">{{ $errors->first('content') }}</p>
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
                            <h4 class="card-title mb-0">Cms List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($cmsdata as $cms)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $cms->title }}</td>
                                            <td>
                                                @if($cms->status == '1')
                                                    Active
                                                @else
                                                    Deactive
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ url('cmsedit/'.$cms->id.'/edit') }}">
                                                    <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                                <a class="btn btn-warning" href="{{ url('cmsdelete/'.$cms->id.'/delete') }}">
                                                    <i style='font-size:24px' class="fa fa-trash-o" aria-hidden="true"></i>
                                                </a>
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
<script>
    // $(function() {
    //     $( "#from" ).datepicker({ 
    //         changeYear: true,
    //         minDate: '-3Y'
    //     });
    //     $( "#to" ).datepicker({ 
    //         changeYear: true,
    //         minDate: '-3Y'
    //     });
    // });
</script>

@endsection