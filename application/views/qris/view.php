
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
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->

			<h1 class="page-header">
				<!--<a href="<?php //echo site_url();?>monitoring/merchant/index/new_merchant" class="btn btn-info"><i class="fa fa-angle-double-left"></i>  Back</a>-->
				Detail Data
				
				<?php echo $this->session->flashdata('message'); ?>

			</h1>
			
			<!-- begin row -->
			<div class="row">
				<!-- begin col-6 -->
				<div class="col-lg-6">
					<!-- begin panel -->
					<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
						<!-- begin panel-heading -->
						<div class="panel-heading">
							<div class="panel-heading-btn">
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
							</div>
							<h4 class="panel-title">Data Merchant</h4>
						</div>
						<!-- end panel-heading -->
						<!-- begin panel-body -->
						<div class="panel-body">
						        <div class="form-group">
								        <label>Kolom (<span style="color:red">*</span>) wajib di isi !</label>
						      	</div>

						      	<?php
						      		$hit_code = $db->hit_code;
						      		$customer_id = $db->id;
						      		if($hit_code == '101' OR $hit_code == '103' OR $hit_code == '104') {
						      			$a = "required";
						      			$b = "";
						      		}
						      		else {
						      			$a = "readonly";
						      			$b = "readonly";
						      		}
									$allow_edit = array('101','103','104');
						      	?>
							
									<input type="hidden" name="id" value="<?php echo $this->uri->segment(4);?>">
							        <div class="form-group">
									        <label><b>ID APLIKASI</b> :</label>
									        <label class="label label-lg label-danger"><?php echo set_value('id',$customer_id);?></label>
							      	</div>

							      	<?php
							      		if($hit_code == '103' OR $hit_code == '104') {
							      	?>
								        <div class="form-group">
										        <label style="color:red">Keterangan Return :</label>
										        <textarea rows="3" class="form-control" name="verified_reason" readonly><?php echo set_value('verified_reason',$db->verified_reason);?></textarea>
								      	</div>
									<?php } ?>

							      	<div class="row row-space-10">
							      		
										<div class="col-md-6">
									        <div class="form-group">
											    <label>Nama Owner :</label>
											    <input type="text" class="form-control" <?php echo $a;?> autocomplete="off" name="owner_name" value="<?php echo set_value('owner_name',$db->owner_name);?>"/>
									      	</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
											    <label>Nama Merchant :</label>
											    <input type="text" class="form-control" <?php echo $a;?> autocomplete="off" name="merchant_name" value="<?php echo set_value('merchant_name',$db->merchant_name);?>"/>
									      	</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
											    <label>Email :</label>
									        	<input type="email" class="form-control" <?php echo $a;?> autocomplete="off" name="email" onkeyup="this.value=this.value.replace(' ','')" value="<?php echo set_value('email',$db->email);?>"/>
									      	</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
											    <label>No. Handphone :</label>
									        	<input type="text" class="form-control" <?php echo $a;?> autocomplete="off" name="no_hp" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" value="<?php echo set_value('no_hp',$db->no_hp);?>"/>
									      	</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
											    <label>No. Handphone Lain :</label>
									        	<input type="text" class="form-control" <?php echo $b;?> autocomplete="off" name="other_no_hp" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" value="<?php echo set_value('other_no_hp',$db->other_no_hp);?>"/>
									      	</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
												<label>Area Akusisi <span style="color:red">*</span> :</label>
												<select class="default-select2 form-control" name="area_akusisi" style="width: 100%" required>
													<?php 
														$area_akusisi = $db->area_akusisi;
													?>
													<option value="">-- Pilih -- </option>
													<option value="QSD" <?php if($area_akusisi == 'QSD') { echo "selected"; }?>>Area Perdagangan</option>
													<option value="QRD" <?php if($area_akusisi == 'QRD') { echo "selected"; }?>>Non Area Perdagangan</option>
												</select>
											</div>
									    </div>
									</div>
							<br>

						</div>
						<!-- end panel-body -->
					</div>
					<!-- end panel -->
				</div>

				<!-- begin col-4 -->
				<div class="col-lg-6">
					<!-- begin panel -->
					<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
						<!-- begin panel-heading -->
						<div class="panel-heading">
							<div class="panel-heading-btn">
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
							</div>
							<h4 class="panel-title">Upload Dokumen</h4>
						</div>
						<!-- end panel-heading -->
						<!-- begin panel-body -->
						<div class="panel-body">
							<div style="" class="row row-space-10">

								<div class="col-md-6">
									<div class="form-group m-b-10 p-t-5">
										<?php
											$query_ktp = $this->db->query("SELECT * FROM upload_temp WHERE customer_id = '$customer_id' AND category_name = 'ktp'");
											
											$cek_ktp = $query_ktp->num_rows();
											if($cek_ktp > 0) {
												$row_ktp = $query_ktp->row();
												$ktp_id = $row_ktp->id;
												$ktp = $row_ktp->image_name;
										?>
									        <label>Foto KTP <span style="color:red">*</span></label>
									        <br>

									        <a title="View Image" target="_blank" href="<?php echo base_url(); ?>upload/dokumen/<?php echo $ktp;?>" data-lightbox="gallery-group-1">
												<img width="210px" height="210px" src="<?php echo base_url(); ?>upload/dokumen/<?php echo $ktp;?>" />
											</a>
											<br>
											<!--bisa edit hanya jika status = '' atau 'return'-->
											<?php
												if(in_array($hit_code,$allow_edit))
												{
											?>
												<a href="<?php echo site_url();?>sales/merchant/edit_document/<?php echo $customer_id;?>/<?php echo $ktp_id;?>">
													<span style="margin-top: 5px" class="btn btn-xs btn-info"><i class="fa fa-md fa-edit" title="Edit Data"></i> Edit KTP</span>
												</a>
											<?php } else { ?>
												&nbsp;
											<?php } ?>

										<?php } else { ?>
									        <label>Foto KTP <span style="color:red">*</span></label>
										    <br>
										    <label style="color:red">Klik untuk upload!</label>
									        <br>
											<a data-toggle="modal" data-target="#ModalKtp" href="javascript:;" title="Klik untuk Upload Dokumen">
												<img width="210px" height="210px" src="<?php echo base_url(); ?>new_assets/img/upload.png" />
											</a>
										<?php } ?>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group m-b-10 p-t-5">
										<?php
											$query_merchant = $this->db->query("SELECT * FROM upload_temp WHERE customer_id = '$customer_id' AND category_name = 'merchant'");
											
											$cek_merchant = $query_merchant->num_rows();
											if($cek_merchant > 0) {
												$row_merchant = $query_merchant->row();
												$merchant_id = $row_merchant->id;
												$merchant = $row_merchant->image_name;
										?>
									        <label>Foto Merchant (tampak depan) <span style="color:red">*</span></label>
									        <br>
									        <a title="View Image" target="_blank" href="<?php echo base_url(); ?>upload/dokumen/<?php echo $merchant;?>" data-lightbox="gallery-group-1">
												<img width="210px" height="210px" src="<?php echo base_url(); ?>upload/dokumen/<?php echo $merchant;?>" />
											</a>
											<br>
											<!--bisa edit hanya jika status = '' atau 'return'-->
											<?php
												if(in_array($hit_code,$allow_edit))
												{
											?>
												<a href="<?php echo site_url();?>sales/merchant/edit_document/<?php echo $customer_id;?>/<?php echo $merchant_id;?>">
													<span style="margin-top: 5px" class="btn btn-xs btn-info"><i class="fa fa-md fa-edit" title="Edit Data"></i> Edit Merchant</span>
												</a>
											<?php } else { ?>
												&nbsp;
											<?php } ?>
										<?php } else { ?>
									        <label>Foto Merchant (tampak depan)<span style="color:red">*</span></label>
										    <br>
										    <label style="color:red">Klik untuk upload!</label>
									        <br>
											<a data-toggle="modal" data-target="#ModalMerchant" href="javascript:;" title="Klik untuk Upload Dokumen"><img width="230px" src="<?php echo base_url(); ?>new_assets/img/upload.png" /></a>
										<?php } ?>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group m-b-10 p-t-5">
										<?php
											$query_product = $this->db->query("SELECT * FROM upload_temp WHERE customer_id = '$customer_id' AND category_name = 'product'");
											
											$cek_product = $query_product->num_rows();
											if($cek_product > 0) {
												$row_product = $query_product->row();
												$product_id = $row_product->id;
												$product = $row_product->image_name;
										?>
									        <label>Foto Produk <span style="color:red">*</span></label>
									        <br>
									        <a title="View Image" target="_blank" href="<?php echo base_url(); ?>upload/dokumen/<?php echo $product;?>" data-lightbox="gallery-group-1">
												<img width="210px" height="210px" src="<?php echo base_url(); ?>upload/dokumen/<?php echo $product;?>" />
											</a>
											<br>
											<!--bisa edit hanya jika status = '' atau 'return'-->
											<?php
												if(in_array($hit_code,$allow_edit))
												{
											?>
												<a href="<?php echo site_url();?>sales/merchant/edit_document/<?php echo $customer_id;?>/<?php echo $product_id;?>">
													<span style="margin-top: 5px" class="btn btn-xs btn-info"><i class="fa fa-md fa-edit" title="Edit Data"></i> Edit Produk</span>
												</a>
											<?php } else { ?>
												&nbsp;
											<?php } ?>
										<?php } else { ?>
									        <label>Foto Produk <span style="color:red">*</span></label>
										    <br>
										    <label style="color:red">Klik untuk upload!</label>
									        <br>
											<a data-toggle="modal" data-target="#ModalProduct" href="javascript:;" title="Klik untuk Upload Dokumen"><img width="230px" src="<?php echo base_url(); ?>new_assets/img/upload.png" /></a>
										<?php } ?>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group m-b-10 p-t-5">
										<?php
											$query_npwp = $this->db->query("SELECT * FROM upload_temp WHERE customer_id = '$customer_id' AND category_name = 'npwp'");
											
											$cek_npwp = $query_npwp->num_rows();
											if($cek_npwp > 0) {
											    $row_npwp = $query_npwp->row();
											    $npwp_id = $row_npwp->id;
											    $npwp = $row_npwp->image_name;
												
										?>
									        <label>Foto NPWP <span style="color:red">*</span></label>
									        <br>
									        <a title="View Image" target="_blank" href="<?php echo base_url(); ?>upload/dokumen/<?php echo $npwp;?>" data-lightbox="gallery-group-1">
												<img width="210px" height="210px" src="<?php echo base_url(); ?>upload/dokumen/<?php echo $npwp;?>" />
											</a>
											<br>
										<?php } elseif($cek_npwp == 0 AND $hit_code == '102') { ?>
									        <label>Foto NPWP <span style="color:red">*</span></label>
										    <br>
											<img width="230px" src="<?php echo base_url(); ?>new_assets/img/upload.png" />
										<?php } else { ?>
									        <label>Foto NPWP <span style="color:red">*</span></label>
										    <br>
										    <label style="color:red">Klik untuk upload!</label>
									        <br>
											<a data-toggle="modal" data-target="#ModalNpwp" href="javascript:;" title="Klik untuk Upload Dokumen"><img width="230px" src="<?php echo base_url(); ?>new_assets/img/upload.png" /></a>
										<?php } ?>

										<!--bisa edit hanya jika hit_code = '101' atau '103'-->
										<?php
											if($cek_npwp > 0 AND in_array($hit_code,$allow_edit))
											{
										?>
											<a href="<?php echo site_url();?>sales/merchant/edit_document/<?php echo $customer_id;?>/<?php echo $npwp_id;?>">
												<span style="margin-top: 5px" class="btn btn-xs btn-info"><i class="fa fa-md fa-edit" title="Edit Data"></i> Edit NPWP</span>
											</a>
											<a href="<?php echo site_url();?>sales/merchant/delete_npwp/<?php echo $customer_id;?>/<?php echo $npwp_id;?>" onclick="return confirm('Yakin hapus dokumen?')">
												<span style="margin-top: 5px" class="btn btn-xs btn-danger"><i class="fa fa-md fa-trash" title="Hapus Data"></i> Delete</span>
											</a>
										<?php } else { ?>
											&nbsp;
										<?php } ?>
									</div>
								</div>

							</div>

						</div>
						<!-- end panel-body -->
					</div>
					<!-- end panel -->
				</div>