<?php $rows = $schedule->row(); ?>


<style>
	.plus,
	.minus {
		display: inline-block;
		background-repeat: no-repeat;
		background-size: 16px 16px !important;
		width: 16px;
		height: 16px;
		/*vertical-align: middle;*/
	}

	.plus {
		background-image: url(https://img.icons8.com/color/48/000000/plus.png);
	}

	.minus {
		background-image: url(https://img.icons8.com/color/48/000000/minus.png);
	}

	ul {
		list-style: none;
		padding: 0px 0px 0px 20px;
	}

	ul.inner_ul li:before {
		content: "├";
		font-size: 18px;
		margin-left: -11px;
		margin-top: -5px;
		vertical-align: middle;
		float: left;
		width: 8px;
		color: #41424e;
	}

	ul.inner_ul li:last-child:before {
		content: "└";
	}

	.inner_ul {
		padding: 0px 0px 0px 35px;
	}
</style>


<?php if ($this->session->flashdata('error')) { ?>
	<div class="alert alert-warning fade in">
		<button class="close" data-dismiss="alert" id="notif">
			×
		</button>
		<i class="fa-fw fa fa-check"></i>
		<?php echo $this->session->flashdata('error'); ?>
	</div>
<?php } ?>
<form method="post" action="<?php echo site_url('meeting/schedule/update_schedule/' . $rows->Schedule_ID); ?>">
	<div class="box box-primary">
		<div class="box-header with-border">
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
				</button>
			</div>
			<h3 class="box-title">Form Edit Schedule</h3>
		</div>
		<br />
		<!-- /.panel-heading -->
		<div class="panel-body">
			<?php //echo form_open('');
			?>

			<?php // echo form_open_multipart('extimasi/do_upload');
			?>
			<div class="form-group">
				<label>Tipe Meeting :</label>
				<select class="form-control" name="schedule_type" id="schedule_type" required onchange="cekType(this.value)">
					<option value="">--Pilih--</option>
					<!-- <option value="online">Online</option>
						<option value="offline">Offline</option> -->
					<option value="online" <?php if ($rows->Schedule_Type == "online") {
												echo 'selected="selected"';
											} ?>>Online</option>
					<option value="offline" <?php if ($rows->Schedule_Type == "offline") {
												echo 'selected="selected"';
											} ?>>Offline</option>
				</select>
			</div>
			<div class="form-group" id="tema">
				<label>Tema / Pembahasan :</label>
				<textarea class="form-control" name="tema" id="tema" required> <?php echo $rows->Tema; ?> </textarea>
			</div>
			<div class="form-group" id="lokasi" style="display: none;">
				<label>Lokasi :</label>
				<input type="text" class="form-control" name="lokasi" id="lokasi" value="<?= $rows->Location_Name ?>" required>
				<!-- <select class="form-control" name="lokasi" id="lokasi">
					<option value="">--Pilih--</option>
					<?php
					$location_name = $rows->Location_Name;
					
					foreach ($query_lokasi->result() as $c) {
						// echo "<option value='" . $c->Location_ID ."-".$c->Location_Name."'>" . $c->Location_Name . "</option>";
						if ($c->Location_Name == $location_name) {
							// echo "<option value='" . $c->Location_ID . "-" . $c->Location_Name . "' selected>" . $c->Location_Name . "</option>";
						} else {
							// echo "<option value='" . $c->Location_ID . "-" . $c->Location_Name . "'>" . $c->Location_Name . "</option>";
						}
					}
					?>
				</select> -->
			</div>
			<div id="hidden_menu" style="display:none;">

				<div class="form-group">
					<label>Tanggal Meeting :</label>
					<!-- <input type="text" name="training_date" autocomplete="off" id="Training_Date" class="form-control tanggal"  placeholder="YYYY-MM-DD"/> -->
					<div class="input-group date" style="display:flex; !important">
						<!-- <input type="text" name="efective_date" class="form-control pull-right tanggal" id="ed" autocomplete="off" onChange="cekBulan(this.value)"> -->
						<input type="date" name="training_date" class="form-control" id="Training_Date" autocomplete="off" required value="<?php echo $rows->Schedule_Date; ?>">
					</div>
				</div>
				<div class="form-group">
					<label>Jam :</label>
					<input type="time" name="training_time" class="form-control" id="Training_Time" value="<?php echo $rows->Schedule_Time; ?>" />
				</div>
			</div>
			<div id="link_meeting" style="display: none;">
				<div class="form-group">
					<label>Link Meeting :</label>
					<input type="text" name="link_meeting" class="form-control" id="Link_Meeting" value="<?php echo $rows->Link_Meeting; ?>" />
				</div>
			</div>
		</div>
	</div>

	<label>Anggota :</label>
	<?php if ($position == 'BSH') { ?>
		<div class="tree_main" id="myTable">
			<ul id="bs_main" class="main_ul">
				<li id="bs_1">
					<span class="plus">&nbsp;</span>
					<input class="1" type="checkbox" id="c_bs_1" onClick="checkAll(this)" value='1' />
					<span>RSM</span>
					<ul id="bs_l_1" class="sub_ul" style="display: none">
						<?php
						foreach ($query_anggota_rsm->result() as $rsm) {
							$isCheck = '';
							foreach ($participants->result() as $p) {
								if ($p->NIK == $rsm->DSR_Code) {
									$isCheck .= 'checked';
								} else {
									$isCheck .= '';
								}
							}
						?>
							<li id="bf_1">
								<span>&nbsp;</span>
								<input class="children1" type="checkbox" id="c_bf_1" name="anggota[]" onClick="checkparent(this,'1')" value="<?php echo $rsm->DSR_Code; ?>-<?php echo $rsm->Name; ?>" <?php echo ($isCheck == "") ? "" : "checked"; ?> />
								<span><?php echo $rsm->Name; ?></span>
							</li>
						<?php } ?>
					</ul>

				</li>
				<li id="bs_1">
					<span class="plus">&nbsp;</span>
					<input class="2" type="checkbox" id="c_bs_2" onClick="checkAll(this)" value='2' />
					<span>ASM</span>
					<ul id="bs_l_1" class="sub_ul" style="display: none">
						<?php
						foreach ($query_anggota_asm->result() as $asm) {
							$isCheck = '';
							foreach ($participants->result() as $p) {
								if ($p->NIK == $asm->DSR_Code) {
									$isCheck .= 'checked';
								} else {
									$isCheck .= '';
								}
							}
						?>
							<li id="bf_1">
								<span>&nbsp;</span>
								<input class="children2" type="checkbox" id="c_bf_2" name="anggota[]" onClick="checkparent(this,'2')" value="<?php echo $asm->DSR_Code; ?>-<?php echo $asm->Name; ?>" <?php echo ($isCheck == "") ? "" : "checked"; ?> />
								<span><?php echo $asm->Name; ?></span>
							</li>
						<?php } ?>
					</ul>

				</li>
				<li id="bs_1">
					<span class="plus">&nbsp;</span>
					<input class="3" type="checkbox" id="c_bs_3" onClick="checkAll(this)" value='3' />
					<span>SPV</span>
					<ul id="bs_l_1" class="sub_ul" style="display: none">
						<?php
						foreach ($query_anggota_spv->result() as $spv) {
							$isCheck = '';
							foreach ($participants->result() as $p) {
								if ($p->NIK == $spv->DSR_Code) {
									$isCheck .= 'checked';
								} else {
									$isCheck .= '';
								}
							}

						?>
							<li id="bf_1">
								<span>&nbsp;</span>
								<input class="children3" type="checkbox" id="c_bf_3" name="anggota[]" onClick="checkparent(this,'3')" value="<?php echo $spv->DSR_Code; ?>-<?php echo $spv->Name; ?>" <?php echo ($isCheck == "") ? "" : "checked"; ?> />
								<span><?php echo $spv->Name; ?></span>
							</li>
						<?php } ?>
					</ul>
				</li>
				<li id="bs_1">
					<span class="plus">&nbsp;</span>
					<input class="4" type="checkbox" id="c_bs_4" onClick="checkAll(this)" value='4' />
					<span>DSR</span>
					<ul id="bs_l_1" class="sub_ul" style="display: none">
						<?php
						foreach ($query_anggota_dsr->result() as $dsr) {
							$isCheck = '';
							foreach ($participants->result() as $p) {
								if ($p->NIK == $dsr->DSR_Code) {
									$isCheck .= 'checked';
								} else {
									$isCheck .= '';
								}
							}
						?>
							<li id="bf_1">
								<span>&nbsp;</span>
								<input class="children4" type="checkbox" id="c_bf_4" name="anggota[]" onClick="checkparent(this,'4')" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" <?php echo ($isCheck == "") ? "" : "checked"; ?> />
								<span><?php echo $dsr->Name; ?></span>
							</li>
						<?php } ?>
					</ul>
				</li>
			</ul>
		</div>
	<?php } elseif ($position == 'RSM') { ?>
		<div class="tree_main" id="myTable">
			<ul id="bs_main" class="main_ul">
				<li id="bs_1">
					<span class="plus">&nbsp;</span>
					<input class="2" type="checkbox" id="c_bs_2" onClick="checkAll(this)" value='2' />
					<span>ASM</span>
					<ul id="bs_l_1" class="sub_ul" style="display: none">
						<?php
						foreach ($query_anggota_asm->result() as $asm) {
							$isCheck = '';
							foreach ($participants->result() as $p) {
								if ($p->NIK == $asm->DSR_Code) {
									$isCheck .= 'checked';
								} else {
									$isCheck .= '';
								}
							}
						?>
							<li id="bf_1">
								<span>&nbsp;</span>
								<input class="children2" type="checkbox" id="c_bf_2" name="anggota[]" onClick="checkparent(this,'2')" value="<?php echo $asm->DSR_Code; ?>-<?php echo $asm->Name; ?>" <?php echo ($isCheck == "") ? "" : "checked"; ?> />
								<span><?php echo $asm->Name; ?></span>
							</li>
						<?php } ?>
					</ul>

				</li>
				<li id="bs_1">
					<span class="plus">&nbsp;</span>
					<input class="3" type="checkbox" id="c_bs_3" onClick="checkAll(this)" value='3' />
					<span>SPV</span>
					<ul id="bs_l_1" class="sub_ul" style="display: none">
						<?php
						foreach ($query_anggota_spv->result() as $spv) {
							$isCheck = '';
							foreach ($participants->result() as $p) {
								if ($p->NIK == $spv->DSR_Code) {
									$isCheck .= 'checked';
								} else {
									$isCheck .= '';
								}
							}

						?>
							<li id="bf_1">
								<span>&nbsp;</span>
								<input class="children3" type="checkbox" id="c_bf_3" name="anggota[]" onClick="checkparent(this,'3')" value="<?php echo $spv->DSR_Code; ?>-<?php echo $spv->Name; ?>" <?php echo ($isCheck == "") ? "" : "checked"; ?> />
								<span><?php echo $spv->Name; ?></span>
							</li>
						<?php } ?>
					</ul>
				</li>
				<li id="bs_1">
					<span class="plus">&nbsp;</span>
					<input class="4" type="checkbox" id="c_bs_4" onClick="checkAll(this)" value='4' />
					<span>DSR</span>
					<ul id="bs_l_1" class="sub_ul" style="display: none">
						<?php
						foreach ($query_anggota_dsr->result() as $dsr) {
							$isCheck = '';
							foreach ($participants->result() as $p) {
								if ($p->NIK == $dsr->DSR_Code) {
									$isCheck .= 'checked';
								} else {
									$isCheck .= '';
								}
							}

						?>
							<li id="bf_1">
								<span>&nbsp;</span>
								<input class="children4" type="checkbox" id="c_bf_4" name="anggota[]" onClick="checkparent(this,'4')" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" <?php echo ($isCheck == "") ? "" : "checked"; ?> />
								<span><?php echo $dsr->Name; ?></span>
							</li>
						<?php } ?>
					</ul>
				</li>
			</ul>
		</div>
	<?php } elseif ($position == 'ASM') { ?>
		<div class="tree_main" id="myTable">
			<ul id="bs_main" class="main_ul">
				<li id="bs_1">
					<span class="plus">&nbsp;</span>
					<input class="3" type="checkbox" id="c_bs_3" onClick="checkAll(this)" value='3' />
					<span>SPV</span>
					<ul id="bs_l_1" class="sub_ul" style="display: none">
						<?php
						foreach ($query_anggota_spv->result() as $spv) {
							$isCheck = '';
							foreach ($participants->result() as $p) {
								if ($p->NIK == $spv->DSR_Code) {
									$isCheck .= 'checked';
								} else {
									$isCheck .= '';
								}
							}

						?>
							<li id="bf_1">
								<span>&nbsp;</span>
								<input class="children3" type="checkbox" id="c_bf_3" name="anggota[]" onClick="checkparent(this,'3')" value="<?php echo $spv->DSR_Code; ?>-<?php echo $spv->Name; ?>" <?php echo ($isCheck == "") ? "" : "checked"; ?> />
								<span><?php echo $spv->Name; ?></span>
							</li>
						<?php } ?>
					</ul>
				</li>
				<li id="bs_1">
					<span class="plus">&nbsp;</span>
					<input class="4" type="checkbox" id="c_bs_4" onClick="checkAll(this)" value='4' />
					<span>DSR</span>
					<ul id="bs_l_1" class="sub_ul" style="display: none">
						<?php
						foreach ($query_anggota_dsr->result() as $dsr) {
							$isCheck = '';
							foreach ($participants->result() as $p) {
								if ($p->NIK == $dsr->DSR_Code) {
									$isCheck .= 'checked';
								} else {
									$isCheck .= '';
								}
							}
						?>
							<li id="bf_1">
								<span>&nbsp;</span>
								<input class="children4" type="checkbox" id="c_bf_4" name="anggota[]" onClick="checkparent(this,'4')" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" <?php echo ($isCheck == "") ? "" : "checked"; ?> />
								<span><?php echo $dsr->Name; ?></span>
							</li>
						<?php } ?>
					</ul>
				</li>
			</ul>
		</div>
	<?php } elseif ($position == 'SPV') { ?>
		<div class="tree_main" id="myTable">
			<ul id="bs_main" class="main_ul">
				<li id="bs_1">
					<span class="plus">&nbsp;</span>
					<input class="4" type="checkbox" id="c_bs_4" onClick="checkAll(this)" value='4' />
					<span>DSR</span>
					<ul id="bs_l_1" class="sub_ul" style="display: none">
						<?php
						foreach ($query_anggota_dsr->result() as $dsr) { 
							$isCheck = '';
							foreach ($participants->result() as $p) {
								if ($p->NIK == $dsr->DSR_Code) {
									$isCheck .= 'checked';
								} else {
									$isCheck .= '';
								}
							}
						?>
							<li id="bf_1">
								<span>&nbsp;</span>
								<input class="children4" type="checkbox" id="c_bf_4" name="anggota[]" onClick="checkparent(this,'4')" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" <?php echo ($isCheck == "") ? "" : "checked"; ?> />
								<span><?php echo $dsr->Name; ?></span>
							</li>
						<?php } ?>
					</ul>
				</li>
			</ul>
		</div>
	<?php } ?>



	<button type="submit" class="btn btn-primary">Simpan</button>

</form>
<script type="text/javascript">
	$(document).ready(function() {

		if ($('#c_bf_1').is(":checked")) {
			$("#c_bs_1").prop("checked", true);
			// $("#c_bs_2").prop( "checked", true );	
			// $("#c_bs_3").prop( "checked", true );
		}

		if ($('#c_bf_2').is(":checked")) {
			$("#c_bs_2").prop("checked", true);
		}

		if ($('#c_bf_3').is(":checked")) {
			$("#c_bs_3").prop("checked", true);
		}

		if ($('#c_bf_4').is(":checked")) {
			$("#c_bs_4").prop("checked", true);
		}
		// } else if($('#c_bf_2').is(":checked")){
		// } else if($('#c_bf_3').is(":checked")){
		// }

		$(".plus").click(function() {
			$(this).toggleClass("minus").siblings("ul").toggle();
		})

		// $("input[type=checkbox]").click(function () {
		//     //alert($(this).attr("id"));
		//     //var sp = $(this).attr("id");
		//     //if (sp.substring(0, 4) === "c_bs" || sp.substring(0, 4) === "c_bf") {
		//         $(this).siblings("ul").find("input[type=checkbox]").prop('checked', $(this).prop('checked'));
		//     //}
		// })
		//    alert("ready - page loaded")
		var schedule_type = $('#schedule_type').val();
		// alert('test');
		if (schedule_type == "online") {
			document.getElementById("lokasi").style.display = "none";
			document.getElementById("hidden_menu").style.display = "block";
			document.getElementById("link_meeting").style.display = "block";
			document.getElementById("Training_Date").required = true;
			document.getElementById("Training_Time").required = true;
			document.getElementById("Link_Meeting").required = true;
		} else if (schedule_type == "offline") {
			document.getElementById("lokasi").style.display = "block";
			document.getElementById("hidden_menu").style.display = "block";
			document.getElementById("link_meeting").style.display = "none";
			document.getElementById("schedule_type").required = true;
			document.getElementById("lokasi").required = true;
			document.getElementById("Training_Date").required = true;
			document.getElementById("Training_Time").required = true;
		} else {
			document.getElementById("hidden_menu").style.display = "none";
			document.getElementById("link_meeting").style.display = "none";
		}
	})

	function checkAll(myCheckbox) {


		var checkboxes = document.querySelectorAll(`input[class = 'children${myCheckbox.className}']`);


		if (myCheckbox.checked == true) {
			checkboxes.forEach(function(checkbox) {
				checkbox.checked = true;
			});
		} else {
			checkboxes.forEach(function(checkbox) {
				checkbox.checked = false;
			});
		}
	}

	function checkparent(add, name) {

		var checkboxes = document.getElementsByClassName(name);

		if (add.checked == true) {
			Array.from(checkboxes).forEach(function(checkbox) {
				checkbox.checked = true;
			});

		}
	}
</script>
<script>
	function cekType(status) {
		var status = status;
		if (status == "online") {
			document.getElementById("lokasi").style.display = "none";
			document.getElementById("hidden_menu").style.display = "block";
			document.getElementById("link_meeting").style.display = "block";
			document.getElementById("Training_Date").required = true;
			document.getElementById("Training_Time").required = true;
			document.getElementById("Link_Meeting").required = true;
		} else if (status == "offline") {
			document.getElementById("lokasi").style.display = "block";
			document.getElementById("hidden_menu").style.display = "block";
			document.getElementById("link_meeting").style.display = "none";
			document.getElementById("schedule_type").required = true;
			document.getElementById("lokasi").required = true;
			document.getElementById("Training_Date").required = true;
			document.getElementById("Training_Time").required = true;
		} else {
			document.getElementById("hidden_menu").style.display = "none";
			document.getElementById("link_meeting").style.display = "none";
		}
	}
</script>