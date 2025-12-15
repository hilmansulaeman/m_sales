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
		<h3 class="box-title">DATA SUMMARY RTS</h3>			  
	</div>
	<div class="panel-body">
		<div class="table-responsive">	
			<div style="height:500px;overflow-y:scroll;">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>NO</th>
							<th>NAME</th>
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
						foreach ($query->result() as $rows) {
							//datediff
							$date1 = $rows->Join_Date;
							$date2 = date('Y-m-d');
							$timeStart = strtotime("$date1");
							$timeEnd = strtotime("$date2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diff = $timeEnd-$timeStart;
							// menghitung selisih bulan
							$numBulan = $diff / 86400 / 30;

							$apps = $this->bsh_model->getAllApps($rows->RSM_Code, $tanggal);
							$dtRts = $apps->row();
							$inc = $dtRts->inc_cc + $dtRts->inc_edc + $dtRts->inc_sc;
							$rts = $dtRts->rts_cc + $dtRts->rts_edc + $dtRts->rts_sc;
							if($inc > 0 AND $rts > 0)
							{
								$persen = ($rts / $inc) * 100;
							}
							else
							{
								$persen = 0;
							}
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>RSM</b> <a href="#" onclick="Detail('<?php echo $rows->RSM_Code ?>')"><?php echo $rows->RSM_Name; ?></a></td>
							<td><?php echo round($numBulan); ?></td>
							<td><?php echo $inc; ?></td>
							<td><?php echo $rts; ?></td>
							<td><?php echo round($persen); ?> %</td>
						</tr>
					<?php
						$nos = 0;
						$sqlDown = $this->bsh_model->getAsmByRsm($rows->RSM_Code);
						foreach ($sqlDown->result() as $child) {

							//realtime
							$qryInc = $this->bsh_model->CurrentAppsAsm($child->ASM_Code, $tanggal);
							if($qryInc->num_rows() > 0)
							{
								$curR = $qryInc->row();
								$jml_inc = $curR->inc_cc + $curR->inc_edc + $curR->inc_sc;
								$jml_rts = $curR->rts_cc + $curR->rts_edc + $curR->rts_sc;
							}
							else
							{
								$jml_inc = 0;
								$jml_rts = 0;
							}

							if($jml_inc > 0 AND $jml_rts > 0)
							{
								$hits = ($jml_rts / $jml_inc) * 100;
							}
							else
							{
								$hits = 0;
							}

							//datediff
							$tgl1 = $child->Join_Date;
							$tgl2 = date('Y-m-d');
							$timeStart1 = strtotime("$tgl1");
							$timeEnd1 = strtotime("$tgl2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diff1 = $timeEnd1-$timeStart1;
							// menghitung selisih bulan
							$numBulan1 = $diff1 / 86400 / 30;
					?>
						<tr class="tr_<?php echo $rows->RSM_Code ?>" style="font-size: 11px; display: none;">
							<td class="text-right"><?php echo ++$nos;?></td>
							<td class="text-right"><b>ASM </b><?php echo $child->ASM_Name; ?></td>
							<td><?php echo round($numBulan1); ?></td>
							<td><?php echo $jml_inc; ?></td>
							<td><?php echo $jml_rts; ?></td>
							<td><?php echo round($hits); ?> %</td>
						</tr>
					<?php
						}
					?>
					<?php
						$curDataDum = $this->bsh_model->getCurentDummys($rows->RSM_Code, $tanggal);
						if($curDataDum->num_rows() > 0)
						{
							$dtDums = $curDataDum->row();
							$incDums = $dtDums->inc_cc + $dtDums->inc_edc + $dtDums->inc_sc;
							$rtsDums = $dtDums->rts_cc + $dtDums->rts_edc + $dtDums->rts_sc;
						}
						else
						{
							$incDums = "-";
							$rtsDums = "-";
						}

						if($incDums > 0 AND $rtsDums > 0)
						{
							$hitung = ($rtsDums / $incDums) * 100;
						}
						else
						{
							$hitung = 0;
						}


					?>
						<tr class="tr2_<?php echo $rows->RSM_Code; ?>" style="font-size: 11px; display: none;">
							<td class="text-right"><?php echo ++$nos; ?></td>
							<td class="text-right"><b>ASM</b> DUMMY ASM</td>
							<td>-</td>
							<td><?php echo $incDums; ?></td>
							<td><?php echo $rtsDums; ?></td>
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
    $('.tr_'+dsr_code).toggle();
    $('.tr2_'+dsr_code).toggle();
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