<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

?>

<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed|Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<div id="ribbon">
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; <a href="<?php echo site_url('input/welma'); ?>">Welma</a></li>
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
	<?php } ?>
	<div id="ribbon">
		<ol class="breadcrumb ">
			<span class="txt-color-blueDark">
				<i class="fa fa-table"></i>
				<b>Form</b>
			</span>
			<span class="txt-color-blueDark">
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
		<h3 class="box-title">FORM <?php echo $this->uri->segment(3); ?> </h3>
	</div>
	<div class="panel-body">
		<form action="<?php echo site_url(); ?>input/welma/edit/<?php echo $query->Customer_ID; ?>" method="post" enctype="multipart/form-data">
			<div class="row">
			<div class="col col-lg-4">
					<input type="hidden" name="Modified_Date" id="Modified_Date" value="<?= date('Y-m-d H:i:s') ?>">
					<input type="hidden" name="Modified_By" id="Modified_By" value="<?= $this->session->userdata('realname') ?>">

					<div>
						<label><b>Nama Customer </b><span style="color:red">*</span></label>
						<input type="text" name="Customer_Name" id="Customer" value="<?php echo $query->Customer_Name; ?>" class="form-control" required />
						<span><?php echo form_error('Customer_Name'); ?></span>
					</div>
					<div>
						<label><b>Nomor Handphone </b><span style="color:red">*</span></label>
						<input type="text" name="Phone_Number" id="Phone_Number" value="<?php echo $query->Phone_Number; ?>" maxlength="13" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" class="form-control" required />
						<span><?php echo form_error('Phone_Number'); ?></span>


					</div>
					<div>
						<label><b>Email</b><span style="color:red">*</span></label>
						<input type="text" name="Email" id="Email" value="<?php echo $query->Email; ?>" class="form-control" required />
						<span><?php echo form_error('Email'); ?></span>
					</div>
					<div>
						<label><b>Kode Promo</b><span style="color:red">*</span></label>

						<select class="form-control" name="Kode_Promo" id="Kode_Promo" required>
							<option value="">No Selected</option>
							<?php
							$a = $query->Kode_Promo;
							foreach ($promo as $s) {
								$b = $s->Kode_Promo;
							?>
								<option value="<?php echo $s->Kode_Promo; ?>" <?php if ($a == $b) {
																							echo "selected";
																						} ?>><?php echo $s->Kode_Promo; ?></option>
							<?php } ?>
						</select>
						<span><?php echo form_error('Kode_Promo'); ?></span>
					</div>
				</div>
			</div>

	</div>
	<div class="box-footer">
		<button type="submit" class="btn btn-primary">Update</button>
		<a href="<?php echo site_url('input/welma') ?>" class="btn btn-danger">
			<i class="fa-fw fa fa-step-backward"></i>
			Kembali
		</a>
	</div>
	</form>
</div>