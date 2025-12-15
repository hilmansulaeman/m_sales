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
		<h3 class="box-title">Data Sales Under Perform</h3>			  
	</div>
	<div class="panel-body">
		<div class="table-responsive">	
			<div style="height:500px;overflow-y:scroll;">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th rowspan="2">NO</th>
							<th rowspan="2">SPV NAME</th>
							<th rowspan="2">MOB</th>
							<th colspan="6" class="text-center"><?php echo date('M-Y', strtotime('-1 month')); ?></th>
							<th colspan="6" class="text-center"><?php echo date('M-Y'); ?></th>
						</tr>
						<tr>
							<th>INC</th>
							<th>RTS</th>
							<th>NOA</th>
							<th>DEC</th>
							<th>A / R</th>
							<th>RATING</th>
							<th>INC</th>
							<th>RTS</th>
							<th>NOA</th>
							<th>DEC</th>
							<th>A / R</th>
							<th>RATING</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$no =0;
						$tanggal_old = date('Y-m', strtotime('-1 month'));
						$tanggal = date('Y-m');
						foreach ($query->result() as $data) {
							$sqlLs = $this->asm_model->getAppsLsAll($data->DSR_Code, $tanggal_old);
							if($sqlLs->num_rows() > 0)
							{
								$rows = $sqlLs->row();
								$inc_ls = $rows->Inc_cc + $rows->Inc_edc + $rows->Inc_sc;
								$rts_ls = $rows->Rts_cc + $rows->Rts_edc + $rows->Rts_sc;
								$noa_ls = $rows->Noa_cc + $rows->Noa_edc + $rows->Noa_sc;
								$dec_ls = $rows->Dec_cc + $rows->Dec_edc + $rows->Dec_sc;
								$apprate_ls = $rows->Apprate_cc + $rows->Apprate_edc + $rows->Apprate_sc." %";
								$rating_ls = $rows->Ratings;
							}
							else
							{
								$inc_ls = "-";
								$rts_ls = "-";
								$noa_ls = "-";
								$dec_ls = "-";
								$apprate_ls = "-";
								$rating_ls = "-";
							}


							//
							//$sqlInc = $this->asm_model->getIncCurrent($data->DSR_Code, $tanggal);
							//if($sqlInc->num_rows() > 0)
							//{
								//$rInc = $sqlInc->row();
								//$totalnyainc = $rInc->inc_cc + $rInc->inc_edc + $rInc->inc_sc;
								//$totalnyarts = $rInc->rts_cc + $rInc->rts_edc + $rInc->rts_sc;
							//}
							//else
							//{
								//$totInc = 0;
								//$totRts = 0;
							//}
							

							$sql = $this->asm_model->getAppsLsAll($data->DSR_Code, $tanggal);
							if($sql->num_rows() > 0)
							{
								$row = $sql->row();
								$totInc = $row->Inc_cc + $row->Inc_edc + $row->Inc_sc;
								$totRts = $row->Rts_cc + $row->Rts_edc + $row->Rts_sc;
								$noa = $row->Noa_cc + $row->Noa_edc + $row->Noa_sc;
								$dec = $row->Dec_cc + $row->Dec_edc + $row->Dec_sc;
								$apprate = $row->Apprate_cc + $row->Apprate_edc + $row->Apprate_sc." %";
								$ratings = $row->Ratings;
							}
							else
							{
								$totInc = "-";
								$totRts = "-";
								$noa = "-";
								$dec = "-";
								$ratings = "-";
								$apprate = "-";
							}
							
							//datediff
							$tgl1 = $data->Efektif_Date;
							if($tgl1 == "0000-00-00")
							{
								$tgl1 = $data->Join_Date;
							}
							$tgl2 = date('Y-m-d');
							$tm1 = strtotime("$tgl1");
							$tm2 = strtotime("$tgl2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diff = $tm2-$tm1;
							// menghitung selisih bulan
							$numBulanS = $diff / 86400 / 30;
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><a href="#" onclick="Detail('<?php echo $data->DSR_Code; ?>')"><?php echo $data->Name; ?></a></td>
							<td><?php echo round($numBulanS); ?></td>
							<td><?php echo $inc_ls; ?></td>
							<td><?php echo $rts_ls; ?></td>
							<td><?php echo $noa_ls; ?></td>
							<td><?php echo $dec_ls; ?></td>
							<td><?php echo $apprate_ls; ?></td>
							<td>
								<?php
									if($rating_ls == "Under Perform 1" OR $rating_ls == "Under Perform 2"  OR $rating_ls == "Under Perform" OR $rating_ls == "Warning" OR $rating_ls == "Zero Account")
									{
										echo "<span class='label label-danger'>".$rating_ls."</span>";
									}elseif($rating_ls == "Acceptable")
									{
										echo "<span class='label label-success'>".$rating_ls."</span>";
									}elseif($rating_ls == "Standard" OR $rating_ls == "Standar")
									{
										echo "<span class='label label-warning'>".$rating_ls."</span>";

									}elseif($rating_ls == "Excellent 1" OR $rating_ls == "Excellent 2" OR $rating_ls == "Excellent 3" OR $rating_ls == "Excellent 4")
									{
										echo "<span class='label label-primary'>".$rating_ls."</span>";
									}
								?>
							</td>
							<td><?php echo $totInc; ?></td>
							<td><?php echo $totRts; ?></td>
							<td><?php echo $noa; ?></td>
							<td><?php echo $dec; ?></td>
							<td><?php echo $apprate; ?></td>
							<td>
								<?php
									if($ratings == "Under Perform 1" OR $ratings == "Under Perform 2"  OR $ratings == "Under Perform" OR $ratings == "Warning" OR $ratings == "Zero Account")
									{
										echo "<span class='label label-danger'>".$ratings."</span>";
									}elseif($ratings == "Acceptable")
									{
										echo "<span class='label label-success'>".$ratings."</span>";
									}elseif($ratings == "Standard" OR $ratings == "Standar")
									{
										echo "<span class='label label-warning'>".$ratings."</span>";

									}elseif($ratings == "Excellent 1" OR $ratings == "Excellent 2" OR $ratings == "Excellent 3" OR $ratings == "Excellent 4")
									{
										echo "<span class='label label-primary'>".$ratings."</span>";
									}
								?>
							</td>
						</tr>
						<?php

							$sqlBreakdown = $this->asm_model->getAllDsrBySpv($data->DSR_Code);
							$nod = 0;
							foreach($sqlBreakdown->result() as $req) {
								$sqlDataOld = $this->asm_model->getAppsLsAllDsr($req->DSR_Code, $tanggal_old);
								if($sqlDataOld->num_rows() > 0)
								{
									$rOld = $sqlDataOld->row();
									$incOld = $rOld->Inc_cc + $rOld->Inc_edc + $rOld->Inc_sc;
									$rtsOld = $rOld->Rts_cc + $rOld->Rts_edc + $rOld->Rts_sc;
									$noaOld = $rOld->Noa_cc + $rOld->Noa_edc + $rOld->Noa_sc;
									$decOld = $rOld->Dec_cc + $rOld->Dec_edc + $rOld->Dec_sc;
									$appOld = $rOld->Apprate_cc + $rOld->Apprate_edc + $rOld->Apprate_sc." %";
									$RatingOld = $rOld->ratings;
								}
								else
								{
									$incOld = "-";
									$rtsOld = "-";
									$noaOld = "-";
									$decOld = "-";
									$appOld = "-";
									$RatingOld = "-";
								}

								//
								/*$qryCurDsr = $this->asm_model->getIncCurrentDsr($req->DSR_Code, $tanggal);
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
								}*/

								$sqlDataNow = $this->asm_model->getAppsLsAllDsr($req->DSR_Code, $tanggal);
								if($sqlDataNow->num_rows() > 0)
								{
									$rNow = $sqlDataNow->row();
									$incNow = $rNow->Inc_cc + $rNow->Inc_edc + $rNow->Inc_sc;
									$rtsNow = $rNow->Rts_cc + $rNow->Rts_edc + $rNow->Rts_sc;
									$noaNow = $rNow->Noa_cc + $rNow->Noa_edc + $rNow->Noa_sc;
									$decNow = $rNow->Dec_cc + $rNow->Dec_edc + $rNow->Dec_sc;
									$appNow = $rNow->Apprate_cc + $rNow->Apprate_edc + $rNow->Apprate_sc." %";
									$RatingNow = $rNow->ratings;
								}
								else
								{
									$incNow = "-";
									$rtsNow = "-";
									$noaNow = "-";
									$decNow = "-";
									$appNow = "-";
									$RatingNow = "-";
								}
								//datediff
								$date1 = $req->Efektif_Date;
								if($date1 == "0000-00-00")
								{
									$date1 = $req->Join_Date;
								}
								$date2 = date('Y-m-d');
								$timeStart = strtotime("$date1");
								$timeEnd = strtotime("$date2");
								// Menambah bulan ini + semua bulan pada tahun sebelumnya
								$diff = $timeEnd-$timeStart;
								// menghitung selisih bulan
								$numBulan = $diff / 86400 / 30;

						?>
						<tr class="tr_<?php echo $data->DSR_Code; ?>" style='font-size: 11px; display: none;'>
							<td class="text-right"><?php echo ++$nod; ?></td>
							<td class="text-right"> <b>Dsr</b> <?php echo $req->Name; ?></td>
							<td class="text-right"> <?php echo round($numBulan); ?></td>
							<td><?php echo $incOld; ?></td>
							<td><?php echo $rtsOld; ?></td>
							<td><?php echo $noaOld; ?></td>
							<td><?php echo $decOld; ?></td>
							<td><?php echo $appOld; ?></td>
							<td>
								<?php
									if($RatingOld == "Under Perform 1" OR $RatingOld == "Under Perform 2"  OR $RatingOld == "Under Perform" OR $RatingOld == "Warning" OR $RatingOld == "Zero Account")
									{
										echo "<span class='label label-danger'>".$RatingOld."</span>";
									}elseif($RatingOld == "Acceptable")
									{
										echo "<span class='label label-success'>".$RatingOld."</span>";
									}elseif($RatingOld == "Standard" OR $RatingOld == "Standar")
									{
										echo "<span class='label label-warning'>".$RatingOld."</span>";

									}elseif($RatingOld == "Excellent 1" OR $RatingOld == "Excellent 2" OR $RatingOld == "Excellent 3" OR $RatingOld == "Excellent 4")
									{
										echo "<span class='label label-primary'>".$RatingOld."</span>";
									}
								?>
							</td>
							<td><?php echo $incNow; ?></td>
							<td><?php echo $rtsNow; ?></td>
							<td><?php echo $noaNow; ?></td>
							<td><?php echo $decNow; ?></td>
							<td><?php echo $appNow; ?></td>
							<td>
								<?php
									if($RatingNow == "Under Perform 1" OR $RatingNow == "Under Perform 2"  OR $RatingNow == "Under Perform" OR $RatingNow == "Warning" OR $RatingNow == "Zero Account")
									{
										echo "<span class='label label-danger'>".$RatingNow."</span>";
									}elseif($RatingNow == "Acceptable")
									{
										echo "<span class='label label-success'>".$RatingNow."</span>";
									}elseif($RatingNow == "Standard" OR $RatingNow == "Standar")
									{
										echo "<span class='label label-warning'>".$RatingNow."</span>";

									}elseif($RatingNow == "Excellent 1" OR $RatingNow == "Excellent 2" OR $RatingNow == "Excellent 3" OR $RatingNow == "Excellent 4")
									{
										echo "<span class='label label-primary'>".$RatingNow."</span>";
									}
								?>
							</td>
						</tr>
					<?php
							}
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