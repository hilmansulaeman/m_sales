
<section>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-angle-double-right"></i> <?php echo $this->session->userdata('position');?> DATA</a></li>
    <!--<li class="active">Top Navigation</li>-->
  </ol>
</section>
<div class="nav-tabs-custom">
<ul class="nav nav-tabs">  
  <li  ><a href="<?php echo base_url(); ?>approval/index" > <i class="fa fa-angle-double-left"></i> &nbsp; TO DO LIST RECRUITMENT SALES</a></li>
  <li class="active"><a href="<?php echo base_url(); ?>approval/listed/<?php echo $this->session->userdata('sl_code'); ?>/<?php echo $this->session->userdata('position');?>"> YOUR LIST APPROVED AND REJECTED SALES <i class="fa fa-angle-double-right"></i></a></li> 
</ul>
</div>
<div class="box box-primary">
	<br>
	<div class="box-header">
	  <h2 class="box-title"><i class="fa fa-book"></i> LIST APPROVED AND REJECTED RECRUITMENT SALES</h2> &nbsp; 	 
	</div>
	<!-- /.box-header -->
	<br>
	<div class="box-body no-padding">
	  <table class="table table-striped">
		<tr>
		  <th>&nbsp;</th>
		  <th>NAMA</th>
		  <th>TANGGAL LAHIR</th>
		  <th>JENIS KELAMIN</th>
		  <th>STATUS</th>
		  <th>PENDIDIKAN</th>
		  <th>POSISI LAMAR</th>		  
		  <th>STATUS APPROVE 1</th>	
		  <th>STATUS APPROVE 2</th>	
		  <th>AKSI</th>
		</tr>
		<?php
			$no = 0;
			
			global $status, $hideap, $hiderj, $style;
			global $status2, $hideap2, $hiderj2, $style2;
			if($sqlRecruitment->num_rows() > 0){  
				foreach($sqlRecruitment->result() as $data)
				{			 
				if($data->approve1 == "1"){
					$status="Approved";
					$hideap="display:none;";
					$hiderj="display:none;";
					$style="success";
				}else{
					$status="Rejected";
					$hideap="";
					$hiderj="display:none;";
					$style="danger";
				}
				if($data->approve2 == "1"){
					$status2="Approved";
					$hideap2="display:none;";
					$hiderj2="display:none;";
					$style2="success";
				}else{
					$status2="Rejected";
					$hideap2="";
					$hiderj2="display:none;";
					$style2="danger";
				}
			
		?>
		<tr>
			<td><?php echo ++$no; ?></td>
			<td><?php echo $data->name; ?></td>
			<td><?php echo $data->dob; ?></td>
			<td><?php echo $data->gender; ?></td>
			<td><?php echo $data->status; ?></td>
			<td><?php echo $data->pendidikan; ?></td>
			<td><?php echo $data->posisi_lamar; ?>, <?php echo $data->posisi_lamar2; ?></td>
			<td><?php if($data->approve1 != "0"){ ?><b><?php echo $status; ?></b>, <?php echo $data->approve1_date; ?><br><span title="<?php echo $status;?>" class='label label-<?php echo $style;?>'>Note : <?php echo $data->approve1_note;?></span><br><span class='label label-primary'>SPV : <?php echo $data->approve1_name; }?></span></td>
			<td><?php if($data->approve2 != "0"){ ?><b><?php echo $status2; ?></b>, <?php echo $data->approve2_date; ?><br><span title="<?php echo $status2;?>" class='label label-<?php echo $style2;?>'>Note : <?php echo $data->approve2_note;?></span><br><span class='label label-primary'>ASM : <?php echo $data->approve2_name; }?></span></td>
			<td>
				<a style="<?php echo $hideap;?>" data-fancybox href="<?php echo base_url(); ?>approval/add_keterangan/approve/<?php echo $data->id; ?>/<?php echo $this->session->userdata('sl_code'); ?>/<?php echo $this->session->userdata('position');?>" class="btn btn-success btn-sm"><i class="fa fa-thumbs-up"></i> Approve</a>				
				<a style="<?php echo $hiderj;?>" data-fancybox href="<?php echo base_url(); ?>approval/add_keterangan/reject/<?php echo $data->id; ?>/<?php echo $this->session->userdata('sl_code'); ?>/<?php echo $this->session->userdata('position');?>" class="btn btn-danger btn-sm"><i class="fa fa-thumbs-down"></i> Reject</a>				
				<a style="<?php echo $hideap2;?>" data-fancybox href="<?php echo base_url(); ?>approval/add_keterangan/approve/<?php echo $data->id; ?>/<?php echo $this->session->userdata('sl_code'); ?>/<?php echo $this->session->userdata('position');?>" class="btn btn-success btn-sm"><i class="fa fa-thumbs-up"></i> Approve</a>				
				<a style="<?php echo $hiderj2;?>" data-fancybox href="<?php echo base_url(); ?>approval/add_keterangan/reject/<?php echo $data->id; ?>/<?php echo $this->session->userdata('sl_code'); ?>/<?php echo $this->session->userdata('position');?>" class="btn btn-danger btn-sm"><i class="fa fa-thumbs-down"></i> Reject</a>				
			</td>
		</tr>
		<?php 	}
			}else{
		?>
		<tr>
			<td colspan="9" align="center"><h3>No data available in table</h3></td>			
		</tr>
		<?php 	
			}
		?>	
	  </table>
	</div>
<!-- /.box-body -->
</div>
<!-- /.box -->

