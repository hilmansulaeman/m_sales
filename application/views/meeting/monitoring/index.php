<!-- <script>var id_schedule = data.Schedule_ID;</script> -->
<?php
// echo "<script>alert(id_schedule)</script>";
$date_from = $this->input->post('date_from');
$date_to = $this->input->post('date_to');

if($date_from !='' && $date_to != ''){
	$date_from = $this->input->post('date_from');
	$date_to = $this->input->post('date_to');
}else{
	$date_from = date('Y-m-01');
	$date_to = date('Y-m-d');
}

?>

<!-- MAIN CONTENT -->
<div id="ribbon">
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-link "></i> &nbsp; Meeting</li>
		<li>Monitoring</li>
	</ol>	 
</div>

<div class="box box-primary">
	<?php if ($this->session->flashdata('message')) { ?>
		<div class="alert alert-warning fade in">
		<button class="close" data-dismiss="alert" id="notif">
			×
		</button>
		<i class="fa-fw fa fa-check"></i>
		<?php echo $this->session->flashdata('message'); ?>
		</div>
	<?php }?>
	<div class="box-header with-border">
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">&nbsp;</i>
			</button>
		</div>		
		<!-- <h3 class="box-title">Meeting Monitoring <?php //echo $position; ?></h3>-->
		<h3 class="box-title">Meeting Monitoring</h3>			  
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-12">
				<span class="pull-right">
					<form id="form_filter" method="post" class="smart-form" novalidate="novalidate"> 		
						<table>
							<tr>											 																				
								<td><h5 class="txt-color-blueDark">Periode &nbsp; </h5></td>	
								<td>
									<label class="input"> 	
										<input type="date" id="date_from" name="date_from" value="<?php echo $date_from; ?>" data-dateformat='yy-mm-dd'  class="form-control datepicker" required/>
									<?php echo form_error('date_from'); ?>
									</label>	
								</td>											
								<td><h5 class="txt-color-blueDark">&nbsp; S/D &nbsp; </h5></td>											
								<td>
									<label class="input"> 							
										<input type="date" id="date_to" name="date_to" value="<?php echo $date_to; ?>" data-dateformat='yy-mm-dd'  class="form-control datepicker" required/>
									<?php echo form_error('date_to'); ?>
									</label>
								</td>
								<td>&nbsp;&nbsp;&nbsp;</td>
								<td>
									<button type="button" id="btn-filter" class="btn btn-success" onclick="filter_data()" style="padding:5px;"><span class="fa fa-filter"></span> Go</button>
								</td>																					
							</tr>						 
						</table>
					</form>
				</span>
			</div>
		</div>
		<br>

		<?php
			$position_ = $this->session->userdata('position');
			$disallow_position = array('DSR','SPV');
			if(in_array($position_,$disallow_position)){
				$control   = "";
				$column    = "5";
				// $column    = "6";
			}
			else{
				$control   = "<th>Action</th>";
				$column    = "6";
				// $column    = "7";
			}
		?>
		<div class="table-responsive">
			<table id="data-table-customer" class="table table-hover" width="100%">
				<thead>											
					<tr>				 											 				
						<th>No</th>
						<th>Nama Sales</th>
						<th>Branch</th>
						<!-- <th>Total DSR</th> -->
						<th>Total Meeting</th>
						<th>Total Hadir</th>
						<!--
						<th>Meeting Bottom</th>
						<th>Hadir Bottom</th>-->
						<?php echo $control; ?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="<?php echo $column; ?>">Loading data from server</td>
					</tr>
				</tbody>
			</table>					
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modalSPV">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<input type="hidden" name="names1" id="names1" value="">
				<input type="hidden" name="pos1" id="pos1" value="">
				<input type="hidden" name="sales1" id="sales1" value="">
				<input type="hidden" name="names2" id="names2" value="">
				<input type="hidden" name="names3" id="names3" value="">
				<p id="header-all"></p>
				<!-- <h4 class="modal-title">Meeting Monitoring <?php //echo $detail; ?></h4> -->
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmspv" class="form-horizontal form-bordered">					 				 								
					<div id="pop"></div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modalSchedule">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<input type="hidden" name="salesNIK" id="salesNIK" value="">				
				<p id="header-all-schedule"></p>
				<!-- <h4 class="modal-title">Meeting Monitoring <?php //echo $detail; ?></h4> -->
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmschedule" class="form-horizontal form-bordered">					 				 								
					<div id="pop_schedule"></div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modalParticipant">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<p id="header-all-participant"></p>
				<!-- <h4 class="modal-title">Meeting Monitoring <?php //echo $detail; ?></h4> -->
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmparticipant" class="form-horizontal form-bordered">					 				 								
					<div id="pop_participant"></div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modalMom">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<p id="header-all-mom"></p>
				<!-- <h4 class="modal-title">Meeting Monitoring <?php //echo $detail; ?></h4> -->
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmmom" class="form-horizontal form-bordered">					 				 								
					<div id="pop_mom"></div>
					<div class="col-lg-12">
						<div class="box box-primary">			
							<div class="box-header with-border">
								<h3 class="box-title">Absensi Meeting</h3>			  
							</div>
							
						
							<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>No.</th>
									<th>NIK</th>
									<th>Posisi</th>
									<th>Nama</th>
								
								</tr>
							</thead>
							<tbody id="body-absensi">
							</tbody>
							</table>
							<div class="panel-body" style="font-family:sans-serif;">
								<!-- begin row -->
								<div class="row">
								
									<div class="col-lg-12">
										<div class="form-group m-b-10">
											<label>Hasil MOM <span style="color:red">*</span>:</label>
											<?php echo form_error('hasil_mom'); ?>
											<textarea type="text" class="form-control" rows="8" required id="hasil_mom" name="hasil_mom" oninput="this.value=this.value.replace(/[<>]/g,'')" onpaste="this.value=this.value.replace(/[<>]/g,'')" value="<?php echo set_value('hasil_mom'); ?>" disabled ></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modalhadir">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<p id="header-all-mom"></p>
				<!-- <h4 class="modal-title">Meeting Monitoring <?php //echo $detail; ?></h4> -->
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmhadir" class="form-horizontal form-bordered">					 				 								
					<div id="pop_mom"></div>
					<div class="col-lg-12">
						<div class="box box-primary">			
							<div class="box-header with-border">
								<h3 class="box-title">Absensi Hadir Meeting</h3>			  
							</div>
							
						
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th>No.</th>
										<th>NIK</th>
										<th>Posisi</th>
										<th>Nama</th>
									
									</tr>
								</thead>
								<tbody id="body-hadir">
								</tbody>
							</table>
						</div>
					</div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modaltidakhadir">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<p id="header-all-mom"></p>
				<!-- <h4 class="modal-title">Meeting Monitoring <?php //echo $detail; ?></h4> -->
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmtidakhadir" class="form-horizontal form-bordered">					 				 								
					<div id="pop_mom"></div>
					<div class="col-lg-12">
						<div class="box box-primary">			
							<div class="box-header with-border">
								<h3 class="box-title">Absensi Tidak Hadir Meeting</h3>			  
							</div>
							
						
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th>No.</th>
										<th>NIK</th>
										<th>Posisi</th>
										<th>Nama</th>
									
									</tr>
								</thead>
								<tbody id="body-tidakhadir">
								</tbody>
							</table>
						</div>
					</div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modalmeeting">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<p id="header-all-meeting"></p>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmmeeting" class="form-horizontal form-bordered">					 				 								
					<div id="pop_meeting"></div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modalmeetinghadir">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<p id="header-all-mom"></p>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmmeetinghadir" class="form-horizontal form-bordered">					 				 								
					<div id="pop_mom"></div>
					<div class="col-lg-12">
						<div class="box box-primary">			
							<div class="box-header with-border">
								<h3 class="box-title">Absensi Hadir Meeting</h3>			  
							</div>
						
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th>No.</th>
										<th>NIK</th>
										<th>Posisi</th>
										<th>Nama</th>
									</tr>
								</thead>
								<tbody id="body-meetinghadir">
								</tbody>
							</table>
						</div>
					</div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
		</div>
	</div>
</div>

<div class="modal modal-message fade" id="modalmeetingtidakhadir">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<p id="header-all-mom"></p>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmmeetingtidakhadir" class="form-horizontal form-bordered">					 				 								
					<div id="pop_mom"></div>
					<div class="col-lg-12">
						<div class="box box-primary">			
							<div class="box-header with-border">
								<h3 class="box-title">Absensi Tidak Hadir Meeting</h3>			  
							</div>
						
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th>No.</th>
										<th>NIK</th>
										<th>Posisi</th>
										<th>Nama</th>
									</tr>
								</thead>
								<tbody id="body-meetingtidakhadir">
								</tbody>
							</table>
						</div>
					</div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
		</div>
	</div>
</div>


<div class="modal modal-message fade" id="modalviewmeeting">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<p id="header-all-viewmeeting"></p>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmviewmeeting" class="form-horizontal form-bordered">					 				 								
					<div id="pop_viewmeeting"></div>
					<div class="col-lg-12">
						<div class="box box-primary">			
							<div class="box-header with-border">
								<h3 class="box-title">Absensi & Hasil Meeting</h3>			  
							</div>
							
						
							<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>No.</th>
									<th>NIK</th>
									<th>Posisi</th>
									<th>Nama</th>
								
								</tr>
							</thead>
							<tbody id="body-viewmeeting">
							</tbody>
							</table>
							<div class="panel-body" style="font-family:sans-serif;">
								<div class="row">
								
									<div class="col-lg-12">
										<div class="form-group m-b-10">
											<label>Hasil MOM <span style="color:red">*</span>:</label>
											<?php echo form_error('hasil_viewmeeting'); ?>
											<textarea type="text" class="form-control" rows="8" required id="hasil_viewmeeting" name="hasil_viewmeeting" oninput="this.value=this.value.replace(/[<>]/g,'')" onpaste="this.value=this.value.replace(/[<>]/g,'')" value="<?php echo set_value('hasil_viewmeeting'); ?>" disabled ></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var table_monitor;
$(document).ready(function() {
    table_monitor = $("#data-table-customer").DataTable({
		ordering: false,
		processing: true,
		serverSide: true,
		responsive:true,
		ajax: {
			url: "<?php echo site_url('meeting/monitoring/get_data') ?>",
			type:'POST',
			data: function ( data ) {
                data.date_from = $('#date_from').val();
                data.date_to = $('#date_to').val();
            }
		},
		initComplete : function() {
			var input = $('#data-table-customer_filter input').unbind(),
				self = this.api(),
				searchButton = $('<span id="btnSearch" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></span>')
						.click(function() {
							self.search(input.val()).draw();
						});
				$(document).keypress(function (event) {
					if (event.which == 13) {
						searchButton.click();
					}
				});
			$('#data-table-customer_filter').append(searchButton);
		}
	});
});

function datediff(first, second) {        
    return Math.round((second - first) / (1000 * 60 * 60 * 24));
}

function parseDate(str) {
    var mdy = str.split('-');
    return new Date(mdy[0], mdy[1] - 1, mdy[2]);
}

function filter_data()
{
	var dd = datediff(parseDate($('#date_from').val()), parseDate($('#date_to').val()));
	if(dd <= 30){
    	table_monitor.draw();
	}else{
		alert('Maaf, range tanggal maksimal 31 hari');
	}
}

function view_spv(sales, pos, name)
{
	$('#modalSPV').modal('show');
	var names1 = $('#names1').val();
	var names2 = $('#names2').val();
	var names3 = $('#names3').val();

	if (names1 == "") {
		$('#names1').val(name);
		$('#pos1').val(pos);
		$('#sales1').val(sales);
		names1 = $('#names1').val();
		$('#header-all').html("<b>"+names1+"</b>");
	}else if(names2 == "" ) {
		$('#names2').val(name);
		names2 = $('#names2').val();
		names1 = $('#names1').val();
		pos1 = $('#pos1').val();
		sales1 = $('#sales1').val();

		$('#header-all').html("<b><a href='javascript:void(0);' onclick='view_spv_click(\""+sales1+"\",\""+pos1+"\",\""+names1+"\")'>"+names1+"</a></b> <i class='fa fa-arrow-right'></i> " + names2);
	}else{
		$('#names3').val(name);
		names3 = $('#names3').val();
		names1 = $('#names1').val();
		names2 = $('#names2').val();

		pos1 = $('#pos1').val();
		sales1 = $('#sales1').val();

		$('#header-all').html("<b><a href='javascript:void(0);' onclick='view_spv_click(\""+sales1+"\",\""+pos1+"\",\""+names1+"\")'>"+names1+"</a></b>  <i class='fa fa-arrow-right'></i>  " + names2 + "  <i class='fa fa-arrow-right'></i>  " + names3);
	}

	$.ajax({
		url:"<?php echo site_url('meeting/monitoring/detail'); ?>/" + sales +"/"+ pos,
		type:"POST",
		// data:$("#frmspv").serialize(),
		data: function ( data ) {
			data.data = $("#frmspv").serialize();
            data.date_from = $('#date_from').val();
            data.date_to = $('#date_to').val();
        },
		success:function(data){ 
			$("#pop").html('');  
			$("#pop").append(data);  
		}  
	});
}

$('#modalSPV').on('hidden.bs.modal', function () {
	$('#names1').val(name);
	$('#names2').val(name);
	$('#names3').val(name);

	$('#pos1').val(name);
	$('#sales1').val(name);
});

function view_spv_click(sales, pos, names)
{
	$('#modalSPV').modal('show');
	$('#header-all').html("<b>"+names+"</b>");
	$('#names2').val("");
	$('#names3').val("");

	$.ajax({
		url:"<?php echo site_url('meeting/monitoring/detail'); ?>/" + sales + "/" + pos,
		type:"POST",
		// data:$("#frmspv").serialize(),
		data: function ( data ) {
			data.data = $("#frmspv").serialize();
            data.date_from = $('#date_from').val();
            data.date_to = $('#date_to').val();
        },
		success:function(data){ 
			$("#pop").html('');  
			$("#pop").append(data);
		}  
	});
}

function view_total_meeting_bottom(sales, pos, names)
{
	$('#modalSchedule').modal('show');
	// $('#header-all-schedule').html("<b>"+names+" ( "+pos+" )</b>");	
	// var names_replace = names.replaceAll(' ','_');

	$.ajax({
		// url:"<?php echo site_url('meeting/monitoring/detail_schedule'); ?>/" + names_replace + "/" + pos,
		url:"<?php echo site_url('meeting/monitoring/detail_schedule'); ?>/" + sales + "/" + pos,
		type:"POST",
		// data:$("#frmspv").serialize(),
		data: function ( data ) {
			data.data = $("#frmschedule").serialize();
            data.date_from = $('#date_from').val();
            data.date_to = $('#date_to').val();
        },
		success:function(data){ 
			$("#pop_schedule").html('');  
			$("#pop_schedule").append(data);
		}  
	});
}

function view_total_meeting_up(sales, pos, names)
{
	$('#modalSchedule').modal('show');
	$('#header-all-schedule').html("<b>"+names+" ( "+pos+" )</b>");	
	// var names_replace = names.replaceAll(' ','_');

	$.ajax({
		// url:"<?php echo site_url('meeting/monitoring/detail_schedule'); ?>/" + names_replace + "/" + pos,
		url:"<?php echo site_url('meeting/monitoring/detail_schedule_up'); ?>/" + sales + "/" + pos,
		type:"POST",
		// data:$("#frmspv").serialize(),
		data: function ( data ) {
			data.data = $("#frmschedule").serialize();
            data.date_from = $('#date_from').val();
            data.date_to = $('#date_to').val();
        },
		success:function(data){ 
			$("#pop_schedule").html('');  
			$("#pop_schedule").append(data);
		}  
	});
}

function view_participant(schedule_id)
{
	$('#modalParticipant').modal('show');

	$.ajax({
		url:"<?php echo site_url('meeting/monitoring/detail_participant'); ?>/" + schedule_id,
		type:"POST",
		// data:$("#frmspv").serialize(),
		data: function ( data ) {
			data.data = $("#frmparticipant").serialize();
            data.date_from = $('#date_from').val();
            data.date_to = $('#date_to').val();
        },
		success:function(data){ 
			$("#pop_participant").html('');  
			$("#pop_participant").append(data);
		}  
	});
}


function view_mom(schedule_id){

	$.ajax({
		url:"<?php echo site_url('meeting/monitoring/detail_mom'); ?>/" + schedule_id,
		type: "GET",
        dataType: "JSON",
		// data:$("#frmspv").serialize(),
		success:function(data){ 
            $('[name="hasil_mom"]').val(data.dataMom.MOM);
			$("#body-absensi").empty();
			let no = 0;
			for (let i = 0; i < data.dataList.length; i++) {
				const element = data.dataList[i];
				++no;
				$("#body-absensi").append("<tr>");
				$("#body-absensi").append("<td>"+no+"</td>");
				$("#body-absensi").append("<td>"+data.dataList[i]['NIK']+"</td>");
				$("#body-absensi").append("<td>"+data.dataList[i]['position']+"</td>");
				$("#body-absensi").append("<td>"+data.dataList[i]['Name']+"</td>");
				$("#body-absensi").append("</tr>");
			}
			$('#modalMom').modal('show');
			
		}  
	});
}


function view_absenhadir(schedule_id){
	$.ajax({
		url:"<?php echo site_url('meeting/monitoring/detail_mom'); ?>/" + schedule_id,
		type: "GET",
		dataType: "JSON",
		success:function(data){ 
			$("#body-hadir").empty();
			let no = 0;
			for (let i = 0; i < data.dataList.length; i++) {
				const element = data.dataList[i];
				++no;
				$("#body-hadir").append("<tr>");
				$("#body-hadir").append("<td>"+no+"</td>");
				$("#body-hadir").append("<td>"+data.dataList[i]['NIK']+"</td>");
				$("#body-hadir").append("<td>"+data.dataList[i]['position']+"</td>");
				$("#body-hadir").append("<td>"+data.dataList[i]['Name']+"</td>");
				$("#body-hadir").append("</tr>");
			}
			$('#modalhadir').modal('show');
			
		}  
	});
}

function view_absentidakhadir(schedule_id){
	$.ajax({
		url:"<?php echo site_url('meeting/monitoring/detail_tidakabsen'); ?>/" + schedule_id,
		type: "GET",
		dataType: "JSON",
		success:function(data){ 
			$("#body-tidakhadir").empty();
			let no = 0;
			for (let i = 0; i < data.dataList.length; i++) {
				const element = data.dataList[i];
				++no;
				$("#body-tidakhadir").append("<tr>");
				$("#body-tidakhadir").append("<td>"+no+"</td>");
				$("#body-tidakhadir").append("<td>"+data.dataList[i]['NIK']+"</td>");
				$("#body-tidakhadir").append("<td>"+data.dataList[i]['position']+"</td>");
				$("#body-tidakhadir").append("<td>"+data.dataList[i]['Name']+"</td>");
				$("#body-tidakhadir").append("</tr>");
			}
			$('#modaltidakhadir').modal('show');
			
		}  
	});
}

function view_schedule(sales, pos, names)
{
	$('#modalmeeting').modal('show');
	$('#header-all-meeting').html("<b>"+names+" ( "+pos+" )</b>");	

	$.ajax({
		url:"<?php echo site_url('meeting/monitoring/detail_meeting'); ?>/" + sales + "/" + pos,
		type:"POST",
		data: function ( data ) {
			data.data 		= $("#frmmeeting").serialize();
            data.date_from 	= $('#date_from').val();
            data.date_to 	= $('#date_to').val();
        },
		success:function(data){ 
			$("#pop_meeting").html('');  
			$("#pop_meeting").append(data);
		}  
	});
}

function view_meetinghadir(schedule_id){
	$.ajax({
		url:"<?php echo site_url('meeting/monitoring/detail_meetinghadir'); ?>/" + schedule_id,
		type: "GET",
		dataType: "JSON",
		success:function(data){ 
			$("#body-meetinghadir").empty();
			let no = 0;
			for (let i = 0; i < data.dataList.length; i++) {
				const element = data.dataList[i];
				++no;
				$("#body-meetinghadir").append("<tr>");
				$("#body-meetinghadir").append("<td>"+no+"</td>");
				$("#body-meetinghadir").append("<td>"+data.dataList[i]['NIK']+"</td>");
				$("#body-meetinghadir").append("<td>"+data.dataList[i]['position']+"</td>");
				$("#body-meetinghadir").append("<td>"+data.dataList[i]['Name']+"</td>");
				$("#body-meetinghadir").append("</tr>");
			}
			$('#modalmeetinghadir').modal('show');
			
		}  
	});
}

function view_meetingtidakhadir(schedule_id){
	$.ajax({
		url:"<?php echo site_url('meeting/monitoring/detail_meetingtidakhadir'); ?>/" + schedule_id,
		type: "GET",
		dataType: "JSON",
		success:function(data){ 
			$("#body-meetingtidakhadir").empty();
			let no = 0;
			for (let i = 0; i < data.dataList.length; i++) {
				const element = data.dataList[i];
				++no;
				$("#body-meetingtidakhadir").append("<tr>");
				$("#body-meetingtidakhadir").append("<td>"+no+"</td>");
				$("#body-meetingtidakhadir").append("<td>"+data.dataList[i]['NIK']+"</td>");
				$("#body-meetingtidakhadir").append("<td>"+data.dataList[i]['position']+"</td>");
				$("#body-meetingtidakhadir").append("<td>"+data.dataList[i]['Name']+"</td>");
				$("#body-meetingtidakhadir").append("</tr>");
			}
			$('#modalmeetingtidakhadir').modal('show');
			
		}  
	});
}

function view_detailmeeting(schedule_id){

	$.ajax({
		url:"<?php echo site_url('meeting/monitoring/detail_viewmeeting'); ?>/" + schedule_id,
		type: "GET",
		dataType: "JSON",
		success:function(data){ 
			$('[name="hasil_viewmeeting"]').val(data.dataviewmeeting.MOM);
			$("#body-viewmeeting").empty();
			let no = 0;
			for (let i = 0; i < data.dataList.length; i++) {
				const element = data.dataList[i];
				++no;
				$("#body-viewmeeting").append("<tr>");
				$("#body-viewmeeting").append("<td>"+no+"</td>");
				$("#body-viewmeeting").append("<td>"+data.dataList[i]['NIK']+"</td>");
				$("#body-viewmeeting").append("<td>"+data.dataList[i]['position']+"</td>");
				$("#body-viewmeeting").append("<td>"+data.dataList[i]['Name']+"</td>");
				$("#body-viewmeeting").append("</tr>");
			}
			$('#modalviewmeeting').modal('show');
			
		}  
	});
}

</script> 