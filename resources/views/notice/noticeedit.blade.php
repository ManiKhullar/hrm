@extends('layouts.layout')

@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Update Notice</h4>
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
                
                <form action="{{ url('noticeupdate/'.$notice->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-2 leave-col">
                            <div class="form-group">
                                <label>Text Color</label>
								<select class="form-control select" name="color">
                                    <option value="MediumSeaGreen" {{ $notice->color == 'MediumSeaGreen' ? 'selected' : '' }}>Green</option>
			                        <option value="blue" {{ $notice->color == 'blue' ? 'selected' : '' }}>Blue</option>
									<option value="red" {{ $notice->color == 'red' ? 'selected' : '' }}>Red</option>
                                </select>
                            </div>
                            @if ($errors->has('color'))
                            <p class="text-danger">{{ $errors->first('color') }}</p>
                            @endif	
                        </div>

						<div class="col-sm-8 leave-col">
                            <div class="form-group">
                                <label>Employee Message</label>
								<textarea class="form-control" id="content" name="content">{{ $notice->content }}</textarea>
                            </div>
                            @if ($errors->has('content'))
                            <p class="text-danger">{{ $errors->first('content') }}</p>
                            @endif	
                        </div>
                       
                        <div class="col-sm-2 leave-col">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select" name="status">
                                    <option value="1" {{ $notice->status == 1 ? 'selected' : '' }}>Active</option>
			                        <option value="0" {{ $notice->status == 0 ? 'selected' : '' }}>Deactivate</option>
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
						<a href="{{ route('notice') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection