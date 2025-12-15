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
					<label>Silahkan input Nama Merchant / Name Owner / Nomor KTP di kolom pencarian di bawah :</label>
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
			<h4>Data Incoming Merchant<small></small></h4>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="">
				<table class="table table-hover" style="font-size:10px;">
					<thead>
						<th>Merchant Name</th>
						<th>Status</th>
						<th>Sales Code</th>
					</thead>
					<tbody>
					<?php
						foreach($incoming as $row)
						{
							$regnoid = $row->RegnoId;
							$last_status = $row->Status;
							$status_submit = array('SUBMIT_TO_BCA','RESUBMIT_TO_BCA','submit_to_bca','resubmit_to_bca');
							if(in_array($last_status,$status_submit)){
								$submit_date = $row->Decision_Date;
							}
							else{
								$submit_date = '';
							}
							//submit logs
							$logs = $this->cek_aplikasi_model->get_log_merchant($regnoid, 'SUBMIT_TO_BCA');
							foreach($logs as $log){
								$submit_date = $log->updated_date.'<br>';
							}
					?>
						<tr>
							<td>
							    <b><?php masking_name($row->Merchant_Name); ?></b><br>
								<b>Owner Name : <?php echo $row->Owner_Name; ?></b><br>
								<b>Product : <?php echo $row->Product_Type; ?></b><br>
								<small>Input Date : <?php echo $row->Created_Date; ?></small><br>
								<small>Receive Date : <?php echo $row->Received_Date; ?></small><br>
								<small>Submit BCA Date : <?php echo $submit_date; ?></small>
							</td>
							<td><?php echo $row->Status; ?></td>
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
			<h4>Data Approval Merchant<small></small></h4>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="">
				<table class="table table-hover" style="font-size:10px;">
					<thead>
						<th>Merchant Name</th>
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
							    <b><?php masking_name($row->Merchant_Name); ?></b><br>
								<b><?php echo $row->Owner_Name; ?></b><br>
								<b>Product : <?php echo $row->Product; ?></b><br>
								<small>Date Result : <?php echo $row->Date_AMH; ?></small>
							</td>
							<td><?php echo $row->Status_1; ?></td>
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
<?php } ?>