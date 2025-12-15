

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php
    $category_id = $this->uri->segment('4');
    if ($category_id == '9') {
      $label = "FORM PERUBAHAN KE ";
    } else {
      $label = "FORM PERUBAHAN ";
    }
    ?>
    <?= $label . strtoupper($category) ?>
    <?php echo $this->session->flashdata('message'); ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Restruct Form</li>
  </ol>
</section>



<!-- Main content -->
<section class="content">

  <div class="row">

        <form method="POST" action="<?php echo site_url();?>request/restruct_form/save_request_restruct/<?php echo $this->uri->segment(3);?>/0">

        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-body">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal Efektif (yyyy-mm-dd)<span style="color: red">*</span></label>
                        <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <!-- <input type="text" name="efective_date" class="form-control pull-right tanggal" id="ed" autocomplete="off" onChange="cekBulan(this.value)"> -->
                        <input type="date" name="efective_date" class="form-control" id="ed" autocomplete="off" onChange="cekBulan(this.value)" required>
                        <!--Category-->
                        <input type="hidden" name="category" value="<?= $category ?>" class="form-control">
                        <input type="hidden" name="category_id" value="<?= $categoryID ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>DSR Code <span style="color:red">*</span></label>
                    
                    <select class="form-control select2" id="data_employee_id" name="data_employee_id" required>
                        <option value="">-- Pilih --</option>
                        <?php
                        foreach ($sales as $s) {
                        ?>
                          <option value="<?= $s->Employee_ID.'|'.$s->Regno_ID.'|'.$s->DSR_Code.'|'.$s->Name.'|'.$s->Position.'|'.$s->Level.'|'.$s->Product;?>">
                            <?= $s->DSR_Code.' - '.$s->Name.' - '.$s->Product.' - '.$s->Branch; ?>
                          </option>
                        <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" rows="4" name="reason" required></textarea>
                    </div>

                    <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>

            </div>
          </div>
        </div>

      </form>

    <div class="col-md-12">

      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Permintaan Form Struktur</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table id="data-request" class="table table-responsive table-bordered table-striped">
            <thead>
              <tr>
                <th width="1%">No</th>
                <th>Kode Sales</th>
                <th>Nama</th>
                <th>Posisi</th>
                <th>Level</th>
                <!--  <th>Resign Date</th>
                    <th>New Status</th> -->
                <th>Keterangan</th>
                <th>Dibuat Oleh</th>
                <th width="10%">Status</th>
              </tr>
            </thead>

            <tbody>
            </tbody>
          </table>


        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->



    </div>
    <!-- /.col -->

  </div>
  <!-- /.row -->
</section>

<script type="text/javascript">

  function cekBulan(val) {
      var dateNow = "<?= date('Y-m'); ?>";

      var pecah = val.split('-');
      var thn = pecah[0];
      var bln = pecah[1];
      var m_input = thn+'-'+bln;
// url2:2;
    console.log(bln);

    $.ajax({
        url: "<?= base_url();?>request/restruct_form/getDate/" + val,
        type: "POST",
        data: {
            effective_date: val
        },
        dataType: "JSON",
        success: function (response) {
          if(response.status)
          {
            var knfrm = confirm("Masih terdapat request dengan tanggal " + val + " apakah anda ingin menyelesaikan nya?");
            if(knfrm)
            {
              window.location.href = '<?= base_url();?>request/restruct_form/detail/'+response.request_id+'/'+response.category_id;
            }
            else {
              location.reload();
            }
          }
          // if (m_input > dateNow) {
          else {
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
</script>