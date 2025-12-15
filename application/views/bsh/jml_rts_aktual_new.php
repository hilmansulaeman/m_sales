<?php
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
		<h3 class="box-title">DATA SUMMARY RTS</h3>			  
	</div>
	<div class="panel-body">
		<div class="table-responsive">	
			<div style="height:500px;overflow-y:scroll;">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>NO</th>
							<th>NAME</th>
							<th>MOB</th>
							<th>TOTAL INC</th>
							<th>TOTAL RTS</th>
							<th>%</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$no = 0;
						$tanggal = date('Y-m');
						foreach ($query->result() as $rows) {
							$sql_apps = $this->bsh_model->getAllAppsNew($rows->RSM_Code, $tanggal);
							if($sql_apps->num_rows() > 0)
							{
								$dt = $sql_apps->row();
								$inc = $dt->Inc_cc + $dt->Inc_edc + $dt->Inc_sc;
								$rts = $dt->Rts_cc + $dt->Rts_edc + $dt->Rts_sc;
							}
							
							if($inc > 0 AND $rts > 0)
							{
								$persen = ($rts / $inc) * 100;
							}else
							{
								$persen = 0;
							}
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>RSM</b> <?php echo $rows->RSM_Name; ?></td>
							<td><?php echo mob($rows->Join_Date, $rows->Efektif_Date); ?></td>
							<td><?php echo $inc; ?></td>
							<td><a href="#" onclick="Detail('<?php echo $rows->RSM_Code ?>')"><?php echo $rts; ?></a></td>
							<td><?php echo round($persen) ?> %</td>
						</tr>
						
						<?php
							$no_asm = 0;
							$sql_asm = $this->bsh_model->getAsmByRsm($rows->RSM_Code);
							foreach($sql_asm->result() as $asm)
							{
								$apps = $this->bsh_model->CurrentAppsAsmNew($asm->ASM_Code, $tanggal);
								if($apps->num_rows() > 0)
								{
									$apl = $apps->row();
									$inc_asm = $apl->Inc_cc + $apl->Inc_edc + $apl->Inc_sc;
									$rts_asm = $apl->Rts_cc + $apl->Rts_edc + $apl->Rts_sc;
								}
								else
								{
									$inc_asm = 0;
									$rts_asm = 0;
								}
								if($inc_asm > 0 AND $rts_asm > 0)
								{
									$persen2 = ($rts_asm / $inc_asm) * 100;
								}
								else
								{
									$persen2 = 0;
								}

						?>
							<tr class="tr_<?php echo $rows->RSM_Code ?>" style="font-size: 11px; display: none;">
								<td align="right"><?php echo ++$no_asm; ?></td>
								<td align="right"><b>ASM</b> <?php echo $asm->ASM_Name; ?></td>
								<td><?php echo mob($asm->Join_Date, $asm->Efektif_Date); ?></td>
								<td><?php echo $inc_asm; ?></td>
								<td><?php echo $rts_asm; ?></td>
								<td><?php echo round($persen2); ?> %</td>
							</tr>
						<?php
							}
						?>
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
    $('.tr2_'+dsr_code).toggle();
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