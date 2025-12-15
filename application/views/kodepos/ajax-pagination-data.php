<?php $i = 0; ?>
<?php if(!empty($query)): foreach($query as $rows): ?>
<tr class="alternate-row">
    <td><?php echo $rows->provinsi; ?></td>
    <td><?php echo $rows->jenis.' '.$rows->kota; ?></td>
    <td><?php echo $rows->kecamatan; ?></td>
    <td><?php echo $rows->kelurahan; ?></td>
    <td><?php echo $rows->kode_pos; ?></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5">Data not available.</td></tr>
<?php endif; ?>
<?php echo $this->ajax_pagination->create_links(); ?>