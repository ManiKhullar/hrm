@extends('layouts.layout')

@section('content')

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Approve Claim</h4>
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
                
                <form action="{{ url('approveclaimupdate/'.$claim->id) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control select" name="category" disabled>
                                    <option value="Broadband" {{ $claim->category == 'Broadband' ? 'selected' : '' }}>Broadband</option>
                                    <option value="Mobile" {{ $claim->category == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                                    <option value="TravelAllowance" {{ $claim->category == 'TravelAllowance' ? 'selected' : '' }}>TravelAllowance</option>
                                    <option value="Other" {{ $claim->category == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>	
                        </div>

                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>From</label>
                                <input type="text" id="from" class="form-control" name="from" value="{{ $claim->start_date }}" disabled>
                            </div>
                        </div>
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>To</label>
                                <input type="text" id="to" class="form-control" name="to" value="{{ $claim->end_date }}" disabled>
                            </div>
                        </div>	
                    </div>
                    <div class="row">
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" id="amount" class="form-control" name="amount" value="{{ $claim->amount }}" disabled>
                            </div>
                        </div>
                        <div class="col-sm-8 leave-col">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea id="description" class="form-control" name="description" disabled>{{ $claim->description }}</textarea>
                            </div>
                            @if ($errors->has('description'))
                            <p class="text-danger">{{ $errors->first('description') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>Upload Files</label>
                                <div id="updateImg">
                                <?php foreach ($claimImg as $value) {?>
                                    <a href="{{ asset('claims/images/'.$value->file_upload) }}" target="_blank"><img src="{{ asset('claims/images/'.$value->file_upload) }}" width="50" height="50"></a>
                                <?php }?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select" name="status">
                                    <option value="">select</option>
                                    <option value="Approve" {{ $claim->status == 'Approve' ? 'selected' : '' }}>Approved</option>
                                    <option value="Reject" {{ $claim->status == 'Reject' ? 'selected' : '' }}>Reject</option>
                                </select>
                            </div>
                            @if ($errors->has('status'))
                            <p class="text-danger">{{ $errors->first('status') }}</p>
                            @endif	
                        </div>
                        <div class="col-sm-4 leave-col">
                            <div class="form-group">
                                <label>Manager Comment</label>
                                <textarea id="manager_comment" class="form-control" name="manager_comment">{{ $claim->manager_comment }}</textarea>
                            </div>
                            @if ($errors->has('manager_comment'))
                            <p class="text-danger">{{ $errors->first('manager_comment') }}</p>
                            @endif	
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="updated_at" value="{{ date('Y-m-d H:i:s') }}">
					<div class="text-center">
						<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Update</button>
						<a href="{{ route('approveclaim') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection