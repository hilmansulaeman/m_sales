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
            </div>
            <div id="hidden_menu" style="display:none;">

                <div class="form-group">
                    <label>Date :</label>
                    <div class="input-group date" style="display:flex; !important">
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

    <label>Anggota :</label>
    <?php if ($position == 'BSH') { ?>
        <div class="tree_main">
            <ul id="bs_main" class="main_ul">
                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="0" type="checkbox" id="c_bs_0" onClick="checkAll(this,'opsional0')" value='0' />
                    <span>GM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no0 = 1;
                        foreach ($query_atasan_gm->result() as $gm) { ?>
                            <li id="gm_0">
                                <table>
                                    <td><input class="superior" type="checkbox" id="c_gm_0" name="anggota[<?php echo $gm->DSR_Code; ?>]" onClick="checkparent(this,'0'); toggleDisable(this, 'opsional0<?= $no0 ?>');" value="<?php echo $gm->DSR_Code; ?>-<?php echo $gm->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $gm->Name; ?></td>
                                    <td><input class="opsional0<?= $no0++ ?>" type="checkbox" id="opsional0<?= $no0++ ?>" name="opsional[<?php echo $gm->DSR_Code; ?>]" value="<?php echo $gm->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="1" type="checkbox" id="c_bs_1" onClick="checkAll(this,'opsional1');" value='1' />
                    <span>RSM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no1= 1;
                        foreach ($query_anggota_rsm->result() as $rsm) { ?>
                            <!-- <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children1" type="checkbox" id="c_bf_1" name="anggota[]" onClick="checkparent(this,'1')" value="<?php echo $rsm->DSR_Code; ?>-<?php echo $rsm->Name; ?>" />
                                <span><?php echo $rsm->Name; ?></span>
                            </li> -->

                            <li id="bf_1">
                                <table>
                                    <tr>
                                        <td><input class="children1" type="checkbox" id="c_bf_1" name="anggota[<?php echo $rsm->DSR_Code; ?>]" onClick="checkparent(this,'1'); toggleDisable(this, 'opsional1<?= $no1 ?>');" value="<?php echo $rsm->DSR_Code; ?>-<?php echo $rsm->Name; ?>" /></td>
                                        <td width="300">&nbsp; <?php echo $rsm->Name; ?></td>
                                        <td><input class="opsional1<?= $no1++ ?>" type="checkbox" id="opsional1<?= $no1++ ?>" name="opsional[<?php echo $rsm->DSR_Code; ?>]" value="<?php echo $rsm->DSR_Code; ?>" disabled/></td>
                                        <td>&nbsp; Opsional</td>
                                    </tr>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                
                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="2" type="checkbox" id="c_bs_2" onClick="checkAll(this,'opsional2');" value='2' />
                    <span>ASM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                         $no2 = 1;
                         foreach ($query_anggota_asm->result() as $asm) { ?>
                             <li id="bf_2">
                                 <table>
                                     <td><input class="children2" type="checkbox" id="c_bf_2" name="anggota[<?php echo $asm->DSR_Code; ?>]" onClick="checkparent(this,'2'); toggleDisable(this, 'opsional2<?= $no2 ?>');" value="<?php echo $asm->DSR_Code; ?>-<?php echo $asm->Name; ?>" /></td>
                                     <td width="300">&nbsp; <?php echo $asm->Name; ?></td>
                                     <td><input class="opsional2<?= $no2++ ?>" type="checkbox" id="opsional2<?= $no2++ ?>" name="opsional[<?php echo $asm->DSR_Code; ?>]" value="<?php echo $asm->DSR_Code; ?>" disabled/></td>
                                     <td>&nbsp; Opsional</td>
                                 </table>
                             </li>
                        <?php } ?>
                    </ul>
                </li>
                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="3" type="checkbox" id="c_bs_3" onClick="checkAll(this,'opsional3');" value='3' />
                    <span>SPV</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <!-- <?php
                        foreach ($query_anggota_spv->result() as $spv) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children3" type="checkbox" id="c_bf_3" name="anggota[]" onClick="checkparent(this,'3')" value="<?php echo $spv->DSR_Code; ?>-<?php echo $spv->Name; ?>" />
                                <span><?php echo $spv->Name; ?></span>
                            </li>
                        <?php } ?> -->
                        <?php
                            $no3 = 1;
                            foreach ($query_anggota_spv->result() as $spv) { ?>
                            <li id="bf-3">
                                <table>
                                    <td><input class="children3" type="checkbox" id="c_bf_3" name="anggota[<?php echo $spv->DSR_Code; ?>]" onClick="checkparent(this,'3'); toggleDisable(this, 'opsional3<?= $no3 ?>');" value="<?php echo $spv->DSR_Code; ?>-<?php echo $spv->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $spv->Name; ?></td>
                                    <td><input class="opsional3<?= $no3++ ?>" type="checkbox" id="opsional3<?= $no3++ ?>" name="opsional[<?php echo $spv->DSR_Code; ?>]" value="<?php echo $spv->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="4" type="checkbox" id="c_bs_4" onClick="checkAll(this, 'opsional4')" value='4' />
                    <span>DSR</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <!-- <?php
                        foreach ($query_anggota_dsr->result() as $dsr) { ?>
                            <li id="bf_1">
                                <span>&nbsp;</span>
                                <input class="children4" type="checkbox" id="c_bf_4" name="anggota[]" onClick="checkparent(this,'4')" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" />
                                <span><?php echo $dsr->Name; ?></span>
                            </li>
                        <?php } ?> -->
                        <?php
                            $no4 = 1; 
                            foreach ($query_anggota_dsr->result() as $dsr) { ?>
                            <li id="bf-4">
                                <table>
                                    <tr>
                                        <td><input class="children4" type="checkbox" id="c_bf_4" name="anggota[<?php echo $dsr->DSR_Code; ?>]" onClick="checkparent(this,'4'); toggleDisable(this, 'opsional4<?= $no4 ?>');" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" /></td>
                                        <td width="300">&nbsp; <?php echo $dsr->Name; ?></td>
                                        <td><input class="opsional4<?= $no4++ ?>" type="checkbox" id="opsional4<?= $no4++ ?>" name="opsional[<?php echo $dsr->DSR_Code; ?>]" value="<?php echo $dsr->DSR_Code; ?>" disabled/></td>
                                        <td>&nbsp; Opsional</td>
                                    </tr>
                                </table>
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
                    <input class="0" type="checkbox" id="c_bs_0" onClick="checkAll(this,'opsional0')" value='0' />
                    <span>GM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no0 = 1;
                        foreach ($query_atasan_gm->result() as $gm) { ?>
                            <li id="gm_0">
                                <table>
                                    <td><input class="superior" type="checkbox" id="c_gm_0" name="anggota[<?= $gm->DSR_Code; ?>]" onClick="checkparent(this,'0'); toggleDisable(this, 'opsional0<?= $no0 ?>');" value="<?php echo $gm->DSR_Code; ?>-<?php echo $gm->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $gm->Name; ?></td>
                                    <td><input class="opsional0<?= $no0++ ?>" type="checkbox" id="opsional0<?= $no0++ ?>" name="opsional[<?= $gm->DSR_Code; ?>]" value="<?php echo $gm->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="1" type="checkbox" id="c_bs_1" onClick="checkAll(this,'opsional1')" value='1' />
                    <span>BSH</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no1 = 1;
                        foreach ($query_atasan_bsh->result() as $bsh) { ?>
                            <li id="bsh_1">
                                <table>
                                    <td><input class="superior1" type="checkbox" id="c_bsh_1" name="anggota[<?= $bsh->DSR_Code?>]" onClick="checkparent(this,'1'); toggleDisable(this, 'opsional1<?= $no1 ?>');" value="<?php echo $bsh->DSR_Code; ?>-<?php echo $bsh->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $bsh->Name; ?></td>
                                    <td><input class="opsional1<?= $no1++ ?>" type="checkbox" id="opsional1<?= $no1++ ?>" name="opsional[<?= $bsh->DSR_Code; ?>]" value="<?php echo $bsh->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>



                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="2" type="checkbox" id="c_bs_2" onClick="checkAll(this,'opsional2');" value='2' />
                    <span>ASM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no2 = 1;
                        foreach ($query_anggota_asm->result() as $asm) { 
                            ?>
                            <li id="bf_2">
                                <table>
                                    <td><input class="children2" type="checkbox" id="c_bf_2" name="anggota[<?= $asm->DSR_Code?>]" onClick="checkparent(this,'2'); toggleDisable(this, 'opsional2<?= $no2 ?>');" value="<?php echo $asm->DSR_Code; ?>-<?php echo $asm->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $asm->Name; ?></td>
                                    <td><input class="opsional2<?= $no2++ ?>" type="checkbox" id="opsional2<?= $no2++ ?>" name="opsional[<?= $asm->DSR_Code; ?>]" value="<?php echo $asm->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>

                </li>
                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="3" type="checkbox" id="c_bs_3" onClick="checkAll(this,'opsional3');" value='3' />
                    <span>SPV</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no3 = 1;
                        foreach ($query_anggota_spv->result() as $spv) { ?>
                            <li id="bf-3">
                                <table>
                                    <td><input class="children3" type="checkbox" id="c_bf_3" name='anggota[<?php echo $spv->DSR_Code; ?>]' onClick="checkparent(this,'3'); toggleDisable(this, 'opsional3<?= $no3 ?>');" value="<?php echo $spv->DSR_Code; ?>-<?php echo $spv->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $spv->Name; ?></td>
                                    <td><input class="opsional3<?= $no3++ ?>" type="checkbox" id="opsional3<?= $no3++ ?>" name="opsional[<?php echo $spv->DSR_Code; ?>]" value="<?php echo $spv->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="4" type="checkbox" id="c_bs_4" onClick="checkAll(this, 'opsional4')" value='4' />
                    <span>DSR</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no4 = 1; 
                        foreach ($query_anggota_dsr->result() as $dsr) { ?>
                            <li id="bf-4">
                                <table>
                                    <tr>
                                        <td><input class="children4" type="checkbox" id="c_bf_4" name="anggota[<?php echo $dsr->DSR_Code; ?>]" onClick="checkparent(this,'4'); toggleDisable(this, 'opsional4<?= $no4 ?>');" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" /></td>
                                        <td width="300">&nbsp; <?php echo $dsr->Name; ?></td>
                                        <td><input class="opsional4<?= $no4++ ?>" type="checkbox" id="opsional4<?= $no4++ ?>" name="opsional[<?php echo $dsr->DSR_Code; ?>]" value="<?php echo $dsr->DSR_Code; ?>" disabled/></td>
                                        <td>&nbsp; Opsional</td>
                                    </tr>
                                </table>
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
                    <input class="0" type="checkbox" id="c_bs_0" onClick="checkAll(this,'opsional0')" value='0' />
                    <span>GM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no0 = 1;
                        foreach ($query_atasan_gm->result() as $gm) { ?>
                            <li id="gm_0">
                                <table>
                                    <td><input class="superior" type="checkbox" id="c_gm_0" name="anggota[<?php echo $gm->DSR_Code; ?>]" onClick="checkparent(this,'0'); toggleDisable(this, 'opsional0<?= $no0 ?>');" value="<?php echo $gm->DSR_Code; ?>-<?php echo $gm->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $gm->Name; ?></td>
                                    <td><input class="opsional0<?= $no0++ ?>" type="checkbox" id="opsional0<?= $no0++ ?>" name="opsional[<?php echo $gm->DSR_Code; ?>]" value="<?php echo $gm->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="1" type="checkbox" id="c_bs_1" onClick="checkAll(this,'opsional1')" value='1' />
                    <span>BSH</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no1 = 1;
                        foreach ($query_atasan_bsh->result() as $bsh) { ?>
                            <li id="bsh_1">
                                <table>
                                    <td><input class="superior1" type="checkbox" id="c_bsh_1" name="anggota[<?php echo $bsh->DSR_Code; ?>]" onClick="checkparent(this,'1'); toggleDisable(this, 'opsional1<?= $no1 ?>');" value="<?php echo $bsh->DSR_Code; ?>-<?php echo $bsh->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $bsh->Name; ?></td>
                                    <td><input class="opsional1<?= $no1++ ?>" type="checkbox" id="opsional1<?= $no1++ ?>" name="opsional[<?php echo $bsh->DSR_Code; ?>]" value="<?php echo $bsh->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="2" type="checkbox" id="c_bs_2" onClick="checkAll(this, 'opsional2')" value='2' />
                    <span>RSM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no2 = 1;
                        foreach ($query_atasan_rsm->result() as $asm) { ?>
                            <li id="rsm_2">
                                <table>
                                    <td><input class="superior2" type="checkbox" id="c_rsm_2" name="anggota[<?php echo $asm->DSR_Code; ?>]" onClick="checkparent(this,'2'); toggleDisable(this, 'opsional2<?= $no2 ?>');" value="<?php echo $asm->DSR_Code; ?>-<?php echo $asm->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $asm->Name; ?></td>
                                    <td><input class="opsional2<?= $no2++ ?>" type="checkbox" id="opsional2<?= $no2++ ?>" name="opsional[<?php echo $asm->DSR_Code; ?>]" value="<?php echo $asm->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>


                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="3" type="checkbox" id="c_bs_3" onClick="checkAll(this,'opsional3');" value='3' />
                    <span>SPV</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no3 = 1;
                        foreach ($query_anggota_spv->result() as $spv) { ?>
                            <li id="bf-3">
                                <table>
                                    <td><input class="children3" type="checkbox" id="c_bf_3" name="anggota[<?php echo $spv->DSR_Code; ?>]" onClick="checkparent(this,'3'); toggleDisable(this, 'opsional3<?= $no3 ?>');" value="<?php echo $spv->DSR_Code; ?>-<?php echo $spv->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $spv->Name; ?></td>
                                    <td><input class="opsional3<?= $no3++ ?>" type="checkbox" id="opsional3<?= $no3++ ?>" name="opsional[<?php echo $spv->DSR_Code; ?>]" value="<?php echo $spv->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="4" type="checkbox" id="c_bs_4" onClick="checkAll(this, 'opsional4')" value='4' />
                    <span>DSR</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no4 = 1; 
                        foreach ($query_anggota_dsr->result() as $dsr) { ?>
                            <li id="bf-4">
                                <table>
                                    <tr>
                                        <td><input class="children4" type="checkbox" id="c_bf_4" name="anggota[<?php echo $dsr->DSR_Code; ?>]" onClick="checkparent(this,'4'); toggleDisable(this, 'opsional4<?= $no4 ?>');" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" /></td>
                                        <td width="300">&nbsp; <?php echo $dsr->Name; ?></td>
                                        <td><input class="opsional4<?= $no4++ ?>" type="checkbox" id="opsional4<?= $no4++ ?>" name="opsional[<?php echo $dsr->DSR_Code; ?>]" value="<?php echo $dsr->DSR_Code; ?>" disabled/></td>
                                        <td>&nbsp; Opsional</td>
                                    </tr>
                                </table>
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
                    <input class="0" type="checkbox" id="c_bs_0" onClick="checkAll(this,'opsional0')" value='0' />
                    <span>GM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no0 = 1;
                        foreach ($query_atasan_gm->result() as $gm) { ?>
                            <li id="gm_0">
                                <table>
                                    <td><input class="superior" type="checkbox" id="c_gm_0" name="anggota[<?php echo $gm->DSR_Code; ?>]" onClick="checkparent(this,'0'); toggleDisable(this, 'opsional0<?= $no0 ?>');" value="<?php echo $gm->DSR_Code; ?>-<?php echo $gm->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $gm->Name; ?></td>
                                    <td><input class="opsional0<?= $no0++ ?>" type="checkbox" id="opsional0<?= $no0++ ?>" name="opsional[<?php echo $gm->DSR_Code; ?>]" value="<?php echo $gm->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="1" type="checkbox" id="c_bs_1" onClick="checkAll(this,'opsional1');" value='1' />
                    <span>BSH</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no1 = 1;
                        foreach ($query_atasan_bsh->result() as $bsh) { ?>
                            <li id="bsh_1">
                                <table>
                                    <td><input class="superior1" type="checkbox" id="c_bsh_1" name="anggota[<?php echo $bsh->DSR_Code; ?>]" onClick="checkparent(this,'1'); toggleDisable(this, 'opsional1<?= $no1 ?>');" value="<?php echo $bsh->DSR_Code; ?>-<?php echo $bsh->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $bsh->Name; ?></td>
                                    <td><input class="opsional1<?= $no1++ ?>" type="checkbox" id="opsional1<?= $no1++ ?>" name="opsional[<?php echo $bsh->DSR_Code; ?>]" value="<?php echo $bsh->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="2" type="checkbox" id="c_bs_2" onClick="checkAll(this,'opsional2');" value='2' />
                    <span>RSM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no1= 1;
                        foreach ($query_atasan_rsm->result() as $rsm) { ?>
                            <li id="bf_2">
                                <table>
                                    <tr>
                                        <td><input class="superior2" type="checkbox" id="c_bf_2" name="anggota[<?php echo $rsm->DSR_Code; ?>]" onClick="checkparent(this,'2'); toggleDisable(this, 'opsional2<?= $no1 ?>');" value="<?php echo $rsm->DSR_Code; ?>-<?php echo $rsm->Name; ?>" /></td>
                                        <td width="300">&nbsp; <?php echo $rsm->Name; ?></td>
                                        <td><input class="opsional2<?= $no1++ ?>" type="checkbox" id="opsional2<?= $no1++ ?>" name="opsional[<?php echo $rsm->DSR_Code; ?>]" value="<?php echo $rsm->DSR_Code; ?>" disabled/></td>
                                        <td>&nbsp; Opsional</td>
                                    </tr>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="3" type="checkbox" id="c_bs_3" onClick="checkAll(this,'opsional3');" value='3' />
                    <span>ASM</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no2 = 1;
                        foreach ($query_atasan_asm->result() as $asm) { 
                            ?>
                            <li id="bf_3">
                                <table>
                                    <td><input class="superior3" type="checkbox" id="c_bf_3" name="anggota[<?= $asm->DSR_Code?>]" onClick="checkparent(this,'3'); toggleDisable(this, 'opsional3<?= $no2 ?>');" value="<?php echo $asm->DSR_Code; ?>-<?php echo $asm->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $asm->Name; ?></td>
                                    <td><input class="opsional3<?= $no2++ ?>" type="checkbox" id="opsional3<?= $no2++ ?>" name="opsional[<?= $asm->DSR_Code; ?>]" value="<?php echo $asm->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
                            </li>
                        <?php } ?>
                    </ul>

                </li> 

                <li id="bs_1">
                    <span class="plus">&nbsp;</span>
                    <input class="4" type="checkbox" id="c_bs_4" onClick="checkAll(this,'opsional4');" value='4' />
                    <span>DSR</span>
                    <ul id="bs_l_1" class="sub_ul" style="display: none">
                        <?php
                        $no4 = 1;
                        foreach ($query_anggota_dsr->result() as $dsr) { ?>
                            <li id="bf_1">
                                <table>
                                    <td><input class="children4" type="checkbox" id="c_bf_4" name="anggota[<?= $dsr->DSR_Code?>]" onClick="checkparent(this,'4'); toggleDisable(this, 'opsional4<?= $no4 ?>');" value="<?php echo $dsr->DSR_Code; ?>-<?php echo $dsr->Name; ?>" /></td>
                                    <td width="300">&nbsp; <?php echo $dsr->Name; ?></td>
                                    <td><input class="opsional4<?= $no4++ ?>" type="checkbox" id="opsional4<?= $no4++ ?>" name="opsional[<?= $dsr->DSR_Code; ?>]" value="<?php echo $dsr->DSR_Code; ?>" disabled/></td>
                                    <td>&nbsp; Opsional</td>
                                </table>
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

    })

    function pageLoad() {
        $(".plus").click(function() {
            $(this).toggleClass("minus").siblings("ul").toggle();
        })
    }

   
    // check box old

    function checkAll(myCheckbox, className) {
        var checkboxes = document.querySelectorAll(`input[class = 'children${myCheckbox.className}']`);
        var opsionalCheckboxes = document.querySelectorAll(`input[class^='${className}']`);

        if (myCheckbox.checked == true) {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
            opsionalCheckboxes.forEach(function (checkbox) {
                checkbox.disabled = false;

            });
        } else {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
            opsionalCheckboxes.forEach(function (checkbox) {
                checkbox.disabled = true;
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

    function toggleDisable(clickedCheckbox, checkboxClass) {
        var opsionalCheckboxes = document.querySelectorAll('.' + checkboxClass);

        opsionalCheckboxes.forEach(function (checkbox) {
            checkbox.disabled = !clickedCheckbox.checked;

            if (!clickedCheckbox.checked && checkbox.disabled) {
                checkbox.checked = false;
            }
        });
    }

</script>



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