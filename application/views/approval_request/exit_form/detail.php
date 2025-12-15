<?php
    $uri3 = $this->uri->segment(3);
    $request_id = $this->uri->segment(4);
    
    $q = $this->db->query("SELECT * FROM internal_sms.data_request_user WHERE Request_ID = '$request_id'")->row();
    $username = $this->session->userdata('sl_code');
    $checker = $q->Checker;
    $approval = $q->Approval;
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        EXIT FORM
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

            <div class="col-md-12">
            
            <div class="box">
                <div class="box-header">
                <h3 class="box-title">Permintaan Exit Form</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="data-request" class="table table-responsive table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="1%"><input type="checkbox" onclick="toggle(this);" /></th>
                                <th>Sales Code</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Level</th>
                                <th>Keterangan</th>
                                <th>Dibuat Oleh</th>
                                <th>Persetujuan</th>
                                <th width="10%">Status</th>
                            </tr>
                        </thead>

                        <form action="<?= site_url();?>approval_request/exit_form/approve/<?=$request_id?>" method="POST" onsubmit="return validate(this);">
                        <tbody>
                            <?php 
                                $no = 1;
                                foreach ($request_user->result() as $r) {
                                    $request_id = $r->Request_ID;
                                    $request_user_id = $r->Request_User_ID;
                                    $hit_code = $r->Hit_Code;
                                    $approval_status = $r->Approval_Status;
                                    if($hit_code == '1002' || $hit_code == '1004') {
                                        $class = 'btn-danger';
                                        $status = "Ditolak Oleh ";
                                    }
                                    elseif($hit_code == '1007') {
                                        $class = 'btn-info';
                                        $status = "Success ";
                                    }
                                    elseif($hit_code == '1010') {
                                        $class = 'btn-warning';
                                        $status = "Batal ";
                                    }
                                    else {
                                        $class = 'btn-info';
                                        $status = "Disetujui Oleh ";
                                    }
                                    if($hit_code == '1002' || $hit_code == '1003' || $hit_code == '1008') {
                                        $by = "$r->Checker_Name";
                                    }
                                    elseif($hit_code == '1004' || $hit_code == '1005' || $hit_code == '1009') {
                                        $by = "$r->Approval_Name";
                                    }
                                    else {
                                        $by = '';
                                    }

                                    if($username == $checker) {
                                        $q_checker = $this->db->query("SELECT * FROM internal_sms.data_request_user WHERE Request_ID = '$request_id' AND Checker_Status = '0'")->num_rows();
                                        if($q_checker > 0) {
                                            $style = 'style="opacity:0; position:absolute; left:9999px;"';
                                        }
                                        else {
                                            if($r->Checker_Status == '1') {
                                                $style = 'style="opacity:0; position:absolute; left:9999px;" checked';
                                            }
                                            elseif($r->Checker_Status == '2') {
                                                $style = 'style="opacity:0; position:absolute; left:9999px;"';
                                            }
                                        }
                                    }
                                    else {
                                        $q_approval = $this->db->query("SELECT * FROM internal_sms.data_request_user WHERE Request_ID = '$request_id' AND Approval_Status = '0' AND Checker_Status = '1'")->num_rows();
                                        if($q_approval > 0) {
                                            $style = 'style="opacity:0; position:absolute; left:9999px;"';
                                        }
                                        else {
                                            if($r->Approval_Status == '1') {
                                                $style = 'style="opacity:0; position:absolute; left:9999px;" checked';
                                            }
                                            elseif($r->Approval_Status == '2') {
                                                $style = 'style="opacity:0; position:absolute; left:9999px;"';
                                            }
                                        }
                                    }
                            ?>
                            <tr>
                                <?php
                                    if($username == $checker AND $hit_code == '1001') {
                                ?>
                                    <td><input type="checkbox" name="request_user_id[]" value="<?php echo $r->Request_User_ID;?>"></td>
                                <?php } elseif($username == $approval AND $hit_code == '1008') { ?>
                                    <td><input type="checkbox" name="request_user_id[]" value="<?php echo $r->Request_User_ID;?>"></td>
                                <?php } elseif($username == $checker AND $hit_code == '1003') { ?>
                                    <td><input type="checkbox" <?=$style?>  name="request_user_id[]" value="<?php echo $r->Request_User_ID;?>"></td>
                                <?php } elseif($username == $approval AND $hit_code == '1005' ) { ?>
                                    <td><input type="checkbox" <?=$style?>  name="request_user_id[]" value="<?php echo $r->Request_User_ID;?>"></td>
                                <?php } else { ?>
                                    <td>&nbsp;</td>
                                <?php } ?>
                                
                                <td><?php echo $r->Sales_Code;?></td>
                                <td><?php echo $r->Sales_Name;?></td>
                                <td><?php echo $r->Position;?></td>
                                <td><?php echo $r->Level;?></td>
                                <td><?php echo $r->Reason;?></td>
                                <td>
                                    <?php echo $r->Checker_Name;?>
                                    <input type="hidden" name="checker" value="<?php echo $r->Checker;?>">
                                    <input type="hidden" name="employee_id[]" value="<?php echo $r->Employee_ID;?>">
                                    <input type="hidden" name="status[]" value="<?php echo $r->Status;?>">
                                    <input type="hidden" name="resign_date[]" value="<?php echo $r->Resign_Date;?>">
                                    <input type="hidden" name="reason[]" value="<?php echo $r->Reason;?>">
                                </td>
                                <td>
                                    <?php echo $r->Approval_Name;?>
                                    <input type="hidden" name="approval" value="<?php echo $r->Approval;?>">
                                </td>
                                <td>
                                <label class="btn btn-xs <?=$class?>"><?= $status.$by?></label>
                                </td>
                            </tr>
                            <!-- end foreach-->
                            <?php } ?>
                        </tbody>
                    </table>
                    <br>
                    <?php
                        if($username == $checker) {
                            $where = "Request_ID = '$request_id' AND Checker_Status = '0' AND Hit_Code = '1001'";
                        }
                        else {
                            $where = "Request_ID = '$request_id' AND Approval_Status = '0' AND Hit_Code = '1008'";
                        }
                        $cek = $this->db->query("SELECT * FROM internal_sms.data_request_user WHERE $where ")->num_rows();
                        if($cek > 0) {
                    ?>
                        <input type="submit" class="btn btn-success" name="approve" value="Setujui" onclick="return confirm('Yakin disetujui?')">
                        <input type="submit" class="btn btn-danger" formaction="<?php echo site_url();?>approval_request/exit_form/reject/<?=$request_id?>" name="reject" value="Tolak" onclick="return confirm('Yakin ditolak?')">
                    <?php } else { ?>
                        <!--cek data approve-->
                        <?php
                            $query_approval = $this->db->query("SELECT * FROM internal_sms.data_request_user WHERE Request_ID = '$request_id' AND Hit_Code IN('1003','1005')")->num_rows();
                            if($query_approval > 0) {
                        ?>
                            <input type="submit" class="btn btn-success" formaction="<?php echo site_url();?>approval_request/exit_form/send/<?=$request_id.'/'.$checker?>" name="finish" value="FINISH" onclick="return confirm('Sure to Finish?')">
                    <?php } else { ?>
                            <a href="<?php echo site_url();?>approval_request/exit_form"><input type="button" class="btn btn-primary" value="Back"></a>
                    <?php }} ?>

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
  
function validate(){
  var checked=false;
  var elements = document.getElementsByName("request_user_id[]");
  for(var i=0; i < elements.length; i++){
      if(elements[i].checked) {
          checked = true;
      }
  }
  if (!checked) {
      alert('Silahkan ceklis data !');
  }
  return checked;
}

function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}
</script>