<?php
if($appAkt > 0)
{
	$dtAppAkt = ($appAkt / ($appAkt + $dtRejAkt)) * 100;
}
else
{
	$dtAppAkt = 0;
}

if($dtAppNas > 0)
{
	$dtNasApp = ($dtAppNas / ($dtAppNas + $dtRejNas)) * 100;
}
else
{
	$dtNasApp = 0;
}

$slsAppR = $dtAppAkt - $dtNasApp;
if($dtAppAkt > 0)
{
	$slsAppR = 0;
}
else
{
	$slsAppR = $slsAppR;
}

$slsApps = $rwApsAkt - $dtTarget;
if($slsApps > 0)
{
	$slsApps = 0;
}
else
{
	$slsApps = $slsApps;
}

if($sqlKomitmen->num_rows() > 0)
{
	$dtCeks = $sqlKomitmen->row();
	if($dtCeks->jmldsr > 0){ $st1 = ""; } else { $st1 = "style='display:none;'"; }
	if($dtCeks->aplikasi > 0){ $st2 = ""; } else { $st2 = "style='display:none;'"; }
	if($dtCeks->rts > 0){ $st3 = ""; } else { $st3 = "style='display:none;'"; }
	if($dtCeks->under_perform > 0){ $st4 = ""; } else { $st4 = "style='display:none;'"; }
	if($dtCeks->app_rate > 0){ $st5 = ""; } else { $st5 = "style='display:none;'"; }
}
else
{
	$st1 = "style='display:none;'";
	$st2 = "style='display:none;'";
	$st3 = "style='display:none;'";
	$st4 = "style='display:none;'";
	$st5 = "style='display:none;'";
}



?>

<section>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-angle-double-right"></i> ASM DATA</a></li>
    <!--<li class="active">Top Navigation</li>-->
  </ol>
</section>
<div class="box box-primary">
	<div class="box-header">
	  <h3 class="box-title">SUMMARY DATA</h3>
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
			<td align="center">JUMLAH SPV</td>
			<td>5</td>
			<td><a data-fancybox href="<?php echo base_url(); ?>asm_new/jml_spv_aktual/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $aktSpv; ?></a></td>
			<td><?php echo $aktSpv - 5;?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>asm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_SPV" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_view/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_SPV" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_SPV" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">JUMLAH DSR</td>
			<td>75</td>
			<?php
				$jmlDsrnya = $aktDsr - 75;
				if($jmlDsrnya > 0)
				{
					$jmlDsrnya = 0;
				}
				else
				{
					$jmlDsrnya = $jmlDsrnya;
				}
			?>
			<td><?php echo $aktDsr; ?></td>
			<td><?php echo $jmlDsrnya; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>asm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_DSR" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_view/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_DSR" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_DSR" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">APLIKASI</td>
			<td><?php echo $dtTarget; ?></td>
			<td><?php echo $rwApsAkt; ?></td>
			<td><?php echo $slsApps; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>asm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/APLIKASI" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_view/<?php echo $this->session->userdata('sl_code');?>/APLIKASI" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/APLIKASI" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<?php
				if($rwApsAkt > 0 AND $dtRts > 0)
				{
					$hitungRts = ($dtRts / $rwApsAkt) * 100;
				}
				else
				{
					$hitungRts = 0;
				}
			?>
			<td align="center">RTS</td>
			<td>0</td>
			<td><a data-fancybox href="<?php echo base_url(); ?>asm/jml_rts_aktual/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $dtRts; ?></a></td>
			<td><?php echo round($hitungRts); ?> %</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>asm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/RTS" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_view/<?php echo $this->session->userdata('sl_code');?>/RTS" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/RTS" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">UNDER PERFORM</td>
			<td>0</td>
			<?php
				if($aktDsr > 0 AND $rwUnder > 0)
				{
					$hitungUp = ($rwUnder / $aktDsr) * 100;
				}
				else
				{
					$hitungUp = 0;
				}
			?>
			<td><a data-fancybox href="<?php echo base_url(); ?>asm/jml_under_aktual/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $rwUnder; ?></a></td>
			<td><?php echo round($hitungUp); ?> %</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>asm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/UNDER_PERFORM" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_view/<?php echo $this->session->userdata('sl_code');?>/UNDER_PERFORM" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/UNDER_PERFORM" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">APPROVAL RATE</td>
			<td><?php echo round($dtNasApp); ?> %</td>
			<td><?php echo round($dtAppAkt); ?> %</td>
			<td><?php echo $slsAppR; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>asm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/APPROVAL_RATE" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_view/<?php echo $this->session->userdata('sl_code');?>/APPROVAL_RATE" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>asm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/APPROVAL_RATE" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
	  </table>
	</div>
<!-- /.box-body -->
</div>
<!-- /.box -->