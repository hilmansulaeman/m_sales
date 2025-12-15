
<!-- script JQuery untuk load gambar pada bagian preview -->
<script type="text/javascript">
$(function() {
  $("#file").change(function() {
	$("#message").empty(); // To remove the previous error message
	var file = this.files[0];
	var imagefile = file.type;
	var match= ["image/jpeg","image/png","image/jpg"];
	if (!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
	{
	  $('#previewing').attr('src','noimage.png');
	  $("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
	  return false;
	}else {
	  var reader = new FileReader();
	  reader.onload = imageIsLoaded;
	  reader.readAsDataURL(this.files[0]);
	}
  });
});
function imageIsLoaded(e) {
  $("#file").css("color","green");
  $('#image_preview').css("display", "block");
  $('#previewing').attr('src', e.target.result);
  $('#previewing').attr('width', '230px');
  $('#previewing').attr('height', '250px');
}
</script>
			
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li class="breadcrumb-item"><a href="javascript:;">Table</a></li>
				<li class="breadcrumb-item"><a href="javascript:;">Merchant</a></li>
				<li class="breadcrumb-item"><a href="javascript:;">Edit Dokumen</a></li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><br></h1>
			<!-- end page-header -->
			
			<!-- begin row -->
			<div class="row">
				<!-- begin col-6 -->
				<div class="col-lg-12">
					<!-- begin panel -->
					<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
						<!-- begin panel-heading -->
						<div class="panel-heading">
							<h3>Edit <?php echo $db->category_name;?></h3>
						</div>
						<!-- end panel-heading -->
						<!-- begin panel-body -->
						<div class="panel-body">
							<?php echo form_open_multipart('input/qris/update_document/'.$db->customer_id); ?>
							<input type="hidden" name="image_old" value="<?php echo $db->image_name;?>">
						        <div class="form-group">
						        		<div id="image_preview"><img id="previewing" src="<?php echo MERCHANT_URL.'upload/dokumen/'.$db->image_name ?>"  height="210px" width="210px"/></div>
										<label style="margin-top: 5px">Silahkan Upload <?php echo $db->category_name;?> :</label>
										<br>
										<label style="color: red; margin-bottom: -10px">Type file : JPG, JPEG, PNG</label>
										<br>
										<label style="color: red">Max size : 5 MB</label>
										<div style="margin-top: 7px" id="selectImage">
			    							<input type="file" name="image_name" id="file" required>
			    						</div>
										<input type="hidden" name="customer_id" value="<?php echo $db->customer_id;?>">
										<input type="hidden" name="doc_id" value="<?php echo $db->id;?>">
										<input type="hidden" name="category_id" value="<?php echo $db->category_id;?>">
			    						<input type="hidden" name="category_name" value="<?php echo $db->category_name;?>">
						      	</div>

							    <div class="form-group">
							        <input type="submit" class="btn btn-primary" name="simpan" value="Update">
								    <a href="<?php echo site_url();?>input/qris/upload_dokumen/<?php echo $db->customer_id;?>">
							        	<input type="button" class="btn btn-warning" value="Back">
							        </a>
							    </div>
					        </form>
						</div>
						<!-- end panel-body -->
					</div>
					<!-- end panel -->
				</div>
			</div>