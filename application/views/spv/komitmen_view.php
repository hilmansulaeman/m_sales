<?php
	$kat = str_replace('_', ' ', $this->uri->segment(4));
	
	function TimeColour($time) 
	{
		if($time <= 0){
			echo "<span class='label label-danger'>".$time." Hari</span>";
		}
		else if($time >= 1 && $time < 10){
			echo "<span class='label label-warning'>".$time." Hari</span>";
		}
		else if($time >= 10 && $time < 20){
			echo "<span class='label label-success'>".$time." Hari</span>";
		}
		else if($time >= 20){
			echo "<span class='label label-primary'>".$time." Hari</span>";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sales Monitoring</title>
	<!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/bootstrap/css/bootstrap.min.css">	
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/dist/css/skins/_all-skins.min.css">
	<!-- Date Picker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/plugins/daterangepicker/daterangepicker.css">
	<!--DataTable-->
	<link href="<?php echo base_url(); ?>public/mytemplate/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css">
</head>
<body>
<div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">&nbsp;</i>
			</button>
		</div>		
		<h3 class="box-title">DATA KOMITMEN <?php echo $kat; ?></h3>			  
	</div>
	<div class="panel-body">
		<div class="table-responsive">	
			<div style="height:500px;overflow-y:scroll;">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>No</th>
							<th>Komitmen</th>
							<th>Tgl Dibuat</th>
							<th>Tgl Komitmen</th>
							<th>Sisa Waktu</th>
							<th>Status</th>
							<th>Control</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$no =0;
						foreach($query->result() as $dt)
						{
							$tgldibuat = date("d-M-Y", strtotime($dt->created_date));
							$tglkomit = date("d-M-Y", strtotime($dt->tanggal));
							
							$tgl1 = date('Y-m-d');
							$tgl2 = $dt->tanggal;
							$selisih = strtotime($tgl2) -  strtotime($tgl1);
							$hari = $selisih/(60*60*24);
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><?php echo $dt->keterangan; ?></td>
							<td><?php echo $tgldibuat; ?></td>
							<td><?php echo $tglkomit; ?></td>
							<td><?php echo $hari; ?> Hari</td>
							<td><?php echo $dt->status; ?></td>
							<td>
								<a href="<?php echo base_url(); ?>spv/ubah_status/<?php echo $this->uri->segment(4) ?>/<?php echo $dt->id; ?>" class="btn btn-primary btn-xs">
									<i class="fa fa-edit"></i>
								</a>
							</td>
						</tr>
					<?php
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>     
</div>
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url(); ?>public/mytemplate/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url() ?>public/mytemplate/plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url() ?>public/mytemplate/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>public/mytemplate/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables/media/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
</body>
</html>
<script type="text/javascript">
$(document).ready(function() {
	$('#example2').DataTable({
			responsive: true,
			"paging" : false,
			"label" : false
	});
});
$(document).ready(function () {
	$('.tanggal').datepicker({
		format: "yyyy-mm-dd",
		autoclose:true
	});
});
$(".date-input").datepicker({
	format: "yyyy-mm-dd",
	autoclose:true
});
$(document).ready(function() {
	$('#example').DataTable();
	 searching: false
} );
</script>