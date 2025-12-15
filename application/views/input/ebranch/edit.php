<div id="ribbon">
	<span class="ribbon-button-alignment"> 
		<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
			<i class="fa fa-refresh"></i>
		</span> 
	</span>	
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; <a href="<?php echo site_url('input/edc/index/new_merchant'); ?>">Index</a></li>
		<li>Edit Data</li>
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
				<b>Edit Data</b>
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
	<?php echo form_open('input/edc/edit/'.$db->RegnoId); ?>	
		<div class="panel-body">
			<?php
				$hitcode = $db->Hit_Code;
				if($hitcode == '103') {
			?>
				<div class="form-group">
						<label>Reason Return<span style="color:red">*</span> :</label>
						<textarea rows="3" class="form-control" readonly style="color:red; font-weight:bold"><?php echo $db->Reason_Category;?> / <?php echo $db->Reason_Detail;?></textarea>
				</div>	
			<?php } elseif($hitcode == '104') {?>
				<div class="form-group">
						<label>Reason Return<span style="color:red">*</span> :</label>
						<textarea rows="3" class="form-control" readonly style="color:red; font-weight:bold"><?php echo $db->FU_Reason;?> / <?php echo $db->FU_Note;?></textarea>
				</div>	
			<?php } ?>
		
			<div class="row">
				<div class="col-md-4">
					<label for="">Jenis Pengajuan <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('product_type'); ?>
					<div class="form-group">
						<select class="default-select2 form-control" name="product_type" id="product_type" style="width: 100%" required>
							<option value="">--- Pilih ---</option>
							<?php
								$product_type = $db->Product_Type;
							?>
							<option value="EDC" <?php if($product_type == 'EDC'){ echo "selected";}?>> EDC </option>
							<option value="EDC_QRIS" <?php if($product_type == 'EDC_QRIS'){ echo "selected";}?>> EDC & QRIS </option>
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
						<input type="email" name="email" class="form-control" value="<?php echo set_value('Email',$db->Email);?>" autocomplete="off" placeholder="Email Wajib di isi !">
					</div>
					
				</div>
				
				<div class="col-md-4">
					<label for="">Status Merchant <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('mid_type'); ?>
					<div class="form-group">
						<select class="default-select2 form-control" name="mid_type" id="mid_type" style="width: 100%" required>
							<option value="">--- Pilih ---</option>
							<?php
								$mid_type = $db->MID_Type;
							?>
							<option value="NEW" <?php if($mid_type == 'NEW'){ echo "selected";}?>> New Merchant </option>
							<option value="Tambah Outlet" <?php if($mid_type == 'Tambah Outlet'){ echo "selected";}?>> Tambahan Cabang </option>
							<option value="Tambah EDC" <?php if($mid_type == 'Tambah EDC'){ echo "selected";}?>> Tambahan Terminal </option>
							<option value="Perubahan Data" <?php if($mid_type == 'Perubahan Data'){ echo "selected";}?>> Perubahan Data </option>
							<option value="Ubah Fasilitas" <?php if($mid_type == 'Ubah Fasilitas'){ echo "selected";}?>> Ubah Fasilitas </option>
							<option value="CUP"> CUP </option <?php if($mid_type == 'CUP'){ echo "selected";}?>>
						</select>
					</div>
					
				</div>
			</div>

			<div class="row">
				<input type="hidden" name="Sales_Code" value="<?php echo $this->session->userdata('username');?>">
				<div class="col-md-6">
					<label for="">Nama Owner <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('owner_name'); ?>
					<div class="form-group">
						<input type="text" id="Owner_Name" name="owner_name" class="form-control" value="<?php echo set_value('Owner_Name',$db->Owner_Name);?>" autocomplete="off" required>
					</div>
					
				</div>
				<div class="col-md-6">
					<label for="">Nama Merchant <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('merchant_name'); ?>
					<div class="form-group">
						<input type="text" id="Merchant_Name" name="merchant_name" class="form-control" value="<?php echo set_value('Merchant_Name',$db->Merchant_Name);?>" autocomplete="off" required>
					</div>
					
				</div>
				<div class="col-md-4">
					<label for="">No Rekening <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('account_number'); ?>
					<div class="form-group">
						<input type="text" id="account_number" name="account_number" value="<?php echo set_value('Account_Number',$db->Account_Number);?>" class="form-control"onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" autocomplete="off" required>
					</div>
					
				</div>
				<div class="col-md-4">
					<label for="">No Handphone <span style='color:red'><b>*</b></span></label>
					<?php echo form_error('mobile_phone_number'); ?>
					<div class="form-group">
						<input type="text" id="Mobile_Phone_Number" name="mobile_phone_number" value="<?php echo set_value('Mobile_Phone_Number',$db->Mobile_Phone_Number);?>" class="form-control"onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" autocomplete="off" required>
					</div>
				</div>
				<div class="col-md-4">
					<label for="">No Handphone Lain</label>
					<?php echo form_error('other_phone_number'); ?>
					<div class="form-group">
						<input type="text" id="Other_Phone_Number" name="other_phone_number" value="<?php echo set_value('Other_Phone_Number',$db->Other_Phone_Number);?>" class="form-control"onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" autocomplete="off">
					</div>
				</div>
			</div>
		</div>

		<div class="box-footer">
			<?php
				$hc_allow = array('102','103','104','107');
				$hc = $db->Hit_Code;
				if(in_array($hc, $hc_allow)) {
			?>
					<input type="submit" class="btn btn-primary" name="simpan" value="Update">
			<?php } ?>
			<?=anchor('input/edc/index/new_merchant','Back',array('class'=>'btn btn-warning'))?>
		</div>
	<?= form_close() ?>
</div>

<script type='text/javascript'>

$(document).ready(function(){
	
	//$('#email').hide();
	
	$('#product_type').change(function(){
		var val = $(this).val();
		
		if(val == 'EDC_QRIS'){
			$('#email').show('slow');
		}
		else{
			$('#email').hide('slow');
		}
	});
});
</script>