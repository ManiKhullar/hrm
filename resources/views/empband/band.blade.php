@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Band</h4>
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
                    <form action="{{ route('empbandsave') }}" method="POST">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Employee Band</label>
                                    <input type="text" id="emp_band" class="form-control" name="emp_band">
                                </div>
                                @if ($errors->has('emp_band'))
                                <p class="text-danger">{{ $errors->first('emp_band') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Basic Salary(%)</label>
                                    <input type="text" id="basic_salary" class="form-control" name="basic_salary">
                                </div>
                                @if ($errors->has('basic_salary'))
                                <p class="text-danger">{{ $errors->first('basic_salary') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
									<label>House Rent Allounce(%)</label>
                                    <input type="text" id="house_rent_allounce" class="form-control" name="house_rent_allounce">
								</div>
								@if ($errors->has('house_rent_allounce'))
								<p class="text-danger">{{ $errors->first('house_rent_allounce') }}</p>
								@endif 
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
									<label>TDS Type</label>
                                    <select class="form-control select" id="tds_type" name="tds_type">
                                        <option value="">Select TDS Type</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
								</div>
								@if ($errors->has('tds_type'))
								<p class="text-danger">{{ $errors->first('tds_type') }}</p>
								@endif 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Transport Allounce(%)</label>
                                    <input type="text" id="transport_allounce" class="form-control" name="transport_allounce">
                                </div>
                                @if ($errors->has('transport_allounce'))
                                <p class="text-danger">{{ $errors->first('transport_allounce') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Special Allounce(%)</label>
                                    <input type="text" id="special_allounce" class="form-control" name="special_allounce">
                                </div>
                                @if ($errors->has('special_allounce'))
                                <p class="text-danger">{{ $errors->first('special_allounce') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
									<label>Extra Pay(%)</label>
                                    <input type="text" id="extra_pay" class="form-control" name="extra_pay">
								</div>
								@if ($errors->has('extra_pay'))
								<p class="text-danger">{{ $errors->first('extra_pay') }}</p>
								@endif 
                            </div>
                            <div id="tds" class="col-sm-3 leave-col">
                                <div class="form-group">
									<label>TDS</label>
                                    <input type="text" id="tds" class="form-control" name="tds">
								</div>
								@if ($errors->has('tds'))
								<p class="text-danger">{{ $errors->first('tds') }}</p>
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
                            <h4 class="card-title mb-0">Band List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Employee Band</th>
                                            <th>Basic Salary(%)</th>
                                            <th>Transport Allounce(%)</th>
                                            <th>Special Allounce(%)</th>
                                            <th>Extra Pay(%)</th>
                                            <th>TDS Type</th>
                                            <th>TDS(%)</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($bandData as $band)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $band->emp_band }}</td>
                                            <td>{{ $band->basic_salary }}</td>
                                            <td>{{ $band->transport_allounce }}</td>
                                            <td>{{ $band->special_allounce }}</td>
                                            <td>{{ $band->extra_pay }}</td>
                                            <td>{{ $band->tds_type }}</td>
                                            <td>{{ $band->tds }}</td>
                                            <td>@if($band->status == 1)
                                                    Active
                                                @else
                                                    Deactivate
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ url('empbandedit/'.$band->id.'/edit') }}">
                                                <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
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
    $(document).ready(function() {
        $('#tds_type').on('change.tds', function() {
            $("#tds").toggle($(this).val() == 'Yes');
        }).trigger('change.tds');
    });
</script>

@endsection