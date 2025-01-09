@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<style>
.card-body.policy_dv .form-group {
  border: 1px solid #ccc;
  padding: 60px;
  display: flex;
  align-content: center;
  justify-content: center;
  gap: 10px;
  flex-direction: column-reverse;
  text-align: center;
  min-height: 250px;
}
.card-body.policy_dv .form-group a i:before{
    font-size: 48px;
}
.card-body.policy_dv .form-group label {
  font-weight: 700;
  font-size: 18px;
}
.card-body.policy_dv .form-group.checkbox_e {
  flex-direction: inherit;
  padding: 10px;
  min-height: auto;
  border: 0px ;
}
.card-body.policy_dv .form-group.checkbox_e label{
    font-size: 14px;
    margin: 0px;
}
.card-body.policy_dv .form-group.checkbox_e input{
    width:25px;
    height:25px;
}
.card-header.policy_head h2 {
  font-size: 32px;
  font-weight: 600;
}
</style>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header policy_head">
                    <h2 class="card-title mb-0">Policy</h2>
                </div>
                <div class="card-body policy_dv">
                    @if (Session::has('error'))
                    <p class="text-danger">{{ Session::get('error') }}</p>
                    @endif
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <form action="{{ route('policyupdate') }}" method="POST">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>HR-Policy Leave Management</label>
                                    <div id="updateImg">
                                    <?php foreach ($policyData as $policy) {?>
                                        <input type="hidden" class="form-control" name="id" value="{{ $policy->id }}">
                                        <a href="{{ asset('policy/images/'.$policy->hr_policy_leave_mang) }}" target="_blank">
                                            <i style='font-size:24px' class="fa fa-eye" aria-hidden="true"></i>
                                        </a><br>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>HR-Process Onboarding</label>
                                    <div id="updateImg">
                                    <?php foreach ($policyData as $policy) {?>
                                        <a href="{{ asset('policy/images/'.$policy->hr_process_onboarding) }}" target="_blank">
                                            <i style='font-size:24px' class="fa fa-eye" aria-hidden="true"></i>
                                        </a><br>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>HR-Process Offboarding and Exit Process</label>
                                    <div id="updateImg">
                                    <?php foreach ($policyData as $policy) {?>
                                        <a href="{{ asset('policy/images/'.$policy->hr_process_offboarding) }}" target="_blank">
                                            <i style='font-size:24px' class="fa fa-eye" aria-hidden="true"></i>
                                        </a><br>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 leave-col">
                                <div class="form-group checkbox_e">
                                    <input type="checkbox" id="status" name="status" value="Agree">
                                    <label for="status"> I have read and agree to the Terms & Conditions.</label>
                                </div>
                                @if ($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Accept</button>
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