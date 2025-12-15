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
		<h3 class="box-title">DATA SUMMARY BY ASM</h3>			  
	</div>
	<div class="panel-body">
		<div class="table-responsive">	
			<div style="height:500px;overflow-y:scroll;">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th rowspan="2">NO</th>
							<th rowspan="2">ASM NAME</th>
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
						$tgl_now = date('Y-m');
						$tgl_old = date('Y-m', strtotime('-1 month'));
						foreach ($query->result() as $row) {
							$sql_ls = $this->rsm_model->getApssByAsm($row->ASM_Code, $tgl_old);
							if($sql_ls->num_rows() > 0)
							{
								$row_ls = $sql_ls->row();
								$total_inc_ls = $row_ls->Inc_cc + $row_ls->Inc_edc + $row_ls->Inc_sc;
								$total_rts_ls = $row_ls->Rts_cc + $row_ls->Rts_edc + $row_ls->Rts_sc;
								$total_noa_ls = $row_ls->Noa_cc + $row_ls->Noa_edc + $row_ls->Noa_sc;
								$total_dec_ls = $row_ls->Dec_cc + $row_ls->Dec_edc + $row_ls->Dec_sc;
								$rating_ls = $row_ls->rating;
								if($row->Product == "CC")
								{
									$apprate_ls = $row_ls->Apprate_cc;
								}elseif($row->Product == "EDC")
								{
									$apprate_ls = $row_ls->Apprate_edc;
								}elseif($row->Product == "SC")
								{
									$apprate_ls = $row_ls->Apprate_sc;
								}
							}
							else
							{
								$total_inc_ls = "-";
								$total_rts_ls = "-";
								$total_noa_ls = "-";
								$total_dec_ls = "-";
								$apprate_ls = "-";
								$rating_ls = "-";
							}

							$sql = $this->rsm_model->getApssByAsm($row->ASM_Code, $tgl_now);
							if($sql->num_rows() > 0)
							{
								$rows = $sql->row();
								$inc = $rows->Inc_cc + $rows->Inc_edc + $rows->Inc_sc;
								$rts = $rows->Rts_cc + $rows->Rts_edc + $rows->Rts_sc;
								$noa = $rows->Noa_cc + $rows->Noa_edc + $rows->Noa_sc;
								$dec = $rows->Dec_cc + $rows->Dec_edc + $rows->Dec_sc;
								$rating = $rows->rating;
								if($row->Product == "CC")
								{
									$apprate = $rows->Apprate_cc;
								}elseif($row->Product == "EDC")
								{
									$apprate = $rows->Apprate_edc;
								}elseif($row->Product == "SC")
								{
									$apprate = $rows->Apprate_sc;
								}
							}
							else
							{
								$inc = "-";
								$rts = "-";
								$noa = "-";
								$dec = "-";
								$apprate = "-";
								$rating = "-";
							}
							
							//datediff
							$tgl1 = $row->Efektif_Date;
							if($tgl1 == "0000-00-00")
							{
								$tgl1 = $row->Join_Date;
							}
							$tgl2 = date('Y-m-d');
							$timeStart1 = strtotime("$tgl1");
							$timeEnd2 = strtotime("$tgl2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diffs = $timeEnd2-$timeStart1;
							// menghitung selisih bulan
							$numBulanS = $diffs / 86400 / 30;

					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>ASM</b> <a href="#" onclick="Detail('<?php echo $row->ASM_Code; ?>');"><?php echo $row->ASM_Name; ?></a></td>
							<td><?php echo round($numBulanS); ?></td>
							<td><?php echo $row->totalnya ?></td>
							<td><?php echo $total_inc_ls; ?></td>
							<td><?php echo $total_rts_ls; ?></td>
							<td><?php echo $total_noa_ls; ?></td>
							<td><?php echo $total_dec_ls; ?></td>
							<td><?php echo round($apprate_ls); ?> %</td>
							<td>
							<?php
								if($rating_ls == "Under Perform" OR $rating_ls == "Under Perform 1" OR $rating_ls == "Under Perform 2" OR $rating_ls == "Warning" OR $rating_ls == "Zero Account")
								{
									echo "<span class='label label-danger'>".$rating_ls."</span>";
								}
								elseif($rating_ls == "Acceptable")
								{
									echo "<span class='label label-success'>".$rating_ls."</span>";
								}
								elseif($rating_ls == "Standard" OR $rating_ls == "Standar")
								{
									echo "<span class='label label-warning'>".$rating_ls."</span>";
								}
								elseif($rating_ls == "Excellent" OR $rating_ls == "Excellent 1" OR $rating_ls == "Excellent 2")
								{
									echo "<span class='label label-primary'>".$rating_ls."</span>";
								}
							?>
							</td>
							<td><?php echo $inc; ?></td>
							<td><?php echo $rts; ?></td>
							<td><?php echo $noa; ?></td>
							<td><?php echo $dec; ?></td>
							<td><?php echo round($apprate); ?> %</td>
							<td>
							<?php
								if($rating == "Under Perform" OR $rating == "Under Perform 1" OR $rating == "Under Perform 2" OR $rating == "Warning" OR $rating == "Zero Account")
								{
									echo "<span class='label label-danger'>".$rating."</span>";
								}
								elseif($rating == "Acceptable")
								{
									echo "<span class='label label-success'>".$rating."</span>";
								}
								elseif($rating == "Standard" OR $rating == "Standar")
								{
									echo "<span class='label label-warning'>".$rating."</span>";
								}
								elseif($rating == "Excellent" OR $rating == "Excellent 1" OR $rating == "Excellent 2")
								{
									echo "<span class='label label-primary'>".$rating."</span>";
								}
							?>
							</td>
						</tr>
						<tr style="display:none;" class="tr_<?php echo $row->ASM_Code ?>">
							<td colspan="16" style="background-color: #F2F4F4;">
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
											<td>1</td>
											<td>CC</td>
											<td><?php echo $row_ls->Inc_cc; ?></td>
											<td><?php echo $row_ls->Rts_cc; ?></td>
											<td><?php echo $row_ls->Noa_cc; ?></td>
											<td><?php echo $row_ls->Dec_cc; ?></td>
											<td><?php echo $row_ls->Apprate_cc; ?> %</td>
											<td><?php echo $rows->Inc_cc; ?></td>
											<td><?php echo $rows->Rts_cc; ?></td>
											<td><?php echo $rows->Noa_cc; ?></td>
											<td><?php echo $rows->Dec_cc; ?></td>
											<td><?php echo $rows->Apprate_cc; ?> %</td>
										</tr>
										<tr>
											<td>2</td>
											<td>EDC</td>
											<td><?php echo $row_ls->Inc_edc; ?></td>
											<td><?php echo $row_ls->Rts_edc; ?></td>
											<td><?php echo $row_ls->Noa_edc; ?></td>
											<td><?php echo $row_ls->Dec_edc; ?></td>
											<td><?php echo $row_ls->Apprate_edc; ?> %</td>
											<td><?php echo $rows->Inc_edc; ?></td>
											<td><?php echo $rows->Rts_edc; ?></td>
											<td><?php echo $rows->Noa_edc; ?></td>
											<td><?php echo $rows->Dec_edc; ?></td>
											<td><?php echo $rows->Apprate_edc; ?> %</td>
										</tr>
										<tr>
											<td>3</td>
											<td>SC</td>
											<td><?php echo $row_ls->Inc_sc; ?></td>
											<td><?php echo $row_ls->Rts_sc; ?></td>
											<td><?php echo $row_ls->Noa_sc; ?></td>
											<td><?php echo $row_ls->Dec_sc; ?></td>
											<td><?php echo $row_ls->Apprate_sc; ?> %</td>
											<td><?php echo $rows->Inc_sc; ?></td>
											<td><?php echo $rows->Rts_sc; ?></td>
											<td><?php echo $rows->Noa_sc; ?></td>
											<td><?php echo $rows->Dec_sc; ?></td>
											<td><?php echo $rows->Apprate_sc; ?> %</td>
										</tr>
									</tbody>
								</table>
								<br>
								<table class="table table-bordered" style="font-size: 11px;">
									<tbody>
										<tr>
											<td colspan="3">
												<strong>SPV NAME</strong>
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
											$no_spv = 0;
											$sql_spv = $this->rsm_model->getSpvByAsm($row->ASM_Code);
											foreach($sql_spv->result() as $spv)
											{
												
												//datediff
												$tanggal_1 = $spv->Efektif_Date;
												if($tanggal_1 == "0000-00-00")
												{
													$tanggal_11 = $spv->Join_Date;
												}
												else{
													$tanggal_11 = $tanggal_1;
												}
												$tanggal_2 = date('Y-m-d');
												$waktu_1 = strtotime("$tanggal_11");
												$waktu_2 = strtotime("$tanggal_2");
												// Menambah bulan ini + semua bulan pada tahun sebelumnya
												$diff = $waktu_2-$waktu_1;
												// menghitung selisih bulan
												$mob1 = $diff / 86400 / 30;
												
												$sqlLs = $this->rsm_model->getAppsLsAll($spv->SPV_Code, $tgl_old);
												if($sqlLs->num_rows() > 0)
												{
													$aps_ls = $sqlLs->row();
													$inc_ls = $aps_ls->Inc_cc + $aps_ls->Inc_edc + $aps_ls->Inc_sc;
													$rts_ls = $aps_ls->Rts_cc + $aps_ls->Rts_edc + $aps_ls->Rts_sc;
													$noa_ls = $aps_ls->Noa_cc + $aps_ls->Noa_edc + $aps_ls->Noa_sc;
													$dec_ls = $aps_ls->Dec_cc + $aps_ls->Dec_edc + $aps_ls->Dec_sc;
													if($spv->Product == "CC")
													{
														$apprate_ls = $aps_ls->Apprate_cc;
													}elseif($spv->Product == "EDC")
													{
														$apprate_ls = $aps_ls->Apprate_edc;
													}elseif($spv->Product == "SC")
													{
														$apprate_ls = $aps_ls->Apprate_sc;
													}
													$ratingLs = $aps_ls->Ratings;
												}
												else
												{
													$inc_ls = 0;
													$rts_ls = 0;
													$noa_ls = 0;
													$dec_ls = 0;
													$apprate_ls = 0;
													$ratingLs = "-";
												}
												
												$sqlNow = $this->rsm_model->getAppsLsAll($spv->SPV_Code, $tgl_now);
												if($sqlNow->num_rows() > 0)
												{
													$aps = $sqlNow->row();
													$inc = $aps->Inc_cc + $aps->Inc_edc + $aps->Inc_sc;
													$rts = $aps->Rts_cc + $aps->Rts_edc + $aps->Rts_sc;
													$noa = $aps->Noa_cc + $aps->Noa_edc + $aps->Noa_sc;
													$dec = $aps->Dec_cc + $aps->Dec_edc + $aps->Dec_sc;
													if($spv->Product == "CC")
													{
														$apprate = $aps_ls->Apprate_cc;
													}elseif($spv->Product == "EDC")
													{
														$apprate = $aps_ls->Apprate_edc;
													}elseif($spv->Product == "SC")
													{
														$apprate = $aps_ls->Apprate_sc;
													}
													$ratingNow = $aps->Ratings;
												}
												else
												{
													$inc = 0;
													$rts = 0;
													$noa = 0;
													$dec = 0;
													$apprate = 0;
													$ratingNow = "-";
												}
										?>
										<tr>
											<td><?php echo ++$no_spv; ?></td>
											<td><?php echo $spv->SPV_Name; ?></td>
											<td><?php echo round($mob1); ?></td>
											<td><?php echo $inc_ls; ?></td>
											<td><?php echo $rts_ls; ?></td>
											<td><?php echo $noa_ls; ?></td>
											<td><?php echo $dec_ls; ?></td>
											<td><?php echo $apprate_ls; ?> %</td>
											<td><?php RatingColour($ratingLs); ?></td>
											<td><?php echo $inc; ?></td>
											<td><?php echo $rts; ?></td>
											<td><?php echo $noa; ?></td>
											<td><?php echo $dec; ?></td>
											<td><?php echo $apprate ?> %</td>
											<td><?php RatingColour($ratingNow); ?></td>
										</tr>
										<?php
											}
										?>
										<?php
											$sql_dummy = $this->rsm_model->getSumDummy($row->ASM_Code, $tgl_old);
											if($sql_dummy->num_rows() > 0)
											{
												$dtDum = $sql_dummy->row();
												$dum_inc_ls = $dtDum->inc;
												$dum_rts_ls = $dtDum->rts;
												$dum_noa_ls = $dtDum->noa;
												$dum_dec_ls = $dtDum->decl;
												//Rating
												if($dum_noa_ls==0){
													$rating_dummy_ls = "Zero Account";
												}
												else if($dum_noa_ls>=1 && $dum_noa_ls<50){
													$rating_dummy_ls = "Under Perform";
												}
												else if($dum_noa_ls>=50 && $dum_noa_ls<80){
													$rating_dummy_ls = "Acceptable";
												}
												else if($dum_noa_ls>=80 && $dum_noa_ls<120){
													$rating_dummy_ls = "Standard";
												}
												else if($dum_noa_ls>=120){
													$rating_dummy_ls = "Excellent";
												}
												if($dum_noa_ls > 0 AND $dum_dec_ls > 0)
												{
													$appr_dm_ls = $dum_noa_ls / ($dum_noa_ls + $dum_dec_ls) * 100;
												}
												else
												{
													$appr_dm_ls = 0;
												}
												
											}
											else
											{
												$dum_inc_ls = 0;
												$dum_rts_ls = 0;
												$dum_noa_ls = 0;
												$dum_dec_ls = 0;
												$appr_dm_ls = 0;
												$rating_dummy_ls = "-";
											}
											
											$sql_dummy_n = $this->rsm_model->getSumDummy($row->ASM_Code, $tgl_now);
											if($sql_dummy_n->num_rows() > 0)
											{
												$dtDumNow = $sql_dummy_n->row();
												$dum_inc = $dtDumNow->inc;
												$dum_rts = $dtDumNow->rts;
												$dum_noa = $dtDumNow->noa;
												$dum_dec = $dtDumNow->decl;
												//Rating
												if($dum_noa==0){
													$rating_dummy = "Zero Account";
												}
												else if($dum_noa>=1 && $dum_noa<50){
													$rating_dummy = "Under Perform";
												}
												else if($dum_noa>=50 && $dum_noa<80){
													$rating_dummy = "Acceptable";
												}
												else if($dum_noa>=80 && $dum_noa<120){
													$rating_dummy = "Standard";
												}
												else if($dum_noa>=120){
													$rating_dummy = "Excellent";
												}
												if($dum_noa > 0 AND $dum_dec > 0)
												{
													$appr_dm = $dum_noa / ($dum_noa + $dum_dec) * 100;
												}
												else{
													$appr_dm = 0;
												}
												
											}
											else
											{
												$dum_inc = 0;
												$dum_rts = 0;
												$dum_noa = 0;
												$dum_dec = 0;
												$appr_dm = 0;
												$rating_dummy = "-";
											}
										?>
										<tr>
											<td><?php echo ++$no_spv; ?></td>
											<td>DUMMY SPV</td>
											<td></td>
											<td><?php echo $dum_inc_ls; ?></td>
											<td><?php echo $dum_rts_ls; ?></td>
											<td><?php echo $dum_noa_ls; ?></td>
											<td><?php echo $dum_dec_ls; ?></td>
											<td><?php echo round($appr_dm_ls); ?> %</td>
											<td><?php RatingColour($rating_dummy_ls); ?></td>
											<td><?php echo $dum_inc; ?></td>
											<td><?php echo $dum_rts; ?></td>
											<td><?php echo $dum_noa; ?></td>
											<td><?php echo $dum_dec; ?></td>
											<td><?php echo round($appr_dm); ?> %</td>
											<td><?php echo RatingColour($rating_dummy); ?></td>
										</tr>
									</tbody>
								</table>
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
function Detail(dsr_code)
{
    $('.tr_'+dsr_code).toggle();
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