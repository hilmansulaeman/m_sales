<?php
$dt_startDate = $this->input->post('dt_startDate');
$dt_endDate = $this->input->post('dt_endDate');

if($dt_startDate !='' && $date_to != ''){
  $dt_startDate = $this->input->post('dt_startDate');
  $dt_endDate = $this->input->post('dt_endDate');
}else{
  $dt_startDate = date('Y-m-01');
  $dt_endDate = date('Y-m-d');
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <a href="<?php echo site_url('meeting/exit_form_meeting/add'); ?>" class="btn btn-header btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-plus"></i></span> Tambah Data</a>
    <a href="<?= site_url('meeting/exit_form_meeting') ?>" class="btn btn-info">Refresh <i class="fa fa-refresh"></i></a>
    <!-- <a href="javascript:void(0);" onclick="Filter()" class="btn btn-warning">Filter <i class="fa fa-filter"></i></a> -->
    <?php echo $this->session->flashdata('message'); ?>
  </h1>
  <ol class="breadcrumb">
    <i class="fa fa-home"></i> &nbsp;
    <li><a href="<?php echo site_url(); ?>">Home</a></li>
    <li><i class="fa fa-cloud-link "></i> &nbsp; Meeting</li>
    <li>Exit</li>
  </ol>
</section>



<!-- Main content -->
<section class="content">

  <div class="row">

    <div class="col-xs-12">

      <div class="alert alert-info alert-dismissible">
        <h4><i class="icon fa fa-info"></i> Periode : <span id="periode"></span></h4>
      </div>


      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Permintaan Form Exit</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-sm-12">
              <span class="pull-right">
                <form id="form_filter" method="post" class="smart-form" novalidate="novalidate">    
                  <table>
                    <tr>                                                              
                      <td><h5 class="txt-color-blueDark">Periode &nbsp; </h5></td>  
                      <td>
                        <label class="input">   
                          <input type="date" id="dt_startDate" name="dt_startDate" value="<?php echo $dt_startDate; ?>" data-dateformat='yy-mm-dd'  class="form-control tanggal" required/>
                        <?php echo form_error('dt_startDate'); ?>
                        </label>  
                      </td>                     
                      <td><h5 class="txt-color-blueDark">&nbsp; S/D &nbsp; </h5></td>                     
                      <td>
                        <label class="input">               
                          <input type="date" id="dt_endDate" name="dt_endDate" value="<?php echo $dt_endDate; ?>" data-dateformat='yy-mm-dd'  class="form-control tanggal" required/>
                        <?php echo form_error('dt_endDate'); ?>
                        </label>
                      </td>
                      <td>&nbsp;&nbsp;&nbsp;</td>
                      <td>
                        <!-- <button type="button" id="btn-filter" class="btn btn-success" onclick="filter_data()" style="padding:5px;"><span class="fa fa-filter"></span> Go</button> -->
                        <button type="button" id="filter" class="btn btn-success" style="padding:5px;"><span class="fa fa-filter"></span> Go</button>
                      </td>                                         
                    </tr>            
                  </table>
                </form>
              </span>
            </div>
          </div>
          <br>
          <div class="table-responsive">
            <table id="data-request" class="table  table-bordered table-striped">
              <thead>
                <tr>
                  <th width="1%"></th>
                  <th>Tanggal Efektif</th>
                  <th>Dibuat Tanggal</th>
                  <th>Dibuat Oleh</th>
                  <th width="12%">Jumlah Data</th>
                  <th width="9%">Disetujui</th>
                  <th width="9%">Dibatalkan</th>
                  <th width="10%">Detail</th>
                </tr>
              </thead>
  
              <tbody>
                <tr>
                  <td colspan="8">Loading data from server</td>
                </tr>
              </tbody>
            </table>
          </div>

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
      //searching:false,
      processing: true,
      serverSide: true,
      responsive: true,
      ajax: {
        url: "<?php echo site_url('meeting/exit_form_meeting/get_data_exit') ?>",
        type: 'POST',
        data: function(data) {
          data.startDate = $('#dt_startDate').val();
          data.endDate = $('#dt_endDate').val();
        }
      },
      initComplete: function() {
        var input = $('#data-request_filter input').unbind(),
          self = this.api(),
          searchButton = $('<span id="btnSearch" class="btn btn-sm btn-default active"><i class="fa fa-search"></i></span>')
          .click(function() {
            self.search(input.val()).draw();
          });
        $(document).keypress(function(event) {
          if (event.which == 13) {
            searchButton.click();
          }
        });
        $('#data-request_filter').append(searchButton);
      }
    });

    $('#filter').on('click', function() {
      if (submit_filter()) {
        table.ajax.reload();
        periode();
        // $('#modalFilter').modal('hide');
      }
    });

    periode();

    function periode() {
      let periode = $('#periode');
      let month = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
      ];

      let start = $('#dt_startDate').val();
      let startDate = start ? new Date(start) : new Date(1);

      let end = $('#dt_endDate').val();
      let endDate = end ? new Date(end) : new Date();

      let periodeVal = `${startDate.getDate()} - ${endDate.getDate()} / ${month[endDate.getMonth()]} / ${endDate.getFullYear()}`;
      //console.log(periodeVal);
      periode.html(periodeVal);

      //let startDate = $('dt_startDate').val() != '' ? $('#dt_startDate').val() : ;

    }
  });



  /*function Filter() {
    //filter_incoming
    $('#modalFilter').modal('show');
  }*/

  function datedif(tgl1, tgl2) {
    // varibel miliday sebagai pembagi untuk menghasilkan hari
    var miliday = 24 * 60 * 60 * 1000;
    //buat object Date
    var tanggal1 = new Date(tgl1);
    var tanggal2 = new Date(tgl2);
    // Date.parse akan menghasilkan nilai bernilai integer dalam bentuk milisecond
    var tglPertama = Date.parse(tanggal1);
    var tglKedua = Date.parse(tanggal2);
    var selisih = (tglKedua - tglPertama) / miliday;
    return selisih;
  }

  function submit_filter() {
    var tgl1 = document.getElementById('dt_startDate').value;
    var tgl2 = document.getElementById('dt_endDate').value;
    var days = datedif(tgl1, tgl2);
    if (tgl1 == "" || tgl2 == "") {
      /*document.getElementById('alert1').style.display = 'none';
      document.getElementById('alert2').style.display = 'block';
      document.getElementById('alert3').style.display = 'none';*/
      alert('Tanggal Kosong. Isi Tanggal Dengan Benar!');
      return false;
    } else if (days > 31) {
      /*document.getElementById('alert1').style.display = 'none';
      document.getElementById('alert2').style.display = 'none';
      document.getElementById('alert3').style.display = 'block';*/
      alert('Maaf, range tanggal maksimal 31 hari');
      return false;
    } else {
      return true;
    }

  }
</script>





<!-- Modal Add -->
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Filter Form</h4>
      </div>
      <div class="modal-body">

        <div class="form-group">
          <div class="input-group">
            <label>Start Date </label>
          </div>
          <div class="input-group">
            <label class="input-group-btn" for="dt_startDate">
              <span class="btn btn-default">
                <span class="fa fa-calendar"></span>
              </span>
            </label>
            <input type="text" name="start_date" readonly id="dt_startDate" class="form-control tanggal" value="<?php echo $this->input->post('start_date'); ?>" autocomplete="off" />
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <label>End Date</label>
          </div>
          <div class="input-group">
            <label class="input-group-btn" for="dt_endDate">
              <span class="btn btn-default">
                <span class="fa fa-calendar"></span>
              </span>
            </label>
            <input type="text" name="end_date" readonly id="dt_endDate" class="form-control tanggal" value="<?php echo $this->input->post('end_date'); ?>" autocomplete="off" />
          </div>
        </div>
        <!--<input type="submit" class="btn btn-primary btn-sm" value="Go"/>-->
        <button type="button" id="filter" class="btn btn-primary btn-sm">Go</button>

        <br>
        <br>
        <div class="alert alert-info alert-dismissible" style="display:block;" id="alert1">
          <h5><i class="icon fa fa-info"></i> Tanggal Tidak Boleh Kosong. <br>
            <i class="icon fa fa-info"></i> Range Tanggal maksimal 31 hari
          </h5>
        </div>
        <div class="alert alert-danger alert-dismissible" style="display:none;" id="alert2">
          <h5><i class="icon fa fa-info"></i> Tanggal Kosong. Isi Tanggal Dengan Benar!</h5>
        </div>
        <div class="alert alert-danger alert-dismissible" style="display:none;" id="alert3">
          <h5><i class="icon fa fa-info"></i> Maaf, range tanggal maksimal 31 hari</h5>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->