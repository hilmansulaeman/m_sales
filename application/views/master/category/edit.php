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

    <form method="POST" action="<?php echo site_url('master/category/edit/' . $db->Category_ID); ?>">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-body">
            <div class="col-md-12">


              <div class="form-group">
                <label>Kategori</label>
                <input type="text" class="form-control" name="category" autocomplete="off" value="<?php echo $db->Category; ?>" required>
              </div>

              <div class="form-group">
                <label>Kategori Form</label>
                <select class="form-control select2" name="category_form" required>
                  <option value="">-- Pilih --</option>
                  <?php
                  $category_form1 = $db->Category_Form;

                  foreach ($category_form as $data) {
                  ?>

                    <option value="<?= $data->Category_Form ?>" <?php if ($category_form1 == $data->Category_Form) {
                                                                  echo 'selected';
                                                                } ?>><?= $data->Category_Form ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label>Persetujuan</label>
                <select class="form-control select2" name="approvalmax" required>
                  <option value="">-- Pilih --</option>
                  <?php
                  $approval_max = $db->Approval_Max;
                  ?>
                  <option value="ASM" <?php if ($approval_max == 'ASM') {
                                        echo 'selected';
                                      } ?>>ASM</option>
                  <option value="RSM" <?php if ($approval_max == 'RSM') {
                                        echo 'selected';
                                      } ?>>RSM</option>
                  <option value="BSH" <?php if ($approval_max == 'BSH') {
                                        echo 'selected';
                                      } ?>>BSH</option>
                  <option value="GM" <?php if ($approval_max == 'GM') {
                                        echo 'selected';
                                      } ?>>GM</option>
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