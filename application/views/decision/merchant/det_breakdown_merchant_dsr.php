<?php
	$uri = $this->uri->segment(3);
	// $status = str_replace('_',' ', $part);
?>
<!-- Status <?php //echo $status; ?> -->
<div class="table-responsive">
	<table class="table table-hover" style="font-size:10px;" id="detailMerchant">
		<thead>
			<th>Merchant Name</th>
			<th>Owner Name</th>
			<th>Sales Code</th>
			<th>Sales Name</th>
		</thead>
		<tbody>
		<?php
			foreach ($query->result() as $data) {
		?>
			<tr>
				<td>
				    <b><?= masking_name(@$data->Merchant_Name); ?></b><br>
					<small>Decision Date : <?= @$data->Date_AMH; ?></small>
				</td>
				<td><?php echo @$data->Owner_Name; ?></td>
				<td><?php echo @$data->Sales_Code; ?></td>
				<td><?php echo @$data->Sales_Name; ?></td>
			</tr>
		<?php
			}
		?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#detailMerchant').DataTable({
			responsive: true,
			"paging" : true,
			"label" : false
	});
});
</script>