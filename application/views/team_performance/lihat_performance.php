<?php
$rowSls = $sql_sales->row();
$posisi = $this->session->userdata('position');
$param = "";
if($posisi == "SPV")
{
	$param = "pilih_dsr";
}
elseif($posisi == "ASM")
{
	$param  = "pilih_spv";
}elseif($posisi == "RSM")
{
	$param = "pilih_asm";
}elseif($posisi == "BSH")
{
	$param = "pilih_rsm";
}
?>
<div class="box box-primary">
	<div class="box-header with-border">
		<h4>Filter</h4>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	    </button>
        </div>
	</div>
	<div class="box-body">
		<table class="table table-hover">
			<tr>
				<td>Name</td>
				<td>:</td>
				<td><?php echo $rowSls->Name; ?></td>
			</tr>
			<tr>
				<td>Position</td>
				<td>:</td>
				<td><?php echo $rowSls->Position; ?></td>
			</tr>
			<tr>
				<td colspan="3" class="text-right">
					<!--<a data-fancybox href="<?php echo base_url() ?>team_performance/<?php echo $param; ?>/<?php echo $this->session->userdata('sl_code'); ?>" class="btn btn-primary btn-sm">Pilih Sales</a>-->
					<a href="javascript:void(0)" onclick="viewSales('<?php echo $param ?>', '<?php echo $this->session->userdata('sl_code'); ?>')" class="btn btn-warning btn-sm">Pilih Sales</a>
				</td>
			</tr>
		</table>
	</div>
	<div class="box-footer clearfix"></div>
</div>

<div class="box box-primary" id="perform">
	<div class="box-header with-border">
		<h4>Performance</h4>
		Periode : <?php echo date('M / Y'); ?>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	    </button>
        </div>
	</div>
	<?php
	
	$row_cc_n = $setoran_cc_n->row();
	$row_edc_n = $setoran_edc_n->row();
	$row_sc_n = $setoran_sc_n->row();
	$row_pl_n = $setoran_pl_n->row();
	$row_corp_n = $setoran_corp_n->row();
	
	$app_cc_n = $approve_cc_n->row();
	$app_edc_n = $approve_edc_n->row();
	$app_sc_n = $approve_sc_n->row();
	$app_pl_n = $approve_pl_n->row();
	$app_corp_n = $approve_corp_n->row();
	
	?>
	<div class="box-body">
		<table class="table table-hover" style="font-size: 10px !important;">
			<tr>
				<td>&nbsp;</td>
				<td>CC</td>
				<td>EDC</td>
				<td>SC</td>
				<td>PL</td>
				<td>CORP</td>
			</tr>
			<tr>
				<td>SETORAN APLIKASI</td>
				<td><?php echo $row_cc_n->setoran_cc; ?></td>
				<td><?php echo $row_edc_n->setoran_edc; ?></td>
				<td><?php echo $row_sc_n->setoran_sc; ?></td>
				<td><?php echo $row_pl_n->setoran_pl; ?></td>
				<td><?php echo $row_corp_n->setoran_corp; ?></td>
			</tr>
			<tr>
				<td>INCOMING</td>
				<td><?php echo $row_cc_n->incoming_cc; ?></td>
				<td><?php echo $row_edc_n->incoming_edc; ?></td>
				<td><?php echo $row_sc_n->incoming_sc; ?></td>
				<td><?php echo $row_pl_n->incoming_pl; ?></td>
				<td><?php echo $row_corp_n->incoming_corp; ?></td>
			</tr>
			<tr>
				<td>APPROVED</td>
				<td><?php echo $app_cc_n->app_cc; ?></td>
				<td><?php echo $app_edc_n->app_edc; ?></td>
				<td><?php echo $app_sc_n->app_sc; ?></td>
				<td><?php echo $app_pl_n->app_pl; ?></td>
				<td><?php echo $app_corp_n->app_corp; ?></td>
			</tr>
			<tr>
				<td>CANCEL</td>
				<td><?php echo $app_cc_n->cancel_cc; ?></td>
				<td><?php echo $app_edc_n->cancel_edc; ?></td>
				<td><?php echo $app_sc_n->cancel_sc; ?></td>
				<td><?php echo $app_pl_n->cancel_pl; ?></td>
				<td><?php echo $app_corp_n->cancel_corp ?></td>
			</tr>
			<tr>
				<td>DECLINE / REJECT</td>
				<td><?php echo $app_cc_n->reject_cc; ?></td>
				<td><?php echo $app_edc_n->reject_edc; ?></td>
				<td><?php echo $app_sc_n->reject_sc; ?></td>
				<td><?php echo $app_pl_n->reject_pl; ?></td>
				<td><?php echo $app_corp_n->reject_corp; ?></td>
			</tr>
			<?php
			//formula apprate
			
			if($app_cc_n->app_cc > 0)
			{
				$rate_cc_n = $app_cc_n->app_cc / ($app_cc_n->app_cc + $app_cc_n->reject_cc) * 100;
				if($rate_cc_n > 100)
				{
					$rate_cc_n = 100;
				}
				else
				{
					$rate_cc_n = $rate_cc_n;
				}
			}
			else
			{
				$rate_cc_n = 0;
			}
			
			if($app_edc_n->app_edc > 0)
			{
				$rate_edc_n = $app_edc_n->app_edc / ($app_edc_n->app_edc + $app_edc_n->reject_edc) * 100;
				if($rate_edc_n > 100)
				{
					$rate_edc_n = 100;
				}
				else
				{
					$rate_edc_n = $rate_edc_n;
				}
			}
			else
			{
				$rate_edc_n = 0;
			}
			
			if($app_sc_n->app_sc > 0)
			{
				$rate_sc_n = $app_sc_n->app_sc / ($app_sc_n->app_sc + $app_sc_n->reject_sc) * 100;
				if($rate_sc_n > 100)
				{
					$rate_sc_n = 100;
				}
				else
				{
					$rate_sc_n = $rate_sc_n;
				}
			}
			else
			{
				$rate_sc_n = 0;
			}
			
			if($app_pl_n->app_pl > 0)
			{
				$rate_pl_n = $app_pl_n->app_pl / ($app_pl_n->app_pl + $app_pl_n->reject_pl) * 100;
				if($rate_pl_n > 100)
				{
					$rate_pl_n = 100;
				}
				else
				{
					$rate_pl_n = $rate_pl_n;
				}
			}
			else
			{
				$rate_pl_n = 0;
			}
			
			if($app_corp_n->app_corp > 0)
			{
				$rate_corp_n = $app_corp_n->app_corp / ($app_corp_n->app_corp + $app_corp_n->reject_corp) * 100;
				if($rate_corp_n > 100)
				{
					$rate_corp_n = 100;
				}
				else
				{
					$rate_corp_n = $rate_corp_n;
				}
			}
			else
			{
				$rate_corp_n = 0;
			}
			
			//end formula apprate
			?>
			<tr>
				<td>APP RATE</td>
				<td><?php echo round($rate_cc_n,1); ?> %</td>
				<td><?php echo round($rate_edc_n,1); ?> %</td>
				<td><?php echo round($rate_sc_n,1); ?> %</td>
				<td><?php echo round($rate_pl_n,1); ?> %</td>
				<td><?php echo round($rate_corp_n,1); ?> %</td>
			</tr>
			<?php
			//formula bookrate
			
			if($row_cc_n->incoming_cc > 0)
			{
				if($app_cc_n->app_cc == 0)
				{
					$app_cc_n->app_cc = 1;
				}
				$bookrate_cc_n = ($app_cc_n->app_cc / $row_cc_n->incoming_cc) * 100;
				if($bookrate_cc_n > 100)
				{
					$bookrate_cc_n = 100;
				}
				else
				{
					$bookrate_cc_n = $bookrate_cc_n;
				}
			}
			else
			{
				$bookrate_cc_n = 0;
			}
			
			if($row_edc_n->incoming_edc > 0)
			{
				if($app_edc_n->app_edc == 0)
				{
					$app_edc_n->app_edc = 1;
				}
				$bookrate_edc_n = ($app_edc_n->app_edc / $row_edc_n->incoming_edc) * 100;
				if($bookrate_edc_n > 100)
				{
					$bookrate_edc_n = 100;
				}
				else
				{
					$bookrate_edc_n = $bookrate_edc_n;
				}
			}
			else
			{
				$bookrate_edc_n = 0;
			}
			
			if($row_sc_n->incoming_sc > 0)
			{
				if($app_sc_n->app_sc == 0)
				{
					$app_sc_n->app_sc = 1;
				}
				$bookrate_sc_n = ($app_sc_n->app_sc / $row_sc_n->incoming_sc) * 100;
				if($bookrate_sc_n > 100)
				{
					$bookrate_sc_n = 100;
				}
				else
				{
					$bookrate_sc_n = $bookrate_sc_n;
				}
			}
			else
			{
				$bookrate_sc_n = 0;
			}
			
			if($row_pl_n->incoming_pl > 0)
			{
				if($app_pl_n->app_pl == 0)
				{
					$app_pl_n->app_pl = 1;
				}
				$bookrate_pl_n = ($app_pl_n->app_pl / $row_pl_n->incoming_pl) * 100;
				if($bookrate_pl_n > 100)
				{
					$bookrate_pl_n = 100;
				}
				else
				{
					$bookrate_pl_n = $bookrate_pl_n;
				}
			}
			else
			{
				$bookrate_pl_n = 0;
			}
			
			if($row_corp_n->incoming_corp > 0)
			{
				if($app_corp_n->app_corp == 0)
				{
					$app_corp_n->app_corp = 1;
				}
				$bookrate_corp_n = ($app_corp_n->app_corp / $row_corp_n->incoming_corp) * 100;
				if($bookrate_corp_n > 100)
				{
					$bookrate_corp_n = 100;
				}
				else
				{
					$bookrate_corp_n = $bookrate_corp_n;
				}
			}
			else
			{
				$bookrate_corp_n = 0;
			}
			
			//end formula bookrate
			?>
			<tr>
				<td>BOOK RATE</td>
				<td><?php echo round($bookrate_cc_n); ?> %</td>
				<td><?php echo round($bookrate_edc_n); ?> %</td>
				<td><?php echo round($bookrate_sc_n); ?> %</td>
				<td><?php echo round($bookrate_pl_n); ?> %</td>
				<td><?php echo round($bookrate_corp_n); ?> %</td>
			</tr>
			<?php
			//formula runrate
			//$hb = hitunghari(date('01-m-Y'), date('d-m-Y'), "-");
			//$hk = hitunghari(date('01-m-Y'), date('t-m-Y'), "-");
			
			if($row_cc_n->incoming_cc > 0)
			{
				$runrate_inc_cc_n = ($row_cc_n->incoming_cc / $hb) * $hk;
			}
			else
			{
				$runrate_inc_cc_n = 0;
			}
			
			if($row_edc_n->incoming_edc > 0)
			{
				$runrate_inc_edc_n = ($row_edc_n->incoming_edc / $hb) * $hk;
			}
			else
			{
				$runrate_inc_edc_n = 0;
			}
			
			if($row_sc_n->incoming_sc > 0)
			{
				$runrate_inc_sc_n = ($row_sc_n->incoming_sc / $hb) * $hk;
			}
			else
			{
				$runrate_inc_sc_n = 0;
			}
			
			if($row_pl_n->incoming_pl > 0)
			{
				$runrate_inc_pl_n = ($row_pl_n->incoming_pl / $hb) * $hk;
			}
			else
			{
				$runrate_inc_pl_n = 0;
			}
			
			if($row_corp_n->incoming_corp > 0)
			{
				$runrate_inc_corp_n = ($row_corp_n->incoming_corp / $hb) * $hk;
			}
			else
			{
				$runrate_inc_corp_n = 0;
			}
			
			//end formula runrate
			?>
			<tr>
				<td>RUN RATE INC</td>
				<td><?php echo round($runrate_inc_cc_n); ?></td>
				<td><?php echo round($runrate_inc_edc_n); ?></td>
				<td><?php echo round($runrate_inc_sc_n); ?></td>
				<td><?php echo round($runrate_inc_pl_n); ?></td>
				<td><?php echo round($runrate_inc_corp_n); ?></td>
			</tr>
			<?php
			//formula runrate app
			
			if($app_cc_n->app_cc > 0)
			{
				$runrate_app_cc_n = ($app_cc_n->app_cc / $hb) * $hk;
			}
			else
			{
				$runrate_app_cc_n = 0;
			}
			
			if($app_edc_n->app_edc > 0)
			{
				$runrate_app_edc_n = ($app_edc_n->app_edc / $hb) * $hk;
			}
			else
			{
				$runrate_app_edc_n = 0;
			}
			
			if($app_sc_n->app_sc > 0)
			{
				$runrate_app_sc_n = ($app_sc_n->app_sc / $hb) * $hk;
			}
			else
			{
				$runrate_app_sc_n = 0;
			}
			
			if($app_pl_n->app_pl > 0)
			{
				$runrate_app_pl_n = ($app_pl_n->app_pl / $hb) * $hk;
			}
			else
			{
				$runrate_app_pl_n = 0;
			}
			
			if($app_corp_n->app_corp > 0)
			{
				$runrate_app_corp_n = ($app_corp_n->app_corp / $hb) * $hk;
			}
			else
			{
				$runrate_app_corp_n = 0;
			}
			
			//end formula
			?>
			<tr>
				<td>RUN RATE APP</td>
				<td><?php echo round($runrate_app_cc_n); ?></td>
				<td><?php echo round($runrate_app_edc_n); ?></td>
				<td><?php echo round($runrate_app_sc_n); ?></td>
				<td><?php echo round($runrate_app_pl_n); ?></td>
				<td><?php echo round($runrate_app_corp_n); ?></td>
			</tr>
			<?php
			//formula productivity
			
			/*if($row_cc_n->incoming_cc > 0)
			{
				$prod_inc_cc_n = $row_cc_n->incoming_cc / $hb;
			}
			else
			{
				$prod_inc_cc_n = 0;
			}
			
			if($row_edc_n->incoming_edc > 0)
			{
				$prod_inc_edc_n = $row_edc_n->incoming_edc / $hb;
			}
			else
			{
				$prod_inc_edc_n = 0;
			}
			
			if($row_sc_n->incoming_sc > 0)
			{
				$prod_inc_sc_n = $row_sc_n->incoming_sc / $hb;
			}
			else
			{
				$prod_inc_sc_n = 0;
			}
			
			if($row_pl_n->incoming_pl > 0)
			{
				$prod_inc_pl_n = $row_pl_n->incoming_pl / $hb;
			}
			else
			{
				$prod_inc_pl_n = 0;
			}
			
			if($row_corp_n->incoming_corp > 0)
			{
				$prod_inc_corp_n = $row_corp_n->incoming_corp / $hb;
			}
			else
			{
				$prod_inc_corp_n = 0;
			}*/
			
			//end formula
			?>
			<tr style="display:none;">
				<td>PRODUCTIVITY INC</td>
				<td><?php //echo round($prod_inc_cc_n); ?></td>
				<td><?php //echo round($prod_inc_edc_n); ?></td>
				<td><?php //echo round($prod_inc_sc_n); ?></td>
				<td><?php //echo round($prod_inc_pl_n); ?></td>
				<td><?php //echo round($prod_inc_corp_n); ?></td>
			</tr>
			<?php
			//formula prodcutivity app
			
			/*if($app_cc_n->app_cc > 0)
			{
				$prod_app_cc_n = $app_cc_n->app_cc / $hb;
			}
			else
			{
				$prod_app_cc_n = 0;
			}
			
			if($app_edc_n->app_edc > 0)
			{
				$prod_app_edc_n = $app_edc_n->app_edc / $hb;
			}
			else
			{
				$prod_app_edc_n = 0;
			}
			
			if($app_sc_n->app_sc > 0)
			{
				$prod_app_sc_n = $app_sc_n->app_sc / $hb;
			}
			else
			{
				$prod_app_sc_n = 0;
			}
			
			if($app_pl_n->app_pl > 0)
			{
				$prod_app_pl_n = $app_pl_n->app_pl / $hb;
			}
			else
			{
				$prod_app_pl_n = 0;
			}
			
			if($app_corp_n->app_corp > 0)
			{
				$prod_app_corp_n = $app_corp_n->app_corp / $hb;
			}
			else
			{
				$prod_app_corp_n = 0;
			}*/
			
			//end formula
			?>
			<tr style="display:none;">
				<td>PRODUCTIVITY APP</td>
				<td><?php //echo round($prod_app_cc_n); ?></td>
				<td><?php //echo round($prod_app_edc_n); ?></td>
				<td><?php //echo round($prod_app_sc_n); ?></td>
				<td><?php //echo round($prod_app_pl_n); ?></td>
				<td><?php //echo round($prod_app_corp_n); ?></td>
			</tr>
		</table>
	</div>
	<div class="box-footer clearfix"></div>
</div>
<?php

$row_cc_ls = $setoran_cc_ls->row();
$row_edc_ls = $setoran_edc_ls->row();
$row_sc_ls = $setoran_sc_ls->row();
$row_pl_ls = $setoran_pl_ls->row();
$row_corp_ls = $setoran_corp_ls->row();

$app_cc_ls = $approve_cc_ls->row();
$app_edc_ls = $approve_edc_ls->row();
$app_sc_ls = $approve_sc_ls->row();
$app_pl_ls = $approve_pl_ls->row();
$app_corp_ls = $approve_corp_ls->row();

//approve cc M-1
$approve_cc_m1 = $app_cc_ls->app_cc;
?>
<div class="box box-primary" id="perform2">
	<div class="box-header with-border">
		<h4>Performance</h4>
		Periode : <?php echo date('M / Y', strtotime('-1 month')); ?>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	    </button>
        </div>
	</div>
	<div class="box-body">
		<table class="table table-hover" style="font-size: 10px !important;">
			<tr>
				<td>&nbsp;</td>
				<td>CC</td>
				<td>EDC</td>
				<td>SC</td>
				<td>PL</td>
				<td>CORP</td>
			</tr>
			<tr>
				<td>SETORAN APLIKASI</td>
				<td><?php echo $row_cc_ls->setoran_cc; ?></td>
				<td><?php echo $row_edc_ls->setoran_edc; ?></td>
				<td><?php echo $row_sc_ls->setoran_sc; ?></td>
				<td><?php echo $row_pl_ls->setoran_pl; ?></td>
				<td><?php echo $row_corp_ls->setoran_corp; ?></td>
			</tr>
			<tr>
				<td>INCOMING</td>
				<td><?php echo $row_cc_ls->incoming_cc; ?></td>
				<td><?php echo $row_edc_ls->incoming_edc; ?></td>
				<td><?php echo $row_sc_ls->incoming_sc; ?></td>
				<td><?php echo $row_pl_ls->incoming_pl; ?></td>
				<td><?php echo $row_corp_ls->incoming_corp; ?></td>
			</tr>
			<tr>
				<td>APPROVED</td>
				<td><?php echo $approve_cc_m1; ?></td>
				<td><?php echo $app_edc_ls->app_edc; ?></td>
				<td><?php echo $app_sc_ls->app_sc; ?></td>
				<td><?php echo $app_pl_ls->app_pl; ?></td>
				<td><?php echo $app_corp_ls->app_corp; ?></td>
			</tr>
			<tr>
				<td>CANCEL</td>
				<td><?php echo $app_cc_ls->cancel_cc; ?></td>
				<td><?php echo $app_edc_ls->cancel_edc; ?></td>
				<td><?php echo $app_sc_ls->cancel_sc; ?></td>
				<td><?php echo $app_pl_ls->cancel_pl; ?></td>
				<td><?php echo $app_corp_ls->cancel_corp; ?></td>
			</tr>
			<tr>
				<td>DECLINE / REJECT</td>
				<td><?php echo $app_cc_ls->reject_cc; ?></td>
				<td><?php echo $app_edc_ls->reject_edc; ?></td>
				<td><?php echo $app_sc_ls->reject_sc; ?></td>
				<td><?php echo $app_pl_ls->reject_pl; ?></td>
				<td><?php echo $app_corp_ls->reject_corp; ?></td>
			</tr>
			<?php
			//formula apprate
			
			if($app_cc_ls->app_cc > 0)
			{
				$rate_cc_ls = $app_cc_ls->app_cc / ($app_cc_ls->app_cc +$app_cc_ls->reject_cc) * 100;
				if($rate_cc_ls > 100)
				{
					$rate_cc_ls = 100;
				}
				else
				{
					$rate_cc_ls = $rate_cc_ls;
				}
			}
			else
			{
				$rate_cc_ls = 0;
			}
			
			if($app_edc_ls->app_edc > 0)
			{
				$rate_edc_ls = $app_edc_ls->app_edc / ($app_edc_ls->app_edc + $app_edc_ls->reject_edc) * 100;
				if($rate_edc_ls > 100)
				{
					$rate_edc_ls = 100;
				}
				else
				{
					$rate_edc_ls = $rate_edc_ls;
				}
			}
			else
			{
				$rate_edc_ls = 0;
			}
			
			if($app_sc_ls->app_sc > 0)
			{
				$rate_sc_ls = $app_sc_ls->app_sc / ($app_sc_ls->app_sc + $app_sc_ls->reject_sc) * 100;
				if($rate_sc_ls > 100)
				{
					$rate_sc_ls = 100;
				}
				else
				{
					$rate_sc_ls = $rate_sc_ls;
				}
			}
			else
			{
				$rate_sc_ls = 0;
			}
			
			if($app_pl_ls->app_pl > 0)
			{
				$rate_pl_ls = $app_pl_ls->app_pl / ($app_pl_ls->app_pl + $app_pl_ls->reject_pl) * 100;
				if($rate_pl_ls > 100)
				{
					$rate_pl_ls = 100;
				}
				else
				{
					$rate_pl_ls = $rate_pl_ls;
				}
			}
			else
			{
				$rate_pl_ls = 0;
			}
			
			if($app_corp_ls->app_corp > 0)
			{
				$rate_corp_ls = $app_corp_ls->app_corp / ($app_corp_ls->app_corp + $app_corp_ls->reject_corp) * 100;
				if($rate_corp_ls > 100)
				{
					$rate_corp_ls = 100;
				}
				else
				{
					$rate_corp_ls = $rate_corp_ls;
				}
			}
			else
			{
				$rate_corp_ls = 0;
			}
			
			//end formula
			?>
			<tr>
				<td>APP RATE</td>
				<td><?php echo round($rate_cc_ls,1) ?> %</td>
				<td><?php echo round($rate_edc_ls,1) ?> %</td>
				<td><?php echo round($rate_sc_ls,1) ?> %</td>
				<td><?php echo round($rate_pl_ls,1) ?> %</td>
				<td><?php echo round($rate_corp_ls,1) ?> %</td>
			</tr>
			<?php
			//formula bookrate
			
			if($row_cc_ls->incoming_cc > 0)
			{
				if($app_cc_ls->app_cc == 0)
				{
					$app_cc_ls->app_cc = 1;
				}
				$bookrate_cc_ls = ($app_cc_ls->app_cc / $row_cc_ls->incoming_cc) * 100;
				if($bookrate_cc_ls > 100)
				{
					$bookrate_cc_ls = 100;
				}
				else
				{
					$bookrate_cc_ls = $bookrate_cc_ls;
				}
			}
			else
			{
				$bookrate_cc_ls = 0;
			}
			
			if($row_edc_ls->incoming_edc > 0)
			{
				if($app_edc_ls->app_edc == 0)
				{
					$app_edc_ls->app_edc = 1;
				}
				$bookrate_edc_ls = ($app_edc_ls->app_edc / $row_edc_ls->incoming_edc) * 100;
				if($bookrate_edc_ls > 100)
				{
					$bookrate_edc_ls = 100;
				}
				else
				{
					$bookrate_edc_ls = $bookrate_edc_ls;
				}
			}
			else
			{
				$bookrate_edc_ls = 0;
			}
			
			if($row_sc_ls->incoming_sc > 0)
			{
				if($app_sc_ls->app_sc == 0)
				{
					$app_sc_ls->app_sc = 1;
				}
				$bookrate_sc_ls = ($app_sc_ls->app_sc / $row_sc_ls->incoming_sc) * 100;
				if($bookrate_sc_ls > 100)
				{
					$bookrate_sc_ls = 100;
				}
				else
				{
					$bookrate_sc_ls = $bookrate_sc_ls;
				}
			}
			else
			{
				$bookrate_sc_ls = 0;
			}
			
			if($row_pl_ls->incoming_pl > 0)
			{
				if($app_pl_ls->app_pl == 0)
				{
					$app_pl_ls->app_pl = 1;
				}
				$bookrate_pl_ls = ($app_pl_ls->app_pl / $row_pl_ls->incoming_pl) * 100;
				if($bookrate_pl_ls > 100)
				{
					$bookrate_pl_ls = 100;
				}
				else
				{
					$bookrate_pl_ls = $bookrate_pl_ls;
				}
			}
			else
			{
				$bookrate_pl_ls = 0;
			}
			
			if($row_corp_ls->incoming_corp > 0)
			{
				if($app_corp_ls->app_corp == 0)
				{
					$app_corp_ls->app_corp = 1;
				}
				$bookrate_corp_ls = ($app_corp_ls->app_corp / $row_corp_ls->incoming_corp) * 100;
				if($bookrate_corp_ls > 100)
				{
					$bookrate_corp_ls = 100;
				}
				else
				{
					$bookrate_corp_ls = $bookrate_corp_ls;
				}
			}
			else
			{
				$bookrate_corp_ls = 0;
			}
			
			//end formula bookrate
			?>
			<tr>
				<td>BOOK RATE</td>
				<td><?php echo round($bookrate_cc_ls,1); ?> %</td>
				<td><?php echo round($bookrate_edc_ls,1); ?> %</td>
				<td><?php echo round($bookrate_sc_ls,1); ?> %</td>
				<td><?php echo round($bookrate_pl_ls,1); ?> %</td>
				<td><?php echo round($bookrate_corp_ls,1); ?> %</td>
			</tr>
			<tr>
				<td>RUN RATE INC</td>
				<td><?php echo $row_cc_ls->incoming_cc; ?></td>
				<td><?php echo $row_edc_ls->incoming_edc; ?></td>
				<td><?php echo $row_sc_ls->incoming_sc; ?></td>
				<td><?php echo $row_pl_ls->incoming_pl; ?></td>
				<td><?php echo $row_corp_ls->incoming_corp; ?></td>
			</tr>
			<tr>
				<td>RUN RATE APP</td>
				<td><?php echo $approve_cc_m1; ?></td>
				<td><?php echo $app_edc_ls->app_edc; ?></td>
				<td><?php echo $app_sc_ls->app_sc; ?></td>
				<td><?php echo $app_pl_ls->app_pl; ?></td>
				<td><?php echo $app_corp_ls->app_corp; ?></td>
			</tr>
			<?php
			//formula productivity
			
			/*if($row_cc_ls->incoming_cc > 0)
			{
				$prod_inc_cc_ls = $row_cc_ls->incoming_cc / $hb;
			}
			else
			{
				$prod_inc_cc_ls = 0;
			}
			
			if($row_edc_ls->incoming_edc > 0)
			{
				$prod_inc_edc_ls = $row_edc_ls->incoming_edc / $hb;
			}
			else
			{
				$prod_inc_edc_ls = 0;
			}
			
			if($row_sc_ls->incoming_sc > 0)
			{
				$prod_inc_sc_ls = $row_sc_ls->incoming_sc / $hb;
			}
			else
			{
				$prod_inc_sc_ls = 0;
			}
			
			if($row_pl_ls->incoming_pl > 0)
			{
				$prod_inc_pl_ls = $row_pl_ls->incoming_pl / $hb;
			}
			else
			{
				$prod_inc_pl_ls = 0;
			}
			
			if($row_corp_ls->incoming_corp > 0)
			{
				$prod_inc_corp_ls = $row_corp_ls->incoming_corp / $hb;
			}
			else
			{
				$prod_inc_corp_ls = 0;
			}*/
			
			//end formula
			?>
			<tr style="display:none;">
				<td>PRODUCTIVITY INC</td>
				<td><?php //echo round($prod_inc_cc_ls); ?></td>
				<td><?php //echo round($prod_inc_edc_ls); ?></td>
				<td><?php //echo round($prod_inc_sc_ls); ?></td>
				<td><?php //echo round($prod_inc_pl_ls); ?></td>
				<td><?php //echo round($prod_inc_corp_ls); ?></td>
			</tr>
			<?php
			//formula prodcutivity app
			
			/*if($app_cc_ls->app_cc > 0)
			{
				$prod_app_cc_ls = $app_cc_ls->app_cc / $hb;
			}
			else
			{
				$prod_app_cc_ls = 0;
			}
			
			if($app_edc_ls->app_edc > 0)
			{
				$prod_app_edc_ls = $app_edc_ls->app_edc / $hb;
			}
			else
			{
				$prod_app_edc_ls = 0;
			}
			
			if($app_sc_ls->app_sc > 0)
			{
				$prod_app_sc_ls = $app_sc_ls->app_sc / $hb;
			}
			else
			{
				$prod_app_sc_ls = 0;
			}
			
			if($app_pl_ls->app_pl > 0)
			{
				$prod_app_pl_ls = $app_pl_ls->app_pl / $hb;
			}
			else
			{
				$prod_app_pl_ls = 0;
			}
			
			if($app_corp_ls->app_corp > 0)
			{
				$prod_app_corp_ls = $app_corp_ls->app_corp / $hb;
			}
			else
			{
				$prod_app_corp_ls = 0;
			}*/
			
			//end formula
			?>
			<tr style="display:none;">
				<td>PRODUCTIVITY APP</td>
				<td><?php //echo round($prod_app_cc_ls); ?></td>
				<td><?php //echo round($prod_app_edc_ls); ?></td>
				<td><?php //echo round($prod_app_sc_ls); ?></td>
				<td><?php //echo round($prod_app_pl_ls); ?></td>
				<td><?php //echo round($prod_app_corp_ls); ?></td>
			</tr>
		</table>
	</div>
	<div class="box-footer clearfix"></div>
</div>
<?php

$row_cc_lss = $setoran_cc_lss->row();
$row_edc_lss = $setoran_edc_lss->row();
$row_sc_lss = $setoran_sc_lss->row();
$row_pl_lss = $setoran_pl_lss->row();
$row_corp_lss = $setoran_corp_lss->row();

$app_cc_lss = $approve_cc_lss->row();
$app_edc_lss = $approve_edc_lss->row();
$app_sc_lss = $approve_sc_lss->row();
$app_pl_lss = $approve_pl_lss->row();
$app_corp_lss = $approve_corp_lss->row();
?>
<div class="box box-primary" id="perform3">
	<div class="box-header with-border">
		<h4>Performance</h4>
		Periode : <?php echo date('M / Y', strtotime('-2 month')); ?>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	    </button>
        </div>
	</div>
	<div class="box-body">
		<table class="table table-hover" style="font-size: 10px !important;">
			<tr>
				<td>&nbsp;</td>
				<td>CC</td>
				<td>EDC</td>
				<td>SC</td>
				<td>PL</td>
				<td>CORP</td>
			</tr>
			<tr>
				<td>SETORAN APLIKASI</td>
				<td><?php echo $row_cc_lss->setoran_cc; ?></td>
				<td><?php echo $row_edc_lss->setoran_edc; ?></td>
				<td><?php echo $row_sc_lss->setoran_sc; ?></td>
				<td><?php echo $row_pl_lss->setoran_pl; ?></td>
				<td><?php echo $row_corp_lss->setoran_corp; ?></td>
			</tr>
			<tr>
				<td>INCOMING</td>
				<td><?php echo $row_cc_lss->incoming_cc; ?></td>
				<td><?php echo $row_edc_lss->incoming_edc; ?></td>
				<td><?php echo $row_sc_lss->incoming_sc; ?></td>
				<td><?php echo $row_pl_lss->incoming_pl; ?></td>
				<td><?php echo $row_corp_lss->incoming_corp; ?></td>
			</tr>
			<tr>
				<td>APPROVED</td>
				<td><?php echo $app_cc_lss->app_cc; ?></td>
				<td><?php echo $app_edc_lss->app_edc; ?></td>
				<td><?php echo $app_sc_lss->app_sc; ?></td>
				<td><?php echo $app_pl_lss->app_pl; ?></td>
				<td><?php echo $app_corp_lss->app_corp; ?></td>
			</tr>
			<tr>
				<td>CANCEL</td>
				<td><?php echo $app_cc_lss->cancel_cc; ?></td>
				<td><?php echo $app_edc_lss->cancel_edc; ?></td>
				<td><?php echo $app_sc_lss->cancel_sc; ?></td>
				<td><?php echo $app_pl_lss->cancel_pl; ?></td>
				<td><?php echo $app_corp_lss->cancel_corp; ?></td>
			</tr>
			<tr>
				<td>DECLINE / REJECT</td>
				<td><?php echo $app_cc_lss->reject_cc; ?></td>
				<td><?php echo $app_edc_lss->reject_edc; ?></td>
				<td><?php echo $app_sc_lss->reject_sc; ?></td>
				<td><?php echo $app_pl_lss->reject_pl; ?></td>
				<td><?php echo $app_corp_lss->reject_corp; ?></td>
			</tr>
			<?php
			//formula apprate
			
			if($app_cc_lss->app_cc > 0)
			{
				$rate_cc_lss = $app_cc_lss->app_cc / ($app_cc_lss->app_cc +$app_cc_lss->reject_cc) * 100;
				if($rate_cc_lss > 100)
				{
					$rate_cc_lss = 100;
				}
				else
				{
					$rate_cc_lss = $rate_cc_lss;
				}
			}
			else
			{
				$rate_cc_lss = 0;
			}
			
			if($app_edc_lss->app_edc > 0)
			{
				$rate_edc_lss = $app_edc_lss->app_edc / ($app_edc_lss->app_edc + $app_edc_lss->reject_edc) * 100;
				if($rate_edc_lss > 100)
				{
					$rate_edc_lss = 100;
				}
				else
				{
					$rate_edc_lss = $rate_edc_lss;
				}
			}
			else
			{
				$rate_edc_lss = 0;
			}
			
			if($app_sc_lss->app_sc > 0)
			{
				$rate_sc_lss = $app_sc_lss->app_sc / ($app_sc_lss->app_sc + $app_sc_lss->reject_sc) * 100;
				if($rate_sc_lss > 100)
				{
					$rate_sc_lss = 100;
				}
				else
				{
					$rate_sc_lss = $rate_sc_lss;
				}
			}
			else
			{
				$rate_sc_lss = 0;
			}
			
			if($app_pl_lss->app_pl > 0)
			{
				$rate_pl_lss = $app_pl_lss->app_pl / ($app_pl_lss->app_pl + $app_pl_lss->reject_pl) * 100;
				if($rate_pl_lss > 100)
				{
					$rate_pl_lss = 100;
				}
				else
				{
					$rate_pl_lss = $rate_pl_lss;
				}
			}
			else
			{
				$rate_pl_lss = 0;
			}
			
			if($app_corp_lss->app_corp > 0)
			{
				$rate_corp_lss = $app_corp_lss->app_corp / ($app_corp_lss->app_corp + $app_corp_lss->reject_corp) * 100;
				if($rate_corp_lss > 100)
				{
					$rate_corp_lss = 100;
				}
				else
				{
					$rate_corp_lss = $rate_corp_lss;
				}
			}
			else
			{
				$rate_corp_lss = 0;
			}
			
			//end formula
			?>
			<tr>
				<td>APP RATE</td>
				<td><?php echo round($rate_cc_lss,1); ?> %</td>
				<td><?php echo round($rate_edc_lss,1); ?> %</td>
				<td><?php echo round($rate_sc_lss,1); ?> %</td>
				<td><?php echo round($rate_pl_lss,1); ?> %</td>
				<td><?php echo round($rate_corp_lss,1); ?> %</td>
			</tr>
			<?php
			//formula bookrate
			
			if($row_cc_lss->incoming_cc > 0)
			{
				if($app_cc_lss->app_cc == 0)
				{
					$app_cc_lss->app_cc = 1;
				}
				$bookrate_cc_lss = ($app_cc_lss->app_cc / $row_cc_lss->incoming_cc) * 100;
				if($bookrate_cc_lss > 100)
				{
					$bookrate_cc_lss = 100;
				}
				else
				{
					$bookrate_cc_lss = $bookrate_cc_lss;
				}
			}
			else
			{
				$bookrate_cc_lss = 0;
			}
			
			if($row_edc_lss->incoming_edc > 0)
			{
				if($app_edc_lss->app_edc == 0)
				{
					$app_edc_lss->app_edc = 1;
				}
				$bookrate_edc_lss = ($app_edc_lss->app_edc / $row_edc_lss->incoming_edc) * 100;
				if($bookrate_edc_lss > 100)
				{
					$bookrate_edc_lss = 100;
				}
				else
				{
					$bookrate_edc_lss = $bookrate_edc_lss;
				}
			}
			else
			{
				$bookrate_edc_lss = 0;
			}
			
			if($row_sc_lss->incoming_sc > 0)
			{
				if($app_sc_lss->app_sc == 0)
				{
					$app_sc_lss->app_sc = 1;
				}
				$bookrate_sc_lss = ($app_sc_lss->app_sc / $row_sc_lss->incoming_sc) * 100;
				if($bookrate_sc_lss > 100)
				{
					$bookrate_sc_lss = 100;
				}
				else
				{
					$bookrate_sc_lss = $bookrate_sc_lss;
				}
			}
			else
			{
				$bookrate_sc_lss = 0;
			}
			
			if($row_pl_lss->incoming_pl > 0)
			{
				if($app_pl_lss->app_pl == 0)
				{
					$app_pl_lss->app_pl = 1;
				}
				$bookrate_pl_lss = ($app_pl_lss->app_pl / $row_pl_lss->incoming_pl) * 100;
				if($bookrate_pl_lss > 100)
				{
					$bookrate_pl_lss = 100;
				}
				else
				{
					$bookrate_pl_lss = $bookrate_pl_lss;
				}
			}
			else
			{
				$bookrate_pl_lss = 0;
			}
			
			if($row_corp_lss->incoming_corp > 0)
			{
				if($app_corp_lss->app_corp == 0)
				{
					$app_corp_lss->app_corp = 1;
				}
				$bookrate_corp_lss = ($app_corp_lss->app_corp / $row_corp_lss->incoming_corp) * 100;
				if($bookrate_corp_lss > 100)
				{
					$bookrate_corp_lss = 100;
				}
				else
				{
					$bookrate_corp_lss = $bookrate_corp_lss;
				}
			}
			else
			{
				$bookrate_corp_lss = 0;
			}
			
			//end formula bookrate
			?>
			<tr>
				<td>BOOK RATE</td>
				<td><?php echo round($bookrate_cc_lss,1); ?> %</td>
				<td><?php echo round($bookrate_edc_lss,1); ?> %</td>
				<td><?php echo round($bookrate_sc_lss,1); ?> %</td>
				<td><?php echo round($bookrate_pl_lss,1); ?> %</td>
				<td><?php echo round($bookrate_corp_lss,1); ?> %</td>
			</tr>
			<tr>
				<td>RUN RATE INC</td>
				<td><?php echo $row_cc_lss->incoming_cc; ?></td>
				<td><?php echo $row_edc_lss->incoming_edc; ?></td>
				<td><?php echo $row_sc_lss->incoming_sc; ?></td>
				<td><?php echo $row_pl_lss->incoming_pl; ?></td>
				<td><?php echo $row_corp_lss->incoming_corp; ?></td>
			</tr>
			<tr>
				<td>RUN RATE APP</td>
				<td><?php echo $app_cc_lss->app_cc; ?></td>
				<td><?php echo $app_edc_lss->app_edc; ?></td>
				<td><?php echo $app_sc_lss->app_sc; ?></td>
				<td><?php echo $app_pl_lss->app_pl; ?></td>
				<td><?php echo $app_corp_lss->app_corp; ?></td>
			</tr>
			<?php
			//formula productivity
			
			/*if($row_cc_lss->incoming_cc > 0)
			{
				$prod_inc_cc_lss = $row_cc_lss->incoming_cc / $hb;
			}
			else
			{
				$prod_inc_cc_lss = 0;
			}
			
			if($row_edc_lss->incoming_edc > 0)
			{
				$prod_inc_edc_lss = $row_edc_lss->incoming_edc / $hb;
			}
			else
			{
				$prod_inc_edc_lss = 0;
			}
			
			if($row_sc_lss->incoming_sc > 0)
			{
				$prod_inc_sc_lss = $row_sc_lss->incoming_sc / $hb;
			}
			else
			{
				$prod_inc_sc_lss = 0;
			}
			
			if($row_pl_lss->incoming_pl > 0)
			{
				$prod_inc_pl_lss = $row_pl_lss->incoming_pl / $hb;
			}
			else
			{
				$prod_inc_pl_lss = 0;
			}
			
			if($row_corp_lss->incoming_corp > 0)
			{
				$prod_inc_corp_lss = $row_corp_lss->incoming_corp / $hb;
			}
			else
			{
				$prod_inc_corp_lss = 0;
			}*/
			
			//end formula
			?>
			<tr style="display:none;">
				<td>PRODUCTIVITY INC</td>
				<td><?php //echo round($prod_inc_cc_lss); ?></td>
				<td><?php //echo round($prod_inc_edc_lss); ?></td>
				<td><?php //echo round($prod_inc_sc_lss); ?></td>
				<td><?php //echo round($prod_inc_pl_lss); ?></td>
				<td><?php //echo round($prod_inc_corp_lss); ?></td>
			</tr>
			<?php
			//formula prodcutivity app
			
			/*if($app_cc_lss->app_cc > 0)
			{
				$prod_app_cc_lss = $app_cc_lss->app_cc / $hb;
			}
			else
			{
				$prod_app_cc_lss = 0;
			}
			
			if($app_edc_lss->app_edc > 0)
			{
				$prod_app_edc_lss = $app_edc_lss->app_edc / $hb;
			}
			else
			{
				$prod_app_edc_lss = 0;
			}
			
			if($app_sc_lss->app_sc > 0)
			{
				$prod_app_sc_lss = $app_sc_lss->app_sc / $hb;
			}
			else
			{
				$prod_app_sc_lss = 0;
			}
			
			if($app_pl_lss->app_pl > 0)
			{
				$prod_app_pl_lss = $app_pl_lss->app_pl / $hb;
			}
			else
			{
				$prod_app_pl_lss = 0;
			}
			
			if($app_corp_lss->app_corp > 0)
			{
				$prod_app_corp_lss = $app_corp_lss->app_corp / $hb;
			}
			else
			{
				$prod_app_corp_lss = 0;
			}*/
			
			//end formula
			?>
			<tr style="display:none;">
				<td>PRODUCTIVITY APP</td>
				<td><?php //echo round($prod_app_cc_lss); ?></td>
				<td><?php //echo round($prod_app_edc_lss); ?></td>
				<td><?php //echo round($prod_app_sc_lss); ?></td>
				<td><?php //echo round($prod_app_pl_lss); ?></td>
				<td><?php //echo round($prod_app_corp_lss); ?></td>
			</tr>
		</table>
	</div>
	<div class="box-footer clearfix"></div>
</div>

<script>
	function viewSales(param, sales_code)
	{
		$('#modalSales').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>team_performance/'+ param + '/'+ sales_code,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}
</script>

<!-- Modal sales -->
<div class="modal fade" id="modalSales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-users"></i> View Team</h4>
			</div>
			<div class="modal-body">
				<div id="pop"></div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->