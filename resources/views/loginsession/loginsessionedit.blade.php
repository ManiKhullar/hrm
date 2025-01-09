@extends('layouts.layout')

@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Update Work Location</h4>
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
                
                <form action="{{ url('loginsessionupdate/'.$sessionLoginData[0]->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Employee Name</label>
                                <input type="text" id="name" class="form-control" name="name" value="{{ $sessionLoginData[0]->name }}">
                            </div>
                            @if ($errors->has('name'))
                            <p class="text-danger">{{ $errors->first('name') }}</p>
                            @endif	
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Employee Email</label>
                                <input type="text" id="email" class="form-control" name="email" value="{{ $sessionLoginData[0]->email }}">
                            </div>
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" id="date" class="form-control" name="date" value="{{ date('d-m-Y', strtotime($sessionLoginData[0]->created_at)) }}">
                            </div>
                            @if ($errors->has('date'))
                            <p class="text-danger">{{ $errors->first('date') }}</p>
                            @endif	
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Work Location</label>
                                <select class="form-control select" name="location">
                                    <option value="WFO" {{ $sessionLoginData[0]->location == 'WFO' ? 'selected' : '' }}>WFO</option>
                                    <option value="WFH" {{ $sessionLoginData[0]->location == 'WFH' ? 'selected' : '' }}>WFH</option>
                                </select>
                            </div>
                            @if ($errors->has('location'))
                            <p class="text-danger">{{ $errors->first('location') }}</p>
                            @endif
                        </div>
                    </div>
                    <!-- <input type="hidden" class="form-control" name="updated_at" value="{{ date('Y-m-d H:i:s') }}"> -->
					<div class="text-center">
						<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Update</button>
						<a href="{{ route('loginsessionlist') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection