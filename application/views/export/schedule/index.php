<?php if (! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php 
    function MonthSelector($inName, $useDate=0) 
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
 
        /* make month selector */ 
        echo "<td><select name=month_".$inName." style='width:auto' class='form-control' required>\n"; 
        for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) 
        { 
            $lengMonth = strlen($currentMonth);
			if ($lengMonth == 1)
			$month = "0".$currentMonth;
			else
			$month = $currentMonth;
			
			echo "<option value=\"$month\""; 
            if(intval(date( "m", $useDate))==$month) 
            { 
                echo " selected"; 
            } 
            echo ">$monthName[$currentMonth]\n";
        } 
        echo "</select></td>"; 
 
        /* make year selector */ 
        echo "<td><select name=year_".$inName." style='width:auto' class='form-control' required>\n"; 
        $startYear = date( "Y", $useDate); 
        for($currentYear = $startYear - 1; $currentYear <= $startYear;$currentYear++) 
        { 
            echo "<option value=\"$currentYear\""; 
            if(date( "Y", $useDate)==$currentYear) 
            { 
                echo " selected"; 
            } 
            echo ">$currentYear\n"; 
        } 
        echo "</select></td>"; 
 
    } 
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Export Data</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Export Data request
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                    	<div class="col-lg-12">
                            
                            <h4>Export Data Schedule</h4>
                            <br />
                    
                            <!-- start id-form -->
							<?php echo form_open('export/schedule/monthly');?>
                            <table>
                                <tr>
                                    <td width="120"><b>Periode Training</b></td>
                                    <td width="10"><b>:</b></td>
                                    <td><?php MonthSelector( "request2"); ?></td>
                                </tr>
                            </table>
                            <br />
                            <table>
                                <tr>
                                    <td width="120">&nbsp;</td>
                                    <td width="10">&nbsp;</td>
                                    <td><input type="submit" name="submit" value=" Export " class="btn btn-primary" /></td>
                                </tr>
                            </table>
                            <?php echo form_close(); ?>
                            <!-- end id-form  -->
                                        
                    	</div>

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