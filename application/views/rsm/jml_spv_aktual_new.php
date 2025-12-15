<?php 
function RatingColour($rating) 
{
	$under_perform = array('Under Perform','Under Perform 1','Under Perform 2','Warning','Zero Account');
	$acceptable = array('Acceptable');
	$standard = array('Standar','Standard');
	$excellent = array('Excellent','Excellent 1','Excellent 2','Excellent 3','Excellent 4','Super Excellent');
	if(in_array($rating, $under_perform)){
		echo "<span class='label label-danger'>".$rating."</span>";
	}
	elseif(in_array($rating, $acceptable)){
		echo "<span class='label label-warning'>".$rating."</span>";
	}
	elseif(in_array($rating, $standard)){
		echo "<span class='label label-success'>".$rating."</span>";
	}
	elseif(in_array($rating, $excellent)){
		echo "<span class='label label-primary'>".$rating."</span>";
	}
} 

function mob($join_date, $efektif_date)
{
	$dateNow = date('Y-m-d');
	
	if($efektif_date == "0000-00-00")
	{
		$mob = $join_date;
	}
	else
	{
		$mob = $efektif_date;
	}
	
	$var_time = strtotime("$mob");
	$var_time2 = strtotime("$dateNow");
	
	$diff = $var_time2-$var_time;
	$curMob = $diff / 86400 / 30;
	return round($curMob);
	
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
								/*$row->Inc_cc = 0;
								$row->Inc_edc = 0;
								$row->Inc_sc = 0;
								$row->Rts_cc = 0;
								$row->Rts_edc = 0;
								$row->Rts_sc = 0;
								$row->Noa_cc = 0;
								$row->Noa_edc = 0;
								$row->Noa_sc = 0;
								$row->Dec_cc = 0;
								$row->Dec_edc = 0;
								$row->Dec_sc = 0;
								$row->Apprate_cc = 0;
								$row->Apprate_edc = 0;
								$row->Apprate_sc = 0;*/
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
						<tr style="display:none;" class="tr_<?php echo $val->SPV_Code; ?>">
							<td colspan="16">
								<table class="table table-bordered" style="font-size: 11px;">
									<tbody>
										<tr>
											<td colspan="2">
												<strong>PRODUCT</strong>
											</td>
											<td align="center" colspan="5">
												<strong>
													<?php echo date('M-Y', strtotime('-1 month')); ?>
												</strong>
											</td>
											<td align="center" colspan="5">
												<strong>
													<?php echo date('M-Y'); ?>
												</strong>
											</td>
										</tr>
										<tr class="bg-blue">
											<td style="width: 5px;"><strong>No</strong></td>
											<td style="width: 200px;"><strong>Product</strong></td>
											<td style="width: 50px;">INC</td>
											<td style="width: 50px;">RTS</td>
											<td style="width: 50px;">NOA</td>
											<td style="width: 50px;">DEC</td>
											<td style="width: 50px;">A / R</td>
											<td style="width: 50px;">INC</td>
											<td style="width: 50px;">RTS</td>
											<td style="width: 50px;">NOA</td>
											<td style="width: 50px;">DEC</td>
											<td style="width: 50px;">A / R</td>
										</tr>
										<tr>
											<td>1.</td>
											<td>CC</td>
											<td><?php echo $rows->Inc_cc; ?></td>
											<td><?php echo $rows->Rts_cc; ?></td>
											<td><?php echo $rows->Noa_cc; ?></td>
											<td><?php echo $rows->Dec_cc; ?></td>
											<td><?php echo $rows->Apprate_cc; ?> %</td>
											<td><?php echo $row->Inc_cc; ?></td>
											<td><?php echo $row->Rts_cc; ?></td>
											<td><?php echo $row->Noa_cc; ?></td>
											<td><?php echo $row->Dec_cc; ?></td>
											<td><?php echo $row->Apprate_cc; ?> %</td>
										</tr>
										<tr>
											<td>2.</td>
											<td>EDC</td>
											<td><?php echo $rows->Inc_edc; ?></td>
											<td><?php echo $rows->Rts_edc; ?></td>
											<td><?php echo $rows->Noa_edc; ?></td>
											<td><?php echo $rows->Dec_edc; ?></td>
											<td><?php echo $rows->Apprate_edc; ?> %</td>
											<td><?php echo $row->Inc_edc; ?></td>
											<td><?php echo $row->Rts_edc; ?></td>
											<td><?php echo $row->Noa_edc; ?></td>
											<td><?php echo $row->Dec_edc; ?></td>
											<td><?php echo $row->Apprate_edc; ?></td>
										</tr>
										<tr>
											<td>3.</td>
											<td>SC</td>
											<td><?php echo $rows->Inc_sc; ?></td>
											<td><?php echo $rows->Rts_sc; ?></td>
											<td><?php echo $rows->Noa_sc; ?></td>
											<td><?php echo $rows->Dec_sc; ?></td>
											<td><?php echo $rows->Apprate_sc; ?> %</td>
											<td><?php echo $row->Inc_sc; ?></td>
											<td><?php echo $row->Rts_sc; ?></td>
											<td><?php echo $row->Noa_sc; ?></td>
											<td><?php echo $row->Dec_sc; ?></td>
											<td><?php echo $row->Apprate_sc; ?></td>
										</tr>
										<br>
									</tbody>
								</table>
								<br>
								<table class="table table-bordered" style="font-size: 11px;">
									<tbody>
										<tr>
											<td colspan="3">
												<strong>DSR NAME</strong>
											</td>
											<td align="center" colspan="6">
												<strong>
													<?php echo date('M-Y', strtotime('-1 month')); ?>
												</strong>
											</td>
											<td align="center" colspan="6">
												<strong>
													<?php echo date('M-Y'); ?>
												</strong>
											</td>
										</tr>
										
										<tr class="bg-green">
											<td style="width: 5px;">No</td>
											<td style="width: 200px;">Name</td>
											<td style="width: 10px;">MOB</td>
											<td style="width: 50px;">INC</td>
											<td style="width: 50px;">RTS</td>
											<td style="width: 50px;">NOA</td>
											<td style="width: 50px;">DEC</td>
											<td style="width: 50px;">A / R</td>
											<td style="width: 50px;">Rating</td>
											<td style="width: 50px;">INC</td>
											<td style="width: 50px;">RTS</td>
											<td style="width: 50px;">NOA</td>
											<td style="width: 50px;">DEC</td>
											<td style="width: 50px;">A / R</td>
											<td style="width: 50px;">Rating</td>
										</tr>
										<?php
											$no_ds = 0;
											$sql_dsr = $this->rsm_model->getAllDsrBySpv($val->SPV_Code);
											foreach($sql_dsr->result() as $dtDsr)
											{
												//datediff
												$date1 = $dtDsr->Efektif_Date;
												if($date1 == "0000-00-00")
												{
													$datenya = $dtDsr->Join_Date;
												}
												else
												{
													$datenya = $date1;
												}
												$date2 = date('Y-m-d');
												$timeStart = strtotime("$datenya");
												$timeEnd = strtotime("$date2");
												// Menambah bulan ini + semua bulan pada tahun sebelumnya
												$diff = $timeEnd-$timeStart;
												// menghitung selisih bulan
												$numBulan = $diff / 86400 / 30;
												
												$sql_angka_ls = $this->rsm_model->getDataDsr($dtDsr->DSR_Code, $tanggal_old);
												if($sql_angka_ls->num_rows() > 0)
												{
													$dsr_ls = $sql_angka_ls->row();
													$ds_inc_ls = $dsr_ls->Inc_cc + $dsr_ls->Inc_edc + $dsr_ls->Inc_sc;
													$ds_rts_ls = $dsr_ls->Rts_cc + $dsr_ls->Rts_edc + $dsr_ls->Rts_sc;
													$ds_noa_ls = $dsr_ls->Noa_cc + $dsr_ls->Noa_edc + $dsr_ls->Noa_sc;
													$ds_dec_ls = $dsr_ls->Dec_cc + $dsr_ls->Dec_edc + $dsr_ls->Dec_sc;
													if($dtDsr->Product == "CC")
													{
														$ds_apprate_ls = $dsr_ls->Apprate_cc;
													}elseif($dtDsr->Product == "EDC")
													{
														$ds_apprate_ls = $dsr_ls->Apprate_edc;
													}elseif($dtDsr->Product == "SC")
													{
														$ds_apprate_ls = $dsr_ls->Apprate_sc;
													}
													$ds_rating_ls = $dsr_ls->ratings;
												}
												else
												{
													$ds_inc_ls = 0;
													$ds_rts_ls = 0;
													$ds_noa_ls = 0;
													$ds_dec_ls = 0;
													$ds_apprate_ls = 0;
													$ds_rating_ls = "-";
												}
												
												$sql_angka = $this->rsm_model->getDataDsr($dtDsr->DSR_Code, $tanggal);
												if($sql_angka->num_rows() > 0)
												{
													$dsr = $sql_angka->row();
													$ds_inc = $dsr->Inc_cc + $dsr->Inc_edc + $dsr->Inc_sc;
													$ds_rts = $dsr->Rts_cc + $dsr->Rts_edc + $dsr->Rts_sc;
													$ds_noa = $dsr->Noa_cc + $dsr->Noa_edc + $dsr->Noa_sc;
													$ds_dec = $dsr->Dec_cc + $dsr->Dec_edc + $dsr->Dec_sc;
													if($dtDsr->Product == "CC")
													{
														$ds_apprate = $dsr->Apprate_cc;
													}elseif($dtDsr->Product == "EDC")
													{
														$ds_apprate= $dsr->Apprate_edc;
													}elseif($dtDsr->Product == "SC")
													{
														$ds_apprate = $dsr->Apprate_sc;
													}
													$ds_rating = $dsr->ratings;
												}
												else
												{
													$ds_inc = 0;
													$ds_rts = 0;
													$ds_noa = 0;
													$ds_dec = 0;
													$ds_apprate = 0;
													$ds_rating = "-";
												}
										?>
											<tr>
												<td><?php echo ++$no_ds; ?></td>
												<td><?php echo $dtDsr->Name; ?></td>
												<td><?php echo round($numBulan); ?></td>
												<td><?php echo $ds_inc_ls; ?></td>
												<td><?php echo $ds_rts_ls; ?></td>
												<td><?php echo $ds_noa_ls; ?></td>
												<td><?php echo $ds_dec_ls; ?></td>
												<td><?php echo round($ds_apprate_ls); ?> %</td>
												<td><?php RatingColour($ds_rating_ls); ?></td>
												<td><?php echo $ds_inc; ?></td>
												<td><?php echo $ds_rts; ?></td>
												<td><?php echo $ds_noa; ?></td>
												<td><?php echo $ds_dec; ?></td>
												<td><?php echo round($ds_apprate); ?> %</td>
												<td><?php RatingColour($ds_rating); ?></td>
											</tr>
										<?php
											}
										?>
									</tbody>
								</table>
							</td>
						</tr>
					<?php
						}
					?>
					
					<?php
						$dummy_sql = $this->rsm_model->getDummyDataDsr($this->uri->segment(3), $tanggal_old);
						if($dummy_sql->num_rows() > 0)
						{
							$rwDumLs = $dummy_sql->row();
							$IncDumLs = $rwDumLs->inc;
							$RtsDumLs = $rwDumLs->rts;
							$NoaDumLs = $rwDumLs->noa;
							$DecDumLs = $rwDumLs->decl;
							if($NoaDumLs > 0 AND $DecDumLs > 0)
							{
								$appr_dm_ls = $NoaDumLs / ($NoaDumLs + $DecDumLs) * 100;
							}
							else
							{
								$appr_dm_ls = 0;
							}
							//Rating
							if($NoaDumLs==0){
								$rating_dummy_ls = "Zero Account";
							}
							else if($NoaDumLs>=1 && $NoaDumLs<50){
								$rating_dummy_ls = "Under Perform";
							}
							else if($NoaDumLs>=50 && $NoaDumLs<80){
								$rating_dummy_ls = "Acceptable";
							}
							else if($NoaDumLs>=80 && $NoaDumLs<120){
								$rating_dummy_ls = "Standard";
							}
							else if($NoaDumLs>=120){
								$rating_dummy_ls = "Excellent";
							}
						}
						else
						{
							$IncDumLs = 0;
							$RtsDumLs = 0;
							$NoaDumLs = 0;
							$DecDumLs = 0;
							$appr_dm_ls = 0;
							$rating_dummy_ls = "-";
						}
						
						$dummy_sql_now = $this->rsm_model->getDummyDataDsr($this->uri->segment(3), $tanggal);
						if($dummy_sql_now->num_rows() > 0)
						{
							$rwDum = $dummy_sql_now->row();
							$IncDum = $rwDum->inc;
							$RtsDum = $rwDum->rts;
							$NoaDum = $rwDum->noa;
							$DecDum = $rwDum->decl;
							
							if($NoaDum > 0 AND $DecDum > 0)
							{
								$appr_dm = $NoaDum / ($NoaDum + $DecDum) * 100;
							}
							else
							{
								$appr_dm = 0;
							}
							//Rating
							if($NoaDum==0){
								$rating_dummy = "Zero Account";
							}
							else if($NoaDum>=1 && $NoaDum<50){
								$rating_dummy = "Under Perform";
							}
							else if($NoaDum>=50 && $NoaDum<80){
								$rating_dummy = "Acceptable";
							}
							else if($NoaDum>=80 && $NoaDum<120){
								$rating_dummy = "Standard";
							}
							else if($NoaDum>=120){
								$rating_dummy = "Excellent";
							}
						}
						else
						{
							$IncDum = 0;
							$RtsDum = 0;
							$NoaDum = 0;
							$DecDum = 0;
							$appr_dm = 0;
							$rating_dummy = "-";
						}
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td>
								<a href="javascript:void(0)" onclick="Detail2()">DUMMY SPV</a>
							</td>
							<td></td>
							<td></td>
							<td><?php echo $IncDumLs; ?></td>
							<td><?php echo $RtsDumLs; ?></td>
							<td><?php echo $NoaDumLs; ?></td>
							<td><?php echo $DecDumLs; ?></td>
							<td><?php echo round($appr_dm_ls); ?> %</td>
							<td><?php RatingColour($rating_dummy_ls); ?></td>
							<td><?php echo $IncDum; ?></td>
							<td><?php echo $RtsDum; ?></td>
							<td><?php echo $NoaDum; ?></td>
							<td><?php echo $DecDum; ?></td>
							<td><?php echo round($appr_dm); ?> %</td>
							<td><?php RatingColour($rating_dummy); ?></td>
						</tr>
						<tr style="display:none;" class="tr2">
							<td colspan="16">
								<table class="table table-bordered" style="font-size: 11px;">
									<tbody>
										<tr>
											<td colspan="3">
												<strong>DSR NAME</strong>
											</td>
											<td align="center" colspan="6">
												<strong>
													<?php echo date('M-Y', strtotime('-1 month')); ?>
												</strong>
											</td>
											<td align="center" colspan="6">
												<strong>
													<?php echo date('M-Y'); ?>
												</strong>
											</td>
										</tr>
										
										<tr class="bg-green">
											<td style="width: 5px;">No</td>
											<td style="width: 200px;">Name</td>
											<td style="width: 10px;">MOB</td>
											<td style="width: 50px;">INC</td>
											<td style="width: 50px;">RTS</td>
											<td style="width: 50px;">NOA</td>
											<td style="width: 50px;">DEC</td>
											<td style="width: 50px;">A / R</td>
											<td style="width: 50px;">Rating</td>
											<td style="width: 50px;">INC</td>
											<td style="width: 50px;">RTS</td>
											<td style="width: 50px;">NOA</td>
											<td style="width: 50px;">DEC</td>
											<td style="width: 50px;">A / R</td>
											<td style="width: 50px;">Rating</td>
										</tr>
										<?php
											$no_br = 0;
											$sql_breakdown = $this->rsm_model->getDsrDummy($this->uri->segment(3), $tanggal);
											foreach($sql_breakdown->result() as $br)
											{
												//datediff
												$var_tgl1 = $br->Efektif_Date;
												if($var_tgl1 == "0000-00-00")
												{
													$vartgl = $br->Join_Date;
												}
												else{
													$vartgl = $var_tgl1;
												}
												$var_tgl2 = date('Y-m-d');
												$var_waktu_1 = strtotime("$vartgl");
												$var_waktu_2 = strtotime("$var_tgl2");
												// Menambah bulan ini + semua bulan pada tahun sebelumnya
												$var_diff = $var_waktu_2-$var_waktu_1;
												// menghitung selisih bulan
												$mob = $var_diff / 86400 / 30;
												
												$sqlDumsLs = $this->rsm_model->getDataDsr($br->DSR_Code, $tanggal_old);
												if($sqlDumsLs->num_rows() > 0)
												{
													$brDumsLs = $sqlDumsLs->row();
													$inc_dums_ls = $brDumsLs->Inc_cc + $brDumsLs->Inc_edc + $brDumsLs->Inc_sc;
													$rts_dums_ls = $brDumsLs->Rts_cc + $brDumsLs->Rts_edc + $brDumsLs->Rts_sc;
													$noa_dums_ls = $brDumsLs->Noa_cc + $brDumsLs->Noa_edc + $brDumsLs->Noa_sc;
													$dec_dums_ls = $brDumsLs->Dec_cc + $brDumsLs->Dec_edc + $brDumsLs->Dec_sc;
													if($br->Product == "CC")
													{
														$app_rate_ls = $brDumsLs->Apprate_cc;
													}elseif($br->Product == "EDC")
													{
														$app_rate_ls = $brDumsLs->Apprate_edc;
													}elseif($br->Product == "SC")
													{
														$app_rate_ls = $brDumsLs->Apprate_sc;
													}
													$ratings_ls = $brDumsLs->ratings;
													
												}else
												{
													$inc_dums_ls = 0;
													$rts_dums_ls = 0;
													$noa_dums_ls = 0;
													$dec_dums_ls = 0;
													$app_rate_ls = 0;
													$ratings_ls = "-";
												}
												
												$sqlDums = $this->rsm_model->getDataDsr($br->DSR_Code, $tanggal);
												if($sqlDums->num_rows() > 0)
												{
													$brDums = $sqlDums->row();
													$inc_dums = $brDums->Inc_cc + $brDums->Inc_edc + $brDums->Inc_sc;
													$rts_dums = $brDums->Rts_cc + $brDums->Rts_edc + $brDums->Rts_sc;
													$noa_dums = $brDums->Noa_cc + $brDums->Noa_edc + $brDums->Noa_sc;
													$dec_dums = $brDums->Dec_cc + $brDums->Dec_edc + $brDums->Dec_sc;
													if($br->Product == "CC")
													{
														$app_rate = $brDums->Apprate_cc;
													}elseif($br->Product == "EDC")
													{
														$app_rate = $brDums->Apprate_edc;
													}elseif($br->Product == "SC")
													{
														$app_rate = $brDums->Apprate_sc;
													}
													$ratingsnya = $brDums->ratings;
													
												}else
												{
													$inc_dums = 0;
													$rts_dums = 0;
													$noa_dums = 0;
													$dec_dums = 0;
													$app_rate = 0;
													$ratingsnya = "-";
												}
										?>
											<tr>
												<td><?php echo ++$no_br; ?></td>
												<td><?php echo $br->Name; ?></td>
												<td><?php echo round($mob); ?></td>
												<td><?php echo $inc_dums_ls; ?></td>
												<td><?php echo $rts_dums_ls; ?></td>
												<td><?php echo $noa_dums_ls; ?></td>
												<td><?php echo $dec_dums_ls; ?></td>
												<td><?php echo $app_rate_ls; ?> %</td>
												<td><?php RatingColour($ratings_ls); ?></td>
												<td><?php echo $inc_dums; ?></td>
												<td><?php echo $rts_dums; ?></td>
												<td><?php echo $noa_dums; ?></td>
												<td><?php echo $dec_dums; ?></td>
												<td><?php echo $app_rate; ?> %</td>
												<td><?php RatingColour($ratingsnya); ?></td>
											</tr>
										<?php
											}
										?>
									</tbody>
								</table>
							</td>
						</tr>
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
    $('.tr2').toggle();
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