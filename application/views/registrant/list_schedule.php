<?php if (! defined('BASEPATH')) exit('No direct script access allowed');?>
<div id="page-wrapper">
        <div class="col-md-12 col-sm-12">
            <h1 class="page-header">Schedule Training</h1>
        </div>
        <!-- /.col-lg-12 -->
    <!-- /.row -->
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                    	<p>Silahkan klik tombol Update pada schedule yang tersedia</p>
						<?php echo form_open(); ?>
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th style="text-align:center">No</th>
                                    <th style="text-align:center">Area</th>
                                    <th style="text-align:center">Location</th>
                                    <th style="text-align:center">Time</th>
                                    <th style="text-align:center">Available Date</th>
                                    <th style="text-align:center">Available Seat</th>
                                    <th style="text-align:center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; ?>
                            <?php foreach($query->result() as $row){ ?>
                                <tr class="odd gradeX">
                                    <td align="center"><?php echo ++$i; ?></td>
                                    <td align="center"><?php echo $row->area; ?></td>
                                    <td align="center"><?php echo $row->location; ?></td>
                                    <td align="center"><?php echo $row->time; ?></td>
                                    <td align="center"><?php echo $row->available_date; ?></td>
                                    <td align="center"><?php echo $row->available_seat; ?> Seat</td>
                                    <td align="center">
                                    <?php
									$seat = $row->available_seat;
									if($seat == 0){
										$book = 'disabled=disabled';
									}
									else{
										$book = '';
									}
									?>
									<a class="btn btn-xs btn-success" href="<?php echo site_url() ?>registrant/update_sch/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>/<?php echo $row->id; ?>" title="Edit">Update</a>
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