<?php
if($status_schedule=='Open'){
	$dis = '';
}else{
	$dis = ' disabled ';
}
?>
<!-- MAIN CONTENT -->
<div id="ribbon">
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-link "></i> &nbsp; Meeting</li>
		<li>History Meeting</li>
	</ol>	 
</div>
<?php if ($this->session->flashdata('message')) { ?>
	<div class="alert alert-warning fade in">
	<button class="close" data-dismiss="alert" id="notif">
		×
	</button>
	<i class="fa-fw fa fa-check"></i>
	<?php echo $this->session->flashdata('message');?>
	</div>
<?php }?>
<!-- begin row -->
<div class="row">
	
	<form action="" method="post" enctype="multipart/form-data">
	<div class="col-lg-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">History Absensi</h3>			  
			</div>
			
			<div class="box-header">
				<!-- <header class="panel-heading col-lg-3" style="background-color:#f4f4f4;text-align:center;">
					<b><input class='<?= ($query->num_rows()>0)?$query->row("Schedule_ID"):"";?>' type='checkbox' onClick="checkAll(this)" name='scheduleID' value='<?= ($query->num_rows()>0)?$query->row("Schedule_ID"):"";?>'  <?php echo $dis;?> > Hadir Semua</b>
				</header> -->
			</div>
			<div class="panel-body">
				<!-- begin row -->
				<div class="row">
					<!-- <div class="col-lg-12" style="height:433px;overflow:auto;font-family:sans-serif;"> -->
					<div class="col-lg-12">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>No.</th>
									<th>NIK</th>
									<th>Posisi</th>
									<th>Nama</th>
									<th>Kehadiran</th>
								</tr>
							</thead>
							<tbody>
							<?php
								if($query->num_rows()>0){
								$no=0;
								foreach ($query->result() as $row) {
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=$row->NIK?></td>
									<td><?=$row->position?></td>
									<td><?=$row->Name?></td>
									<td>
									<input class="children<?=$row->Schedule_ID?>" type="checkbox" name="participantID[]" value="<?=$row->Participant_ID?>" <?php echo $dis;?> <?php echo ($status_schedule=='Closed' && $row->Absent_Status==1)?' checked ':'';?> >
									</td>
								</tr>
							<?php
								}
								}else{
							?>
								<tr >
									<td align="center" colspan="4">No data available</td>       
								</tr>							
							<?php
								}
							?>
							</tbody>
						</table>
						<a href="<?php echo base_url();?>meeting/history_meeting" style="margin-bottom: 25px;" class="btn btn-danger">Back</a>			  
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="box box-primary">			
			<div class="box-header with-border">
				<h3 class="box-title">Hasil MOM</h3>			  
			</div>
			
			<div class="panel-body" style="font-family:sans-serif;">
				<!-- begin row -->
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group m-b-10">
							<label>Hasil MOM <span style="color:red">*</span>:</label>
							<?php echo form_error('hasil_mom'); ?>
                  			<textarea type="text" rows="8" class="form-control" required id="hasil_mom" name="hasil_mom" oninput="this.value=this.value.replace(/[<>]/g,'')" onpaste="this.value=this.value.replace(/[<>]/g,'')" value="<?php echo set_value('hasil_mom'); ?>" <?php echo $dis;?>><?php echo (isset($data_update_meeting))?$data_update_meeting->row("MOM"):"";?></textarea>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- <div class="col-lg-12">
		<div class="box-footer with-border">
			<a href="<?php echo base_url();?>meeting/schedule" class="btn btn-danger">Back</a>			  
		<?php
			if($this->uri->segment(3)!='' && $status_schedule == 'Open'){
		?>
			<button type="submit" class="btn btn-primary">Save</button>			  
		<?php
			}
		?>	
		</div>
	</div>	 -->
	
	</form>
</div>

<script>
	function checkAll(myCheckbox) {

        var checkboxes = document.querySelectorAll(`input[class = 'children${myCheckbox.className}']`);

        if (myCheckbox.checked == true) {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
        } else {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });            
        }
    }

    $("#file").change(function(){
		const file = this.files[0];
        // console.log(file);
        if (file){
          let reader = new FileReader();
          
          reader.onload = function(readerEvent){
            // console.log(event.target.result);
            $('#preview_foto').attr('src', readerEvent.target.result);
          }
      	  
      	  reader.readAsDataURL(file);
        }
		
	});

	function AddNew()
	{
		$('#modalAdd').modal('show');
	}
	
	function ShowDetail(id)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo base_url(); ?>meeting/location/getDetail/'+ id,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}
	
	function ShowEdit(id)
	{
		$('#modalEdit').modal('show');
		$.ajax({
            url:'<?php echo base_url(); ?>meeting/location/getEdit/'+ id,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop2").html('');  
                $("#pop2").append(data);  
            }  
        });
	}
</script>

<!-- Modal Add -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Form Input</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url(); ?>meeting/location/save">
					<fieldset>
						<div class="form-group">
							<label class="col-form-label">Nama Lokasi :</label>
							<div class="input-group">
								<input type="text" name="location_name" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-building"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Alamat Lokasi :</label>
							<div class="input-group">
								<input type="text" name="location_address" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Kota :</label>
							<div class="input-group">
								<input type="text" name="location_city" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Kuota :</label>
							<div class="input-group">
								<input type="text" name="quota" oninput="validateNumber(this)" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-users"></i></span>
							</div>
						</div>
						<button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
					</fieldset>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-eye"></i> Detail Data</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<div id="pop">
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil-alt"></i> Form Ubah Data</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<div id="pop2">
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->