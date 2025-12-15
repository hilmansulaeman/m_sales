<?php
	$date_from = date('d/M/Y',strtotime($this->session->userdata('date_from')));
	$date_to   = date('d/M/Y',strtotime($this->session->userdata('date_to')));
    
	$total_basic = ($query == null) ? 0 : $query->total_basic;
	$total_platinum = ($query == null) ? 0 : $query->total_platinum;
	$total_produk = ($query == null) ? 0 : $query->jumlah;

	$premi_basic = ($query->premi_basic == null) ? 0 : $query->premi_basic;
	$premi_platinum = ($query->premi_platinum == null) ? 0 : $query->premi_platinum;
	$total_premi = ($query->total_premi == null) ? 0 : $query->total_premi;

?>

<div class="alert alert-info">
	<div class="row">
		<div class="col-sm-12">
			<span class="pull-right">
				<form action="" method="post">
					<table style="width:100%;">
						<tr>
							<th colspan="5"><b>Periode : </b></th>	
						</tr>
						<tr>	
							<td>
								<label class="input">
									<input type="date" name="date_from" value="<?php echo $this->session->userdata('date_from'); ?>" class="form-control" required/>
								<?php echo form_error('date_from'); ?>
								</label>	
							</td>											
							<td><h5 class="txt-color-blueDark">&nbsp; S/D &nbsp; </h5></td>											
							<td>
								<label class="input">
									<input type="date" name="date_to" value="<?php echo $this->session->userdata('date_to'); ?>" class="form-control" required/>
								<?php echo form_error('date_to'); ?>
								</label>
							</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td>
								<button type="submit" id="btn-filter" name="date_filter" class="btn btn-success" style="padding:5px;"><span class="fa fa-filter"></span> Go</button>
							</td>																					
						</tr>						 
					</table>
				</form>
			</span>
		</div>
	</div>
</div>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h4>Setoran Aplikasi Pasar Polis</h4>
			</div>
			<div class="col-md-6">
			
			</div>
		</div>
		<!-- <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
		</button> -->
		<!-- </div> -->
		<div class="box-body">
			<!-- Add the bg color to the header using any of the bg-* classes --> 
			<ul class="nav nav-stacked"> 
				
				<li style="height:70px";>Basic <span class="pull-right badge bg-blue"> <?php echo $total_basic ?></span><span class="pull-right badge">Total Produk &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><br><span class="pull-right badge bg-blue"> <?php echo  $premi_basic ?></span><span class="pull-right badge">Total Premi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></li>
				
				<li style="height:70px";>Platinum <span class="pull-right badge bg-blue"> <?php echo $total_platinum ?></span><span class="pull-right badge">Total Produk &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><br><span class="pull-right badge bg-blue"> <?php echo $premi_platinum ?></span><span class="pull-right badge">Total Premi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></li>

				<li style="height:70px";>Total <span class="pull-right badge bg-blue"> <?php echo $total_produk ?></span><span class="pull-right badge">Total Produk &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><br><span class="pull-right badge bg-blue"> <?php echo $total_premi ?></span><span class="pull-right badge">Total Premi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></li>


			</ul>
		</div>
	</div>
</div>
