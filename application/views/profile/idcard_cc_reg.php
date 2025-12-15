<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ID Card</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/plugins/iCheck/square/blue.css">
  <!-- Slider -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/plugins/slider/jquery.bbslider.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <style>
		.logo{
			width: 100%;
			height: 100%;
			margin-top:-10px;
		}
		
		.photo{
			/*width: 180px;
			height: 235px;*/
			margin-top:10px;
			width: 80%;
			height: 80%;
			box-shadow: none;
			background-color:#FFCC00;
			/* background: url('<?php echo base_url(); ?>public/images/idcard/bg-foto.png'); */
		}
		
		.runningtext{
			font-family: "Times New Roman", Times, serif;
			font-size:21px;
			margin-top:15px;
			color:#000;
		}
		
		.stamp{
			width: 30%;
			height: 30%;
			margin-top:-95px;
			margin-left:25px;
		}
		
		.qr{
			width: 100%;
			height: 100%;
			box-shadow: none;
			/*margin-top:110px;*/
		}
		
		.nama{
			font-family: "Times New Roman", Times, serif;
			font-size:22px;
			/*margin-top:-15px;*/
			color:#000;
		}
		
		.sales_code{
			font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif;
			font-size:20px;
			/*margin-top:-10px;*/
			color:#000;
		}
		
		.tanggal{
			font-family: "Times New Roman", Times, serif;
			font-size:22px;
			/*margin-top:-10px;*/
		}
		
		.blink {
		  animation: blink-animation 1s steps(5, start) infinite;
		  -webkit-animation: blink-animation 1s steps(5, start) infinite;
		  color:blue;
		}
		@keyframes blink-animation {
		  to {
			visibility: hidden;
		  }
		}
		@-webkit-keyframes blink-animation {
		  to {
			visibility: hidden;
		  }
		}
	</style>
</head>
<body class="hold-transition login-page" id="box-id">
<div class="login-box" style="width:100%; height:100%">
  <div class="login-box-body">
      <?php
		//Format Tanggal
		$tanggal = date('d');
		//Array Bulan
		$array_bulan = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		$bulan = $array_bulan[date('n')];
		//Format Tahun
		$tahun = date('Y');
		$date = $tanggal.' '.$bulan.' '.$tahun;
	  ?>
      <center>
        <img src="<?php echo base_url(); ?>public/images/idcard/logo-bca.png" class="logo">
      </center>
        <p class="runningtext"><marquee>KARTU KREDIT</marquee></p>
        <div id="auto">
            <div style="box-shadow: none;">
                 <center>
                  <a href="javascript:;" onClick="openFullscreen()">
                  <img src="https://internal.ptdika.com/assets/photos/real/<?php echo $this->session->userdata('foto'); ?>" class="photo">
                  </a>
                  </center>
                  <img src="<?php echo base_url(); ?>public/images/idcard/stamp_cc.png" class="stamp">
            </div>
            <div style="box-shadow: none;">
                <center>
                <a href="javascript:;" onClick="openFullscreen()">
                  <img src="<?php echo base_url('upload/qrcode/'.$query->filename); ?>" class="qr">
                </a>
                </center>
            </div>
        </div>
      <center>
        <p class="nama">
        	<b><?php echo substr($this->session->userdata('realname'),0,20); ?></b><br>
        	<b><?php echo $this->session->userdata('sl_code'); ?></b>
        </p>
      </center>
      <div class="tanggal" align="center">
        <h4 class="blink"><b><?php echo $date; ?></b></h4>
      </div>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url(); ?>public/mytemplate/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url(); ?>public/mytemplate//bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url(); ?>public/mytemplate/plugins/iCheck/icheck.min.js"></script>
<!-- Slider -->
<script src="<?php echo base_url(); ?>public/mytemplate/plugins/slider/jquery-3.2.1.min.js"></script>
<script src="<?php echo base_url(); ?>public/mytemplate/plugins/slider/jquery.bbslider.min.js"></script>
<script>
  $(document).ready(function () {
	$('#auto').bbslider({auto: true, timer: 10000, loop: true, pauseOnHit: false});
  });
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
<script>
var elem = document.getElementById("box-id");
function openFullscreen() {
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.mozRequestFullScreen) { /* Firefox */
    elem.mozRequestFullScreen();
  } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE/Edge */
    elem.msRequestFullscreen();
  }
}

</script>
</body>
</html>
