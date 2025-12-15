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
					<label>Silahkan input Nama / Nomor KTP di kolom pencarian di bawah :</label>
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
			<h4>Data Incoming Smartcash<small></small></h4>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="">
				<table class="table table-hover" style="font-size:10px;">
					<thead>
						<th>Customer Name</th>
						<th>Status</th>
						<th>Sales Code</th>
					</thead>
					<tbody>
					<?php
						foreach($incoming as $row)
						{
					?>
						<tr>
							<td>
							    <b><?php masking_name($row->Customer_Name); ?></b><br>
								<small>Input Date : <?php echo $row->Created_Date2; ?></small>
							</td>
							<td><?php echo $row->Status ?></td>
							<td><?php echo $row->Sales_Code; ?></td>
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
			<h4>Data Approval Smartcash<small></small></h4>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="">
				<table class="table table-hover" style="font-size:10px;">
					<thead>
						<th>Customer Name</th>
						<th>Status</th>
						<th>Sales Code</th>
					</thead>
					<tbody>
					<?php
						foreach($decision->result() as $row)
						{
					?>
						<tr>
							<td>
							    <b><?php masking_name($row->cust_name); ?></b><br>
								<small>Date Result : <?php echo $row->date_result; ?></small>
							</td>
							<td><?php echo $row->status_1; ?></td>
							<td><?php echo $row->sales_code; ?></td>
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