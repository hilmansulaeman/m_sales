<?php
$row = $query->row();
?>
<form method="post" action="<?php echo base_url(); ?>meeting/location/ubah/<?php echo $this->uri->segment(4); ?>">
	<fieldset>
		<div class="form-group">
			<label class="col-form-label">Nama Lokasi : <span class="text-danger">*</span></label>
			<div class="input-group">
				<input type="text" name="location_name" value="<?php echo $row->Location_Name; ?>" class="form-control" required />
				<span class="input-group-addon"><i class="fa fa-building"></i></span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-form-label">Alamat Lokasi : <span class="text-danger">*</span></label>
			<div class="input-group">
				<input type="text" name="location_address" value="<?php echo $row->Location_Address; ?>" class="form-control" required />
				<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-form-label">Kota : <span class="text-danger">*</span></label>
			<div class="input-group">
				<input type="text" name="location_city" value="<?php echo $row->Location_City; ?>" class="form-control" required />
				<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
			</div>
		</div>
		<!-- <div class="form-group">
			<label class="col-form-label">Quota : <span class="text-danger">*</span></label>
			<div class="input-group">
				<input type="text" name="quota" oninput="this.value=this.value.replace(/[^0-9]/g,'')" value="<?php echo $row->Quota; ?>" class="form-control" required/>
				<span class="input-group-addon"><i class="fa fa-users"></i></span>
			</div>
		</div> -->
		<button type="submit" data-dismiss="modal" aria-hidden="true" class="btn btn-sm btn-default">Cancel</button>
		<button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
	</fieldset>
</form>