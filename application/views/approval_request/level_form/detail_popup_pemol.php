<?php
	$uri = $this->uri->segment(3);
	//$status = str_replace('_',' ', $part);
	$dateBase = date('Y-m-d');
?>

<div class="table-responsive">
	<table class="table table-responsive table-bordered table-striped" id="example2">
		<thead>
			<tr>
				<th colspan="3" class="text-center" style="background-color:#3c8dbc; color:white">Performance Incoming</th>
			</tr>
			<tr>
				<?php for ($i=1; $i <= 3 ; $i++) { 
					// $bulan = date('F', strtotime("-{$i} month", strtotime($dateBase)));
					$dates = mktime(0,0,0,date("m")-$i,1,date("Y"));
					$year  = date('Y', $dates);
					$month = date('F', $dates);
				?>
						<th class="text-center"><?= "$month - $year" ?></th>
				<?php	} ?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="text-center"><?= $query1->total ?></td>
				<td class="text-center"><?= $query2->total ?></td>
				<td class="text-center"><?= $query3->total ?></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
// $(document).ready(function() {
// 	$('#example2').DataTable({
// 			responsive: true,
// 			"paging" : true,
// 			"label" : false
// 	});
// });
</script>