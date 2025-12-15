<?php

$date_from = $this->input->post('date_from');
$date_to = $this->input->post('date_to');

if($date_from !='' && $date_to != ''){
    $date_from = $this->input->post('date_from');
    $date_to = $this->input->post('date_to');
}else{
    $date_from = date('Y-m-01');
    $date_to = date('Y-m-d');
}

?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        FORM EXIT MEETING
        <?php echo $this->session->flashdata('message'); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Form Exit Meeting</li>
      </ol>
    </section>



    <!-- Main content -->
    <section class="content">

      <div class="row">

        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Monitoring Meeting (DSR, SPG, SPB, Mobile sales)</h3>
            </div>
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
                              <input type="date" id="date_from" name="date_from" value="<?php echo $date_from; ?>" data-dateformat='yy-mm-dd'  class="form-control datepicker" required/>
                            <?php echo form_error('date_from'); ?>
                            </label>  
                          </td>                     
                          <td><h5 class="txt-color-blueDark">&nbsp; S/D &nbsp; </h5></td>                     
                          <td>
                            <label class="input">               
                              <input type="date" id="date_to" name="date_to" value="<?php echo $date_to; ?>" data-dateformat='yy-mm-dd'  class="form-control datepicker" required/>
                            <?php echo form_error('date_to'); ?>
                            </label>
                          </td>
                          <td>&nbsp;&nbsp;&nbsp;</td>
                          <td>
                            <button type="button" id="btn-filter" class="btn btn-success" onclick="filter_data()" style="padding:5px;"><span class="fa fa-filter"></span> Go</button>
                          </td>                                         
                        </tr>            
                      </table>
                    </form>
                  </span>
                </div>
              </div>
              <br>

              <div class="table-responsive">
                <table id="data-table-customer" class="table table-hover" width="100%">
                  <thead>                     
                    <tr>                                      
                      <th>No</th>
                      <th>Nama Sales</th>
                      <th>Branch</th>
                      <th>Total Meeting</th>
                      <th>Total Hadir</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="6">Loading data from server</td>
                    </tr>
                  </tbody>
                </table>          
              </div>

            </div>
          </div>
        </div>


        <div class="col-md-12">
          
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Permintaan Form Exit Meeting</h3>
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
  var table;
  $(document).ready(function() {
      table = $("#data-table-customer").DataTable({
      ordering: false,
      processing: true,
      serverSide: true,
      responsive:true,
      ajax: {
        url: "<?php echo site_url('meeting/exit_form_meeting/get_data_monitoring') ?>",
        type:'POST',
        data: function ( data ) {
                  data.date_from = $('#date_from').val();
                  data.date_to = $('#date_to').val();
              }
      },
      initComplete : function() {
        var input = $('#data-table-customer_filter input').unbind(),
          self = this.api(),
          searchButton = $('<span id="btnSearch" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></span>')
              .click(function() {
                self.search(input.val()).draw();
              });
          $(document).keypress(function (event) {
            if (event.which == 13) {
              searchButton.click();
            }
          });
        $('#data-table-customer_filter').append(searchButton);
      }
    });
  });

  function datediff(first, second) {        
      return Math.round((second - first) / (1000 * 60 * 60 * 24));
  }

  function parseDate(str) {
      var mdy = str.split('-');
      return new Date(mdy[0], mdy[1] - 1, mdy[2]);
  }

  function filter_data()
  {
    var dd = datediff(parseDate($('#date_from').val()), parseDate($('#date_to').val()));
    if(dd <= 30){
        table.draw();
    }else{
      alert('Maaf, range tanggal maksimal 31 hari');
    }
  }

  function cekBulan(val) {
      var dateNow = "<?= date('Y-m'); ?>";

      var pecah = val.split('-');
      var thn = pecah[0];
      var bln = pecah[1];
      var m_input = thn+'-'+bln;
// url2:2;
    console.log(bln);

    $.ajax({
        url: "<?= base_url();?>meeting/exit_form_meeting/getDate/" + val,
        type: "POST",
        data: {
            effective_date: val,
        },
        dataType: "JSON",
        success: function (response) {
          if(response.status)
          {
            var knfrm = confirm("Masih terdapat request dengan tanggal " + val + " apakah anda ingin menyelesaikan nya?");
            if(knfrm)
            {
              window.location.href = '<?= base_url();?>meeting/exit_form_meeting/detail/'+response.request_id+'/'+response.category_id;
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

  function addExit(dsr_code) {
    $.ajax({
      url: "<?= base_url();?>meeting/exit_form_meeting/getDetailSales/" + dsr_code,
      type: "POST",
      dataType: "JSON",
      success: function (response) {
        if(response.status)
        {
          // console.log(response.sales);
          $('#modalAdd').modal('show');
          $('#data_sales').val(response.sales);
        }else {
          alert('Data pegawai tidak ditemukan');
        }
      }

    });
  }

  $('#saveExit').on('click', function() {
    $('#modalAdd').modal('hide');
  });

</script>
<!-- Modal Add -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add Form</h4>
      </div>
      <div class="modal-body row">
        <form method="POST" action="<?php echo site_url();?>meeting/exit_form_meeting/save_request_exit/<?php echo $this->uri->segment(3);?>/0">

        <div class="col-md-6">
            <div class="form-group">
                <label>Tanggal Efektif (yyyy-mm-dd)<span style="color: red">*</span></label>
                <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="date" name="efective_date" class="form-control" id="ed" autocomplete="off" onChange="cekBulan(this.value)" required>
                <!--Category-->
                <input type="hidden" name="category" value="<?= $category ?>" class="form-control">
                <input type="hidden" name="category_id" value="<?= $categoryid ?>" class="form-control">
                <input type="hidden" id="data_sales" name="data_sales" value="" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Exit Reason <span style="color:red">*</span></label>
              <div class="input-group">
                <select class="form-control" style="width:120%;" name="exit_reason" required>
                    <option value="">-- Pilih --</option>
                    <?php
                    foreach ($exit_reason->result() as $s) {
                    ?>
                        <option value="<?php echo $s->Exit_Reason;?>"><?php echo $s->Exit_Reason;?></option>
                    <?php } ?>
                </select>
              </div>
          </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" rows="4" name="reason"  required></textarea>
            </div>

            <div class="box-footer">
            <button id='saveExit' type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>

        </form>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->