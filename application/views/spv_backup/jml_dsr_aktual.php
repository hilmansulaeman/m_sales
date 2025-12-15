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
		<h3 class="box-title">Data Summary Applications DSR</h3>			  
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<div style="height:500px;overflow-y:scroll;">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th rowspan="2">NO</th>
							<th rowspan="2">DSR NAME</th>
							<th rowspan="2">MOB</th>
							<th colspan="6" class="text-center"><?php echo date('M-Y', strtotime('-1 month')) ?></th>
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
						$lst_date = date('Y-m', strtotime('-1 month'));
						$tanggal = date('Y-m');
						foreach ($query->result() as $val) {
							//current
							//$sqlInc = $this->spv_model->getAllIncomingCurrent($val->DSR_Code, $tanggal);
							//$rowInc = $sqlInc->row();
							//$incCurrent = $rowInc->inc_cc + $rowInc->inc_edc + $rowInc->inc_sc;
							//$rtsCurrent = $rowInc->rts_cc + $rowInc->rts_edc + $rowInc->rts_sc;

							$sqlNoa = $this->spv_model->getAllNoaCurrent($val->DSR_Code, $tanggal);
							if($sqlNoa->num_rows() > 0)
							{
								$dtNoa = $sqlNoa->row();
								$incCc = $dtNoa->Inc_cc;
								$incEdc = $dtNoa->Inc_edc;
								$incSc = $dtNoa->Inc_sc;
								$totInc = $incCc + $incEdc + $incSc;
								
								$Rts_cc = $dtNoa->Rts_cc;
								$Rts_edc = $dtNoa->Rts_edc;
								$Rts_sc = $dtNoa->Rts_sc;
								$totRts = $Rts_cc + $Rts_edc + $Rts_sc;
								
								$noaCC = $dtNoa->Noa_cc;
								$noaEDC = $dtNoa->Noa_edc;
								$noaSC = $dtNoa->Noa_sc;
								$TotNoa = $noaCC + $noaEDC + $noaSC;

								$decCC = $dtNoa->Dec_cc;
								$decEDC = $dtNoa->Dec_edc;
								$decSC = $dtNoa->Dec_sc;
								$TotDec = $decCC + $decEDC + $decSC;
								$AppCC = $dtNoa->Apprate_cc;
								$AppEDC = $dtNoa->Apprate_edc;
								$AppSC = $dtNoa->Apprate_sc;
								$ratingDsr = $dtNoa->ratings;
							}
							else
							{
								$incCc = "-";
								$Rts_cc = "-";
								$incEdc = "-";
								$Rts_edc = "-";
								$incSc = "-";
								$Rts_sc = "-";
								$totInc = "-";
								$totRts = "-";
								$noaCC = "-";
								$noaEDC = "-";
								$noaSC = "-";
								$TotNoa = "-";

								$decCC = "-";
								$decEDC = "-";
								$decSC = "-";
								$TotDec = "-";
								$AppCC = "-";
								$AppEDC = "-";
								$AppSC = "-";
								$ratingDsr = "-";
							}

							//last month
							$sqlLsApps = $this->spv_model->getAllNoaCurrent($val->DSR_Code, $lst_date);
							if($sqlLsApps->num_rows() > 0)
							{
								$rwLs = $sqlLsApps->row();
								$lsIncCc = $rwLs->Inc_cc;
								$lsIncEdc = $rwLs->Inc_edc;
								$lsIncSc = $rwLs->Inc_sc;
								$totalLsInc = $lsIncCc + $lsIncEdc + $lsIncSc;

								$lsRtsCc = $rwLs->Rts_cc;
								$lsRtsEdc = $rwLs->Rts_edc;
								$lsRtsSc = $rwLs->Rts_sc;
								$totalLsRts = $lsRtsCc + $lsRtsEdc + $lsRtsSc;

								$lsNoaCc = $rwLs->Noa_cc;
								$lsNoaEdc = $rwLs->Noa_edc;
								$lsNoaSc = $rwLs->Noa_sc;
								$TotalLsNoa = $lsNoaCc  + $lsNoaEdc + $lsNoaSc;

								$lsDecCc = $rwLs->Dec_cc;
								$lsDecEdc = $rwLs->Dec_edc;
								$lsDecSc = $rwLs->Dec_sc;
								$totalLsDec = $lsDecCc + $lsDecEdc + $lsDecSc;

								$lsAppRateCc = $rwLs->Apprate_cc;
								$lsAppRateEdc = $rwLs->Apprate_edc;
								$lsAppRateSc = $rwLs->Apprate_sc;
								$rating_ls = $rwLs->ratings;
							}
							else
							{
								$lsIncCc = "-";
								$lsIncEdc = "-";
								$lsIncSc = "-";
								$totalLsInc = "-";

								$lsRtsCc = "-";
								$lsRtsEdc = "-";
								$lsRtsSc = "-";
								$totalLsRts = "-";
								
								$lsNoaCc = "-";
								$lsNoaEdc = "-";
								$lsNoaSc = "-";
								$TotalLsNoa = "-";

								$lsDecCc = "-";
								$lsDecEdc = "-";
								$lsDecSc = "-";
								$totalLsDec = "-";

								$lsAppRateCc = "-";
								$lsAppRateEdc = "-";
								$lsAppRateSc = "-";
								$rating_ls = "-";
							}
							//end last month

							//datediff
							$date1 = $val->Efektif_date;
							if($date1 == "0000-00-00")
							{
								$date11 = $val->Join_Date;
							}
							else
							{
								$date11 = $date1;
							}
							$date2 = date('Y-m-d');
							$timeStart = strtotime("$date11");
							$timeEnd = strtotime("$date2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diff = $timeEnd-$timeStart;
							// menghitung selisih bulan
							$numBulan = $diff / 86400 / 30;
							
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><a href="javascript:void(0)" onclick="Detail('<?php echo $val->DSR_Code; ?>')"> <?php echo $val->Name; ?></a>
							</td>
							<td><?php echo round($numBulan); ?></td>
							<td><?php echo $totalLsInc; ?></td>
							<td><?php echo $totalLsRts; ?></td>
							<td><?php echo $TotalLsNoa; ?></td>
							<td><?php echo $totalLsDec; ?></td>
							<td></td>
							<td>
								<?php
									if($rating_ls == "Under Perform 1" OR $rating_ls == "Under Perform 2"  OR $rating_ls == "Under Perform" OR $rating_ls == "Zero Account")
									{
										echo "<span class='label label-danger'>".$rating_ls."</span>";
									}elseif($rating_ls == "Acceptable")
									{
										echo "<span class='label label-success'>".$rating_ls."</span>";
									}elseif($rating_ls == "Standard" OR $rating_ls == "Standar" OR $rating_ls == "Warning")
									{
										echo "<span class='label label-warning'>".$rating_ls."</span>";

									}elseif($rating_ls == "Excellent 1" OR $rating_ls == "Excellent 2" OR $rating_ls == "Excellent 3" OR $rating_ls == "Excellent 4")
									{
										echo "<span class='label label-primary'>".$rating_ls."</span>";
									}
									else
									{
										echo $rating_ls;
									}
								?>
							</td>
							<td><?php echo $totInc; ?></td>
							<td><?php echo $totRts; ?></td>
							<td><?php echo $TotNoa; ?></td>
							<td><?php echo $TotDec; ?></td>
							<td>-</td>
							<td>
								<?php
									if($ratingDsr == "Under Perform 1" OR $ratingDsr == "Under Perform 2"  OR $ratingDsr == "Under Perform" OR $ratingDsr == "Zero Account")
									{
										echo "<span class='label label-danger'>".$ratingDsr."</span>";
									}elseif($ratingDsr == "Acceptable")
									{
										echo "<span class='label label-success'>".$ratingDsr."</span>";
									}elseif($ratingDsr == "Standard" OR $ratingDsr == "Standar" OR $ratingDsr == "Warning")
									{
										echo "<span class='label label-warning'>".$ratingDsr."</span>";

									}elseif($ratingDsr == "Excellent 1" OR $ratingDsr == "Excellent 2" OR $ratingDsr == "Excellent 3" OR $ratingDsr == "Excellent 4")
									{
										echo "<span class='label label-primary'>".$ratingDsr."</span>";
									}
									else
									{
										echo $ratingDsr;
									}
								?>
							</td>
						</tr>
						<tr style="display: none; font-size: 11px;" id="tr_cc_<?php echo $val->DSR_Code; ?>">
							<td><i class="fa fa-long-arrow-right"></i></td>
							<td class="text-right">CC</td>
							<td></td>
							<td><?php echo $lsIncCc; ?></td>
							<td><?php echo $lsRtsCc; ?></td>
							<td><?php echo $lsNoaCc; ?></td>
							<td><?php echo $lsDecCc; ?></td>
							<td><?php echo round($lsAppRateCc); ?> %</td>
							<td>-</td>
							<td><?php echo $incCc; ?></td>
							<td><?php echo $Rts_cc; ?></td>
							<td><?php echo $noaCC; ?></td>
							<td><?php echo $decCC; ?></td>
							<td><?php echo round($AppCC); ?> %</td>
							<td>-</td>
						</tr>
						<tr style="display: none; font-size: 11px;" id="tr_edc_<?php echo $val->DSR_Code; ?>">
							<td><i class="fa fa-long-arrow-right"></i></td>
							<td class="text-right">EDC</td>
							<td></td>
							<td><?php echo $lsIncEdc; ?></td>
							<td><?php echo $lsRtsEdc; ?></td>
							<td><?php echo $lsNoaEdc; ?></td>
							<td><?php echo $lsDecEdc; ?></td>
							<td><?php echo round($lsAppRateEdc); ?> %</td>
							<td>-</td>
							<td><?php echo $incEdc; ?></td>
							<td><?php echo $Rts_edc; ?></td>
							<td><?php echo $noaEDC; ?></td>
							<td><?php echo $decEDC; ?></td>
							<td><?php echo round($AppEDC); ?> %</td>
							<td>-</td>
						</tr>
						<tr style="display: none; font-size: 11px;" id="tr_sc_<?php echo $val->DSR_Code; ?>">
							<td><i class="fa fa-long-arrow-right"></i></td>
							<td class="text-right">SC</td>
							<td></td>
							<td><?php echo $lsIncSc; ?></td>
							<td><?php echo $lsRtsSc; ?></td>
							<td><?php echo $lsNoaSc; ?></td>
							<td><?php echo $lsDecSc; ?></td>
							<td><?php echo round($lsAppRateSc); ?> %</td>
							<td>-</td>
							<td><?php echo $incSc; ?></td>
							<td><?php echo $Rts_sc; ?></td>
							<td><?php echo $noaSC; ?></td>
							<td><?php echo $decSC; ?></td>
							<td><?php echo round($AppSC); ?> %</td>
							<td>-</td>
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
	if(document.getElementById('tr_cc_'+ dsr_code).style.display =='none')
	{
		document.getElementById('tr_cc_'+ dsr_code).style.display = '';
		document.getElementById('tr_edc_'+ dsr_code).style.display = '';
		document.getElementById('tr_sc_'+ dsr_code).style.display = '';
	}
	else if(document.getElementById('tr_cc_'+ dsr_code).style.display =='')
	{
		document.getElementById('tr_cc_'+ dsr_code).style.display = 'none';
		document.getElementById('tr_edc_'+ dsr_code).style.display = 'none';
		document.getElementById('tr_sc_'+ dsr_code).style.display = 'none';
	}
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