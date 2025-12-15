<div class="box box-primary">
	<div class="box-header with-border">
		<h4>Kode Pos</h4>
        <div class="box-tools pull-right">
        </div>
	</div>
	<div class="box-body">
		<table class="table table-hover" id="example2">
			<thead>
				<th>Provinsi</th>
				<th>Kota</th>
				<th>Kecamatan</th>
				<th>Kelurahan</th>
				<th>Kode Pos</th>
			</thead>
			<tbody>
			<?php
				foreach($results as $data)
				{
			?>
				<tr>
					<td><?php echo $data->provinsi; ?></td>
					<td><?php echo $data->kota; ?></td>
					<td><?php echo $data->kecamatan; ?></td>
					<td><?php echo $data->kelurahan; ?></td>
					<td><?php echo $data->kode_pos; ?></td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
		<p class="footer"><?php echo $links; ?></p>
	</div>
</div>