<!-- Content Header (Page header) -->

<section class="content-header">
    <h1>
        FORM INPUT DATA PENGUMUMAN
        <?php echo $this->session->flashdata('message'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Add Announcement</li>
    </ol>
</section>



<!-- Main content -->
<section class="content">



    <form method="POST" action="" enctype="multipart/form-data">

        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Daftar Karyawan <span style="color: red">*</span></label>
                            <!-- <select class="select2 form-control" data-live-search="true" name="list_employee" id="list_employee" style="width: 100%" required>
                                <option value="">--- Pilih ---</option>
                                <option value="0">All</option>
                                <?php
                                foreach ($list_employee->result() as $le) {
                                ?>
                                    <option value="<?php echo $le->DSR_Code; ?>"><?php echo $le->Name; ?> :: <?php echo $le->Email; ?> </option>
                                <?php } ?>
                            </select> -->
                            <select class="multiple-select2 form-control" data-live-search="true" name="idEmp[]" style="width: 100%" required multiple>

                                <?php
                                foreach ($list_employee->result() as $le) {
                                ?>
                                    <option value="<?php echo $le->DSR_Code; ?>"><?php echo $le->Name; ?> </option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                    <!-- <div class="col-md-12">
                        <div class="form-group">
                            <label>Subjek Email<span style="color:red">*</span></label>

                            <input type="input" class="form-control" name="email_subject" id="email_subject" required />

                        </div>
                    </div> -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Kategori<span style="color:red">*</span></label>


                            <select class="select2 form-control" name="category" id="category" style="width: 100%" required>
                                <option value="">--- Pilih ---</option>
                                <?php
                                foreach ($category->result() as $c) {
                                ?>
                                    <option value="<?php echo $c->Category; ?>"><?php echo $c->Category; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>File</label><br>
                            <span class="btn btn-success">
                                <i class="fa-fw fa fa-upload"></i>
                                <input type="file" id="file" name="file" onchange="this.parentNode.nextSibling.value = this.value">

                            </span>


                        </div>
                    </div>

                    <!-- <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-9">
                                <div class="checkbox checkbox-css checkbox-inline">
                                    <input type="checkbox" name="is_publish" value="1" id="inlineCssCheckbox1" />
                                    <label for="inlineCssCheckbox1">Publish</label>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="col-md-12">
                        <div class="form-group m-b-10">
                            <label>Deskripsi <span style="color:red">*</span></label>
                            <textarea class="ckeditor" id="announcement_description" name="announcement_description" style="width: 100%; padding:3px; margin:5px" rows="10" required></textarea>
                        </div>



                    </div>


                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="simpan" value="kirimdansave">Kirim Sekarang & Simpan</button>
                        <!-- <button type="submit" class="btn btn-primary">Simpan</button> -->
                        <?= anchor('announcement/sales', 'Back', array('class' => 'btn btn-warning')) ?>
                    </div>

                </div>
            </div>
        </div>

    </form>


    <!-- /.col -->


    <!-- /.row -->
</section>

<script type="text/javascript">
    // $(document).ready(function() {
    //   $('#ed').on('change', function() {
    //     var ed = $(this).val();

    //     $('#tgl_efective_date').val(ed);
    //   });

    // });

    function cekBulan(val) {
        var dateNow = "<?= date('Y-m'); ?>";

        var pecah = val.split('-');
        var thn = pecah[0];
        var bln = pecah[1];
        var m_input = thn + '-' + bln;

        // console.log(val);
        $.ajax({
            url: "<?= base_url(); ?>request/reaktif_form/getDate/" + val,
            type: "POST",
            data: {
                effective_date: val
            },
            dataType: "JSON",
            success: function(response) {
                console.log(response);

                if (response.status) {
                    var knfrm = confirm("Masih terdapat request dengan tanggal " + val + " apakah anda ingin menyelesaikan nya?");
                    if (knfrm) {
                        window.location.href = '<?= base_url(); ?>request/reaktif_form/detail/' + response.request_id + '/' + response.category_id;
                    } else {
                        location.reload();
                    }
                } else {
                    if (m_input > dateNow) {
                        alert('Silahkan pilih tanggal di bulan ini!');
                        $("#ed").val('');
                        $("#ed").reset();
                        $("#ed").focus();
                    }
                }
            }
        });
    }

    const monthSelect = document.getElementById("schedule_bln");
    const daySelect = document.getElementById("schedule_tgl");

    const months = [
        '01', '02', '03', '04', '05',
        '06', '07', '08', '09', '10',
        '11', '12'
    ];

    for (let i = 0; i < months.length; i++) {
        const option = document.createElement('option');
        var txt = document.createTextNode(months[i]);
        // option.textContent = months[i];
        // monthSelect.appendChild(option);
        option.appendChild(txt);
        option.setAttribute('value', months[i]);
        monthSelect.insertBefore(option, monthSelect.lastChild);
    }


    for (let i = 1; i < 32; i++) {
        const option = document.createElement('option');
        var txt = document.createTextNode(i);
        option.appendChild(txt);
        option.setAttribute('value', i);
        daySelect.insertBefore(option, daySelect.lastChild);
    }

    $(".multiple-select2").select2({
        placeholder: "Select a state"
    });
</script>