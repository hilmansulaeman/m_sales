



    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Form Add Merchant
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?php echo site_url();?>testimoni">Merchant</a></li>
        <li class="active">Add</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <!-- form start -->
              <div class="box-body">

				<form action="" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>Kolom (<span style="color:red">*</span>) wajib di isi !</label>
					</div>

					<div class="row row-space-10">
						<div class="col-md-3">
							<div class="form-group m-b-10">
								<input type="hidden" name="sales_code" value="<?= $this->session->userdata('username') ?>">
								<input type="hidden" name="sales_name" value="<?= $this->session->userdata('realname') ?>">
								<input type="hidden" name="created_by" value="<?= $this->session->userdata('realname') ?>">
								<input type="hidden" name="branch" value="test_branch">
								<label>Nama Owner <span style="color:red">*</span></label>
								<?php echo form_error('owner_name'); ?>
								<input type="text" class="form-control" required autocomplete="off" id="owner_name" name="owner_name" value="<?php echo set_value('owner_name'); ?>"/>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group m-b-10">
								<label>Nama Merchant <span style="color:red">*</span></label>
								<?php echo form_error('merchant_name'); ?>
								<input type="text" class="form-control" required autocomplete="off"  id="merchant_name" name="merchant_name" value="<?php echo set_value('merchant_name'); ?>"/>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group m-b-10">
								<label>Email <span style="color:red">*</span></label>
								<?php echo form_error('email'); ?>
								<input type="email" name="email" id="email" autocomplete="off" value="<?php echo set_value('email'); ?>" class="form-control" value="<?php echo set_value('email'); ?>" required/>
							</div>
						</div>
			
						<div class="col-md-3">
							<label for="">Status Merchant <span style='color:red'><b>*</b></span></label>
							<?php echo form_error('mid_type'); ?>
							<div class="form-group">
								<select class="form-control" name="mid_type" id="mid_type" style="width: 100%" required>
									<option value="">--- Pilih ---</option>
									<option value="NEW MERCHANT" <?php echo set_select('mid_type','NEW MERCHANT', ( !empty($mid_type) && $mid_type == "NEW MERCHANT" ? TRUE : FALSE )); ?>> NEW MERCHANT </option>
									<option value="EXISTING MERCHANT" <?php echo set_select('mid_type','EXISTING MERCHANT', ( !empty($mid_type) && $mid_type == "EXISTING MERCHANT" ? TRUE : FALSE )); ?>> EXISTING MERCHANT </option>
								</select>
							</div>
							
						</div>
					</div>

					<div class="row row-space-10">
						<div class="col-md-3">
							<label for="">No Rekening <span style='color:red'><b>*</b></span></label>
							<?php echo form_error('account_number'); ?>
							<div class="form-group">
								<input type="text" id="account_number" name="account_number" value="<?php echo set_value('account_number'); ?>" class="form-control"onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" autocomplete="off" required>
							</div>
							
						</div>
						
						<div class="col-md-3">
							<div class="form-group m-b-10">
								<label>No Handphone <span style="color:red">*</span></label>
								<?php echo form_error('mobile_phone_number'); ?>
								<input type="text" class="form-control" required autocomplete="off" id="mobile_phone_number" name="mobile_phone_number" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" value="<?php echo set_value('mobile_phone_number'); ?>"/>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group m-b-10">
								<label>No Handphone Lain</label>
								<input type="text" class="form-control" autocomplete="off" id="other_phone_number" name="other_phone_number" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" value="<?php echo set_value('other_phone_number'); ?>"/>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Area Akusisi <span style="color:red">*</span> :</label>
								<?php echo form_error('area_akusisi'); ?>
								<select class="form-control" id="officer_code" name="officer_code" style="width: 100%" required>
									<option value="">-- Pilih -- </option>
									<?php 
										foreach($area_akusisi as $a) {
									?>
										<option value="<?php echo $a->area_akusisi;?>" <?php echo set_select('officer_code','<?php echo $a->area_akusisi;?>', ( !empty($officer_code) && $officer_code == "<?php echo $a->area_akusisi;?>" ? TRUE : FALSE )); ?>> <?php echo $a->description;?> </option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
				<input type="submit" class="btn btn-primary" name="simpan" value="Next">
				<?=anchor('insentif/merchant/index/new_merchant','Back',array('class'=>'btn btn-warning'))?>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
