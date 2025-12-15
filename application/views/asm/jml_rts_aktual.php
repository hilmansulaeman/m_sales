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
							<th>SPV NAME</th>
							<th>MOB</th>
							<th>TOTAL INC</th>
							<th>TOTAL RTS</th>
							<th>%</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$no =0;
						$tanggal = date('Y-m');
						$jml = 0;
						$jml_inc = 0;
						foreach($query->result() as $dt)
						{
							$sql_rts = $this->asm_model->getRts($dt->SPV_Code, $tanggal);
							$rowRts = $sql_rts->row();
							$inc = $rowRts->inc_cc + $rowRts->inc_edc + $rowRts->inc_sc; 
							$rts = $rowRts->cc + $rowRts->edc + $rowRts->sc;
							if($inc > 0 AND $rts > 0)
							{
								$hit = ($rts / $inc) * 100;
							}else
							{
								$hit = 0;
							}
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>Spv</b> <?php echo $dt->SPV_Name; ?></td>
							<td>-</td>
							<td><?php echo $inc; ?></td>
							<td><a href="#" onclick="Detail('<?php echo $dt->SPV_Code; ?>')"><?php echo $rts; ?></a></td>
							<td><?php echo number_format($hit); ?> %</td>
						</tr>
					<?php
						$sqlDownline = $this->asm_model->getAllDsrBySpv($dt->SPV_Code);
						$nod = 0;
						foreach ($sqlDownline->result() as $dtDown) {

							//
							$qryCurDsr = $this->asm_model->getIncCurrentDsr($dtDown->DSR_Code, $tanggal);
							if($qryCurDsr->num_rows() > 0)
							{
								$CurDsr = $qryCurDsr->row();
								$totCurInc = $CurDsr->inc_cc + $CurDsr->inc_edc + $CurDsr->inc_sc;
								$totCurRts = $CurDsr->rts_cc + $CurDsr->rts_edc + $CurDsr->rts_sc;
							}
							else
							{
								$totCurInc = "-";
								$totCurRts = "-";
							}


							$date1 = $dtDown->Join_Date;
							$date2 = date('Y-m-d');
							$timeStart = strtotime("$date1");
							$timeEnd = strtotime("$date2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diff = $timeEnd-$timeStart;
							// menghitung selisih bulan
							$numBulan = $diff / 86400 / 30;

							if($totCurRts > 0 AND $totCurInc > 0)
							{
								$kals = ($totCurRts / $totCurInc) * 100;
							}
							else
							{
								$kals = 0;
							}
					?>
						<tr class="tr_<?php echo $dt->SPV_Code; ?>" style="font-size:11px; display: none">
							<td class="text-right"><?php echo ++$nod; ?>.</td>
							<td class="text-right"><b>Dsr</b> <?php echo $dtDown->Name; ?></td>
							<td><?php echo round($numBulan); ?></td>
							<td><?php echo $totCurInc; ?></td>
							<td><?php echo $totCurRts; ?></td>
							<td><?php echo number_format($kals); ?> %</td>
						</tr>
					<?php } ?>
					<?php
							$jml += $rts;
							$jml_inc += $inc;
						}
					?>
						<?php
							$asm_code = $this->session->userdata('sl_code');
							$sql_rts_dm = $this->asm_model->getDataDm($asm_code, $tanggal);
							$rowDm = $sql_rts_dm->row();
							$count_inc = $rowDm->inc_cc + $rowDm->inc_edc + $rowDm->inc_sc + $rowDm->inc_pl + $rowDm->inc_corp;
							$counter = $rowDm->cc + $rowDm->edc + $rowDm->sc + $rowDm->pl + $rowDm->corp;
							if($count_inc > 0 AND $counter > 0)
							{
								$hits = ($counter / $count_inc) * 100;
							}
							else
							{
								$hits = 0;
							}
						?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>Spv</b> DUMMY SPV</td>
							<td>-</td>
							<td><?php echo $count_inc; ?></td>
							<td><a href="#" onclick="Detail2();"><?php echo $counter; ?></a></td>
							<td><?php echo number_format($hits); ?> %</td>
						</tr>
					<?php
						$sqlDummy = $this->asm_model->getDsrDummy($this->uri->segment(3));
						$nods = 0;
						foreach ($sqlDummy->result() as $rDum) {
							//
							$sqlCurrent = $this->asm_model->getIncCurrentDsr($rDum->DSR_Code, $tanggal);
							if($sqlCurrent->num_rows() > 0)
							{
								$apCur = $sqlCurrent->row();
								$totalnyaInc = $apCur->inc_cc + $apCur->inc_edc + $apCur->inc_sc;
								$totalnyaRts = $apCur->rts_cc + $apCur->rts_edc + $apCur->rts_sc;
							}
							else
							{
								$totalnyaInc = "-";
								$totalnyaRts = "-";
							}

							$tgl1 = $rDum->Join_Date;
							$tgl2 = date('Y-m-d');
							$tmstr = strtotime("$tgl1");
							$tmend = strtotime("$tgl2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diffs = $tmend-$tmstr;
							// menghitung selisih bulan
							$numBulans = $diffs / 86400 / 30;

							if($totalnyaRts > 0 AND $totalnyaInc > 0)
							{
								$kall = ($totalnyaRts / $totalnyaInc) * 100;
							}
							else
							{
								$kall = 0;
							}
					?>
						<tr class="tr_detail2" style="font-size: 11px; display: none;">
							<td class="text-right"><?php echo ++$nods; ?>.</td>
							<td class="text-right"><b>Dsr</b> <?php echo $rDum->Name; ?></td>
							<td><?php echo round($numBulans) ?></td>
							<td><?php echo $totalnyaInc; ?></td>
							<td><?php echo $totalnyaRts; ?></td>
							<td><?php echo number_format($kall); ?> %</td>
						</tr>
					<?php
						}
					?>
						<tr>
							<td></td>
							<td></td>
							<td class="text-right"><b>Total</b></td>
							<td><b><?php echo $jml_inc + $count_inc; ?></b></td>
							<td><b><?php echo $jml + $counter; ?></b></td>
							<td>-</td>
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