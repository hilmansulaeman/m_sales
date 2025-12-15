<table class="table table-hover" id="example2" style="font-size:10px !important;">
	<thead>
		<th>Name</th>
		<th>Actions</th>
	</thead>
	<tbody>
	<?php
	
	foreach($query->result() as $row)
	{
		$spv_code = $row->DSR_Code;
	?>
		<tr>
			<td><i class="fa fa-caret-right"></i> <b>SPV</b> <?php echo $row->Name; ?></td>
			<td>
				<a href="javascript:void(0)" onclick="return pilihSalesSpv('<?php echo $spv_code ?>', 'SPV')" class="btn btn-primary btn-xs">Pilih <i class="fa fa-chevron-down"></i></a>
			</td>
			<td>
		
			<?php
				$sql_dsr = $this->db->query("SELECT DSR_Code as ds_code, Name as namaDs FROM `internal`.`data_sales_structure` WHERE SPV_Code='$spv_code' AND Position IN('DSR', 'SPG', 'SPB') AND Status='ACTIVE' ORDER BY namaDs ASC");
				foreach($sql_dsr->result() as $rowDsr)
				{
					$dsr_code = $rowDsr->ds_code;
				?>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-chevron-right"></i> <b>DSR</b> <?php echo $rowDsr->namaDs; ?></td>
					<td>
						<a href="javascript:void(0)" onclick="return pilihSalesDs('<?php echo $dsr_code; ?>', 'DSR')" class="btn btn-primary btn-xs">Pilih <i class="fa fa-chevron-down"></i></a>
					</td>
				</tr>
				<?php
				}
				?>
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