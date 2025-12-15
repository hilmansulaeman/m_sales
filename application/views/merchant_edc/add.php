<div id="ribbon">
	<span class="ribbon-button-alignment"> 
		<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
			<i class="fa fa-refresh"></i>
		</span> 
	</span>	
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; <a href="<?php echo site_url('input/merchant_edc/index/new_merchant'); ?>">Index</a></li>
		<li>Add Data</li>
	</ol>
</div>

<div id="content">
	<?php if ($this->session->flashdata('message')) { ?>
		<div class="alert alert-warning fade in">
		<button class="close" data-dismiss="alert" id="notif">
			×
		</button>
		<i class="fa-fw fa fa-check"></i>
		<?php echo $this->session->flashdata('message'); ?>
		</div>
	<?php }?>
	<div id="ribbon" >	 
		<ol class="breadcrumb ">	 			
			<span class="txt-color-blueDark" >
			<i class="fa fa-table"></i>
				<b>Form</b>
			</span>
			<span class="txt-color-blueDark" >>  
				<b>Add Data</b>
			</span>			 			
		</ol>
	</div>
</div>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse">&nbsp;</i>
			</button>
		</div>		
		<h3 class="box-title">FORM <?php echo $this->uri->segment(3); ?> EDC</h3>			  
	</div>
	<?= form_open(''); ?>
		<div class="panel-body">
			<div class="row">
				<input type="hidden" name="sales_code" id="sales_code" value="<?= $this->session->userdata('username') ?>">
				<input type="hidden" name="sales_name" id="sales_name" value="<?= $this->session->userdata('realname') ?>">
				<input type="hidden" name="branch" id="branch" value="Example_Branch">
				
				<div class="col-md-4">
					<label for="">Jenis Pengajuan <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('product_type'); ?>
					<div class="form-group">
						<select class="default-select2 form-control" name="product_type" id="product_type" style="width: 100%" required>
							<option value="">--- Pilih ---</option>
							<option value="EDC" <?php echo set_select('product_type','EDC', ( !empty($product_type) && $product_type == "EDC" ? TRUE : FALSE )); ?>> EDC </option>
							<option value="EDC_QRIS" <?php echo set_select('product_type','EDC_QRIS', ( !empty($product_type) && $product_type == "EDC_QRIS" ? TRUE : FALSE )); ?>> EDC & QRIS </option>
						</select>

					</div>
				</div>
				<?php
				$display_email = ($product_type == 'EDC_QRIS')? '':'none';
				?>
				<div id="email" class="col-md-4" style="display:<?php echo $display_email; ?>">
					<label for="">Email <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('email'); ?>
					<div class="form-group">
						<input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" autocomplete="off" placeholder="Email Wajib di isi !">
					</div>
					
				</div>
				
				<div class="col-md-4">
					<label for="">Status Merchant <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('mid_type'); ?>
					<div class="form-group">
						<select class="default-select2 form-control" name="mid_type" id="mid_type" style="width: 100%" required>
							<option value="">--- Pilih ---</option>
							<option value="NEW" <?php echo set_select('mid_type','NEW', ( !empty($mid_type) && $mid_type == "NEW" ? TRUE : FALSE )); ?>> New Merchant </option>
							<option value="Tambah Outlet" <?php echo set_select('mid_type','Tambah Outlet', ( !empty($mid_type) && $mid_type == "Tambah Outlet" ? TRUE : FALSE )); ?>> Tambahan Cabang </option>
							<option value="Tambah EDC" <?php echo set_select('mid_type','Tambah EDC', ( !empty($mid_type) && $mid_type == "Tambah EDC" ? TRUE : FALSE )); ?>> Tambahan Terminal </option>
							<option value="Perubahan Data" <?php echo set_select('mid_type','Perubahan Data', ( !empty($mid_type) && $mid_type == "Perubahan Data" ? TRUE : FALSE )); ?>> Perubahan Data </option>
							<option value="Ubah Fasilitas" <?php echo set_select('mid_type','Ubah Fasilitas', ( !empty($mid_type) && $mid_type == "Ubah Fasilitas" ? TRUE : FALSE )); ?>> Ubah Fasilitas </option>
							<option value="CUP" <?php echo set_select('mid_type','CUP', ( !empty($mid_type) && $mid_type == "CUP" ? TRUE : FALSE )); ?>> CUP </option>
						</select>
					</div>
					
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<label for="">Nama Owner <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('owner_name'); ?>
					<div class="form-group">
						<input type="text" id="Owner_Name" name="owner_name" class="form-control" value="<?php echo set_value('owner_name'); ?>" autocomplete="off" required>
					</div>
					
				</div>
				<div class="col-md-6">
					<label for="">Nama Merchant <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('merchant_name'); ?>
					<div class="form-group">
						<input type="text" id="Merchant_Name" name="merchant_name" class="form-control" value="<?php echo set_value('merchant_name'); ?>" autocomplete="off" required>
					</div>
					
				</div>
				<div class="col-md-4">
					<label for="">No Rekening <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('account_number'); ?>
					<div class="form-group">
						<input type="text" id="account_number" name="account_number" value="<?php echo set_value('account_number'); ?>" class="form-control"onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" autocomplete="off" required>
					</div>
					
				</div>
				<div class="col-md-4">
					<label for="">No Handphone <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('mobile_phone_number'); ?>
					<div class="form-group">
						<input type="text" id="Mobile_Phone_Number" name="mobile_phone_number" value="<?php echo set_value('mobile_phone_number'); ?>" class="form-control"onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" autocomplete="off" required>
					</div>
				</div>
				<div class="col-md-4">
					<label for="">No Handphone Lain </label>
					<?php echo form_error('other_phone_number'); ?>
					<div class="form-group">
						<input type="text" id="Other_Phone_Number" name="other_phone_number" value="<?php echo set_value('other_phone_number'); ?>" class="form-control"onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" autocomplete="off">
					</div>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<a href="<?= site_url('input/merchant_edc/index/new_merchant') ?>" class="btn btn-danger">
				<i class="fa-fw fa fa-step-backward"></i>
				Back
			</a>
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
	<?= form_close(); ?>
</div>

<script type='text/javascript'>

$(document).ready(function(){
	
	//$('#email').hide();
	
	$('#product_type').change(function(){
		var val = $(this).val();
		
		if(val == 'EDC_QRIS'){
			$('#email').show('slow');
			//$('#email').attribute('required', true);
		}
		else{
			$('#email').hide('slow');
			//$('#email').attribute('required', false);
		}
	});
});
</script>