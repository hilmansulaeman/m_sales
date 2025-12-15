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
        FORM DEVIASI
        <?php echo $this->session->flashdata('message'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">DEVIASI Form</li>
    </ol>
</section>



<!-- Main content -->
<section class="content">

    <div class="row">

        <div class="col-md-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Permintaan Form Deviasi</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="data-request" class="table table-responsive table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="1%"><input type="checkbox" onclick="toggle(this);" /></th>
                                <th>Kode Sales</th>
                                <th>Nama Sales</th>
                                <th>Posisi</th>
                                <th>Level</th>
                                <th>Keterangan</th>
                                <th>Dibuat Oleh</th>
                                <th width="5%">Persetujuan RSM</th>
                                <th width="5%">Persetujuan BSH</th>
                                <th width="5%">Persetujuan GM</th>
                                <?php
                                $position = $this->session->userdata('position');
                                if ($position == 'GM') {
                                    echo '<th>Catatan HRD</th>';
                                }
                                ?>
                                <th width="10%">Status</th>
                            </tr>
                        </thead>

                        <form action="<?= site_url(); ?>approval_request/deviasi_form/approve/<?= $request_id ?>" method="POST" onsubmit="return validate(this);">
                            <tbody>
                                <?php

                                $no = 1;
                                foreach ($request_user->result() as $r) {
                                    // var_dump('test');
                                    // die;
                                    global $statusRSM,
                                        $statusBSH,
                                        $statusGM;

                                    $dibuatoleh = $r->Created_By;
                                    $querydibuatoleh = $this->db->query("SELECT * FROM db_hrd.data_employee WHERE DSR_Code = '$dibuatoleh'")->row();
                                    $namadibuatoleh = $querydibuatoleh->Name;


                                    $request_id = $r->Request_ID;

                                    $request_user_id = $r->Request_User_ID;

                                ?>
                                    <tr>
                                        <?php
                                        $q = $this->db->query("SELECT * FROM internal_sms.data_request_approval WHERE Sales_Code = '$username' AND Request_User_ID = '$request_user_id'")->row();
                                        if ($q->Status == '0') {
                                        ?>
                                            <td><input type="checkbox" name="request_user_id[]" value="<?php echo $r->Request_User_ID; ?>"></td>
                                        <?php } else { ?>
                                            <td>&nbsp;</td>
                                        <?php } ?>

                                        <td><?php echo $r->Sales_Code; ?></td>
                                        <td><?php echo $r->Sales_Name; ?></td>
                                        <td><?php echo $r->Position; ?></td>
                                        <td><?php echo $r->Level; ?></td>
                                        <td><?php echo $r->Reason; ?></td>
                                        <td>
                                            <?php echo $namadibuatoleh; ?>
                                            <input type="hidden" name="checker" value="<?php echo $r->Checker; ?>">
                                            <input type="hidden" name="employee_id[]" value="<?php echo $r->Employee_ID; ?>">
                                            <input type="hidden" name="reason[]" value="<?php echo $r->Reason; ?>">
                                        </td>
                                        <td>
                                            <?php

                                            $queryStatus = $this->db->query(
                                                "SELECT `Status`, `Position`
                                                FROM data_request_approval 
                                                WHERE Request_User_ID = '$request_user_id'"
                                            )->result();

                                            foreach ($queryStatus as $key => $value) {
                                                $outStr = "";

                                                if ($value->Status == 1) {
                                                    $outStr = "Disetujui";
                                                } elseif ($value->Status == 0) {
                                                    $outStr = "Belum Disetujui";
                                                }
                                                if ($value->Position == 'RSM') {
                                                    $statusRSM = $outStr;
                                                } elseif ($value->Position == 'BSH') {
                                                    $statusBSH = $outStr;
                                                } elseif ($value->Position == 'GM') {
                                                    $statusGM = $outStr;
                                                } else {
                                                    $statusGM = "Belum Disetujui";
                                                }
                                            }
                                            ?>
                                            <?= $statusRSM; ?>
                                        </td>
                                        <td>
                                            <?= $statusBSH; ?>
                                        </td>
                                        <td>
                                            <?= $statusGM; ?>
                                        </td>
                                        <?php $position = $this->session->userdata('position');
                                        if ($position == 'GM') {
                                            echo '<td>' . $r->Note . '</td>';
                                        }
                                        ?>
                                        <td>
                                            <?php
                                            $cek = $this->db->query("SELECT * FROM data_request_approval WHERE Request_User_ID = '$request_user_id' ORDER BY Request_Approval_ID ASC LIMIT 1 ")->row();

                                            $approval = $cek->Sales_Name;
                                            $status = $cek->Status;
                                            if ($status == '0') {
                                                $label = 'Data Baru ';
                                                $approval_name = '';
                                            } else {
                                                $label = 'Disetujui Oleh <br>';
                                                $approval_name = $r->Approval_Name;
                                            }
                                            ?>
                                            <label class="label label-xs label-info"><?= $label ?><?= $approval_name ?></label>
                                        </td>
                                    </tr>
                                    <!-- end foreach-->
                                <?php }
                                ?>

                            </tbody>
                    </table>
                    <br>
                    <?php $q_cek = $this->db->query("SELECT * FROM internal_sms.data_request_approval WHERE Sales_Code = '$username' AND Status = '0'")->num_rows();
                    if ($q_cek > 0) {
                    ?>
                        <input type="submit" class="btn btn-success" name="approve" value="Setujui" onclick="return confirm('Yakin setujui data ini?')">
                        <input type="submit" class="btn btn-danger" formaction="<?php echo site_url(); ?>approval_request/deviasi_form/reject/<?= $request_id ?>" name="reject" value="Tolak" onclick="return confirm('Yakin tolak data ini?')">
                    <?php } else { ?>
                        <a href="<?= site_url() ?>approval_request/deviasi_form" class="btn btn-primary">Back</a>
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
    function validate() {
        var checked = false;
        var elements = document.getElementsByName("request_user_id[]");
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].checked) {
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