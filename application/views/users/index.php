<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Data Users</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Data Users
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th align="center">No</b></th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Privilege</th>
                                    <th align="center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; ?>
                            <?php foreach($query->result() as $rows){ ?>
                                <tr class="odd gradeX">
                                    <td align="center"><?php echo ++$i; ?></td>
                                    <td><?php echo $rows->username; ?></td>
                                    <td><?php echo $rows->name; ?></td>
                                    <td><?php echo $rows->email; ?></td>
                                    <td><?php echo $rows->privilege; ?></td>
                                    <td align="center">
                                    <a href="<?php echo site_url()."users/edit/".$rows->id; ?>" title="Edit Data"><span class="glyphicon glyphicon-edit"></span></a>
                                    <a href="<?php echo site_url()."users/delete/".$rows->id; ?>" onClick="return confirm('Delete this data?')" title='Delete Data'><span class="glyphicon glyphicon-remove"></span></a>
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