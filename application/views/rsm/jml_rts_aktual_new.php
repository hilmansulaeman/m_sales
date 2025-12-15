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
						$jml = 0;
						$jml_inc = 0;
						foreach ($query->result() as $data) {
							
							$sql_data = $this->rsm_model->getDataRtsNew($data->ASM_Code, $tanggal);
							if($sql_data->num_rows() > 0)
							{
								$dt = $sql_data->row();
								$inc = $dt->Inc_cc + $dt->Inc_edc + $dt->Inc_sc;
								$rts = $dt->Rts_cc + $dt->Rts_edc + $dt->Rts_sc;
							}
							else
							{
								$inc = 0;
								$rts = 0;
							}
							
							if($inc > 0 AND $rts > 0)
							{
								$persen = ($rts / $inc) * 100;
							}
							else
							{
								$persen = 0;
							}
							
							//datediff
							$date1 = $data->Efektif_Date;
							if($date1 == "0000-00-00")
							{
								$tglnya = $data->Join_Date;
							}
							else
							{
								$tglnya = $date1;
							}
							$date2 = date('Y-m-d');
							$timeStart = strtotime("$tglnya");
							$timeEnd = strtotime("$date2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diff = $timeEnd-$timeStart;
							// menghitung selisih bulan
							$numBulan = $diff / 86400 / 30;
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>ASM</b> <?php echo $data->ASM_Name; ?></td>
							<td><?php echo round($numBulan); ?></td>
							<td><?php echo $inc; ?></td>
							<td><a href="#" onclick="Detail('<?php echo $data->ASM_Code ?>')"><?php echo $rts; ?></a></td>
							<td><?php echo round($persen); ?> %</td>
						</tr>
						<?php
							$no_spv = 0;
							$sql_spv = $this->rsm_model->getSpvByAsm($data->ASM_Code);
							foreach($sql_spv->result() as $spv)
							{
								//datediff
								$vartgl = $spv->Efektif_Date;
								if($vartgl == "0000-00-00")
								{
									$vartgl1 = $spv->Join_Date;
								}
								else
								{
									$vartgl1 = $vartgl;
								}
								$vartgl2 = date('Y-m-d');
								$varStart = strtotime("$vartgl1");
								$varEnd = strtotime("$vartgl2");
								// Menambah bulan ini + semua bulan pada tahun sebelumnya
								$var_diff = $varEnd-$varStart;
								// menghitung selisih bulan
								$var_mob = $var_diff / 86400 / 30;
								
								//angka
								
								$query_spv = $this->rsm_model->getDataRtsBySpv($spv->SPV_Code, $tanggal);
								if($query_spv->num_rows() > 0)
								{
									$rw = $query_spv->row();
									$inc_spv = $rw->Inc_cc + $rw->Inc_edc + $rw->Inc_sc;
									$rts_spv = $rw->Rts_cc + $rw->Rts_edc + $rw->Rts_sc;
								}
								else
								{
									$inc_spv = 0;
									$rts_spv = 0;
								}
								if($inc_spv > 0 AND $rts_spv > 0)
								{
									$persen2 = ($rts_spv / $inc_spv) * 100;
								}
								else
								{
									$persen2 = 0;
								}
							
						?>
							<tr style="font-size:11px; display:none;" class="tr_<?php echo $data->ASM_Code; ?>">
								<td align="right"><?php echo ++$no_spv; ?></td>
								<td><b>SPV</b> <?php echo $spv->SPV_Name; ?></td>
								<td><?php echo round($var_mob); ?></td>
								<td><?php echo $inc_spv; ?></td>
								<td><?php echo $rts_spv; ?></td>
								<td><?php echo round($persen2) ?> %</td>
							</tr>
						<?php
							}
						?>
						<?php
							$rts_dum = $this->rsm_model->DataRtsDummy($data->ASM_Code, $tanggal);
							if($rts_dum->num_rows() > 0)
							{
								$dm = $rts_dum->row();
								$inc_dum = $dm->inc;
								$rts_dum = $dm->rts;
							}
							else
							{
								$inc_dum = 0;
								$rts_dum = 0;
							}
							if($inc_dum > 0 AND $rts_dum > 0)
							{
								$hitung = ($rts_dum / $inc_dum) * 100;
							}
							else
							{
								$hitung = 0;
							}
						?>
							<tr style="font-size:11px; display:none;" class="tr2_<?php echo $data->ASM_Code; ?>">
								<td align="right"><?php echo ++$no_spv; ?></td>
								<td>DUMMY SPV</td>
								<td>-</td>
								<td><?php echo $inc_dum; ?></td>
								<td><?php echo $rts_dum; ?></td>
								<td><?php echo round($hitung); ?> %</td>
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
    $('.tr2_'+dsr_code).toggle();
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