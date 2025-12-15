<?php
$jam = array("00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00");
?>
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="<?= base_url(""); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= base_url("request/overtime"); ?>">Lembur</a></li>
</ol>
<!-- end breadcrumb -->
<div class='row'>
    <!-- begin page-header -->
    <div class="col-lg-6">
        <h1 class="page-header">&nbsp;</h1>
    </div>
    <!-- end page-header -->
    <!-- notif -->
    <?php echo $this->session->flashdata('message'); ?>
    <!-- end notif -->
</div>
<!-- begin row -->
<div class="row">
    <div class="col-lg-12">
        <!-- begin panel -->
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>

                <h5 style="color:white">Lembur</h5>
            </div>
            <!-- end panel-heading -->

            <!-- Modal -->
            <?= form_open('request/add_overtime'); ?>
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Form Pengajuan Lembur</h5>
                            <button type="button" id="close" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <div class="form_group">
                                <label for="">Jam Mulai : <code>*</code></label>
                                <input type="text" name="Starting_Hour" id="Starting_Hour" class="form-control" placeholder="ex: 17:30" autocomplete="off" required onKeyUp="this.value=this.value.replace(/[^0-9-:]/g,'')">
                                <!-- <select class="form-control selectpicker" data-size="8" data-live-search="true" name="Starting_Hour" required>
                                    <option selected disabled>-- Atur Jam Mulai --</option>
                                    <?php foreach ($jam as $_jam) { ?>
                                        <option value="<?= $_jam ?>"><?= $_jam ?></option>
                                    <?php } ?>
                                </select> -->
                            </div>

                            <br>

                            <div class="form_group">
                                <label for="">Jam Selesai : <code>*</code></label>
                                <input type="text" name="Finishing_Hour" id="Finishing_Hour" class="form-control" placeholder="ex: 17:30" autocomplete="off" required onKeyUp="this.value=this.value.replace(/[^0-9-:]/g,'')">
                                <!--<select class="form-control selectpicker" data-size="8" data-live-search="true" name="Finishing_Hour" required>
                                    <option selected disabled>-- Atur Jam Selesai --</option>
                                     <?php foreach ($jam as $_jam) { ?>
                                        <option value="<?= $_jam ?>"><?= $_jam ?></option>
                                    <?php } ?> 
                                </select> -->
                            </div>

                            <br>

                            <div class="form_group">
                                <label for="">Keperluan : <code>*</code></label>
                                <textarea name="Necessity" id="Necessity" class="form-control" cols="30" rows="10" placeholder="Tulis keperluan untuk lembur . . . ." required></textarea>
                            </div>

                            <br>

                            <div class="form_group">
                                <label for="">Tanggal Request Lembur : <code>*</code></label>
                                <input type="date" class="form-control" name="Created_date" id="Created_date" required>
                                <!-- <textarea name="Created_date" id="Created_date" class="form-control" cols="30" rows="10" placeholder="Tulis keperluan untuk lembur . . . ." required></textarea> -->
                            </div>


                        </div>

                        <div class="modal-footer">
                            <button type="button" id="close" class="btn btn-danger" data-dismiss="modal">Batal <i class="fas fa-times"></i></button>
                            <button type="submit" class="btn btn-primary">Ajukan Lembur <i class="fas fa-check"></i></button>
                        </div>

                    </div>
                </div>
            </div>
            <?= form_close(); ?>
            <!-- Modal -->

            <!-- begin panel-body -->
            <div class="panel-body">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                    Request Lembur <i class="fa fa-plus"></i>
                </button>
                <br><br>
                <table id="data-table-default" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style='width:5%;text-align:center'>No</th>
                            <th style='text-align:center'>Sales Code</th>
                            <th style='text-align:center'>Name</th>
                            <th style='text-align:center'>Jam Mulai</th>
                            <th style='text-align:center'>Jam Selesai</th>
                            <th style='text-align:center'>Keterangan Lembur</th>
                            <th style='text-align:center'>Tanggal Lembur</th>
                            <th style='text-align:center'>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($overtime->result() as $row) {
                        ?>
                            <tr>
                                <td align='center'><?php echo $no++; ?></td>
                                <td align="center"><?php echo $row->Sales_Code; ?></td>
                                <td align="center"><?php echo $row->Name; ?></td>
                                <td align="center"><?php echo $row->Starting_Hour; ?></td>
                                <td align="center"><?php echo $row->Finishing_Hour; ?></td>
                                <td align="center"><?php echo $row->Necessity; ?></td>
                                <td align="center"><?php echo substr($row->Created_date, 0, 10); ?></td>
                                <td style='text-align:center'>
                                    <?php
                                    if ($row->Process_Code > "2" && $row->Approved_Status != "R") { ?>
                                        <p class="badge badge-success">Approved</p>
                                    <?php } else if ($row->Process_Code <= "2" && $row->Approved_Status != "R") { ?>
                                        <p class="badge badge-warning">Waiting Approval</p>
                                    <?php } else if ($row->Approved_Status == "R" && $row->Process_Code == "0") { ?>
                                        <p class="badge badge-danger">Rejected</p>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>

            </div>
            <!-- end panel-body -->
        </div>
        <!-- end panel -->
    </div>
</div>

</div>
<!-- end row -->