
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
				<a href="<?php echo site_url();?>input/merchant/index/new_merchant" class="btn btn-info"><i class="fa fa-angle-double-left"></i>  Back</a>
				
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
							<h3>Data Merchant</h3>
							<hr>
						</div>
						<!-- end panel-heading -->
						<!-- begin panel-body -->
						<div class="panel-body">
						        <div class="form-group">
								        <label>Kolom (<span style="color:red">*</span>) wajib di isi !!</label>
						      	</div>

								<form method="POST" action="">
								<input type="hidden" name="RegnoId" value="<?php echo $db->RegnoId; ?>" />

						      	<?php
						      		$hit_code = $db->Hit_Code;
						      		$customer_id = $db->RegnoId;
									$qris_id = $db->Qris_ID;
									$allow_edit = array('101','102','107','103','104');
						      		if(in_array($hit_code, $allow_edit)) {
						      			$a = "required";
						      			$b = "";
						      			$c = "";
						      		}
						      		else {
						      			$a = "readonly";
						      			$b = "readonly";
										$c = "disabled";
						      		}
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
										        <textarea rows="3" class="form-control" name="verified_reason" readonly><?php echo set_value('verified_reason',$db->Verified_Reason);?></textarea>
								      	</div>
									<?php } ?>

							      	<div class="row row-space-10">
							      		
										<div class="col-md-6">
									        <div class="form-group">
											    <label>Nama Owner :</label>
												<input type="hidden" name="regnoid" value="<?=$db->RegnoId?>">
											    <input type="text" class="form-control" <?php echo $a;?> autocomplete="off" name="owner_name" value="<?php echo set_value('owner_name',$db->Owner_Name);?>"/>
									      	</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
											    <label>Nama Merchant :</label>
											    <input type="text" class="form-control" <?php echo $a;?> autocomplete="off" name="merchant_name" value="<?php echo set_value('merchant_name',$db->Merchant_Name);?>"/>
									      	</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
											    <label>Email :</label>
												<?php echo form_error('email'); ?>
									        	<input type="email" class="form-control" <?php echo $a;?> autocomplete="off" id="email" name="email" onkeyup="this.value=this.value.replace(' ','')" value="<?php echo set_value('email',$db->Email);?>"/>
									      	</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
											    <label>No. Handphone :</label>
									        	<input type="text" class="form-control" <?php echo $a;?> autocomplete="off" name="mobile_phone_number" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" value="<?php echo set_value('mobile_phone_number',$db->Mobile_Phone_Number);?>"/>
									      	</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
											    <label>No. Handphone Lain :</label>
									        	<input type="text" class="form-control" <?php echo $b;?> autocomplete="off" name="other_phone_number" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" value="<?php echo set_value('other_phone_number',$db->Other_Phone_Number);?>"/>
									      	</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
											    <label>No Rekening :</label>
												<?php echo form_error('account_number'); ?>
									        	<input type="text" class="form-control" <?php echo $a;?> autocomplete="off" id="account_number" name="account_number" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" onPaste="false" value="<?php echo set_value('account_number',$db->Account_Number);?>"/>
									      	</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
												<label>Status Merchant <span style="color:red">*</span> :</label>
												<select class="default-select2 form-control" name="mid_type" style="width: 100%" <?php echo $c;?>>
													<option value="">-- Pilih -- </option>
													<?php 
														$mid_type = $db->MID_Type;
														if($mid_type == 'NEW MERCHANT') {
													?>
														<option value="NEW MERCHANT" selected>- NEW MERCHANT</option>
														<option value="EXISTING MERCHANT">- EXISTING MERCHANT</option>
													<?php } else { ?>
														<option value="NEW MERCHANT">- NEW MERCHANT</option>
														<option value="EXISTING MERCHANT" selected>- EXISTING MERCHANT</option>
													<?php } ?>
												</select>
											</div>
									    </div>
									    <div class="col-md-6">
									        <div class="form-group">
												<label>Area Akusisi <span style="color:red">*</span> :</label>
												<select class="default-select2 form-control" name="officer_code" style="width: 100%" <?php echo $c;?>>
													<option value="">-- Pilih -- </option>
													<?php 
														$area_akusisi1 = $db->Officer_Code;
														foreach($area_akusisi as $a) {
															if($area_akusisi1 == $a->area_akusisi) {
													?>
														<option value="<?php echo $a->area_akusisi;?>" selected>- <?php echo $a->description;?></option>
													<?php } else {?>
														<option value="<?php echo $a->area_akusisi;?>">- <?php echo $a->description;?></option>
													<?php }} ?>
												</select>
											</div>
									    </div>

									    <div class="col-md-12">
										    <div class="form-group">
												<?php
													if(in_array($hit_code, $allow_edit)) { 
												?>
													<input type="submit" class="btn btn-primary" name="simpan" value="Update">
												<?php } else { ?>
													&nbsp;
												<?php } ?>
										    </div>
										</div>
									</div>

								</form>
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
							<h3>Upload Dokumen</h3>
							<hr>
						</div>
						<!-- end panel-heading -->
						<!-- begin panel-body -->
						<div class="panel-body">
							<div style="" class="row row-space-10">

								<div class="col-md-6">
									<div class="form-group m-b-10 p-t-5">
										<?php
											//$query_ktp = $this->db->query("SELECT * FROM upload_temp WHERE category_name = 'ktp' AND (customer_id = '$customer_id' OR customer_id = '$qris_id')");
											//$cek_ktp = $query_ktp->num_rows();
											//$cek_ktp = count((array)$query_ktp->id);
											if($query_ktp != '') {
												//$row_ktp = $query_ktp->row();
												$ktp_id = $query_ktp->id;
												$ktp = $query_ktp->image_name;
										?>
									        <label>Foto KTP <span style="color:red">*</span></label>
									        <br>

									        <a title="View Image" target="_blank" href="<?php echo MERCHANT_URL.'upload/dokumen/'.$ktp ?>" data-lightbox="gallery-group-1">
												<img width="210px" height="210px" src="<?php echo MERCHANT_URL.'upload/dokumen/'.$ktp ?>" />
											</a>
											<br>
											<!--bisa edit hanya jika status = '' atau 'return'-->
											<?php
												if(in_array($hit_code,$allow_edit))
												{
											?>
												<a href="<?php echo site_url();?>input/merchant/edit_document/<?php echo $ktp_id;?>">
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
												<img width="210px" height="210px" src="<?php echo MERCHANT_URL ?>new_assets/img/upload.png" />
											</a>
											<br>
										<?php } ?>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group m-b-10 p-t-5">
										<?php
											//$query_merchant = $this->db->query("SELECT * FROM upload_temp WHERE category_name = 'merchant' AND (customer_id = '$customer_id' OR customer_id = '$qris_id')");
											
											//$cek_merchant = $query_merchant->num_rows();
											//$cek_merchant = count((array)$query_merchant->id);
											if($query_merchant != '') {
												//$row_merchant = $query_merchant->row();
												$merchant_id = $query_merchant->id;
												$merchant = $query_merchant->image_name;
										?>
									        <label>Foto Merchant (tampak depan) <span style="color:red">*</span></label>
									        <br>
									        <a title="View Image" target="_blank" href="<?php echo MERCHANT_URL.'upload/dokumen/'.$merchant;?>" data-lightbox="gallery-group-1">
												<img width="210px" height="210px" src="<?php echo MERCHANT_URL.'upload/dokumen/'.$merchant;?>" />
											</a>
											<br>
											<!--bisa edit hanya jika status = '' atau 'return'-->
											<?php
												if(in_array($hit_code,$allow_edit))
												{
											?>
												<a href="<?php echo site_url();?>input/merchant/edit_document/<?php echo $merchant_id;?>">
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
											<a data-toggle="modal" data-target="#ModalMerchant" href="javascript:;" title="Klik untuk Upload Dokumen"><img width="230px" src="<?php echo MERCHANT_URL ?>new_assets/img/upload.png" /></a>
											<br><br>
										<?php } ?>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group m-b-10 p-t-5">
										<?php
											//$query_product = $this->db->query("SELECT * FROM upload_temp WHERE category_name = 'product' AND (customer_id = '$customer_id' OR customer_id = '$qris_id')");
											
											//$cek_product = $query_product->num_rows();
											//$cek_product = count((array)$query_product->id);
											if($query_product != '') {
												//$row_product = $query_product->row();
												$product_id = $query_product->id;
												$product = $query_product->image_name;
										?>
									        <label>Foto Produk <span style="color:red">*</span></label>
									        <br>
									        <a title="View Image" target="_blank" href="<?php echo MERCHANT_URL.'upload/dokumen/'.$product;?>" data-lightbox="gallery-group-1">
												<img width="210px" height="210px" src="<?php echo MERCHANT_URL.'upload/dokumen/'.$product;?>" />
											</a>
											<br>
											<!--bisa edit hanya jika status = '' atau 'return'-->
											<?php
												if(in_array($hit_code,$allow_edit))
												{
											?>
												<a href="<?php echo site_url();?>input/merchant/edit_document/<?php echo $product_id;?>">
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
											<a data-toggle="modal" data-target="#ModalProduct" href="javascript:;" title="Klik untuk Upload Dokumen"><img width="230px" src="<?php echo MERCHANT_URL ?>new_assets/img/upload.png" /></a>
											<br>
										<?php } ?>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group m-b-10 p-t-5">
										<?php
											//$query_npwp = $this->db->query("SELECT * FROM upload_temp WHERE category_name = 'npwp' AND (customer_id = '$customer_id' OR customer_id = '$qris_id')");
											
											//$cek_npwp = $query_npwp->num_rows();
											//$cek_npwp = count((array)$query_npwp->id);
											if($query_npwp != '') {
											    //$row_npwp = $query_npwp->row();
											    $npwp_id = $query_npwp->id;
											    $npwp = $query_npwp->image_name;
												
										?>
									        <label>Foto NPWP <span style="color:red">*</span></label>
									        <br>
									        <a title="View Image" target="_blank" href="<?php echo MERCHANT_URL.'upload/dokumen/'.$npwp;?>" data-lightbox="gallery-group-1">
												<img width="210px" height="210px" src="<?php echo MERCHANT_URL.'upload/dokumen/'.$npwp;?>" />
											</a>
											<br>
										<?php } elseif($query_npwp == '' AND $hit_code == '102') { ?>
									        <label>Foto NPWP <span style="color:red">*</span></label>
										    <br>
											<img width="230px" src="https://dev.ptdika.com/merchant/new_assets/img/upload.png" />
										<?php } else { ?>
									        <label>Foto NPWP <span style="color:red">*</span></label>
										    <br>
										    <label style="color:red">Klik untuk upload!</label>
									        <br>
											<a data-toggle="modal" data-target="#ModalNpwp" href="javascript:;" title="Klik untuk Upload Dokumen"><img width="230px" src="<?php echo MERCHANT_URL ?>new_assets/img/upload.png" /></a>
										<?php } ?>

										<!--bisa edit hanya jika hit_code = '101' atau '103'-->
										<?php
											if($query_npwp != '' AND in_array($hit_code,$allow_edit))
											{
										?>
											<a href="<?php echo site_url();?>input/merchant/edit_document/<?php echo $npwp_id;?>">
												<span style="margin-top: 5px" class="btn btn-xs btn-info"><i class="fa fa-md fa-edit" title="Edit Data"></i> Edit NPWP</span>
											</a>
											<a href="<?php echo site_url();?>input/merchant/delete_npwp/<?php echo $customer_id;?>/<?php echo $npwp_id;?>" onclick="return confirm('Yakin hapus dokumen?')">
												<span style="margin-top: 5px" class="btn btn-xs btn-danger"><i class="fa fa-md fa-trash" title="Hapus Data"></i> Delete</span>
											</a>
										<?php } else { ?>
											&nbsp;
										<?php } ?>
									</div>
								</div>

							</div>

							<?php
								if($hit_code == '101' AND ($query_ktp == '' OR $query_merchant == '' OR $query_product == '')) {
							?>
								<br>
								<h5><span style="color:red">*</span>Silahkan lengkapi upload dokumen</h5>
							<?php } elseif($hit_code == '101' AND ($query_ktp != '' OR $query_merchant != '' OR $query_product != '')) {
							?>
								<a href="<?php echo site_url();?>input/merchant/submit_aplikasi/<?php echo $customer_id;?>" class="btn btn-warning">Submit to APP</a>
							<?php } elseif($hit_code == '103' OR $hit_code == '104') {
							?>
								<a href="<?php echo site_url();?>input/merchant/submit_aplikasi/<?php echo $customer_id;?>" class="btn btn-warning">Submit to APP</a>
							<?php } else { ?>
								&nbsp;
							<?php } ?>

						</div>
						<!-- end panel-body -->
					</div>
					<!-- end panel -->
				</div>







			<!--MODAL KTP-->
			    <div class="modal fade" id="ModalKtp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			      	<div class="modal-dialog" role="document">
				        <div class="modal-content">
				          	<div class="modal-header">
				            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				          	</div>
						    <?php echo form_open_multipart('input/merchant/save_dokumen/'.$db->RegnoId); ?>
						        <div class="modal-body">
			                        <div class="form-group">
										<label>Silahkan Upload KTP :</label>
										<br>
										<div style="margin-top: 7px" id="selectImage">
			    							<input type="file" name="image_name" id="file">
			    						</div>
			    						<br>
										<label style="color: red; margin-bottom: -10px">Type file : JPG, JPEG, PNG</label>
										<br>
										<label style="color: red">Max size : 5 MB</label>
			    						<div id="message"></div>
			    						<input type="hidden" name="customer_id" value="<?php echo $customer_id;?>">
			    						<input type="hidden" name="category_id" value="1">
			    						<input type="hidden" name="category_name" value="ktp">
								    </div>
						      	</div>
							    <div class="modal-footer">
							        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							        <input type="submit" class="btn btn-primary" name="simpan" value="Save">
							    </div>
					        <?php echo form_close(); ?>
					    </div>
					</div>
			    </div>
			<!--END MODAL KTP-->

			<!--MODAL NPWP-->
			    <div class="modal fade" id="ModalNpwp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			      	<div class="modal-dialog" role="document">
				        <div class="modal-content">
				          	<div class="modal-header">
				            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				          	</div>
						    <?php echo form_open_multipart('input/merchant/save_dokumen/'.$db->RegnoId); ?>
						        <div class="modal-body">
			                        <div class="form-group">
										<label>Silahkan Upload NPWP :</label>
										<br>
										<div style="margin-top: 7px" id="selectImage">
			    							<input type="file" name="image_name" id="file">
			    						</div>
										<br>
										<label style="color: red; margin-bottom: -10px">Type file : JPG, JPEG, PNG</label>
										<br>
										<label style="color: red">Max size : 5 MB</label>
			    						<div id="message"></div>
			    						<input type="hidden" name="customer_id" value="<?php echo $customer_id;?>">
			    						<input type="hidden" name="category_id" value="2">
			    						<input type="hidden" name="category_name" value="npwp">
								    </div>
						      	</div>
							    <div class="modal-footer">
							        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							        <input type="submit" class="btn btn-primary" name="simpan" value="Save">
							    </div>
					        <?php echo form_close(); ?>
					    </div>
					</div>
			    </div>
			<!--END MODAL NPWP-->

			<!--MODAL MERCHANT-->
			    <div class="modal fade" id="ModalMerchant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			      	<div class="modal-dialog" role="document">
				        <div class="modal-content">
				          	<div class="modal-header">
				            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				          	</div>
						    <?php echo form_open_multipart('input/merchant/save_dokumen/'.$db->RegnoId); ?>
						        <div class="modal-body">
			                        <div class="form-group">
										<label>Silahkan Upload Toko Tampak Depan :</label>
										<br>
										<div style="margin-top: 7px" id="selectImage">
			    							<input type="file" name="image_name" id="file">
			    						</div>
										<br>
										<label style="color: red; margin-bottom: -10px">Type file : JPG, JPEG, PNG</label>
										<br>
										<label style="color: red">Max size : 5 MB</label>
			    						<div id="message"></div>
			    						<input type="hidden" name="customer_id" value="<?php echo $customer_id;?>">
			    						<input type="hidden" name="category_id" value="3">
			    						<input type="hidden" name="category_name" value="merchant">
								    </div>
						      	</div>
							    <div class="modal-footer">
							        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							        <input type="submit" class="btn btn-primary" name="simpan" value="Save">
							    </div>
					        <?php echo form_close(); ?>
					    </div>
					</div>
			    </div>
			<!--END MODAL MERCHANT-->

			<!--MODAL MERCHANT-->
			    <div class="modal fade" id="ModalProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			      	<div class="modal-dialog" role="document">
				        <div class="modal-content">
				          	<div class="modal-header">
				            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				          	</div>
						    <?php echo form_open_multipart('input/merchant/save_dokumen/'.$db->RegnoId); ?>
						        <div class="modal-body">
			                        <div class="form-group">
										<label>Silahkan Upload Produk :</label>
										<br>
										<div style="margin-top: 7px" id="selectImage">
			    							<input type="file" name="image_name" id="file">
			    						</div>
										<br>
										<label style="color: red; margin-bottom: -10px">Type file : JPG, JPEG, PNG</label>
										<br>
										<label style="color: red">Max size : 5 MB</label>
			    						<div id="message"></div>
			    						<input type="hidden" name="customer_id" value="<?php echo $customer_id;?>">
			    						<input type="hidden" name="category_id" value="4">
			    						<input type="hidden" name="category_name" value="product">
								    </div>
						      	</div>
							    <div class="modal-footer">
							        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							        <input type="submit" class="btn btn-primary" name="simpan" value="Save">
							    </div>
					        <?php echo form_close(); ?>
					    </div>
					</div>
			    </div>
			<!--END MODAL MERCHANT-->