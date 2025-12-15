<?php $level = $this->session->userdata('level'); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Peserta Training</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <a href="<?php echo site_url(); ?>export/registrant/export/<?php echo $rows->id; ?>"><button class="btn btn-primary">Export</button></a>
    <br /><br />
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Data Peserta
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                    	<table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <td><label>Area</label></td>
                                            <td><?php echo $rows->area; ?></td>
                                        </tr>
                                        <tr>
                                            <td><label>Lokasi</label></td>
                                            <td><?php echo $rows->location; ?></td>
                                        </tr>
                                        <tr>
                                            <td><label>Hari / Tanggal</label></td>
                                            <td><?php echo $rows->training_day." / ".date('d M Y', strtotime ($rows->available_date)); ?></td>
                                        </tr>
                                        <tr>
                                            <td><label>Waktu Training</label></td>
                                            <td><?php echo $rows->time; ?></td>
                                        </tr>
                                        <tr>
                                            <td><label>Quota</label></td>
                                            <td><?php echo $rows->quota; ?></td>
                                        </tr>
                                        <tr>
                                            <td><label>Available Seat</label></td>
                                            <td><?php echo $rows->available_seat; ?></td>
                                        </tr>
                                        </tbody>
                                        </table>
                                        
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th align="center">No</b></th>
                                    <th>Nama Peserta</th>
                                    <th>Nama Merchant</th>
                                    <th>No Telp</th>
                                    <th>Email</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; ?>
                            <?php foreach($query->result() as $rows){ ?>
                                <tr class="odd gradeX">
                                    <td align="center"><?php echo ++$i; ?></td>
                                    <td><?php echo $rows->nama_kasir; ?></td>
                                    <td><?php echo $rows->nama_merchant; ?></td>
                                    <td><?php echo $rows->tlp_kasir; ?></td>
                                    <td><?php echo $rows->email; ?></td>
                                    <td align="center">
                                    <a href="<?php echo site_url()."schedule/detail_registrant/".$rows->id; ?>" title="Edit Data"><span class="glyphicon glyphicon-edit"></span></a>
                                    <?php if($level == '1' || $level == '2'){ ?>
                                    <a href="<?php echo site_url()."schedule/delete/".$rows->id; ?>" onClick="return confirm('Delete this data?')" title='Delete Data'><span class="glyphicon glyphicon-remove"></span></a>
                                    <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->