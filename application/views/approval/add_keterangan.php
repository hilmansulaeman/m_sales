 
 <?php
	$row = $getRecruitment_id->row(); 
	 
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
		<h3 class="box-title">FORM <?php echo $this->uri->segment(3); ?> </h3>			  
	</div>
	<div class="panel-body">
	<form action="<?php echo base_url(); ?>approval/simpan_keterangan/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>" method="post">
		<div class="row">
			<div class="col col-lg-4 col-xs-4">			 
				<label>NAMA :</label>
				<input type="text" name="keperluan" id="keperluan" value="<?php echo $row->name;?>"  readonly  class="form-control" required/>																			
			</div>
			<div class="col col-lg-4 col-xs-4">				 
				<label>TANGGAL LAHIR :</label>
				<input type="text" name="dob" id="dob"  value="<?php echo $row->dob;?>"  readonly class="form-control" required/>															 
			</div>
			<div class="col col-lg-4 col-xs-4">				 
				<label>JENIS KELAMIN :</label>
				<input type="text" name="dob" id="dob"  value="<?php echo $row->gender;?>" readonly  class="form-control" required/>															 
			</div>
		</div>
		<div class="row">
			<div class="col col-lg-4 col-xs-4">			 
				<label>AGAMA :</label>
				<input type="text" name="keperluan" id="keperluan" value="<?php echo $row->agama;?>"  readonly  class="form-control" required/>																			
			</div>			
			<div class="col col-lg-4 col-xs-8">				 
				<label>ALAMAT :</label>
				<input type="text" name="dob" id="dob" readonly  value="<?php echo $row->alamat;?>,Kel : <?php echo $row->kelurahan;?>,Kec <?php echo $row->kecamatan;?>,Kota <?php echo $row->kota_ktp;?> ,Negara <?php echo $row->bangsa;?>"  class="form-control" required/>															 
			</div>
		</div>
		<div class="row">
			<div class="col col-lg-4 col-xs-4">			 
				<label>STATUS NIKAH :</label>
				<input type="text" name="keperluan" id="keperluan" readonly  value="<?php echo $row->status;?>"   class="form-control" required/>																			
			</div>
			<div class="col col-lg-4 col-xs-4">				 
				<label>POSISI LAPAR :</label>
				<input type="text" name="dob" id="dob" readonly  value="<?php echo $row->posisi_lamar;?>, <?php echo $row->posisi_lamar2;?>"  class="form-control" required/>															 
			</div>
			<div class="col col-lg-4 col-xs-4">				 
				<label>PENGALAMAN :</label>
				<input type="text" name="dob" id="dob"  readonly value="<?php echo $row->pengalaman;?>"  class="form-control" required/>															 
			</div>
		</div>
		<br>
		<div class="form-group">
			<label>Keterangan <?php echo $this->uri->segment(3); ?> :</label>
			<textarea class="form-control" rows="3" placeholder="" name="note"></textarea>
			<span style="color:red; font-size:10px;">* Wajib Diisi</span>
		</div>	 
	</div> 
	<div class="box-footer">
		<button type="submit" class="btn btn-primary pull-right">Submit</button>
	</div> 
	<form>	
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