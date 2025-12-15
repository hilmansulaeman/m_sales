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
							$sql_ls = $this->rsm_model->getApssByAsm($rows->ASM_Code, $tanggal_old);
							if($sql_ls->num_rows() > 0)
							{
								$row_ls = $sql_ls->row();
								$total_inc_ls = $row_ls->Inc_cc + $row_ls->Inc_edc + $row_ls->Inc_sc;
								$total_rts_ls = $row_ls->Rts_cc + $row_ls->Rts_edc + $row_ls->Rts_sc;
								$total_noa_ls = $row_ls->Noa_cc + $row_ls->Noa_edc + $row_ls->Noa_sc;
								$total_dec_ls = $row_ls->Dec_cc + $row_ls->Dec_edc + $row_ls->Dec_sc;
								if($rows->Product == "CC")
								{
									$apprate_ls = $row_ls->Apprate_cc;
									$rating_ls = $row_ls->Rating_cc;
								}elseif($rows->Product == "EDC")
								{
									$apprate_ls = $row_ls->Apprate_edc;
									$rating_ls = $row_ls->Rating_edc;
								}elseif($rows->Product == "SC")
								{
									$apprate_ls = $row_ls->Apprate_sc;
									$rating_ls = $row_ls->Rating_sc;
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

							//realtime
							$sqlReal = $this->rsm_model->getApsRealtime($rows->ASM_Code, $tanggal);
							// if($sqlReal->num_rows() > 0)
							// {
								// $rReal = $sqlReal->row();
								// $totInc = $rReal->inc_cc + $rReal->inc_edc + $rReal->inc_sc;
								// $totRts = $rReal->rts_cc + $rReal->rts_edc + $rReal->rts_sc;
							// }
							// else
							// {
								// $totInc = "-";
								// $totRts = "-";
							// }
							
							$totInc = "-";
							$totRts = "-";

							$sql = $this->rsm_model->getApssByAsm($rows->ASM_Code, $tanggal);
							if($sql->num_rows() > 0)
							{
								$rows2 = $sql->row();
								$noa = $rows2->Noa_cc + $rows2->Noa_edc + $rows2->Noa_sc;
								$dec = $rows2->Dec_cc + $rows2->Dec_edc + $rows2->Dec_sc;
								if($rows->Product == "CC")
								{
									$apprate = $rows2->Apprate_cc;
									$rating = $rows2->Rating_cc;
								}elseif($rows->Product == "EDC")
								{
									$apprate = $rows2->Apprate_edc;
									$rating = $rows2->Rating_edc;
								}elseif($rows->Product == "SC")
								{
									$apprate = $rows2->Apprate_sc;
									$rating = $rows2->Rating_sc;
								}
							}
							else
							{
								$noa = "-";
								$dec = "-";
								$apprate = "-";
								$rating = "-";
							}
					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>ASM</b> <a href="#" onclick="Detail('<?php echo $rows->ASM_Code ?>');"><?php echo $rows->ASM_Name; ?></a></td>
							<td>-</td>
							<td><?php echo $total_inc_ls; ?></td>
							<td><?php echo $total_rts_ls; ?></td>
							<td><?php echo $total_noa_ls; ?></td>
							<td><?php echo $total_dec_ls; ?></td>
							<td><?php echo $apprate_ls; ?></td>
							<td>
							<?php
								if($rating_ls == "Under Perform")
								{
									echo "<span class='label label-danger'>".$rating_ls."</span>";
								}
								elseif($rating_ls == "Acceptable")
								{
									echo "<span class='label label-success'>".$rating_ls."</span>";
								}
								elseif($rating_ls == "Standard" or $rating_ls == "Standar")
								{
									echo "<span class='label label-warning'>".$rating_ls."</span>";
								}
								elseif($rating_ls == "Excellent")
								{
									echo "<span class='label label-primary'>".$rating_ls."</span>";
								}
							?>
							<td><?php echo $totInc; ?></td>
							<td><?php echo $totRts; ?></td>
							<td><?php echo $noa; ?></td>
							<td><?php echo $dec; ?></td>
							<td><?php echo $apprate; ?></td>
							<td>
								<?php
								if($rating == "Under Perform")
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
								elseif($rating == "Excellent")
								{
									echo "<span class='label label-primary'>".$rating."</span>";
								}
							?>
							</td>
						</tr>
					<?php
						$nous = 0;
						$sql_us = $this->rsm_model->getSpvByAsm($rows->ASM_Code);
						foreach ($sql_us->result() as $dt) {
							$sqlLs = $this->rsm_model->getAppsLsAll($dt->SPV_Code, $tanggal_old);
							if($sqlLs->num_rows() > 0)
							{
								$dts = $sqlLs->row();
								$dtinc_ls = $dts->Inc_cc + $dts->Inc_edc + $dts->Inc_sc;
								$dtrts_ls = $dts->Rts_cc + $dts->Rts_edc + $dts->Rts_sc;
								$dtnoa_ls = $dts->Noa_cc + $dts->Noa_edc + $dts->Noa_sc;
								$dtdec_ls = $dts->Dec_cc + $dts->Dec_edc + $dts->Dec_sc;
								if($dt->Product == "CC")
								{
									$dtrating_ls = $dts->Rating_cc;
									$dtapprate_ls = $dts->Apprate_cc;
								}
								elseif($dt->Product == "EDC")
								{
									$dtrating_ls = $dts->Rating_edc;
									$dtapprate_ls = $dts->Apprate_edc;
								}
								elseif($dt->Product == "SC")
								{
									$dtrating_ls = $dts->Rating_sc;
									$dtapprate_ls = $dts->Apprate_sc;
								}
							}
							else
							{
								$dtinc_ls = "-";
								$dtrts_ls = "-";
								$dtnoa_ls = "-";
								$dtdec_ls = "-";
								$dtapprate_ls = "-";
								$dtrating_ls = "-";
							}

							//reals
							$sqlInc = $this->rsm_model->getIncCurrent($dt->SPV_Code, $tanggal);
							if($sqlInc->num_rows() > 0)
							{
								$rInc = $sqlInc->row();
								$totsInc = $rInc->inc_cc + $rInc->inc_edc + $rInc->inc_sc;
								$totsRts = $rInc->rts_cc + $rInc->rts_edc + $rInc->rts_sc;
							}
							else
							{
								$totsInc = 0;
								$totsRts = 0;
							}

							$sqlN = $this->rsm_model->getAppsLsAlls($dt->SPV_Code, $tanggal);
							if($sqlN->num_rows() > 0)
							{
								$dtn = $sqlLs->row();
								$dtinc_n = $dtn->Inc_cc + $dtn->Inc_edc + $dtn->Inc_sc;
								$dtrts_n = $dtn->Rts_cc + $dtn->Rts_edc + $dtn->Rts_sc;
								$dtnoa_n = $dtn->Noa_cc + $dtn->Noa_edc + $dtn->Noa_sc;
								$dtdec_n = $dtn->Dec_cc + $dtn->Dec_edc + $dtn->Dec_sc;
								if($dt->Product == "CC")
								{
									$dtrating_n = $dtn->Rating_cc;
									$dtapprate_n = $dtn->Apprate_cc;
								}
								elseif($dt->Product == "EDC")
								{
									$dtrating_n = $dtn->Rating_edc;
									$dtapprate_n = $dtn->Apprate_edc;
								}
								elseif($dt->Product == "SC")
								{
									$dtrating_n = $dtn->Rating_sc;
									$dtapprate_n = $dtn->Apprate_sc;
								}
							}
							else
							{
								$dtinc_n = "-";
								$dtrts_n = "-";
								$dtnoa_n = "-";
								$dtdec_n = "-";
								$dtapprate_n = "-";
								$dtrating_n = "-";
							}

							//datediff
							$date1 = $dt->Join_Date;
							$date2 = date('Y-m-d');
							$timeStart = strtotime("$date1");
							$timeEnd = strtotime("$date2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diff = $timeEnd-$timeStart;
							// menghitung selisih bulan
							$numBulan = $diff / 86400 / 30;
					?>
						<tr class="tr_<?php echo $rows->ASM_Code; ?>" style="font-size: 11px; display: none">
							<td class="text-right"><?php echo ++$nous; ?></td>
							<td><b>SPV</b> <?php echo $dt->SPV_Name; ?></td>
							<td><?php echo round($numBulan); ?></td>
							<td><?php echo $dtinc_ls; ?></td>
							<td><?php echo $dtrts_ls; ?></td>
							<td><?php echo $dtnoa_ls; ?></td>
							<td><?php echo $dtdec_ls; ?></td>
							<td><?php echo $dtapprate_ls; ?></td>
							<td>
							<?php
								if($dtrating_ls == "Under Perform")
								{
									echo "<span class='label label-danger'>".$dtrating_ls."</span>";
								}
								elseif($dtrating_ls == "Acceptable")
								{
									echo "<span class='label label-success'>".$dtrating_ls."</span>";
								}
								elseif($dtrating_ls == "Standard")
								{
									echo "<span class='label label-warning'>".$dtrating_ls."</span>";
								}
								elseif($dtrating_ls == "Excellent")
								{
									echo "<span class='label label-primary'>".$dtrating_ls."</span>";
								}
							?>
							</td>
							<td><?php echo $totsInc; ?></td>
							<td><?php echo $totsRts; ?></td>
							<td><?php echo $dtnoa_n; ?></td>
							<td><?php echo $dtdec_n; ?></td>
							<td><?php echo $dtapprate_n; ?></td>
							<td><?php echo $dtrating_n ?></td>
						</tr>
					<?php
						}
					?>
					<?php
						}
					?>
					<?php
						$sqlDummySpv = $this->rsm_model->getDummyApsByAsm($this->uri->segment(3),$tanggal_old);
						if($sqlDummySpv->num_rows() > 0 )
						{
							$dtDum = $sqlDummySpv->row();
							$dum_inc = $dtDum->Inc_cc + $dtDum->Inc_edc + $dtDum->Inc_sc;
							$dum_rts = $dtDum->Rts_cc + $dtDum->Rts_edc + $dtDum->Rts_sc;
							$dum_noa = $dtDum->Noa_cc + $dtDum->Noa_edc + $dtDum->Noa_sc;
							$dum_dec = $dtDum->Dec_cc + $dtDum->Dec_edc + $dtDum->Dec_sc;
						}
						else
						{
							$dum_inc = "-";
							$dum_rts = "-";
							$dum_noa = "-";
							$dum_dec = "-";
						}

						//reals 
						$sqlDummySpvCur = $this->rsm_model->getDummyCurrently($this->uri->segment(3),$tanggal);
						if($sqlDummySpvCur->num_rows() > 0)
						{
							$dtCrnly = $sqlDummySpvCur->row();
							$inc_cr = $dtCrnly->inc_cc + $dtCrnly->inc_edc + $dtCrnly->inc_sc;
							$rts_cr = $dtCrnly->rts_cc + $dtCrnly->rts_edc + $dtCrnly->rts_sc;
						}
						else
						{
							$inc_cr = "-";
							$rts_cr = "-";
						}

						$sqlDummySpv2 = $this->rsm_model->getDummyApsByAsm($this->uri->segment(3),$tanggal);
						if($sqlDummySpv2->num_rows() > 0 )
						{
							$dtDum2 = $sqlDummySpv2->row();
							$dum_inc2 = $dtDum2->Inc_cc + $dtDum2->Inc_edc + $dtDum2->Inc_sc;
							$dum_rts2 = $dtDum2->Rts_cc + $dtDum2->Rts_edc + $dtDum2->Rts_sc;
							$dum_noa2 = $dtDum2->Noa_cc + $dtDum2->Noa_edc + $dtDum2->Noa_sc;
							$dum_dec2 = $dtDum2->Dec_cc + $dtDum2->Dec_edc + $dtDum2->Dec_sc;
						}
						else
						{
							$dum_inc2 = "-";
							$dum_rts2 = "-";
							$dum_noa2 = "-";
							$dum_dec2 = "-";
						}
					?>
						<tr class="tr2_<?php echo $rows->ASM_Code; ?>" style="font-size: 11px; display: none;">
							<td class="text-right"><?php echo ++$nous; ?></td>
							<td><b>SPV</b> DUMMY SPV</td>
							<td>-</td>
							<td><?php echo $dum_inc; ?></td>
							<td><?php echo $dum_rts; ?></td>
							<td><?php echo $dum_noa; ?></td>
							<td><?php echo $dum_dec; ?></td>
							<td></td>
							<td></td>
							<td><?php echo $inc_cr; ?></td>
							<td><?php echo $rts_cr; ?></td>
							<td><?php echo $dum_noa2; ?></td>
							<td><?php echo $dum_dec2; ?></td>
							<td></td>
							<td></td>
						</tr>
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