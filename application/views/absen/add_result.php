<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Input Dataaa</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Input Result
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php if (isset($error)) { ?>
                                    <div class="alert alert-warning">
                                        <?php echo $error . ' ' . $link_back; ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="alert alert-success">
                                        <?php echo $message; ?> <?php echo $link_back; ?>
                                    </div>
                                <?php } ?>
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