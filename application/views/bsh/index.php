<?php

if($query->num_rows > 0)
{
	$rows = $query->row();
	$totalRsm = $rows->total_rsm;
	$totalAsm = $rows->total_asm;
	$totalSpv = $rows->total_spv;
	$totalDsr = $rows->total_dsr;
	$apps = $rows->inc_cc + $rows->inc_edc + $rows->inc_sc;
	$apps_rts = $rows->rts_cc + $rows->rts_edc + $rows->rts_sc;
	$target_aps = $rows->target_apps;
	$up = $rows->up;
}
else
{
	$totalRsm = 0;
	$totalAsm = 0;
	$totalSpv = 0;
	$totalDsr = 0;
	$apps = 0;
	$apps_rts = 0;
	$target_aps = 0;
	$up = 0;
}

?>
<section>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-angle-double-right"></i> BSH DATA</a></li>
    <!--<li class="active">Top Navigation</li>-->
  </ol>
</section>
<div class="box box-primary">
	<div class="box-header">
	  <h3 class="box-title">DATA SUMMARY BSH</h3>
	</div>
	<!-- /.box-header -->
	<div class="box-body no-padding">
	  <table class="table table-striped">
		<tr>
		  <th>&nbsp;</th>
		  <th>TARGET</th>
		  <th>AKTUAL</th>
		  <th>SELISIH</th>
		  <th>ACTIONS</th>
		</tr>
		<tr>
			<td align="center">JUMLAH RSM</td>
			<td>3</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/jml_rsm_aktual_new/<?php echo $this->session->userdata('sl_code'); ?>">
					<?php echo $totalRsm; ?>
				</a>
			</td>
			<td><?php echo $totalRsm - 3; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_RSM" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">JUMLAH ASM</td>
			<td>5</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/jml_asm_aktual_new/<?php echo $this->session->userdata('sl_code'); ?>">
					<?php echo $totalAsm; ?>
				</a>
			</td>
			<td><?php echo $totalAsm - 5; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_ASM" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">JUMLAH SPV</td>
			<td>15</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/jml_spv_aktual_new/<?php echo $this->session->userdata('sl_code'); ?>">
					<?php echo $totalSpv ?>
				</a>
			</td>
			<td><?php echo $totalSpv - 15; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_SPV" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">JUMLAH DSR</td>
			<td>0</td>
			<td><?php echo $totalDsr; ?></td>
			<td>0</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_DSR" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">APLIKASI</td>
			<td><?php echo $target_aps; ?></td>
			<td><?php echo $apps; ?></td>
			<td>0</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/APLIKASI" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">RTS</td>
			<td>0</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/jml_rts_aktual_new/<?php echo $this->session->userdata('sl_code'); ?>">
					<?php echo $apps_rts; ?>
				</a>
			</td>
			<!-- <td><?php echo round(($apps_rts / $apps) * 100); ?> %</td> -->
			<td><?php echo ($apps != 0) ? round(($apps_rts / $apps) * 100) . ' %' : '0 %'; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/RTS" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">UNDER PERFORM</td>
			<td>0</td>
			<td><a data-fancybox href="<?php echo base_url(); ?>bsh/jml_under_aktual_new/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $under_perf; ?></a></td>
			<td>0</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/UNDER_PERFORM" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">APPROVAL RATE</td>
			<td>0 %</td>
			<td>0 %</td>
			<td>0</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>bsh/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/APPROVAL_RATE" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
	  </table>
	</div>
<!-- /.box-body -->
</div>
<!-- /.box -->