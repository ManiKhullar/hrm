@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Email Template</h4>
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
                    <form action="{{ route('etemplatesave') }}" method="POST" enctype="multipart/form-data">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>Email Subject</label>
                                    <input type="text" id="subject" class="form-control" name="subject">
                                </div>
                                @if ($errors->has('subject'))
                                <p class="text-danger">{{ $errors->first('subject') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>Email Type</label>
                                    <input type="text" id="type" class="form-control" name="type">
                                </div>
                                @if ($errors->has('type'))
                                <p class="text-danger">{{ $errors->first('type') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>Email Content</label>
                                    <textarea class="form-control" id="content" name="content"></textarea>
                                </div>
                                @if ($errors->has('content'))
                                <p class="text-danger">{{ $errors->first('content') }}</p>
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
                            <h4 class="card-title mb-0">Email Template List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Subject</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($etemplates as $etemplate)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $etemplate->subject }}</td>
                                            <td>{{ $etemplate->type }}</td>
                                            <td>@if($etemplate->status == 1)
                                                    Active
                                                @else
                                                    Deactivate
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ url('etemplateedit/'.$etemplate->id.'/edit') }}">
                                                <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $etemplates->links() !!}
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
    // $(function() {
    //     $( "#from" ).datepicker({ 
    //         changeYear: true,
    //         minDate: '-3Y'
    //     });
    //     $( "#to" ).datepicker({ 
    //         changeYear: true,
    //         minDate: '-3Y'
    //     });
    // });
</script>
<script>
    // $(document).ready(function() {
    //     $('#category').on('change.mobileinput', function() {
    //         $("#mobileinput").toggle($(this).val() == 'Mobile');
    //     }).trigger('change.mobileinput');
    // });
</script>

@endsection