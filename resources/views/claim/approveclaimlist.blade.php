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
    
    .claim_admin {
        display: flex;
        align-items: center;
        justify-content: end;
        margin-left: auto;
        width: 50%;
        gap: 10px;
        margin-bottom: 20px;
    }

    .claim_admin button.btn.btn-theme.button-1.text-white.ctm-border-radius.mt-4.claim_search {
        margin: 0px !important;
    }

    .claim_admin input {
        width: 226px;
    }
</style>

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
                    <form action="{{ route('claimfilter') }}" method="GET">
						<div class="claim_admin">
							<input type="text" id="search" class="form-control" name="search" placeholder="search"
                                value="<?php
                                    if(isset($_GET['search']) && ($_GET['search']) != ''){
                                       echo $_GET['search'];
                                    }
                                ?>"
                            >
							<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4 claim_search">Search</button>
						</div>
					</form>
                </div>
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <h4 class="card-title mb-0">All Claim List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($claimList as $claim)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $claim->name }}</td>
                                            <td>{{ $claim->amount }}</td>
                                            <td><?php echo date("d/m/Y",strtotime($claim->start_date)); ?></td>
                                            <td><?php echo date("d/m/Y",strtotime($claim->end_date)); ?></td>
                                            <td>
                                                <div class="tooltip"><?php echo substr($claim->description,0,10) ?>
                                                    <span class="tooltiptext">{{ $claim->description }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $claim->status }}</td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ url('approveclaimedit/'.$claim->id.'/edit') }}">
                                                <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $claimList->links() !!}
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