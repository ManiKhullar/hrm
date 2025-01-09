@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Client Master List</h4>
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
                    <form action="{{ route('clientmastersave') }}" method="POST" enctype="multipart/form-data">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Technology</label>
                                    <input type="text" id="technology" class="form-control" name="technology">
                                </div>
                                @if ($errors->has('technology'))
                                <p class="text-danger">{{ $errors->first('technology') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Interview Date</label>
                                    <input type="text" id="interview_date" class="form-control" name="interview_date">
                                </div>
                                @if ($errors->has('interview_date'))
                                <p class="text-danger">{{ $errors->first('interview_date') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Company</label>
                                    <input type="text" id="company" class="form-control" name="company">
                                </div>
                                @if ($errors->has('company'))
                                <p class="text-danger">{{ $errors->first('company') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" id="name" class="form-control" name="name">
                                </div>
                                @if ($errors->has('name'))
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Contact Person</label>
                                    <input type="text" id="contact_person" class="form-control" name="contact_person">
                                </div>
                                @if ($errors->has('contact_person'))
                                <p class="text-danger">{{ $errors->first('contact_person') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Client Email</label>
                                    <input type="text" id="client_email" class="form-control" name="client_email">
                                </div>
                                @if ($errors->has('client_email'))
                                <p class="text-danger">{{ $errors->first('client_email') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" id="contact_number" class="form-control" name="contact_number">
                                </div>
                                @if ($errors->has('contact_number'))
                                <p class="text-danger">{{ $errors->first('contact_number') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Source</label>
                                    <input type="text" id="source" class="form-control" name="source">
                                </div>
                                @if ($errors->has('source'))
                                <p class="text-danger">{{ $errors->first('source') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Rate</label>
                                    <input type="text" id="rate" class="form-control" name="rate">
                                </div>
                                @if ($errors->has('rate'))
                                <p class="text-danger">{{ $errors->first('rate') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Meeting Link</label>
                                    <textarea id="meeting_link" class="form-control" name="meeting_link"></textarea>
                                </div>
                                @if ($errors->has('meeting_link'))
                                <p class="text-danger">{{ $errors->first('meeting_link') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Status</label>
                                    <textarea id="status" class="form-control" name="status"></textarea>
                                </div>
                                @if ($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Interview Taken By</label>
                                    <input type="text" id="interview_taken_by" class="form-control" name="interview_taken_by">
                                </div>
                                @if ($errors->has('interview_taken_by'))
                                <p class="text-danger">{{ $errors->first('interview_taken_by') }}</p>
                                @endif
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-sm-6 leave-col">
                                <div class="form-group">
                                    <label>Pre Call Notes</label>
                                    <textarea id="pre_call_notes" class="form-control" name="pre_call_notes"></textarea>
                                </div>
                                @if ($errors->has('pre_call_notes'))
                                <p class="text-danger">{{ $errors->first('pre_call_notes') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-6 leave-col">
                                <div class="form-group">
                                    <label>Post Call Notes</label>
                                    <textarea id="post_call_notes" class="form-control" name="post_call_notes"></textarea>
                                </div>
                                @if ($errors->has('post_call_notes'))
                                <p class="text-danger">{{ $errors->first('post_call_notes') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 leave-col">
                                <div class="form-group">
                                    <label>End Client</label>
                                    <input type="text" id="end_client" class="form-control" name="end_client">
                                </div>
                                @if ($errors->has('end_client'))
                                <p class="text-danger">{{ $errors->first('end_client') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-6 leave-col">
                                <div class="form-group">
                                    <label>Interview Type</label>
                                    <input type="text" id="interview_type" class="form-control" name="interview_type">
                                </div>
                                @if ($errors->has('interview_type'))
                                <p class="text-danger">{{ $errors->first('interview_type') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
                            <a href="{{ route('clientmasterlist') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
                        </div>
                    </form>
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

@endsection