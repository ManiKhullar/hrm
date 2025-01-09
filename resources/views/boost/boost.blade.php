@extends('layouts.layout')

@section('content')

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Delete History</h4>
                </div>
                <div class="card-body">
                    @if (Session::has('error'))
                    <div class="alert alert-danger">
                        <p class="text-danger">{{ Session::get('error') }}</p>
                    </div>
                    @endif
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <form action="{{ route('boostserver') }}" method="POST">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control select" id="category" name="category">
                                        <option value="Timesheet">Time Sheet</option>
                                        <option value="Leavelist">Leave List</option>
                                        <option value="Claimlist">Claim List</option>
                                        <option value="Salarylist">Salary List</option>
                                        <option value="Loginlist">Login List</option>
                                    </select>
                                </div>
                                @if ($errors->has('category'))
                                <p class="text-danger">{{ $errors->first('category') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="text" id="from" class="form-control" name="from" value="<?php echo date('m/d/Y'); ?>">
                                </div>
                                @if ($errors->has('from'))
                                <p class="text-danger">{{ $errors->first('from') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="text" id="to" class="form-control" name="to" value="<?php echo date('m/d/Y'); ?>">
                                </div>
                                @if ($errors->has('to'))
                                <p class="text-danger">{{ $errors->first('to') }}</p>
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
<script>
    $(function() {
        $( "#from" ).datepicker({ 
            changeYear: true,
            minDate: '-3Y'
        });
        $( "#to" ).datepicker({ 
            changeYear: true,
            minDate: '-3Y'
        });
    });
</script>

@endsection