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
				<td class="text-center"><?= $query1->send ?></td>
				<td class="text-center"><?= $query2->send ?></td>
				<td class="text-center"><?= $query3->send ?></td>
			</tr>
		</tbody>
	</table>
</div>
<br>

<!-- <h4>Performance 1 bulan sebelum</h4>
<div class="table-responsive">
	<table class="table table-responsive table-bordered table-striped" id="example2">
		<thead>
			<tr>
				<th>Send</th>
				<th>Send HC</th>
				<th>Inprocess</th>
				<th>Duplicate</th>
				<th>RTS</th>
				<th>Cancel</th>
				<th>Reject</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?= $query1->send ?></td>
				<td><?= $query1->send_hc ?></td>
				<td><?= $query1->inprocess ?></td>
				<td><?= $query1->duplicate ?></td>
				<td><?= $query1->rts ?></td>
				<td><?= $query1->cancel ?></td>
				<td><?= $query1->reject ?></td>
				<td><?= $query1->total ?></td>
			</tr>
		</tbody>
	</table>
</div>
<br>

<h4>Performance 2 bulan sebelum</h4>
<div class="table-responsive">
	<table class="table table-responsive table-bordered table-striped" id="example2">
		<thead>
			<tr>
				<th>Send</th>
				<th>Send HC</th>
				<th>Inprocess</th>
				<th>Duplicate</th>
				<th>RTS</th>
				<th>Cancel</th>
				<th>Reject</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?= $query2->send ?></td>
				<td><?= $query2->send_hc ?></td>
				<td><?= $query2->inprocess ?></td>
				<td><?= $query2->duplicate ?></td>
				<td><?= $query2->rts ?></td>
				<td><?= $query2->cancel ?></td>
				<td><?= $query2->reject ?></td>
				<td><?= $query2->total ?></td>
			</tr>
		</tbody>
	</table>
</div>
<br>

<h4>Performance 3 bulan sebelum</h4>
<div class="table-responsive">
	<table class="table table-responsive table-bordered table-striped" id="example2">
		<thead>
			<tr>
				<th>Send</th>
				<th>Send HC</th>
				<th>Inprocess</th>
				<th>Duplicate</th>
				<th>RTS</th>
				<th>Cancel</th>
				<th>Reject</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?= $query3->send ?></td>
				<td><?= $query3->send_hc ?></td>
				<td><?= $query3->inprocess ?></td>
				<td><?= $query3->duplicate ?></td>
				<td><?= $query3->rts ?></td>
				<td><?= $query3->cancel ?></td>
				<td><?= $query3->reject ?></td>
				<td><?= $query3->total ?></td>
			</tr>
		</tbody>
	</table>
</div> -->

<script type="text/javascript">
// $(document).ready(function() {
// 	$('#example2').DataTable({
// 			responsive: true,
// 			"paging" : true,
// 			"label" : false
// 	});
// });
</script>