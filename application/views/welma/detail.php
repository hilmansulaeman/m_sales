<div id="ribbon">
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; <a href="<?php echo site_url('input/welma'); ?>">Welma</a></li>
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
	<?php }?>
	<div id="ribbon" >	 
		<ol class="breadcrumb ">	 			
			<span class="txt-color-blueDark" >
			<i class="fa fa-table"></i>
				<b>Form</b>
			</span>
			<span class="txt-color-blueDark" >>  
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
	<form id="checkout-form" method="post" enctype="multipart/form-data" class="smart-form" onsubmit="return confirm('Data sudah benar?');" >
		<div class="row">
		<div class="col col-lg-4">
				<div>
				<label><b>Nama Customer </b><span style="color:red"></span></label>
					<input type="text" name="Customer_Name" id="Customer" value="<?php echo $query->Customer_Name; ?>"  class="form-control"disabled  />										
					<span><?php echo form_error('Customer_Name'); ?></span>				
				</div>
				
				<div>
				<label><b>Nomor Handphone </b><span style="color:red"></span></label>
					<input type="text" name="Phone_Number" id="Phone_Number" value="<?php echo $query->Phone_Number;  ?>" maxlength="13" onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" class="form-control" disabled />										
					<span><?php echo form_error('Phone_Number'); ?></span>
				</div>
				
				<div>
				<label><b>Email</b><span style="color:red"></span></label>
				<input type="text" name="Email" id="Email" value="<?php echo $query->Email; ?>"  class="form-control" disabled />										
					<span><?php echo form_error('Email'); ?></span>
				</div>
				
				<div>
				<label><b>Kode Promo</b><span style="color:red"></span></label>
				
				
				
				<input type="text" name="Kode_Promo" id="Kode_Promo" value="<?php echo $query->Kode_Promo; ?>"  class="form-control" disabled />
				
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
	</div> 
	<form>	
</div>