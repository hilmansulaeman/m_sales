<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            &nbsp;
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Random Data
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">
					<?php echo form_open(); ?>
					<form action="#" id="form" class="form-horizontal">
					<div>
					  <!-- Nav tabs -->
					  <ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Acak Pemenang</a></li>
						<li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Histori Pemenang</a></li>
					  </ul>
					</div>
					
					<!-- Nav tabs content -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="home">
							<table class="table table-striped" id="dataTables-example1">
								<tr>
									<td align="center">
										<div class="form-group">
											<div class="col-md-7">
												 <input type="text" name="Start_date" placeholder="Click to Show Date" id="calendar1" class="form-control">
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<div class="col-md-7">
												 <input type="text" name="End_date" placeholder="Click to Show Date" id="calendar2" class="form-control">
											</div>
										</div>
									</td>
									<td width="350">
										<button type="submit" class="btn btn-primary">Acak Pemenang &nbsp;<i class="glyphicon glyphicon-random"></i></button>
									</td>
								</tr>
							</table>
							<table class="table table-striped table-bordered table-hover" id="dataTables-example">
								<thead>
									<tr>
										<th align="center">No</b></th>
										<th>Nama Peserta</th>
										<th>Nama Merchant</th>
										<th>No Telp</th>
										<th>Email</th>
										<th>Status Kehadiran</th>
										<th>Detail</th>
									</tr>
								</thead>
								<tbody>
								<?php $i = 0; ?>
								<?php foreach($query->result() as $rows){ ?>
								<tr class="odd gradeX">
									<td align="center"><?php echo ++$i; ?></td>
									<td><?php echo $rows->nama_kasir; ?><input type="hidden" name="id" value="<?php echo $rows->id; ?>"/></td>
									<td><?php echo $rows->nama_merchant; ?></td>
									<td><?php echo $rows->tlp_kasir; ?></td>
									<td><?php echo $rows->email; ?></td>
									<td align="center"><?php echo $rows->status_kehadiran; ?></td>
									<td align="center">
									<a href="<?php echo site_url()."random_data/update_flag/".$rows->id; ?>" title="Detail Data" class="btn btn-success btn-xs">Pilih Pemenang &nbsp;<span class="glyphicon glyphicon-hand-right"></span></a>
									</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
						<div role="tabpanel" class="tab-pane" id="profile">
							<table class="table table-striped table-bordered table-hover" id="dataTables-example">
								<thead>
									<tr>
										<th align="center">No</b></th>
										<th>Nama Peserta</th>
										<th>Nama Merchant</th>
										<th>No Telp</th>
										<th>Email</th>
										<th>Status Kehadiran</th>
									</tr>
								</thead>
								<tbody>
								<?php $i = 0; ?>
								<?php foreach($sql->result() as $item){ ?>
								<tr class="odd gradeX">
									<td align="center"><?php echo ++$i; ?></td>
									<td><?php echo $item->nama_kasir; ?></td>
									<td><?php echo $item->nama_merchant; ?></td>
									<td><?php echo $item->tlp_kasir; ?></td>
									<td><?php echo $item->email; ?>< /td>
									<td align="center"><?php echo $item->status_kehadiran; ?></td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
						</form>
						<?php echo form_close(); ?>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">
	// When the document is ready
	$(document).ready(function () {
		
		$('#calendar1').datepicker({
			format: "yyyy-mm-dd"
		});  
	
	});
	// When the document is ready
	$(document).ready(function () {
		
		$('#calendar2').datepicker({
			format: "yyyy-mm-dd"
		});  
	
	});
	$('#myTabs a').click(function (e) {
	  e.preventDefault()
		$('#myTabs a[href="#profile"]').tab('show') // Select tab by name
		$('#myTabs a:first').tab('show') // Select first tab
		$('#myTabs a:last').tab('show') // Select last tab
		$('#myTabs li:eq(2) a').tab('show') // Select third tab (0-indexed)
	})
</script>