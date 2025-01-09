@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Holiday</h4>
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
                    <form action="{{ route('holidaysave') }}" method="POST" enctype="multipart/form-data">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Holiday Name</label>
                                    <input type="text" id="holiday_name" class="form-control" name="holiday_name">
                                </div>
                                @if ($errors->has('holiday_name'))
                                <p class="text-danger">{{ $errors->first('holiday_name') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="text" id="date" class="form-control datetimepicker" name="date">
                                </div>
                                @if ($errors->has('date'))
                                <p class="text-danger">{{ $errors->first('date') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
									<label>Type</label>
                                    <select class="form-control select" name="type">
                                        <option value="">Select Holiday Type</option>
                                        <option value="Company Holiday">Company Holiday</option>
                                        <option value="Restricted Holiday">Restricted Holiday</option>
                                    </select>
								</div>
								@if ($errors->has('type'))
								<p class="text-danger">{{ $errors->first('type') }}</p>
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
                            <h4 class="card-title mb-0">Holiday List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Holiday Name</th>
                                            <th>Holiday Type</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($holidays as $holiday)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $holiday->holiday_name }}</td>
                                            <td>{{ $holiday->type }}</td>
                                            <td><?php echo date("d/m/Y",strtotime($holiday->date)); ?></td>
                                            <td>@if($holiday->status == 1)
                                                    Active
                                                @else
                                                    Deactivate
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ url('holidayedit/'.$holiday->id.'/edit') }}">
                                                <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $holidays->links() !!}
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