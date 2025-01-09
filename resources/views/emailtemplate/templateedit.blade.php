@extends('layouts.layout')

@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Update Email Template</h4>
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
                
                <form action="{{ url('etemplateupdate/'.$template->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-6 leave-col">
                            <div class="form-group">
                                <label>Email Subject</label>
                                <input type="text" id="subject" class="form-control" name="subject" value="{{ $template->subject }}">
                            </div>
                            @if ($errors->has('subject'))
                            <p class="text-danger">{{ $errors->first('subject') }}</p>
                            @endif	
                        </div>
                        <div class="col-sm-6 leave-col">
                            <div class="form-group">
                                <label>Email Type</label>
                                <input type="text" id="type" class="form-control" name="type" value="{{ $template->type }}">
                            </div>
                            @if ($errors->has('type'))
                            <p class="text-danger">{{ $errors->first('type') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 leave-col">
                            <div class="form-group">
                                <label>Email Content</label>
                                <textarea class="form-control" id="content" name="content">{{ $template->content }}</textarea>
                            </div>
                            @if ($errors->has('content'))
                            <p class="text-danger">{{ $errors->first('content') }}</p>
                            @endif
                        </div>
                        <div class="col-sm-6 leave-col">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select" name="status">
                                    <option value="1" {{ $template->status == 1 ? 'selected' : '' }}>Active</option>
			                        <option value="0" {{ $template->status == 0 ? 'selected' : '' }}>Deactivate</option>
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
						<a href="{{ route('etemplate') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection