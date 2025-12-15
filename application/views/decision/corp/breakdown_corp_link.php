<?php
	$uri = $this->uri->segment(3);
?>
<a href="<?= site_url('decision/corp') ?>" class="btn btn-danger">Back</a>
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
						<th>Perusahaan</th>
						<th>DSR</th>
						<th>Date</th>
					</thead>
					<tbody>
					<?php
						foreach($query->result() as $row)
						{
					?>
						<tr>
							<td>
								<b><?php masking_name($row->customer_name); ?></b><br>
								<small>Decision Date: <?php echo $row->date_result; ?></small>
							</td>
							<td><?php echo $row->company_name; ?></td>
							<td><?php echo $row->sales_name; ?></td>
							<td><?php echo $row->date_result; ?></td>
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