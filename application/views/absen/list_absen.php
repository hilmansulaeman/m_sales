<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>public/jquery/js/jquery-1.7.js"></script>
<script src="<?php echo base_url(); ?>public/jquery/js/jquery.form.js"></script>
<script type="text/javascript">
    $(function() {
        $("#file").change(function() {
            $("#message").empty(); // To remove the previous error message
            var file = this.files[0];
            var imagefile = file.type;
            var match = ["image/jpeg", "image/png", "image/jpg"];
            if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                $('#previewing').attr('src', 'noimage.png');
                $("#message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                return false;
            } else {
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    function imageIsLoaded(e) {
        $("#file").css("color", "green");
        $('#image_preview').css("display", "block");
        $('#previewing').attr('src', e.target.result);
        $('#previewing').attr('width', '230px');
        $('#previewing').attr('height', '250px');
    }
</script>
<?php
$jam = array("00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00");
?>
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="<?= base_url(""); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= base_url("request/absen"); ?>">Lembur</a></li>
</ol>
<!-- end breadcrumb -->
<div class='row'>
    <!-- begin page-header -->
    <div class="col-lg-6">
        <h1 class="page-header">&nbsp;</h1>
    </div>
    <!-- end page-header -->
    <!-- notif -->
    <?php echo $this->session->flashdata('message'); ?>
    <!-- end notif -->
</div>
<!-- begin row -->
<div class="row">
    <div class="col-lg-12">
        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>

                <h5 style="color:white">Absen</h5>
            </div>
            <!-- end panel-heading -->

            <!-- begin panel-body -->
            <div class="panel-body">
                <!-- notif -->
                <?php if ($this->session->flashdata('msg-success')) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('msg-success'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php }; ?>
                <?php if ($this->session->flashdata('msg-danger')) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('msg-danger'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php }; ?>
                <!-- end notif -->
                <form action="<?= site_url('request/add_absen') ?>" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="sales_code" id="sales_code" value="<?= $this->session->userdata('sales_code') ?>">

                            <div class="form-group">
                                <div id="image_preview">
                                    <img id="previewing" src="<?= base_url(); ?>public/images/noimage.png">
                                </div>
                                <div id="selectImage">
                                    <label for="">Upload Foto Selfie : <span style="color:#FF0000">*</span></label>
                                    <br>
                                    <input type="file" name="foto" id="file" required>
                                </div>
                                <div id="message"></div>
                                <?= form_error("foto", "<span style='color:red;'>","</span>"); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Kategori</label>
                                <select name="kategori" id="kategori" class="form-control selectpicker" data-size="10" data-live-search="true" data-style="btn-white">
                                    <option value="">---Pilih---</option>
                                    <option value="Cuti">Cuti</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Izin Pulang Cepat">Izin Pulang Cepat</option>
                                    <option value="Izin Datang Terlambat">Izin Datang Terlambat</option>
                                </select>
                                <?= form_error("kategori", "<span style='color:red;'>","</span>"); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                                <?= form_error("keterangan", "<span style='color:red;'>","</span>"); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>

            </div>
            <!-- end panel-body -->
        </div>
        <!-- end panel -->
    </div>
</div>

</div>
<!-- end row -->