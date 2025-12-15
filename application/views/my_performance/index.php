<?php

//Current Month

$row_cc = $sql_cc->row();
$row_edc = $sql_edc->row();
$row_sc = $sql_sc->row();
$row_pl = $sql_pl->row();
$row_corp = $sql_corp->row();
$row_tele = $sql_tele->row();

$rapp_cc = $app_cc->row();
$rapp_edc = $app_edc->row();
$rapp_sc = $app_sc->row();
$rapp_pl = $app_pl->row();
$rapp_corp = $app_corp->row();

?>
<div class="box box-primary">
	<div class="box-header with-border">
		<h4>My Performance</h4><br>
		Periode : <?php echo date('M-Y'); ?>
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
				<td><?php echo $row_cc->setoran_cc; ?></td>
				<td><?php echo $row_edc->setoran_edc; ?></td>
				<td><?php echo $row_sc->setoran_sc; ?></td>
				<td><?php echo $row_pl->setoran_pl; ?></td>
				<td><?php echo $row_corp->setoran_corp; ?></td>
			</tr>
			<tr>
				<td>INCOMING</td>
				<td><?php echo $row_cc->incoming_cc; ?></td>
				<td><?php echo $row_edc->incoming_edc; ?></td>
				<td><?php echo $row_sc->incoming_sc; ?></td>
				<td><?php echo $row_pl->incoming_pl; ?></td>
				<td><?php echo $row_corp->incoming_corp; ?></td>
			</tr>
			<tr>
				<td>APPROVED</td>
				<td><?php echo $rapp_cc->app_cc; ?></td>
				<td><?php echo $rapp_edc->app_edc; ?></td>
				<td><?php echo $rapp_sc->app_sc; ?></td>
				<td><?php echo $rapp_pl->app_pl; ?></td>
				<td><?php echo $rapp_corp->app_corp ?></td>
			</tr>
			<tr>
				<td>CANCEL</td>
				<td><?php echo $rapp_cc->cancel_cc; ?></td>
				<td><?php echo $rapp_edc->cancel_edc; ?></td>
				<td><?php echo $rapp_sc->cancel_sc; ?></td>
				<td><?php echo $rapp_pl->cancel_pl; ?></td>
				<td><?php echo $rapp_corp->cancel_corp; ?></td>
			</tr>
			<tr>
				<td>DECLINE / REJECT</td>
				<td><?php echo $rapp_cc->reject_cc; ?></td>
				<td><?php echo $rapp_edc->reject_edc; ?></td>
				<td><?php echo $rapp_sc->reject_sc; ?></td>
				<td><?php echo $rapp_pl->reject_pl; ?></td>
				<td><?php echo $rapp_corp->reject_corp ?></td>
			</tr>
			<!--Formula App Rate-->
			<?php
			if($rapp_cc->app_cc > 0)
			{
				$ar_cc_ = ($rapp_cc->app_cc / ($rapp_cc->app_cc + $rapp_cc->reject_cc)) * 100;
				if($ar_cc_ > 100)
				{
					$ar_cc = 100;
				}
				else
				{
					$ar_cc = $ar_cc_;
				}
			}
			else
			{
				$ar_cc = 0;
			}
			
			if($rapp_edc->app_edc > 0)
			{
				$ar_edc_ = ($rapp_edc->app_edc / ($rapp_edc->app_edc + $rapp_edc->reject_edc)) * 100;
				if($ar_edc_ > 100)
				{
					$ar_edc = 100;
				}
				else
				{
					$ar_edc = $ar_edc_;
				}
			}
			else
			{
				$ar_edc = 0;
			}
			
			if($rapp_sc->app_sc > 0)
			{
				$ar_sc_ = ($rapp_sc->app_sc / ($rapp_sc->app_sc + $rapp_sc->reject_sc)) * 100;
				if($ar_sc_ > 100)
				{
					$ar_sc =  100;
				}
				else
				{
					$ar_sc = $ar_sc_;
				}
			}
			else
			{
				$ar_sc = 0;
			}
			
			if($rapp_pl->app_pl > 0)
			{
				$ar_pl_ = ($rapp_pl->app_pl / ($rapp_pl->app_pl + $rapp_pl->reject_pl)) * 100;
				if($ar_pl_ > 100)
				{
					$ar_pl = 100;
				}
				else
				{
					$ar_pl=$ar_pl_;
				}
			}
			else
			{
				$ar_pl = 0;
			}
			
			if($rapp_corp->app_corp > 0)
			{
				$ar_corp_ = ($rapp_corp->app_corp / ($rapp_corp->app_corp + $rapp_corp->reject_corp)) * 100;
				if($ar_corp_ > 100)
				{
					$ar_corp = 100;
				}
				else
				{
					$ar_corp = $ar_corp_;
				}
			}
			else
			{
				$ar_corp = 0;
			}
			?>
			<!--End Formula App Rate-->
			<tr>
				<td>APP RATE</td>
				<td><?php echo round($ar_cc,1); ?> %</td>
				<td><?php echo round($ar_edc,1); ?> %</td>
				<td><?php echo round($ar_sc,1); ?> %</td>
				<td><?php echo round($ar_pl,1); ?> %</td>
				<td><?php echo round($ar_corp,1); ?> %</td>
			</tr>
			<!--Formula Book Rate-->
			<?php
			
			if($row_cc->incoming_cc > 0)
			{
				if($rapp_cc->app_cc == 0)
				{
					$rapp_cc->app_cc = 1;
				}
				$br_cc = ($rapp_cc->app_cc / $row_cc->incoming_cc) * 100;
				if($br_cc > 100)
				{
					$br_cc = 100;
				}
				else
				{
					$br_cc = $br_cc;
				}
			}
			else
			{
				$br_cc = 0;
			}
			
			if($row_edc->incoming_edc > 0)
			{
				if($rapp_edc->app_edc == 0)
				{
					$rapp_edc->app_edc = 1;
				}
				$br_edc = ($rapp_edc->app_edc / $row_edc->incoming_edc) * 100;
				if($br_edc > 100)
				{
					$br_edc = 100;
				}
				else
				{
					$br_edc = $br_edc;
				}
			}
			else
			{
				$br_edc = 0;
			}
			
			if($row_sc->incoming_sc > 0)
			{
				if($rapp_sc->app_sc == 0)
				{
					$rapp_sc->app_sc = 1;
				}
				$br_sc = ($rapp_sc->app_sc / $row_sc->incoming_sc) * 100;
				if($br_sc > 100)
				{
					$br_sc = 100;
				}
				else
				{
					$br_sc = $br_sc;
				}
			}
			else
			{
				$br_sc = 0;
			}
			
			if($row_pl->incoming_pl > 0)
			{
				if($rapp_pl->app_pl == 0)
				{
					$rapp_pl->app_pl = 1;
				}
				$br_pl = ($rapp_pl->app_pl / $row_pl->incoming_pl) * 100;
				if($br_pl > 100)
				{
					$br_pl = 100;
				}
				else
				{
					$br_pl = $br_pl;
				}
			}
			else
			{
				$br_pl = 0;
			}
			
			if($row_corp->incoming_corp > 0)
			{
				if($rapp_corp->app_corp == 0)
				{
					$rapp_corp->app_corp = 1;
				}
				$br_corp = ($rapp_corp->app_corp / $row_corp->incoming_corp) * 100;
				if($br_corp > 100)
				{
					$br_corp = 100;
				}
				else
				{
					$br_corp = $br_corp;
				}
			}
			else
			{
				$br_corp = 0;
			}
			
			
			?>
			<!--End Formula Book Rate-->
			<tr>
				<td>BOOK RATE</td>
				<td><?php echo round($br_cc,1); ?> %</td>
				<td><?php echo round($br_edc,1); ?> %</td>
				<td><?php echo round($br_sc,1); ?> %</td>
				<td><?php echo round($br_pl,1); ?> %</td>
				<td><?php echo round($br_corp,1); ?> %</td>
			</tr>
			<!--Formula RunRate-->
			<?php
				//$hb = hitunghari(date('01-m-Y'), date('d-m-Y'), "-");
				//$hk = hitunghari(date('01-m-Y'), date('t-m-Y'), "-");
				$runRateCc = ($row_cc->incoming_cc / $hb) * $hk;
				$runRateEdc = ($row_edc->incoming_edc / $hb) * $hk;
				$runRateSc = ($row_sc->incoming_sc / $hb) * $hk;
				$runRatePl = ($row_pl->incoming_pl / $hb) * $hk;
				$runRateCorp = ($row_corp->incoming_corp / $hb) * $hk;
			?>
			<!--End Formula RunRate Inc-->
			<tr>
				<td>RUN RATE INC</td>
				<td><?php echo round($runRateCc); ?></td>
				<td><?php echo round($runRateEdc); ?></td>
				<td><?php echo round($runRateSc); ?></td>
				<td><?php echo round($runRatePl); ?></td>
				<td><?php echo round($runRateCorp); ?></td>
			</tr>
			<!-- Formula RunRate App-->
			<?php
				$app_runRateCc = ($rapp_cc->app_cc / $hb) * $hk;
				$app_runRateEdc = ($rapp_edc->app_edc / $hb) * $hk;
				$app_runRateSc = ($rapp_sc->app_sc / $hb) * $hk;
				$app_runRatePl = ($rapp_pl->app_pl / $hb) * $hk;
				$app_runRateCorp = ($rapp_corp->app_corp / $hb) * $hk;
			?>
			<!---->
			<tr>
				<td>RUN RATE APP</td>
				<td><?php echo round($app_runRateCc); ?></td>
				<td><?php echo round($app_runRateEdc); ?></td>
				<td><?php echo round($app_runRateSc); ?></td>
				<td><?php echo round($app_runRatePl); ?></td>
				<td><?php echo round($app_runRateCorp); ?></td>
			</tr>
		</table>
	</div>
	<div class="box-footer clearfix"></div>
</div>

<!--Last Month-->
<?php
// Last month Setoran & Incoming
$last_inc_cc = $ls_sql_cc->row();
$last_inc_edc = $ls_sql_edc->row();
$last_inc_sc = $ls_sql_sc->row();
$last_inc_pl = $ls_sql_pl->row();
$last_inc_corp = $ls_sql_corp->row();

// Last month Approal, decline, reject

$last_app_cc = $ls_app_cc->row();
$last_app_edc = $ls_app_edc->row();
$last_app_sc = $ls_app_sc->row();
$last_app_pl = $ls_app_pl->row();
$last_app_corp = $ls_app_corp->row();

?>
<!--End Last Month-->

<div class="box box-primary collapsed-box">
	<div class="box-header with-border">
		<h4>My Performance</h4><br>
		Periode : <?php echo date('M-Y', strtotime('-1 month')); ?>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
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
				<td><?php echo $last_inc_cc->setoran_cc; ?></td>
				<td><?php echo $last_inc_edc->setoran_edc; ?></td>
				<td><?php echo $last_inc_sc->setoran_sc; ?></td>
				<td><?php echo $last_inc_pl->setoran_pl; ?></td>
				<td><?php echo $last_inc_corp->setoran_corp; ?></td>
			</tr>
			<tr>
				<td>INCOMING</td>
				<td><?php echo $last_inc_cc->incoming_cc; ?></td>
				<td><?php echo $last_inc_edc->incoming_edc; ?></td>
				<td><?php echo $last_inc_sc->incoming_sc; ?></td>
				<td><?php echo $last_inc_pl->incoming_pl; ?></td>
				<td><?php echo $last_inc_corp->incoming_corp; ?></td>
			</tr>
			<tr>
				<td>APPROVED</td>
				<td><?php echo $last_app_cc->app_cc; ?></td>
				<td><?php echo $last_app_edc->app_edc; ?></td>
				<td><?php echo $last_app_sc->app_sc; ?></td>
				<td><?php echo $last_app_pl->app_pl; ?></td>
				<td><?php echo $last_app_corp->app_corp; ?></td>
			</tr>
			<tr>
				<td>CANCEL</td>
				<td><?php echo $last_app_cc->cancel_cc; ?></td>
				<td><?php echo $last_app_edc->cancel_edc; ?></td>
				<td><?php echo $last_app_sc->cancel_sc; ?></td>
				<td><?php echo $last_app_pl->cancel_pl; ?></td>
				<td><?php echo $last_app_corp->cancel_corp; ?></td>
			</tr>
			<tr>
				<td>DECLINE / REJECT</td>
				<td><?php echo $last_app_cc->reject_cc; ?></td>
				<td><?php echo $last_app_edc->reject_edc; ?></td>
				<td><?php echo $last_app_sc->reject_sc; ?></td>
				<td><?php echo $last_app_pl->reject_pl; ?></td>
				<td><?php echo $last_app_corp->reject_corp; ?></td>
			</tr>
			<!-- Formula AppRate | App / (app + reject) / 100 -->
			<?php
				
				if($last_app_cc->app_cc > 0)
				{ $ls_apprate_cc = ($last_app_cc->app_cc / ($last_app_cc->app_cc + $last_app_cc->reject_cc)) * 100; }
				else
				{ $ls_apprate_cc = 0; }
				
				if($last_app_edc->app_edc > 0)
				{ $ls_apprate_edc = ($last_app_edc->app_edc / ($last_app_edc->app_edc + $last_app_edc->reject_edc)) * 100; }
				else
				{ $ls_apprate_edc = 0; }
			
				if($last_app_sc->app_sc > 0)
				{ $ls_apprate_sc = ($last_app_sc->app_sc / ($last_app_sc->app_sc + $last_app_sc->reject_sc)) * 100; }
				else
				{ $ls_apprate_sc = 0; }
				
				if($last_app_pl->app_pl > 0)
				{ $ls_apprate_pl = ($last_app_pl->app_pl / ($last_app_pl->app_pl + $last_app_pl->reject_pl)) * 100; }
				else
				{ $ls_apprate_pl = 0; }
			
				if($last_app_corp->app_corp > 0)
				{ $ls_apprate_corp = ($last_app_corp->app_corp / ($last_app_corp->app_corp + $last_app_corp->reject_corp)) * 100; }
				else
				{ $ls_apprate_corp = 0; }
			
			
				
			?>
			<!-- End Formula AppRate-->
			<tr>
				<td>APP RATE</td>
				<td><?php echo round($ls_apprate_cc,1); ?> %</td>
				<td><?php echo round($ls_apprate_edc,1); ?> %</td>
				<td><?php echo round($ls_apprate_sc,1); ?> %</td>
				<td><?php echo round($ls_apprate_pl,1); ?> %</td>
				<td><?php echo round($ls_apprate_corp,1); ?> %</td>
			</tr>
			<!--Formula BookRate | (app / inc) / 100 -->
			<?php
				if($last_inc_cc->incoming_cc > 0)
				{ $ls_bookrate_cc = ($last_app_cc->app_cc / $last_inc_cc->incoming_cc) * 100; }
				else
				{ $ls_bookrate_cc = 0 ; }

				if($last_inc_edc->incoming_edc > 0)
				{ $ls_bookrate_edc = ($last_app_edc->app_edc / $last_inc_edc->incoming_edc) * 100; }
				else
				{ $ls_bookrate_edc = 0 ; }
			
				if($last_inc_sc->incoming_sc > 0)
				{ $ls_bookrate_sc = ($last_app_sc->app_sc / $last_inc_sc->incoming_sc) * 100; }
				else
				{ $ls_bookrate_sc = 0 ; }
			
				if($last_inc_pl->incoming_pl > 0)
				{ $ls_bookrate_pl = ($last_app_pl->app_pl / $last_inc_pl->incoming_pl) * 100; }
				else
				{ $ls_bookrate_pl = 0 ; }
			
				if($last_inc_corp->incoming_corp > 0)
				{ $ls_bookrate_corp = ($last_app_corp->app_corp / $last_inc_corp->incoming_corp) * 100; }
				else
				{ $ls_bookrate_corp = 0 ; }
			?>
			<!--End Formula BookRate-->
			<tr>
				<td>BOOK RATE</td>
				<td><?php echo round($ls_bookrate_cc,1); ?> %</td>
				<td><?php echo round($ls_bookrate_edc,1); ?> %</td>
				<td><?php echo round($ls_bookrate_sc,1); ?> %</td>
				<td><?php echo round($ls_bookrate_pl,1); ?> %</td>
				<td><?php echo round($ls_bookrate_corp,1); ?> %</td>
			</tr>
			<tr>
				<td>RUN RATE INC</td>
				<td><?php echo $last_inc_cc->incoming_cc; ?></td>
				<td><?php echo $last_inc_edc->incoming_edc; ?></td>
				<td><?php echo $last_inc_sc->incoming_sc; ?></td>
				<td><?php echo $last_inc_pl->incoming_pl; ?></td>
				<td><?php echo $last_inc_corp->incoming_corp; ?></td>
			</tr>
			<tr>
				<td>RUN RATE APP</td>
				<td><?php echo $last_app_cc->app_cc; ?></td>
				<td><?php echo $last_app_edc->app_edc; ?></td>
				<td><?php echo $last_app_sc->app_sc; ?></td>
				<td><?php echo $last_app_pl->app_pl; ?></td>
				<td><?php echo $last_app_corp->app_corp; ?></td>
			</tr>
		</table>
	</div>
	<div class="box-footer clearfix"></div>
</div>


<!-- -2 month -->
<?php
// Last month Setoran & Incoming
$tw_inc_cc = $two_sql_cc->row();
$tw_inc_edc = $two_sql_edc->row();
$tw_inc_sc = $two_sql_sc->row();
$tw_inc_pl = $two_sql_pl->row();
$tw_inc_corp = $two_sql_corp->row();

// Last month Approal, decline, reject

$tw_app_cc = $two_app_cc->row();
$tw_app_edc = $two_app_edc->row();
$tw_app_sc = $two_app_sc->row();
$tw_app_pl = $two_app_pl->row();
$tw_app_corp = $two_app_corp->row();

?>
<!-- End -2 month -->
<div class="box box-primary collapsed-box">
	<div class="box-header with-border">
		<h4>My Performance</h4><br>
		Periode : <?php echo date('M-Y', strtotime('-2 month')); ?>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
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
				<td><?php echo $tw_inc_cc->setoran_cc; ?></td>
				<td><?php echo $tw_inc_edc->setoran_edc; ?></td>
				<td><?php echo $tw_inc_sc->setoran_sc; ?></td>
				<td><?php echo $tw_inc_pl->setoran_pl; ?></td>
				<td><?php echo $tw_inc_corp->setoran_corp; ?></td>
			</tr>
			<tr>
				<td>INCOMING</td>
				<td><?php echo $tw_inc_cc->incoming_cc; ?></td>
				<td><?php echo $tw_inc_edc->incoming_edc; ?></td>
				<td><?php echo $tw_inc_sc->incoming_sc; ?></td>
				<td><?php echo $tw_inc_pl->incoming_pl; ?></td>
				<td><?php echo $tw_inc_corp->incoming_corp; ?></td>
			</tr>
			<tr>
				<td>APPROVED</td>
				<td><?php echo $tw_app_cc->app_cc; ?></td>
				<td><?php echo $tw_app_edc->app_edc; ?></td>
				<td><?php echo $tw_app_sc->app_sc; ?></td>
				<td><?php echo $tw_app_pl->app_pl; ?></td>
				<td><?php echo $tw_app_corp->app_corp; ?></td>
			</tr>
			<tr>
				<td>CANCEL</td>
				<td><?php echo $tw_app_cc->cancel_cc; ?></td>
				<td><?php echo $tw_app_edc->cancel_edc; ?></td>
				<td><?php echo $tw_app_sc->cancel_sc; ?></td>
				<td><?php echo $tw_app_pl->cancel_pl; ?></td>
				<td><?php echo $tw_app_corp->cancel_corp; ?></td>
			</tr>
			<tr>
				<td>DECLINE / REJECT</td>
				<td><?php echo $tw_app_cc->reject_cc; ?></td>
				<td><?php echo $tw_app_edc->reject_edc; ?></td>
				<td><?php echo $tw_app_sc->reject_sc; ?></td>
				<td><?php echo $tw_app_pl->reject_pl; ?></td>
				<td><?php echo $tw_app_corp->reject_corp; ?></td>
			</tr>
			<!-- Formula AppRate | App / (app + reject) / 100 -->
			<?php
				
				if($tw_app_cc->app_cc > 0)
				{ $tw_apprate_cc = ($tw_app_cc->app_cc / ($tw_app_cc->app_cc + $tw_app_cc->reject_cc)) * 100; }
				else
				{ $tw_apprate_cc = 0; }
				
				if($tw_app_edc->app_edc > 0)
				{ $tw_apprate_edc = ($tw_app_edc->app_edc / ($tw_app_edc->app_edc + $tw_app_edc->reject_edc)) * 100; }
				else
				{ $tw_apprate_edc = 0; }
			
				if($tw_app_sc->app_sc > 0)
				{ $tw_apprate_sc = ($tw_app_sc->app_sc / ($tw_app_sc->app_sc + $tw_app_sc->reject_sc)) * 100; }
				else
				{ $tw_apprate_sc = 0; }
				
				if($tw_app_pl->app_pl > 0)
				{ $tw_apprate_pl = ($tw_app_pl->app_pl / ($tw_app_pl->app_pl + $tw_app_pl->reject_pl)) * 100; }
				else
				{ $tw_apprate_pl = 0; }
			
				if($tw_app_corp->app_corp > 0)
				{ $tw_apprate_corp = ($tw_app_corp->app_corp / ($tw_app_corp->app_corp + $tw_app_corp->reject_corp)) * 100; }
				else
				{ $tw_apprate_corp = 0; }
			
			
				
			?>
			<!-- End Formula AppRate-->
			<tr>
				<td>APP RATE</td>
				<td><?php echo round($tw_apprate_cc,1); ?> %</td>
				<td><?php echo round($tw_apprate_edc,1); ?> %</td>
				<td><?php echo round($tw_apprate_sc,1); ?> %</td>
				<td><?php echo round($tw_apprate_pl,1); ?> %</td>
				<td><?php echo round($tw_apprate_corp,1); ?> %</td>
			</tr>
			<!--Formula BookRate | (app / inc) / 100 -->
			<?php
				if($tw_inc_cc->incoming_cc > 0)
				{ $tw_bookrate_cc = ($tw_app_cc->app_cc / $tw_inc_cc->incoming_cc) * 100; }
				else
				{ $tw_bookrate_cc = 0 ; }

				if($tw_inc_edc->incoming_edc > 0)
				{ $tw_bookrate_edc = ($tw_app_edc->app_edc / $tw_inc_edc->incoming_edc) * 100; }
				else
				{ $tw_bookrate_edc = 0 ; }
			
				if($tw_inc_sc->incoming_sc > 0)
				{ $tw_bookrate_sc = ($tw_app_sc->app_sc / $tw_inc_sc->incoming_sc) * 100; }
				else
				{ $tw_bookrate_sc = 0 ; }
			
				if($tw_inc_pl->incoming_pl > 0)
				{ $tw_bookrate_pl = ($tw_app_pl->app_pl / $tw_inc_pl->incoming_pl) * 100; }
				else
				{ $tw_bookrate_pl = 0 ; }
			
				if($tw_inc_corp->incoming_corp > 0)
				{ $tw_bookrate_corp = ($tw_app_corp->app_corp / $tw_inc_corp->incoming_corp) * 100; }
				else
				{ $tw_bookrate_corp = 0 ; }
			?>
			<!--End Formula BookRate-->
			<tr>
				<td>BOOK RATE</td>
				<td><?php echo round($tw_bookrate_cc,1); ?> %</td>
				<td><?php echo round($tw_bookrate_edc,1); ?> %</td>
				<td><?php echo round($tw_bookrate_sc,1); ?> %</td>
				<td><?php echo round($tw_bookrate_pl,1); ?> %</td>
				<td><?php echo round($tw_bookrate_corp,1); ?> %</td>
			</tr>
			<tr>
				<td>RUN RATE INC</td>
				<td><?php echo $tw_inc_cc->incoming_cc; ?></td>
				<td><?php echo $tw_inc_edc->incoming_edc; ?></td>
				<td><?php echo $tw_inc_sc->incoming_sc; ?></td>
				<td><?php echo $tw_inc_pl->incoming_pl; ?></td>
				<td><?php echo $tw_inc_corp->incoming_corp; ?></td>
			</tr>
			<tr>
				<td>RUN RATE APP</td>
				<td><?php echo $tw_app_cc->app_cc; ?></td>
				<td><?php echo $tw_app_edc->app_edc; ?></td>
				<td><?php echo $tw_app_sc->app_sc; ?></td>
				<td><?php echo $tw_app_pl->app_pl; ?></td>
				<td><?php echo $tw_app_corp->app_corp; ?></td>
			</tr>
		</table>
	</div>
	<div class="box-footer clearfix"></div>
</div>