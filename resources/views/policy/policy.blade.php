@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Policy</h4>
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
                    <form action="{{ route('policysave') }}" method="POST" enctype="multipart/form-data">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>HR-Policy Leave Management</label>
                                    <input type="file" id="hr_policy_leave_mang" class="form-control" name="hr_policy_leave_mang">
                                    <div id="updateImg">
                                    <?php foreach ($policyData as $policy) {?>
                                        <input type="hidden" class="form-control" name="id" value="{{ $policy->id }}">
                                        <a href="{{ asset('policy/images/'.$policy->hr_policy_leave_mang) }}" target="_blank">
                                            <i style='font-size:24px' class="fa fa-eye" aria-hidden="true"></i>
                                        </a><br>
                                    <?php } ?>
                                    </div>
                                </div>
                                @if ($errors->has('hr_policy_leave_mang'))
                                <p class="text-danger">{{ $errors->first('hr_policy_leave_mang') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>HR-Process Onboarding</label>
                                    <input type="file" id="hr_process_onboarding" class="form-control" name="hr_process_onboarding">
                                    <div id="updateImg">
                                    <?php foreach ($policyData as $policy) {?>
                                        <a href="{{ asset('policy/images/'.$policy->hr_process_onboarding) }}" target="_blank">
                                            <i style='font-size:24px' class="fa fa-eye" aria-hidden="true"></i>
                                        </a><br>
                                    <?php } ?>
                                    </div>
                                </div>
                                @if ($errors->has('hr_process_onboarding'))
                                <p class="text-danger">{{ $errors->first('hr_process_onboarding') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>HR-Process Offboarding and Exit Process</label>
                                    <input type="file" id="hr_process_offboarding" class="form-control" name="hr_process_offboarding">
                                    <div id="updateImg">
                                    <?php foreach ($policyData as $policy) {?>
                                        <a href="{{ asset('policy/images/'.$policy->hr_process_offboarding) }}" target="_blank">
                                            <i style='font-size:24px' class="fa fa-eye" aria-hidden="true"></i>
                                        </a><br>
                                    <?php } ?>
                                    </div>
                                </div>
                                @if ($errors->has('hr_process_offboarding'))
                                <p class="text-danger">{{ $errors->first('hr_process_offboarding') }}</p>
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