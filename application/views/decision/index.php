<?php
    $month = date('M');
    $year = date('Y');
    $periode = $month." / ".$year;
	
	if($this->input->post('date') == "")
	{
		$tgl = date('Y-m');
		$periode = $periode;
	}
	else
	{
		$tgl = $this->input->post('date');
		$periode = date('M / Y', strtotime($this->input->post('date')));
	}
	
	//$rowCount = $counter->row();
	$br_cc = $breakdown_cc->row();
	$br_edc = $breakdown_edc->row();
	$br_sc = $breakdown_sc->row();
	$br_pl = $breakdown_pl->row();
	$br_corp = $breakdown_corp->row();
?>
<div class="alert alert-success alert-dismissible">
	<h4><i class="icon fa fa-info"></i> Periode : <?php echo $periode; ?> </h4>
</div>
<a href="" class="btn btn-success">Resfresh <i class="fa fa-refresh"></i></a>
<a data-toggle="modal" data-target="#myModal" class="btn btn-success">Filter <i class="fa fa-filter"></i></a>
<br>
<br>
<div class="box box-success collapsed-box">
	<div class="box-header with-border">
		<h4>Data Decision CC</h4>
		<span class="pull-right badge bg-yellow"><?php echo $br_cc->count_app_cc; ?></span>
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
			    	<a href="javascript:void(0);" onclick="detail_cc('APPROVE', '<?php echo $tgl; ?>')" href="<?php echo base_url() ?>decision/det_breakdown_cc/APPROVE/<?php echo $tgl; ?>">APPROVE<span class="pull-right badge bg-green"><?php echo $br_cc->approve_cc; ?></span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_cc('IN_PROSSES', '<?php echo $tgl; ?>')">IN PROSSES<span class="pull-right badge bg-green">0</span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_cc('CANCEL', '<?php echo $tgl; ?>')">CANCEL<span class="pull-right badge bg-green"><?php echo $br_cc->cancel_cc; ?></span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_cc('DECLINE', '<?php echo $tgl; ?>')">DECLINE<span class="pull-right badge bg-green"><?php echo $br_cc->decline_cc; ?></span></a>
			    </li>
			  </ul>
			</div>
		</div>
	</div>
	<div class="box-footer clearfix"></div>
</div>
<div class="box box-success collapsed-box">
	<div class="box-header with-border">
		<h4>Data Decision EDC</h4>
		<span class="pull-right badge bg-yellow"><?php echo $br_edc->counter_edc; ?></span>
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
			    	<a href="javascript:void(0);" onclick="detail_edc('APPROVE', '<?php echo $tgl; ?>')">APPROVE NTB<span class="pull-right badge bg-green"><?php echo $br_edc->approve_edc; ?></span></a>
			    </li>
				<li>
			    	<a href="javascript:void(0);" onclick="detail_edc('ADDON', '<?php echo $tgl; ?>')">APPROVE ADDON<span class="pull-right badge bg-green"><?php echo $br_edc->approve_addon; ?></span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_edc('CANCEL', '<?php echo $tgl; ?>')">CANCEL<span class="pull-right badge bg-green"><?php echo $br_edc->cancel_edc; ?></span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_edc('DECLINE', '<?php echo $tgl; ?>')">DECLINE<span class="pull-right badge bg-green"><?php echo $br_edc->decline_edc; ?></span></a>
			    </li>
			  </ul>
			</div>
		</div>
	</div>
	<div class="box-footer clearfix"></div>
</div>
<div class="box box-success collapsed-box">
	<div class="box-header with-border">
		<h4>Data Decision SC</h4>
		<span class="pull-right badge bg-yellow"><?php echo $br_sc->counter_sc; ?></span>
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
			    	<a href="javascript:void(0);" onclick="detail_sc('APPROVED', '<?php echo $tgl; ?>')">APPROVE<span class="pull-right badge bg-green"><?php echo $br_sc->approve_sc; ?></span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_sc('IN_PROSSES', '<?php echo $tgl; ?>')">IN PROSSES<span class="pull-right badge bg-green">0</span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_sc('CANCEL', '<?php echo $tgl; ?>')">CANCEL<span class="pull-right badge bg-green"><?php echo $br_sc->cancel_sc; ?></span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_sc('DECLINED', '<?php echo $tgl; ?>')">DECLINE<span class="pull-right badge bg-green"><?php echo $br_sc->decline_sc; ?></span></a>
			    </li>
			  </ul>
			</div>
		</div>
	</div>
	<div class="box-footer clearfix"></div>
</div>
<div class="box box-success collapsed-box">
	<div class="box-header with-border">
		<h4>Data Decision PL</h4>
		<span class="pull-right badge bg-yellow"><?php echo $br_pl->counter_pl; ?></span>
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
			    	<a href="javascript:void(0);" onclick="detail_pl('APPROVED', '<?php echo $tgl; ?>')">APPROVE<span class="pull-right badge bg-green"><?php echo $br_pl->approve_pl; ?></span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_pl('IN_PROSSES', '<?php echo $tgl; ?>')">IN PROSSES<span class="pull-right badge bg-green">0</span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_pl('CANCEL', '<?php echo $tgl; ?>')">CANCEL<span class="pull-right badge bg-green"><?php echo $br_pl->cancel_pl; ?></span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_pl('DECLINED', '<?php echo $tgl; ?>')">DECLINE<span class="pull-right badge bg-green"><?php echo $br_pl->decline_pl; ?></span></a>
			    </li>
			  </ul>
			</div>
		</div>
	</div>
	<div class="box-footer clearfix"></div>
</div>
<div class="box box-success collapsed-box">
	<div class="box-header with-border">
		<h4>Data Decision CORP</h4>
		<span class="pull-right badge bg-yellow"><?php echo $br_corp->counter_corp; ?></span>
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
			    	<a href="javascript:void(0);" onclick="detail_corp('APPROVED', '<?php echo $tgl; ?>')">APPROVE<span class="pull-right badge bg-green"><?php echo $br_corp->approve_corp; ?></span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_corp('CANCEL', '<?php echo $tgl; ?>')">CANCEL<span class="pull-right badge bg-green"><?php echo $br_corp->cancel_corp; ?></span></a>
			    </li>
			    <li>
			    	<a href="javascript:void(0);" onclick="detail_corp('DECLINED', '<?php echo $tgl; ?>')">DECLINE<span class="pull-right badge bg-green"><?php echo $br_corp->decline_corp; ?></span></a>
			    </li>
			  </ul>
			</div>
		</div>
	</div>
	<div class="box-footer clearfix"></div>
</div>

<script>
	function detail_corp(status, tanggal)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>decision/det_breakdown_corp/'+ status + '/'+ tanggal,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}

	function detail_pl(status, tanggal)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>decision/det_breakdown_pl/'+ status + '/'+ tanggal,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}

	function detail_sc(status, tanggal)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>decision/det_breakdown_sc/'+ status + '/'+ tanggal,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}

	function detail_edc(status, tanggal)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>decision/det_breakdown_edc/'+ status + '/'+ tanggal,
            type:'POST',
            data:$('#frmsave').serialize(),
            success:function(data){ 
                $("#pop").html('');  
                $("#pop").append(data);  
            }  
        });
	}

	function detail_cc(status, tanggal)
	{
		$('#modalDetail').modal('show');
		$.ajax({
            url:'<?php echo site_url(); ?>decision/det_breakdown_cc/'+ status + '/'+ tanggal,
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
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> View Detail</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<div id="pop"></div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Filter</h4>
      </div>
      <div class="modal-body">
	  <form method="post">
        <div class="form-group">		 
			<div class="input-group">
				<label>Date </label>
			</div>	
			<div class="input-group">
				<label class="input-group-btn" for="dt_startDate">
					<span class="btn btn-default">
						<span class="fa fa-calendar"></span>
					</span>
				</label>
				<input type="text" name="date" id="dt_startDate" class="form-control date-input" value="<?php echo $this->input->post('start_date'); ?>" placeholder="Y-m" autocomplete="off"/>
			</div>
		</div>
	  
      </div>
      <div class="modal-footer">
		<input type="submit" value="Go" name="submit" class="btn btn-primary" />
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
	 </form>
      </div>
    </div>
  </div>
</div>