<?php
$rows = $query;
$hidden = ($rows->Category == "Referral") ? "show" : "hidden";
?>
<div id="ribbon">
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; <a href="<?php echo site_url('input/pemol'); ?>">Pemol</a></li>
		<li>Detail Data</li>
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
			<span class="txt-color-blueDark">>
				<b>Detail Data</b>
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

		<div class="form-group">
			<label><b>Nomor Rekening </b></label>
			<input type="text" class="form-control" value="<?php echo $rows->Account_Number; ?>" disabled />
			<span><?php echo form_error('Account_Number'); ?></span>
		</div>
		<div class="form-group">
			<label><b>Source </b></label>
			<input type="text" class="form-control" value="<?php echo $rows->Source; ?>" disabled />
			<span><?php echo form_error('Account_Number'); ?></span>
		</div>
		<div class="form-group">
			<label><b>Kategori </b></label>
			<input type="text" class="form-control" value="<?php echo $rows->Category; ?>" disabled />
			<span><?php echo form_error('Account_Number'); ?></span>
		</div>
		<div class="div_referal" <?= $hidden ?>>
			<div class="form-group">
				<label><b>Referral </b></label>
				<input type="text" class="form-control" value="<?php echo $rows->Referral . ' - ' . $referalChoice; ?>" disabled />
				<span><?php echo form_error('Account_Number'); ?></span>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<a href="<?php echo site_url('input/pemol') ?>" class="btn btn-danger">
			<i class="fa-fw fa fa-step-backward"></i>
			Kembali
		</a>
	</div>
	<form>
</div>