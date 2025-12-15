<?php
	$uri = $this->uri->segment(3);
?>
<a href="<?= site_url('decision/sc') ?>" class="btn btn-danger">Back</a>
<br><br>

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-md-6">
				<h3>Status : <?php echo $status; ?></h3>
			</div>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-hover" style="font-size:10px;" id="example2">
					<thead>
						<th>Nama CH</th>
						<th>DSR</th>
						<th>Date</th>
					</thead>
					<tbody>
					<?php 
						foreach($query->result() as $value)
						{
					?>
						<tr>
							<td><?php masking_name($value->cust_name); ?></td>
							<td><?php echo $value->sales_name; ?></td>
							<td><?php echo $value->date_result; ?></td>
						</tr>
					<?php
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>