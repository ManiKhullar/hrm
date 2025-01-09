@extends('layouts.layout')
@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
   <div class="row">
      <div class="col-md-12">
         <div class="card ctm-border-radius shadow-sm grow">
            <div class="card-header">
               <h4 class="card-title mb-0">Add Manager</h4>
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
               <form action="{{ route('managersavedata') }}" method="POST">
                  @csrf
                  <div class="row">
                     <div class="col-sm-6 leave-col">
                        <div class="form-group">
                           <label>Manager Name</label>
                           <input type="text" class="form-control" name="manager_name">
                        </div>
                        @if ($errors->has('manager_name'))
                        <p class="text-danger">{{ $errors->first('manager_name') }}</p>
                        @endif	
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label>
                           Skill Type
                           <span class="text-danger">*</span>
                           </label>
                           <select class="form-control select" multiple name="skill_type[]">
                              <option value="">Select</option>
                              @foreach ($skills as $skill)
                              <option value="{{ $skill->id }}">{{ $skill->skill_name }}</option>
                              @endforeach
                           </select>
                        </div>
                        @if ($errors->has('skill_type'))
                        <p class="text-danger">{{ $errors->first('skill_type') }}</p>
                        @endif	
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-sm-6">
                        <div class="form-group">
                           <label>
                           Status
                           <span class="text-danger">*</span>
                           </label>
                           <select class="form-control select" name="status">
                              <option value="1">Active</option>
                              <option value="0">Deactive</option>
                           </select>
                        </div>
                        @if ($errors->has('status'))
                        <p class="text-danger">{{ $errors->first('status') }}</p>
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
                     <h4 class="card-title mb-0">Manager List</h4>
                  </div>
                  <div class="card-body">
                     <div class="employee-office-table">
                        <div class="table-responsive">
                           <table class="table custom-table mb-0">
                              <tr>
                                 <th>No</th>
                                 <th>Manager Name</th>
                                 <th>Skill Type</th>
                                 <th>Status</th>
                                 <th width="280px">Action</th>
                              </tr>
                              @foreach ($managers as $manager)
                              <tr>
                                 <td>{{ ++$i }}</td>
                                 <td>{{ $manager->manager_name }}</td>
                                 <td>
                                    @foreach(explode(',', $manager->skill_type) as $info)
                                    @foreach ($skills as $skill)
                                    @if ($info == $skill->id)
                                    {{ $skill->skill_name.', ' }}
                                    @endif
                                    @endforeach 
                                    @endforeach
                                 </td>
                                 <td>
                                    @if($manager->status == 1)
                                    <button type="button" class="btn btn-success">Active</button>
                                    @else
                                    <button type="button" class="btn btn-danger">Deactivate</button>
                                    @endif
                                 </td>
                                 <td>
                                    <a class="btn btn-primary" href="{{ url('manageredit/'.$manager->id.'/edit') }}">Edit</a>
                                    <a class="btn btn-sm btn-outline-danger" href="managerdelete/{{$manager->id}}/delete"><span class="lnr lnr-trash"></span>Delete</a> 
                                 </td>
                              </tr>
                              @endforeach
                           </table>
                           {!! $managers->links() !!}
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