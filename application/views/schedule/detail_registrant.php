<?php if (! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $rows = $request_data->row(); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Detail registrant</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Detail Registrant
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                 <div class="table-responsive">
                                        <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <td><label>Nama Peserta</label></td>
                                            <td><?php echo $rows->nama_kasir; ?></td>
                                        </tr>
                                        <tr>
                                            <td><label>Nama Merchant</label></td>
                                            <td><?php echo $rows->nama_merchant; ?></td>
                                        </tr>
                                        <tr>
                                            <td><label>Telp Peserta</label></td>
                                            <td><?php echo $rows->tlp_kasir; ?></td>
                                        </tr>
                                        <tr>
                                            <td><label>Email Peserta</label></td>
                                            <td><?php echo $rows->email; ?></td>
                                        </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                    <a href="<?php echo site_url(); ?>schedule/"><button class="btn btn-primary">Back</button></a>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
                
            </div>
            <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->