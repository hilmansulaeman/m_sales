<?php
/*function masking_name($name)
{
	$ex_name = explode(" ", $name);
	$jml_kata = count($ex_name);
	if($jml_kata > 1)
	{
		// > 1 kata
		$ex_name = explode(" ", $name);
		for($i = 0; $i < count($ex_name); $i++)
		{
			$jml_char = strlen($ex_name[$i]);
			if($i == 0)
			{
				echo $replace_name = $ex_name[$i]." ";
			}
			elseif($i == 1)
			{
				//$replace_name = substr($ex_name[$i], 0, 3);
				if($jml_char > 6)
				{
					$left_string = substr($ex_name[$i], 0, 2);
					$jml_string = $jml_char - 2;
					echo $replace_name = $left_string."".str_repeat("*", $jml_string)." ";
				}
				else
				{
					$jml_string = 6 - 2;
					if($jml_char > 2)
					{
						$left_string = substr($ex_name[$i], 0, 2);
						$repeater_mask = str_repeat("*", $jml_string);
						echo $replace_name = $left_string."".$repeater_mask." ";
					}
					else
					{
						echo $replace_name = $ex_name[$i]." ";
					}
				}
			}
			elseif($i >= 2)
			{
				$repeater_mask = str_repeat("*", $jml_char);
				echo $replace_name = $repeater_mask;
			}
		}
	}
	else
	{
		// 1 kata
		$jml_char = strlen($name);
		$default_count_mask = 6;
		if($jml_char > 6)
		{
			$left_string = substr($name, 0, 3);
			$jml_string = $jml_char - 3;
			$repeater_mask = str_repeat("*", $jml_string);
			echo $replace_name = $left_string."".$repeater_mask;
		}
		else
		{
			if($jml_char > 3)
			{
				$left_string = substr($name, 0, 3);
				$jml_string = $default_count_mask - 3;
				$repeater_mask = str_repeat("*", $jml_string);
				echo $replace_name = $left_string."".$repeater_mask;
			}
			else
			{
				$jml_string = 6 - $jml_char;
				echo $replace_name = $name."".str_repeat("*", $jml_string);
			}
		}
	}
}*/
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h4>Search<small></small></h4>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="box-body">
		<form method="post">
			<div class="row">
				<div class="col col-lg-4 col-xs-4">
					<select name="kategori" class="form-control">
						<option>-- Pilih --</option>
						<option value="CC">CC</option>
						<option value="EDC">EDC</option>
						<option value="SC">SC</option>
						<option value="PL">PL</option>
						<option value="CORP">CORP</option>
					</select>
				</div>
				<div class="col col-lg-4 col-xs-4">
					<input name="tcari" type="text" class="form-control" value="" placeholder="Name" />
				</div>
				<div class="col col-lg-4 col-xs-4">
					<input type="submit" value="Cari" name="submit" class="btn btn-primary btn-md"/>
				</div>
			</div>
		</form>
    </div>
    <div class="box-footer clearfix"></div>
</div>
<!--Batas-->
<?php
$kategori = $this->input->post('kategori');
$show = "none";
$show2 = "none";
if($kategori <> "")
{
	$show = "block";
	$show2 = "block";
}
?>
<div style="display:<?php echo $show; ?>">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h4>Data Incoming <?php echo $kategori = $this->input->post('kategori'); ?><small></small></h4>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="">
				<table class="table table-hover" style="font-size:10px;">
					<thead>
						<th>
							Customer Name
						</th>
						<th>
							Status
						</th>
						<th>
							Input Date
						</th>
					</thead>
					<tbody>
					<?php
						foreach($query_inc->result() as $dtIncCc)
						{
					?>
						<tr>
							<td><?php masking_name($dtIncCc->cs_name); ?></td>
							<td><?php echo $dtIncCc->status ?></td>
							<td><?php echo $dtIncCc->dates; ?></td>
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
</div>

<div style="display:<?php echo $show2; ?>">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h4>Data Approval <?php echo $kategori = $this->input->post('kategori'); ?><small></small></h4>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			</div>
		</div>
		<div class="box-body">
			<div class="">
				<table class="table table-hover" style="font-size:10px;">
					<thead>
						<th>
							Customer Name
						</th>
						<th>
							Status
						</th>
						<th>
							Date Result
						</th>
					</thead>
					<tbody>
					<?php
						foreach($query_app->result() as $valCc)
						{
					?>
						<tr>
							<td><?php masking_name($valCc->name); ?></td>
							<td><?php echo $valCc->sts; ?></td>
							<td><?php echo $valCc->tgl; ?></td>
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
</div>