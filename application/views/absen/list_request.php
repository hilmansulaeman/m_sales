<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Tables</a></li>
    <li class="breadcrumb-item"><a href="javascript:;">Longshift Request</a></li>
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
                <h4 class="panel-title">Longshift Request</h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <?php
            $sales_code = $this->session->userdata('sales_code');
            $cek = $this->db->query("SELECT * FROM data_absen WHERE kategori = 'masuk' AND approved_status != '' AND sales_code = '$sales_code' AND DAY(created_date) = DAY(NOW())");
            $cek2 = $cek->num_rows();

            if ($cek2 > 0) { ?>
                <div class="panel-body">
                    <table id="data-table-default" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style='width:5%;text-align:center'>No</th>
                                <th style='text-align:center'>Sales Code</th>
                                <th style='text-align:center'>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $spg = $this->db->query("SELECT * FROM data_absen WHERE kategori = 'masuk' AND sales_code = '$sales_code' AND DAY(created_date) = DAY(NOW())");
                            foreach ($spg->result() as $row) {
                                $sales_code = $row->sales_code;
                                ?>
                                <tr>
                                    <td align='center'><?php echo $no++; ?></td>
                                    <td align="center"><?php echo $row->sales_code; ?></td>
                                    <td style='text-align:center'>
                                        <?php
                                        if ($row->approved_status == "Approved") { ?>
                                            <p class="badge badge-success">Approved</p>
                                        <?php } else if ($row->approved_status == "Rejected") { ?>
                                            <p class="badge badge-danger">Rejected</p>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>

                </div>
            <?php } else {
                ?>
                <div class="panel-body">
                    <table id="data-table-default" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style='width:5%;text-align:center'>No</th>
                                <th style='text-align:center'>Sales Code</th>
                                <th style='text-align:center'>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $spg = $this->db->query("SELECT * FROM data_absen WHERE kategori = 'masuk' AND sales_code = '$sales_code' AND DAY(created_date) = DAY(NOW())");
                            foreach ($spg->result() as $row) {
                                $sales_code = $row->sales_code;
                                ?>
                                <tr>
                                    <td align='center'><?php echo $no++; ?></td>
                                    <td align="center"><?php echo $row->sales_code; ?></td>
                          
                                    <td style='text-align:center'>
                                        <?php if ($row->longshift_status == "YES") { ?>
                                            <p class="badge badge-warning">Waiting For Confirmation</p>
                                        <?php } else { ?>
                                            <a href="<?php echo site_url() . "request/approval/" . $sales_code; ?>" class='btn btn-primary btn-xs' data-original-title='Edit Row'>Request</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>

                </div>
            <?php } ?>
            <!-- end panel-body -->
        </div>
        <!-- end panel -->
    </div>
</div>

</div>
<!-- end row -->