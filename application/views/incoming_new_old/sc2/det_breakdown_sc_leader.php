<?php
	$uri = $this->uri->segment(3);
	$status = str_replace('_',' ', $uri);
?>
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
Status <?php echo $status; ?>
<div class="table-responsive">
	<table class="table table-hover" style="font-size:10px;" id="example2">
		<thead>
			<th>Name</th>
			<th>DSR</th>
			<th>SPV</th>
			<th>Date</th>
		</thead>
		<tbody>
		<?php
			foreach ($query->result() as $value) {
		?>
			<tr>
				<td><?php masking_name($value->Customer_Name); ?></td>
				<td><?php echo $value->Name; ?></td>
				<td><?php echo $value->SPV_Name; ?></td>
				<td><?php echo $value->Created_Date; ?></td>
			</tr>
		<?php
			}
		?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#example2').DataTable({
			responsive: true,
			"paging" : false,
			"label" : false
	});
});
</script>