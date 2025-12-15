<?php
$uri3 = $this->uri->segment(3);
$request_id = $this->uri->segment(4);

?>
<script type="text/javascript">
  function getDetailSales() {
    var code = document.getElementById("data_employee_id").value;
    $.ajax({
      url: "<?php echo site_url(); ?>request/demosi_form/getDetailSales/" + code,
      dataType: "json",
      type: 'GET',
      success: function(data) {
        $.each(data, function(i, n) {
          if (code == "") {
            document.getElementById("employee_id").value = "";
            document.getElementById("dsr_code").value = "";
            document.getElementById("name").value = "";
            document.getElementById("position").value = "";
            document.getElementById("level").value = "";
          } else {
            document.getElementById("employee_id").value = n["Employee_ID"];
            document.getElementById("dsr_code").value = n["DSR_Code"];
            document.getElementById("name").value = n["Name"];
            document.getElementById("position").value = n["Position"];
            document.getElementById("level").value = n["Level"];
          }
        });
      },
      error: function(data) {
        document.getElementById("employee_id").value = "";
        document.getElementById("dsr_code").value = "";
        document.getElementById("name").value = "";
        document.getElementById("position").value = "";
        document.getElementById("level").value = "";
      }
    });
  }
</script>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    FORM DEMOSI
    <?php echo $this->session->flashdata('message'); ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Demosi Form</li>
  </ol>
</section>



<!-- Main content -->
<section class="content">

  <div class="row">

    <form method="POST" action="<?php echo site_url(); ?>request/demosi_form/save_request_demosi/<?= $uri3 ?>/<?= $request_id ?>">

      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-body">
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal Efektif (yyyy-mm-dd)<span style="color: red">*</span></label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="efective_date2" class="form-control pull-right tanggal" value="<?php echo $db->Efective_Date; ?>" disabled>
                  <input type="hidden" name="efective_date" value="<?php echo $db->Efective_Date; ?>">
                  <input type="hidden" name="category" value="<?php echo $db->Category; ?>">

                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>DSR Code <span style="color:red">*</span></label>

                <?php
                $request_id = $db->Request_ID;
                $cek = $this->db->query("SELECT * FROM internal_sms.data_request_user WHERE Request_ID = '$request_id' AND Hit_Code > '1000'")->num_rows();
                if ($cek > 0) {
                  $class = 'disabled';
                  $class2 = 'readonly';
                } else {
                  $class = 'required';
                  $class2 = 'required';
                }
                ?>

                <select class="form-control select2" id="data_employee_id" name="data_employee_id" onchange="getDetailSales(); return false;" <?= $class ?>>
                  <option value="">-- Pilih --</option>
                  <?php
                  foreach ($sales->result() as $s) {
                  ?>
                    <option value="<?php echo $s->DSR_Code; ?>"><?php echo $s->Name; ?> - <?php echo $s->DSR_Code; ?> - <?php echo $s->Position; ?> - <?php echo $s->Branch; ?></option>
                  <?php } ?>
                </select>

                <input type="hidden" class="form-control" id="employee_id" name="employee_id" readonly />
                <input type="hidden" class="form-control" id="dsr_code" name="sales_code" readonly />
                <input type="hidden" class="form-control" id="name" name="sales_name" readonly />
                <input type="hidden" class="form-control" id="position" name="position" readonly />
                <input type="hidden" class="form-control" id="level" name="level" readonly />
                <!-- <input type="hidden" name="category" value="<?php echo $db->Category; ?>"> -->

              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" rows="4" name="reason" <?= $class2 ?>></textarea>
              </div>

              <div class="box-footer">
                <?php
                if ($cek > 0) {
                ?>
                  &nbsp;
                <?php } else { ?>
                  <button type="submit" class="btn btn-primary">Simpan</button>
                <?php } ?>
              </div>
            </div>

          </div>
        </div>
      </div>

    </form>

    <div class="col-md-12">

      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Permintaan Form Demosi</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table id="data-request" class="table table-responsive table-bordered table-striped">
            <thead>
              <tr>
                <th width="1%">No</th>
                <th>Kode Sales</th>
                <th>Nama Sales</th>
                <th>Posisi</th>
                <th>Level</th>
                <!--  <th>Resign Date</th>
                    <th>New Status</th> -->
                <th>Keterangan</th>
                <th>Diperiksa Oleh</th>
                <th>Disetujui Oleh</th>
                <th>Alasan Ditolak</th>
                <th width="10%">Status</th>
              </tr>
            </thead>

            <?php echo form_open('request/demosi_form/send/' . $request_id); ?>
            <tbody>
              <?php
              $no = 1;
              foreach ($request_user->result() as $r) {
                $request_user_id = $r->Request_User_ID;
                $hit_code = $r->Hit_Code;
                $hrd_checker_name = $r->HRD_Checker_Name;
                $hrd_note = "";

                $cek_status_ = $this->db->query("SELECT * FROM data_request_approval WHERE Request_User_ID = '$request_user_id' AND Status != '0' ORDER BY Request_Approval_ID DESC LIMIT 1");
                $cek_status = $cek_status_->num_rows();

                if ($hit_code == '1010') {
                  $class = 'btn-warning';
                  $status = "Dibatalkan Oleh ";
                  $by = $hrd_checker_name;
                  $hrd_note = $r->HRD_Note;
                } else {
                  if ($cek_status > 0) {
                    $data = $cek_status_->row();
                    if ($data->Status == '1') {
                      $class = 'btn-info';
                      $status = "Disetujui Oleh ";
                    } else {
                      $class = 'btn-danger';
                      $status = "Ditolak Oleh ";
                    }
                    $by = $data->Sales_Name;
                  } else {
                    if ($hit_code == '1002' || $hit_code == '1004') {
                      $class = 'btn-danger';
                      $status = "Disetujui Oleh ";
                    } elseif ($hit_code == '1007') {
                      $class = 'btn-success';
                      $status = "Sukses ";
                    } elseif ($hit_code == '1008') {
                      $class = 'btn-info';
                      $status = "Data Baru ";
                    } elseif ($hit_code == '1010') {
                      $class = 'btn-warning';
                      $status = "Batal ";
                    } elseif ($hit_code == '1009') {
                      $class = 'btn-info';
                      $status = "Dikirim Ke HRD ";
                    } else {
                      $class = 'btn-info';
                      $status = "Disetujui Oleh HRD ";
                    }
                    if ($hit_code == '1002' || $hit_code == '1003') {
                      $by = "$r->Checker_Name";
                    } elseif ($hit_code == '1004' || $hit_code == '1005') {
                      $by = "$r->Approval_Name";
                    } else {
                      $by = '';
                    }
                  }
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
                  <td><?php echo $r->Reason; ?></td>
                  <td><?php
                      $name = $this->db->query("SELECT * FROM db_hrd.data_employee WHERE DSR_Code = '$r->Created_By'");
                      $namerow = $name->row();
                      echo $namerow->Name;  ?>
                  <td><?php echo $r->Checker_Name; ?>
                  <td><?php echo $hrd_note; ?>
                    <input type="hidden" name="checker" value="<?php echo $r->Checker; ?>">
                    <input type="hidden" name="dsr_code[]" value="<?php echo $r->Sales_Code; ?>">
                    <input type="hidden" name="name[]" value="<?php echo $r->Sales_Name; ?>">
                    <input type="hidden" name="employee_id[]" value="<?php echo $r->Employee_ID; ?>">
                    <input type="hidden" name="reason[]" value="<?php echo $r->Reason; ?>">
                    <input type="hidden" name="category[]" value="<?php echo $db->Category; ?>">
                  </td>
                  <?php
                  if ($hit_code == '1000') {
                  ?>
                    <td>
                      <a href="<?php echo site_url(); ?>request/demosi_form/delete_detail/<?php echo $request_id; ?>/<?php echo $r->Request_User_ID; ?>" onclick="return confirm('Sure to delete?')"><span class="btn btn-xs btn-danger"><i class="fa fa-md fa-trash" title="Delete Data"></i></span></a>
                    </td>
                  <?php } else { ?>
                    <td>
                      <label class="btn btn-xs <?= $class ?>"><?= $status . $by ?></label>
                    </td>
                  <?php } ?>
                </tr>
                <!-- end foreach-->
              <?php } ?>
            </tbody>
          </table>
          <br>

          <?php
          $query = $this->db->query("SELECT * FROM internal_sms.data_request_user WHERE Request_ID = '$request_id' AND Hit_Code = '1000'");
          $row = $query->num_rows();
          if ($row > 0) {
            $checker = $query->row()->Checker;
            $approval = $query->row()->Approval;
          ?>
            <?php if ($checker == $approval) { ?>
              <input type="submit" class="btn btn-success" formaction="<?php echo site_url(); ?>request/demosi_form/send/<?= $request_id . '/' . $checker ?>" name="finish" value="KIRIM KE HRD" onclick="return confirm('Sure to Finish?')">
            <?php } else { ?>
              <input type="submit" class="btn btn-info" name="simpan" value="Kirim Permintaan >>" float="right" onclick="return confirm('Yakin kirim permintaan?')">
            <?php } ?>
          <?php } else { ?>
            &nbsp;
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
</script>