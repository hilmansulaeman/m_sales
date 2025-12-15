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
		<h3 class="box-title">DATA SUMMARY SPV</h3>			  
	</div>
	<div class="panel-body">
		<div class="table-responsive">	
			<div id="mynav" style="height:500px;">
				<table id="mytable" class="table table-hover table-bordered">
					<thead>
						<tr>
							<th rowspan="2">NO</th>
							<th rowspan="2">NAME</th>
							<th rowspan="2">MOB</th>
							<th rowspan="2">DSR</th>
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
						$no = 0;
						$tanggal = date('Y-m');
						$tanggal_old = date('Y-m', strtotime('-1 month'));
						foreach ($query->result() as $val) {
							
							//datediff
							$dates1 = $val->Efektif_Date;
							if($dates1 == "0000-00-00")
							{
								$dates1 = $val->Join_Date;
							}
							$dates2 = date('Y-m-d');
							$awal = strtotime("$dates1");
							$akhir = strtotime("$dates2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diffs = $akhir-$awal;
							// menghitung selisih bulan
							$totalbulan = $diffs / 86400 / 30;
							
							$sqlLs = $this->rsm_model->getAppsLsAll($val->SPV_Code, $tanggal_old);
							if($sqlLs->num_rows() > 0)
							{
								$rows = $sqlLs->row();
								$inc_ls = $rows->Inc_cc + $rows->Inc_edc + $rows->Inc_sc;
								$rts_ls = $rows->Rts_cc + $rows->Rts_edc + $rows->Rts_sc;
								$noa_ls = $rows->Noa_cc + $rows->Noa_edc + $rows->Noa_sc;
								$dec_ls = $rows->Dec_cc + $rows->Dec_edc + $rows->Dec_sc;
								//$apprate_ls = $rows->Apprate_cc + $rows->Apprate_edc + $rows->Apprate_sc." %";
								$rating_ls = $rows->Ratings;
								if($val->Product == "CC")
								{
									$apprate_ls = $rows->Apprate_cc;
								}
								elseif($val->Product == "EDC")
								{
									$apprate_ls = $rows->Apprate_edc;
								}
								elseif($val->Product == "SC")
								{
									$apprate_ls = $rows->Apprate_sc;
								}
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
							/*$sqlInc = $this->rsm_model->getIncCurrent($val->SPV_Code, $tanggal);
							if($sqlInc->num_rows() > 0)
							{
								$rInc = $sqlInc->row();
								$totInc = $rInc->inc_cc + $rInc->inc_edc + $rInc->inc_sc;
								$totRts = $rInc->rts_cc + $rInc->rts_edc + $rInc->rts_sc;
							}
							else
							{
								$totInc = 0;
								$totRts = 0;
							}*/

							$sql = $this->rsm_model->getAppsLsAll($val->SPV_Code, $tanggal);
							if($sql->num_rows() > 0)
							{
								$row = $sql->row();
								$inc = $row->Inc_cc + $row->Inc_edc + $row->Inc_sc;
								$rts = $row->Rts_cc + $row->Rts_edc + $row->Rts_sc;
								$noa = $row->Noa_cc + $row->Noa_edc + $row->Noa_sc;
								$dec = $row->Dec_cc + $row->Dec_edc + $row->Dec_sc;
								$ratings = $row->Ratings;
								if($val->Product == "CC")
								{
									$apprate = $row->Apprate_cc;
								}
								elseif($val->Product == "EDC")
								{
									$apprate = $row->Apprate_edc;
								}
								elseif($val->Product == "SC")
								{
									$apprate = $row->Apprate_sc;
								}
								
								
							}
							else
							{
								$inc = "-";
								$rts = "-";
								$noa = "-";
								$dec = "-";
								$ratings = "-";
								$apprate = "-";
							}

					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>SPV</b> <a href="#" onclick="Detail('<?php echo $val->SPV_Code; ?>')"><?php echo $val->SPV_Name; ?></a></td>
							<td><?php echo round($totalbulan); ?></td>
							<td><?php echo $val->totalnya; ?></td>
							<td><?php echo $inc_ls; ?></td>
							<td><?php echo $rts_ls; ?></td>
							<td><?php echo $noa_ls; ?></td>
							<td><?php echo $dec_ls; ?></td>
							<td><?php echo round($apprate_ls); ?></td>
							<td>
							<?php
								if($rating_ls == "Under Perform" OR $rating_ls == "Under Perform 1" OR $rating_ls == "Under Perform 2" OR $rating_ls == "Warning")
								{
									echo "<span class='label label-danger'>".$rating_ls."</span>";
								}
								elseif($rating_ls == "Acceptable")
								{
									echo "<span class='label label-success'>".$rating_ls."</span>";
								}
								elseif($rating_ls == "Standard" OR $rating_ls == "Standar" OR $rating_ls == "Zero Account")
								{
									echo "<span class='label label-warning'>".$rating_ls."</span>";
								}
								elseif($rating_ls == "Excellent" OR $rating_ls == "Excellent 1" OR $rating_ls == "Excellent 2")
								{
									echo "<span class='label label-primary'>".$rating_ls."</span>";
								}
								else
								{
									echo $rating_ls;
								}
							?>
							</td>
							<td><?php echo $inc; ?></td>
							<td><?php echo $rts; ?></td>
							<td><?php echo $noa; ?></td>
							<td><?php echo $dec; ?></td>
							<td><?php echo round($apprate); ?></td>
							<td>
							<?php
								if($ratings == "Under Perform" OR $ratings == "Under Perform 1" OR $ratings == "Under Perform 2" OR $ratings == "Warning")
								{
									echo "<span class='label label-danger'>".$ratings."</span>";
								}
								elseif($ratings == "Acceptable")
								{
									echo "<span class='label label-success'>".$ratings."</span>";
								}
								elseif($ratings == "Standard" OR $ratings == "Standar" OR $ratings == "Zero Account")
								{
									echo "<span class='label label-warning'>".$ratings."</span>";
								}
								elseif($ratings == "Excellent" OR $ratings == "Excellent 1" OR $ratings == "Excellent 2")
								{
									echo "<span class='label label-primary'>".$ratings."</span>";
								}
								else
								{
									echo $ratings;
								}
							?>
							</td>
						</tr>

						<!--Breakdown-->

						<?php

							$sqlBreakdown = $this->rsm_model->getAllDsrBySpv($val->SPV_Code);
							$nod = 0;
							foreach($sqlBreakdown->result() as $req) {

								$sqlDataOld = $this->rsm_model->getAppsLsAllDsr($req->DSR_Code, $tanggal_old);
								if($sqlDataOld->num_rows() > 0)
								{
									$rOld = $sqlDataOld->row();
									$incOld = $rOld->Inc_cc + $rOld->Inc_edc + $rOld->Inc_sc;
									$rtsOld = $rOld->Rts_cc + $rOld->Rts_edc + $rOld->Rts_sc;
									$noaOld = $rOld->Noa_cc + $rOld->Noa_edc + $rOld->Noa_sc;
									$decOld = $rOld->Dec_cc + $rOld->Dec_edc + $rOld->Dec_sc;
									$RatingOld = $rOld->ratings;
									if($req->Product == "CC")
									{
										$appOld = $rOld->Apprate_cc." %";
									}
									elseif($req->Product == "EDC")
									{
										$appOld = $rOld->Apprate_edc." %";
									}
									elseif($req->Product == "SC")
									{
										$appOld = $rOld->Apprate_sc." %";
									}
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
								/*$qryCurDsr = $this->rsm_model->getIncCurrentDsr($req->DSR_Code, $tanggal);
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

								$sqlDataNow = $this->rsm_model->getAppsLsAllDsr($req->DSR_Code, $tanggal);
								if($sqlDataNow->num_rows() > 0)
								{
									$rNow = $sqlDataNow->row();
									$incNow = $rNow->Inc_cc + $rNow->Inc_edc + $rNow->Inc_sc;
									$rtsNow = $rNow->Rts_cc + $rNow->Rts_edc + $rNow->Rts_sc;
									$noaNow = $rNow->Noa_cc + $rNow->Noa_edc + $rNow->Noa_sc;
									$decNow = $rNow->Dec_cc + $rNow->Dec_edc + $rNow->Dec_sc;
									$RatingNow = $rNow->ratings;
									if($req->Product == "CC")
									{
										$appNow = $rNow->Apprate_cc." %";
									}
									elseif($req->Product == "EDC")
									{
										$appNow = $rNow->Apprate_edc." %";
									}
									elseif($req->Product == "SC")
									{
										$appNow = $rNow->Apprate_sc." %";
									}
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

						<tr class="tr_<?php echo $val->SPV_Code; ?>" style='font-size: 11px; display: none;'>
							<td class="text-right"><?php echo ++$nod; ?></td>
							<td class="text-right"> <b>Dsr</b> <?php echo $req->Name; ?></td>
							<td class="text-right"><?php echo round($numBulan); ?></td>
							<td class="text-right">-</td>
							<td><?php echo $incOld; ?></td>
							<td><?php echo $rtsOld; ?></td>
							<td><?php echo $noaOld; ?></td>
							<td><?php echo $decOld; ?></td>
							<td><?php echo round($appOld); ?></td>
							<td>
							<?php
								if($RatingOld == "Under Perform" OR $RatingOld == "Under Perform 1" OR $RatingOld == "Under Perform 2" OR $RatingOld == "Warning")
								{
									echo "<span class='label label-danger'>".$RatingOld."</span>";
								}
								elseif($RatingOld == "Acceptable")
								{
									echo "<span class='label label-success'>".$RatingOld."</span>";
								}
								elseif($RatingOld == "Standard" OR $RatingOld == "Standar" OR $RatingOld == "Zero Account")
								{
									echo "<span class='label label-warning'>".$RatingOld."</span>";
								}
								elseif($RatingOld == "Excellent" OR $RatingOld == "Excellent 1" OR $RatingOld == "Excellent 2")
								{
									echo "<span class='label label-primary'>".$RatingOld."</span>";
								}
							?>
							</td>
							<td><?php echo $incNow; ?></td>
							<td><?php echo $rtsNow; ?></td>
							<td><?php echo $noaNow; ?></td>
							<td><?php echo $decNow; ?></td>
							<td><?php echo round($appNow); ?></td>
							<td>
							<?php
								if($RatingNow == "Under Perform" OR $RatingNow == "Under Perform 1" OR $RatingNow == "Under Perform 2" OR $RatingNow == "Warning")
								{
									echo "<span class='label label-danger'>".$RatingNow."</span>";
								}
								elseif($RatingNow == "Acceptable")
								{
									echo "<span class='label label-success'>".$RatingNow."</span>";
								}
								elseif($RatingNow == "Standard" OR $RatingNow == "Standar" OR $RatingNow == "Zero Account")
								{
									echo "<span class='label label-warning'>".$RatingNow."</span>";
								}
								elseif($RatingNow == "Excellent" OR $RatingNow == "Excellent 1" OR $RatingNow == "Excellent 2")
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


						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>SPV</b> <a href="#" onclick="Detail2();">DUMMY SPV</a></td>
							<td>0</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>

						<?php
							$sqlDummy = $this->rsm_model->getDsrDummy($this->uri->segment(3));
							$nods = 0;
							foreach ($sqlDummy->result() as $rDum) {

								$sqlDumLs = $this->rsm_model->getAppsLsAllDsr($rDum->DSR_Code, $tanggal_old);
								if($sqlDumLs->num_rows() > 0)
								{
									$dtLs = $sqlDumLs->row();
									$inc_dt = $dtLs->Inc_cc + $dtLs->Inc_edc + $dtLs->Inc_sc;
									$rts_dt = $dtLs->Rts_cc + $dtLs->Rts_edc + $dtLs->Rts_sc;
									$noa_dt = $dtLs->Noa_cc + $dtLs->Noa_edc + $dtLs->Noa_sc;
									$dec_dt = $dtLs->Dec_cc + $dtLs->Dec_edc + $dtLs->Dec_sc;
									$rtg = $dtLs->ratings;
									if($rDum->Product == "CC")
									{
										$aps_dt = $dtLs->Apprate_cc." %";
									}
									elseif($rDum->Product == "EDC")
									{
										$aps_dt = $dtLs->Apprate_edc." %";
									}
									elseif($rDum->Product == "SC")
									{
										$aps_dt = $dtLs->Apprate_sc." %";
									}
								}
								else
								{
									$inc_dt = "-";
									$rts_dt = "-";
									$noa_dt = "-";
									$dec_dt = "-";
									$aps_dt = "-";
									$rtg = "-";
								}

								//
								/*$qrD = $this->rsm_model->getIncCurrentDsr($rDum->DSR_Code, $tanggal);
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
								}*/


								$sqlDums = $this->rsm_model->getAppsLsAllDsr($rDum->DSR_Code, $tanggal);
								if($sqlDums->num_rows() > 0)
								{
									$dums = $sqlDums->row();
									$inc_dums = $dums->Inc_cc + $dums->Inc_edc + $dums->Inc_sc;
									$rts_dums = $dums->Rts_cc + $dums->Rts_edc + $dums->Rts_sc;
									$noa_dums = $dums->Noa_cc + $dums->Noa_edc + $dums->Noa_sc;
									$dec_dums = $dums->Dec_cc + $dums->Dec_edc + $dums->Dec_sc;
									$rtg_dums = $dums->ratings;
									if($rDum->Product == "CC")
									{
										$aps_dums = $dums->Apprate_cc." %";
									}
									elseif($rDum->Product == "EDC")
									{
										$aps_dums = $dums->Apprate_edc." %";
									}
									elseif($rDum->Product == "SC")
									{
										$aps_dums = $dums->Apprate_sc." %";
									}
								}
								else
								{
									$inc_dums = "-";
									$rts_dums = "-";
									$noa_dums = "-";
									$dec_dums = "-";
									$aps_dums = "-";
									$rtg_dums = "-";
								}

								//datediff
								$tangs1 = $rDum->Efektif_Date;
								if($tangs1 == "0000-00-00")
								{
									$tangs1 = $rDum->Join_Date;
								}
								$tangs2 = date('Y-m-d');
								$timeStarts = strtotime("$tangs1");
								$timeEnds = strtotime("$tangs2");
								// Menambah bulan ini + semua bulan pada tahun sebelumnya
								$diffs = $timeEnds-$timeStarts;
								// menghitung selisih bulan
								$numBulans = $diffs / 86400 / 30;
						?>

						<tr class="tr_detail2" style='font-size: 11px; display: none;'>
							<td class="text-right"><?php echo ++$nods; ?></td>
							<td class="text-right"> <b>Dsr</b> <?php echo $rDum->Name; ?></td>
							<td class="text-right"><?php echo round($numBulans); ?></td>
							<td class="text-right">-</td>
							<td><?php echo $inc_dt; ?></td>
							<td><?php echo $rts_dt; ?></td>
							<td><?php echo $noa_dt; ?></td>
							<td><?php echo $dec_dt; ?></td>
							<td><?php echo $aps_dt; ?></td>
							<td>
							<?php
								if($rtg == "Under Perform" OR $rtg == "Under Perform 1" OR $rtg == "Under Perform 2" OR $rtg == "Warning")
								{
									echo "<span class='label label-danger'>".$rtg."</span>";
								}
								elseif($rtg == "Acceptable")
								{
									echo "<span class='label label-success'>".$rtg."</span>";
								}
								elseif($rtg == "Standard" OR $rtg == "Standar" OR $rtg == "Zero Account")
								{
									echo "<span class='label label-warning'>".$rtg."</span>";
								}
								elseif($rtg == "Excellent" OR $rtg == "Excellent 1" OR $rtg == "Excellent 2")
								{
									echo "<span class='label label-primary'>".$rtg."</span>";
								}
							?>
							</td>
							<td><?php echo $inc_dums; ?></td>
							<td><?php echo $rts_dums ?></td>
							<td><?php echo $noa_dums; ?></td>
							<td><?php echo $dec_dums; ?></td>
							<td><?php echo $aps_dums; ?></td>
							<td>
							<?php
								if($rtg_dums == "Under Perform" OR $rtg == "Under Perform 1" OR $rtg == "Under Perform 2" OR $rtg == "Warning")
								{
									echo "<span class='label label-danger'>".$rtg_dums."</span>";
								}
								elseif($rtg_dums == "Acceptable")
								{
									echo "<span class='label label-success'>".$rtg_dums."</span>";
								}
								elseif($rtg_dums == "Standard" OR $rtg_dums == "Standar" OR $rtg_dums == "Zero Account")
								{
									echo "<span class='label label-warning'>".$rtg_dums."</span>";
								}
								elseif($rtg_dums == "Excellent" OR $rtg_dums == "Excellent 1" OR $rtg_dums == "Excellent 2")
								{
									echo "<span class='label label-primary'>".$rtg_dums."</span>";
								}
							?>
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
<script src="<?php echo base_url(); ?>public/mytemplate/plugins/jQuery/jquery.min.js"></script>
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