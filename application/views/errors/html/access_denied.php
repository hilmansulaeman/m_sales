<?php if (! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="pace-top" style="background-color: black;color: white;">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show"><span class="spinner"></span></div>
	<!-- end #page-loader -->

	<div class="w3-display-middle">
		<h1 style="color: red;" class="w3-jumbo w3-animate-top w3-center"><code>Access Denied</code></h1>
		<hr class="w3-border-white w3-animate-left" style="margin:auto;width:50%">
		<h3 class="w3-center w3-animate-right">You dont have permission to view this page !!</h3>
		<h3 class="w3-center w3-animate-zoom">🚫🚫🚫🚫</h3>
		<h6 style="color: red;" class="w3-center w3-animate-zoom"><?= $error_message2;?></h6>
		<a href="<?= site_url('dashboard');?>">
			<h3 class="w3-center w3-animate-right">
				<input type="button" class="btn btn-warning" value="Go Home">
			</h3>
		</a>
	</div>
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="new_assets/plugins/jquery/jquery-3.3.1.min.js"></script>
	<script src="new_assets/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="new_assets/plugins/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
	<!--[if lt IE 9]>
		<script src="new_assets/crossbrowserjs/html5shiv.js"></script>
		<script src="new_assets/crossbrowserjs/respond.min.js"></script>
		<script src="new_assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="new_assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="new_assets/plugins/js-cookie/js.cookie.js"></script>
	<script src="new_assets/js/theme/default.min.js"></script>
	<script src="new_assets/js/apps.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();
		});
	</script>
</body>
</html>