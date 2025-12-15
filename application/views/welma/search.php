<style>
#data-table-customer td {
    background:#4caf50;
}
</style>
<?php
$level=$this->session->userdata('level');
?>
<!-- MAIN CONTENT -->
<div id="ribbon">
	<ol class="breadcrumb">
		<i class="fa fa-home"></i> &nbsp;
		<li><a href="<?php echo site_url(); ?>">Home</a></li>
		<li><i class="fa fa-cloud-upload "></i> &nbsp; Pemol</li>
		<li>Search</li>
	</ol>	 
</div>
<div id="content">

<?php if ($this->session->flashdata('message')) { ?>
    <div class="alert alert-warning fade in">
      <button class="close" data-dismiss="alert" id="notif">
        ×
      </button>
      <i class="fa-fw fa fa-check"></i>
      <?php echo $this->session->flashdata('message'); ?>
    </div>
<?php }?>
    <div class="row" >
		<button onclick="topFunction()" id="myBtn" title="Go to top">UP</button> 
	</div>
	<section id="widget-grid">
		<div class="well" > 
			<div class="widget-body-toolbar">				
				<div class="row">
					<div class="col-sm-12">
					    <?php
						if($key==null){
					        echo "<p style='color:red;'>Please enter keyword...!!!!!</p>";
						}
						else{
						?>
						<h6 class="txt-color-blueDark"><i class="fa fa-bank fa-fw "></i> SEARCH RESULT</h6>
						<span>Found <b><?php echo $search_count ?></b> search results with keyword <b><?php echo $key ?></b></span>
					</div>							
				</div>
			</div>
			
			<table id="data-table-customer" class="table table-hover" width="100%">
				<thead>											
					<tr>				 											 				
						<th>No</th>
						<th>Data Pemol</th>
					</tr>
				</thead>
				<tbody>
				    <?php 
					foreach($query->result() as $row){
					    $sales_code = $row->Sales_Code;
						if($level == '1' || $level == '2'){
						    $display_button = "";
						}
						else{
							if($sales_code == $this->session->userdata('nik')){
								$display_button = "";
							}
							else{
								$display_button = "display:none";
							}
						}
					?>
				    <tr>
						<td><div style="color:#FFFFFF;font-size:12px;font-family: Arial;"><strong><?php echo ++$id; ?></strong></div></td>
						<td>
						    <div style="color:#FFFFFF;font-size:12px;font-family: Arial;">
								<i class="fa-fw fa fa-building"></i> <strong>Nomor Rekening &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : <?php echo $row->Account_Number; ?> </strong><br>					
								<i class="fa-fw fa fa-calendar"></i> <strong>Tanggal Input : <?php echo $row->Input_Date; ?></strong></i><br>
								<i class="pull-right">
									<i class="fa-fw fa fa-user"></i> <strong>Nama Sales : <?php echo $row->Sales_Name.' ('.$row->Sales_Code.')'; ?> </strong>
								</i><br>
								<span class="pull-right">
									<a style="<?php echo $display_button; ?>" href="<?php echo site_url('pemol/detail/'.$row->RegnoId) ?>" class="btn btn-primary btn-xs"><i class="fa fa-building"></i></a>						
									<a style="<?php echo $display_button; ?>" href="<?php echo site_url('pemol/edit/'.$row->RegnoId) ?>" class="btn btn-danger btn-xs"><i class="fa fa-edit"></i></a>						
								</span>
							</div>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php } ?>
		</div>					
	</section>		 
</div>