<?php
$row = $query->row();
?>
<table class="table table-hover">
	<tr>
		<td><b>Nama Lokasi</b></td>
		<td width="1px;"><b>:</b></td>
		<td><?php echo $row->Location_Name; ?></td>
	</tr>
	<tr>
		<td><b>Alamat Lokasi</b></td>
		<td><b>:</b></td>
		<td><?php echo $row->Location_Address; ?></td>
	</tr>
	<tr>
		<td><b>Kota</b></td>
		<td><b>:</b></td>
		<td><?php echo $row->Location_City; ?></td>
	</tr>
	<!-- <tr>
		<td><b>Quota</b></td>
		<td><b>:</b></td>
		<td><?php echo $row->Quota; ?></td>
	</tr> -->
</table>