<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Form Ubah Password</title>

     <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>public/mytemplate/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="<?php echo base_url() ?>public/mytemplate/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/mytemplate/dist/css/AdminLTE.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/mytemplate/dist/css/skins/_all-skins.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/mytemplate/plugins/iCheck/flat/blue.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/mytemplate/plugins/morris/morris.css" rel="stylesheet">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/fancybox/dist/jquery.fancybox.min.css">



</head>

<body class="hold-transition register-page" onload="myFunction();">
    <div class="register-box">
	  <div class="register-logo">
		<a href="../../index2.html"><b>DIKA</b> Sales Monitoring</a>
	  </div>

	  <div class="register-box-body">
		<p class="login-box-msg">Form Ubah Password</p>
		<form method="post">
		  <div class="form-group has-feedback">
			<input type="text" name="sales_code" class="form-control" placeholder="Sales Code">
			<span class="glyphicon glyphicon-user form-control-feedback"></span>
			<?php echo form_error('sales_code'); ?>
		  </div>
		  <div class="form-group has-feedback">
			<input type="password" name="password" class="form-control" placeholder="Password">
			<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
			<?php echo form_error('password'); ?>
		  </div>
		  <div class="form-group has-feedback">
			<input type="password" name="retype_password" class="form-control" placeholder="Retype Password">
			<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
			<?php echo form_error('retype_password'); ?>
		  </div>
		  <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
		</form>
	  </div>
	  <!-- /.form-box -->
	</div>
	<!-- /.register-box -->
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="<?php echo base_url() ?>public/mytemplate/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>public/mytemplate/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url() ?>public/mytemplate/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    
    
    <!-- Morris.js charts -->
    <!-- InputMask -->
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <!-- Select2 -->
    <script src="<?php echo base_url(); ?>public/mytemplate/plugins/select2/select2.full.min.js"></script>
    <!-- Sparkline -->
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url() ?>public/mytemplate/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url() ?>public/mytemplate/dist/js/demo.js"></script>
    <script src="<?php echo base_url() ?>public/mytemplate/fancybox/dist/jquery.fancybox.min.js"></script>
	<script>
		function myFunction()
		{
			$('#modalInfo').modal('show');
		}
	</script>
<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-info"></i> INFORMASI</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<h4>Demi keamanan sistem silahkan untuk merubah password, lalu login menggunakan <b>Sales Code dan Password</b> yang telah didaftarkan. <br><br> <p style='color:red; text-transform: uppercase;'>Pastikan Untuk Mengingat Password yang baru! <p> <br><br> <b>Terima Kasih</b></h4>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>
