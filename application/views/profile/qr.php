<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ID Card</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url(); ?>public/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url(); ?>public/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
	<style>
    #bg {
      margin:auto;
      max-width:100%;
      height:auto;
    }
    
    #id {
      width:310px;
      height:475px;
      position:absolute;
      opacity: 0.88;
      font-family: sans-serif;
      transition: 0.4s;
      /* background-color: #FFFFFF; */
      border-radius: 2%;
    }
    
    #id::before {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      background: url('<?php echo base_url(); ?>public/images/idcard/idcard2-bg.png');   /*if you want to change the background image replace logo.png*/
      background-repeat:repeat-x;
      background-size: 310px 475px;
      border-radius: 2%;
      /* opacity: 0.2; */
      z-index: -1;
      text-align:center;
     
    }
    .id-1{
        transition: 0.4s;
        width:310px;
        height:450px;
        background: #FFFFFF;
        text-align:center;
        font-size: 16px;
        font-family: sans-serif;
        float: center;
        margin:auto;		  	
        margin-left:270px;
        border-radius:2%;	
    }
	
	.runningtext{
		font-family: "Times New Roman", Times, serif;
		font-size:21px;
		margin-top:70px;
		color:#000;
	}
    
    .photo{
        width: 180px;
        height: 185px;
        border-radius: 100%;
        border: 1px solid black;
        /*margin-top:110px;*/
        background-color:#FFCC00;
    }
    
    .qr{
        height: 235px;
        height: 235px;
        /*margin-top:110px;*/
    }
    
    .nama{
        font-family: "Times New Roman", Times, serif;
        font-size:21px;
        margin-top:10px;
		color:#000;
    }
    
    .sales_code{
        font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size:18px;
        margin-top:-10px;
		color:#000;
    }
    
    .tanggal{
        font-family: "Times New Roman", Times, serif;
        font-size:18px;
        margin-top:-8px;
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
<body style="background-color: #EFEFEF;">
<br>
<div class="container">
	<div id="dialog" class="dialog dialog-effect-in">
	  <div class="dialog-front">
		<div class="dialog-content">
			<?php
            $qrtime = $query->qr_time;
            //Format Tanggal
            $tanggal = date ('j',$qrtime);
            //Array Bulan
            $array_bulan = array('1'=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
            $bulan = $array_bulan[date('n',$qrtime)];
            //Format Tahun
            $tahun = date('Y',$qrtime);
            $date_qr = $tanggal.' '.$bulan.' '.$tahun;
            ?>
            <div id="bg">
                <div id="id">
                  <p class="runningtext"><marquee>Pembukaan Rekening Online</marquee></p>
                  <center>
                    <img src="<?php echo base_url('upload/qrcode/'.$query->filename); ?>" class="qr">
                    <p class="nama"><b><?php echo $sales->Name; ?></b></p>
                    <p class="sales_code"><b><?php echo $sales->DSR_Code; ?></b></p>
                  </center>
                  <div class="tanggal" align="center">
                    <h4 class="blink"><b><?php echo $date_qr; ?></b></h4>
                  </div>
                </div>
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