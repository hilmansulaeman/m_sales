<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    KATEGORI FORM
    <?php echo $this->session->flashdata('message'); ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Kategori Form</li>
  </ol>
</section>



<!-- Main content -->
<section class="content">

  <div class="row">
    <?php
    foreach ($kategori->result() as $s) {
    ?>
      <form method="POST" action="<?php echo site_url('master/Category_Form/edit/' . $s->Category_Form_ID); ?>">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-body">
              <div class="col-md-12">


                <div class="form-group">
                  <label>Kategori Form</label>
                  <input type="text" class="form-control" name="category_form" autocomplete="off" value="<?php echo $s->Category_Form; ?>" required>
                </div>


                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </div>
            <?php } ?>
            </div>
          </div>
        </div>

      </form>


  </div>
  <!-- /.row -->
</section>