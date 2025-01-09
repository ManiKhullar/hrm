@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">

                <div class="card-body">
                    @if (Session::has('error'))
                    <p class="text-danger">{{ Session::get('error') }}</p>
                    @endif
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <form action="{{ route('clientmasterfilter') }}" method="GET">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12 leave-col">
                                    <div class="form-group">
                                        <label>Technology</label>
                                        <input type="text" id="technology" class="form-control" name="technology"
                                            value="<?php 
                                                if(isset($_GET['technology']) && ($_GET['technology']) != ''){ 
                                                    echo $_GET['technology']; 
                                                }else{
                                                }
                                            ?>"
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12 leave-col">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <input type="text" id="company" class="form-control" name="company"
                                            value="<?php 
                                                if(isset($_GET['company']) && ($_GET['company']) != ''){ 
                                                    echo $_GET['company']; 
                                                }else{
                                                }
                                            ?>"
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-6 col-12 leave-col">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" id="name" class="form-control" name="name"
                                            value="<?php 
                                                if(isset($_GET['name']) && ($_GET['name']) != ''){ 
                                                    echo $_GET['name']; 
                                                }else{
                                                }
                                            ?>"
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-6 col-12 leave-col">
                                    <div class="form-group">
                                        <label>Interview Taken By</label>
                                        <input type="text" id="interview_taken_by" class="form-control" name="interview_taken_by"
                                            value="<?php 
                                                if(isset($_GET['interview_taken_by']) && ($_GET['interview_taken_by']) != ''){ 
                                                    echo $_GET['interview_taken_by']; 
                                                }else{
                                                }
                                            ?>"
                                        >
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-6 col-12 leave-col">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <a class="btn btn-theme button-1 text-white ctm-border-radius mt-4" href="{{ route('clientmasteradd') }}">Add Client Master</a>
                            <h4 class="card-title mb-0">Client Master List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Technology</th>
                                            <th>Interview Date</th>
                                            <th>Company</th>
                                            <th>Name</th>
                                            <th>Contact Person</th>
                                            <th>Contact Number</th>
                                            <th>Client Email</th>
                                            <th>Action</th>
                                        </tr>
                                        <?php $i=0; ?>
                                        @foreach ($clientMaster as $data)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $data->technology }}</td>
                                            <td>{{ $data->interview_date }}</td>
                                            <td>{{ $data->company }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->contact_person }}</td>
                                            <td>{{ $data->contact_number }}</td>
                                            <td>{{ $data->client_email }}</td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ url('clientmasteredit/'.$data->id.'/edit') }}">
                                                    <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $clientMaster->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
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