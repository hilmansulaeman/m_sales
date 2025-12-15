<?php if (! defined('BASEPATH')) exit('No direct script access allowed');?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
function searchFilter(page_num) {
    page_num = page_num?page_num:0;
    var keywords = $('#keywords').val();
    var sortBy = $('#sortBy').val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>kode_pos/ajaxPaginationData/'+page_num,
        data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy,
        beforeSend: function () {
            $('.loading').show();
        },
        success: function (html) {
            $('#listKota').html(html);
            $('.loading').fadeOut("slow");
        }
    });
}
</script>
<div class="box box-primary">
	<div class="box-header with-border">
		<h4>Kode Pos</h4>
        <div class="box-tools pull-right">
        </div>
	</div>
	<div class="box-body">
		<div class="col-lg-3 col-md-3 col-xs-3 pull-right">
			<input type="text" id="keywords" class="form-control" placeholder="Search" onKeyUp="searchFilter()"/>
		</div>
		<br>
		<br>
		<div id="application_list">
			<table class="table table-hover" width="100%" border="0" cellpadding="0" cellspacing="0" id="product-table">
				<thead>
					<tr>
						<th>Provinsi</th>
						<th>Kota</th>
						<th>Kecamatan</th>
						<th>Kelurahan</th>
						<th>Kode Pos</th>
					</tr>
				</thead>
				<tbody class="post-list" id="listKota">
					<?php $i = 0;
						if(!empty($query)): foreach($query as $rows):
					?>
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
				</tbody>
			</table>
			 <ul class="pagination pull-right">
			  <?php echo $this->ajax_pagination->create_links(); ?>
			</ul> 
		</div>
	</div>
</div>