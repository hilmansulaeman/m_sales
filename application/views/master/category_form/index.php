<!-- Content Header (Page header) -->

<section class="content-header">
  <h1>
    <a href="<?php echo site_url('master/Category_Form/add'); ?>" class="btn btn-header btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-plus"></i></span> Tambah Data</a>
    <a href="<?= site_url('master/Category_Form') ?>" class="btn btn-info">Refresh <i class="fa fa-refresh"></i></a>
    <?php echo $this->session->flashdata('message'); ?><p></p>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li>Master</li>
    <li class="active">Kategori Form</li>
  </ol>
</section>



<!-- Main content -->
<section class="content">

  <div class="row">

    <div class="col-xs-12">

      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Kategori Form</h3>
        </div>
        <!-- /.box-header -->

        <div class="box-body">
          <table id="data-request" class="table table-responsive table-bordered table-striped">
            <thead>
              <tr>
                <th width="1%">No</th>
                <th>Kategori Form</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td colspan="8">Loading data from server</td>
              </tr>
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
  var table;
  $(document).ready(function() {
    table = $("#data-request").DataTable({
      ordering: false,
      searching: false,
      processing: true,
      serverSide: true,
      responsive: true,
      ajax: {
        url: "<?php echo site_url('master/Category_Form/get_data_category') ?>",
        type: 'POST',
      }
    });

  });
</script>