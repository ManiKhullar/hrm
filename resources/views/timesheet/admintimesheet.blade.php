@extends('layouts.layout')

@section('content')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Time Sheet For Employee</h4>
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
                    <form action="{{ route('admintimesheetsave') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Select Employee <span class="text-danger">*</span></label>
                                    <select class="form-control select select_emp" id="select_emp" name="select_emp">
                                        <option value="">Select Employee</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('select_emp'))
                                <p class="text-danger">{{ $errors->first('select_emp') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Select Project <span class="text-danger">*</span></label>
                                    <select class="form-control select select_project" id="select_project" name="select_project">
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('select_project'))
                                <p class="text-danger">{{ $errors->first('select_project') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Select Date <span class="text-danger">*</span></label>
                                    <input type="text" id="select_date" class="form-control datepicker" name="select_date">
                                </div>
                                @if ($errors->has('select_date'))
                                <p class="text-danger">{{ $errors->first('select_date') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-3 leave-col">
                                <div class="row">
                                    <div class="col-sm-6 col-6 leave-col">
                                        <div class="form-group">
                                            <label>Time (In Hours)</label>
                                            <select class="form-control select" id="hours" name="hours">
                                                <?php 
                                                foreach ($hours as $key => $hour) { ?>
                                                <option value="{{ $key }}">{{ $hour }}</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        @if ($errors->has('hours'))
                                        <p class="text-danger">{{ $errors->first('hours') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-sm-6 col-6 leave-col">
                                        <div class="form-group">
                                            <label>Time (In Minute)</label>
                                            <select class="form-control select" id="minutes" name="minutes">
                                                <?php 
                                                foreach ($minutes as $key => $minute) { ?>
                                                <option value="{{ $key }}">{{ $minute }}</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        @if ($errors->has('minutes'))
                                        <p class="text-danger">{{ $errors->first('minutes') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>   
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-12">
                                <div class="form-group">
                                    <label>Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                                @if ($errors->has('description'))
                                <p class="text-danger">{{ $errors->first('description') }}</p>
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
    $(document).ready(function() {
        $(function() {
            $("#select_date").datepicker();
        });
        $("#select_emp").select2();
        $("#select_project").select2();
    })
</script>

@endsection