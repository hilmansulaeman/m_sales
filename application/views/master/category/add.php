<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    KATEGORI
    <?php echo $this->session->flashdata('message'); ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Kategori</li>
  </ol>
</section>



<!-- Main content -->
<section class="content">

  <div class="row">

    <form method="POST" action="<?php echo site_url(); ?>master/category/add">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-body">
            <div class="col-md-12">
              <div class="form-group">
                <label>Kategori</label>
                <input type="text" class="form-control" name="category" autocomplete="off" required>
              </div>

              <div class="form-group">
                <label>Kategori Form</label>
                <select class="form-control select2" name="category_form" required>
                  <option value="">-- Pilih --</option>
                  <?php
                  foreach ($kategori_form->result() as $data) {
                  ?>
                    <option value="<?php echo $data->Category_Form; ?>"><?php echo $data->Category_Form; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label>Persetujuan</label>
                <select class="form-control select2" name="approvalmax" required>
                  <option value="">-- Pilih --</option>
                  <option value="ASM">ASM</option>
                  <option value="RSM">RSM</option>
                  <option value="BSH">BSH</option>
                  <option value="GM">GM</option>
                </select>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </div>

          </div>
        </div>
      </div>

    </form>


  </div>
  <!-- /.row -->
</section>