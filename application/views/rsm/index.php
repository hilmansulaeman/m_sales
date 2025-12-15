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

?>
<section>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-angle-double-right"></i> RSM DATA</a></li>
    <!--<li class="active">Top Navigation</li>-->
  </ol>
</section>
<div class="box box-primary">
	<div class="box-header">
	  <h3 class="box-title">DATA</h3>
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
			<td align="center">JUMLAH ASM</td>
			<td><?php echo $target_asmnya; ?></td>
			<td>
				<!--<a data-fancybox href="<?php echo base_url(); ?>rsm/jml_asm_aktual/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $jml_asm; ?></a>-->
				<a data-fancybox href="<?php echo base_url(); ?>rsm/jml_asm_aktual_new/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $jml_asm; ?></a>
			</td>
			<?php
				$selisih_asm = $jml_asm - $target_asmnya;
				if($selisih_asm > 0)
				{
					$selisih_asm = 0;
				}
				else
				{
					$selisih_asm = $selisih_asm;
				}
			?>
			<td><?php echo $selisih_asm; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_ASM" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_view/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_ASM" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_ASM" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">JUMLAH SPV</td>
			<td><?php echo $target_spvnya ?></td>
			<td>
				<!--<a data-fancybox href="<?php echo base_url(); ?>rsm/jml_spv_aktual/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $jml_spv; ?></a>-->
				<a data-fancybox href="<?php echo base_url(); ?>rsm/jml_spv_aktual_new/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $jml_spv; ?></a>
			</td>
			<?php
				$selisih_spv = $jml_spv -  $target_spvnya;
				if($selisih_spv > 0)
				{
					$selisih_spv = 0;
				}
				else
				{
					$selisih_spv = $selisih_spv;
				}
			?>
			<td><?php echo $selisih_spv; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_SPV" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_view/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_SPV" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_SPV" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">JUMLAH DSR</td>
			<td><?php echo $target_dsrnya; ?></td>
			<td><?php echo $jml_dsr; ?></td>
			<?php
				$selisih_dsr = $jml_dsr - $target_dsrnya;
				if($selisih_dsr > 0)
				{
					$selisih_dsr = 0;
				}
				else
				{
					$selisih_dsr = $selisih_dsr;
				}
			?>
			<td><?php echo $selisih_dsr; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_DSR" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_view/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_DSR" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_DSR" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">APLIKASI</td>
			<td><?php echo $target_aplikasinya ?></td>
			<td><?php echo $aplikasi_akt; ?></td>
			<td><?php echo $aplikasi_akt - $target_aplikasinya; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/APLIKASI" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_view/<?php echo $this->session->userdata('sl_code');?>/APLIKASI" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/APLIKASI" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">ACCOUNT</td>
			<td><?php echo $target_noanya; ?></td>
			<td><?php echo $total_noa; ?></td>
			<?php
				$selisih_noa = $total_noa - $target_noanya;
				if($selisih_noa > 0)
				{
					$selisih_noa = 0;
				}
				else
				{
					$selisih_noa = $selisih_noa;
				}
			?>
			<td><?php echo $selisih_noa; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/ACCOUNT" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen/<?php echo $this->session->userdata('sl_code'); ?>/ACCOUNT" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/ACCOUNT" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">RTS</td>
			<td>0</td>
			<?php
				if($aplikasi_rts > 0 AND $aplikasi_akt > 0)
				{
					$htgrts = ($aplikasi_rts / $aplikasi_akt) * 100;
				}
				else
				{
					$htgrts = 0;
				}
			
			?>
			<td>
				<!--<a data-fancybox href="<?php echo base_url(); ?>rsm/jml_rts_aktual/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $aplikasi_rts; ?></a>-->
				<a data-fancybox href="<?php echo base_url(); ?>rsm/jml_rts_aktual_new/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $aplikasi_rts; ?></a>
			</td>
			<td><?php echo round($htgrts); ?> %</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/RTS" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_view/<?php echo $this->session->userdata('sl_code');?>/RTS" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/RTS" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">UNDER PERFORM</td>
			<td>0</td>
			<td>
				<!--<a data-fancybox href="<?php echo base_url(); ?>rsm/jml_under_aktual/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $under_perf; ?></a>-->
				<a data-fancybox href="<?php echo base_url(); ?>rsm/jml_under_aktual_new/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $under_perf; ?></a>
			</td>
			<td><?php echo $under_perf - 0; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/UNDER_PERFORM" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_view/<?php echo $this->session->userdata('sl_code');?>/UNDER_PERFORM" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/UNDER_PERFORM" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">APPROVAL RATE</td>
			<td><?php echo round($dtNasApp); ?> %</td>
			<td><?php echo round($dtAppAkt); ?> %</td>
			<td><?php echo $slsAppR; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/APPROVAL_RATE" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_view/<?php echo $this->session->userdata('sl_code');?>/APPROVAL_RATE" class="btn btn-primary btn-sm"/><i class="fa fa-eye"></i></a>
				<a data-fancybox href="<?php echo base_url(); ?>rsm/komitmen_others/<?php echo $this->session->userdata('sl_code'); ?>/APPROVAL_RATE" class="btn btn-primary btn-sm"><i class="fa fa-users"></i></a>
			</td>
		</tr>
	  </table>
	</div>
<!-- /.box-body -->
</div>
<!-- /.box -->