<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$rows = $query;
$recruitment_id = $rows->Recruitment_ID;
$current_position = $this->session->userdata('position');
/* create array so we can name months */
$monthName = array(
    01 => "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember"
);

$dob = strtotime($rows->Date_Of_Birth);
$useDate = time();

function checked_verification($recruitment_id, $checklist_id)
{
    $ci = &get_instance();
    $get_checklist_verification = $ci->db->query("select * from data_recruitment_verification
        where Recruitment_ID = '$recruitment_id' and Checklist_ID = '$checklist_id'");
    $result = $get_checklist_verification->num_rows();
    if ($result > 0) {
        $checkbox = 'checked';
    } else {
        $checkbox = '';
    }
    return $checkbox;
}

?>

<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Dashboard, <?php echo  $this->session->userdata('position'); ?>, <?php echo  $this->session->userdata('sl_code'); ?> </a></li>
        <li class="breadcrumb-item"><a href="<?php echo site_url('recruitment_approval'); ?>">Verifikasi</a></li>
        <li class="breadcrumb-item active">Detail Data</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        Detail Data
    </h1>
    <!-- end page-header -->

    <form action="#" class="form-horizontal" id="applicant_form" data-parsley-validate="true">
        <input type="hidden" name="Recruitment_ID" id="Recruitment_ID" value="<?php echo $recruitment_id; ?>" />
        <input type="hidden" name="Current_Position" id="Current_Position" value="<?php echo $current_position ?>" />

        <?php if ($this->session->flashdata('message')) : ?>
            <div class="alert alert-danger alert-dismissible" id="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Danger!</strong> <?= $this->session->flashdata('message') ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('messageBerhasil')) : ?>
            <div class="alert alert-success alert-dismissible" id="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Success!</strong> <?= $this->session->flashdata('messageBerhasil') ?>
            </div>
        <?php endif; ?>
        <!-- begin row -->
        <div class="row">
            <!-- begin col-6 -->
            <div class="col-lg-6">
                <!-- begin panel -->
                <div class="panel panel-primary">
                    <!-- begin panel-heading -->
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        </div>
                        <h4 class="panel-title">Data</h4>
                    </div>
                    <!-- end panel-heading -->

                    <!-- begin panel-body -->
                    <div class="panel-body" style="overflow-y: scroll; height:2000px;">
                        <!-- begin hljs-wrapper -->
                        <div class="hljs-wrapper">
                            <strong>Recruiter</strong>
                        </div>
                        <!-- end hljs-wrapper -->
                        <br>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Source Recruiter :</label>
                            <div class="col-md-8">
                                <input type="text" name="Recruiter_Source" id="Recruiter_Source" value="<?php echo $rows->Recruiter_Source; ?>" class="form-control" data-parsley-required="true" readonly />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Recruiter :</label>
                            <div class="col-md-8">
                                <input type="text" name="Recruiter" id="Recruiter" value="<?php echo $rows->Recruiter; ?>" class="form-control" data-parsley-required="true" readonly />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Wawancara Oleh :</label>
                            <div class="col-md-8">
                                <input type="text" name="Interview_by" id="Interview_by" value="<?php echo $rows->Interview_by ?>" class="form-control" data-parsley-required="true" readonly />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Hasil Wawancara :</label>
                            <div class="col-md-8">
                                <?php
                                $interview_result_ = $rows->Interview_Result;
                                if ($interview_result_ == '0') {
                                    $interview_result = 'Tidak Lulus';
                                } else {
                                    $interview_result = 'Lulus';
                                }
                                ?>
                                <input type="text" name="Interview_Result" id="Interview_Result" value="<?php echo $interview_result; ?>" class="form-control" data-parsley-required="true" readonly />
                            </div>
                        </div>

                        <!-- begin hljs-wrapper -->
                        <div class="hljs-wrapper">
                            <strong>Data Personal</strong>
                        </div>
                        <!-- end hljs-wrapper -->
                        <br>
                        <?php
                        $photo = $rows->Image_Photo;
                        if ($photo == '') {
                            $foto = 'noimage.gif';
                        } else {
                            $foto = $photo;
                        } ?>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nama : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Name" id="Name" value="<?php echo $rows->Name ?>" class="form-control" data-parsley-required="true" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Tempat Lahir : <span class="text-danger">*</span></label>
                            <div class="col-md-8">

                                <input type="text" name="Place_Of_Birth" id="Place_Of_Birth" value="<?php echo $rows->Place_Of_Birth ?>" class="form-control" data-parsley-required="true" disabled />

                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Tgl Lahir : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <?php
                                /* make day selector */
                                echo "<table><tr><td valign='top'><select name='Date_Of_Birth' id='Date_Of_Birth' class='form-control' disabled>\n";
                                echo "<option value=\"\">Tanggal";
                                for ($currentDay = 1; $currentDay <= 31; $currentDay++) {
                                    $length = strlen($currentDay);
                                    if ($length == 1)
                                        $day = "0" . $currentDay;
                                    else
                                        $day = $currentDay;

                                    echo "<option value=\"$day\"";
                                    if (intval(date("d", $dob)) == $day) {
                                        echo " selected";
                                    }
                                    echo ">$day\n";
                                }
                                echo "</select></td>&nbsp;";

                                /* make month selector */
                                echo "<td><select name='Month_Of_Birth' id='Month_Of_Birth' class='form-control' disabled>\n";
                                echo "<option value=\"\">Bulan";
                                for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
                                    $lengMonth = strlen($currentMonth);
                                    if ($lengMonth == 1)
                                        $month = "0" . $currentMonth;
                                    else
                                        $month = $currentMonth;

                                    echo "<option value=\"$month\"";
                                    if (intval(date("m", $dob)) == $month) {
                                        echo " selected";
                                    }
                                    echo ">$monthName[$currentMonth]\n";
                                }
                                echo "</select></td>&nbsp;";

                                /* make year selector */
                                echo "<td><select name='Year_Of_Birth' id='Year_Of_Birth' class='form-control' disabled>\n";
                                echo "<option value=\"\">Tahun";
                                $startYear = date("Y", $useDate);
                                for ($currentYear = $startYear - 80; $currentYear <= $startYear; $currentYear++) {
                                    echo "<option value=\"$currentYear\"";
                                    if (date("Y", $dob) == $currentYear) {
                                        echo " selected";
                                    }
                                    echo ">$currentYear\n";
                                }
                                echo "</select></td></tr></table>";
                                ?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nomor KTP : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="ID_Number" id="ID_Number" value="<?php echo $rows->ID_Number; ?>" class="form-control" data-parsley-required="true" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="16" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nomor Telp Rumah :</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="Home_Phone_Number_Code" id="Home_Phone_Number_Code" value="<?php echo $rows->Home_Phone_Number_Code ?>" class="form-control" data-parsley-required="true" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="13" disabled />
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="Home_Phone_Number" id="Home_Phone_Number" value="<?php echo $rows->Home_Phone_Number ?>" class="form-control" data-parsley-required="true" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="13" disabled />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nomor HP : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Mobile_Phone_Number" id="Mobile_Phone_Number" value="<?php echo $rows->Mobile_Phone_Number ?>" class="form-control" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="13" data-parsley-required="true" disabled />
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Email : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Email" id="Email" value="<?php echo $rows->Email; ?>" class="form-control" data-parsley-required="true" disabled />
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Facebook :</label>
                            <div class="col-md-8">
                                <input type="text" name="FaceBook" id="FaceBook" value="<?php echo $rows->FaceBook; ?>" class="form-control" data-parsley-required="true" disabled />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Twitter :</label>
                            <div class="col-md-8">
                                <input type="text" name="Twitter" id="Twitter" value="<?php echo $rows->Twitter; ?>" class="form-control" data-parsley-required="true" disabled />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Instagram :</label>
                            <div class="col-md-8">
                                <input type="text" name="Instagram" id="Instagram" value="<?php echo $rows->Instagram; ?>" class="form-control" data-parsley-required="true" disabled />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Tinggi Badan :<code>/ Cm</code></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" id="Body_Height" name="Body_Height" value="<?php echo $rows->Body_Height; ?>" pattern=".{1,3}" required title="1 to 3 characters" maxlength="3" class="form-control" disabled />
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </div>
                                    <?php echo form_error('Body_Height'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Berat Badan :<code>/ Kg</code></label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" id="Body_Weight" name="Body_Weight" value="<?php echo $rows->Body_Weight; ?>" onkeyup="validAngkatile(this)" pattern=".{1,3}" required title="1 to 3 characters" maxlength="3" class="form-control" disabled />
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </div>
                                    <?php echo form_error('Body_Weight'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Ibu Kandung :</label>
                            <div class="col-md-8">
                                <input type="text" name="Mother_Maid" id="Mother_Maid" value="<?php echo $rows->Mother_Maid ?>" class="form-control" data-parsley-required="true" disabled />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Agama : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Religion" id="Religion" value="<?php echo $rows->Religion ?>" class="form-control" data-parsley-required="true" disabled />


                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Status Pernikahan : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Marital_Status" id="Marital_Status" value="<?php echo $rows->Marital_Status ?>" class="form-control" data-parsley-required="true" disabled />


                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Jumlah Anak :</label>
                            <div class="col-md-8">
                                <input type="text" name="Total_Of_Child" id="Total_Of_Child" value="<?php echo $rows->Total_Of_Child; ?>" class="form-control" data-parsley-required="true" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" disabled />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Alamat Sesuai KTP : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea name="Address_By_ID" id="Address_By_ID" class="form-control" rows="3" disabled><?php echo $rows->Address_By_ID; ?></textarea>
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label"></label>
                            <div class="col-md-8">
                                <table>
                                    <tr>
                                        <td>RT:</td>
                                        <td>
                                            <input type="text" name="Address_ID_RT" id="Address_ID_RT" value="<?php echo $rows->Address_ID_RT; ?>" class="form-control" data-parsley-required="true" disabled />
                                            <span class="help-block"></span>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>RW:</td>
                                        <td>
                                            <input type="text" name="Address_ID_RW" id="Address_ID_RW" value="<?php echo $rows->Address_ID_RW; ?>" class="form-control" data-parsley-required="true" disabled />
                                            <span class="help-block"></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kota : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Address_ID_City" id="Address_ID_City" class="default-select2 form-control" onChange="viewKecamatanKtp()" disabled="disabled" required>
                                    <option value="<?php echo $rows->Address_ID_City; ?>"><?php echo $rows->Address_ID_City; ?></option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kecamatan : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Address_ID_District" id="Address_ID_District" class="default-select2 form-control" onChange="viewKelurahanKtp()" disabled="disabled" required>
                                    <option value="<?php echo $rows->Address_ID_District; ?>"><?php echo $rows->Address_ID_District; ?></option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kelurahan : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Address_ID_Village" id="Address_ID_Village" class="default-select2 form-control" onChange="viewKodePosKtp()" disabled="disabled" required>
                                    <option value="<?php echo $rows->Address_ID_Village; ?>"><?php echo $rows->Address_ID_Village; ?></option>
                                </select>
                                <span class="help-block"></span>
                                <input type="hidden" name="Address_ID_Zip_Code" id="Address_ID_Zip_Code" value="<?php echo $rows->Address_ID_Zip_Code; ?>" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Alamat Tinggal : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea name="Address_By_Residence" id="Address_By_Residence" class="form-control" rows="3" disabled><?php echo $rows->Address_By_Residence; ?></textarea>
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label"></label>
                            <div class="col-md-8">
                                <table>
                                    <tr>
                                        <td>RT:</td>
                                        <td>
                                            <input type="text" name="Address_Residence_RT" id="Address_Residence_RT" value="<?php echo $rows->Address_Residence_RT; ?>" class="form-control" data-parsley-required="true" disabled />
                                            <span class="help-block"></span>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>RW:</td>
                                        <td>
                                            <input type="text" name="Address_Residence_RW" id="Address_Residence_RW" value="<?php echo $rows->Address_Residence_RW; ?>" class="form-control" data-parsley-required="true" disabled />
                                            <span class="help-block"></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Provinsi : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Address_Residence_Province" id="Address_Residence_Province" value="<?php echo $rows->Address_Residence_Province ?>" class="form-control" data-parsley-required="true" disabled />

                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kota : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Address_Residence_City" id="Address_Residence_City" class="default-select2 form-control" onChange="viewKecamatan2()" disabled="disabled" required>
                                    <option value="<?php echo $rows->Address_Residence_City; ?>"><?php echo $rows->Address_Residence_City; ?></option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kecamatan : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Address_Residence_District" id="Address_Residence_District" class="default-select2 form-control" onChange="viewKelurahan2()" disabled="disabled" required>
                                    <option value="<?php echo $rows->Address_Residence_District; ?>"><?php echo $rows->Address_Residence_District; ?></option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kelurahan : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Address_Residence_Village" id="Address_Residence_Village" class="default-select2 form-control" onChange="viewKodePos2()" disabled="disabled" required>
                                    <option value="<?php echo $rows->Address_Residence_Village; ?>"><?php echo $rows->Address_Residence_Village; ?></option>
                                </select>
                                <span class="help-block"></span>
                                <input type="hidden" name="Address_Residence_Zip_Code" id="Address_Residence_Zip_Code" value="<?php echo $rows->Address_Residence_Zip_Code; ?>" />
                            </div>
                        </div>

                        <!-- begin hljs-wrapper -->
                        <div class="hljs-wrapper">
                            <strong>Data Pendidikan</strong>
                        </div>
                        <!-- end hljs-wrapper -->
                        <br>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Pendidikan Terakhir :</label>
                            <div class="col-md-8">
                                <input type="text" name="Education" id="Education" value="<?php echo $rows->Education; ?>" class="form-control" data-parsley-required="true" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" disabled />



                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nama Sekolah :</label>
                            <div class="col-md-8">
                                <input type="text" name="School_Name" id="School_Name" value="<?php echo $rows->School_Name; ?>" class="form-control" data-parsley-required="true" disabled />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Jurusan :</label>
                            <div class="col-md-8">
                                <input type="text" name="Majoring" id="Majoring" value="<?php echo $rows->Majoring; ?>" class="form-control" data-parsley-required="true" disabled />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">IPK/Nilai Rata-rata :</label>
                            <div class="col-md-8">
                                <input type="text" name="IPK_Value" id="IPK_Value" value="<?php echo $rows->IPK_Value ?>" class="form-control" data-parsley-required="true" disabled />
                            </div>
                        </div>

                        <!-- begin hljs-wrapper -->
                        <div class="hljs-wrapper">
                            <strong>Saudara Tidak Serumah</strong>
                        </div>
                        <!-- end hljs-wrapper -->
                        <br>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nama : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Econ_Name" id="Econ_Name" value="<?php echo $rows->Econ_Name ?>" class="form-control" data-parsley-required="true" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Hubungan : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Econ_Relationship" id="Econ_Relationship" value="<?php echo $rows->Econ_Relationship; ?>" class="form-control" data-parsley-required="true" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nomor Telp/HP : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Econ_Phone_Number" id="Econ_Phone_Number" value="<?php echo $rows->Econ_Phone_Number; ?>" class="form-control" data-parsley-required="true" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Alamat : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea name="Econ_Address" id="Econ_Address" class="form-control" rows="3" data-parsley-range="[20,200]" disabled><?php echo $rows->Econ_Address; ?></textarea>
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label"></label>
                            <div class="col-md-8">
                                <table>
                                    <tr>
                                        <td>RT:</td>
                                        <td>
                                            <input type="text" name="Econ_Address_RT" id="Econ_Address_RT" value="<?php echo $rows->Econ_Address_RT; ?>" class="form-control" data-parsley-required="true" disabled />
                                            <span class="help-block"></span>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>RW:</td>
                                        <td>
                                            <input type="text" name="Econ_Address_RW" id="Econ_Address_RW" value="<?php echo $rows->Econ_Address_RW; ?>" class="form-control" data-parsley-required="true" disabled />
                                            <span class="help-block"></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Provinsi : <span class="text-danger">*</span></label>
                            <div class="col-md-8">

                                <input type="text" name="Econ_Address_Province" id="Econ_Address_Province" value="<?php echo $rows->Econ_Address_Province; ?>" class="form-control" data-parsley-required="true" onKeyUp="this.value=this.value.replace(/[^0-9]/g,'')" disabled />

                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kota : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Econ_Address_City" id="Econ_Address_City" class="default-select2 form-control" onChange="viewKecamatanEc()" disabled="disabled" required>
                                    <option value="<?php echo $rows->Econ_Address_City; ?>"><?php echo $rows->Econ_Address_City; ?></option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kecamatan : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Econ_Address_District" id="Econ_Address_District" class="default-select2 form-control" onChange="viewKelurahanEc()" disabled="disabled" required>
                                    <option value="<?php echo $rows->Econ_Address_District; ?>"><?php echo $rows->Econ_Address_District; ?></option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kelurahan : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Econ_Address_Village" id="Econ_Address_Village" class="default-select2 form-control" onChange="viewKodePosEc()" disabled="disabled" required>
                                    <option value="<?php echo $rows->Econ_Address_Village; ?>"><?php echo $rows->Econ_Address_Village; ?></option>
                                </select>
                                <span class="help-block"></span>
                                <input type="hidden" name="Econ_Address_Zip_Code" id="Econ_Address_Zip_Code" value="<?php echo $rows->Econ_Address_Zip_Code; ?>" />
                            </div>
                        </div>

                        <div class="hljs-wrapper">
                            <strong>Saudara Serumah</strong>
                        </div>
                        <!-- end hljs-wrapper -->
                        <br>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nama : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Econ_Name_Home" id="Econ_Name_Home" value="<?php echo $rows->Econ_Name_Home ?>" class="form-control" data-parsley-required="true" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Hubungan : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Econ_Relationship_Home" id="Econ_Relationship_Home" value="<?php echo $rows->Econ_Relationship_Home; ?>" class="form-control" data-parsley-required="true" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nomor Telp/HP : <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Econ_Phone_Number_Home" id="Econ_Phone_Number_Home" value="<?php echo $rows->Econ_Phone_Number_Home; ?>" class="form-control" data-parsley-required="true" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <!-- begin hljs-wrapper -->
                        <div class="hljs-wrapper">
                            <strong>Struktur</strong>
                        </div>
                        <!-- end hljs-wrapper -->
                        <br>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Divisi <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Division" id="Division" value="<?php echo $rows->Division; ?>" class="form-control" data-parsley-required="true" disabled />

                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Product <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Product" id="Product" value="<?php echo $rows->Product; ?>" class="form-control" data-parsley-required="true" disabled />

                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group row" id="div-bd">
                            <label class="col-md-4 col-form-label">Client Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Company_Name_BD" id="Company_Name_BD" value="<?php echo $rows->Client_Name; ?>" class="form-control" data-parsley-required="true" disabled />
                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Project Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Project_Name_BD" id="Project_Name_BD" class="form-control default-select2" style="width:100%;" disabled onchange="renderPosition(this.value)">
                                    <option value="<?= $rows->Project_Name ?>" selected><?= $rows->Project_Name ?></option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Posisi <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Position_BD" id="Position_BD" class="form-control default-select2" style="width:100%;" disabled>
                                    <option value="<?= $rows->Position ?>" selected><?= $rows->Position ?></option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Channel <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Channel_BD" id="Channel_BD" class="default-select2 form-control" style="width:100%;" disabled>
                                    <option value="undefined" selected>undefined</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Group Type <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Group_Type_BD" id="Group_Type_BD" class="default-select2 form-control" style="width:100%;" disabled>
                                    <option value="undefined" selected>undefined</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Level <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Level_BD" id="Level_BD" class="default-select2 form-control" style="width:100%;" disabled>
                                    <option value="undefined" selected>undefined</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Sales Mode <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Sales_Mode_BD" id="Sales_Mode_BD" class="default-select2 form-control" style="width:100%;" disabled>
                                    <option value="undefined" selected>undefined</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row" id="div-rto">
                            <label class="col-md-4 col-form-label">Client Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Company_Name_RTO" id="Company_Name_RTO" class="form-control default-select2" style="width:100%;" disabled>
                                    <option value="Undefined" selected>Undefined</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Project Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="Project_Name_RTO" id="Project_Name_RTO" class="form-control default-select2" style="width:100%;" disabled>
                                    <option value="Undefined" selected>Undefined</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Posisi <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Position_RTO" id="Position_RTO" value="<?php echo $rows->Position; ?>" class="form-control" data-parsley-required="true" disabled />
                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Channel <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Channel_RTO" id="Channel_RTO" value="<?php echo $rows->Channel; ?>" class="form-control" data-parsley-required="true" disabled />


                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Group Type <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Group_Type_RTO" id="Group_Type_RTO" value="<?php echo $rows->Group_Type; ?>" class="form-control" data-parsley-required="true" disabled />


                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Level <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Level_RTO" id="Level_RTO" value="<?php echo $rows->Level; ?>" class="form-control" data-parsley-required="true" disabled />
                                <span class="help-block"></span>
                            </div>
                            <label class="col-md-4 col-form-label">Sales Mode <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Sales_Mode_RTO" id="Sales_Mode_RTO" value="<?php echo $rows->Sales_Mode; ?>" class="form-control" data-parsley-required="true" disabled />
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Departement <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Departement" id="Departement" value="<?php echo $rows->Departement; ?>" class="form-control" data-parsley-required="true" disabled />


                                <span class="help-block"></span>
                                <div id="project_field" style="display:none;">
                                    <label>Project Name <span class="text-danger">*</span></label>
                                    <input type="text" name="Project_Name" id="Project_Name" value="<?php echo $rows->Project_Name; ?>" class="form-control" data-parsley-required="true" disabled />

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Cabang <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Branch" id="Branch" value="<?php echo $rows->Branch; ?>" class="form-control" data-parsley-required="true" disabled />


                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Tipe Karyawan <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="Employee_Type" id="Employee_Type" value="<?php echo $rows->Employee_Type; ?>" class="form-control" data-parsley-required="true" disabled />


                                <span class="help-block"></span>
                            </div>
                        </div>



                        <div class="hljs-wrapper">
                            <strong>Data Payroll</strong>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nama Bank :</label>
                            <div class="col-md-8">
                                <input type="text" name="Bank_Name" id="Bank_Name" value="<?php echo $rows->Bank_Name; ?>" class="form-control" data-parsley-required="false" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Cabang :</label>
                            <div class="col-md-8">
                                <input type="text" name="Bank_Branch" id="Bank_Branch" value="<?php echo $rows->Bank_Branch; ?>" class="form-control" data-parsley-required="false" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nomor Rekening : <code>*</code></label>
                            <div class="col-md-8">
                                <input type="text" name="Bank_Account" id="Bank_Account" value="<?php echo $rows->Bank_Account; ?>" class="form-control" data-parsley-required="false" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Nomor NPWP :</label>
                            <div class="col-md-8">
                                <input type="text" name="NPWP_Number" id="NPWP_Number" value="<?php echo $rows->NPWP_Number; ?>" class="form-control" data-parsley-required="false" disabled />
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Alamat NPWP :</label>
                            <div class="col-md-8">
                                <textarea name="NPWP_Address" id="NPWP_Address" class="form-control" rows="5" data-parsley-required="false" disabled><?php echo $rows->NPWP_Address; ?></textarea>
                                <span></span>
                                <span class="help-block"></span>
                            </div>
                        </div>


                    </div>
                    <!-- end panel-body -->

                </div>
                <!-- end panel -->

            </div>
            <!-- end col-6 -->

            <!-- begin col-6 -->
            <div class="col-lg-6">
                <!-- begin panel -->
                <div class="panel panel-danger">
                    <!-- begin panel-heading -->
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        </div>
                        <h4 class="panel-title">Foto & Dokumen</h4>
                    </div>
                    <!-- end panel-heading -->
                    <!-- begin panel-body -->
                    <div class="panel-body" style="overflow-y: scroll; height:2000px;">
                        <div class="form-group row">
                            <div class="col-md-12 pull-right">
                                <h4 class="panel-title">Foto</h4>
                                <br>
                                <a class="pull-right" href="<?php echo $foto; ?>" target="_blank">
                                    <!-- <img src="<?php echo $image_url . $rows->Image_Photo; ?>" target="blank_" width="100%" height="100%" /> -->
                                </a>
                            </div>
                        </div>

                        <?php
                        $i = 0;

                        // Tambahkan pengecekan apakah $query_doc bisa diiterasi
                        if (is_array($query_doc) || is_object($query_doc)) {
                            foreach ($query_doc as $rows_doc) {
                                $foto = "";
                                if (!empty($rows_doc->Document_Name)) {
                                    $foto = $image_url . $rows_doc->Document_Name;
                                    // $foto = file_url().$rows_doc->Document_Name;
                                } else {
                                    $foto = base_url() . "public/images/noimage.png";
                                }

                                $document_type_ = $rows_doc->Document_Type;
                                if ($document_type_ == 'Izajah') {
                                    $document_type = 'Ijazah';
                                } else {
                                    $document_type = $document_type_;
                                }
                        ?>
                                <div class="form-group row">
                                    <div class="col-md-12 pull-right">
                                        <h4 class="panel-title"><?php echo ++$i; ?>. <?php echo $document_type; ?></h4>
                                        <br>
                                        <a class="pull-right" href="<?php echo $foto; ?>" target="_blank">
                                            <img src="<?php echo $foto; ?>" target="blank_" width="100%" height="100%" />
                                        </a>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p>Data dokumen tidak tersedia.</p>";
                        }
                        ?>
                    </div>

                    <!-- end panel-body -->

                </div>
                <!-- end panel -->

            </div>

            <!-- end col-6 -->
    </form>
    <div class="col-lg-12">
        <!-- begin panel -->
        <div class="panel panel-success">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
                <h4 class="panel-title">Approve data</h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body" style="overflow-y: scroll;">
                <div class="form-group row">
                    <div class="col-md-12 pull-right">
                        <h4 class="panel-title">Approve</h4>
                        <br>
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <a href="javascript:;" class="btn btn-primary" id="btnSubmit" onClick="submit(<?php echo $rows->Recruitment_ID; ?>)"><i class="fa-fw fa fa-thumbs-up"></i> Submit</a>
                                <a href="javascript:void(0);" class="btn btn-primary" id="btnWait" style="display:none" disabled><i class="fa-fw fa fa-thumbs-up"></i> Memproses...</a>
                                <a href="javascript:;" id="btnReturn" class="btn btn-danger" onClick="actionreturn(<?php echo $rows->Recruitment_ID; ?>)"><i class="fa-fw fa fa-thumbs-up"></i> Return</a>

                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- end panel-body -->

        </div>
        <!-- end panel -->

    </div>

</div>

<!--end row -->

<script src="template/ajax/zipcode.js"></script>
<script type="text/javascript">
    function disable_form() {
        $("#applicant_form :input").prop("disabled", true);
        document.getElementById("control_update").style.display = "none";
        document.getElementById("btnEdit").style.display = "block";
        $('#btnEdit').attr('disabled', false);
    }

    function display_project() {
        if (document.getElementById("Departement").value == 'Project') {
            document.getElementById("project_field").style.display = "block";
        } else {
            document.getElementById("project_field").style.display = "none";
        }
    }

    // function actionreturn() {
    //     $('#modal_form_return').modal('show');
    //     notetext.required = true

    // }


    function submit(rec_id) {
        $('#btnSubmit').css('display', 'none');
        $('#btnWait').css('display', 'inline-block');


        var url = "<?php echo site_url('recruitment_approval/submit/'); ?>" + rec_id;

        $.ajax({
            url: url,
            type: "POST",

            dataType: "JSON",
            success: function(data) {
                console.log(data);
                if (data.status) {
                    alert("Proses submit berhasil");
                    window.location.href = "<?php echo site_url('recruitment_approval') ?>";
                } else {
                    alert("Proses submit gagal, " + data.message);
                    window.location.href = "<?php echo site_url('recruitment_approval/submit/') ?>" + rec_id;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Proses submit gagal.');
                $('#btnSubmit').css('display', 'inline-block');
                $('#btnWait').css('display', 'none');
            }
        });
    }

    function actionreturn(rec_id) {
        $('#btnReject').css('display', 'none');
        // $('#btnWait').css('display', 'inline-block');
        $('#modal_form_return').modal('show');
        //     notetext.required = true
    }

    $(document).ready(function() {
        $('#form_return').submit(function(e) {
            e.preventDefault();

            // let app_id = '<?php echo $rows->Recruitment_ID; ?>';
            var rec_id = <?php echo $rows->Recruitment_ID; ?>;

            var url = "<?php echo site_url('recruitment_approval/return/'); ?>" + rec_id;
            var reason = $('#reason').val();
            var updated_by = "<?php echo $this->session->userdata('realname'); ?>";

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    reason: reason,
                    rec_id: rec_id,
                    updated_by: updated_by

                },
                dataType: "JSON",

                success: function(data) {
                    if (data.status) {
                        alert("Proses return berhasil");
                        window.location.href = "<?php echo site_url('recruitment_approval') ?>";
                    } else {
                        alert("Proses return gagal, " + data.message);
                        window.location.href = "<?php echo site_url('recruitment_approval/approve_data/') ?>" + rec_id;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Proses return gagal.');
                    $('#btnReturn').css('display', 'inline-block');
                    $('#btnWait').css('display', 'none');
                }
            });

        })
    })
</script>

<div class="modal fade" id="modal_form_return" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reason Return</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form class="smart-form" action="<?php echo site_url('recruitment_approval/return/' . $rows->Recruitment_ID); ?>" method="POST" id="form_return">
                <div class="modal-body form" style="background:#2196f3;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Reason <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <textarea rows="5" name="reason" id="reason" class="form-control"></textarea>
                                    <?php echo form_error('reason'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-primary pull-left" value="save"><span class="fa fa-save"></span> Save Return</button>
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-step-backward"></i> Cancel</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->