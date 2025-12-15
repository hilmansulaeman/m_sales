<?php
header("Content-type: application/ms-excel");
header("Content-Disposition: attachment; filename=training_participant.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<?php
echo "DATA PESERTA";
echo "<br>";
echo "TRAINING KASIR MERCHANT";
echo "<br><br><br>";
?>

<table border='1' width="100%">
<tr>
<td align="center"><b>NO</b></td>
<td align="center"><b>NAMA LENGKAP PESERTA</b></td>
<td align="center"><b>FUNCTION (JABATAN)</b></td>
<td align="center"><b>NO TLP/HP</b></td>
<td align="center"><b>MID</b></td>
<td align="center"><b>NAMA PERUSAHAAN</b></td>
<td align="center"><b>ALAMAT EMAIL</b></td>
<td align="center"><b>FACEBOOK</b></td>
<td align="center"><b>PIN BB</b></td>
<td align="center"><b>TANGGAL TRAINING</b></td>
<td align="center"><b>JAM TRAINING</b></td>
<td align="center"><b>LOKASI TRAINING</b></td>
<td align="center"><b>KOTA</b></td>
<td align="center"><b>STATUS KEHADIRAN</b></td>
</tr>
<?php
foreach($query as $item) {	
?>
<tr>
<td align="center"><?php echo ++$id; ?></td>
<td><?php echo $item['nama_kasir']; ?></td>
<td><?php echo $item['jabatan']; ?></td>
<td>'<?php echo $item['tlp_kasir']; ?></td>
<td>'<?php echo $item['mid']; ?></td>
<td><?php echo $item['nama_merchant']; ?></td>
<td><?php echo $item['email']; ?></td>
<td><?php echo $item['facebook']; ?></td>
<td><?php echo $item['pin_bb']; ?></td>
<td><?php echo $item['tgl_training']; ?></td>
<td><?php echo $item['waktu_training']; ?></td>
<td><?php echo $item['Location']; ?></td>
<td><?php echo $item['Area']; ?></td>
<td><?php echo $item['status_kehadiran']; ?></td>
</tr>
<?php } ?>
</table>