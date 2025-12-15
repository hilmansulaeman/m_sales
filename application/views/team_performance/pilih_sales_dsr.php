<table class="table table-hover" style="font-size:10px !important;">
	<thead>
		<th>Name</th>
		<th>Actions</th>
	</thead>
	<tbody>
		<?php
			foreach($query->result() as $rowDsr)
			{
			?>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>DSR</b> <?php echo $rowDsr->Name; ?></td>
				<td>
					<a href="javascript:void(0)" onclick="return pilihSalesDs('<?php echo $rowDsr->DSR_Code; ?>', 'DSR')" class="btn btn-primary btn-xs">Pilih <i class="fa fa-chevron-down"></i></a>
				</td>
			</tr>
			<?php
			}
			?>
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function() {
	$('#example2').DataTable({
			"paging" : false,
			"label" : false
	});
});
function pilihSalesRsm(id, posisi)
{
	parent.parent.location.href='<?php echo base_url() ?>team_performance/lihat_performance/' + id +'/' + posisi; 
}
function pilihSalesAsm(id, posisi)
{
	parent.parent.location.href='<?php echo base_url() ?>team_performance/lihat_performance/' + id +'/' + posisi; 
}
function pilihSalesSpv(id, posisi)
{
	parent.parent.location.href='<?php echo base_url() ?>team_performance/lihat_performance/' + id +'/' + posisi; 
}
function pilihSalesDs(id, posisi)
{
	parent.parent.location.href='<?php echo base_url() ?>team_performance/lihat_performance/' + id +'/' + posisi; 
}
</script>