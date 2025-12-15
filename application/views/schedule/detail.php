<?php if (! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $rows = $request_data->row(); ?>

<?php
$tgl_training = $rows->available_date;
$tgl_training_ = explode('-',$tgl_training);
$day = $tgl_training_[2];
function DateSelector($inName, $class=NULL, $useDate=0) 
{ 
	/* create array so we can name months */ 
	$monthName = array(01=> "Januari", "Februari", "Maret", 
		"April", "Mei", "Juni", "Juli", "Agustus", 
		"September", "Oktober", "November", "Desember"); 

	/* if date invalid or not supplied, use current time */ 
	if($useDate == 0) 
	{ 
		$useDate = time(); 
	} 
	
	/* make day selector */
	echo "<table><tr><td><select id=day_".$inName." name=Date_Of_".$inName." class=".$class.">\n"; 
	echo "<option value=\"\">Tgl";
	for($currentDay=1; $currentDay <= 31; $currentDay++) 
	{
		$length = strlen($currentDay);
		if ($length == 1)
		$day = "0".$currentDay;
		else
		$day = $currentDay;
	
		echo "<option value=\"$day\"";  
		echo ">$day\n"; 
	} 
	echo "</select></td>";

	/* make month selector */ 
	echo "<td><select id=month_".$inName." name=Month_Of_".$inName." class=".$class.">\n"; 
	echo "<option value=\"\">Bln";
	for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) 
	{ 
		$lengMonth = strlen($currentMonth);
		if ($lengMonth == 1)
		$month = "0".$currentMonth;
		else
		$month = $currentMonth;
		
		echo "<option value=\"$month\"";  
		echo ">$monthName[$currentMonth]\n";
	} 
	echo "</select></td>"; 

	/* make year selector */ 
	echo "<td><select id=year_".$inName." name=Year_Of_".$inName." class=".$class.">\n"; 
	echo "<option value=\"\">Thn";
	$startYear = date( "Y", $useDate); 
	for($currentYear = $startYear - 80; $currentYear <= $startYear;$currentYear++) 
	{ 
		echo "<option value=\"$currentYear\"";  
		echo ">$currentYear\n"; 
	} 
	echo "</select></td></tr></table>"; 

} 
?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Schedule Training</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
		<?php echo form_open('schedule/update_detail/'.$rows->id);?>
        <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Detail Schedule
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
									<div class="form-group">
									<input type="hidden" name="idDet" value="<?php echo $rows->id; ?>" class="form-control"/>
                                        <?php
											echo form_label('Area');
											foreach($levels->result() as $row)
											{
												$array_level[$row->Area] = $row->Area;
											}
											echo form_dropdown('area',$array_level,$rows->area,'class="form-control"'); 
										?>
                                    </div>
									<div class="form-group">
										<label>Lokasi</label>
										<input type="text" name="location" placeholder="Location" value="<?php echo $rows->location; ?>" class="form-control"/>
									</div>
									<div class="form-group" class="col-md-3">
										<label>Tanggal training</label>
										<input type="text" name="available_date" placeholder="click to show datepicker" id="calendar" class="form-control" value="<?php echo $rows->available_date; ?>">
									</div>
									<div class="form-group">
										<label>Waktu Training</label>
										<select class="form-control" id="sel1" name="time">
										<?php $time = $rows->time; $choose1=""; $choose2=""; $choose3=""; $choose4=""; $choose5=""; if($time == "09.00 - 11.00") { $choose1="selected";}elseif($time== "10.00 - 12.00"){ $choose2="selected"; }elseif($time=="13.00 - 15.00"){ $choose3="selected"; }elseif($time=="14.00 - 16.00"){ $choose4="selected"; }elseif($time=="16.00 - 18.00"){ $choose5=="selected";} ?>
											<option value="09.00 - 11.00" <?php echo $choose1 ?>>09.00 - 11.00</option>
											<option value="10.00 - 12.00" <?php echo $choose2 ?>>10.00 - 12.00</option>
											<option value="13.00 - 15.00" <?php echo $choose3 ?>>13.00 - 15.00</option>
											<option value="14.00 - 16.00" <?php echo $choose4 ?>>14.00 - 16.00</option>
											<option value="16.00 - 18.00" <?php echo $choose5 ?>>16.00 - 18.00</option>
										 </select>
									</div>
									<div class="form-group">
										<label>Quota</label>
										<input type="text" name="quota" placeholder="Quota" value="<?php echo $rows->quota; ?>" class="form-control"/>
									</div>
                                </div>
								<div class="panel-footer">
									<input type="submit" value="Save" class="btn btn-primary" />
								</div>
								<!--<a href="<?php echo site_url(); ?>schedule/"><button class="btn btn-primary">Back</button></a>-->
								<?php echo form_close();?>
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
                
            </div>
            <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">
	// When the document is ready
	$(document).ready(function () {
		
		$('#calendar').datepicker({
			format: "yyyy-mm-dd"
		});  
	
	});
</script>