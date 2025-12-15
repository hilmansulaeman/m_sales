<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="ribbon">
    <ol class="breadcrumb">
        <i class="fa fa-home"></i> &nbsp;
        <li><a href="<?php echo site_url(); ?>">Home</a></li>
        <li><a href="<?php echo site_url('pelamar'); ?>">Data Pelamar</a></li>
        <li>Input Data</li>
    </ol>
</div>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Input</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <?php echo form_open(''); ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Input
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="form-group">
                                    <label>ID Pelamar : </label>
                                    <input type="text" name="id" class="form-control" onchange="cekPelamar(this.value)" value="<?= set_value('id') ?>" autocomplete="off">
                                    <?php echo form_error('id',  '<span style="color: red;">', '</span>'); ?>
                                </div>
                                <script>
                                    function cekPelamar(val) {
                                        $.ajax({
                                            url: "<?= site_url('recruitment/cekPelamar/') ?>" + val,
                                            type: "GET",
                                            dataType: "JSON",
                                            success: function(data) {
                                                if (data != null) {
                                                    $("#namaPelamar").val(data.Name);
                                                } else {
                                                    alert('Data Tidak Ditemukan!');
                                                    $("#namaPelamar").val("");
                                                }
                                            }
                                        })
                                    }
                                </script>
                                <div class="form-group">
                                    <label>Nama Pelamar : </label>
                                    <input type="text" name="namaPelamar" id="namaPelamar" class="form-control" readonly value="<?= set_value('namaPelamar') ?>">
                                    <?php echo form_error('namaPelamar',  '<span style="color: red;">', '</span>'); ?>
                                </div>

                                <div class="form-group">
                                    <label>Area : <span style="color:#FF0000">*</span></label>
                                    <select name="area" class="form-control" onchange="cekArea(this.value)">
                                        <option value="">-- Pilih Area --</option>
                                        <?php foreach ($area as $a) { ?>
                                            <option value="<?= $a->area ?>" <?php echo set_select('area', $a->area); ?>>
                                                <?= $a->area ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error('area',  '<span style="color: red;">', '</span>'); ?>
                                </div>

                                <div class="form-group">
                                    <label>Produk : <span style="color:#FF0000">*</span></label>
                                    <?php if ($position != 'SPV') { ?>
                                        <select name="produk" class="form-control">
                                            <option value="">-- Pilih Produk --</option>
                                            <?php foreach ($produk as $p_) { ?>
                                                <option value="<?= $p_->produk ?>" <?php echo set_select('produk', $p_->produk); ?>>
                                                    <?= $p_->produk ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    <?php } else { ?>
                                        <input type="text" name="produk" class="form-control" value="<?= $product ?>" readonly>
                                    <?php } ?>
                                    <?php echo form_error('produk',  '<span style="color: red;">', '</span>'); ?>
                                </div>

                                <div class="form-group">
                                    <label>Posisi : <span style="color:#FF0000">*</span></label>
                                    <select name="posisi" class="form-control">
                                        <option value="">-- Pilih Posisi --</option>
                                        <?php foreach ($posisi as $pos) { ?>
                                            <option value="<?= $pos->posisi ?>" <?php echo set_select('posisi', $pos->posisi); ?>>
                                                <?= $pos->posisi ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error('posisi',  '<span style="color: red;">', '</span>'); ?>
                                </div>

                                <div class="form-group">
                                    <label>Level : <span style="color:#FF0000">*</span></label>
                                    <select name="level" class="form-control">
                                        <option value="">-- Pilih Level --</option>
                                        <?php foreach ($level as $l) { ?>
                                            <option value="<?= $l->level ?>" <?php echo set_select('level', $l->level); ?>>
                                                <?= $l->level ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error('level',  '<span style="color: red;">', '</span>'); ?>
                                </div>

                                <div class="form-group">
                                    <label>Upliner : <span style="color:#FF0000">*</span></label>
                                    <?php if ($position != 'SPV') { ?>
                                        <select name="spv" class="form-control" onchange="cekSPV(this.value)">
                                            <option value="">-- Pilih SPV --</option>
                                            <?php if ($position == "ASM") { ?>
                                                <option value="0|SPV|DUMMY SPV">DUMMY SPV</option>
                                            <?php } ?>
                                            <?php if ($position == "RSM") { ?>
                                                <option value="0|ASM|DUMMY ASM">DUMMY ASM</option>
                                            <?php } ?>
                                            <?php foreach ($list_spv as $l_) { ?>
                                                <option value="<?= $l_->DSR_Code . '|' . $l_->Position . '|' . $l_->Name ?>" <?php echo set_select('spv', $l_->DSR_Code); ?>>
                                                    <?= $l_->Name . " | " . $l_->Position ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    <?php } else { ?>
                                        <input type="text" name="spv" class="form-control" readonly value="<?= $realname . ' | ' . $position ?>" readonly>
                                    <?php } ?>
                                    <?php echo form_error('spv',  '<span style="color: red;">', '</span>'); ?>
                                </div>

                                <div class="form-group">
                                    <label>Nama SPV : <span style="color:#FF0000">*</span></label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="spv_code" id="spv_code" value="<?= set_value('spv_code') ?>" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="spv_name" id="spv_name" value="<?= set_value('spv_name') ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Nama ASM : <span style="color:#FF0000">*</span></label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="asm_code" id="asm_code" value="<?= set_value('asm_code') ?>" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="asm_name" id="asm_name" value="<?= set_value('asm_name') ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Nama RSM : <span style="color:#FF0000">*</span></label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="rsm_code" id="rsm_code" value="<?= set_value('rsm_code') ?>" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="rsm_name" id="rsm_name" value="<?= set_value('rsm_name') ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Nama BSH : <span style="color:#FF0000">*</span></label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="bsh_code" id="bsh_code" value="<?= set_value('bsh_code') ?>" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="bsh_name" id="bsh_name" value="<?= set_value('bsh_name') ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- 
                                <input type="text" name="spv_code" id="spv_code" value="<?= set_value('spv_code') ?>">
                                <input type="text" name="spv_name" id="spv_name" value="<?= set_value('spv_name') ?>"> -->

                                <input type="submit" value="Submit" class="btn btn-primary" />
                                <?php echo form_close(); ?>
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
<script>
    let position = "<?= $position ?>"
    let nikSess = "<?= $nik ?>"
    let realname = "<?= $realname ?>"

    let spvDetail = nikSess + '|' + position + '|' + realname

    $(document).ready(function() {
        if (position == "SPV") {
            cekSPV(spvDetail);
        }
        // $('#cost_centre').change(function() {
        //     cc();
        // });
    });

    // alert(position)

    function cekSPV(val) {
        let splitString = val.split('|');
        let nik = splitString[0];
        let post = splitString[1];
        let name = splitString[2];

        // alert(nik + ' - ' + post + ' - ' + name)

        if (nik == 0 && post == "SPV") { // UNTUK DUMMY SPV
            $.ajax({
                url: "<?= site_url('recruitment/cekSPV/') ?>" + nikSess,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $("#spv_code").val(0)
                    $("#spv_name").val("DUMY SPV")
                    $("#asm_code").val(data.SM_Code)
                    $("#asm_name").val(data.SM_Name)
                    $("#rsm_code").val(data.RSM_Code)
                    $("#rsm_name").val(data.RSM_Name)
                    $("#bsh_code").val(data.BSH_Code)
                    $("#bsh_name").val(data.BSH_Name)
                }
            })
        } else if (nik == 0 && post == "ASM") { // UNTUK DUMMY ASM
            $.ajax({
                url: "<?= site_url('recruitment/cekSPV/') ?>" + nikSess,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(nikSess);
                    $("#spv_code").val(0)
                    $("#spv_name").val("DUMY SPV")
                    $("#asm_code").val(0)
                    $("#asm_name").val("DUMMY ASM")
                    $("#rsm_code").val(data.SM_Code)
                    $("#rsm_name").val(data.SM_Name)
                    $("#bsh_code").val(data.BSH_Code)
                    $("#bsh_name").val(data.BSH_Name)
                }
            })
        } else {
            $.ajax({
                url: "<?= site_url('recruitment/cekSPV/') ?>" + nik,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    if (post == "ASM") {
                        $("#spv_code").val(0)
                        $("#spv_name").val('DUMMY SPV')
                        $("#asm_code").val(data.SM_Code)
                        $("#asm_name").val(data.SM_Name)
                        $("#rsm_code").val(data.RSM_Code)
                        $("#rsm_name").val(data.RSM_Name)
                        $("#bsh_code").val(data.BSH_Code)
                        $("#bsh_name").val(data.BSH_Name)
                    } else {
                        $("#spv_code").val(data.SM_Code)
                        $("#spv_name").val(data.SM_Name)
                        $("#asm_code").val(data.ASM_Code)
                        $("#asm_name").val(data.ASM_Name)
                        $("#rsm_code").val(data.RSM_Code)
                        $("#rsm_name").val(data.RSM_Name)
                        $("#bsh_code").val(data.BSH_Code)
                        $("#bsh_name").val(data.BSH_Name)
                    }

                }
            })
        }
    }
</script>