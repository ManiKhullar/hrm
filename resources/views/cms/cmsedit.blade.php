@extends('layouts.layout')

@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Update Cms</h4>
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
                
                <form action="{{ url('cmsupdate/'.$cmsdata[0]->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" id="title" class="form-control" name="title" value="{{ $cmsdata[0]->title }}">
                            </div>
                            @if ($errors->has('title'))
                            <p class="text-danger">{{ $errors->first('title') }}</p>
                            @endif	
                        </div>
                        <div class="col-sm-8 leave-col">
                            <div class="form-group">
                                <label>Content</label>
                                <textarea type="text" id="content" class="form-control" name="content">{{ $cmsdata[0]->content }}</textarea>
                            </div>
                            @if ($errors->has('content'))
                            <p class="text-danger">{{ $errors->first('content') }}</p>
                            @endif
                        </div>	
                    </div>
                    <div class="row">
                        <div class="col-sm-6 leave-col">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select" name="status">
                                    <option value="">Select Status</option>
                                    <option value="1" {{ $cmsdata[0]->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $cmsdata[0]->status == 0 ? 'selected' : '' }}>Deactive</option>
                                </select>
                            </div>
                            @if ($errors->has('file_upload'))
                            <p class="text-danger">{{ $errors->first('file_upload') }}</p>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="updated_at" value="{{ date('Y-m-d H:i:s') }}">
					<div class="text-center">
						<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Update</button>
						<a href="{{ route('cms') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection