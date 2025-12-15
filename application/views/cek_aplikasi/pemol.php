<div class="box box-primary">
    <div class="box-header with-border">
        <h4>Form Pencarian<small></small></h4>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
	<div class="col-md-6">
	    <form action="" method="get" class="form-horizontal">
            <div class="box-body">
			    <div class="form-group">
					<label>Silahkan input nomor rekening di kolom pencarian di bawah :</label>
					<div class="input-group input-group">
						<input type="text" name="key" class="form-control" placeholder="Search" value="<?php echo $key; ?>">
							<span class="input-group-btn">
							  <input type="submit" value="Cari!" class="btn btn-info btn-flat">
							</span>
					</div>
				</div>
		    </div>
		</form>
    </div>
    <div class="box-footer clearfix"></div>
</div>

<?php if($key != ''){ ?>
    <div class="box box-primary">
		<div class="box-header with-border">
			<h4>Data Incoming Pemol<small></small></h4>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="">
				<table class="table table-hover" style="font-size:10px;">
					<thead>
						<th>Nomor Rekening</th>
						<th>Sales Code</th>
						<th>Input Date</th>
					</thead>
					<tbody>
					<?php
						foreach($incoming as $row)
						{
					?>
						<tr>
							<td>
							    <b><?php $row->Account_Number; ?></b><br>
							</td>
							<td><?php echo $row->Sales_Code; ?></td>
							<td><?php echo $row->Created_Date; ?></td>
						</tr>
					<?php
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="box-footer clearfix"></div>
	</div>
	
	<div class="box box-primary">
		<div class="box-header with-border">
			<h4>Data Approval Pemol<small></small></h4>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="">
				<table class="table table-hover" style="font-size:10px;">
					<thead>
						<th>Nomor Rekening</th>
						<th>Sales Code</th>
						<th>Status</th>
					</thead>
					<tbody>
					<?php
						foreach($decision->result() as $row)
						{
					?>
						<tr>
							<td>
							    <b><?php $row->Nomor_Rekening; ?></b><br>
								<small>Date OA : <?php echo $row->Tgl_Open_Account; ?></small>
							</td>
							<td><?php echo $row->Sales_Code; ?></td>
							<td><?php echo $row->Status; ?></td>
						</tr>
					<?php
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="box-footer clearfix"></div>
	</div>
<?php } ?>