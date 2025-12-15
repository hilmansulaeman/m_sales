 <?php if (! defined('BASEPATH')) exit('No direct script access allowed');
 // echo $filter;
 // echo $myid;
 $query = $this->db->query("Select *  from tbl_incoming_cc  where sales_code='$myid' and status in('DUPLICATE_DIKA','PROJECT','SEND_BCA') $filter");
 $rows = $query->row(); 
//  print_r("Select *  from tbl_incoming_cc  where sales_code='$myid' and status in('DUPLICATE_DIKA','PROJECT','SEND_BCA') $filter");
 //$this->duplicate_cc_model->data_duplicate_cc($myid, $filter) //$query_duplicate->row() 
 //print_r($cek);
 $duplcate="display:none;";
 $available="display:none;";
		 if($query->num_rows() > 0){      
				if($rows->expire == "1"){					
					$available="";
					$duplcate="display:none;";	
					
				}else{
					$available="display:none;";
					$duplcate="";					 					 
				}
				
		}else{
			if($cek == "0"){
				$duplcate="display:none;";
				$available="display:none;";		 				
			}else{
				$available="";
				$duplcate="display:none;";					 
			}			
		}
 ?>
	
 
<section class="content">
<form method="post"> 
  <div class="box box-primary">
		<div class="box-header with-border">
			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>		
			<h3 class="box-title">View Check Duplicate CC</h3>		  
		</div>
		<br />
					<!-- /.panel-heading -->					
		<div class="panel-body" >
				 <?php //echo form_open('');?>
			  	
				<?php // echo form_open_multipart('extimasi/do_upload');?>
				<div class="form-group">
					<label>Check By: </label>
					<select class="form-control" name="cek" id="cek"  >
						<option value="0">--Pilih--</option>
						<option value="1">Nama</option>
						<option value="2">KTP</option>
					</select>
				</div>	
				<div style="display:none;" id="div_nama"  class="form-group">
					<label>Nama : </label>
					<input type="text" name="txt_nama"  id="txt_nama"  class="form-control"/>
				</div>		
				<div style="display:none;" id="div_ttl" class="input-group">
					<label>Tanggal Lahir </label>
				</div>	
				<div style="display:none;" id="div_ttl2"   class="input-group">	
					<label class="input-group-btn" for="dt_tgl_lahir">
						<span class="btn btn-default">
							<span class="fa fa-calendar"></span>
						</span>
					</label>
					<input type="text" name="dt_tgl_lahir" readonly id="dt_tgl_lahir" class="form-control date-input" value="<?php echo $this->input->post('dt_tgl_lahir'); ?>"   />
				</div>
				<br />
				<div  style="display:none;" id="div_ktp" class="form-group">
					<label>No KTP : </label>
					<input type="text" name="txt_no_ktp" id="txt_no_ktp"class="form-control"/>
				</div>				
				  <div style="display:none;" id="div_btn"  class="form-group">
					<input type="submit" name="cari" value="Go" class="btn btn-primary">			
				  </div>  
			   
			 			 
         </div>     
	  </div>	  	
		 <div id="div_d" style="<?php echo $duplcate;?>" class="callout callout-danger">
                <h4><i class="icon fa fa-check"></i> Information !</h4>
					<p>Data Duplicate</p>					 
         </div>	
		 <div id="div_a" style="<?php echo $available;?>" class="callout callout-success">
                <h4><i class="icon fa fa-ban"></i> Information !</h4>					 
					<p> Data is Available</p>
              </div>
</form>		  
</section>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
<script type="text/javascript">
// function render_bsh(bsh)
  // {
    // var data = document.getElementById('bsh').value = bsh;
    // window.location.href = '<?php echo base_url(); ?>duplicate_cc/index/'+ bsh;
	
	//$("#div_rsm").show();
 // }

 </script> 
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
<script>
$(document).ready(function(){
    $('#cek').on('change', function() {
      if ( this.value == '0')
      //.....................^.......
      {
        $("#div_nama").hide();
		$("#div_ttl").hide();
		$("#div_ttl2").hide();
		$("#div_ktp").hide();
		$("#div_d").hide();
		$("#div_a").hide();
		
		var data = document.getElementById('txt_nama').value = "";
		var data = document.getElementById('dt_tgl_lahir').value = "";
		var data = document.getElementById('txt_no_ktp').value ="";
      }
      else  if ( this.value == '1')
      {
        $("#div_nama").show();
		$("#div_ttl").show();
		$("#div_ttl2").show();
		$("#div_ktp").hide();
		$("#div_btn").show();
		$("#div_d").hide();
		$("#div_a").hide();
		var data = document.getElementById('txt_nama').value = "";
		var data = document.getElementById('dt_tgl_lahir').value = "";
		var data = document.getElementById('txt_no_ktp').value ="";
		
		
      }
	   else  if ( this.value == '2')
      {
        $("#div_nama").hide();
		$("#div_ttl").hide();
		$("#div_ttl2").hide();
		$("#div_ktp").show();
		$("#div_btn").show();
		$("#div_d").hide();
		$("#div_a").hide();
		var data = document.getElementById('txt_nama').value = "";
		var data = document.getElementById('dt_tgl_lahir').value = "";
		var data = document.getElementById('txt_no_ktp').value ="";
      }
    });
});
// $(document).ready(function(){
   // $('#cek').on('change', function() {
	  // if ( this.value == '0');
	  // {
		// $("#div_nama").hide();
		// $("#div_ttl").hide();
		// $("#div_ktp").hide();
	  // }
		// else  if ( this.value == '1')
	  // {
		// $("#div_nama").show();
		// $("#div_ttl").show();
		// $("#div_ktp").hide();
	  // } 
		// else  
	  // {
		// $("#div_nama").hide();
		// $("#div_ttl").hide();
		// $("#div_ktp").show();
	  // }
	// });
// });
 
</script>

