@extends('layouts.layout')

@section('content')

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Employee Notice</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive" id="project_list_data">
                                    <table class="table custom-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Color</th>
                                                <th>Content</th>
                                                <th>Status</th>
                                                <th class='text-center'>Action</th>
                                            </tr>
                                        </thead>
                                            @foreach ($emp_notice as $notice)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>
                                                    @if($notice->color == 'MediumSeaGreen')
                                                        Green
                                                    @elseif($notice->color == 'blue')
                                                        Blue
                                                    @else
                                                        Red
                                                    @endif
                                                </td>
                                                <td>{{ $notice->content }}</td>
                                                <td>
                                                    @if($notice->status == 1)
                                                        Active
                                                    @else
                                                        Deactivate
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('noticeedit/'.$notice->id.'/edit') }}"><i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
		$("#search_data").keyup(function(e) {
			var currentInput = $(this).val();
			$.ajax({
				url: "{{ route('projectfilter') }}",
				type: "POST",
				data: {"_token": "{{ csrf_token() }}",
							'search':currentInput},
				dataType: "json",
				success: function (data) {
					
					var html = "<table class='table custom-table mb-0 table-hover'><thead><tr><th>No</th><th>Project Name</th><th>Vendor Name</th><th>Start Date</th><th>End Date</th><th>Status</th><th class='text-center'>Action</th></tr></thead><tbody id='ajaxProjectData'>";

                    var i = 0;
					$.each(data, function(index, value) {
                        i = ++i;
                        if(value.project_enddate === null){
                            value.project_enddate = '';
                        }
						if(value.status == 1){
							var dataStatus = 'Deactivate';
							var Status = 'Active';
							var prostatuschange = $("#url").val()+'/projectupdate/'+value.id+'/0';
						} else{
							var dataStatus = 'Active';
							var Status = 'Deactivate';
							var prostatuschange = $("#url").val()+'/projectupdate/'+value.id+'/1';
						}
						html+="<tr><td>"+i+"</td><td>"+value.project_name+"</td><td>"+value.vendor_name+"</td><td>"+value.project_startdate+"</td><td>"+value.project_enddate+"</td><td>"+Status+"</td><td><a href="+prostatuschange+">"+dataStatus+"</a></td></tr>";
					});
					html+="</tbody></table>";
					$('#project_list_data').html(html);
				}
			});
		});
	})
</script>

@endsection