<?php
header("Content-type: application/ms-excel");
header("Content-Disposition: attachment; filename=schedule_training.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php
echo "<b>PT. DIKA</b>";
echo "<br>";
echo "DATA SCHEDULE TRAINING";
echo "<br><br><br>";
?>
<table border='1' width="70%">
<tr>
<td align="center"><b>NO</b></td>
<td align="center"><b>Area</b></td>
<td align="center"><b>Lokasi</b></td>
<td align="center"><b>Tanggal Training</b></td>
<td align="center"><b>Waktu Training</b></td>
<td align="center"><b>Quota</b></td>
<td align="center"><b>Available Seat</b></td>
</tr>
<?php
foreach($query as $item) {	
?>
<tr>
<td align="center"><?php echo ++$id; ?></td>
<td><?php echo $item['area']; ?></td>
<td><?php echo $item['location']; ?></td>
<td><?php echo $item['available_date']; ?></td>
<td><?php echo $item['time']; ?></td>
<td><?php echo $item['quota']; ?></td>
<td><?php echo $item['available_seat']; ?></td>
</tr>
<?php } ?>
</table>