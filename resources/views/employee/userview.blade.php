@extends('layouts.layout')

@section('content')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<style>
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }
    .card.flex-fill.ctm-border-radius.shadow-sm.grow.empl_view_dv input {
        color: #201c1c;
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: left;
        display: block;
        width: 10px;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>
<div class="col-xl-9 col-lg-8  col-md-12">
    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
            <div class="card flex-fill ctm-border-radius shadow-sm grow empl_view_dv">
                <div class="card-header">
                    <h4 class="card-title mb-0">Basic Information</h4>
                </div>
               
                <div class="card-body text-center">
                    <p class="card-text mb-3"><span class="text-primary"> Name : </span>{{ucwords($employeeData[0]->name)}}</p>
                    <p class="card-text mb-3"><span class="text-primary"> Email : </span>{{$employeeData[0]->email}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Date of Birth :</span><?php echo date("d/m/Y", strtotime($employeeData[0]->dob)); ?></p>
                    <p class="card-text mb-3"><span class="text-primary">Gender : </span>{{ucwords($employeeData[0]->gender)}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Blood Group :</span>{{$employeeData[0]->blood_group}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Date of Joining :</span><?php echo date("d/m/Y", strtotime($employeeData[0]->joining_date)); ?></p>
                    <p class="card-text mb-3"><span class="text-primary">Employee Type :</span>{{ucwords($employeeData[0]->employment_type)}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Employee Band :</span>{{$employeeData[0]->employee_band}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Job Title :</span>{{ucwords($employeeData[0]->job_title)}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Shift :</span>{{ucwords($employeeData[0]->shift_name)}}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
            <div class="card flex-fill  ctm-border-radius shadow-sm grow empl_view_dv">
                <div class="card-header">
                    <h4 class="card-title mb-0">Account Details</h4>
                </div>
                <div class="card-body text-center">
                    <p class="card-text mb-3"><span class="text-primary">Bank Name : </span>{{ ucwords($employeeData[0]->bank_name) }}</p>
                    <p class="card-text mb-3"><span class="text-primary">Account No : </span>{{$employeeData[0]->acc_no}}</p>
                    <p class="card-text mb-3"><span class="text-primary">IFSC Code : </span>{{$employeeData[0]->ifsc}}</p>
                    
                    <form id="accountDetailsform">
                        @csrf

                        <div id="accountDetailsModal" class="modal">
                            <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ $employeeData[0]->user_id }}">

                            <!-- Modal content -->
                            <div class="modal-content">
                                <span class="close" id="account_model">&times;</span>
                                <p class="msg"></p>
                                <div class="row">
                                    <div class="col-md-3 col-6 form-group">
                                        <label>Bank Name</label>
                                        <input type="text" required class="form-control" name="bank_name" id="bank_name" value="{{$employeeData[0]->bank_name}}">
                                        <p id="bank_name_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-md-3 col-6 form-group">
                                        <label>Account Number</label>
                                        <input type="text" required class="form-control" name="acc_no" id="acc_no" value="{{$employeeData[0]->acc_no}}">
                                        <p id="acc_no_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-md-3 col-6 form-group">
                                        <label>IFSC</label>
                                       
                                        <input type="text"  class="form-control" name="ifsc" id="ifsc" value="{{$employeeData[0]->ifsc}}">
                                        <p id="ifsc_error" class="text-danger"></p>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <p id="accountDetail" type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4"  >Submit</p>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="multi_dlex">
                        <div class="uniq_btn_item  ">
                            <input type="submit" onclick="empFunction('accountDetailsModal','account_model')" class="multiple_btn" id="{{ $employeeData[0]->user_id }}" value="Edit">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
            <div class="card flex-fill  ctm-border-radius shadow-sm grow empl_view_dv">
                <div class="card-header">
                    <h4 class="card-title mb-0">Current Address</h4>
                </div>
                <div class="card-body text-center">
                    <p class="card-text mb-3"><span class="text-primary">Street : </span>{{$employeeData[0]->street}}</p>
                    <p class="card-text mb-3"><span class="text-primary">City : </span>{{$employeeData[0]->city}}</p>
                    <p class="card-text mb-3"><span class="text-primary">State : </span>{{$employeeData[0]->state}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Country : </span>{{$employeeData[0]->country}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Pincode : </span>{{$employeeData[0]->pincode}}</p>
                    
                    <form id="empform">
                        @csrf

                        <div id="empModal" class="modal">
                            <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ $employeeData[0]->user_id }}">

                            <!-- Modal content -->
                            <div class="modal-content">
                                <span class="close" id="employee_model">&times;</span>
                                
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <label>Street address</label>
                                        <input type="text" class="form-control" name="street" id="street" value="{{$employeeData[0]->street}}">
                                        <p id="street_error" class="text-danger"></p>
                                    </div>
                                    
                                    <div class="col-md-6 form-group">
                                        <label>Street state</label>
                                        <select class="form-control select" name="state"  id="statelist">
                                            <option value="" selected>Select State</option>
                                            @foreach($state as $statedate)
                                            <option value="{{$statedate->state}}" {{ $statedate->state == $employeeData[0]->state ? 'selected' : '' }}>{{$statedate->state}}</option>
                                            @endforeach
                                        </select>
                                        <p id="statelist_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>Street city</label>
                                        <select class="form-control select" name="city" id="citylist">
                                        <option value="" selected>Select State</option>
                                            <?php 
                                            if(isset($cityCollection[$employeeData[0]->state])){
                                            foreach($cityCollection[$employeeData[0]->state] as $citydata){?>
                                            <option value="<?php echo $citydata; ?>" <?php if($employeeData[0]->city==$citydata){echo "selected"; }?>><?php echo $citydata; ?></option>
                                            <?php }} ?>
                                        </select>
                                        <p id="citylist_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>Pincode</label>
                                        <input type="text" class="form-control" name="pincode" id="pincode" value="{{$employeeData[0]->pincode}}">
                                        <p id="pincode_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>Country</label>
                                        <input type="text" class="form-control" name="country" id="country" value="{{$employeeData[0]->country}}">
                                        <p id="country_error" class="text-danger"></p>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <p id="empformdata" type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4" >Submit</p>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="multi_dlex">
                        <div class="uniq_btn_item "muliple_edit_btn>
                            <input type="submit" onclick="empFunction('empModal','employee_model')" class="multiple_btn" id="{{ $employeeData[0]->user_id }}" value="Edit">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
            <div class="card flex-fill  ctm-border-radius shadow-sm grow empl_view_dv">
                <div class="card-header">
                    <h4 class="card-title mb-0">Permanent Address</h4>
                </div>
                <div class="card-body text-center">
                    <p class="card-text mb-3"><span class="text-primary">Street : </span>{{$employeeData[0]->p_street}}</p>
                    <p class="card-text mb-3"><span class="text-primary">City : </span>{{$employeeData[0]->p_city}}</p>
                    <p class="card-text mb-3"><span class="text-primary">State : </span>{{$employeeData[0]->p_state}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Country : </span>{{$employeeData[0]->p_country}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Pincode : </span>{{$employeeData[0]->p_pincode}}</p>
                    
                    <form id="perAddressforms">
                        @csrf

                        <div id="perAddressModal" class="modal">
                            <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ $employeeData[0]->user_id }}">

                            <!-- Modal content -->
                            <div class="modal-content">
                                <span class="close" id="per_employee_model">&times;</span>
                                
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <label>Street address</label>
                                        <input type="text" class="form-control" name="p_street" id="p_street" value="{{$employeeData[0]->p_street}}">
                                        <p id="p_street_error" class="text-danger"></p>
                                    </div>
                                    
                                    <div class="col-md-6 form-group">
                                        <label>Street state</label>
                                        <select class="form-control select" name="p_state" id="p_statelist">
                                            <option value="" selected>Select State</option>
                                            @foreach($state as $statedate)
                                            <option value="{{$statedate->state}}" {{ $statedate->state == $employeeData[0]->p_state ? 'selected' : '' }}>{{$statedate->state}}</option>
                                            @endforeach
                                        </select>
                                        <p id="p_street_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>Street city</label>
                                        <select class="form-control select" name="p_city" id="p_citylist"> 
                                        <option value="" selected>Select State</option>
                                        
                                            <?php 
                                            if(isset($cityCollection[$employeeData[0]->p_state])){
                                            foreach($cityCollection[$employeeData[0]->p_state] as $citydata){?>
                                            <option value="<?php echo $citydata; ?>" <?php if($employeeData[0]->p_city==$citydata){echo "selected"; }?>><?php echo $citydata; ?></option>
                                            <?php } }?>
                                            </select>
                                        <p id="p_citylist_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>Pincode</label>
                                        <input type="text" class="form-control" name="p_pincode" id="p_pincode" value="{{$employeeData[0]->p_pincode}}">
                                        <p id="p_pincode_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>Country</label>
                                        <input type="text" class="form-control" name="p_country" id="p_country" value="{{$employeeData[0]->p_country}}">
                                        <p id="p_country_error" class="text-danger"></p>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <p id="perAddressform" type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4" >Submit</p>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="multi_dlex">
                        <div class="uniq_btn_item "muliple_edit_btn>
                            <input type="submit" onclick="empFunction('perAddressModal','per_employee_model')" class="multiple_btn" id="{{ $employeeData[0]->user_id }}" value="Edit">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
            <div class="card flex-fill  ctm-border-radius shadow-sm grow empl_view_dv">
                <div class="card-header">
                    <h4 class="card-title mb-0">Family Details</h4>
                </div>
                <div class="card-body text-center">
                    <p class="card-text mb-3"><span class="text-primary">Father Name : </span>{{ucwords($employeeData[0]->father_name)}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Mother Name : </span>{{ucwords($employeeData[0]->mother_name)}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Spouse Name : </span>{{ucwords($employeeData[0]->spouse_name)}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Number Type : </span><?php
                      if($employeeData[0]->number_type ==1){
                        echo "Mother";
                      }elseif($employeeData[0]->number_type ==2){
                        echo "Father";
                      }elseif($employeeData[0]->number_type ==3){
                        echo "Spouse";
                      }else {
                        echo "";
                      }
                    ?></p>
                    <p class="card-text mb-3"><span class="text-primary">Contact Number : </span>{{$employeeData[0]->contact_number}}</p>
                    
                    <form id="familyform">
                        @csrf

                        <div id="familyModal" class="modal">
                            <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ $employeeData[0]->user_id }}">

                            <!-- Modal content -->
                            <div class="modal-content">
                                <span class="close" id="Family_model">&times;</span>
                                
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <label>Father Name</label>
                                        <input type="text" class="form-control" name="father_name" id="father_name" value="{{$employeeData[0]->father_name}}">
                                        <p id="father_name_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>Mother Name</label>
                                        <input type="text" class="form-control" name="mother_name" id="mother_name" value="{{$employeeData[0]->mother_name}}">
                                        <p id="mother_name_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>Spouse Name</label>
                                        <input type="text" class="form-control" name="spouse_name" id="spouse_name" value="{{$employeeData[0]->spouse_name}}">
                                        <p id="spouse_name_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Phone Number Type</label>
                                        <select class="form-control select" name="number_type" id="number_type">
                                            <option value="">Phone Number Type</option>
                                            <option value="1" {{ $employeeData[0]->number_type == 1 ? 'selected' : '' }}>Mother</option>
                                            <option value="2" {{ $employeeData[0]->number_type == 2 ? 'selected' : '' }}>Father</option>
                                            <option value="3" {{ $employeeData[0]->number_type == 3 ? 'selected' : '' }}>Spouse</option>
                                        </select>
                                        <p id="number_type_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" name="contact_number" id="contact_number" value="{{$employeeData[0]->contact_number}}">
                                        <p id="contact_number_error" class="text-danger"></p>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <p id="familyformbutton" type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4" >Submit</p>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="multi_dlex">
                        <div class="uniq_btn_item "muliple_edit_btn>
                            <input type="submit" onclick="empFunction('familyModal','Family_model')" class="multiple_btn" id="{{ $employeeData[0]->user_id }}" value="Edit">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
            <div class="card flex-fill  ctm-border-radius shadow-sm grow empl_view_dv">
                <div class="card-header">
                    <h4 class="card-title mb-0">Education Details</h4>
                </div>
                <div class="card-body text-center">
                <p class="card-text mb-3"><span class="text-primary">High School : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->highschool!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->highschool) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                     <?php } ?></p>
                    
                    
                    <p class="card-text mb-3"><span class="text-primary">Intermediate : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->intermediate!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->intermediate) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>
                    
                    
                    <p class="card-text mb-3"><span class="text-primary">Graduation : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->graduation!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->graduation) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>
                    
                    
                    <p class="card-text mb-3"><span class="text-primary">Post Graduation : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->post_graduation!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->post_graduation) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>

                    <form id="eduactionform" enctype="multipart/form-data">
                        @csrf

                        <div id="eduactionModal" class="modal">
                            <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ $employeeData[0]->user_id }}">

                            <!-- Modal content -->
                            <div class="modal-content">
                                <span class="close" id="eduaction_model">&times;</span>
                                
                                <div class="row">
                                    <div class="col-3 form-group">
                                        <p>High School Marksheet</p>
                                        <input type="file" class="form-control" name="high_marksheet" id="high_marksheet" value="{{ $employeeData[0]->highschool}}">
                                        <p id="high_marksheet_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Intermediate School Marksheet</p>
                                        <input type="file" class="form-control" name="inter_marksheet" id="inter_marksheet" value="{{ $employeeData[0]->intermediate }}">
                                        <p id="inter_marksheet_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Graduation Marksheet</p>
                                        <input type="file" class="form-control" name="graducation_marksheet" id="graducation_marksheet" value="{{ $employeeData[0]->graduation }}">
                                        <p id="graducation_marksheet_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Post Graduation Marksheet</p>
                                        <input type="file" class="form-control" name="post_graduation_marksheet" id="post_graduation_marksheet" value="{{ $employeeData[0]->post_graduation }}">
                                        <p id="post_graduation_marksheet_error" class="text-danger"></p>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <button id="button" type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="multi_dlex">
                        <div class="uniq_btn_item "muliple_edit_btn>
                            <input type="submit" onclick="empFunction('eduactionModal','eduaction_model')" class="multiple_btn" id="{{ $employeeData[0]->user_id }}" value="Edit">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
            <div class="card flex-fill  ctm-border-radius shadow-sm grow empl_view_dv">
                <div class="card-header">
                    <h4 class="card-title mb-0">Communication Details</h4>
                </div>
                <div class="card-body text-center">
                    <p class="card-text mb-3"><span class="text-primary">Mobile Number : </span>{{$employeeData[0]->mobile_number}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Company Email Id : </span>{{$employeeData[0]->company_email_id}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Internal Email Id : </span>{{$employeeData[0]->internal_email_id}}</p>
                    <p class="card-text mb-3"><span class="text-primary">Email Id : </span>{{$employeeData[0]->email_id}}</p>
                    <form id="communicationform">
                        @csrf

                        <div id="communicationModal" class="modal">
                            <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ $employeeData[0]->user_id }}">

                            <!-- Modal content -->
                            <div class="modal-content">
                                <span class="close" id="communication_model">&times;</span>
                                
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <label>Mobile Number</label>
                                        <input type="text" class="form-control" name="mobile_number" id="mobile_number" value="{{ $employeeData[0]->mobile_number }}">
                                        <p id="mobile_number_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group" style="display:none">
                                        <label>Company Email Id</label>
                                        <input type="text" class="form-control" name="company_email_id" value="{{ $employeeData[0]->company_email_id }}" id="company_email_id">
                                        <p id="company_email_id_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group" style="display:none">
                                        <label>Internal Email Id</label>
                                        <input type="text" class="form-control" name="internal_email_id" value="{{ $employeeData[0]->internal_email_id }}" id="internal_email_id">
                                        <p id="internal_email_id_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>Email Id</label>
                                        <input type="text" class="form-control" name="email_id" value="{{ $employeeData[0]->email_id }}" id="email_id">
                                        <p id="email_id_error" class="text-danger"></p>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <p id="communicationformdata" type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4" >Submit</p>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="multi_dlex">
                        <div class="uniq_btn_item "muliple_edit_btn>
                            <input type="submit" onclick="empFunction('communicationModal','communication_model')" class="multiple_btn" id="{{ $employeeData[0]->user_id }}" value="Edit">
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
            <div class="card flex-fill  ctm-border-radius shadow-sm grow empl_view_dv">
                <div class="card-header">
                    <h4 class="card-title mb-0">Document Section</h4>
                </div>
                <div class="card-body text-center">
                <p class="card-text mb-3"><span class="text-primary">Profile Photo :
                    <?php if(isset($employeeData[0]) && $employeeData[0]->profile_pic!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->profile_pic) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>

                    <p class="card-text mb-3"><span class="text-primary">Addhar Number : </span>{{$employeeData[0]->addhar_number }} </p>

                    <p class="card-text mb-3"><span class="text-primary">Addhar Doc : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->addhar_doc_file!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->addhar_doc_file) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>


                    <p class="card-text mb-3"><span class="text-primary">Pan Number : </span>{{ $employeeData[0]->pan_number}}</p>

                    <p class="card-text mb-3"><span class="text-primary">Pan Doc : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->pan_doc_file!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->pan_doc_file) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>

                    <p class="card-text mb-3"><span class="text-primary">Offer Letter : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->offer_letter!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->offer_letter) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>

                    <p class="card-text mb-3"><span class="text-primary">Relieving Letter : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->relieving_latter!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->relieving_latter) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>

                    <p class="card-text mb-3"><span class="text-primary">Resignation Letter : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->resignation_letter!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->resignation_letter) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>

                    <p class="card-text mb-3"><span class="text-primary">Appointment Letter : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->appointment_latter!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->appointment_latter) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>

                    <p class="card-text mb-3"><span class="text-primary">Bank Statment : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->bank_statment!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->bank_statment) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>

                    <p class="card-text mb-3"><span class="text-primary">Salary Slip1 :
                    <?php if(isset($employeeData[0]) && $employeeData[0]->salary_slip1!=""){?>  
                     </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->salary_slip1) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                     <?php } ?></p>

                    <p class="card-text mb-3"><span class="text-primary">Salary Slip2 : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->salary_slip2!=""){?>  
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->salary_slip2) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                    <?php } ?></p>

                    <p class="card-text mb-3"><span class="text-primary">Salary Slip3 : 
                    <?php if(isset($employeeData[0]) && $employeeData[0]->salary_slip3!=""){?>
                    </span><a href="{{ asset('doc_images'.$employeeData[0]->employee_code.'/'.$employeeData[0]->salary_slip3) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a></p>
                    <?php } ?></p>
                    
                    <form id="accountform" enctype="multipart/form-data">
                        @csrf

                        <div id="accountModal" class="modal">
                            <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ $employeeData[0]->user_id }}">

                            <!-- Modal content -->
                            <div class="modal-content">
                                <span class="close" id="accountsec_model">&times;</span>
                                
                                <div class="row">
                                    <div class="col-3 form-group">
                                        <p>Profile Photo</p>
                                        <input type="file" class="form-control" name="profile_pic" id="profile_pic" value="{{$employeeData[0]->profile_pic }}">
                                        <p id="profile_pic_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Addhar Number</p>
                                        <input type="text" class="form-control" name="addhar_number"  id="addhar_number" value="{{$employeeData[0]->addhar_number }}">
                                        <p id="addhar_number_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Addhar Doc File</p>
                                        <input type="file" class="form-control" name="addhar_doc_file" id="addhar_doc_file" value="{{$employeeData[0]->profile_pic }}">
                                        <p id="addhar_doc_file_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>PAN Number</p>
                                        <input type="text" class="form-control" name="pan_number" id="pan_number" value="{{$employeeData[0]->pan_number }}">
                                        <p id="pan_number_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>PAN Doc File</p>
                                        <input type="file" class="form-control" name="pan_doc_file" id="pan_doc_file" value="{{$employeeData[0]->pan_doc_file }}">
                                        <p id="pan_doc_file_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Offer Letter</p>
                                        <input type="file" class="form-control" name="offer_letter" id="offer_letter" value="{{$employeeData[0]->offer_letter }}">
                                        <p id="offer_letter_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Reliving Letter</p>
                                        <input type="file" class="form-control" name="relieving_latter" id="relieving_latter" value="{{$employeeData[0]->relieving_latter }}">
                                        <p id="relieving_latter_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Regination Letter</p>
                                        <input type="file" class="form-control" name="resignation_letter" id="resignation_letter" value="{{$employeeData[0]->resignation_letter }}">
                                        <p id="resignation_letter_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Appointment Letter</p>
                                        <input type="file" class="form-control" name="appointment_latter" id="appointment_latter" value="{{$employeeData[0]->appointment_latter }}">
                                        <p id="appointment_latter_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Bank Statment</p>
                                        <input type="file" class="form-control" name="bank_statment" id="bank_statment" value="{{$employeeData[0]->bank_statment }}">
                                        <p id="bank_statment_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Salary Slip1</p>
                                        <input type="file" class="form-control" name="salary_slip1" id="salary_slip1" value="{{$employeeData[0]->salary_slip1 }}">
                                        <p id="salary_slip1_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Salary Slip2</p>
                                        <input type="file" class="form-control" name="salary_slip2" id="salary_slip2" value="{{$employeeData[0]->salary_slip2 }}">
                                        <p id="salary_slip2_error" class="text-danger"></p>
                                    </div>
                                    <div class="col-3 form-group">
                                        <p>Salary Slip3</p>
                                        <input type="file" class="form-control" name="salary_slip3" id="salary_slip3" value="{{$employeeData[0]->salary_slip3 }}">
                                        <p id="salary_slip3_error" class="text-danger"></p>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <button id="button" type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4" >Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="multi_dlex">
                        <div class="uniq_btn_item "muliple_edit_btn>
                            <input type="submit" onclick="empFunction('accountModal','accountsec_model')" class="multiple_btn" id="{{ $employeeData[0]->user_id }}" value="Edit">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        

        <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
    <div class="card flex-fill  ctm-border-radius shadow-sm grow empl_view_dv">
        <div class="card-header">
        <h4 class="card-title mb-0">Previous Empoyement Details</h4>
        </div>
        <div class="card-body text-center">
            <?php 
             $i=0;
             $company_name = "";
             $start_date = "";
             $end_date = "";
             $role = "";
             $company_emp_ref_name = "";
             $company_emp_ref_email = "";
             $company_emp_ref_mobile = "";
             $company_emp_ref_role = "";
            foreach($employeeData['previous_employee'] as $preEmployee){
           $i++;
           $company_name = $preEmployee['company_name'];
           $start_date = $preEmployee['start_date'];
           $end_date = $preEmployee['end_date'];
           $role = $preEmployee['role'];
           $company_emp_ref_name = $preEmployee['company_emp_ref_name'];
           $company_emp_ref_email = $preEmployee['company_emp_ref_email'];
           $company_emp_ref_mobile = $preEmployee['company_emp_ref_mobile'];
           $company_emp_ref_role = $preEmployee['company_emp_ref_role'];
            ?>
       
        <p class="card-text mb-3"><span class="text-primary">Comapany Name : </span><?php echo $preEmployee['company_name']; ?></p>
        <p class="card-text mb-3"><span class="text-primary">Start Date : </span><?php echo date("d/m/Y",strtotime($preEmployee['start_date'])); ?></p>
        <p class="card-text mb-3"><span class="text-primary">End Date : </span><?php echo date("d/m/Y",strtotime($preEmployee['end_date'])); ?></p>
        <p class="card-text mb-3"><span class="text-primary">Role : </span><?php echo $preEmployee['role']; ?></p>
        <p class="card-text mb-3"><span class="text-primary">Reference Name : </span><?php echo $preEmployee['company_emp_ref_name']; ?></p>
        <p class="card-text mb-3"><span class="text-primary">Reference Email : </span><?php echo $preEmployee['company_emp_ref_email']; ?></p>
        <p class="card-text mb-3"><span class="text-primary">Reference Mobile : </span><?php echo $preEmployee['company_emp_ref_mobile']; ?></p>
        <p class="card-text mb-3"><span class="text-primary">Reference Role : </span><?php echo $preEmployee['company_emp_ref_role']; ?></p>
        <?php } ?>
            <form id="previousform">
                @csrf

                <div id="previousModal" class="modal">
                    <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ $employeeData[0]->user_id }}">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <span class="close" id="previous_model">&times;</span>
                        
                        <div class="row previous-employment">
                           <div class="col-md-2">
                                <div class="form-group change">
                                    <label for="">&nbsp;</label><br/>
                                    <a class="btn btn-success add-more">+ Add More</a>
                                </div>
                            </div>
                            <div class="col-6 form-group">
                                <p>Company Name</p>
                                <input type="text" class="form-control" name="company_name[]" value="<?php echo $company_name; ?>">
                            </div>
                            <div class="col-6 form-group">
                                <p>Company Role</p>
                                <input type="text" class="form-control" name="role[]" value="<?php echo $role; ?>">
                            </div>
                            <div class="col-6 form-group">
                                <p>Start Date</p>
                                <input type="date" class="form-control" name="start_date[]" value="<?php echo $start_date; ?>">
                            </div>
                            <div class="col-6 form-group">
                                <p>End Date</p>
                                <input type="date" class="form-control" name="end_date[]" value="<?php echo $end_date; ?>">
                            </div>
                            <div class="col-6 form-group">
                                <p>Company Reference Name</p>
                                <input type="text" class="form-control" name="company_emp_ref_name[]" value="<?php echo $company_emp_ref_name; ?>">
                            </div>
                            <div class="col-6 form-group">
                                <p>Company Reference Email</p>
                                <input type="text" class="form-control" name="company_emp_ref_email[]" value="<?php echo $company_emp_ref_email; ?>">
                            </div>
                            <div class="col-6 form-group">
                                <p>Company Reference Mobile</p>
                                <input type="text" class="form-control" name="company_emp_ref_mobile[]" value="<?php echo $company_emp_ref_mobile; ?>">
                            </div>
                            <div class="col-6 form-group">
                                <p>Company Reference Role</p>
                                <input type="text" class="form-control" name="company_emp_ref_role[]" value="<?php echo $company_emp_ref_role; ?>">
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <p id="button" type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4" onclick = "formData('{{ route('employeePreviousUpdate') }}', '#previousform');">Submit</p>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="multi_dlex">
                <div class="uniq_btn_item "muliple_edit_btn>
                    <input type="submit" onclick="empFunction('previousModal','previous_model')" class="multiple_btn" id="{{ $employeeData[0]->user_id }}" value="Edit">
                </div>
            </div>
            
        </div>
    </div>
</div>
    </div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>

<script>
    function empFunction(modalName,closeId){
        // Get the modal
        var modal = document.getElementById(modalName);
        modal.style.display = "block";

        

        // Get the <span> element that closes the modal
        var span = document.getElementById(closeId);

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

    }
</script>
<script>
    
    function formData(routesName,formId) {

        $.ajax({
            url: routesName,
            type: "POST",
            data: $(formId).serialize(),
            dataType: "json",
            beforeSend: function() {
              $("#loader-wrapper").show();
            },
            success: function (data) {
                $("#loader-wrapper").hide();
                $('.msg').html('Data Saved Successfully');
                    window.location = "{{ route('employeeview') }}";
            }
        });
    }



    $("#accountDetail").click(function(){
        var error = false;
        $('#bank_name_error').html('');
        if($('#bank_name').val()==''){
            $('#bank_name_error').html('Bank name is required field');
            var error = true;
        }

        $('#acc_no_error').html('');
        
        if(!$.isNumeric($("#acc_no").val())){
            $('#acc_no_error').html('Account Number is required field');
            var error = true;
        }

        $('#ifsc_error').html('');
        if($('#ifsc').val()==''){
            $('#ifsc_error').html('IFSC Code is required field');
            var error = true;
        }

        if(error){
         return false;
        }

        $.ajax({
            url: "{{ route('employeeAccountDetailsUpdate') }}",
            type: "POST",
            data: $('#accountDetailsform').serialize(),
            dataType: "json",
            beforeSend: function() {
              $("#loader-wrapper").show();
            },
            success: function (data) {
                $("#loader-wrapper").hide();
                $('.msg').html('Data Saved Successfully');
                    window.location = "{{ route('employeeview') }}";             
            }
        });

    });

    $("#empformdata").click(function(){
        var error = false;
        $('#street_error').html('');
        if($('#street').val()==''){
            $('#street_error').html('Street is required field');
            var error = true;
        }

        $('#statelist_error').html('');
        if($('#statelist').val()==''){
            $('#statelist_error').html('State is required field');
            var error = true;
        }

        $('#citylist_error').html('');
        if($('#citylist').val()==''){
            $('#citylist_error').html('City is required field');
            var error = true;
        }

        $('#pincode_error').html('');
        if(!$.isNumeric($("#pincode").val()) || $('#pincode').val().length<6){
            $('#pincode_error').html('Pincode Code is required field');
            var error = true;
        }

        $('#country_error').html('');
        if($('#country').val()==''){
            $('#country_error').html('Country is required field');
            var error = true;
        }

        if(error){
         return false;
        }

        $.ajax({
            url: "{{ route('employeeAddressUpdate') }}",
            type: "POST",
            data: $('#empform').serialize(),
            dataType: "json",
            beforeSend: function() {
              $("#loader-wrapper").show();
            },
            success: function (data) {
                $("#loader-wrapper").hide();
                $('.msg').html('Data Saved Successfully');
                window.location = "{{ route('employeeview') }}";
            }
        });

    });

   


    $("#perAddressform").click(function(){
        var error = false;
        $('#p_street_error').html('');
        if($('#p_street').val()==''){
            $('#p_street_error').html('Street is required field');
            var error = true;
        }

        $('#p_statelist_error').html('');
        if($('#p_statelist').val()==''){
            $('#p_statelist_error').html('State is required field');
            var error = true;
        }

        $('#p_citylist_error').html('');
        if($('#p_citylist').val()==''){
            $('#p_citylist_error').html('City is required field');
            var error = true;
        }

        $('#p_pincode_error').html('');
        if(!$.isNumeric($("#p_pincode").val()) || $('#p_pincode').val().length!=6){
            $('#p_pincode_error').html('Pincode Code is required field');
            var error = true;
        }

        $('#p_country_error').html('');
        if($('#p_country').val()==''){
            $('#p_country_error').html('Country is required field');
            var error = true;
        }

        if(error){
         return false;
        }

        $.ajax({
            url: "{{ route('employeePerAddressUpdate') }}",
            type: "POST",
            data: $('#perAddressforms').serialize(),
            dataType: "json",
            beforeSend: function() {
              $("#loader-wrapper").show();
            },
            success: function (data) {
                $("#loader-wrapper").hide();
                $('.msg').html('Data Saved Successfully');
                    window.location = "{{ route('employeeview') }}";
                
            }
        });

    });


    $("#familyformbutton").click(function(){
        var error = false;
        $('#father_name_error').html('');
        if($('#father_name').val()==''){
            $('#father_name_error').html('Father name is required field');
            var error = true;
        }

        $('#mother_name_error').html('');
        if($('#mother_name').val()==''){
            $('#mother_name_error').html('Mother Name is required field');
            var error = true;
        }

        // $('#spouse_name_error').html('');
        // if($('#spouse_name').val()==''){
        //     $('#spouse_name_error').html('Spouse Name is required field');
        //     var error = true;
        // }

        $('#number_type_error').html('');
        if($('#number_type').val()==''){
            $('#number_type_error').html('Phone Number Type is required field');
            var error = true;
        }

        $('#contact_number_error').html('');
        if(!$.isNumeric($("#contact_number").val()) || $('#contact_number').val().length==12){
            $('#contact_number_error').html('Contact Number is invalid format');
            var error = true;
        }

        if(error){
         return false;
        }

        $.ajax({
            url: "{{ route('employeeFamilyUpdate') }}",
            type: "POST",
            data: $('#familyform').serialize(),
            dataType: "json",
            beforeSend: function() {
              $("#loader-wrapper").show();
            },
            success: function (data) {
                $("#loader-wrapper").hide();
                $('.msg').html('Data Saved Successfully');
                    window.location = "{{ route('employeeview') }}";
                }
        });

    });


    $("#communicationformdata").click(function(){
        var error = false;
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        $('#mobile_number_error').html('');
        if(!$.isNumeric($("#mobile_number").val()) || $('#mobile_number').val().length==12){
            $('#mobile_number_error').html('Mobile number is invalid');
            var error = true;
        }

        $('#company_email_id_error').html('');
        if(reg.test($('#company_email_id').val()) == false){
            $('#company_email_id_error').html('Invalid Company Email Id');
            var error = true;
        }

        $('#internal_email_id_error').html('');
        if(reg.test($('#internal_email_id').val()) == false){
            $('#internal_email_id_error').html('Invalid Internal Email Id');
            var error = true;
        }
        
        $('#email_id_error').html('');
        if(reg.test($('#email_id').val()) == false){
            $('#email_id_error').html('Invalid Email Id');
            var error = true;
        }


        if(error){
         return false;
        }

        $.ajax({
            url: "{{ route('employeeCommunicationUpdate') }}",
            type: "POST",
            data: $('#communicationform').serialize(),
            dataType: "json",
            beforeSend: function() {
              $("#loader-wrapper").show();
            },
            success: function (data) {
                $("#loader-wrapper").hide();
                $('.msg').html('Data Saved Successfully');
                    window.location = "{{ route('employeeview') }}";
                
            }
        });

    });


    $(document).ready(function (e) {
     $("#eduactionform").on('submit',(function(e) {
        console.log('aaaaa');
        e.preventDefault();
        var error = false;
        var file_ext= ['png','pdf','jpeg','jpg'];
        var image_size = 2;
        var high_marksheet = $('#high_marksheet').val().split('.').pop().toLowerCase();
        var high_marksheet_size = 0;
        if(typeof $("#high_marksheet")[0].files[0]!== 'undefined'){
            var high_marksheet_size = $("#high_marksheet")[0].files[0].size/(1024*1024);
        }
        $('#high_marksheet_error').html('');
        if(($.inArray(high_marksheet, file_ext) == -1 || high_marksheet_size>image_size) && high_marksheet_size!=0) {
            $('#high_marksheet_error').html('Invalid Image Format or file is greater than 2MB');
            error = true;
        }
        

        var inter_marksheet = $('#inter_marksheet').val().split('.').pop().toLowerCase();
        var inter_marksheet_size = 0;
        if(typeof $("#inter_marksheet")[0].files[0]!== 'undefined'){
            var inter_marksheet_size = $("#inter_marksheet")[0].files[0].size/(1024*1024);
        }
        $('#inter_marksheet_error').html('');
        if(($.inArray(inter_marksheet, file_ext) == -1 || inter_marksheet_size>image_size) && inter_marksheet_size!=0) {
            $('#inter_marksheet_error').html('Invalid Image Format or file is greater than 2MB');
            error = true;
        }

        var graducation_marksheet = $('#graducation_marksheet').val().split('.').pop().toLowerCase();
        var graducation_marksheet_size = 0;
        if(typeof $("#graducation_marksheet")[0].files[0]!== 'undefined'){
            var graducation_marksheet_size = $("#graducation_marksheet")[0].files[0].size/(1024*1024);
        }
        $('#graducation_marksheet_error').html('');
        if(($.inArray(graducation_marksheet, file_ext) == -1 || graducation_marksheet_size>image_size) && graducation_marksheet_size!=0) {
            $('#graducation_marksheet_error').html('Invalid Image Format or file is greater than 2MB');
            error = true;
        }

        var post_graduation_marksheet = $('#post_graduation_marksheet').val().split('.').pop().toLowerCase();
        var post_graduation_size = 0;
        if(typeof $("#post_graduation_marksheet")[0].files[0]!== 'undefined'){
            var post_graduation_size = $("#post_graduation_marksheet")[0].files[0].size/(1024*1024);
        }
        $('#post_graduation_marksheet_error').html('');
        if(($.inArray(post_graduation_marksheet, file_ext) == -1 || post_graduation_size>image_size) && post_graduation_size!=0) {
            $('#post_graduation_marksheet_error').html('Invalid Image Format or file is greater than 2MB');
            error = true;
        }

        if(error){
         return false;
        }
        //return false;
        $.ajax({
            url: "{{ route('employeeEduactionUpdate') }}",
            type: "POST",
            data:  new FormData(this),
            contentType: false,
                    cache: false,
            processData:false,
            beforeSend: function() {
              $("#loader-wrapper").show();
            },
            success: function(data)
            {
                $("#loader-wrapper").hide();
                    window.location = "{{ route('employeeview') }}";
            }
            
        });
     }));
   });

   $(document).ready(function (e) {
     $("#accountform").on('submit',(function(e) {
        console.log('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
        e.preventDefault();

        var error = false;
        var file_ext= ['png','pdf','jpeg','jpg'];
        var image_size = 2;
        var profile_pic = $('#profile_pic').val().split('.').pop().toLowerCase();
        var profile_pic_size = 0;
        $('#profile_pic_error').html('');
        if(typeof $("#profile_pic")[0].files[0]!== 'undefined'){
            var profile_pic_size = $("#profile_pic")[0].files[0].size/(1024*1024);
        }
        if(($.inArray(profile_pic, file_ext) == -1 || profile_pic_size>image_size) && profile_pic_size!=0) {
            $('#profile_pic_error').html('Invalid Image Format or file is greater than 2MB');
            error = true;
        }
        
        $('#addhar_number_error').html('');
        if(!$.isNumeric($("#addhar_number").val()) || $('#addhar_number').val().length < 12){
            $('#addhar_number_error').html('Addhar Number is required field and It should be 12 digit number');
            var error = true;
        }

        $('#pan_number_error').html('');
        if($('#pan_number').val().length < 10){
            $('#pan_number_error').html('Pan Number is required field or length should be 10');
            var error = true;
        }

        var addhar_doc_file = $('#addhar_doc_file').val().split('.').pop().toLowerCase();
        var addhar_doc_file_size = 0;
        if(typeof $("#addhar_doc_file")[0].files[0]!== 'undefined'){
            var addhar_doc_file_size = $("#addhar_doc_file")[0].files[0].size/(1024*1024);
        }
        
        $('#addhar_doc_file_error').html('');
        if(($.inArray(addhar_doc_file, file_ext) == -1 || addhar_doc_file_size>image_size) && addhar_doc_file_size!=0) {
            $('#addhar_doc_file_error').html('Invalid Image Format or file is greater than 2MB');
            error = true;
        }

        var pan_doc_file = $('#pan_doc_file').val().split('.').pop().toLowerCase();
        var pan_doc_file_size = 0;
        if(typeof $("#pan_doc_file")[0].files[0]!== 'undefined'){
        var pan_doc_file_size = $("#pan_doc_file")[0].files[0].size/(1024*1024);
        }
        $('#pan_doc_file_error').html('');
        if(($.inArray(pan_doc_file, file_ext) == -1 || pan_doc_file_size>image_size) && pan_doc_file_size!=0) {
            $('#pan_doc_file_error').html('Invalid Image Format or file is greater than 2MB');
            error = true;
        }
    

        // var offer_letter = $('#offer_letter').val().split('.').pop().toLowerCase();
        // var offer_letter_size = 0;
        // if(typeof $("#offer_letter")[0].files[0]!== 'undefined'){
        // var offer_letter_size = $("#offer_letter")[0].files[0].size/(1024*1024);
        // }
        // $('#offer_letter_error').html('');
        // if(($.inArray(offer_letter, file_ext) == -1 || offer_letter_size>image_size) && offer_letter_size!=0) {
        //     $('#offer_letter_error').html('Invalid Image Format or file is greater than 2MB');
        //     error = true;
        // }

        // var relieving_latter = $('#relieving_latter').val().split('.').pop().toLowerCase();
        // var relieving_latter_size = 0;
        // if(typeof $("#relieving_latter")[0].files[0]!== 'undefined'){
        // var relieving_latter_size = $("#relieving_latter")[0].files[0].size/(1024*1024);
        // }
        // $('#relieving_latter_error').html('');
        // if(($.inArray(relieving_latter, file_ext) == -1 || relieving_latter_size>image_size) && relieving_latter_size!=0) {
        //     $('#relieving_latter_error').html('Invalid Image Format or file is greater than 2MB');
        //     error = true;
        // }


        // var resignation_letter = $('#resignation_letter').val().split('.').pop().toLowerCase();
        // var resignation_letter_size = 0;
        // if(typeof $("#resignation_letter")[0].files[0]!== 'undefined'){
        // var resignation_letter_size = $("#resignation_letter")[0].files[0].size/(1024*1024);
        // }
        // $('#resignation_letter_error').html('');
        // if(($.inArray(resignation_letter, file_ext) == -1 || resignation_letter_size>image_size) && resignation_letter_size!=0) {
        //     $('#resignation_letter_error').html('Invalid Image Format or file is greater than 2MB');
        //     error = true;
        // }


        // var appointment_latter = $('#appointment_latter').val().split('.').pop().toLowerCase();
        // var appointment_latter_size =0;
        // if(typeof $("#relieving_latter")[0].files[0]!== 'undefined'){
        // var appointment_latter_size = $("#appointment_latter")[0].files[0].size/(1024*1024);
        // }
        // $('#appointment_latter_error').html('');
        // if(($.inArray(appointment_latter, file_ext) == -1 || appointment_latter_size>image_size) && appointment_latter_size!=0) {
        //     $('#appointment_latter_error').html('Invalid Image Format or file is greater than 2MB');
        //     error = true;
        // }


        // var bank_statment = $('#bank_statment').val().split('.').pop().toLowerCase();
        // var bank_statment_size = 0;
        // if(typeof $("#relieving_latter")[0].files[0]!== 'undefined'){
        // var bank_statment_size = $("#bank_statment")[0].files[0].size/(1024*1024);
        // }
        // $('#bank_statment_error').html('');
        // if(($.inArray(bank_statment, file_ext) == -1 || bank_statment_size>image_size) && bank_statment_size!=0) {
        //     $('#bank_statment_error').html('Invalid Image Format or file is greater than 2MB');
        //     error = true;
        // }


        // var salary_slip1 = $('#salary_slip1').val().split('.').pop().toLowerCase();
        // var salary_slip1_size = 0;
        // if(typeof $("#salary_slip1")[0].files[0]!== 'undefined'){
        //    var salary_slip1_size = $("#salary_slip1")[0].files[0].size/(1024*1024);
        // }
        // $('#salary_slip1_error').html('');
        // if(($.inArray(salary_slip1, file_ext) == -1 || salary_slip1_size>image_size) && salary_slip1_size!=0) {
        //     $('#salary_slip1_error').html('Invalid Image Format or file is greater than 2MB');
        //     error = true;
        // }

        // var salary_slip2 = $('#salary_slip2').val().split('.').pop().toLowerCase();
        // var salary_slip2_size = 0;
        // if(typeof $("#salary_slip2")[0].files[0]!== 'undefined'){
        // var salary_slip2_size = $("#salary_slip2")[0].files[0].size/(1024*1024);
        // }
        // $('#salary_slip2_error').html('');
        // if(($.inArray(salary_slip2, file_ext) == -1 || salary_slip2_size>image_size) && salary_slip2_size!=0) {
        //     $('#salary_slip2_error').html('Invalid Image Format or file is greater than 2MB');
        //     error = true;
        // }


        // var salary_slip3 = $('#salary_slip3').val().split('.').pop().toLowerCase();
        // var salary_slip3_size =0;
        // if(typeof $("#salary_slip3")[0].files[0]!== 'undefined'){
        // var salary_slip3_size = $("#salary_slip3")[0].files[0].size/(1024*1024);
        // }
        // $('#salary_slip3_error').html('');
        // if(($.inArray(salary_slip3, file_ext) == -1 || salary_slip3_size>image_size) && salary_slip3_size!=0) {
        //     $('#salary_slip3_error').html('Invalid Image Format or file is greater than 2MB');
        //     error = true;
        // }

       

        if(error){
         return false;
        }


        $.ajax({
            url: "{{ route('employeeAccountUpdate') }}",
            type: "POST",
            data:  new FormData(this),
            contentType: false,
                    cache: false,
            processData:false,
            beforeSend: function() {
              $("#loader-wrapper").show();
            },
            success: function(data)
            {
                $("#loader-wrapper").hide();
                  var data = JSON.parse(data)
                    window.location = "{{ route('employeeview') }}";
            }
            
        });
     }));
   });
</script>


<script>
	$(document).ready(function() {
		$("body").on("click",".add-more",function(e){ 
			e.preventDefault();
			var html = $(".previous-employment").first().clone();
			$(html).find(".change").html("<label for=''>&nbsp;</label><br/><a class='btn btn-danger remove'>- Remove</a>");
			$(".previous-employment").last().after(html);
		});

		$("body").on("click",".remove",function(e){ 
			e.preventDefault();
			$(this).parents(".previous-employment").remove();
		});
	});
</script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#statelist').on('change', function() {		
			var userURL = "{{  url('city_ajax') }}";

			$.ajax({
				url: userURL,
				type: 'POST',
				data: {
					"_token": "{{ csrf_token() }}",
					'state':this.value
                },
                dataType: 'json',
                beforeSend: function() {
                $("#loader-wrapper").show();
                },
                success: function(data) {
                    $("#loader-wrapper").hide();
                    let htmldata="";
                    data.map(datas=>{
                        htmldata+= "<option value="+datas.city+">"+datas.city+"</option>";
                    })
                    $('#citylist').html(htmldata);
                }
            });
		});	
	});	
</script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#p_statelist').on('change', function() {		
			var userURL = "{{  url('city_ajax') }}";

			$.ajax({
				url: userURL,
				type: 'POST',
				data: {
					"_token": "{{ csrf_token() }}",
					'state':this.value
                },
                dataType: 'json',
                beforeSend: function() {
                $("#loader-wrapper").show();
                },
                success: function(data) {
                    $("#loader-wrapper").hide();
                    let htmldata="";
                    data.map(datas=>{
                        htmldata+= "<option value="+datas.city+">"+datas.city+"</option>";
                    })
                    $('#p_citylist').html(htmldata);
                }
            });
		});	
	});	
</script>
<script>
$(document).ready(function(){
 
 // Initialize select2
 $("#statelist").select2();
 $("#citylist").select2();
 $("#p_statelist").select2();
 $("#p_citylist").select2();

});
</script>
<style>
    .select2-container {
    box-sizing: border-box;
    display: inline-block;
    margin: 0;
    position: relative;
    vertical-align: middle;
    width: 100% !important;
}
</style>
@endsection
