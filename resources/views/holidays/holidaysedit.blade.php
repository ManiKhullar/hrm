@extends('layouts.layout')

@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Update Holiday</h4>
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
                
                <form action="{{ url('holidayupdate/'.$holidays->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>Holiday Name</label>
                                <input type="text" id="holiday_name" class="form-control" name="holiday_name" value="{{ $holidays->holiday_name }}">
                            </div>
                            @if ($errors->has('holiday_name'))
                            <p class="text-danger">{{ $errors->first('holiday_name') }}</p>
                            @endif	
                        </div>
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>Date</label>
                                <?php $date = date("d/m/Y", strtotime($holidays->date)); ?>
                                <input type="text" id="date" class="form-control datetimepicker" name="date" value="{{ $date }}">
                            </div>
                            @if ($errors->has('date'))
                            <p class="text-danger">{{ $errors->first('date') }}</p>
                            @endif
                        </div>
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control select" name="type">
                                    <option value="Company Holiday" {{ $holidays->type == 'Company Holiday' ? 'selected' : '' }}>Company Holiday</option>
			                        <option value="Restricted Holiday" {{ $holidays->type == 'Restricted Holiday' ? 'selected' : '' }}>Restricted Holiday</option>
                                </select>
                            </div>
                            @if ($errors->has('type'))
                            <p class="text-danger">{{ $errors->first('type') }}</p>
                            @endif
                        </div>
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select" name="status">
                                    <option value="1" {{ $holidays->status == 1 ? 'selected' : '' }}>Active</option>
			                        <option value="0" {{ $holidays->status == 0 ? 'selected' : '' }}>Deactivate</option>
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
						<a href="{{ route('holiday') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection