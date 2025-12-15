<table border="1" cellspacing="1">
	<thead>
		<tr>
			<td>No</td>
			<td>DSR Name</td>
			<td>MOB</td>
			<td>JML INCOMING</td>
			<td>JML RTS</td>
			<td>%</td>
		</tr>
	</thead>
	<tbody>
	<?php
		$no = 0;
		$tanggal = date('Y-m', strtotime('-1 days'));
		foreach($query->result() as $row)
		{
			$inc = $row->inc_cc + $row->inc_edc + $row->inc_sc;
			$rts = $row->rts_cc + $row->rts_edc + $row->rts_sc;
			
			//datediff
			$date1 = $row->Join_Date;
			if($date1 == "0000-00-00")
			{
				$date1 = $row->Efektif_Date;
			}
			$date2 = date('Y-m-d');
			$timeStart = strtotime("$date1");
			$timeEnd = strtotime("$date2");
			// Menambah bulan ini + semua bulan pada tahun sebelumnya
			$diff = $timeEnd-$timeStart;
			// menghitung selisih bulan
			$numBulan = $diff / 86400 / 30;
			
			if($inc > 0 AND $rts > 0)
			{
				$persen = ($rts / $inc) * 100;
			}
			else
			{
				$persen = 0;
			}
	?>
		<tr>
			<td><?php echo ++$no; ?></td>
			<td><?php echo $row->Name; ?></td>
			<td><?php echo round($numBulan); ?></td>
			<td><?php echo $inc; ?></td>
			<td><?php echo $rts ?></td>
			<td><?php echo round($persen); ?></td>
		<tr>
		<?php
			if($row->Product == "CC")
			{
				$sql = $this->spv_model->getCustNameCc($row->DSR_Code, $tanggal);
			}elseif($row->Product == "EDC")
			{
				$sql = $this->spv_model->getCustNameEdc($row->DSR_Code, $tanggal);
			}elseif($row->Product == "SC")
			{
				$sql = $this->spv_model->getCustNameSc($row->DSR_Code, $tanggal);
			}
			$noc = 0;
			foreach ($sql->result() as $child) {
		?>
			<tr class="tr_<?php echo $row->DSR_Code; ?>" style="font-size:11px;">
				<td class="text-right"><i class="fa fa-long-arrow-right"></i> <?php echo ++$noc; ?></td>
				<td align="right"><b>Application Name</b></td>
				<td colspan="4"><?php echo $child->Apps_name; ?></td>
			</tr>
		<?php
			}
		?>
	<?php
		}
	?>
	</tbody>
</table>
<script>
	window.print();
</script>