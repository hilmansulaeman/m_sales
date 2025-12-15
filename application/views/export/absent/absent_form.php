<?php
header("Content-type: application/ms-excel");
header("Content-Disposition: attachment; filename=absent_form.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
<tr>
<td colspan="8" align="center"><b>DATA HADIR PESERTA</b></td>
</tr>
<tr>
<td colspan="8" align="center"><b>TRAINING KASIR MERCHANT</b></td>
</tr>
<tr>
<td colspan="8" align="center"><b><?php echo $rows->area; ?>, <?php echo date('d M Y', strtotime ($rows->available_date)); ?></b></td>
</tr>
</table>
<br />

<table class="table table-striped">
<tbody>
<tr>
    <td><label>Time</label></td>  
    <td></td>
    <td>: <?php echo $rows->training_day.' / '.date('d M Y', strtotime ($rows->available_date)); ?></td>
</tr>
<tr>
    <td><label>City</label></td>
    <td></td>
    <td>: <?php echo $rows->area; ?></td>
</tr>
<tr>
    <td><label>Training Venue</label></td>
    <td></td>
    <td>: <?php echo $rows->location; ?></td>
</tr>
<tr>
    <td><label>Trainer Information</label></td>
    <td></td>
    <td>: </td>
</tr>
<tr>
    <td><label>PIC</label></td>
    <td></td>
    <td>: </td>
</tr>
</tbody>
</table>

<br /><br />

<table border='1' width="100%">
<tr>
<td align="center"><b>NO</b></td>
<td align="center"><b>NAMA LENGKAP PESERTA</b></td>
<td align="center"><b>FUNCTION (JABATAN)</b></td>
<td align="center"><b>NO TLP/HP</b></td>
<td align="center"><b>MID</b></td>
<td align="center"><b>NAMA PERUSAHAAN</b></td>
<td align="center"><b>ALAMAT EMAIL</b></td>
<td align="center"><b>TTD</b></td>
</tr>
<?php
foreach($query as $item) {	
?>
<tr>
<td align="center"><?php echo ++$id; ?></td>
<td><?php echo $item['nama_kasir']; ?></td>
<td></td>
<td>'<?php echo $item['tlp_kasir']; ?></td>
<td></td>
<td><?php echo $item['nama_merchant']; ?></td>
<td><?php echo $item['email']; ?></td>
<td></td>
</tr>
<?php } ?>
</table>