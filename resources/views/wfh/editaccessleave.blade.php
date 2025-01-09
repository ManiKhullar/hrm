
@extends('layouts.layout')
 
 @section('content')
 
 
 <div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
       
       <div class="col-md-9 d-flex">
          <div class="card ctm-border-radius shadow-sm flex-fill">
             <div class="card-header">
                <h4 class="card-title mb-0">
                Wfh Edit
                </h4>
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

            
          
             <form  action="{{ url('update_wfh/'.$leave[0]->id) }}" method="post">
             @csrf
             @method('PUT')
             <div class="row">
                                 
                 <div class="col-sm-6">
                     <div class="form-group">
                         <label>CC</label>
                         <span class="text-danger">*</span>
                         <?php echo $leave[0]->cc; ?>
                         @if ($errors->has('project_manager'))
                         <p class="text-danger">{{ $errors->first('project_manager') }}</p>
                         @endif
                     </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-sm-6">
                     <div class="form-group">
                         <label>From</label>
                         <span class="text-danger">*</span>
                         <?php echo date("d/m/Y",strtotime($leave[0]->start_date)); ?>
                         @if ($errors->has('start_date'))
                         <p class="text-danger">{{ $errors->first('start_date') }}</p>
                         @endif
                     </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-sm-6 leave-col">
                     <div class="form-group">
                         <label>To</label>
                         <span class="text-danger">*</span>
                         <?php echo date("d/m/Y",strtotime($leave[0]->end_date)); ?>
                         @if ($errors->has('end_date'))
                         <p class="text-danger">{{ $errors->first('end_date') }}</p>
                         @endif
                     </div>
                 </div>
                 
             </div>
             <div class="row">
                 <div class="col-sm-12">
                     <div class="form-group mb-0">
                         <label>Reason</label>
                         <span class="text-danger">*</span>
                         <p>{{$leave[0]->message}}</p>
                         @if ($errors->has('message'))
                         <p class="text-danger">{{ $errors->first('message') }}</p>
                         @endif
                     </div>
                 </div>
                 </div>
             <div class="row">
                 <div class="col-sm-12">
                     <div class="form-group mb-0">
                         <label>Comment</label>
                         
                         <textarea class="form-control" name="comment" rows="4">{{$leave[0]->comment}}</textarea>
                         @if ($errors->has('message'))
                         <p class="text-danger">{{ $errors->first('comment') }}</p>
                         @endif
                     </div>
                 </div>
                 <?php 
                 use Illuminate\Support\Facades\Auth;
                  $roleType = Auth::user()->role;
                 if($roleType == 4 || $roleType ==5 || $roleType ==6 || $roleType ==3){
                 ?>
                 <div class="col-sm-12 leave-col">
                     <div class="form-group">
                         <label>Status</label>
                         <span class="text-danger">*</span>
                         <select class="form-control select select2-hidden-accessible" name="status" tabindex="-1" aria-hidden="true">
                         <option value="">Select Session</option>
                         <option value="1" <?php if($leave[0]->status == 1){ echo "selected";} ?>>Approved</option>
                         <option value="2" <?php if($leave[0]->status == 2){ echo "selected";} ?>>Reject</option>
                         </select>
                         @if ($errors->has('end_session'))
                         <p class="text-danger">{{ $errors->first('end_session') }}</p>
                         @endif
                     </div>
                 </div>
                 <?php }else{ ?>
                  <input type="hidden" name="status" value="0" />
                 <?php } ?>
             </div>
             <div class="text-center">
                 <input type="submit" href="javascript:void(0);" value="Update" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">
                 <a href="{{ route('accesswfhlist') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Back</a>
             </div>
             </form>
       </div>
    </div>
 </div>
 
 @endsection