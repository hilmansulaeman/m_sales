<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$rows = $query;
$id = $rows->RegnoId;
$hidden = ($this->session->userdata('show')) ? '' : 'hidden';
?>

<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed|Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<div id="ribbon">
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; <a href="<?php echo site_url('input/pemol'); ?>">Pemol</a></li>
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
		<?= form_open('input/pemol/edit/' . $id); ?>
		<div class="form-group">
			<label><b>Nomor Rekening </b> <code>*</code></label>
			<input type="text" name="Account_Number" id="Account_Number" value="<?php echo $rows->Account_Number; ?>" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" class="form-control" required autocomplete="off" />
			<span><?php echo form_error('Account_Number'); ?></span>
		</div>

		<div class="form-group">
			<label><b>Source </b> <code>*</code></label>
			<select name="Source" id="Source" class="form-control select2" required>
				<option value="">-- Pilih --</option>
				<?php foreach ($ref_source->result() as $rs) {
					if ($rs->Source == $rows->Source) {
						$selectedSource = 'selected';
					} else {
						$selectedSource = '';
					} ?>
					<option value="<?= $rs->Source ?>" <?= $selectedSource ?> <?= set_select('Source', $rs->Source, FALSE); ?>><?= $rs->Source ?></option>
				<?php } ?>
			</select>
			<span><?php echo form_error('Source'); ?></span>
		</div>

		<div class="form-group">
			<label><b>Kategori</b> <code>*</code></label>
			<select name="Category" id="Category" class="form-control select2" required>
				<option value="">-- Pilih --</option>
				<?php foreach ($ref_category->result() as $rc) {
					if ($rc->Category == $rows->Category) {
						$selectedCategory = 'selected';
					} else {
						$selectedCategory = '';
					} ?>
					<option value="<?= $rc->Category ?>" <?= $selectedCategory ?> <?= set_select('Category', $rc->Category, FALSE); ?>><?= $rc->Category ?></option>
				<?php } ?>
			</select>
			<span><?php echo form_error('Category'); ?></span>
		</div>

		<div id="div_referal" <?= $hidden ?>>
			<div class="form-group">
				<label><b>Referral </b> <code>*</code></label>
				<select name="Referral" id="Referral" class="form-control select2">
					<option value="">-- Pilih --</option>
					<?php foreach ($ref_referal2 as $rr) {
						if ($rr->Referral == $rows->Referral) {
							$selectedReferral = 'selected';
						} else {
							$selectedReferral = '';
						} ?>
						<option value="<?= $rr->Referral ?>" <?= $selectedReferral ?> <?= set_select('Referral', $rr->Referral, FALSE); ?>><?= $rr->Referral . ' - ' . $rr->Description ?></option>
					<?php } ?>
				</select>
				<span><?php echo form_error('Referral'); ?></span>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button type="submit" class="btn btn-primary">Update</button>
		<a href="<?php echo site_url('input/pemol') ?>" class="btn btn-danger">
			<i class="fa-fw fa fa-step-backward"></i>
			Kembali
		</a>
	</div>
	<?= form_close(); ?>
</div>

<script>
	$(document).ready(function() {
		$('#Category').on('change', function() {
			if (this.value == 'Referral') {
				$("#div_referal").show();
				$("#Referral").prop('required', true);
			} else {
				$("#div_referal").hide();
				$("#Referral").prop('required', false);
				$("#Referral").val("");
			}
		});
	});
</script>