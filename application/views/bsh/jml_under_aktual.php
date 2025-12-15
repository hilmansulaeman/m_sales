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
		<h3 class="box-title">DATA SUMMARY UNDER PERFORM</h3>			  
	</div>
	<div class="panel-body">
		<div class="table-responsive">	
			<div style="height:500px;overflow-y:scroll;">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th rowspan="2">NO</th>
							<th rowspan="2">NAME</th>
							<th rowspan="2">MOB</th>
							<th rowspan="2">SPV</th>
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
						foreach($query->result() as $row)
						{
							//datediff
							$date1 = $row->Join_Date;
							$date2 = date('Y-m-d');
							$timeStart = strtotime("$date1");
							$timeEnd = strtotime("$date2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diff = $timeEnd-$timeStart;
							// menghitung selisih bulan
							$numBulan = $diff / 86400 / 30;
							
							$inc_now = $row->Inc_cc + $row->Inc_edc + $row->Inc_sc;
							$rts_now = $row->Rts_cc + $row->Rts_edc + $row->Rts_sc;
							$noa_now = $row->Noa_cc + $row->Noa_edc + $row->Noa_sc;
							$dec_now = $row->Dec_cc + $row->Dec_edc + $row->Dec_sc;
							$rating_now = $row->rating;
							if($row->Product == "CC")
							{
								$apprate_now = $row->Apprate_cc;
							}elseif($row->Product == "EDC")
							{
								$apprate_now = $row->Apprate_edc;
							}elseif($row->Product == "SC")
							{
								$apprate_now = $row->Apprate_sc;
							}
							
							//realtime
							/*$sqlInc = $this->bsh_model->CurrentApps($row->DSR_Code, $tanggal);
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
							
							$sqlApss = $this->bsh_model->getApps($row->DSR_Code, $tanggal);
							if($sqlApss->num_rows() > 0)
							{
								$rOld = $sqlApss->row();
								$inc_old = $rOld->Inc_cc + $rOld->Inc_edc + $rOld->Inc_sc;
								$rts_old = $rOld->Inc_cc + $rOld->Inc_edc + $rOld->Inc_sc;
								$noa_old = $rOld->Noa_cc + $rOld->Noa_edc + $rOld->Noa_sc;
								$dec_old = $rOld->Dec_cc + $rOld->Dec_edc + $rOld->Dec_sc;
								$rating_old = $rOld->rating;
								if($row->Product == "CC")
								{
									$apprate_old = $rOld->Apprate_cc;
								}elseif($row->Product == "EDC")
								{
									$apprate_old = $rOld->Apprate_edc;
								}elseif($row->Product == "SC")
								{
									$apprate_old = $rOld->Apprate_sc;
								}
							}
							else
							{
								$inc_old = "-";
								$rts_old = "-";
								$noa_old = "-";
								$dec_old = "-";
								$apprate_old = "-";
								$rating_old = "-";
							}
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>RSM</b> <a href="#" onclick="Detail('<?php echo $row->DSR_Code; ?>')"><?php echo $row->Name; ?></a></td>
							<td><?php echo round($numBulan); ?></td>
							<td><?php echo $row->totalnya; ?></td>
							<td><?php echo $inc_now; ?></td>
							<td><?php echo $rts_now; ?></td>
							<td><?php echo $noa_now; ?></td>
							<td><?php echo $dec_now; ?></td>
							<td><?php echo $apprate_now; ?></td>
							<td>
								<?php
									if($rating_now == "Under Perform 1" OR $rating_now == "Under Perform 2" OR $rating_now == "Under Perform" OR $rating_now == "Zero Account")
									{
										echo "<span class='label label-danger'>".$rating_now."</span>";
									}elseif($rating_now == "Acceptable" OR $rating_now == "Acceptable 1" OR $rating_now == "Acceptable 2")
									{
										echo "<span class='label label-success'>".$rating_now."</span>";
									}elseif($rating_now == "Standard" OR $rating_now == "Standar")
									{
										echo "<span class='label label-warning'>".$rating_now."</span>";

									}elseif($rating_now == "Excellent" OR $rating_now == "Excellent 1" OR $rating_now == "Excellent 2" OR $rating_now == "Excellent 3" OR $rating_now == "Excellent 4")
									{
										echo "<span class='label label-primary'>".$rating_now."</span>";
									}
								?>
							</td>
							<td><?php echo $inc_old; ?></td>
							<td><?php echo $rts_old; ?></td>
							<td><?php echo $noa_old; ?></td>
							<td><?php echo $dec_old; ?></td>
							<td><?php echo $apprate_old; ?></td>
							<td>
								<?php
									if($rating_old == "Under Perform 1" OR $rating_old == "Under Perform 2"  OR $rating_old == "Under Perform" OR $rating_old == "Zero Account")
									{
										echo "<span class='label label-danger'>".$rating_old."</span>";
									}elseif($rating_old == "Acceptable" OR $rating_old == "Acceptable 1" OR $rating_old == "Acceptable 2")
									{
										echo "<span class='label label-success'>".$rating_old."</span>";
									}elseif($rating_old == "Standard" OR $rating_old == "Standar")
									{
										echo "<span class='label label-warning'>".$rating_old."</span>";

									}elseif($rating_old == "Excellent" OR $rating_old == "Excellent 1" OR $rating_old == "Excellent 2" OR $rating_old == "Excellent 3" OR $rating_old == "Excellent 4")
									{
										echo "<span class='label label-primary'>".$rating_old."</span>";
									}
								?>
							</td>
						</tr>
						<?php
								$number = 0;
								$sqlDown = $this->bsh_model->getAsmByRsm($row->DSR_Code);
								foreach ($sqlDown->result() as $dt) {
									//datediff
									$tgl1 = $dt->Join_Date;
									$tgl2 = date('Y-m-d');
									$timeStart1 = strtotime("$tgl1");
									$timeEnd1 = strtotime("$tgl2");
									// Menambah bulan ini + semua bulan pada tahun sebelumnya
									$diff1 = $timeEnd1-$timeStart1;
									// menghitung selisih bulan
									$numBulan1 = $diff1 / 86400 / 30;

									$qryAps = $this->bsh_model->getAppsAsm($dt->ASM_Code, $tanggal_old);
									if($qryAps->num_rows() > 0)
									{
										$arrOld = $qryAps->row();
										$old_inc = $arrOld->Inc_cc + $arrOld->Inc_edc + $arrOld->Inc_sc;
										$old_rts = $arrOld->Rts_cc + $arrOld->Rts_edc + $arrOld->Rts_sc;
										$old_noa = $arrOld->Noa_cc + $arrOld->Noa_edc + $arrOld->Noa_sc;
										$old_dec = $arrOld->Dec_cc + $arrOld->Dec_edc + $arrOld->Dec_sc;
										$ratings = $arrOld->rating;
										if($dt->Product == "CC")
										{
											$appr = $arrOld->Apprate_cc;
										}elseif($dt->Product == "EDC")
										{
											$appr = $arrOld->Apprate_edc;
										}elseif($dt->Product == "SC")
										{
											$appr = $arrOld->Apprate_sc;
										}
									}
									else
									{
										$old_inc = "-";
										$old_rts = "-";
										$old_noa = "-";
										$old_dec = "-";
										$appr = "-";
										$ratings = "-";
									}

									//realtime
									/*$qryInc = $this->bsh_model->CurrentAppsAsm($dt->ASM_Code, $tanggal);
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
									}*/

									$qryApsNow = $this->bsh_model->getAppsAsm($dt->ASM_Code, $tanggal);
									if($qryApsNow->num_rows() > 0)
									{
										$arSkr = $qryApsNow->row();
										$inc_s = $arSkr->Inc_cc + $arSkr->Inc_edc + $arSkr->Inc_sc;
										$rts_s = $arSkr->Rts_cc + $arSkr->Rts_edc + $arSkr->Rts_sc;
										$noa_s = $arSkr->Noa_cc + $arSkr->Noa_edc + $arSkr->Noa_sc;
										$dec_s = $arSkr->Dec_cc + $arSkr->Dec_edc + $arSkr->Dec_sc;
										$ratings_s = $arSkr->rating;
										if($dt->Product == "CC")
										{
											$appr_s = $arSkr->Apprate_cc;
										}elseif($dt->Product == "EDC")
										{
											$appr_s = $arSkr->Apprate_edc;
										}elseif($dt->Product == "SC")
										{
											$appr_s = $arSkr->Apprate_sc;
										}
									}
									else
									{
										$inc_s = "-";
										$rts_s = "-";
										$noa_s = "-";
										$dec_s = "-";
										$appr_s = "-";
										$ratings_s = "-";
									}
							?>
								<tr class="tr_<?php echo $row->DSR_Code; ?>" style="font-size: 11px; display: none;">
								<td class="text-right"><?php echo ++$number; ?></td>
								<td class="text-right"><b>ASM</b> <?php echo $dt->ASM_Name; ?></td>
								<td><?php echo round($numBulan1); ?></td>
								<td>-</td>
								<td><?php echo $old_inc ?></td>
								<td><?php echo $old_rts; ?></td>
								<td><?php echo $old_noa; ?></td>
								<td><?php echo $old_dec; ?></td>
								<td><?php echo $appr; ?></td>
								<td>
									<?php
										if($ratings == "Under Perform 1" OR $ratings == "Under Perform 2" OR $ratings == "Under Perform" OR $ratings == "Zero Account")
										{
											echo "<span class='label label-danger'>".$ratings."</span>";
										}elseif($ratings == "Acceptable" OR $ratings == "Acceptable 1" OR $ratings == "Acceptable 2")
										{
											echo "<span class='label label-success'>".$rating."</span>";
										}elseif($ratings == "Standard" OR $ratings == "Standar")
										{
											echo "<span class='label label-warning'>".$ratings."</span>";

										}elseif($ratings == "Excellent" OR $ratings == "Excellent 1" OR $ratings == "Excellent 2" OR $ratings == "Excellent 3" OR $ratings == "Excellent 4")
										{
											echo "<span class='label label-primary'>".$ratings."</span>";
										}
									?>
								</td>
								<td><?php echo $inc_s; ?></td>
								<td><?php echo $rts_s; ?></td>
								<td><?php echo $noa_s; ?></td>
								<td><?php echo $dec_s; ?></td>
								<td><?php echo $appr_s; ?></td>
								<td>
									<?php
										if($ratings_s == "Under Perform 1" OR $ratings_s == "Under Perform 2"  OR $ratings_s == "Under Perform" OR $ratings_s == "Zero Account")
										{
											echo "<span class='label label-danger'>".$ratings_s."</span>";
										}elseif($ratings_s == "Acceptable" OR $ratings_s == "Acceptable 1" OR $ratings_s == "Acceptable 2")
										{
											echo "<span class='label label-success'>".$rating."</span>";
										}elseif($ratings_s == "Standard" OR $ratings_s == "Standar")
										{
											echo "<span class='label label-warning'>".$ratings_s."</span>";

										}elseif($ratings_s == "Excellent" OR $ratings_s == "Excellent 1" OR $ratings_s == "Excellent 2" OR $ratings_s == "Excellent 3" OR $ratings_s == "Excellent 4")
										{
											echo "<span class='label label-primary'>".$ratings_s."</span>";
										}
									?>
								</td>
							</tr>
							<?php
								}
							?>
							
							<?php

								$queryDummy = $this->bsh_model->DummyAsm($row->DSR_Code, $tanggal_old);
								if($queryDummy->num_rows() > 0)
								{
									$rDm = $queryDummy->row();
									$dul_inc = $rDm->inc;
									$dul_rts = $rDm->rts;
									$dul_noa = $rDm->noa;
									$dul_dec = $rDm->decl;
								}
								else
								{
									$dul_inc = "-";
									$dul_rts = "-";
									$dul_noa = "-";
									$dul_dec = "-";
								}

								//realtime
								/*$sqlRealDum = $this->bsh_model->DummyAsmReal($row->DSR_Code, $tanggal);
								if($sqlRealDum->num_rows() > 0)
								{
									$rrD = $sqlRealDum->row();
									$incReal = $rrD->inc_cc + $rrD->inc_edc + $rrD->inc_sc;
									$rtsReal = $rrD->inc_cc + $rrD->inc_edc + $rrD->inc_sc;
								}
								else
								{
									$incReal = "-";
									$rtsReal = "-";
								}*/

								$queryDummy2 = $this->bsh_model->DummyAsm($row->DSR_Code, $tanggal);
								if($queryDummy2->num_rows() > 0)
								{
									$rDm2 = $queryDummy2->row();
									$inc2 = $rDm2->inc;
									$rts2 = $rDm2->rts;
									$noa2 = $rDm2->noa;
									$dec2 = $rDm2->decl;
								}
								else
								{
									$inc2 = "-";
									$rts2 = "-";
									$noa2 = "-";
									$dec2 = "-";
								}

							?>
							<tr class="tr2_<?php echo $row->DSR_Code; ?>" style="font-size: 11px; display: none;">
								<td class="text-right"><?php echo ++$number; ?></td>
								<td class="text-right"><b>ASM</b> DUMMY ASM</td>
								<td></td>
								<td></td>
								<td><?php echo $dul_inc; ?></td>
								<td><?php echo $dul_rts; ?></td>
								<td><?php echo $dul_noa; ?></td>
								<td><?php echo $dul_dec; ?></td>
								<td>-</td>
								<td>-</td>
								<td><?php echo $inc2; ?></td>
								<td><?php echo $rts2; ?></td>
								<td><?php echo $noa2; ?></td>
								<td><?php echo $dec2; ?></td>
								<td></td>
								<td></td>
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
    $('.tr2_'+dsr_code).toggle()
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