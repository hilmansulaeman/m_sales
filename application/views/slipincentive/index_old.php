<?php
$rows = $query->row();
$info = "none";
$info2 = "";
$this->input->post('periode');
if($this->input->post('periode') == "" || $this->input->post('periode') == "-- Pilih --")
{
	$info = "none";
	$info2 = "<div style='color:red'>Silahkan Pilih Periode!!</div>";
}else
{
	$info = "block";
}
?>
<div class="alert alert-info alert-dismissible">
	<h4>
		<i class="icon fa fa-info"></i>
		Silahkan pilih periode untuk menampilkan data incentive..
	</h4>
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h4>Slip Incentive<small></small></h4>
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
				<?php echo $info2; ?>
			</div>	
			<div class="input-group">
				<select name="periode" class="form-control">
					<option>-- Pilih --</option>
				<?php 
					foreach($combo->result() as $row_cmb)
					{
				?>
					<option><?php echo $row_cmb->periode; ?></option>
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
<div>
<div class="box box-primary" style="display:<?php echo $info; ?>">
    <div class="box-header with-border">
        <h4>Data Incentive <?php echo $this->input->post('periode'); ?><small></small></h4>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="box-body">
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<td><b>No</td>
					<td><b>Pendapatan</td>
					<td><b>Jumlah</td>
				</tr>
				<tr>
					<td>1.</td>
					<td>Allowance</td>
					<td><?php echo number_format($rows->Allowance); ?></td>
				</tr>
				<tr>
					<td>2.</td>
					<td>Incentive</td>
					<td><?php echo number_format($rows->Incentive); ?></td>
				</tr>
				<tr>
					<td>3.</td>
					<td>Bonus</td>
					<td><?php echo number_format($rows->Bonus); ?></td>
				</tr>
				<tr>
					<td>4.</td>
					<td>Adjusment</td>
					<td><?php echo number_format($rows->Adjustment); ?></td>
				</tr>
				<tr>
					<td>5.</td>
					<td>Tunjangan Komunikasi</td>
					<td><?php echo number_format($rows->tunjangan_komunikasi); ?></td>
				</tr>
				<tr>
					<td></td>
					<td class="text-right"><b>Total Pendapatan: </td>
					<td><b><?php echo number_format($rows->Total_Pendapatan_Kotor); ?></td>
				</tr>
			</table>
			<table class="table table-hover">
				<tr>
					<td><b>No</td>
					<td><b>Potongan</td>
					<td><b>Jumlah</td>
				</tr>
				<tr>
					<td>1.</td>
					<td>Pajak Penghasilan</td>
					<td><?php echo number_format($rows->Pajak); ?></td>
				</tr>
				<tr>
					<td>2.</td>
					<td>Potongan Seragam</td>
					<td><?php echo number_format($rows->Potongan_Seragam); ?></td>
				</tr>
				<tr>
					<td>3.</td>
					<td>Potongan Punishment</td>
					<td><?php echo number_format($rows->Potongan_Punishment); ?></td>
				</tr>
				<tr>
					<td>4.</td>
					<td>Potongan Wagely</td>
					<td><?php echo number_format($rows->Potongan_Mopro); ?></td>
				</tr>
				<tr>
					<td>5.</td>
					<td>Potongan Lain-lain</td>
					<td><?php echo number_format($rows->Potongan_Lain); ?></td>
				</tr>
				<tr>
					<td></td>
					<td class="text-right"><b>Total Potongan:</td>
					<?php
						$total_potongan = 0;
						$total_potongan = $rows->Pajak + $rows->Potongan_Seragam + $rows->Potongan_Punishment + $rows->Potongan_Mopro + $rows->Potongan_Mopro + $rows->Potongan_Lain;
					?>
					<td><b><?php echo number_format($total_potongan); ?></td>
				</tr>
				<tr>
					<td></td>
					<td class="text-right"><b>Grand Total</td>
					<td style="color:red;"><b><?php echo number_format($rows->Total_Diterima); ?></td>
				</tr>
			</table>
		</div>
    </div>
    <div class="box-footer clearfix"></div>
</div>
</div>
