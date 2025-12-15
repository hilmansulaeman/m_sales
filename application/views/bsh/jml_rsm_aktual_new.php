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
		<h3 class="box-title">DATA SUMMARY RSM AKTUAL</h3>			  
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
							<th rowspan="2">ASM</th>
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
						foreach ($query->result() as $rows) {

						$sqlApss = $this->bsh_model->getApps($rows->RSM_Code, $tanggal_old);
						if($sqlApss->num_rows() > 0)
						{
							$rOld = $sqlApss->row();
							$inc_old = $rOld->Inc_cc + $rOld->Inc_edc + $rOld->Inc_sc;
							$rts_old = $rOld->Rts_cc + $rOld->Rts_edc + $rOld->Rts_sc;
							$noa_old = $rOld->Noa_cc + $rOld->Noa_edc + $rOld->Noa_sc;
							$dec_old = $rOld->Dec_cc + $rOld->Dec_edc + $rOld->Dec_sc;
							$rating_old = $rOld->rating;
							if($rows->Product == "CC")
							{
								$apprate_old = $rOld->Apprate_cc;
							}elseif($rows->Product == "EDC")
							{
								$apprate_old = $rOld->Apprate_edc;
							}elseif($rows->Product == "SC")
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

						$sqlApss_n = $this->bsh_model->getApps($rows->RSM_Code, $tanggal);
						if($sqlApss_n->num_rows() > 0)
						{
							$rNow = $sqlApss_n->row();
							$inc = $rNow->Inc_cc + $rNow->Inc_edc + $rNow->Inc_sc;
							$rts = $rNow->Rts_cc + $rNow->Rts_edc + $rNow->Rts_sc;
							$noa = $rNow->Noa_cc + $rNow->Noa_edc + $rNow->Noa_sc;
							$dec = $rNow->Dec_cc + $rNow->Dec_edc + $rNow->Dec_sc;
							$rating = $rNow->rating;
							if($rows->Product == "CC")
							{
								$apprate = $rNow->Apprate_cc;
							}elseif($rows->Product == "EDC")
							{
								$apprate = $rNow->Apprate_edc;
								$rating = $rNow->Rating_edc;
							}elseif($rows->Product == "SC")
							{
								$apprate = $rNow->Apprate_sc;
								$rating_ = $rNow->Rating_sc;
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
								<td><b>RSM</b> <a href="#" onclick="Detail('<?php echo $rows->RSM_Code; ?>')"><?php echo $rows->RSM_Name; ?></a></td>
								<td><?php echo mob($rows->Join_Date, $rows->Efektif_Date); ?></td>
								<td><?php echo $rows->asmJml; ?></td>
								<td><?php echo $inc_old; ?></td>
								<td><?php echo $rts_old; ?></td>
								<td><?php echo $noa_old; ?></td>
								<td><?php echo $dec_old; ?></td>
								<td><?php echo $apprate_old; ?></td>
								<td><?php RatingColour($rating_old); ?></td>
								<td><?php echo $inc; ?></td>
								<td><?php echo $rts; ?></td>
								<td><?php echo $noa; ?></td>
								<td><?php echo $dec; ?></td>
								<td><?php echo $apprate; ?></td>
								<td><?php RatingColour($rating); ?></td>
							</tr>
							<tr style="display:none;" class="tr_<?php echo $rows->RSM_Code; ?>">
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
												<td><?php echo $rOld->Inc_cc; ?></td>
												<td><?php echo $rOld->Rts_cc; ?></td>
												<td><?php echo $rOld->Noa_cc; ?></td>
												<td><?php echo $rOld->Dec_cc; ?></td>
												<td><?php echo $rOld->Apprate_cc; ?> %</td>
												<td><?php echo $rNow->Inc_cc; ?></td>
												<td><?php echo $rNow->Rts_cc; ?></td>
												<td><?php echo $rNow->Noa_cc; ?></td>
												<td><?php echo $rNow->Dec_cc; ?></td>
												<td><?php echo $rNow->Apprate_cc; ?> %</td>
											</tr>
											<tr>
												<td>2</td>
												<td>EDC</td>
												<td><?php echo $rOld->Inc_edc; ?></td>
												<td><?php echo $rOld->Rts_edc; ?></td>
												<td><?php echo $rOld->Noa_edc; ?></td>
												<td><?php echo $rOld->Dec_edc; ?></td>
												<td><?php echo $rOld->Apprate_edc; ?> %</td>
												<td><?php echo $rNow->Inc_edc; ?></td>
												<td><?php echo $rNow->Rts_edc; ?></td>
												<td><?php echo $rNow->Noa_edc; ?></td>
												<td><?php echo $rNow->Dec_edc; ?></td>
												<td><?php echo $rNow->Apprate_edc; ?> %</td>
											</tr>
											<tr>
												<td>3</td>
												<td>SC</td>
												<td><?php echo $rOld->Inc_sc; ?></td>
												<td><?php echo $rOld->Rts_sc; ?></td>
												<td><?php echo $rOld->Noa_sc; ?></td>
												<td><?php echo $rOld->Dec_sc; ?></td>
												<td><?php echo $rOld->Apprate_sc; ?> %</td>
												<td><?php echo $rNow->Inc_sc; ?></td>
												<td><?php echo $rNow->Rts_sc; ?></td>
												<td><?php echo $rNow->Noa_sc; ?></td>
												<td><?php echo $rNow->Dec_sc; ?></td>
												<td><?php echo $rNow->Apprate_sc; ?> %</td>
											</tr>
										</tbody>
									</table>
									<br>
									<table class="table table-bordered" style="font-size: 11px;">
										<tbody>
											<tr>
												<td colspan="3">
													<strong>ASM NAME</strong>
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
												$no_asm = 0;
												$sql_asm = $this->bsh_model->getAsmByRsm($rows->RSM_Code);
												foreach($sql_asm->result() as $arr)
												{
													//-1
													$sql_ls = $this->bsh_model->getAppsAsm($arr->ASM_Code, $tanggal_old);
													if($sql_ls->num_rows() > 0)
													{
														$ls = $sql_ls->row();
														$ls_inc = $ls->Inc_cc + $ls->Inc_edc + $ls->Inc_sc;
														$ls_rts = $ls->Rts_cc + $ls->Rts_edc + $ls->Rts_sc;
														$ls_noa = $ls->Noa_cc + $ls->Noa_edc + $ls->Noa_sc;
														$ls_dec = $ls->Dec_cc + $ls->Dec_edc + $ls->Dec_sc;
														if($arr->Product == "CC")
														{
															$ls_apprate = $ls->Apprate_cc;
														}elseif($arr->Product == "EDC")
														{
															$ls_apprate = $ls->Apprate_edc;
														}elseif($arr->Product == "SC")
														{
															$ls_apprate = $ls->Apprate_sc;
														}
														$ls_ratings = $ls->rating;
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
													//-1
													$sql_now = $this->bsh_model->getAppsAsm($arr->ASM_Code, $tanggal);
													if($sql_now->num_rows() > 0)
													{
														$now = $sql_now->row();
														$now_inc = $now->Inc_cc + $now->Inc_edc + $now->Inc_sc;
														$now_rts = $now->Rts_cc + $now->Rts_edc + $now->Rts_sc;
														$now_noa = $now->Noa_cc + $now->Noa_edc + $now->Noa_sc;
														$now_dec = $now->Dec_cc + $now->Dec_edc + $now->Dec_sc;
														if($arr->Product == "CC")
														{
															$now_apprate = $now->Apprate_cc;
														}elseif($arr->Product == "EDC")
														{
															$now_apprate = $now->Apprate_edc;
														}elseif($arr->Product == "SC")
														{
															$now_apprate = $now->Apprate_sc;
														}
														$now_ratings = $now->rating;
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
													<td><?php echo ++$no_asm; ?></td>
													<td><?php echo $arr->ASM_Name; ?></td>
													<td><?php echo mob($arr->Join_Date, $arr->Efektif_Date); ?></td>
													<td><?php echo $ls_inc; ?></td>
													<td><?php echo $ls_rts; ?></td>
													<td><?php echo $ls_noa; ?></td>
													<td><?php echo $ls_dec; ?></td>
													<td><?php echo $ls_apprate; ?></td>
													<td><?php RatingColour($ls_ratings); ?></td>
													<td><?php echo $now_inc; ?></td>
													<td><?php echo $now_rts; ?></td>
													<td><?php echo $now_noa; ?></td>
													<td><?php echo $now_dec; ?></td>
													<td><?php echo $now_apprate; ?></td>
													<td><?php RatingColour($now_ratings); ?></td>
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