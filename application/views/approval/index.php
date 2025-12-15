
<section>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-angle-double-right"></i> <?php echo $this->session->userdata('position');?> DATA</a></li>
    <!--<li class="active">Top Navigation</li>-->
  </ol>
</section>
<div class="nav-tabs-custom">
<ul class="nav nav-tabs">  
  <li class="active" ><a href="<?php echo base_url(); ?>approval/index" > <i class="fa fa-angle-double-left"></i> &nbsp; TO DO LIST RECRUITMENT SALES</a></li>
  <li><a href="<?php echo base_url(); ?>approval/listed/<?php echo $this->session->userdata('sl_code'); ?>"> YOUR LIST APPROVED AND REJECTED SALES <i class="fa fa-angle-double-right"></i></a></li> 
</ul>
</div>
<div class="box box-primary">
	<br>
	<div class="box-header">
	  <h2 class="box-title"><i class="fa fa-book"></i> TO DO LIST APPROVAL RECRUITMENT SALES</h2>  &nbsp;	
	</div>
	<!-- /.box-header -->
	<br>
	
	<div class="box-body no-padding">
	
	  <table id="example" class="table table-striped table-bordered table-hover" width="100%">
		<tr>
		  <th>&nbsp;</th>
		  <th>NAMA</th>
		  <th>TANGGAL LAHIR</th>
		  <th>JENIS KELAMIN</th>
		  <th>STATUS</th>
		  <th>PENDIDIKAN</th>
		  <th>POSISI LAMAR</th>		  
		  <th>AKSI</th>
		</tr>
		<?php
			$no = 0;
			$status="";
			if($sqlRecruitment->num_rows() > 0){
			foreach($sqlRecruitment->result() as $data)
			{			 
		?>
			<tr>
			<td><?php echo ++$no; ?></td>
			<td><?php echo $data->name; ?></td>
			<td><?php echo $data->dob; ?></td>
			<td><?php echo $data->gender; ?></td>
			<td><?php echo $data->status; ?></td>
			<td><?php echo $data->pendidikan; ?></td>
			<td><?php echo $data->posisi_lamar; ?>, <?php echo $data->posisi_lamar2; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>approval/add_keterangan/approve/<?php echo $data->id; ?>/<?php echo $this->session->userdata('sl_code'); ?>/<?php echo $this->session->userdata('position');?>" class="btn btn-success btn-sm"><i class="fa fa-thumbs-up"></i> Approve</a>				
				<a data-fancybox href="<?php echo base_url(); ?>approval/add_keterangan/reject/<?php echo $data->id; ?>/<?php echo $this->session->userdata('sl_code'); ?>/<?php echo $this->session->userdata('position');?>" class="btn btn-danger btn-sm"><i class="fa fa-thumbs-down"></i> Reject</a>				
			</td>
		</tr>
			<?php 	}
			}else{
		?>
		<tr>
			<td colspan="8" align="center"><h3>No data available in table</h3></td>			
		</tr>
		<?php 	
			}
		?>	
	  </table>
	</div>
<!-- /.box-body -->
</div>
<!-- /.box -->
