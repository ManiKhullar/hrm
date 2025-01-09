@extends('layouts.layout')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $(document).on('click', '#ajax-delete', function (e) {
            var id = $(this).attr('data-id');
            $.ajax({ 
                url: "{{ route('deleteimg') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id" : id
                },
                dataType: "json",
                success: function(result)
                {
                    var html="";
                    for( var i = 0; i < result.length; ++i) {
                        html += "<i data-id="+result[i].id+" style='color: red;font-size: 20px;' class='fa fa-times-circle-o' id='ajax-delete' aria-hidden='true'></i><a href="+result[i].path+" target='_blank'><img src="+result[i].path+" width='50' height='50'></a><br>";
                    }
                    $("#updateImg").html(html);
                }
            });
        });
    });
</script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Update Claim</h4>
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
                
                <form action="{{ url('claimupdate/'.$claim->id) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 leave-col">
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control select" name="category">
                                    <option value="Broadband" {{ $claim->category == 'Broadband' ? 'selected' : '' }}>Broadband</option>
                                    <option value="Mobile" {{ $claim->category == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                                    <option value="TravelAllowance" {{ $claim->category == 'TravelAllowance' ? 'selected' : '' }}>TravelAllowance</option>
                                    <option value="Other" {{ $claim->category == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            @if ($errors->has('category'))
                            <p class="text-danger">{{ $errors->first('category') }}</p>
                            @endif	
                        </div>

                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>From</label>
                                <input type="text" id="from" class="form-control" name="from" value="{{ $claim->start_date }}">
                            </div>
                            @if ($errors->has('from'))
                            <p class="text-danger">{{ $errors->first('from') }}</p>
                            @endif
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>To</label>
                                <input type="text" id="to" class="form-control" name="to" value="{{ $claim->end_date }}">
                            </div>
                            @if ($errors->has('to'))
                            <p class="text-danger">{{ $errors->first('to') }}</p>
                            @endif
                        </div>	
                    </div>
                    <div class="row">
                        <div class="col-sm-6 leave-col">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" id="amount" class="form-control" name="amount" value="{{ $claim->amount }}">
                            </div>
                            @if ($errors->has('amount'))
                            <p class="text-danger">{{ $errors->first('amount') }}</p>
                            @endif
                        </div>
                        <div class="col-sm-6 leave-col">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea id="description" class="form-control" name="description">{{ $claim->description }}</textarea>
                            </div>
                            @if ($errors->has('description'))
                            <p class="text-danger">{{ $errors->first('description') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 leave-col">
                            <div class="form-group">
                                <label>File Upload</label>
                                <input type="file" id="file_upload" class="form-control" name="file_upload[]" multiple>
                                <div id="updateImg">
                                <?php foreach ($claimImg as $value) {?>
                                    <i data-id="{{ $value->id }}" id="ajax-delete" style="color: red;font-size: 20px;" class="fa fa-times-circle-o" aria-hidden="true"></i>
                                    <a href="{{ asset('claims/images/'.$value->file_upload) }}" target="_blank"><img src="{{ asset('claims/images/'.$value->file_upload) }}" width="50" height="50"></a><br>
                                <?php } ?>
                                </div>
                            </div>
                            @if ($errors->has('file_upload'))
                            <p class="text-danger">{{ $errors->first('file_upload') }}</p>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="updated_at" value="{{ date('Y-m-d H:i:s') }}">
					<div class="text-center">
						<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Update</button>
						<a href="{{ route('claim') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection