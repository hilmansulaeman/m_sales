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
		<h3 class="box-title">DATA SUMMARY ASM AKTUAL</h3>			  
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
						$tgl_now = date('Y-m');
						$tgl_old = date('Y-m', strtotime('-1 month'));
						foreach ($query->result() as $row) {
							$sql_ls = $this->bsh_model->getApssByAsm($row->ASM_Code, $tgl_old);
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

							$sql = $this->bsh_model->getApssByAsm($row->ASM_Code, $tgl_now);
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

					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>ASM </b><a href="#" onclick="Detail('<?php echo $row->ASM_Code; ?>');"><?php echo $row->ASM_Name; ?></a></td>
							<td><?php echo mob($row->Join_Date, $row->Efektif_Date); ?></td>
							<td><?php echo $row->totalnya; ?></td>
							<td><?php echo $total_inc_ls; ?></td>
							<td><?php echo $total_rts_ls; ?></td>
							<td><?php echo $total_noa_ls; ?></td>
							<td><?php echo $total_dec_ls; ?></td>
							<td><?php echo $apprate_ls; ?></td>
							<td><?php RatingColour($rating_ls); ?></td>
							<td><?php echo $inc; ?></td>
							<td><?php echo $rts; ?></td>
							<td><?php echo $noa; ?></td>
							<td><?php echo $dec; ?></td>
							<td><?php echo $apprate; ?></td>
							<td><?php RatingColour($rating); ?></td>
						</tr>
						<tr style="display:none;" class="tr_<?php echo $row->ASM_Code; ?>">
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
											<td><?php echo $row_ls->Apprate_cc; ?></td>
											<td><?php echo $rows->Inc_cc; ?></td>
											<td><?php echo $rows->Rts_cc; ?></td>
											<td><?php echo $rows->Noa_cc; ?></td>
											<td><?php echo $rows->Dec_cc; ?></td>
											<td><?php echo $rows->Apprate_cc; ?></td>
										</tr>
										<tr>
											<td>2</td>
											<td>EDC</td>
											<td><?php echo $row_ls->Inc_edc; ?></td>
											<td><?php echo $row_ls->Rts_edc; ?></td>
											<td><?php echo $row_ls->Noa_edc; ?></td>
											<td><?php echo $row_ls->Dec_edc; ?></td>
											<td><?php echo $row_ls->Apprate_edc; ?></td>
											<td><?php echo $rows->Inc_edc; ?></td>
											<td><?php echo $rows->Rts_edc; ?></td>
											<td><?php echo $rows->Noa_edc; ?></td>
											<td><?php echo $rows->Dec_edc; ?></td>
											<td><?php echo $rows->Apprate_edc; ?></td>
										</tr>
										<tr>
											<td>3</td>
											<td>SC</td>
											<td><?php echo $row_ls->Inc_sc; ?></td>
											<td><?php echo $row_ls->Rts_sc; ?></td>
											<td><?php echo $row_ls->Noa_sc; ?></td>
											<td><?php echo $row_ls->Dec_sc; ?></td>
											<td><?php echo $row_ls->Apprate_sc; ?></td>
											<td><?php echo $rows->Inc_sc; ?></td>
											<td><?php echo $rows->Rts_sc; ?></td>
											<td><?php echo $rows->Noa_sc; ?></td>
											<td><?php echo $rows->Dec_sc; ?></td>
											<td><?php echo $rows->Apprate_sc; ?></td>
										</tr>
									</body>
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
											$sql_spv = $this->bsh_model->getSpvByAsm($row->ASM_Code);
											foreach($sql_spv->result() as $spv)
											{
												//-1
												$sqlLs = $this->bsh_model->getAppsLsAll($spv->SPV_Code, $tgl_old);
												if($sqlLs->num_rows() > 0)
												{
													$arr_ls = $sqlLs->row();
													$ls_inc = $arr_ls->Inc_cc + $arr_ls->Inc_edc + $arr_ls->Inc_sc;
													$ls_rts = $arr_ls->Rts_cc + $arr_ls->Rts_edc + $arr_ls->Rts_sc;
													$ls_noa = $arr_ls->Noa_cc + $arr_ls->Noa_edc + $arr_ls->Noa_sc;
													$ls_dec = $arr_ls->Dec_cc + $arr_ls->Dec_edc + $arr_ls->Dec_sc;
													if($spv->Product == "CC")
													{
														$ls_apprate = $arr_ls->Apprate_cc;
													}elseif($spv->Product == "EDC")
													{
														$ls_apprate = $arr_ls->Apprate_edc;
													}elseif($spv->Product == "SC")
													{
														$ls_apprate = $arr_ls->Apprate_sc;
													}
													$ls_ratings = $arr_ls->Ratings;
												}
												else
												{
													$ls_inc = 0;
													$ls_rts = 0;
													$ls_noa = 0;
													$ls_dec = 0;
													$ls_apprate = 0;
													$ls_ratings = "-";
												}
												
												//Current
												$sqlNow = $this->bsh_model->getAppsLsAll($spv->SPV_Code, $tgl_now);
												if($sqlNow->num_rows() > 0)
												{
													$arr_now = $sqlNow->row();
													$now_inc = $arr_now->Inc_cc + $arr_now->Inc_edc + $arr_now->Inc_sc;
													$now_rts = $arr_now->Rts_cc + $arr_now->Rts_edc + $arr_now->Rts_sc;
													$now_noa = $arr_now->Noa_cc + $arr_now->Noa_edc + $arr_now->Noa_sc;
													$now_dec = $arr_now->Dec_cc + $arr_now->Dec_edc + $arr_now->Dec_sc;
													if($spv->Product == "CC")
													{
														$now_apprate = $arr_now->Apprate_cc;
													}elseif($spv->Product == "EDC")
													{
														$now_apprate = $arr_now->Apprate_edc;
													}elseif($spv->Product == "SC")
													{
														$now_apprate = $arr_now->Apprate_sc;
													}
													$now_ratings = $arr_now->Ratings;
												}
												else
												{
													$now_inc = 0;
													$now_rts = 0;
													$now_noa = 0;
													$now_dec = 0;
													$now_apprate = 0;
													$now_ratings = "-";
												}
										?>
											<tr>
												<td><?php echo ++$no_spv; ?></td>
												<td><?php echo $spv->SPV_Name; ?></td>
												<td><?php echo mob($spv->Join_Date, $spv->Efektif_Date); ?></td>
												<td><?php echo $ls_inc; ?></td>
												<td><?php echo $ls_rts; ?></td>
												<td><?php echo $ls_noa; ?></td>
												<td><?php echo $ls_dec; ?></td>
												<td><?php echo $ls_apprate; ?> %</td>
												<td><?php RatingColour($ls_ratings); ?></td>
												<td><?php echo $now_inc; ?></td>
												<td><?php echo $now_rts; ?></td>
												<td><?php echo $now_noa; ?></td>
												<td><?php echo $now_dec; ?></td>
												<td><?php echo $now_apprate; ?> %</td>
												<td><?php RatingColour($now_ratings); ?></td>
											</tr>
										<?php
											}
										?>
										
										<?php
											$dummy_ls = $this->bsh_model->getDummyData($row->ASM_Code, $tgl_old);
											if($dummy_ls->num_rows() > 0)
											{
												$ls_dm = $dummy_ls->row();
												$ls_dm_inc = $ls_dm->inc;
												$ls_dm_rts = $ls_dm->rts;
												$ls_dm_noa = $ls_dm->noa;
												$ls_dm_dec = $ls_dm->decl;
												if($ls_dm_noa > 0 AND $ls_dm_dec > 0)
												{
													$appr_dm_ls = $ls_dm_noa / ($ls_dm_noa + $ls_dm_dec) * 100;
												}
												else
												{
													$appr_dm_ls = 0;
												}
												
												//Rating
												if($ls_dm_noa==0){
													$rating_dummy_ls = "Zero Account";
												}
												else if($ls_dm_noa>=1 && $ls_dm_noa<50){
													$rating_dummy_ls = "Under Perform";
												}
												else if($ls_dm_noa>=50 && $ls_dm_noa<80){
													$rating_dummy_ls = "Acceptable";
												}
												else if($ls_dm_noa>=80 && $ls_dm_noa<120){
													$rating_dummy_ls = "Standard";
												}
												else if($ls_dm_noa>=120){
													$rating_dummy_ls = "Excellent";
												}
											}
											else
											{
												$ls_dm_inc = 0;
												$ls_dm_rts = 0;
												$ls_dm_noa = 0;
												$ls_dm_dec = 0;
												$appr_dm_ls = 0;
												$rating_dummy_ls = "-";
											}
											
											$dummy_now = $this->bsh_model->getDummyData($row->ASM_Code, $tgl_now);
											if($dummy_now->num_rows() > 0)
											{
												$now_dm = $dummy_now->row();
												$now_dm_inc = $now_dm->inc;
												$now_dm_rts = $now_dm->rts;
												$now_dm_noa = $now_dm->noa;
												$now_dm_dec = $now_dm->decl;
												if($now_dm_noa > 0 AND $now_dm_dec > 0)
												{
													$appr_dm_now = $now_dm_noa / ($now_dm_noa + $now_dm_dec) * 100;
												}
												else
												{
													$appr_dm_now = 0;
												}
												
												//Rating
												if($now_dm_noa==0){
													$rating_dummy_now = "Zero Account";
												}
												else if($now_dm_noa>=1 && $now_dm_noa<50){
													$rating_dummy_now = "Under Perform";
												}
												else if($now_dm_noa>=50 && $now_dm_noa<80){
													$rating_dummy_now = "Acceptable";
												}
												else if($now_dm_noa>=80 && $now_dm_noa<120){
													$rating_dummy_now = "Standard";
												}
												else if($now_dm_noa>=120){
													$rating_dummy_now = "Excellent";
												}
											}
											else
											{
												$now_dm_inc = 0;
												$now_dm_rts = 0;
												$now_dm_noa = 0;
												$now_dm_dec = 0;
												$appr_dm_now = 0;
												$rating_dummy_now = "-";
											}
										?>
											<tr>
												<td><?php echo ++$no_spv; ?></td>
												<td>DUMMY SPV</td>
												<td></td>
												<td><?php echo $ls_dm_inc; ?></td>
												<td><?php echo $ls_dm_rts; ?></td>
												<td><?php echo $ls_dm_noa; ?></td>
												<td><?php echo $ls_dm_dec; ?></td>
												<td><?php echo round($appr_dm_ls); ?> %</td>
												<td><?php RatingColour($rating_dummy_ls); ?></td>
												<td><?php echo $now_dm_inc; ?></td>
												<td><?php echo $now_dm_rts; ?></td>
												<td><?php echo $now_dm_noa; ?></td>
												<td><?php echo $now_dm_dec; ?></td>
												<td><?php echo round($appr_dm_now); ?> %</td>
												<td><?php RatingColour($rating_dummy_now); ?></td>
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