<?php
	$fl_pass = $this->session->userdata('FL_pass');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
	<meta name="google" content="notranslate">

    <title><?php echo $title ?></title>

     <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>public/mytemplate/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- <link href="<?php echo base_url(); ?>public/mytemplate/bower_components/datatables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" /> -->
    <link href="<?php echo base_url(); ?>public/mytemplate/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

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
    <script src="<?php echo base_url() ?>public/mytemplate/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="<?php echo base_url() ?>public/mytemplate/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>public/mytemplate/bower_components/datatables/media/js/jquery.dataTables.js"></script>
    <!-- <script src="<?php echo base_url() ?>public/mytemplate/bower_components/datatables/media/js/jquery.dataTables.min.js"></script> -->
    <script src="<?php echo base_url() ?>public/mytemplate/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>public/highcharts/js/highcharts.js"></script>
    <script src="<?php echo base_url() ?>public/highcharts/js/highcharts-more.js"></script>
    <script src="<?php echo base_url() ?>public/highcharts/js/modules/exporting.js"></script>
    <!-- <script src="<?php echo base_url() ?>public/highcharts/js/modules/export-data.js"></script> -->
    <!-- <script src="<?php echo base_url() ?>public/highcharts/js/modules/accessibility.js"></script> -->
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>public/mytemplate/plugins/select2/select2.min.css">

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
	<?php $this->load->view('template/header'); ?>
	<?php
		if($fl_pass == 1)
		{
	?>
		<?php $this->load->view('template/menu'); ?> 
	<?php
		}else
		{
		}
	?>
    <div class="content-wrapper">
		<section class="content">
			<?php echo $contents; ?>
		</section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">SMS. Version 4.1</div>
    </footer>
    
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

    <script src="<?php echo base_url(); ?>public/bower_components/flot/excanvas.min.js"></script>
<script>
$(document).ready(function() {
    // $(document).ready(function() {
    //     $('#example').DataTable({
    //             responsive: true,
	// 			searching: true
    //     });
    // });

    $(document).ready(function() {
        $('#example2').DataTable({
                responsive: true,
                paging :false
        });
    });

    $(document).ready(function() {
        $('#example3').DataTable({
                responsive: true,
                paging :true
        });
    });

    $('.tanggal').datepicker({
        format: "yyyy-mm-dd",
        autoclose:true
    });

    $('.tanggal2').datepicker({
        format: "yyyy-mm-dd",
        autoclose:true
    });

    $(".date-input").datepicker({
        format: "yyyy-mm",
        autoclose:true
    });

    $(".select2").select2();

    $(".multiple-select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();
});
</script>
</body>

</html>