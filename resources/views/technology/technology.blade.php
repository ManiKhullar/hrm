@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Technology</h4>
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
                    <form action="{{ route('technologysave') }}" method="POST" enctype="multipart/form-data">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Technology</label>
                                    <input type="text" id="technology" class="form-control" name="technology">
                                </div>
                                @if ($errors->has('technology'))
                                <p class="text-danger">{{ $errors->first('technology') }}</p>
                                @endif	
                            </div>
                            
                            
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
									<label>Status</label>
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
                            <h4 class="card-title mb-0">Technology List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Technology</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($technology as $tech)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $tech->technology }}</td>
                                            <td>@if($tech->status == 1)
                                                    Active
                                                @else
                                                    Deactivate
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ url('technologyedit/'.$tech->id.'/edit') }}">
                                                <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $technology->links() !!}
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
<script>
    // $(document).ready(function() {
    //     $('#category').on('change.mobileinput', function() {
    //         $("#mobileinput").toggle($(this).val() == 'Mobile');
    //     }).trigger('change.mobileinput');
    // });
</script>

@endsection