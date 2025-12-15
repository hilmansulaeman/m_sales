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
		<h3 class="box-title pull-right">
			<a href="<?php echo base_url() ?>asm/print_data/<?php echo $this->uri->segment(3) ?>/<?php echo $this->uri->segment(4) ?>">Print Data</a>
		</h3>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered" id="tableid">
				<thead>
					<tr>
						<th>NO</th>
						<th>TGL INPUT</th>
						<th>NAMA CUSTOMER</th>
						<th>NAMA DSR</th>
						<th>NAMA SPV</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$no = 0;
					foreach ($sql->result() as $row) {
				?>
					<tr>
						<td><?php echo ++$no; ?></td>
						<td><?php echo $row->Tgl_Input; ?></td>
						<td><?php echo $row->Cust_Name; ?></td>
						<td><?php echo $row->Ds_Name; ?></td>
						<td><?php echo $row->Spv_Name; ?></td>
					</tr>
				<?php
					}
				?>
				</tbody>
			</table>
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

function Detail(no)
{
    $('.tr_'+no).toggle()
}

function print_data()
{
	window.print();
}
</script>
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