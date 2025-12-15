<?php if (! defined('BASEPATH')) exit('No direct script access allowed');?>
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
		echo "<span class='label label-success'>".$rating."</span>";
	}
	elseif(in_array($rating, $standard)){
		echo "<span class='label label-warning'>".$rating."</span>";
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
						$asm_code = $this->session->userdata('sl_code');
						$totDsr = 0;
						foreach ($query->result() as $val) {
							$sqlLs = $this->asm_model->getAppsLsAll($val->SPV_Code, $tanggal_old);
							if($sqlLs->num_rows() > 0)
							{
								$rows = $sqlLs->row();
								$inc_ls = $rows->Inc_cc + $rows->Inc_edc + $rows->Inc_sc;
								$rts_ls = $rows->Rts_cc + $rows->Rts_edc + $rows->Rts_sc;
								$noa_ls = $rows->Noa_cc + $rows->Noa_edc + $rows->Noa_sc;
								$dec_ls = $rows->Dec_cc + $rows->Dec_edc + $rows->Dec_sc;
								//total m-1
								
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

							$sql = $this->asm_model->getAppsLsAll($val->SPV_Code, $tanggal);
							if($sql->num_rows() > 0)
							{
								$row = $sql->row();
								$inc = $row->Inc_cc + $row->Inc_edc + $row->Inc_sc;
								$rts = $row->Rts_cc + $row->Rts_edc + $row->Rts_sc;
								$noa = $row->Noa_cc + $row->Noa_edc + $row->Noa_sc;
								$dec = $row->Dec_cc + $row->Dec_edc + $row->Dec_sc;
								//total current month
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
							}
							
							//datediff
							$tanggal_1 = $val->Efektif_Date;
							if($tanggal_1 == "0000-00-00")
							{
								$tanggal_11 = $val->Join_Date;
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

					?>
						<tr>
							<td><?php echo ++$no; ?></td>
							<td><b>SPV</b> <a href="#" onClick="Detail('<?php echo $val->SPV_Code; ?>')"><?php echo $val->SPV_Name; ?></a></td>
							<td><?php echo round($mob1); ?></td>
							<td><?php echo $val->totalDsr; ?></td>
							<td><?php echo $inc_ls; ?></td>
							<td><?php echo $rts_ls; ?></td>
							<td><?php echo $noa_ls; ?></td>
							<td><?php echo $dec_ls; ?></td>
							<td><?php echo round($apprate_ls); ?> %</td>
							<td><?php RatingColour($rating_ls); ?></td>
							<td><?php echo $inc; ?></td>
							<td><?php echo $rts; ?></td>
							<td><?php echo $noa; ?></td>
							<td><?php echo $dec; ?></td>
							<td><?php echo round($apprate); ?> %</td>
							<td><?php RatingColour($ratings); ?></td>
						</tr>

						<!--BreakDown-->

						<tr style="display: none;" class="tr_<?php echo $val->SPV_Code; ?>">
							<td colspan="16" style="background-color: #CFCECD;">
								<table class="table table-bordered" style="font-size: 11px;">
									<tbody>
										<tr>
											<td></td>
											<td></td>
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
											<td>2</td>
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
											<td>3</td>
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
											<td></td>
											<td></td>
											<td></td>
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
											<td style="width: 200px;">DSR Name</td>
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
											$sql_ds = $this->asm_model->getDsrBySpv($val->SPV_Code);
											foreach ($sql_ds->result() as $ds) {

												//Last Mont
												$angkads_ls = $this->asm_model->getAppsLsAllDsr($ds->DSR_Code, $tanggal_old);
												if($angkads_ls->num_rows() > 0)
												{
													$ls = $angkads_ls->row();
													$inc_ls = $ls->Inc_cc + $ls->Inc_edc + $ls->Inc_sc;
													$rts_ls = $ls->Rts_cc + $ls->Rts_edc + $ls->Rts_sc;
													$noa_ls = $ls->Noa_cc + $ls->Noa_edc + $ls->Noa_sc;
													$dec_ls = $ls->Dec_cc + $ls->Dec_edc + $ls->Dec_sc;
													$ratingds_ls = $ls->ratings;
													if($ds->Product == "CC")
													{
														$apprate_ls = $ls->Apprate_cc." %";
													}elseif($ds->Product == "EDC")
													{
														$apprate_ls = $ls->Apprate_edc." %";
													}elseif($ds->Product == "SC")
													{
														$apprate_ls = $ls->Apprate_sc." %";
													}
												}
												else
												{
													$inc_ls = "-";
													$rts_ls = "-";
													$noa_ls = "-";
													$dec_ls = "-";
													$ratingds_ls = "-";
													$apprate_ls = "-";
												}

												//Current
												$angkads = $this->asm_model->getAppsLsAllDsr($ds->DSR_Code, $tanggal);
												if($angkads->num_rows() > 0)
												{
													$now = $angkads->row();
													$inc = $now->Inc_cc + $now->Inc_edc + $now->Inc_sc;
													$rts = $now->Rts_cc + $now->Rts_edc + $now->Rts_sc;
													$noa = $now->Noa_cc + $now->Noa_edc + $now->Noa_sc;
													$dec = $now->Dec_cc + $now->Dec_edc + $now->Dec_sc;
													$ratingds = $now->ratings;
													if($ds->Product == "CC")
													{
														$apprate = $now->Apprate_cc." %";
													}elseif($ds->Product == "EDC")
													{
														$apprate = $now->Apprate_edc." %";
													}elseif($ds->Product == "SC")
													{
														$apprate = $now->Apprate_sc." %";
													}
												}
												else
												{
													$inc = "-";
													$rts = "-";
													$noa = "-";
													$dec = "-";
													$ratingds = "-";
													$apprate = "-";
												}

												//Mob
												$tgl_1 = $ds->Efektif_Date;
												if($tgl_1 == "0000-00-00")
												{
													$tgl_1_ = $ds->Join_Date;
												}
												else
												{
													$tgl_1_ = $tgl_1;
												}
												$tgl_2 = date('Y-m-d');
												$x_date = strtotime("$tgl_1_");
												$y_date = strtotime("$tgl_2");
												// Menambah bulan ini + semua bulan pada tahun sebelumnya
												$hitung_mob = $y_date-$x_date;
												// menghitung selisih bulan
												$mob = $hitung_mob / 86400 / 30;
										?>
										<tr>
											<td><?php echo ++$no_ds; ?></td>
											<td><?php echo $ds->Name; ?></td>
											<td><?php echo round($mob); ?></td>
											<td><?php echo $inc_ls; ?></td>
											<td><?php echo $rts_ls; ?></td>
											<td><?php echo $noa_ls; ?></td>
											<td><?php echo $dec_ls; ?></td>
											<td><?php echo $apprate_ls; ?></td>
											<td><?php RatingColour($ratingds_ls); ?></td>
											<td><?php echo $inc; ?></td>
											<td><?php echo $rts; ?></td>
											<td><?php echo $noa; ?></td>
											<td><?php echo $dec; ?></td>
											<td><?php echo $apprate; ?></td>
											<td><?php RatingColour($ratingds); ?></td>
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
					?></tbody>
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
function Detail(dsr_code)
{
    $('.tr_'+dsr_code).toggle()
}
function Detail2()
{
	$('.dummy').toggle()
}
</script>