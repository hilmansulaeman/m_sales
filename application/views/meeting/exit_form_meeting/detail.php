<?php
$uri3 = $this->uri->segment(3);
$request_id = $this->uri->segment(4);

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
                    <div class="box-header">
                      <h3 class="box-title with-border">Monitoring Meeting (DSR, SPG, SPB, Mobile sales)</h3>
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
                    <h3 class="box-title">Permintaan Form Meeting</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    
                    <div class="table-responsive">
                        <table id="data-request" class="table table-responsive table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="1%">No</th>
                                    <th>Kode Sales</th>
                                    <th>Nama</th>
                                    <th>Posisi</th>
                                    <th>Level</th>
                                    <th>Exit Reason</th>
                                    <th>Keterangan</th>
                                    <th>Dibuat Oleh</th>
                                    <th width="10%">Status</th>
                                </tr>
                            </thead>

                            <?php echo form_open('meeting/exit_form_meeting/send/' . $request_id); ?>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($request_user->result() as $r) {
                                    $request_user_id = $r->Request_User_ID;
                                    // var_dump($request_user_id);
                                    // die;
                                    $hit_code = $r->Hit_Code;
                                    $hrd_checker_name = $r->HRD_Checker_Name;

                                    if ($hit_code == '1010') {
                                        $class = 'btn-danger';
                                        $status = "Ditolak Oleh ";
                                        $by = $hrd_checker_name;
                                        $hrd_note = '*'.$r->HRD_Note;
                                    } elseif ($hit_code == '1009') {
                                        $class = 'btn-info';
                                        $status = "Pending HRD";
                                        $by = '';
                                        $hrd_note = '';
                                    } else {
                                        $class = 'btn-success';
                                        $status = "Approve By ";
                                        $by = "$hrd_checker_name";
                                        $hrd_note = '';
                                        
                                    }

                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $r->Sales_Code; ?>
                                            <input type="hidden" name="request_user_id[]" value="<?php echo $r->Request_User_ID; ?>">
                                        </td>
                                        <td><?php echo $r->Sales_Name; ?></td>
                                        <td><?php echo $r->Position; ?></td>
                                        <td><?php echo $r->Level; ?></td>
                                        <td><?php echo $r->Exit_Reason; ?></td>
                                        <td><?php echo $r->Reason; ?></td>
                                        <td>
                                            <?php
                                                echo $r->Created_Name; 
                                            ?>
                                            <!-- <input type="hidden" name="employee_id[]" value="<?php //echo $r->Employee_ID; ?>"> -->
                                            <input type="hidden" name="regnoID[]" value="<?php echo $r->Regno_ID; ?>">
                                            <input type="hidden" name="sales_code[]" value="<?php echo $r->Sales_Code; ?>">
                                            <input type="hidden" name="name[]" value="<?php echo $r->Sales_Name; ?>">
                                            <input type="hidden" name="reason[]" value="<?php echo $r->Reason; ?>">
                                            <input type="hidden" name="new_status[]" value="<?php echo $r->Exit_Status; ?>">
                                            <input type="hidden" name="exit_reason[]" value="<?php echo $r->Exit_Reason; ?>">

                                            <input type="hidden" name="category" value="<?php echo $db->Category; ?>">
                                            <input type="hidden" name="resign_date[]" value="<?php echo $db->Efective_Date; ?>">

                                        </td>
                                        <?php
                                        if ($hit_code == '1000') {
                                        ?>
                                            <td>
                                                <a href="<?php echo site_url(); ?>meeting/exit_form_meeting/delete_detail/<?php echo $request_id; ?>/<?php echo $r->Request_User_ID; ?>/<?php echo $category_id; ?>" onclick="return confirm('Yakin Hapus?')"><span class="btn btn-xs btn-danger"><i class="fa fa-md fa-trash" title="Delete Data"></i></span></a>
                                            </td>
                                        <?php } else { ?>
                                            <td>
                                                <label class="btn btn-xs <?= $class ?>"><?= $status . $by.'<br>'.$hrd_note ?></label>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                    <!-- end foreach-->
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <?php $query = $this->db->get_where('data_request_user', array('Request_ID' => $request_id, 'Hit_Code' => '1000'));
                    $row = $query->num_rows();

                    if ($row == 0) { ?>
                        <p></p>
                    <?php } else { ?>
                        <input formaction="<?php echo site_url(); ?>meeting/exit_form_meeting/send/<?= $request_id ?>" type="submit" class="btn btn-info" name="simpan" value="Kirim Permintaan >>" float="right" onclick="return confirm('Yakin kirim permintaan?')">
                    <?php } ?>



                    <?php echo form_close(); ?>

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
    $(document).ready(function() {
        $('#ed').on('change', function() {
            var ed = $(this).val();

            $('#tgl_efective_date').val(ed);
        });

    });

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
              alert(response.sales);
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
        <form method="POST" action="<?php echo site_url(); ?>meeting/exit_form_meeting/save_request_exit/<?= $uri3 ?>/<?= $request_id ?>">

        <div class="col-md-6">
            <div class="form-group">
                <label>Tanggal Efektif (yyyy-mm-dd)<span style="color: red">*</span></label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="efective_date2" class="form-control pull-right tanggal" value="<?php echo $db->Efective_Date; ?>" disabled>
                    <input type="hidden" name="efective_date" value="<?php echo $db->Efective_Date; ?>">
                    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                    <input type="hidden" name="category" value="<?= $category ?>" class="form-control">
                    <input type="hidden" id="data_sales" name="data_sales" value="" class="form-control">

                </div>
            </div>
        </div>
        <?php
            if ($cekDetail > 0) {
                $class = 'disabled';
                $class2 = 'readonly';
            } else {
                $class = 'required';
                $class2 = 'required';
            }
        ?>
        <div class="col-md-6">
            <div id="modalAddSelect" class="form-group">
                <label>Exit Reason <span style="color:red">*</span></label>
                <div class="input-group">
                    <select class="form-control" style="width:120%;" name="exit_reason" <?= $class ?>>
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
                <textarea class="form-control" rows="4" name="reason" <?= $class2 ?>></textarea>
            </div>

            <div class="box-footer">
                <?php
                if ($cekDetail > 0) {
                ?>
                    &nbsp;
                <?php } else { ?>
                    <button id="saveExit" type="submit" class="btn btn-primary">Simpan</button>
                <?php } ?>
            </div>
        </div>

        </form>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->