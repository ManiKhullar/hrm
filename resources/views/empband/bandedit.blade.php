@extends('layouts.layout')

@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Update Employee Band</h4>
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
                
                <form action="{{ url('empbandupdate/'.$empband->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Employee Band</label>
                                <input type="text" id="emp_band" class="form-control" name="emp_band" value="{{ $empband->emp_band }}">
                            </div>
                            @if ($errors->has('emp_band'))
                            <p class="text-danger">{{ $errors->first('emp_band') }}</p>
                            @endif	
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Basic Salary(%)</label>
                                <input type="text" id="basic_salary" class="form-control" name="basic_salary" value="{{ $empband->basic_salary }}">
                            </div>
                            @if ($errors->has('basic_salary'))
                            <p class="text-danger">{{ $errors->first('basic_salary') }}</p>
                            @endif
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>House Rent Allounce(%)</label>
                                <input type="text" id="house_rent_allounce" class="form-control" name="house_rent_allounce" value="{{ $empband->house_rent_allounce }}">
                            </div>
                            @if ($errors->has('house_rent_allounce'))
                            <p class="text-danger">{{ $errors->first('house_rent_allounce') }}</p>
                            @endif 
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>TDS Type</label>
                                <select class="form-control select" id="tds_type" name="tds_type">
                                    <option value="Yes" {{ $empband->tds_type == 'Yes' ? 'selected' : '' }}>Yes</option>
			                        <option value="No" {{ $empband->tds_type == 'No' ? 'selected' : '' }}>No</option>
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
                                <input type="text" id="transport_allounce" class="form-control" name="transport_allounce" value="{{ $empband->transport_allounce }}">
                            </div>
                            @if ($errors->has('transport_allounce'))
                            <p class="text-danger">{{ $errors->first('transport_allounce') }}</p>
                            @endif	
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Special Allounce(%)</label>
                                <input type="text" id="special_allounce" class="form-control" name="special_allounce" value="{{ $empband->special_allounce }}">
                            </div>
                            @if ($errors->has('special_allounce'))
                            <p class="text-danger">{{ $errors->first('special_allounce') }}</p>
                            @endif
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Extra Pay(%)</label>
                                <input type="text" id="extra_pay" class="form-control" name="extra_pay" value="{{ $empband->extra_pay }}">
                            </div>
                            @if ($errors->has('extra_pay'))
                            <p class="text-danger">{{ $errors->first('extra_pay') }}</p>
                            @endif 
                        </div>
                        <div id="tds" class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>TDS</label>
                                <input type="text" id="tds" class="form-control" name="tds" value="{{ $empband->tds }}">
                            </div>
                            @if ($errors->has('tds'))
                            <p class="text-danger">{{ $errors->first('tds') }}</p>
                            @endif 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select" name="status">
                                    <option value="1" {{ $empband->status == 1 ? 'selected' : '' }}>Active</option>
			                        <option value="0" {{ $empband->status == 0 ? 'selected' : '' }}>Deactivate</option>
                                </select>
                            </div>
                            @if ($errors->has('status'))
                            <p class="text-danger">{{ $errors->first('status') }}</p>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="updated_at" value="{{ date('Y-m-d H:i:s') }}">
					<div class="text-center">
						<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Update</button>
						<a href="{{ route('empband') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#tds_type').on('change.tds', function() {
            $("#tds").toggle($(this).val() == 'Yes');
        }).trigger('change.tds');
    });
</script>
@endsection