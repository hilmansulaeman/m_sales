<?php
	$rowCount = $sql_counter->row();
	$rowbrcc = $sql_br_cc->row();
	$rowbredc = $sql_br_edc->row();
	$rowbrsc = $sql_br_sc->row();
	$rowbrpl = $sql_br_pl->row();
	$rowbrcorp = $sql_br_corp->row();
	$rowbrtele = $sql_br_tele->row();
	
	$posisi = $this->session->userdata('position');
	if($posisi == "DSR")
	{
		$var_code = "sales_code";
	}elseif ($posisi == "SPV" ) {
		$var_code = "spv_code";
	}elseif ($posisi == "ASM" ) {
		$var_code = "asm_code";
	}elseif ($posisi == "RSM" ) {
		$var_code = "rsm_code";
	}elseif ($posisi == "BSH" ) {
		$var_code = "bsh_code";
	}
	
	$tgl1 = strtotime($this->uri->segment(5));
	$tgl2 = strtotime($this->uri->segment(5));
    $periode = date('d F Y', $tgl1)." - ".date('d F Y', $tgl2);
?>
<div class="alert alert-info alert-dismissible">
	<h4>
		<i class="icon fa fa-info"></i> Periode : 
		<?php echo $periode; ?>
	</h4>
</div>

<!-- <a href="<?php echo site_url('incoming') ?>" class="btn btn-primary">Refresh <i class="fa fa-refresh"></i></a>
<a data-fancybox href="<?php echo site_url('incoming/filter_incoming') ?>" class="btn btn-primary">Filter <i class="fa fa-filter"></i></a> -->
<a href="<?php echo site_url('incoming/'.$this->uri->segment(4).'/index') ?>" class="btn btn-danger">Back <i class="fa fa-arrow-left"></i></a>

<br>
<br>
<?php if ($part == 'cc') { ?>
	<div class="box box-primary collapsed-box">
		<div class="box-header with-border">
			<h4>Data Incoming CC</h4>
			<span class="pull-right badge bg-yellow"><?php echo $rowCount->cc; ?></span>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
			</button>
			</div>
		</div>
		<div class="box-body">
			<div class="box box-widget widget-user-2">
			<!-- Add the bg color to the header using any of the bg-* classes -->
				<div class="box-footer no-padding">
				<ul class="nav nav-stacked">
					<li>
						<a href="javascript:void(0);" onclick="detail_cc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'SEND_BCA');">SEND BCA <span class="pull-right badge bg-blue"><?php echo $rowbrcc->send_bca; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_cc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'RESEND_BCA');">RESEND BCA <span class="pull-right badge bg-blue"><?php echo $rowbrcc->resend_bca; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_cc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'SEND_DIKA');">SEND DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrcc->send_dika; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_cc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'DUPLICATE');">DUPLICATE DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrcc->duplicate_dika; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_cc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'DUPLICATE_BCA');">DUPLICATE BCA <span class="pull-right badge bg-blue"><?php echo $rowbrcc->duplicate_bca; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_cc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'DUPLICATE_HP');">DUPLICATE HP <span class="pull-right badge bg-blue"><?php echo $rowbrcc->duplicate_hp; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_cc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'RETURN_TO_SALES');">RETURN TO SALES <span class="pull-right badge bg-blue"><?php echo $rowbrcc->return_to_sales; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_cc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'RETURN_FROM_BCA');">RETURN FROM BCA <span class="pull-right badge bg-blue"><?php echo $rowbrcc->return_from_bca; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_cc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'PENDING_FU');">PENDING FU <span class="pull-right badge bg-blue"><?php echo $rowbrcc->pending_fu; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_cc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'PROJECT');">REJECT BY DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrcc->reject_by_dika; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_cc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'CANCEL');">CANCEL <span class="pull-right badge bg-blue"><?php echo $rowbrcc->cancel; ?></span></a>
					</li>
				</ul>
				</div>
			</div>
		</div>
		<div class="box-footer clearfix"></div>
	</div>
<?php }else if($part == 'edc_new'){ ?>
	<div class="box box-primary collapsed-box">
		<div class="box-header with-border">
			<h4>Data Incoming EDC</h4>
			<span class="pull-right badge bg-yellow"><?php echo $rowCount->edc; ?></span>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
			</button>
			</div>
		</div>
		<div class="box-body">
			<div class="box box-widget widget-user-2">
			<!-- Add the bg color to the header using any of the bg-* classes -->
				<div class="box-footer no-padding">
				<ul class="nav nav-stacked">
					<li>
						<a href="javascript:void(0);" onclick="detail_edc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'SUBMIT_TO_BCA');">SUBMIT TO BCA <span class="pull-right badge bg-blue"><?php echo $rowbredc->submit_to_bca ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_edc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'SUBMIT_TO_DIKA');">SUBMIT TO DIKA <span class="pull-right badge bg-blue"><?php echo $rowbredc->submit_to_dika ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_edc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'RETURN_TO_SALES');">RETURN TO SALES <span class="pull-right badge bg-blue"><?php echo $rowbredc->return_to_sales ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_edc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'RETURN_TO_DIKA');">RETURN TO DIKA <span class="pull-right badge bg-blue"><?php echo $rowbredc->return_to_dika ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_edc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'RETURN_FROM_BCA');">RETURN FROM BCA <span class="pull-right badge bg-blue"><?php echo $rowbredc->return_from_bca ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_edc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'RESUBMIT_TO_BCA');">RESUBMIT TO BCA <span class="pull-right badge bg-blue"><?php echo $rowbredc->resubmit_to_bca ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_edc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'REJECT');">REJECT BY DIKA <span class="pull-right badge bg-blue"><?php echo $rowbredc->reject_by_dika ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_edc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'CANCEL');">CANCEL <span class="pull-right badge bg-blue"><?php echo $rowbredc->cancel ?></span></a>
					</li>
				</ul>
				</div>
			</div>
		</div>
		<div class="box-footer clearfix"></div>
	</div>
<?php }else if($part == 'sc'){ ?>
	<div class="box box-primary collapsed-box">
		<div class="box-header with-border">
			<h4>Data Incoming SC</h4>
			<span class="pull-right badge bg-yellow"><?php echo $rowCount->sc; ?></span>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
			</button>
			</div>
		</div>
		<div class="box-body">
			<div class="box box-widget widget-user-2">
			<!-- Add the bg color to the header using any of the bg-* classes -->
				<div class="box-footer no-padding">
				<ul class="nav nav-stacked">
					<li>
						<a href="javascript:void(0);" onclick="detail_sc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'SEND_BCA')">SEND BCA <span class="pull-right badge bg-blue"><?php echo $rowbrsc->send_bca ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_sc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'SEND_DIKA')">SEND DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrsc->send_dika ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_sc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'DUPLICATE')">DUPLICATE <span class="pull-right badge bg-blue"><?php echo $rowbrsc->duplicate ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_sc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'RETURN_TO_SALES')">RETURN TO SALES <span class="pull-right badge bg-blue"><?php echo $rowbrsc->return_to_sales ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_sc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'PROJECT')">REJECT BY DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrsc->reject_by_dika ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_sc('<?php echo $var_code; ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'CANCEL')">CANCEL <span class="pull-right badge bg-blue"><?php echo $rowbrsc->cancel ?></span></a>
					</li>
				</ul>
				</div>
			</div>
		</div>
		<div class="box-footer clearfix"></div>
	</div>
<?php }else if($part == 'pl'){ ?>
	<div class="box box-primary collapsed-box">
		<div class="box-header with-border">
			<h4>Data Incoming PL</h4>
			<span class="pull-right badge bg-yellow"><?php echo $rowCount->pl; ?></span>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
			</button>
			</div>
		</div>
		<div class="box-body">
			<div class="box box-widget widget-user-2">
			<!-- Add the bg color to the header using any of the bg-* classes -->
				<div class="box-footer no-padding">
				<ul class="nav nav-stacked">
					<li>
						<a href="javascript:void(0);" onclick="detail_pl('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','SEND_BCA')">SEND BCA <span class="pull-right badge bg-blue"><?php echo $rowbrpl->send_bca; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_pl('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','SEND_DIKA')">SEND DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrpl->send_dika; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_pl('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','DUPLICATE')">DUPLICATE <span class="pull-right badge bg-blue"><?php echo $rowbrpl->duplicate; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_pl('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','RETURN_TO_SALES')">RETURN TO SALES <span class="pull-right badge bg-blue"><?php echo $rowbrpl->return_to_sales; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_pl('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','PROJECT')">REJECT BY DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrpl->project; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_pl('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','CANCEL')">CANCEL <span class="pull-right badge bg-blue"><?php echo $rowbrpl->cancel; ?></span></a>
					</li>
				</ul>
				</div>
			</div>
		</div>
		<div class="box-footer clearfix"></div>
	</div>
<?php }else if($part == 'corp'){ ?>
	<div class="box box-primary collapsed-box">
		<div class="box-header with-border">
			<h4>Data Incoming CORP</h4>
			<span class="pull-right badge bg-yellow"><?php echo $rowCount->corp; ?></span>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
			</button>
			</div>
		</div>
		<div class="box-body">
			<div class="box box-widget widget-user-2">
			<!-- Add the bg color to the header using any of the bg-* classes -->
				<div class="box-footer no-padding">
				<ul class="nav nav-stacked">
					<li>
						<a href="javascript:void(0);" onclick="detail_corp('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','SEND_BCA')">SEND BCA <span class="pull-right badge bg-blue"><?php echo $rowbrcorp->send_bca; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_corp('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','SEND_DIKA')">SEND DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrcorp->send_dika; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_corp('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','DUPLICATE')">DUPLICATE DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrcorp->duplicate_dika; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_corp('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','RETURN_TO_SALES')">RETURN TO SALES <span class="pull-right badge bg-blue"><?php echo $rowbrcorp->return_to_sales; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_corp('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','RETURN_FROM_BCA')">RETURN FROM BCA <span class="pull-right badge bg-blue"><?php echo $rowbrcorp->return_from_bca; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_corp('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','PENDING_FU')">PENDING FU <span class="pull-right badge bg-blue"><?php echo $rowbrcorp->pending_fu; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_corp('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','PROJECT')">REJECT BY DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrcorp->reject_by_dika; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_corp('<?php echo $var_code ?>','<?php echo $this->uri->segment(4) ?>','<?php echo $this->uri->segment(5) ?>','CANCEL')">CANCEL <span class="pull-right badge bg-blue"><?php echo $rowbrcorp->cancel; ?></span></a>
					</li>
				</ul>
				</div>
			</div>
		</div>
		<div class="box-footer clearfix"></div>
	</div>
<?php }else if($part == 'tele'){ ?>
	<div class="box box-primary collapsed-box">
		<div class="box-header with-border">
			<h4>Data Incoming TELE</h4>
			<span class="pull-right badge bg-yellow"><?php echo $rowCount->tele; ?></span>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
			</button>
			</div>
		</div>
		<div class="box-body">
			<div class="box box-widget widget-user-2">
			<!-- Add the bg color to the header using any of the bg-* classes -->
				<div class="box-footer no-padding">
				<ul class="nav nav-stacked">
					<li>
						<a href="javascript:void(0);" onclick="detail_tele('<?php echo $var_code ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'SEND_BCA')">SEND BCA <span class="pull-right badge bg-blue"><?php echo $rowbrtele->send_bca; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_tele('<?php echo $var_code ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'SEND_DIKA')">SEND DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrtele->send_dika; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_tele('<?php echo $var_code ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'DUPLICATE')">DUPLICATE DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrtele->duplicate_dika; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_tele('<?php echo $var_code ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'RETURN_TO_SALES')">RETURN TO SALES <span class="pull-right badge bg-blue"><?php echo $rowbrtele->return_to_sales; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_tele('<?php echo $var_code ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'RETURN_FROM_BCA')">RETURN FROM BCA <span class="pull-right badge bg-blue"><?php echo $rowbrtele->return_from_bca; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_tele('<?php echo $var_code ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'PENDING_FU')">PENDING FU <span class="pull-right badge bg-blue"><?php echo $rowbrtele->pending_fu; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_tele('<?php echo $var_code ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'PROJECT')">REJECT BY DIKA <span class="pull-right badge bg-blue"><?php echo $rowbrtele->reject_by_dika; ?></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="detail_tele('<?php echo $var_code ?>', '<?php echo $this->uri->segment(4) ?>', '<?php echo $this->uri->segment(5) ?>', 'CANCEL')">CANCEL <span class="pull-right badge bg-blue"><?php echo $rowbrtele->cancel; ?></span></a>
					</li>
				</ul>
				</div>
			</div>
		</div>
		<div class="box-footer clearfix"></div>
	</div>
<?php } ?>

<script>
	function detail_tele(field, tgl1, tgl2, status)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>filter_incoming/detail_br_tele/'+ field +'/'+ tgl1 +'/'+ tgl2 +'/'+ status,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}
	
	function detail_corp(field, tgl1, tgl2, status)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>filter_incoming/detail_br_corp/'+ field +'/'+ tgl1 +'/'+ tgl2 +'/'+ status,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}
	
	function detail_pl(field, tgl1, tgl2, status)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>filter_incoming/detail_br_pl/'+ field +'/'+ tgl1 +'/'+ tgl2 +'/'+ status,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}
	
	function detail_sc(field, tgl1, tgl2, status)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>filter_incoming/detail_br_sc/'+ field +'/'+ tgl1 +'/'+ tgl2 +'/'+ status,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}
	
	function detail_edc(field, tgl1, tgl2, status)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>filter_incoming/detail_br_edc/'+ field +'/'+ tgl1 +'/'+ tgl2 +'/'+ status,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}
		
	function detail_cc(field, tgl1, tgl2, status)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>filter_incoming/detail_br_cc/'+ field +'/'+ tgl1 +'/'+ tgl2 +'/'+ status,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}
</script>

<!-- Modal Add -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
		    <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Filter Form</h4>
            </div>
			<div class="modal-body">
				<div id="pop"></div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->