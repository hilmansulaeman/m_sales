<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        FORM INPUT CATEGORY
        <?php echo $this->session->flashdata('message'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Add Category</li>
    </ol>
</section>



<!-- Main content -->
<section class="content">



    <form method="POST" action="">

        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Category <span style="color: red">*</span></label>

                            <!-- <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div> -->
                            <input type="input" name="category" class="form-control" id="ed" autocomplete="off" required>
                            <!--Category-->

                            <input type="hidden" name="category_id" value="" class="form-control">
                            <!--Category-->
                            <?php echo form_error('category'); ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Schedule <span style="color:red">*</span></label>

                            <select class="form-control select2" id="schedule" name="schedule" style="width: 100%" required>
                                <option value="">-- Pilih --</option>
                                <option value="manual">Manual</option>
                                <option value="daily-trigger">Daily Trigger</option>
                                <option value="daily-birthday">Daily Birthday</option>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Schedule Tanggal<span style="color:red">*</span></label>

                            <select class="form-control select2" id="schedule_tgl" name="schedule_tgl" style="width: 100%" required>
                                <option value="0">-- Pilih --</option>

                            </select>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Schedule Bulan<span style="color:red">*</span></label>

                            <select class="form-control select2" id="schedule_bln" name="schedule_bln" style="width: 100%" required>
                                <option value="0">-- Pilih --</option>
                            </select>

                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <?= anchor('announcement/ref_category', 'Back', array('class' => 'btn btn-warning')) ?>
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
</script>