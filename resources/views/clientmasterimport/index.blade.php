@extends('layouts.layout')

@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Import Client Master Data</h4>
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

                    <form action="{{ route('clientmasterimport') }}" method="post" enctype="multipart/form-data">
                        @csrf    
                        <input type="file" name="file" />
                        @if ($errors->has('file'))
                        <p class="text-danger">{{ $errors->first('file') }}</p>
                        @endif
                        <input type="submit" class="btn btn-primary" name="import" value="IMPORT">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection