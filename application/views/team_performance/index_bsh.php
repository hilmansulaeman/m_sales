<?php
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
		<h4>&nbsp;</h4>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	    </button>
        </div>
	</div>
	<div class="box-body">
		<div class="callout callout-info">
			<h4>Info!</h4>
			<p>Untuk melihat performance sales. Silahkan tekan tombol <b>Pilih Sales</b> untuk memilih Anggota Sales. Lalu tekan tombol <b>Lihat Performance</b> untuk menampilkan data performance</p>
		</div>
		<!--<a data-fancybox href="<?php echo base_url() ?>team_performance/<?php echo $param; ?>/<?php echo $this->session->userdata('sl_code'); ?>" class="btn btn-primary">Pilih Sales</a>-->
		<a href="javascript:void(0);" onclick="viewSales('<?php echo $param ?>', '<?php echo $this->session->userdata('sl_code'); ?>')" class="btn btn-warning">Pilih Sales</a>
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
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-users"></i> View Team<h4>
			</div>
			<div class="modal-body">
				<div id="pop"></div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
