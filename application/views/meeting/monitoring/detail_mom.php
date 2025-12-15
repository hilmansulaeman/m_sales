<div class="col-lg-12">
		
		<div class="box box-primary">			
			<div class="box-header with-border">
				<h3 class="box-title">Update MOM1</h3>			  
			</div>
			
			<div class="panel-body" style="font-family:sans-serif;">
				<!-- begin row -->
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group m-b-10">
							<label>Hasil MOM <span style="color:red">*</span>:</label>
							<?php echo form_error('hasil_mom'); ?>
                  			<textarea type="text" class="form-control" required id="hasil_mom" name="hasil_mom" oninput="this.value=this.value.replace(/[<>]/g,'')" onpaste="this.value=this.value.replace(/[<>]/g,'')" value="<?php echo set_value('hasil_mom'); ?>" disabled ><?php echo (isset($data_schedule))?$data_schedule->MOM:"";?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>