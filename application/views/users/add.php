<?php if (! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Add User</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <?php echo form_open('');?>
        <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Add User
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Name : <span style="color:#FF0000">*</span></label>
                                        <input type="text" name="name" value="<?php echo set_value('name'); ?>" class="form-control" required/>
                                        <?php echo form_error('name'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Email : <span style="color:#FF0000">*</span></label>
                                        <input type="text" name="email" value="<?php echo set_value('email'); ?>" class="form-control" required/>
                                        <?php echo form_error('email'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Username : <span style="color:#FF0000">*</span></label>
                                        <input type="text" name="username" value="<?php echo set_value('username'); ?>" class="form-control" required/>
                                        <?php echo form_error('username'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Password : <span style="color:#FF0000">*</span></label>
                                        <input type="password" name="password" class="form-control" required/>
                                        <?php echo form_error('password'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Password Confirmation : <span style="color:#FF0000">*</span></label>
                                        <input type="password" name="password_conf" class="form-control" required/>
                                        <?php echo form_error('password'); ?>
                                    </div>
                                    <div class="form-group">
                                        <?php
											echo form_label('Privilege');
											// show dropdown privilege
											foreach($level->result() as $row)
											{
												$array_level[$row->prv_id] = $row->prv_name;
											}
											echo form_dropdown('privilege',$array_level,set_value('privilege'),'class="form-control"'); 
										?>
                                        <?php echo form_error('privilege');?>
                                    </div>
                                    <input type="submit" value="Submit" class="btn btn-primary" />
                                    <?php echo form_close();?>
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