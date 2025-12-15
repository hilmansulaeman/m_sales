<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>new_assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>new_assets/plugins/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>new_assets/plugins/font-awesome/5.3/css/all.min.css" rel="stylesheet" />

    <link href="<?php echo base_url(); ?>new_assets/css/default/style.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>new_assets/css/default/style-responsive.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>new_assets/css/default/theme/default.css" rel="stylesheet" id="theme" />

    <link href="<?php echo base_url(); ?>new_assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>new_assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />

    <link href="<?php echo base_url(); ?>new_assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body style="-moz-box-sizing: border-box; -webkit-box-sizing: border-box; background-color: #f9f9f9; box-sizing: border-box; font-family: Arial, Sans-serif; font-size: 10px; line-height: 24px; margin: 0;">
    <p style="font-size:15px;"><b><i><?php echo $category; ?>,</i></b></p>
    <?php if ($card != "") { ?>
        <div style="margin-bottom:15px;text-align:center;">
            <img src="<?= base_url('upload/announcement/' . $card); ?>" width="100%" height="100%">
            <br><br>
        </div>
    <?php } ?>
    <p style="font-size:20px;"><?= $deskripsi; ?></p>


    <br>
    <p style="font-size:15px;color:#b0bfc3">Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke email ini.</p>


    <p style="font-size:15px;"><b>Best Regards,<br>
            HED <br>
            PT DIKA <br>
            Graha Dika / Lantai.3 <br>
            Jalan Bendungan Hilir Raya No. 31 A - Jakarta Pusat 102210</b></p>

</body>



</html>
<script src="<?php echo base_url(); ?>new_assets/plugins/jquery/jquery-3.3.1.min.js"></script>
<script src="<?php echo base_url(); ?>new_assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>new_assets/plugins/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>new_assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<!--[if lt IE 9]>
        <script src="../assets/crossbrowserjs/html5shiv.js"></script>
        <script src="../assets/crossbrowserjs/respond.min.js"></script>
        <script src="../assets/crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
<script src="<?php echo base_url(); ?>new_assets/js/apps.min.js"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="<?php echo base_url(); ?>new_assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>new_assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>new_assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>

<script src="<?php echo base_url(); ?>new_assets/js/demo/table-manage-default.demo.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script src="<?php echo base_url(); ?>new_assets/plugins/select2/dist/js/select2.min.js"></script>