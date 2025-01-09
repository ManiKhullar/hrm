@extends('layouts.layout')

@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Working hours details</h4>
                </div>
                <div class="card-body">
                    <div class="employee-office-table">
                        <div class="table-responsive">
                            <table class="table custom-table mb-0">
                                <tr>
                                    <th>No</th>
                                    <th>Work Hours</th>
                                    <th>Location</th>
                                    <th>Login Time</th>
                                    <th>Logout Time</th>
                                </tr>
                                <?php $i = 0; ?>
                                @foreach ($sessionLoginData as $login)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $login->work_hours }}</td>
                                    <td>{{ $login->location }}</td>
                                    <td>{{ date('d-m-Y h:i:s A', strtotime($login->created_at)) }}</td>
                                    <td>{{ date('d-m-Y h:i:s A', strtotime($login->updated_at)) }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection