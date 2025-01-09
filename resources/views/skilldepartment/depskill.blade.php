@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Department Skill</h4>
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
                    <form action="{{ route('depskillsave') }}" method="POST">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
									<label>Skill Name</label>
                                    <select class="form-control select" name="skill_name">
                                        <option value="">Select Skill</option>
                                        <option value="Php">Php</option>
                                        <option value="Java">Java</option>
                                        <option value="Python">Python</option>
                                        <option value="MsDynamics">MsDynamics</option>
                                        <option value="UI/UX">UI/UX</option>
                                    </select>
								</div>
								@if ($errors->has('skill_name'))
								<p class="text-danger">{{ $errors->first('skill_name') }}</p>
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
                            <h4 class="card-title mb-0">Skill List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Skill Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($depskills as $depskill)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $depskill->skill_name }}</td>
                                            <td>@if($depskill->status == 1)
                                                    Active
                                                @else
                                                    Deactivate
                                                @endif
                                            </td>
                                            <td>
                                                @if($depskill->status == 0)
                                                    <a onclick="return confirm('Are you want to Active?')" href="{{ url('depskillupdate/'.$depskill->id.'/1') }}">Active</a>
                                                @else
                                                    <a onclick="return confirm('Are you want to Deactive?')" href="{{ url('depskillupdate/'.$depskill->id.'/0') }}">Deactivate</a>
                                                @endif
                                                
                                                <!-- <a class="btn btn-primary" href="{{ url('depskilledit/'.$depskill->id.'/edit') }}">
                                                    <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a> -->
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $depskills->links() !!}
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