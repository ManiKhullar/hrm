@extends('layouts.layout')

@section('content')


<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Employee Salary Generate</h4>
                            <form action="{{ route('salarycron') }}" method="POST">
                            @csrf
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-sm-3 col-12">
                                            <div class="form-group">
                                                <label>Month</label>
                                                <select id="month" name="month">
                                                    <option value="">Select Month</option>
                                                    <?php
                                                    for ($i_month = 1; $i_month <= 12; $i_month++) { 
                                                        if(isset($_GET['month'])){
                                                            $selected = $_GET['month'] == $i_month ? ' selected' : '';
                                                            echo '<option value="'.str_pad($i_month,2,'0', STR_PAD_LEFT).'"'.$selected.'>'. date('F', mktime(0,0,0,$i_month)).'</option>'."\n";
                                                        }else{
                                                            $selected_month = date('m'); //current month
                                                            $selected = $selected_month == $i_month ? ' selected' : '';
                                                            echo '<option value="'.str_pad($i_month,2,'0', STR_PAD_LEFT).'"'.$selected.'>'. date('F', mktime(0,0,0,$i_month)).'</option>'."\n";
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-12">
                                            <div class="form-group">
                                                <label>Year</label>
                                                <select id="year" name="year">
                                                    <option value="">Select Year</option>
                                                    <?php $year_end = date('Y'); // current Year
                                                    for ($i_year = date("Y"); $i_year >= date("Y")-2; $i_year--) {
                                                        if(isset($_GET['year'])){
                                                            $selected = $_GET['year'] == $i_year ? ' selected' : '';
                                                            echo '<option value="'.$i_year.'"'.$selected.'>'.$i_year.'</option>'."\n";
                                                        }else{
                                                            $selected = $year_end == $i_year ? ' selected' : '';
                                                            echo '<option value="'.$i_year.'"'.$selected.'>'.$i_year.'</option>'."\n";
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Generate</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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