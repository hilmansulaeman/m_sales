<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<!-- Check Duplicate -->
<script>
	// function check_rekening(){
	//     var id  = $('#Account_Number').val(); //Get the value in the no_rek textbox

	//     $.ajax({
	//         url: '<?php echo site_url('ajax/check_rekening'); ?>/'+id,
	// 		type: 'post',
	//         dataType: 'html',
	// 		beforeSend : function(){
	// 			//$("#error_rekening").html('<img src="<?php //echo base_url(); 
															?>public/images/loader.gif" align="absmiddle">&nbsp;Checking...');
	// 		},
	//         success:
	// 		function(result){
	// 			console.log(result);
	// 			$('#error_rekening').html(result);
	// 		}
	//     });
	// }
</script>

<div id="ribbon">
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; <a href="<?php echo site_url('input/welma'); ?>">Welma</a></li>
		<li>Add Data</li>
	</ol>
</div>

<div id="content">
	<?php if ($this->session->flashdata('message')) { ?>
		<div class="alert alert-warning fade in">
			<button class="close" data-dismiss="alert" id="notif">
				�
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
		<h3 class="box-title">FORM <?php echo $this->uri->segment(3); ?> </h3>
	</div>
	<div class="panel-body">
		<form id="checkout-form" action="<?php echo site_url(); ?>input/welma/add" method="post" enctype="multipart/form-data" class="smart-form" onsubmit="return confirm('Data sudah benar?');">
			<div class="row">
				<div class="col col-lg-4">
					<input type="hidden" name="Sales_Code" id="Sales_Code" value="<?= $this->session->userdata('username') ?>">
					<input type="hidden" name="Sales_Name" id="Sales_Name" value="<?= $this->session->userdata('realname') ?>">
					<input type="hidden" name="Branch" id="Branch" value="Example_Branch">
					<input type="hidden" name="Input_Date" id="Input_Date" value="<?= date('Y-m-d') ?>">
					<div class="form-group">
						<label><b>Nama Customer </b><span style="color:red">*</span></label>
						<input type="text" name="Customer_Name" id="Customer" value="<?php echo set_value('Customer_Name'); ?>" class="form-control" required />
						<span><?php echo form_error('Customer_Name'); ?></span>
					</div>
					<div>
						<label><b>Nomor Handphone </b><span style="color:red">*</span></label>
						<input type="text" name="Phone_Number" id="Phone_Number" value="<?php echo set_value('Phone_Number'); ?>" maxlength="13" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" class="form-control" required />
						<span><?php echo form_error('Phone_Number'); ?></span>


					</div>
					<div>
						<label><b>Email</b><span style="color:red">*</span></label>
						<input type="text" name="Email" id="Email" value="<?php echo set_value('Email'); ?>" class="form-control" required />
						<span><?php echo form_error('Email'); ?></span>
					</div>
					<div>
						<label><b>Kode Promo</b><span style="color:red">*</span></label>

						<select class="form-control" name="Kode_Promo" id="Kode_Promo" required>
							<option value="">No Selected</option>
							<?php foreach ($promo as $a) { ?>
								<option value="<?= $a->Kode_Promo; ?>" <?php echo set_select('Kode_Promo', $a->Kode_Promo, False); ?>> <?= $a->Kode_Promo ?></option>
							<?php } ?>
						</select>
						<span><?php echo form_error('Kode_Promo'); ?></span>
					</div>
				</div>
			</div>
	</div>
	<div class="box-footer">
		<a href="<?php echo site_url('input/welma') ?>" class="btn btn-danger">
			<i class="fa-fw fa fa-step-backward"></i>
			Kembali
		</a>
		<button type="submit" class="btn btn-primary">Submit</button>
	</div>
	<form>
</div>
<!--<script>
	$(document).ready(function(){
		$('#kategori').on('change', function() {
		if ( this.value == 'Canvassing')      
		{      	
			$("#div_canvassing").show();
			$("#div_event").hide();
		}
		else 
		{		
			$("#div_canvassing").hide(); 	        	 
			$("#div_event").show(); 	
		}      
		});
	});
	</script>-->
<script>
	function viewEvent() {
		category = document.getElementById("kategori").value;
		$.ajax({
			url: "<?php echo site_url('input/pemol/get_event'); ?>/" + category,
			success: function(response) {
				$("#event").html(response);
			},
			dataType: "html"
		});
		return false;
	}
</script>