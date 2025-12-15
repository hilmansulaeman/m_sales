<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sales Monitoring</title>
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/bootstrap/css/bootstrap.min.css">	
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/dist/css/skins/_all-skins.min.css">
	<!--DataTable-->
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/dataTables.jqueryui.min.css">
	<!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<section class="content">
  <div class="box box-primary">
		<div class="box-header with-border">
			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>		
			<h3 class="box-title">Pilih Sales</h3>
		</div>
		<div class="panel-body">
				<table class="table table-hover" style="font-size:10px !important;">
					<thead>
						<th>Name</th>
						<th>Actions</th>
					</thead>
					<tbody>
					<?php
					
					foreach($query->result() as $row)
					{
						$rms_code = $row->DSR_Code;
					?>
						<tr>
							<td><b>RSM</b> <?php echo $row->Name; ?></td>
							<td>
								<a href="javascript:void(0)" onclick="return pilihSalesRsm('<?php echo $rms_code ?>', 'RSM')" class="btn btn-primary btn-xs">Pilih <i class="fa fa-chevron-down"></i></a>
							</td>
						</tr>
						
							<?php
							$sqlAsm = $this->db->query("SELECT DSR_Code as asm_code, Name as namaAsm FROM `internal`.`data_sales_structure` WHERE RSM_Code='$rms_code' AND Position='ASM' ORDER BY namaAsm ASC");
							foreach($sqlAsm->result() as $rowAsm)
							{
								$asm_code = $rowAsm->asm_code;
							?>
							<tr>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ASM</b> <?php echo $rowAsm->namaAsm; ?></td>
								<td>
									<a href="javascript:void(0)" onclick="return pilihSalesAsm('<?php echo $asm_code ?>', 'ASM')" class="btn btn-primary btn-xs">Pilih <i class="fa fa-chevron-down"></i></a>
								</td>
							</tr>
							
								<?php
								$sql_spv = $this->db->query("SELECT DSR_Code as spv_code, Name as namaSpv FROM `internal`.`data_sales_structure` WHERE ASM_Code='$asm_code' AND Position='SPV' ORDER BY namaSpv ASC");
								foreach($sql_spv->result() as $rowSpv)
								{
									$spv_code = $rowSpv->spv_code;
								?>
								<tr>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>SPV</b> <?php echo $rowSpv->namaSpv; ?></td>
									<td>
										<a href="javascript:void(0)" onclick="return pilihSalesSpv('<?php echo $spv_code; ?>', 'SPV')" class="btn btn-primary btn-xs">Pilih <i class="fa fa-chevron-down"></i></a>
									</td>
								</tr>
									<?php
									$sql_ds = $this->db->query("SELECT DSR_Code as ds_code, Name as namaDs FROM `internal`.`data_sales_structure` WHERE SPV_Code='$spv_code' AND Position IN('DSR', 'SPG', 'SPB') ORDER BY namaDs ASC");
									foreach($sql_ds->result() as $riwDs)
									{
										$dsr_code = $riwDs->ds_code;
									?>
									<tr>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>DSR</b> <?php echo $riwDs->namaDs; ?></td>
										<td>
											<a href="javascript:void(0)" onclick="return pilihSalesDs('<?php echo $dsr_code ?>', 'DSR')" class="btn btn-primary btn-xs">Pilih <i class="fa fa-chevron-down"></i></a>
										</td>
									</tr>
									<?php
									}
									?>
								<?php
								}
								?>
							
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
	</section>
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url(); ?>public/mytemplate/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>public/mytemplate/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url(); ?>public/mytemplate/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables/media/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
</body>
</html>
<script type="text/javascript">
$(document).ready(function() {
        $('#example2').DataTable({
				"paging" : false,
                "label" : false
        });
    });
function pilihSalesRsm(id, posisi)
{
	parent.parent.location.href='<?php echo base_url() ?>team_performance/lihat_performance/' + id +'/' + posisi; 
}
function pilihSalesAsm(id, posisi)
{
	parent.parent.location.href='<?php echo base_url() ?>team_performance/lihat_performance/' + id +'/' + posisi; 
}
function pilihSalesSpv(id, posisi)
{
	parent.parent.location.href='<?php echo base_url() ?>team_performance/lihat_performance/' + id +'/' + posisi; 
}
function pilihSalesDs(id, posisi)
{
	parent.parent.location.href='<?php echo base_url() ?>team_performance/lihat_performance/' + id +'/' + posisi; 
}
</script>