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
							
							$sqlLs = $this->bsh_model->getAppsLsAll($val->SPV_Code, $tanggal_old);
							if($sqlLs->num_rows() > 0)
							{
								$rows = $sqlLs->row();
								$inc_ls = $rows->Inc_cc + $rows->Inc_edc + $rows->Inc_sc;
								$rts_ls = $rows->Rts_cc + $rows->Rts_edc + $rows->Rts_sc;
								$noa_ls = $rows->Noa_cc + $rows->Noa_edc + $rows->Noa_sc;
								$dec_ls = $rows->Dec_cc + $rows->Dec_edc + $rows->Dec_sc;
								$rating_ls = $rows->Ratings;
								if($val->Product == "CC")
								{
									$apprate_ls = $rows->Apprate_cc." %";
								}
								elseif($val->Product == "EDC")
								{
									$apprate_ls = $rows->Apprate_edc." %";
								}
								elseif($val->Product == "SC")
								{
									$apprate_ls = $rows->Apprate_sc." %";
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

							$sql = $this->bsh_model->getAppsLsAll($val->SPV_Code, $tanggal);
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
									$apprate = $row->Apprate_cc." %";
								}
								elseif($val->Product == "EDC")
								{
									$apprate = $row->Apprate_edc." %";
								}
								elseif($val->Product == "SC")
								{
									$apprate = $row->Apprate_sc." %";
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
							<td><?php echo mob($val->Join_Date,$val->Efektif_Date); ?></td>
							<td><?php echo $val->totalnya; ?></td>
							<td><?php echo $inc_ls; ?></td>
							<td><?php echo $rts_ls; ?></td>
							<td><?php echo $noa_ls; ?></td>
							<td><?php echo $dec_ls; ?></td>
							<td><?php echo $apprate_ls; ?></td>
							<td><?php RatingColour($rating_ls); ?></td>
							<td><?php echo $inc; ?></td>
							<td><?php echo $rts; ?></td>
							<td><?php echo $noa; ?></td>
							<td><?php echo $dec; ?></td>
							<td><?php echo $apprate; ?></td>
							<td><?php RatingColour($ratings); ?></td>
						</tr>
						<tr style="display:none;" class="tr_<?php echo $val->SPV_Code; ?>">
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
											<td>1.</td>
											<td>CC</td>
											<td><?php echo $rows->Inc_cc; ?></td>
											<td><?php echo $rows->Rts_cc; ?></td>
											<td><?php echo $rows->Noa_cc; ?></td>
											<td><?php echo $rows->Dec_cc; ?></td>
											<td><?php echo $rows->Apprate_cc; ?> %</td>
											<td><?php echo $row->Inc_cc ?></td>
											<td><?php echo $row->Rts_cc ?></td>
											<td><?php echo $row->Noa_cc ?></td>
											<td><?php echo $row->Dec_cc ?></td>
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
											<td><?php echo $row->Apprate_edc; ?> %</td>
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
											<td><?php echo $row->Apprate_sc; ?> %</td>
										</tr>
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
											$no_dsr = 0;
											$sql_dsr = $this->bsh_model->getAllDsrBySpv($val->SPV_Code);
											foreach($sql_dsr->result() as $arr_dsr)
											{
												$query_ls = $this->bsh_model->getAppsLsAllDsr($arr_dsr->DSR_Code, $tanggal_old);
												if($query_ls->num_rows() > 0)
												{
													$dt_ds_ls = $query_ls->row();
													$inc_ds_ls = $dt_ds_ls->Inc_cc + $dt_ds_ls->Inc_edc + $dt_ds_ls->Inc_sc;
													$rts_ds_ls = $dt_ds_ls->Rts_cc + $dt_ds_ls->Rts_edc + $dt_ds_ls->Rts_sc;
													$noa_ds_ls = $dt_ds_ls->Noa_cc + $dt_ds_ls->Noa_edc + $dt_ds_ls->Noa_sc;
													$dec_ds_ls = $dt_ds_ls->Dec_cc + $dt_ds_ls->Dec_edc + $dt_ds_ls->Dec_sc;
													if($arr_dsr->Product == "CC")
													{
														$ds_apprate_ls = $dt_ds_ls->Apprate_cc;
													}elseif($arr_dsr->Product == "EDC")
													{
														$ds_apprate_ls = $dt_ds_ls->Apprate_edc;
													}elseif($arr_dsr->Product == "SC")
													{
														$ds_apprate_ls = $dt_ds_ls->Apprate_sc;
													}
													$ds_rating_ls = $dt_ds_ls->ratings;
												}
												else
												{
													$inc_ds_ls = 0;
													$rts_ds_ls = 0;
													$noa_ds_ls = 0;
													$dec_ds_ls = 0;
													$ds_apprate_ls = 0;
													$ds_rating_ls = "-";
												}
												
												$query_now = $this->bsh_model->getAppsLsAllDsr($arr_dsr->DSR_Code, $tanggal);
												if($query_now->num_rows() > 0)
												{
													$dt_ds_now = $query_now->row();
													$inc_ds_now = $dt_ds_now->Inc_cc + $dt_ds_now->Inc_edc + $dt_ds_now->Inc_sc;
													$rts_ds_now = $dt_ds_now->Rts_cc + $dt_ds_now->Rts_edc + $dt_ds_now->Rts_sc;
													$noa_ds_now = $dt_ds_now->Noa_cc + $dt_ds_now->Noa_edc + $dt_ds_now->Noa_sc;
													$dec_ds_now = $dt_ds_now->Dec_cc + $dt_ds_now->Dec_edc + $dt_ds_now->Dec_sc;
													if($arr_dsr->Product == "CC")
													{
														$ds_apprate_now = $dt_ds_now->Apprate_cc;
													}elseif($arr_dsr->Product == "EDC")
													{
														$ds_apprate_now = $dt_ds_now->Apprate_edc;
													}elseif($arr_dsr->Product == "SC")
													{
														$ds_apprate_now = $dt_ds_now->Apprate_sc;
													}
													$ds_rating_now = $dt_ds_now->ratings;
												}
												else
												{
													$inc_ds_now = 0;
													$rts_ds_now = 0;
													$noa_ds_now = 0;
													$dec_ds_now = 0;
													$ds_apprate_now = 0;
													$ds_rating_now = "-";
												}
										?>
											<tr>
												<td><?php echo ++$no_dsr; ?></td>
												<td><?php echo $arr_dsr->Name; ?></td>
												<td><?php echo mob($arr_dsr->Join_Date, $arr_dsr->Efektif_Date); ?></td>
												<td><?php echo $inc_ds_ls; ?></td>
												<td><?php echo $rts_ds_ls; ?></td>
												<td><?php echo $noa_ds_ls; ?></td>
												<td><?php echo $dec_ds_ls; ?></td>
												<td><?php echo $ds_apprate_ls; ?> %</td>
												<td><?php RatingColour($ds_rating_ls); ?></td>
												<td><?php echo $inc_ds_now; ?></td>
												<td><?php echo $rts_ds_now; ?></td>
												<td><?php echo $noa_ds_now; ?></td>
												<td><?php echo $dec_ds_now; ?></td>
												<td><?php echo $ds_apprate_now; ?> %</td>
												<td><?php RatingColour($ds_rating_now); ?></td>
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