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
<section class="content">
  <div class="box box-primary">
		<div class="box-header with-border">
			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">&nbsp;</i>
                </button>
            </div>		
			<h3 class="box-title">Filter</h3>			  
		</div>
		<div class="panel-body">
			<form method="post">
				<div class="form-group">		 
					<div class="input-group">
						<label>Start Date </label>
					</div>	
					<div class="input-group">
						<label class="input-group-btn" for="dt_startDate">
							<span class="btn btn-default">
								<span class="fa fa-calendar"></span>
							</span>
						</label>
						<input type="text" name="start_date" readonly id="dt_startDate" class="form-control date-input" value="<?php echo $this->input->post('start_date'); ?>"/>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<label>End Date</label>
					</div>
					<div class="input-group">
						<label class="input-group-btn" for="dt_endDate">Start Date
							<span class="btn btn-default">
								<span class="fa fa-calendar"></span>
							</span>
						</label>
						<input type="text" name="end_date" readonly id="dt_endDate" class="form-control date-input" value="<?php echo $this->input->post('end_date'); ?>" />
					</div>			 
				</div>
				<!--<input type="submit" class="btn btn-primary btn-sm" value="Go"/>-->
				<a onclick="return submit_filter()" class="btn btn-primary btn-sm">Go</a>
			</form>
		 </div>     
	  </div>	  
	</div>
	<div class="alert alert-info alert-dismissible" style="display:block;" id="alert1">
		<h5><i class="icon fa fa-info"></i> Tanggal Tidak Boleh Kosong. <br> 
			<i class="icon fa fa-info"></i> Range Tanggal maksimal 31 hari
		</h5>
	</div>
	<div class="alert alert-danger alert-dismissible" style="display:none;" id="alert2">
		<h5><i class="icon fa fa-info"></i> Tanggal Kosong. Isi Tanggal Dengan Benar!</h5>
	</div>
	<div class="alert alert-danger alert-dismissible" style="display:none;" id="alert3">
		<h5><i class="icon fa fa-info"></i> Maaf, range tanggal maksimal 31 hari</h5>
	</div>
</section>
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
	
	function datedif(tgl1, tgl2){
		// varibel miliday sebagai pembagi untuk menghasilkan hari
		var miliday = 24 * 60 * 60 * 1000;
		//buat object Date
		var tanggal1 = new Date(tgl1);
		var tanggal2 = new Date(tgl2);
		// Date.parse akan menghasilkan nilai bernilai integer dalam bentuk milisecond
		var tglPertama = Date.parse(tanggal1);
		var tglKedua = Date.parse(tanggal2);
		var selisih = (tglKedua - tglPertama) / miliday;
		return selisih;
	}
	
	function submit_filter()
	{
		var tgl1 = document.getElementById('dt_startDate').value;
		var tgl2 = document.getElementById('dt_endDate').value;
		var days = datedif(tgl1,tgl2);
		if(tgl1 == "" || tgl2 ==  ""){
			document.getElementById('alert1').style.display= 'none';
			document.getElementById('alert2').style.display= 'block';
			document.getElementById('alert3').style.display= 'none';
		}
		else if(days > 31){
		    document.getElementById('alert1').style.display= 'none';
			document.getElementById('alert2').style.display= 'none';
			document.getElementById('alert3').style.display= 'block';
		}
		else
		{
			window.parent.parent.location='<?php echo site_url() ?>filter_incoming/index/' + tgl1 +'/'+ tgl2;
		}
		
	}



</script>