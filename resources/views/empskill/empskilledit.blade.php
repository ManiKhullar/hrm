@extends('layouts.layout')

@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Update Holiday</h4>
                </div>
                <div class="card-body">
                   @if ($errors->any())
                   <div class="alert alert-danger">
                    <strong>Error!</strong> <br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form action="{{ url('empskillupdate/'.$empskills->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Skill Name</label>
                                @foreach ($skillNames as $skillName)
                                    @if ($empskills->skill_id == $skillName->id)
                                        <input type="text" id="skill_id" class="form-control" name="skill_id" value="{{ $skillName->skill_name }}" disabled>
                                    @endif
                                @endforeach
                            </div>
                            @if ($errors->has('skill_id'))
                            <p class="text-danger">{{ $errors->first('skill_id') }}</p>
                            @endif 
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Skill Level</label>
                                <select class="form-control select" name="skill_level">
                                    <option value="">Select Skill Level</option>
                                    <option value="Beginner" {{ $empskills->skill_level == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="Proficient" {{ $empskills->skill_level == 'Proficient' ? 'selected' : '' }}>Proficient</option>
                                    <option value="Expert" {{ $empskills->skill_level == 'Expert' ? 'selected' : '' }}>Expert</option>
                                </select>
                            </div>
                            @if ($errors->has('skill_level'))
                            <p class="text-danger">{{ $errors->first('skill_level') }}</p>
                            @endif
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Experience</label>
                                <input type="text" id="experience" class="form-control" name="experience" value="{{ $empskills->experience }}">
                            </div>
                            @if ($errors->has('experience'))
                            <p class="text-danger">{{ $errors->first('experience') }}</p>
                            @endif	
                        </div>
                        <div class="col-sm-3 leave-col">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select" name="status">
                                    <option value="">Select</option>
                                    <option value="1" {{ $empskills->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $empskills->status == 0 ? 'selected' : '' }}>Deactivate</option>
                                </select>
                            </div>
                            @if ($errors->has('status'))
                            <p class="text-danger">{{ $errors->first('status') }}</p>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="updated_at" value="{{ date('Y-m-d H:i:s') }}">
					<div class="text-center">
						<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Update</button>
						<a href="{{ route('empskill') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection