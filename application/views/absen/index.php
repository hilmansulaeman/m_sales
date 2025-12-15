<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
    .status-available {
        color: #2FC332;
    }

    .status-not-available {
        color: #D60202;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
    function check_merchant() {
        var name = escape($("#gerai_merchant").val()); //Get the value in the name textbox

        $.ajax({
            url: '<?php echo site_url(); ?>ajax/get_merchant/' + name,
            type: 'post',
            dataType: 'html',

            beforeSend: function() {
                $("#check_merchant").html('<img src="<?php echo base_url(); ?>public/images/loader.gif" align="absmiddle">&nbsp;Checking...');
            },
            success: function(result) {
                $('#check_merchant').html(result);
            }
        });
    }


    if (geo_position_js.init()) {
        geo_position_js.getCurrentPosition(success_callback, error_callback, {
            enableHighAccuracy: true
        });
    } else {
        alert("Functionality not available");
    }

    function success_callback(p) {
        var latitude = p.coords.latitude.toFixed(2);
        var longitude = p.coords.longitude.toFixed(2);
        document.getElementById('cals').innerHTML = "latitude=" + latitude + " longitude = " + longitude;
    }

    function error_callback(p) {
        alert('error=' + p.message);
    }

    function check_hp() {
        var name = escape($("#hp_penanggung_jawab").val()); //Get the value in the name textbox

        $.ajax({
            url: '<?php echo site_url(); ?>ajax/get_hp/' + name,
            type: 'post',
            dataType: 'html',

            beforeSend: function() {
                $("#check_hp").html('<img src="<?php echo base_url(); ?>public/images/loader.gif" align="absmiddle">&nbsp;Checking...');
            },
            success: function(result) {
                $('#check_hp').html(result);
            }
        });
    }

    function check_email() {
        var name = escape($("#email_penanggung_jawab").val()); //Get the value in the name textbox

        $.ajax({
            url: '<?php echo site_url(); ?>ajax/get_email/' + name,
            type: 'post',
            dataType: 'html',

            beforeSend: function() {
                $("#check_email").html('<img src="<?php echo base_url(); ?>public/images/loader.gif" align="absmiddle">&nbsp;Checking...');
            },
            success: function(result) {
                $('#check_email').html(result);
            }
        });
    }

    function check_wilayah() {
        var name = escape($("#wilayah").val()); //Get the value in the name textbox

        $.ajax({
            url: '<?php echo site_url(); ?>ajax/get_wilayah/' + name,
            type: 'post',
            dataType: 'html',
            beforeSend: function() {
                $("#check_wilayah").html('<img src="<?php echo base_url(); ?>public/images/loader.gif" align="absmiddle">&nbsp;Checking...');
            },
            success: function(result) {
                $('#check_wilayah').html(result);
            }
        });
    }
</script>
<script src="<?= base_url('assets/geo-location-javascript/') ?>js/geo.js" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('assets/geo-location-javascript/') ?>js/geo-min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url('assets/geo-location-javascript/') ?>js/geo_position_js_simulator.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url(); ?>public/jquery/js/jquery-1.7.js"></script>
<script src="<?php echo base_url(); ?>public/jquery/js/jquery.form.js"></script>

<!-- script JQuery untuk load gambar pada bagian preview -->
<script type="text/javascript">
    $(function() {


        $("#file").change(function() {
            const d = new Date();


            $("#message").empty(); // To remove the previous error message
            var file = this.files[0];
            var imagefile = file.type;

            var imageDate = file.lastModifiedDate;



            const months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
            var imageDate_ = imageDate.getDate() + "-" + months[imageDate.getMonth()] + "-" + imageDate.getFullYear();
            var dateNow = d.getDate() + "-" + months[d.getMonth()] + "-" + d.getFullYear();


            if (dateNow == imageDate_) {
                // window.alert("Foto Anda sesuai dengan tanggal hari ini");	
                // document.getElementById('submit').disabled = false;
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
                $('#dateImage').html("<p><h5><b> Tanggal foto : " + imageDate_ + ", sesuai tanggal hari ini : " + dateNow + "</b></h5></p>");

            } else {
                // window.alert("Foto Anda tidak sesuai dengan tanggal hari ini");	
                // document.getElementById('submit').disabled = true;
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
                $('#dateImage').html("<p><h5><b> Tanggal foto : " + imageDate_ + ", TIDAK sesuai tanggal hari ini : " + dateNow + "</b></h5></p>");
            }

            var match = ["image/jpeg", "image/png", "image/jpg"];

            if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                $('#previewing').attr('src', 'noimage.png');
                $("#message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                return false;
            } else {

            }
        });

 
    });





    function disabled() {
        if (document.getElementById('terms').checked) {
            document.getElementById('submit').disabled = false;
        } else {
            document.getElementById('submit').disabled = true;
        }
    }

    function imageIsLoaded(e) {
        $("#file").css("color", "green");
        $('#image_preview').css("display", "block");
        $('#previewing').attr('src', e.target.result);
        $('#previewing').attr('width', '230px');
        $('#previewing').attr('height', '250px');
    }

    $(document).ready(function() {
    $('#office_branch').on('change', function() {
        if($(this).val() != '') {
            $('#submit').prop('disabled', false);
        } else {
            $('#submit').prop('disabled', true);
        }
    });
});

</script>
<script type="text/javascript">
    if (geo_position_js.init()) {
        geo_position_js.getCurrentPosition(success_callback, error_callback, {
            enableHighAccuracy: true
        });
    } else {
        alert("Functionality not available");
    }

    function success_callback(p) {
        var latitude = p.coords.latitude.toFixed(2);
        var longitude = p.coords.longitude.toFixed(2);
        document.getElementById('cals').innerHTML = "latitude=" + latitude + " longitude = " + longitude;
    }

    function error_callback(p) {
        alert('error=' + p.message);
    }


</script>

<style>
    #title {
        background-color: #e22640;
        padding: 5px;
    }

    #current {
        font-size: 10pt;
        padding: 5px;
    }
</style>

<!-- Page Content -->

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <?php
                $kategori = $this->uri->segment(2);
                if ($kategori == 'masuk') {
                ?>
                    <h1 class="page-header">Absen Masuk</h1>
                <?php } else if ($kategori == 'request') { ?>
                    <h1 class="page-header">Long Shift Request</h1>
                <?php } else if ($kategori == 'izin') { ?>
                    <h1 class="page-header">Absen Izin</h1>
                <?php } else if ($kategori == 'off') { ?>
                    <h1 class="page-header">Absen Cuti</h1>
                <?php } else if ($kategori == 'sakit') { ?>
                    <h1 class="page-header">Absen Sakit</h1>
                <?php } else { ?>
                    <h1 class="page-header">Absen Pulang</h1>
                <?php } ?>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Field <span style="color:#FF0000">*</span> harus diisi
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- <form action="" method="post" enctype="multipart/form-data">
                                    <input type="text" name="sales_code" value="<?php echo $this->session->userdata('username'); ?>">

                                    <div class="form-group">
                                        <div id="image_preview"><img id="previewing" src="<?php echo base_url(); ?>images/noimage.png"></div>
                                        <div>
                                            <label>Upload foto selfie : <span style="color:#FF0000">*</span></label>
                                            <br>
                                            <input type="file" name="files" required>
                                        </div>
                                        <div id="message"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Isi Keterangan di bawah ini !</label>
                                        <textarea class='form-control' name='keterangan' required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="Submit" class="btn btn-primary" />
                                    </div>
                                </form> -->

                                <?php
                                $kategori = $this->uri->segment(2);
                                echo form_open_multipart(''); ?>

                                <input type="hidden" name="sales_code" value="<?php echo $this->session->userdata('username'); ?>">

                                <div class="form-group">
                                    <div id="image_preview"><img id="previewing" src="<?php echo base_url(); ?>images/noimage.png"></div>
                                    <br>
                                    <div id="dateImage"></div>
                                    <div>
                                        <label>Upload foto selfie : <span style="color:#FF0000">*</span></label>
                                        <br>
                                        <input type="file" name="foto" id="file" required>
                                    </div>
                                    <div id="message"></div>
                                </div>

                                <!-- <div class="form-group">
                                    <label>Branch <span style="color:#FF0000">*</span></label>

                                    <select class="form-control" id="branch" name="branch" required>
                                        <option value="">-- Pilih -- </option>
                                        <?php foreach ($branch as $a) { ?>
                                            <option value="<?= $a->branch; ?>" <?php echo set_select('branch', $a->branch, False); ?>> <?= $a->branch ?></option>
                                        <?php } ?>
                                    </select>
                                </div> -->

                                <div class="form-group" id="office_branch">
                                    <label>Office Branch </label>
                                    <select class="form-control" id="office_branch" name="office_branch" >
                                    <option value="">---Pilih----</option>
									
                                    <?php  
									
									$kota=$this->session->userdata('branch');
									
									if(strtoupper($kota) == 'JAKARTA'){ 
                                        foreach($jb as $row){ 
                                            echo "<option value='" . $row->office_branch . "'>" . $row->office_branch . "</option>";
                                        }?>
									<?php  }else if(strtoupper($kota) == 'BOGOR'){ 
                                        foreach($jb as $row){
                                            echo "<option value='" . $row->office_branch . "'>" . $row->office_branch . "</option>";
                                         }?>
									<?php  }else if(strtoupper($kota) == 'DEPOK'){ 
                                        foreach($jb as $row){
                                            echo "<option value='" . $row->office_branch . "'>" . $row->office_branch . "</option>";
                                         }?>
									<?php  }else if(strtoupper($kota) == 'TANGERANG'){ 
                                        foreach($jb as $row){
                                            echo "<option value='" . $row->office_branch . "'>" . $row->office_branch . "</option>";
                                         }?>
									<?php  }else if(strtoupper($kota) == 'BEKASI'){ 
                                        foreach($jb as $row){
                                            echo "<option value='" . $row->office_branch . "'>" . $row->office_branch . "</option>";
                                         }?>
                                   <?php  }else if(strtoupper($kota) == 'SURABAYA'){ 
                                        foreach($sb as $row){
                                            echo "<option value='" . $row->office_branch . "'>" . $row->office_branch . "</option>";
                                         }?>
                                   <?php  }else if(strtoupper($kota) == 'MEDAN'){
                                        foreach($md as $row){
                                            echo "<option value='" . $row->office_branch . "'>" . $row->office_branch . "</option>";
                                         }?>
                                   <?php  }else if(strtoupper($kota) == 'SEMARANG'){
                                        foreach($sm as $row){
                                            echo "<option value='" . $row->office_branch . "'>" . $row->office_branch . "</option>";
                                         }?>
                                    <?php }else if(strtoupper($kota) == 'MALANG'){
                                        foreach($ml as $row){
                                            echo "<option value='" . $row->office_branch . "'>" . $row->office_branch . "</option>";
                                         }?>
                                    <?php }else if(strtoupper($kota) == 'MAKASSAR'){
                                        foreach($mk as $row){
                                            echo "<option value='" . $row->office_branch . "'>" . $row->office_branch . "</option>";
                                         }?>
                                    <?php }else if(strtoupper($kota) == 'BANDUNG'){
                                        foreach($bd as $row){
                                            echo "<option value='" . $row->office_branch . "'>" . $row->office_branch . "</option>";
                                         }?>
                                    <?php } ?>
                                   
                                    </select>
                                </div>

                                <div class="form-group">

                                    <label>Isi Keterangan di bawah ini !</label>
                                    <textarea class='form-control' name='keterangan' required></textarea>
                                </div>
                                <label class="form-group"> Your location</label>
                                <br>
                                <label id="cals"></label>

                                <input id="submit" type="submit" value="upload" class="btn btn-primary" disabled="disabled" />

                                </form>
                            </div>

                            <!-- /.col-lg-6 (nested) -->
                        </div>
                        <!-- /.row (nested) -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<script type="text/javascript">
        $(document).ready(function() {
    $('#office_branch').on('change', function() {
        if($(this).val() != '') {
            $('#submit').prop('disabled', true);
        } else {
            $('#submit').prop('disabled', false);
        }
    });
});

</script>

<script type="text/javascript" defer>
    $(document).ready(function() {
        function diklik();
        alert('success');
    });


  
</script>