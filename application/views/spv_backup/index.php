<?php

$dsr = $sql_countdsr->row();
$selisih_dsr = 15 - $dsr->total_dsr;
if($selisih_dsr < 0)
{
	$selisih_dsr = 0;
}
else
{
	$selisih_dsr = $selisih_dsr;
}
if($dsr->total_dsr < 15)
{
	$st = "red";
}
else
{
	$st = "green";
}

$selisih_apps = $apps_aktual - $targets;
if($selisih_apps > 0)
{
	$selisih_apps = 0;
}
else
{
	$selisih_apps = $selisih_apps;
}

$selisih_persen = $aktual_persen - $target_persen;
if($selisih_persen > 0)
{
	$selisih_persen = 0;
}
else
{
	$selisih_persen = $selisih_persen;
}

if($sqlCek->num_rows() > 0)
{
	$dtCeks = $sqlCek->row();
	if($dtCeks->jmldsr > 0){$st1 = ""; $btn1 = "btn-success"; } else { $st1 = "style='display:none;'"; $btn1 = "btn-primary"; }
	if($dtCeks->aplikasi > 0){ $st2 = ""; $btn2 = "btn-success"; } else { $st2 = "style='display:none;'"; $btn2 = "btn-primary"; }
	if($dtCeks->rts > 0){ $st3 = ""; $btn3 = "btn-success"; } else { $st3 = "style='display:none;'"; $btn3 = "btn-primary"; }
	if($dtCeks->under_perform > 0){ $st4 = ""; $btn4 = "btn-success"; } else { $st4 = "style='display:none;'"; $btn4 = "btn-primary"; }
	if($dtCeks->app_rate > 0){ $st5 = ""; $btn5 = "btn-success"; } else { $st5 = "style='display:none;'"; $btn5="btn-primary"; }
	if($dtCeks->account > 0){ $st6 = ""; $btn6 = "btn-success"; } else { $st6 = "style='display:none;'"; $btn6="btn-primary"; }
}
else
{
	$st1 = "style='display:none;'";
	$st2 = "style='display:none;'";
	$st3 = "style='display:none;'";
	$st4 = "style='display:none;'";
	$st5 = "style='display:none;'";
	$st6 = "style='display:none;'";
}

?>
<section>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-angle-double-right"></i> SPV DATA</a></li>
    <!--<li class="active">Top Navigation</li>-->
  </ol>
</section>
<div class="box box-primary">
	<div class="box-header">
	  <h3 class="box-title">DATA SUMMARY</h3>
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
			<td align="center">JUMLAH DSR</td>
			<td>15</td>
			<td><a data-fancybox class="fancyboxhd fancybox.iframe" href="<?php echo base_url(); ?>spv/jml_dsr_aktual/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $dsr->total_dsr; ?></a></td>
			<td><?php echo $selisih_dsr; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>spv/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_DSR" class="btn <?php echo $btn1 ?> btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>spv/komitmen/<?php echo $this->session->userdata('sl_code'); ?>/JUMLAH_DSR" class="btn btn-success btn-sm" <?php echo $st1; ?>><i class="fa fa-eye"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">APLIKASI</td>
			<td><?php echo $targets; ?></td>
			<td><?php echo $apps_aktual; ?></td>
			<td><?php echo $selisih_apps; ?></td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>spv/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/APLIKASI" class="btn <?php echo $btn2 ?> btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>spv/komitmen/<?php echo $this->session->userdata('sl_code'); ?>/APLIKASI" class="btn btn-success btn-sm" <?php echo $st2; ?>><i class="fa fa-eye"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">ACCOUNT</td>
			<td>0</td>
			<td><?php echo $total_noa; ?></td>
			<td>0</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>spv/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/ACCOUNT" class="btn <?php echo $btn6 ?> btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>spv/komitmen/<?php echo $this->session->userdata('sl_code'); ?>/ACCOUNT" class="btn btn-success btn-sm" <?php echo$st6; ?>><i class="fa fa-eye"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">RTS</td>
			<td>0</td>
			<?php
				if($apps_aktual == 0)
				{
					$hits = 0;
				}
				else
				{
					$hits = round(($apl_rts / $apps_aktual) * 100);
				}
			?>
			<td><a data-fancybox href="<?php echo base_url(); ?>spv/jml_rts_aktual/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $apl_rts; ?></a></td>
			<td><?php echo $hits; ; ?> %</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>spv/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/RTS" class="btn <?php echo $btn3 ?> btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>spv/komitmen/<?php echo $this->session->userdata('sl_code'); ?>/RTS" class="btn btn-success btn-sm" <?php echo $st3; ?>><i class="fa fa-eye"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">UNDER PERFORM</td>
			<td>0</td>
			<td><a data-fancybox href="<?php echo base_url(); ?>spv/jml_under_perform/<?php echo $this->session->userdata('sl_code'); ?>"><?php echo $sales_underperf; ?></a></td>
			<td><?php echo round(($sales_underperf / $dsr->total_dsr) * 100); ?> %</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>spv/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/UNDER_PERFORM" class="btn <?php echo $btn4 ?> btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>spv/komitmen/<?php echo $this->session->userdata('sl_code'); ?>/UNDER_PERFORM" class="btn btn-success btn-sm" <?php echo $st4; ?>><i class="fa fa-eye"></i></a>
			</td>
		</tr>
		<tr>
			<td align="center">APPROVAL RATE</td>
			<td><?php echo round($target_persen); ?> %</td>
			<td><?php echo round($aktual_persen); ?> %</td>
			<td><?php echo round($selisih_persen);?> %</td>
			<td>
				<a data-fancybox href="<?php echo base_url(); ?>spv/add_keterangan/<?php echo $this->session->userdata('sl_code'); ?>/APPROVAL_RATE" class="btn <?php echo $btn5 ?> btn-sm"><i class="fa fa-plus"></i> Komitmen</a>
				<a data-fancybox href="<?php echo base_url(); ?>spv/komitmen/<?php echo $this->session->userdata('sl_code'); ?>/APPROVAL_RATE" class="btn btn-success btn-sm" <?php echo $st5; ?>><i class="fa fa-eye"></i></a>
			</td>
		</tr>
	  </table>
	</div>
<!-- /.box-body -->
</div>
<!-- /.box -->