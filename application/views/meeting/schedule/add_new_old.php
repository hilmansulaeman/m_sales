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
<form method="post" action="<?php echo site_url('meeting/schedule/save2'); ?>">
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <h3 class="box-title">Form Add Schedule</h3>
        </div>
        <br />
        <!-- /.panel-heading -->
        <div class="panel-body">
            <?php //echo form_open('');
            ?>

            <?php // echo form_open_multipart('extimasi/do_upload');
            ?>
            <div class="form-group">
                <label>Schedule Type :</label>
                <select class="form-control" name="schedule_type" id="schedule_type" required onchange="cekType(this.value, '.<?php echo $position; ?>.')">
                    <option value="">--Pilih--</option>
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                </select>
            </div>
            <div class="form-group" id="tema">
                <label>Tema / Pembahasan :</label>
                <textarea class="form-control" name="tema" id="tema" required></textarea>
            </div>
            <div class="form-group" id="lokasi" style="display: none;">
                <label>Lokasi :</label>
                <input type="text" class="form-control" name="lokasi" id="lokasi" required>
                <!-- <select class="form-control" name="lokasi" id="lokasi">
                    <option value="">--Pilih--</option>
                    <?php
                    foreach ($query_lokasi->result() as $c) {
                        // echo "<option value='" . $c->Location_ID . "-" . $c->Location_Name . "'>" . $c->Location_Name . "</option>";
                    }
                    ?>
                </select> -->
            </div>
            <div id="hidden_menu" style="display:none;">

                <div class="form-group">
                    <label>Date :</label>
                    <!-- <input type="text" name="training_date" autocomplete="off" id="Training_Date" class="form-control tanggal"  placeholder="YYYY-MM-DD"/> -->
                    <div class="input-group date" style="display:flex; !important">
                        <!-- <input type="text" name="efective_date" class="form-control pull-right tanggal" id="ed" autocomplete="off" onChange="cekBulan(this.value)"> -->
                        <input type="date" name="training_date" class="form-control" id="Training_Date" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Jam :</label>
                    <input type="time" name="training_time" class="form-control" value="" id="Training_Time" />
                </div>
            </div>
            <div id="link_meeting" style="display: none;">
                <div class="form-group">
                    <label>Link Meeting :</label>
                    <input type="text" name="link_meeting" class="form-control" id="Link_Meeting" />
                </div>
            </div>
        </div>
    </div>
    <!-- <ul>
		<li>
			<details>
					<summary>First Item</summary>
					<ul class="nested">
						<li>Water</li>
						<li>Coffee</li>
						<li><span class="caret">Tea</span>
							<ul class="nested">
							<li>Black Tea</li>
							<li>White Tea</li>
							<li><span class="caret">Green Tea</span>
								<ul class="nested">
								<li>Sencha</li>
								<li>Gyokuro</li>
								<li>Matcha</li>
								<li>Pi Lo Chun</li>
								</ul>
							</li>
							</ul>
						</li>
					</ul>
			</details>
			
		</li>
	</ul> -->
    <label>Anggota :</label>
    <?php if ($position == 'BSH') { ?>
        <div class="tree_main">
            <ul id="bs_main" class="main_ul">
                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="1" type="checkbox" id="c_bs_1" onClick="checkAll(this)" value='1' />
                    <span>RSM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        foreach ($query_anggota_rsm->result() as $rsm) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children1" type="checkbox" id="c_bf_1" name="anggota[]" onClick="checkparent(this,'1')" value="<?php echo $rsm->DSR_Code; ?>-<?php echo $rsm->Name; ?>" />
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
                        foreach ($query_anggota_asm->result() as $asm) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children2" type="checkbox" id="c_bf_2" name="anggota[]" onClick="checkparent(this,'2')" value="<?php echo $asm->DSR_Code; ?>-<?php echo $asm->Name; ?>" />
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
                        foreach ($query_anggota_spv->result() as $spv) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children3" type="checkbox" id="c_bf_3" name="anggota[]" onClick="checkparent(this,'3')" value="<?php echo $spv->DSR_Code; ?>-<?php echo $spv->Name; ?>" />
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
                        foreach ($query_anggota_dsr->result() as $dsr) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children4" type="checkbox" id="c_bf_4" name="anggota[]" onClick="checkparent(this,'4')" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" />
                                <span><?php echo $dsr->Name; ?></span>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    <?php } elseif ($position == 'RSM') { ?>
        <div class="tree_main">
            <ul id="bs_main" class="main_ul">
                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="2" type="checkbox" id="c_bs_2" onClick="checkAll(this)" value='2' />
                    <span>ASM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        foreach ($query_anggota_asm->result() as $asm) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children2" type="checkbox" id="c_bf_2" name="anggota[]" onClick="checkparent(this,'2')" value="<?php echo $asm->DSR_Code; ?>-<?php echo $asm->Name; ?>" />
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
                        foreach ($query_anggota_spv->result() as $spv) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children3" type="checkbox" id="c_bf_3" name="anggota[]" onClick="checkparent(this,'3')" value="<?php echo $spv->DSR_Code; ?>-<?php echo $spv->Name; ?>" />
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
                        foreach ($query_anggota_dsr->result() as $dsr) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children4" type="checkbox" id="c_bf_4" name="anggota[]" onClick="checkparent(this,'4')" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" />
                                <span><?php echo $dsr->Name; ?></span>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    <?php } elseif ($position == 'ASM') { ?>
        <div class="tree_main">
            <ul id="bs_main" class="main_ul">
                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="3" type="checkbox" id="c_bs_3" onClick="checkAll(this)" value='3' />
                    <span>SPV</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        foreach ($query_anggota_spv->result() as $spv) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children3" type="checkbox" id="c_bf_3" name="anggota[]" onClick="checkparent(this,'3')" value="<?php echo $spv->DSR_Code; ?>-<?php echo $spv->Name; ?>" />
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
                        foreach ($query_anggota_dsr->result() as $dsr) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children4" type="checkbox" id="c_bf_4" name="anggota[]" onClick="checkparent(this,'4')" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" />
                                <span><?php echo $dsr->Name; ?></span>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    <?php } elseif ($position == 'SPV') { ?>
        <div class="tree_main">
            <ul id="bs_main" class="main_ul">
                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="4" type="checkbox" id="c_bs_4" onClick="checkAll(this)" value='4' />
                    <span>DSR</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        foreach ($query_anggota_dsr->result() as $dsr) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children4" type="checkbox" id="c_bf_4" name="anggota[]" onClick="checkparent(this,'4')" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" />
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

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script> -->


<script type="text/javascript">
    $(document).ready(function() {
        $(".plus").click(function() {
            $(this).toggleClass("minus").siblings("ul").toggle();
        })

        $("input[type=checkbox]").click(function() {
            //alert($(this).attr("id"));
            //var sp = $(this).attr("id");
            //if (sp.substring(0, 4) === "c_bs" || sp.substring(0, 4) === "c_bf") {
            $(this).siblings("ul").find("input[type=checkbox]").prop('checked', $(this).prop('checked'));
            //}
        })

        // $("input[type=checkbox]").change(function () {
        //     var sp = $(this).attr("id");
        //     if (sp.substring(0, 4) === "c_io") {
        //         var ff = $(this).parents("ul[id^=bf_l]").attr("id");
        //         if ($('#' + ff + ' > li input[type=checkbox]:checked').length == $('#' + ff + ' > li input[type=checkbox]').length) {
        //             $('#' + ff).siblings("input[type=checkbox]").prop('checked', true);
        //             check_fst_lvl(ff);
        //         }
        //         else {
        //             $('#' + ff).siblings("input[type=checkbox]").prop('checked', false);
        //             check_fst_lvl(ff);
        //         }
        //     }

        //     if (sp.substring(0, 4) === "c_bf") {
        //         var ss = $(this).parents("ul[id^=bs_l]").attr("id");
        //         if ($('#' + ss + ' > li input[type=checkbox]:checked').length == $('#' + ss + ' > li input[type=checkbox]').length) {
        //             $('#' + ss).siblings("input[type=checkbox]").prop('checked', true);
        //             check_fst_lvl(ss);
        //         }
        //         else {
        //             $('#' + ss).siblings("input[type=checkbox]").prop('checked', false);
        //             check_fst_lvl(ss);
        //         }
        //     }
        // });

    })

    function pageLoad() {
        $(".plus").click(function() {
            $(this).toggleClass("minus").siblings("ul").toggle();
        })
    }

    // $('#jstree').jstree();
    // $(document).ready(function(){
    // 	$('#treeview_json').treeview();

    // });
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

<!-- <script>
var toggler = document.getElementsByClassName("caret");
var i;

for (i = 0; i < toggler.length; i++) {
  toggler[i].addEventListener("click", function() {
    this.parentElement.querySelector(".nested").classList.toggle("active");
    this.classList.toggle("caret-down");
  });
}
</script> -->


<script>
    function cekType(status, position) {
        var status = status;
        var position = position;
        if (status == "online") {
            // $("#other_recruiter").style.display = "block";
            document.getElementById("lokasi").style.display = "none";
            document.getElementById("hidden_menu").style.display = "block";
            document.getElementById("link_meeting").style.display = "block";
            // document.getElementById("tema").style.display = "block";
            document.getElementById("Training_Date").required = true;
            document.getElementById("Training_Time").required = true;
            document.getElementById("Link_Meeting").required = true;
            if (position == 'BSH') {
                document.getElementById("c_bs_1").required = true;
            } else if (position == 'RSM') {
                document.getElementById("c_bs_2").required = true;
            } else if (position == 'ASM') {
                document.getElementById("c_bs_3").required = true;
            }
            // document.getElementById("training_date").style.display = "block";
            // document.getElementById("time_meeting").style.display = "block";
            // document.getElementById("Training_Time").required = true;
            // document.getElementById("time_meeting").style.display = "block";
            // document.getElementById("Training_Time").required = true;

        } else if (status == "offline") {

            document.getElementById("lokasi").style.display = "block";
            document.getElementById("hidden_menu").style.display = "block";
            document.getElementById("link_meeting").style.display = "none";
            // document.getElementById("tema").style.display = "block";
            document.getElementById("schedule_type").required = true;
            document.getElementById("lokasi").required = true;
            document.getElementById("Training_Date").required = true;
            document.getElementById("Training_Time").required = true;
            // document.getElementById("Total_Of_Child").required = false;
        } else {
            document.getElementById("hidden_menu").style.display = "none";
            document.getElementById("link_meeting").style.display = "none";
        }
    }
</script>

<script>
    // $('#jstree').jstree({
    // 		"core":{"multiple":false,
    // 				"animation":0
    // 		},
    // 		"plugins":["checkbox"]

    // 	});
    //   $(function () {
    //     // 6 create an instance when the DOM is ready
    //     $('#jstree').jstree({
    // 		'core': {
    // 			"themes":{
    // 				'icons':false,
    // 			}
    // 		},
    // 		'plugins':['checkbox'],

    // 	});
    //     // 7 bind to events triggered on the tree
    //     // $('#jstree').on("changed.jstree", function (e, data) {
    //     //   console.log(data.selected);
    //     // });
    //     // // 8 interact with the tree - either way is OK
    //     $('#jstree').on('changed.jstree', function (e,data) {
    // 		var arrName = '';
    // 		if(data.selected.length){
    // 			$(data.selected).each(function (idx){
    // 				var node = data.instance.get_node(data.selected[idx]);
    // 				// arrName[idx] = node.text;

    // 				arrName += node.text.trim()+'|';
    // 			});
    // 			console.log(arrName);
    // 		}

    //     });
    //   });
</script>