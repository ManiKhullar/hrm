@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<style>
    .tooltip {
        position: relative;
        display: inline-block;
        opacity: inherit;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 500px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        right: 0px;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }
</style>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Claim</h4>
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
                    <form action="{{ route('claimsave') }}" method="POST" enctype="multipart/form-data">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control select" id="category" name="category">
                                        <option value="Broadband">Broadband</option>
                                        <option value="Mobile">Mobile</option>
                                        <option value="TravelAllowance">TravelAllowance</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                @if ($errors->has('category'))
                                <p class="text-danger">{{ $errors->first('category') }}</p>
                                @endif	
                            </div>
                            <div id="mobileinput" class="col-sm-3 leave-col">
                                <div class="form-group">
                                    <label>Mobile Number</label>
                                    <input type="text" id="mobile" class="form-control" name="mobile">
                                </div>
                                @if ($errors->has('mobile'))
                                <p class="text-danger">{{ $errors->first('mobile') }}</p>
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
                        <div class="row">
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>File Upload</label>
                                    <input type="file" id="file_upload" class="form-control" name="file_upload[]" multiple>
                                </div>
                                @if ($errors->has('file_upload'))
                                <p class="text-danger">{{ $errors->first('file_upload') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" id="amount" class="form-control" name="amount">
                                </div>
                                @if ($errors->has('amount'))
                                <p class="text-danger">{{ $errors->first('amount') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea id="description" class="form-control" name="description"></textarea>
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
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Claim List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Category</th>
                                            <th>Amount</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Status</th>
                                            <th>Approved By</th>
                                            <th>Comment</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($claims as $claim)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $claim->category }}</td>
                                            <td>{{ $claim->amount }}</td>
                                            <td><?php echo date("d/m/Y",strtotime($claim->start_date)); ?></td>
                                            <td><?php echo date("d/m/Y",strtotime($claim->end_date)); ?></td>
                                            <td>{{ $claim->status }}</td>
                                            <td>{{ $claim->name }}</td>
                                            <td>
                                                <div class="tooltip"><?php echo substr($claim->manager_comment,0,10) ?>
                                                    <span class="tooltiptext">{{ $claim->manager_comment }}</span>
                                                </div>
                                            </td>
                                            <td> @if ($claim->status == 'Pending')
                                                    <a class="btn btn-primary" href="{{ url('claimedit/'.$claim->id.'/edit') }}">
                                                    <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $claims->links() !!}
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
<script>
    $(document).ready(function() {
        $('#category').on('change.mobileinput', function() {
            $("#mobileinput").toggle($(this).val() == 'Mobile');
        }).trigger('change.mobileinput');
    });
</script>

@endsection