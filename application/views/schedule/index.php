<?php $level = $this->session->userdata('level'); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Schedule Training</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <a href="<?php echo site_url(); ?>export/request"><button class="btn btn-primary">Export</button></a>
    <br /><br />
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Data Schedule
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th align="center">No</b></th>
                                    <th>Lokasi</th>
                                    <th>Area</th>
                                    <th>Quota</th>
                                    <th>Available Seat</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; ?>
                            <?php foreach($query->result() as $rows){ ?>
                                <tr class="odd gradeX">
                                    <td align="center"><?php echo ++$i; ?></td>
                                    <td><b><?php echo $rows->location; ?></b>
                                    	<small><p>Tgl Training : <?php echo $rows->available_date; ?><br />
                                        Jam Training : <?php echo $rows->time; ?></p></small>
                                    </td>
                                    <td><?php echo $rows->area; ?></td>
                                    <td><?php echo $rows->quota; ?></td>
                                    <td><?php echo $rows->available_seat; ?></td>
                                    <td align="center">
                                    <a href="<?php echo site_url()."schedule/detail/".$rows->id; ?>" title="Detail Data"><span class="glyphicon glyphicon-edit"></span></a>
                                    <a href="<?php echo site_url()."schedule/registrant/".$rows->id; ?>" title="Data Peserta"><span class="glyphicon glyphicon-user"></span></a>
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