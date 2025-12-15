<div class="box box-primary">
    <div class="box-header with-border">
        <h4>Payroll Weekly <?php echo $this->input->post('periode'); ?><small></small></h4>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="box-body">
	<form method="post">
		<div class="form-group">		 
			<div class="input-group">
				<label>Periode :</label>
			</div>	
			<div class="input-group">
				<select name="periode" class="form-control">
					<option>-- Pilih --</option>
					<?php
						$selected = "";
						foreach($periode->result() as $row_periode)
						{
							if($this->input->post('periode') == $row_periode->periode)
							{
								$selected = "selected";
							}
							else
							{
								$selected = "";
							}
					?>
					<option <?php echo $selected; ?> value="<?php echo $row_periode->periode; ?>"><?php echo $row_periode->periode; ?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>
		<input type="submit" value="Go" class="btn btn-primary btn-smal"/>
	</form>
    </div>
    <div class="box-footer clearfix"></div>
</div>

<!--Batas-->
<?php

$row = $query->row();
$ntf1 = "none";
$ntf2 = "none";
// if($notif == "Ada")
// {
	// $ntf1 = "none";
	// $ntf2 = "block";
// }
// else
// {
	// $ntf1 = "block";
	// $ntf2 = "none";
// }


?>
<div class="alert alert-danger alert-dismissible" style="display:<?php echo $notif1; ?>;">
	<h4>
		<i class="icon fa fa-info"></i>
		Data Kosong
	</h4>
</div>

<div style="display:<?php echo $notif2; ?>">
<div class="box box-primary">
    <div class="box-header with-border">
        <h4>Data Payroll Weekly<small></small></h4>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="box-body">
		<div class="form-group">
			<div class="input-group">
				<label>Incoming :</label>
			</div>	
			<div class="input-group">
				<input type="text" name="incoming" class="form-control" value="<?php echo $row->incoming; ?>"/>
			</div>
		</div>
		<div class="form-group">
			<div class="input-group">
				<label>Noa :</label>
			</div>	
			<div class="input-group">
				<input type="text" name="incoming" class="form-control" value="<?php echo $row->noa_cc; ?>"/>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<td width="5">No.</td>
					<td>Ket</td>
					<td>Total</td>
				</tr>
				<tr>
					<td>1.</td>
					<td>Allowance</td>
					<td><?php echo number_format($row->allowance); ?></td>
				</tr>
				<tr>
					<td>2.</td>
					<td>Incentive</td>
					<td><?php echo number_format($row->incentive); ?></td>
				</tr>
				<tr>
					<td>3.</td>
					<td>Bonus</td>
					<td><?php echo number_format($row->bonus); ?></td>
				</tr>
				<tr>
					<td class="text-right">Total</td>
					<td>&nbsp;</td>
					<td>
						<?php
						
						echo number_format($total = $row->allowance + $row->incentive + $row->bonus);
						
						?>
					</td>
				</tr>
			</table>
		</div>
    </div>
    <div class="box-footer clearfix"></div>
</div>
</div>
