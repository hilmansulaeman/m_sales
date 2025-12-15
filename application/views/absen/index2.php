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

<script src="<?php echo base_url(); ?>public/jquery/js/jquery-1.7.js"></script>
<script src="<?php echo base_url(); ?>public/jquery/js/jquery.form.js"></script>

<!-- script JQuery untuk load gambar pada bagian preview -->
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

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <?php
                $kategori = $this->uri->segment(2);
                if ($kategori == 'masuk') {
                ?>
                    <h1 class="page-header">Absen Masuk 2 (INI FORM ABSEN 2)</h1>
                <?php } else if ($kategori == 'request') { ?>
                    <h1 class="page-header">Long Shift Request 2 (INI FORM Request 2)</h1>
                <?php } else if ($kategori == 'izin') { ?>
                    <h1 class="page-header">Absen Izin 2 (INI FORM Izin 2)</h1>
                <?php } else if ($kategori == 'off') { ?>
                    <h1 class="page-header">Absen Off 2 (INI FORM Off 2)</h1>
                <?php } else if ($kategori == 'sakit') { ?>
                    <h1 class="page-header">Absen Sakit 2 (INI FORM Sakit 2)</h1>
                <?php } else { ?>
                    <h1 class="page-header">Absen Pulang 2 (INI FORM Pulang 2)</h1>
                <?php } ?>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Field <span style="color:#FF0000">*</span> harus diisi 2
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">

                                <form id="uploadimage" action="<?= site_url('absen2/'.$this->uri->segment(2)) ?>" method="post" enctype="multipart/form-data">

                                    <input type="hidden" name="sales_code" value="<?php echo $this->session->userdata('username'); ?>">

                                    <div class="form-group">
                                        <div id="image_preview"><img id="previewing" src="<?php echo base_url(); ?>new_assets/img/noimage.png" /></div>
                                        <div id="selectImage">
                                            <label>Upload Foto Selfie : 2<span style="color:#FF0000">*</span></label>
                                            <br>
                                            <input type="file" name="foto" id="file" required>
                                        </div>
                                        <div id="message"></div>
                                    </div>

                                    <?php if ($this->session->userdata('unit') == 'TIKET.COM') { ?>
                                        <div class="form-group">
                                            <label for="">Divisi</label>
                                            <select name="divisi" id="divisi" class="form-control" onchange="renderDivisi(this.value)">
                                                <option selected value="">---Pilih---</option>
                                                <option value="all">ALL</option>
                                                <option value="call_center">Call Center</option>
                                                <option value="attraction">Attraction</option>
                                                <option value="rent_car">Rent Car</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Nama Shift</label>
                                            <select name="shift_name" id="shift_name" class="form-control selectpicker" data-size="10" data-live-search="true" data-style="btn-white">
                                                
                                            </select>
                                        </div>
                                    <?php } else{ ?>
                                        <div class="form-group">
                                            <label for="">Nama Shift</label>
                                            <select name="shift_name" id="shift_name" class="form-control selectpicker" data-size="10" data-live-search="true" data-style="btn-white">
                                                <option value="">---Pilih---</option>
                                                <option value="Non Shift">Non Shift</option>
                                                <?php  
                                                    foreach ($getShift->result() as $g) {
                                                ?>
                                                    <option value="<?= $g->Shift_Name ?>" <?= set_select('shift_name', $g->Shift_Name, FALSE) ?>><?= $g->Shift_Name ?> (<?= $g->Jam_Mulai ?> - <?= $g->Jam_Selesai ?>)</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    <?php } ?>

                                    <div class="form-group">
                                        <label>Isi Keterangan di bawah ini ! 2</label>
                                        <textarea class='form-control' name='keterangan' required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" value="Submit" class="btn btn-primary" />
                                    </div>
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
    function renderDivisi(data)
    {
        var divisi = data;

        $.ajax({
            url: '<?= site_url(); ?>absen2/getDivisi/'+divisi,
            type: 'post',
            data: {divisi},
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $('#shift_name').empty();
                $('#shift_name').append('<option selected value="">---Pilih---</option>');
                $('#shift_name').append('<option value="Non Shift">Non Shift</option>');
                if (len > 0) {
                    for (var i = 0; i < len; i++) {
                        var shift_name = response[i]['Shift_Name'];
                        var jam_mulai = response[i]['Jam_Mulai'];
                        var jam_selesai = response[i]['Jam_Selesai'];
                        $('#shift_name').append('<option>'+shift_name+' ('+jam_mulai+' - '+jam_selesai+')</option>').selectpicker('refresh');
                    }
                }else{
                    $('#shift_name').html('').selectpicker('refresh');
                }
            }
        });
    }
</script>