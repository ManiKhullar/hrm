@extends('layouts.layout')

@section('content')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<style>
    .salary_list {
        text-align: center;
        padding: 10px;
        border-radius: 4px;
        color: #000;
        margin-top: 15px;
        text-transform: capitalize;
    }
</style>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-body">
                    @if (Session::has('error'))
                    <p class="text-danger">{{ Session::get('error') }}</p>
                    @endif
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <form action="{{ route('salarylistfilter') }}" method="GET">
                        <div class="row">
                            <div class="col-sm-4 leave-col">
                                <div class="form-group">
                                    <label>Employee Name</label>
                                    <select class="form-control select emp_id" id="emp_id" name="emp_id">
                                        <option value="">Select Employee</option>
                                        @foreach ($users as $user)
                                        <?php if(isset($_GET['emp_id'])){?>
                                        <option value="{{ $user->id }}" <?php if($_GET['emp_id']==$user->id){echo "selected";}?>>{{ $user->name }}</option>
                                        <?php }else{ ?>
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        <?php } ?>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 leave-col">
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
                                    @if ($errors->has('year'))
                                        <p class="text-danger">{{ $errors->first('year') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3 leave-col">
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
                                    @if ($errors->has('month'))
                                        <p class="text-danger">{{ $errors->first('month') }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-sm-2 leave-col">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Salary List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <form>
                                        <div class="col-sm-2 leave-col">
                                            <div class="form-group">
                                                <select id="massExport" class="form-control select" name="massExport">
                                                    <option value="">Select</option>
                                                    <option value="Export">Export</option>
                                                </select>
                                            </div>
                                        </div>
                                        <table class="table custom-table mb-0">
                                            <tr>
                                                <th><input id="allchecked" type="checkbox" onchange="checkAll(this)" name="chk[]" /></th>
                                                <th>No</th>
                                                <th>Employee Name</th>
                                                <th>Employee Id</th>
                                                <th>Employee Email</th>
                                                <th>Salary</th>
                                                <th>Month</th>
                                                <th>Year</th>
                                                <th>Action</th>
                                            </tr>
                                            <?php if(count($empData)) {?>
                                                @foreach ($empData as $value)
                                                <tr>
                                                    <td><input type="checkbox" name="checkbox" value="{{ $value->id }}"></td>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->employee_code }}</td>
                                                    <td>{{ $value->email }}</td>
                                                    <td>{{ $value->credit_salary }}</td>
                                                    <td> <?php echo  date('F', mktime(0,0,0,($value->month))) ?></td>
                                                    <td><?php echo  $value->year; ?></td>
                                                    <td>
                                                        <a href="{{ url('usercalandarview/'.$value->id) }}">
                                                            <i style='font-size:24px' class="fa fa-eye" aria-hidden="true"></i>
                                                            |
                                                        <a href="{{ url('pdfview/'.$value->id) }}">
                                                            <i style='font-size:24px' class="fa fa-download" aria-hidden="true"></i>
                                                        </a>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            {!! $empData->links() !!}
                                            <?php }else { ?>
                                        </table>
                                            <p class="salary_list" >No record found</p>
                                        <?php } ?>
                                    </form>
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
    $(document).ready(function() {
        $("#emp_id").select2();
        $('#allchecked').click(function() {
            $('input[type="checkbox"]').prop('checked', this.checked);
        })
    });
</script>
<script>
    $(document).ready(function(){
        $('#massExport').on('change', function() {
            if(this.value!=''){
            if (confirm("Are you want to "+this.value) == true ) {
                let selectedIds =[];
                $("input:checkbox[name=checkbox]:checked").each(function(){
                    selectedIds.push($(this).val());
                });
                if(selectedIds.length === 0){
                    confirm("The checked value can not be blankkkkkkkkkkkkkkkkk.");
                    return;
                }
                var month = document.getElementById("month").value;
                var year = document.getElementById("year").value;
                
                $.ajax({
                    url: "{{ route('exportsalaryCSV') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "selectedIds":selectedIds,
                        "month":month,
                        "year":year,
                        "status" : this.value
                    },
                    dataType: "html",
                    success: function (data) {
                        /*
                        * Make CSV downloadable
                        */
                        var downloadLink = document.createElement("a");
                        var fileData = ['\ufeff'+data];

                        var blobObject = new Blob(fileData,{
                            type: "text/csv;charset=utf-8;"
                        });

                        var url = URL.createObjectURL(blobObject);
                        downloadLink.href = url;
                        downloadLink.download = "salarylist.csv";

                        /*
                        * Actually download CSV
                        */
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(downloadLink);
                                }
                });
            }
            }
        });

    });
</script>

@endsection