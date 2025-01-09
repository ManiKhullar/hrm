@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Employee Skill</h4>
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
                    <form action="{{ route('empskillsave') }}" method="POST">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
									<label>Skill Name</label>
                                    <select class="form-control select" name="skill_id">
                                        <option value="">Select Skill Name</option>
                                        @foreach ($skillNames as $skillName)
                                            <option value="{{$skillName->id}}">{{$skillName->skill_name}}</option>
                                        @endforeach
                                    </select>
								</div>
								@if ($errors->has('skill_id'))
								<p class="text-danger">{{ $errors->first('skill_id') }}</p>
								@endif 
                            </div>
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
									<label>Skill Level</label>
                                    <select class="form-control select" name="skill_level">
                                        <option value="">Select Skill Level</option>
                                        <option value="Beginner">Beginner</option>
                                        <option value="Proficient">Proficient</option>
                                        <option value="Expert">Expert</option>
                                    </select>
								</div>
								@if ($errors->has('skill_level'))
								<p class="text-danger">{{ $errors->first('skill_level') }}</p>
								@endif
                            </div>
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>Experience</label>
                                    <input type="text" id="experience" class="form-control" name="experience">
                                </div>
                                @if ($errors->has('experience'))
                                <p class="text-danger">{{ $errors->first('experience') }}</p>
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
                            <h4 class="card-title mb-0">Employee Skill List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Skill Name</th>
                                            <th>Employee Name</th>
                                            <th>Skill Level </th>
                                            <th>Experience</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($empskills as $empskill)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $empskill->skill_name }}</td>
                                            <td>{{ $empskill->name }}</td>
                                            <td>{{ $empskill->skill_level }}</td>
                                            <td>{{ $empskill->experience }}</td>
                                            <td>@if($empskill->status == 1)
                                                    Active
                                                @else
                                                    Deactivate
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ url('empskilledit/'.$empskill->id.'/edit') }}">
                                                    <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                                <a class="btn btn-danger" href="{{ url('empskilldelete/'.$empskill->id.'/delete') }}">
                                                    <i style='font-size:24px' class="lnr lnr-trash" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $empskills->links() !!}
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