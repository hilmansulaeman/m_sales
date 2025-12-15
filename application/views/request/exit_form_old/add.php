
<script type="text/javascript">
    function getDetailSales() {
        var code  = document.getElementById("data_employee_id").value;
        
        $.ajax({
            url: "<?php echo site_url(); ?>request/exit_form/getDetailSales/" + code,
            dataType:"json",
            type: 'GET',
            success:
            function(data){
               $.each(data, function(i,n){
                    if(code == ""){
                        document.getElementById("employee_id").value = "" ;
                        document.getElementById("dsr_code").value = "" ;
                        document.getElementById("name").value = "" ;
                        document.getElementById("position").value = "" ;
                        document.getElementById("level").value = "" ;
                        document.getElementById("status").value = "" ;
                        document.getElementById("resign_date").value = "" ;
                        document.getElementById("join_date").value = "" ;
                    }
                    else {
                        document.getElementById("employee_id").value = n["Employee_ID"] ;
                        document.getElementById("dsr_code").value = n["DSR_Code"] ;
                        document.getElementById("name").value = n["Name"] ;
                        document.getElementById("position").value = n["Position"] ;
                        document.getElementById("level").value = n["Level"] ;
                        document.getElementById("status").value = n["Status"] ;
                        document.getElementById("resign_date").value = n["Resign_Date"] ;
                        document.getElementById("join_date").value = n["Join_Date"] ;
                    }
               });
            },
            error: function(data){
                        document.getElementById("employee_id").value = "" ;
                        document.getElementById("dsr_code").value = "" ;
                        document.getElementById("name").value = "" ;
                        document.getElementById("position").value = "" ;
                        document.getElementById("level").value = "" ;
                        document.getElementById("status").value = "" ;
                        document.getElementById("resign_date").value = "" ;
                        document.getElementById("join_date").value = "" ;
            }
        });
    }
</script>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        EXIT FORM <?= $this->session->userdata('sl_code');?>
        <?php echo $this->session->flashdata('message'); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Exit Form</li>
      </ol>
    </section>



    <!-- Main content -->
    <section class="content">

      <div class="row">

        <form method="POST" action="<?php echo site_url();?>request/exit_form/save_request_exit/<?php echo $this->uri->segment(3);?>/0">

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
                        <input type="text" name="efective_date" class="form-control pull-right tanggal" id="ed" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>DSR Code <span style="color:red">*</span></label>
                    
                    <select class="form-control select2" id="data_employee_id" name="data_employee_id" onchange="getDetailSales(); return false;" required>
                        <option value="">-- Pilih --</option>
                        <?php
                        foreach ($sales->result() as $s) {
                        ?>
                            <option value="<?php echo $s->Employee_ID;?>"><?php echo $s->Name;?> - <?php echo $s->DSR_Code;?> - <?php echo $s->Position;?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" class="form-control" id="employee_id" name="employee_id" readonly/>
                    <input type="hidden" class="form-control" id="dsr_code" name="sales_code" readonly/>
                    <input type="hidden" class="form-control" id="name" name="sales_name" readonly/>
                    <input type="hidden" class="form-control" id="position" name="position" readonly/>
                    <input type="hidden" class="form-control" id="level" name="level" readonly/>

                    <input type="hidden" class="form-control" id="status" name="status" readonly/>
                    <input type="hidden" class="form-control" id="resign_date" name="resign_date" readonly/>
                    <input type="hidden" class="form-control" id="join_date" name="join_date" readonly/>
                    
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Status Baru <span style="color:red">*</span></label>
                    
                    <select class="form-control select2" name="new_status" required>
                        <option value="">-- Pilih --</option>
                        <?php
                        foreach ($status->result() as $s) {
                        ?>
                            <option value="<?php echo $s->Status;?>"><?php echo $s->Status;?></option>
                        <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" rows="4" name="reason"></textarea>
                    </div>

                    <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </div>

            </div>
          </div>
        </div>

      </form>

        <div class="col-md-12">
          
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Request Exit Form</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="data-request" class="table table-responsive table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="1%">No</th>
                    <th>Sales Code</th>
                    <th>Sales Name</th>
                    <th>Position</th>
                    <th>Level</th>
                  <!--  <th>Resign Date</th>
                    <th>New Status</th> -->
                    <th>Reason</th>
                    <th>Checker</th>
                    <th>Approval</th>
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

  $(document).ready(function(){
    $('#ed').on('change', function(){
      var ed = $(this).val();

      $('#tgl_efective_date').val(ed);
    });

  });

</script>