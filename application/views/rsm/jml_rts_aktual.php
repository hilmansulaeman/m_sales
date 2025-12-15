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
						$jml = 0;
						$jml_inc = 0;
						foreach ($query->result() as $data) {
							$sqlRts = $this->rsm_model->getAllApps($data->ASM_Code, $tanggal);
							$dtRts = $sqlRts->row();
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
							<td><b>ASM</b> <?php echo $data->ASM_Name; ?></td>
							<td>-</td>
							<td><?php echo $inc; ?></td>
							<td><a href="#" onclick="Detail('<?php echo $data->ASM_Code ?>')"><?php echo $rts; ?></a></td>
							<td><?php echo round($persen); ?> %</td>
						</tr>
					<?php
						$noms = 0;
						$tgl_now = date('Y-m');
						$sql_us = $this->rsm_model->getSpvByAsm($data->ASM_Code);
							foreach ($sql_us->result() as $dt) {
								//real
								$sqlInc = $this->rsm_model->getIncCurrent($dt->SPV_Code, $tgl_now);
								if($sqlInc->num_rows() > 0)
								{
									$rInc = $sqlInc->row();
									$totsInc = $rInc->inc_cc + $rInc->inc_edc + $rInc->inc_sc;
									$totsRts = $rInc->rts_cc + $rInc->rts_edc + $rInc->rts_sc;
								}
								else
								{
									$totsInc = 0;
									$totsRts = 0;
								}
								if($totsRts > 0 AND $totsInc > 0)
								{
									$kals = ($totsRts / $totsInc) * 100;
								}
								else
								{
									$kals = 0;
								}

								//datediff
								$date1 = $dt->Join_Date;
								$date2 = date('Y-m-d');
								$timeStart = strtotime("$date1");
								$timeEnd = strtotime("$date2");
								// Menambah bulan ini + semua bulan pada tahun sebelumnya
								$diff = $timeEnd-$timeStart;
								// menghitung selisih bulan
								$numBulan = $diff / 86400 / 30;
					?>
						<tr class="tr_<?php echo $data->ASM_Code ?>" style="font-size: 11px; display: none;">
							<td class="text-right"><?php echo ++$noms; ?></td>
							<td><b>SPV</b> <?php echo $dt->SPV_Name; ?></td>
							<td><?php echo round($numBulan); ?></td>
							<td><?php echo $totsInc; ?></td>
							<td><?php echo $totsRts; ?></td>
							<td><?php echo round($kals); ?> %</td>
						</tr>
					<?php
						}
					?>
					<?php
						//reals 
						$sqlDummySpvCur = $this->rsm_model->getDummyCurrently($data->ASM_Code,$tanggal);
						if($sqlDummySpvCur->num_rows() > 0)
						{
							$dtCrnly = $sqlDummySpvCur->row();
							$inc_cr = $dtCrnly->inc_cc + $dtCrnly->inc_edc + $dtCrnly->inc_sc;
							$rts_cr = $dtCrnly->rts_cc + $dtCrnly->rts_edc + $dtCrnly->rts_sc;
						}
						else
						{
							$inc_cr = "-";
							$rts_cr = "-";
						}
						if($inc_cr > 0 AND $rts_cr)
						{
							$perhitung = ($rts_cr / $inc_cr) * 100;
						}
						else
						{
							$perhitung = 0;
						}
					?>
						<tr class="tr2_<?php echo $data->ASM_Code ?>" style="font-size: 11px; display: none;">
							<td class="text-right"><?php echo ++$noms; ?></td>
							<td><b>SPV</b> DUMMY SPV</td>
							<td></td>
							<td><?php echo $inc_cr; ?></td>
							<td><?php echo $rts_cr; ?></td>
							<td><?php echo round($perhitung); ?> %</td>
						</tr>
					<?php
							$jml += $rts;
							$jml_inc += $inc;
						}
					?>
					<?php
						$sqlDummyAsm = $this->rsm_model->getRtsDummyAsm($this->uri->segment(3), $tanggal);
						if($sqlDummyAsm->num_rows() > 0)
						{
							$rDum = $sqlDummyAsm->row();
							$dtDum_inc = $rDum->inc_cc + $rDum->inc_edc + $rDum->inc_sc;
							$dtDum = $rDum->rts_cc + $rDum->rts_edc + $rDum->rts_sc;
						}
						else
						{
							$dtDum_inc = 0;
							$dtDum = 0;
						}
						$total = $jml + $dtDum;
						$totals= $jml_inc + $dtDum_inc;
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td>DUMMY ASM</td>
							<td>-</td>
							<td><?php echo $dtDum_inc; ?></td>
							<td><a href="#" onclick="Detail2()"><?php echo $dtDum; ?></a></td>
							<?php
								if($dtDum > 0 AND $dtDum_inc > 0)
								{
									$cals = $dtDum / $dtDum_inc;
								}
								else
								{
									$cals = 0;
								}
							?>
							<td><?php echo round($cals);  ?>%</td>
						</tr>
					<?php
						$sqlDummy = $this->rsm_model->getDsrDummy($this->uri->segment(3));
						$nods = 0;
						foreach ($sqlDummy->result() as $rDumay) {

							//
							$qrD = $this->rsm_model->getIncCurrentDsr($rDumay->DSR_Code, $tanggal);
							if($qrD->num_rows() > 0)
							{
								$qrDt = $qrD->row();
								$totDmInc = $qrDt->inc_cc + $qrDt->inc_edc + $qrDt->inc_sc;
								$totDmRts = $qrDt->rts_cc + $qrDt->rts_edc + $qrDt->rts_sc;
							}
							else
							{
								$totDmInc = "-";
								$totDmRts = "-";
							}

							if($totDmInc > 0 AND $totDmRts > 0)
							{
								$kall = $totDmRts / $totDmInc;
							}
							else
							{
								$kall = 0;
							}

							//datediff
							$dates1 = $rDumay->Join_Date;
							$dates2 = date('Y-m-d');
							$timeStart1 = strtotime("$dates1");
							$timeEnd2 = strtotime("$dates2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diff2 = $timeEnd2-$timeStart1;
							// menghitung selisih bulan
							$numBulans2 = $diff2 / 86400 / 30;
					?>
						<tr class="tr_detail2" style="font-size: 11px; display: none;">
							<td class="text-right"><?php echo ++$nods; ?></td>
							<td><b>SPV</b> <?php echo $rDumay->Name; ?></td>
							<td><?php echo round($numBulans2); ?></td>
							<td><?php echo $totDmInc; ?></td>
							<td><?php echo $totDmRts; ?></td>
							<td><?php echo number_format($kall, 2); ?> %</td>
						</tr>
					<?php
						}
					?>
						<tr>
							<td></td>
							<td></td>
							<td class="text-right"><b>Total</b></td>
							<td><b><?php echo $totals; ?></b></td>
							<td><b><?php echo $total; ?></b></td>
							<td><?php echo round($total / $totals * 100); ?>%</td>
						</tr>
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
    $('.tr_detail2').toggle();
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