<?php
	$row_cc = $sql_cc->row();
	$row_edc = $sql_edc->row();
	$row_sc = $sql_sc->row();
	$row_pl = $sql_pl->row();
	$row_corp = $sql_corp->row();
	
	$row_app_cc = $app_cc->row();
	$row_app_edc = $app_edc->row();
	$row_app_sc = $app_sc->row();
	$row_app_pl = $app_pl->row();
	$row_app_corp = $app_corp->row();
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
				<td><?php echo $row_app_cc->app_cc; ?></td>
				<td><?php echo $row_app_edc->app_edc; ?></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>CANCEL</td>
				<td><?php echo $row_app_cc->cancel_cc; ?></td>
				<td><?php echo $row_app_edc->cancel_edc; ?></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>DECLINE / REJECT</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>APP RATE</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>BOOK RATE</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>RUN RATE INC</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>RUN RATE APP</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</table>
	</div>
	<div class="box-footer clearfix"></div>
</div>