@extends('layouts.layout')

@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Change Password</h4>
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
                    <form action="{{ route('changepasswordsave') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Current Password</label>
                                 <input type="password" class="form-control" name="current_password">
                                </div>
                                @if ($errors->has('current_password'))
                                <p class="text-danger">{{ $errors->first('current_password') }}</p>
                                @endif	
                            </div>	
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>New Password</label>
                                 <input type="password" class="form-control" name="new_password">
                                </div>
                                @if ($errors->has('new_password'))
                                <p class="text-danger">{{ $errors->first('new_password') }}</p>
                                @endif	
                            </div>	

                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>New Password Confirmation</label>
                                 <input type="password" class="form-control" name="new_password_confirmation">
                                </div>
                                @if ($errors->has('new_password_confirmation'))
                                <p class="text-danger">{{ $errors->first('new_password_confirmation') }}</p>
                                @endif	
                            </div>	
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
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