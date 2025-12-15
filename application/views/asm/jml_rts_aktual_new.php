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
		<h3 class="box-title">DATA SUMMARY RTS NEW</h3>			  
	</div>
	<div class="panel-body">
		<div class="table-responsive">	
			<div style="height:500px;overflow-y:scroll;">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>NO</th>
							<th>SPV NAME</th>
							<th>MOB</th>
							<th>TOTAL INC</th>
							<th>TOTAL RTS</th>
							<th>%</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$no = 0;
						$tanggal = date('Y-m');
						$tanggal_old = date('Y-m', strtotime('-1 month'));
						foreach ($query->result() as $rows) {

							$date1 = $rows->Efektif_Date;
							if($date1 == "0000-00-00")
							{
								$tglnya = $rows->Join_Date;
							}
							else
							{
								$tglnya = $date1;
							}
							$date2 = date('Y-m-d');
							$timeStart = strtotime("$tglnya");
							$timeEnd = strtotime("$date2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diff = $timeEnd-$timeStart;
							// menghitung selisih bulan
							$numBulan = $diff / 86400 / 30;

							//AMBIL ANGKA

							$sql_angka_ls = $this->asm_model->getAppsBySpv($rows->SPV_Code, $tanggal);
							if($sql_angka_ls->num_rows() > 0)
							{
								$dt_ls = $sql_angka_ls->row();
								$inc_ls = $dt_ls->Inc_cc + $dt_ls->Inc_edc + $dt_ls->Inc_sc;
								$rts_ls = $dt_ls->Rts_cc + $dt_ls->Rts_edc + $dt_ls->Rts_sc;
							}
							else
							{
								$inc_ls = "-";
								$rts_ls = "-";
							}

							if($inc_ls > 0 AND $rts_ls > 0)
							{
								$hitung = ($rts_ls / $inc_ls) * 100;
							}
							else
							{
								$hitung = 0;
							}
							
							//
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><?php echo $rows->SPV_Name; ?></td>
							<td><?php echo round($numBulan); ?></td>
							<td><?php echo $inc_ls; ?></td>
							<td>
								<a href="<?php echo base_url(); ?>asm/detail_rts/<?php echo $rows->SPV_Code ?>/<?php echo $rows->Product ?>" target="_blank">
									<?php echo $rts_ls; ?>
								</a>
							</td>
							<td><?php echo round($hitung); ?> %</td>
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
function Detail(dsr_code)
{
    $('.tr_'+dsr_code).toggle()
}
function Detail2()
{
	$('.tr_detail2').toggle()
}
</script>
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