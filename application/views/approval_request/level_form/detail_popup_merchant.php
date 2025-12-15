<?php
	$uri = $this->uri->segment(3);
	//$status = str_replace('_',' ', $part);
	$dateBase = date('Y-m-d');
?>

<?php
	for ($i=1; $i <= 3 ; $i++) { 
		// $bulan = date('F', strtotime("-{$i} month", strtotime($dateBase)));
		$dates = mktime(0,0,0,date("m")-$i,1,date("Y"));
		$year  = date('Y', $dates);
		$month = date('F', $dates);
?>

	<div class="box box-solid" style="background: #f2f2f2;">
		<div class="box-header">
			<i class="fa fa-th"></i>
			<h3 class="box-title"><?= "$month - $year" ?></h3>
			<div class="box-tools pull-right">
				<!-- <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
				</button> -->
				<!-- <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
				</button> -->
			</div>
		</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<div class="row">
						<div class="col-md-6">
							<h5>Total Submit</h5>
						</div>
						<div class="col-md-6">
							<span class="pull-right badge bg-yellow"><?php echo number_format($totalsSend[$i]->total); ?></span>
						</div>
					</div>
					<div class="box-body">
						<ul class="nav nav-stacked">
							<li>EDC <span class="pull-right badge bg-blue"><?= $dataPS[$i]->edc ?></span></a></li>
							<li>QRIS <span class="pull-right badge bg-blue"><?= $dataPS[$i]->qris ?></span></a></li>
							<li>EDC+QRIS <span class="pull-right badge bg-blue"><?= $dataPS[$i]->edc_qris ?></span></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- <div class="box-footer no-border">
			<div class="row">
				<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
					1
				</div>
				<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
					2
				</div>
				<div class="col-xs-4 text-center">
					3
				</div>
			</div>
		</div> -->
	</div>

<?php } ?>

<script type="text/javascript">
	// $(document).ready(function() {
	// 	$('#example2').DataTable({
	// 			responsive: true,
	// 			"paging" : true,
	// 			"label" : false
	// 	});
	// });
</script>