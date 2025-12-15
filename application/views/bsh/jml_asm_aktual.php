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

							//realtime
							/*$sqlReal = $this->bsh_model->getApsRealtime($row->ASM_Code, $tgl_now);
							$rReal = $sqlReal->row();
							$totInc = $rReal->inc_cc + $rReal->inc_edc + $rReal->inc_sc;
							$totRts = $rReal->rts_cc + $rReal->rts_edc + $rReal->rts_sc;*/

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

							//datediff
							$date1 = $row->Efektif_Date;
							if($date1 == "0000-00-00")
							{
								$date1 = $row->Join_Date;
							}
							$date2 = date('Y-m-d');
							$timeStart = strtotime("$date1");
							$timeEnd = strtotime("$date2");
							// Menambah bulan ini + semua bulan pada tahun sebelumnya
							$diff = $timeEnd-$timeStart;
							// menghitung selisih bulan
							$numBulan = $diff / 86400 / 30;

					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>ASM </b><a href="#" onclick="Detail('<?php echo $row->ASM_Code; ?>');"><?php echo $row->ASM_Name; ?></a></td>
							<td><?php echo round($numBulan); ?></td>
							<td><?php echo $row->totalnya; ?></td>
							<td><?php echo $total_inc_ls; ?></td>
							<td><?php echo $total_rts_ls; ?></td>
							<td><?php echo $total_noa_ls; ?></td>
							<td><?php echo $total_dec_ls; ?></td>
							<td><?php echo $apprate_ls; ?></td>
							<td>
							<?php
								if($rating_ls == "Under Perform" OR $rating_ls == "Under Perform 1" OR $rating_ls == "Under Perform 2" OR $rating_ls == "Warning" OR $rating_ls == "Zero Account")
								{
									echo "<span class='label label-danger'>".$rating_ls."</span>";
								}
								elseif($rating_ls == "Acceptable" OR $rating_ls == "Acceptable 1" OR $rating_ls == "Acceptable 1")
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
							<td><?php echo $apprate; ?></td>
							<td>
							<?php
								if($rating == "Under Perform" OR $rating == "Under Perform 1" OR $rating == "Under Perform 2" OR $rating == "Warning" OR $rating == "Zero Account")
								{
									echo "<span class='label label-danger'>".$rating."</span>";
								}
								elseif($rating == "Acceptable" OR $rating == "Acceptable 1" OR $rating == "Acceptable 2")
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

						<!--Nax-->

						<?php
							$nop = 0;
							$sql_us = $this->bsh_model->getSpvByAsm($row->ASM_Code);
							foreach ($sql_us->result() as $dt) {
								$sqlLs = $this->bsh_model->getAppsLsAll($dt->SPV_Code, $tgl_old);
								if($sqlLs->num_rows() > 0)
								{
									$dts = $sqlLs->row();
									$dtinc_ls = $dts->Inc_cc + $dts->Inc_edc + $dts->Inc_sc;
									$dtrts_ls = $dts->Rts_cc + $dts->Rts_edc + $dts->Rts_sc;
									$dtnoa_ls = $dts->Noa_cc + $dts->Noa_edc + $dts->Noa_sc;
									$dtdec_ls = $dts->Dec_cc + $dts->Dec_edc + $dts->Dec_sc;
									$dtrating_ls = $dts->Ratings;
									if($dt->Product == "CC")
									{
										$dtapprate_ls = $dts->Apprate_cc;
									}
									elseif($dt->Product == "EDC")
									{
										$dtapprate_ls = $dts->Apprate_edc;
									}
									elseif($dt->Product == "SC")
									{
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
								/*$sqlInc = $this->bsh_model->getIncCurrent($dt->SPV_Code, $tgl_now);
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
								}*/

								$sqlN = $this->bsh_model->getAppsLsAlls($dt->SPV_Code, $tgl_now);
								if($sqlN->num_rows() > 0)
								{
									$dtn = $sqlN->row();
									$dtinc_n = $dtn->Inc_cc + $dtn->Inc_edc + $dtn->Inc_sc;
									$dtrts_n = $dtn->Rts_cc + $dtn->Rts_edc + $dtn->Rts_sc;
									$dtnoa_n = $dtn->Noa_cc + $dtn->Noa_edc + $dtn->Noa_sc;
									$dtdec_n = $dtn->Dec_cc + $dtn->Dec_edc + $dtn->Dec_sc;
									$dtrating_n = $dtn->Ratings;
									if($dt->Product == "CC")
									{
										$dtapprate_n = $dtn->Apprate_cc;
									}
									elseif($dt->Product == "EDC")
									{
										$dtapprate_n = $dtn->Apprate_edc;
									}
									elseif($dt->Product == "SC")
									{
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
								$date1_ = $dt->Efektif_Date;
								if($date1_ == "0000-00-00")
								{
									$date1_ = $dt->Join_Date;
								}
								$date2_ = date('Y-m-d');
								$timeStart_ = strtotime("$date1");
								$timeEnd_ = strtotime("$date2");
								// Menambah bulan ini + semua bulan pada tahun sebelumnya
								$diff_ = $timeEnd_-$timeStart_;
								// menghitung selisih bulan
								$numBulan_ = $diff_ / 86400 / 30;


						?>
							<tr class="tr_<?php echo $row->ASM_Code; ?>" style="font-size: 11px; display: none;">
								<td class="text-right"><?php echo ++$nop; ?></td>
								<td class="text-right"><b>SPV</b> <?php echo $dt->SPV_Name; ?></td>
								<td class="text-right"><?php echo round($numBulan_); ?></td>
								<td class="text-right">-</td>
								<td><?php echo $dtinc_ls; ?></td>
								<td><?php echo $dtrts_ls; ?></td>
								<td><?php echo $dtnoa_ls; ?></td>
								<td><?php echo $dtdec_ls; ?></td>
								<td><?php echo $dtapprate_ls; ?></td>
								<td>
								<?php
									if($dtrating_ls == "Under Perform" OR $dtrating_ls == "Under Perform 1" OR $dtrating_ls == "Under Perform 2" OR $dtrating_ls == "Warning" OR $dtrating_ls == "Zero Account")
									{
										echo "<span class='label label-danger'>".$dtrating_ls."</span>";
									}
									elseif($dtrating_ls == "Acceptable" OR $dtrating_ls == "Acceptable 1" OR $dtrating_ls == "Acceptable 2")
									{
										echo "<span class='label label-success'>".$dtrating_ls."</span>";
									}
									elseif($dtrating_ls == "Standard" OR $dtrating_ls == "Standar")
									{
										echo "<span class='label label-warning'>".$dtrating_ls."</span>";
									}
									elseif($dtrating_ls == "Excellent" OR $dtrating_ls == "Excellent 1" OR $dtrating_ls == "Excellent 2")
									{
										echo "<span class='label label-primary'>".$dtrating_ls."</span>";
									}
								?>
								</td>
								<td><?php echo $dtinc_n; ?></td>
								<td><?php echo $dtrts_n; ?></td>
								<td><?php echo $dtnoa_n; ?></td>
								<td><?php echo $dtdec_n; ?></td>
								<td><?php echo $dtapprate_n; ?></td>
								<td>
								<?php
									if($dtrating_n == "Under Perform" OR $dtrating_n == "Under Perform 1" OR $dtrating_n == "Under Perform 2" OR $dtrating_n == "Warning" OR $dtrating_n == "Zero Account")
									{
										echo "<span class='label label-danger'>".$dtrating_n."</span>";
									}
									elseif($dtrating_n == "Acceptable" OR $dtrating_n == "Acceptable 1" OR $dtrating_n == "Acceptable 2")
									{
										echo "<span class='label label-success'>".$dtrating_n."</span>";
									}
									elseif($dtrating_n == "Standard" OR $dtrating_n == "Standar")
									{
										echo "<span class='label label-warning'>".$dtrating_n."</span>";
									}
									elseif($dtrating_n == "Excellent" OR $dtrating_n == "Excellent 1" OR $dtrating_n == "Excellent 2")
									{
										echo "<span class='label label-primary'>".$dtrating_n."</span>";
									}
								?>
								</td>
							</tr>
						<?php
								}
						?>

						<?php
							$sqlDum = $this->bsh_model->DummySpv($dt->SPV_Code, $tgl_old);
							if($sqlDum->num_rows() > 0)
							{
								$dtDum = $sqlDum->row();
								$inc_old = $dtDum->inc;
								$rts_old = $dtDum->rts;
								$noa_old = $dtDum->noa;
								$dec_old = $dtDum->decl;
							}
							else
							{
								$inc_old = 0;
								$rts_old = 0;
								$noa_old = 0;
								$dec_old = 0;
							}

							//reals
							/*$qryReal = $this->bsh_model->DummySpvReal($row->ASM_Code, $tgl_now);
							if($qryReal->num_rows > 0)
							{
								$rRs = $qryReal->row();
								$dtInc = $rRs->inc_cc + $rRs->inc_edc + $rRs->inc_sc;
								$dtRts = $rRs->rts_cc + $rRs->rts_edc + $rRs->rts_sc;
							}*/

							$sqlDum2 = $this->bsh_model->DummySpv($dt->SPV_Code, $tgl_now);
							if($sqlDum2->num_rows() > 0)
							{
								$dtDum2 = $sqlDum2->row();
								$inc_now = $dtDum2->inc;
								$rts_now = $dtDum2->rts;
								$noa_now = $dtDum2->noa;
								$dec_now = $dtDum2->decl;
							}
							else
							{
								$inc_now = 0;
								$rts_now = 0;
								$noa_now = 0;
								$dec_now = 0;
							}
						?>
							<tr class="tr2_<?php echo $row->ASM_Code; ?>" style="font-size: 11px; display: none;">
								<td class="text-right"><?php echo ++$nop ?></td>
								<td class="text-right"><b>SPV</b> DUMMY SPV</td>
								<td></td>
								<td></td>
								<td><?php echo $inc_old; ?></td>
								<td><?php echo $rts_old; ?></td>
								<td><?php echo $noa_old; ?></td>
								<td><?php echo $dec_old; ?></td>
								<td>-</td>
								<td>-</td>
								<td><?php echo $inc_now; ?></td>
								<td><?php echo $rts_now; ?></td>
								<td><?php echo $noa_now; ?></td>
								<td><?php echo $dec_now; ?></td>
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