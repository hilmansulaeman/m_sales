<?php
	$show1 = "none";
	$show2 = "none";
	$show3 = "none";
	$table = "none";
	$names = "none";
	$ktps = "none";
	if($result == "duplicate bca")
	{
		$show1 = "block";
	}
	elseif($result == "duplicate dika")
	{
		$show3 = "block";
	}
	elseif($result == "available")
	{
		$show2 = "block";
	}
	
	if($this->input->post('nama') <> "")
	{
		$names = "block";
	}
	
	if($this->input->post('ktp') <> "")
	{
		$ktps = "block";
	}

?>
<section class="content">
<div class="callout callout-success">
	<h4><i class="fa fa-info"></i> Info</h4>
	<p> * Opsi Cek Duplicate dengan 2 cara</p>
	<p> - Cek Duplicate dengan Nama dan Dob</p>
	<p> - Cek Duplicate dengan Nomor KTP</p>
</div>
<form method="post"> 
  <div class="box box-primary">
		<div class="box-header with-border">
			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>		
			<h3 class="box-title">Form Cek Duplicate</h3>		  
		</div>
		<br />
					<!-- /.panel-heading -->					
		<div class="panel-body" >
				 <?php //echo form_open('');?>
			  	
				<?php // echo form_open_multipart('extimasi/do_upload');?>
				<div class="form-group">
					<label>Check By:</label>
					<select class="form-control" name="cek" id="cek" onchange="choose(this.value)">
						<option value="0">--Pilih--</option>
						<option value="nama">Nama</option>
						<option value="ktp">KTP</option>
					</select>
				</div>
				<div id="nama" style="display:none;">
					<div class="form-group">
						<label>Nama :</label>
						<input type="text" name="nama" class="form-control"/>
					</div>
					<div class="form-group">
						<label>DOB :</label>
						<input type="text" name="dob" class="form-control tanggal" placeholder="YYYY-MM-DD" value=""/>
					</div>
				</div>
				<div id="ktp" style="display:none;">
					<div class="form-group">
						<label>No KTP :</label>
						<input type="text" name="ktp" class="form-control" />
					</div>
				</div>
				<div id="btn" style="display:none;" class="form-group">
					<input type="submit" name="cari" value="Go" class="btn btn-primary">			
				</div>
				<table class="table table-hover" id="table">
					<tr style="display: <?php echo $names; ?>;">
						<td><b>Name</b></td>
						<td><b>:</b></td>
						<td><?php echo $this->input->post('nama'); ?></td>
					</tr>
					<tr style="display: <?php echo $names; ?>;">
						<td><b>DOB</b></td>
						<td><b>:</b></td>
						<td><?php echo $this->input->post('dob'); ?></td>
					</tr>
					<tr style="display: <?php echo $ktps; ?>;">
						<td><b>KTP</b></td>
						<td><b>:</b></td>
						<td><?php echo $this->input->post('ktp'); ?></td>
					</tr>
					<tr>
						<td colspan="3">
							<div style="display: <?php echo $show2; ?>;" class="callout callout-success">
								<h4><i class="fa fa-info"></i> Info</h4>
								<p><b>AVAILABLE</b></p>
							</div>
							<div style="display: <?php echo $show1; ?>;" class="callout callout-danger">
								<h4><i class="fa fa-info"></i> Info</h4>
								<p><b>DUPLICATE BCA</b></p>
							</div>
							<div style="display: <?php echo $show3; ?>;" class="callout callout-danger">
								<h4><i class="fa fa-info"></i> Info</h4>
								<p><b>DUPLICATE DIKA</b></p>
							</div>
						</td>
					</tr>
				</table>
         </div>     
	  </div>
</form>
<script>
function choose(val)
{
	var data = val;
	if(data == "nama")
	{
		document.getElementById("nama").style.display = "block";
		document.getElementById("ktp").style.display = "none";
		document.getElementById("btn").style.display = "block";
		document.getElementById("table").style.display = "none";
	}else if(data == "ktp")
	{
		document.getElementById("nama").style.display = "none";
		document.getElementById("ktp").style.display = "block";
		document.getElementById("btn").style.display = "block";
		document.getElementById("table").style.display = "none";
	}
}
</script>

