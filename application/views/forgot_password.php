<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta name="robots" content="noindex">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title><?php echo $title; ?></title>

	<!-- Bootstrap Core CSS -->
	<link href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- MetisMenu CSS -->
	<link href="<?php echo base_url(); ?>public/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="<?php echo base_url(); ?>public/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet"
		type="text/css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>

<body>
	<br>
	<br>
	<div class="container">
		<div id="dialog" class="dialog dialog-effect-in">
			<div class="dialog-front">
				<div class="dialog-content">
					<table align="center">
						<tr>
							<td>&nbsp;</td>
							<td align="center">
								<img align="center" src="<?php echo base_url(); ?>public/images/logo-dika1.png"
									width="80%" />
							</td>
							<td>&nbsp;</td>
						</tr>
					</table>

					<div class="well" style="background:#FFFFFF">
						<?php
						// just for info
						if ($this->session->flashdata('login_info')) {
							echo $this->session->flashdata('login_info');
						}
						else{
						?>
						<form class="login-form" role="form" action="" method="post">
						    <div class="form-group">
								<label for="email" style="color:#999999;" class="control-label">Silahkan masukkan email anda yang digunakan saat melamar di PT DIKA:</label>
								<input type="text" name="email" value="<?= set_value('email') ?>" style="color:#999999;" class="form-control" placeholder="Alamat Email" autofocus required />
								<?php echo form_error('email'); ?>
							</div>
							<div class="pad-top-20 pad-btm-20">
								<input type="submit" style="background: linear-gradient(#0099FF, #2C4257),#FFFFFF;"
									class="btn btn-primary btn-block btn-lg" value="Reset">
							</div>
						</form>
						<?php } ?>
						<a href="<?= site_url('login') ?>"><h5>Login</h5></a>
					</div>
			
				</div>
			</div>
		</div>
	</div>

	<!-- jQuery -->
	<script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

	<!-- Metis Menu Plugin JavaScript -->
	<script src="<?php echo base_url(); ?>public/bower_components/metisMenu/dist/metisMenu.min.js"></script>

	<!-- Custom Theme JavaScript -->
	<script src="<?php echo base_url(); ?>public/login/log_in.js"></script>

</body>

</html>