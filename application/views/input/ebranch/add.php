<script src="<?php echo base_url(); ?>public/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function getLocation() {
        var code = document.getElementById("Source_Code").value;

        $.ajax({
            url: "<?php echo site_url('input/ebranch/get_source_code'); ?>/" + code,
            dataType: "json",
            type: 'GET',
            success: function(data) {
                $.each(data, function(i, n) {
                    if (code == "") {
                        document.getElementById("Location").value = "";
                    } else {
                        document.getElementById("Location").value = n["desc"];
                    }
                });
            },
            error: function(data) {
                alert("Source Code tidak ditemukan..!");
                document.getElementById("Source_Code").value = "";
                document.getElementById("Location").value = "";
                document.getElementById("Source_Code").focus() = "";
            }
        });
    }
</script>

<div id="ribbon">
    <ol class="breadcrumb">
        <i class="fa fa-home"></i> &nbsp;
        <li><a href="<?php echo site_url(); ?>">Home</a></li>
        <li><i class="fa fa-cloud-upload "></i> &nbsp; <a href="<?php echo site_url('input/ebranch'); ?>">Index</a></li>
        <li>Add Data</li>
    </ol>
</div>

<div id="content">
    <?php if ($this->session->flashdata('message')) { ?>
        <?php echo $this->session->flashdata('message'); ?>
    <?php } ?>
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">&nbsp;</i>
            </button>
        </div>
        <h3 class="box-title">Form E-Branch</h3>
    </div>
    <?php //echo form_open(''); 
    ?>
    <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
        <div class="panel-body">
            <div class="row">
                <input type="hidden" name="Sales_Code" id="Sales_Code" value="<?php echo $this->session->userdata('sl_code') ?>">
                <input type="hidden" name="Sales_Name" id="Sales_Name" value="<?php echo $this->session->userdata('realname') ?>">
                <input type="hidden" name="Branch" id="Branch" value="<?php echo $this->session->userdata('branch') ?>">
                <input type="hidden" name="Created_by" id="Created_by" value="<?php echo $this->session->userdata('realname') ?>">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Nasabah <span style='color:red'><b>*</b></span></label>
                        <input type="text" id="Customer_Name" name="Customer_Name" class="form-control" value="<?php echo set_value('Customer_Name'); ?>" autocomplete="off" required>
                        <?php echo form_error('Customer_Name'); ?>
                    </div>

                    <div class="form-group">
                        <label>Source Code <span style='color:red'><b>*</b></span></label>
                        <!-- <input type="text" id="Source_Code" name="Source_Code" class="form-control" value="<?php echo set_value('Source_Code'); ?>" onchange="getLocation(); return false;" onKeyUp="this.value=this.value.replace(' ','')" onPaste="false" autocomplete="off" minlength="6" maxlength="6" required>
						<div id="Location"></div> -->
                        <select class="form-control select2" name="Source_Code" id="Source_Code" style="width: 100%;" required>
                            <option value="">-- Pilih --</option>
                            <?php
                            foreach ($event as $row) {
                            ?>
                                <option value="<?php echo $row->source_code; ?>"><?php echo $row->source_code; ?></option>
                            <?php } ?>
                        </select>
                        <?php echo form_error('Source_Code'); ?>
                    </div>

                    <div class="form-group">
                        <label>Event <span style='color:red'><b>*</b></span></label>
                        <select class="form-control select2" name="Project" id="Project" style="width: 100%;" required>
                            <option value="NO">Tidak Ada</option>
                            <!-- <?php
                                    foreach ($event as $row) {
                                    ?>
                            <option value="<?php echo $row->project_name; ?>"><?php echo $row->project_name; ?></option>
                            <?php } ?> -->
                        </select>
                        <?php echo form_error('event'); ?>
                    </div>

                    <div class="form-group">
                        <label>No Referensi <span style="color:red"><b>*</b></span> <span style="color:blue"><small>Contoh :
                                    A012345678</small></span></label>
                        <input type="text" id="no_referensi" name="no_referensi" class="form-control" value="<?php echo set_value('no_referensi'); ?>" onKeyUp="this.value=this.value.replace(' ','')" onPaste="false" autocomplete="off" minlength="6" maxlength="10" required>
                        <?php echo form_error('no_referensi'); ?>
                        <span id="mno_referensi"></span>
                    </div>

                    <div class="form-group">
                        <label>Kode Referensi <span style='color:red'><b>*</b></span></label>
                        <select class="form-control" name="kode_referensi" id="kode_referensi">
                            <option value="">--Pilih--</option>
                            <?php
                            foreach ($ref_referensi->result() as $ref) {
                            ?>
                                <option value="<?php echo $ref->kode_referensi; ?>-<?php echo $ref->send_status; ?>">
                                    <?php echo $ref->deskripsi; ?></option>
                            <?php } ?>
                        </select>
                        <?php echo form_error('kode_referensi'); ?>
                    </div>
                </div>


                <div class="col-md-6">

                    <div class="form-group">
                        <div id="image_preview"><img id="previewing" src="<?php echo base_url(); ?>assets/noimage.png" height="210px" width="210px" /></div>
                        <label style="margin-top: 5px">Foto Acco <span style="color:red">*</span></label>
                        <div id="selectImage">
                            <input type="file" name="file" id="file" required>
                        </div>
                        <p style="color:blue" class="help-block">Format Picture : JPG, JPEG, PNG | Max Size : 5MB</p>
                    </div>

                </div>







                <!-- <div class="col-md-12">

                </div> -->

            </div>

        </div>
        <div class="box-footer">
            <a href="<?= site_url('input/cc/ebranch') ?>" class="btn btn-danger" style="display:none;">
                <i class="fa-fw fa fa-step-backward"></i>
                Back
            </a>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </div>
        <?php echo form_close(); ?>
</div>









<!-- script JQuery untuk load gambar pada bagian preview -->
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('uploadimage').addEventListener('submit', function(event) {
            var mno_referensi = document.getElementById('no_referensi');
            var messagemno_referensi = document.getElementById('mno_referensi');
            if (mno_referensi.value.length != 10) {
                messagemno_referensi.style.color = "red";
                messagemno_referensi.innerHTML = "required 10 digits"
                event.preventDefault();
            }
            var isSubmitting = false;
            if (isSubmitting) {
                alert('Data sedang dikirim, harap tunggu.');
                event.preventDefault();
            } else {
                isSubmitting = true;
            }
        });
    });
    var site_url = `<?php site_url(); ?>`;
    $(function() {
        $(".select2").select2();
        $("#file").change(function() {
            $("#message").empty(); // To remove the previous error message
            var file = this.files[0];
            var imagefile = file.type;
            var match = ["image/jpeg", "image/png", "image/jpg"];
            if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                $('#previewing').attr('src', 'noimage.png');
                $("#message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" +
                    "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                return false;
            } else {
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
            }
        });

        $("#Source_Code").change(function() {
            // alert($(this).val())
            var idx = $(this).val();
            $.ajax({
                url: site_url + "get_event",
                type: "POST",
                data: {
                    idx: idx
                },
                dataType: "JSON",
                success: function(data) {
                    // $('#modal_form').modal('hide'); // show bootstrap modal when complete loaded

                    // console.log(data);

                    $(`#Project`).html('');
                    $(`#Project`).append($('<option>', {
                        value: "",
                        text: `- Select Project  -`,
                    }));

                    $.each(data, function(i, item) {
                        // console.log(item)
                        // console.log(`i >> ${i}|item >> ${item.project_name}`)
                        $(`#Project`).append($('<option>', {
                            value: item.project_name,
                            // value: item.project_id,
                            text: item.project_name,
                        }));
                    })

                    // setTimeout(function() {
                    //     window.location.href = url + `/master/validasi`
                    // }, 3300);

                    // swal("Information", "Data berhasil di Approved", "success");

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Sorry, You are not allowed access');
                }
            });
        })
    });

    function imageIsLoaded(e) {
        $("#file").css("color", "green");
        $('#image_preview').css("display", "block");
        $('#previewing').attr('src', e.target.result);
        $('#previewing').attr('width', '230px');
        $('#previewing').attr('height', '250px');
    }
</script>