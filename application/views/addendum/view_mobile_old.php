<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

$rows = $query->row();
$recruitment_id = $rows->Recruitment_ID;

?>

<!-- Signature -->
<link href="<?php echo base_url(); ?>new_assets/plugins/signature2/jquery.signaturepad.css" rel="stylesheet">

<div id="content" class="content">

    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo site_url('signing'); ?>">Signing</a></li>
        <li class="breadcrumb-item active">Signing Form</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        Signing Form
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-lg-12">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">Preview Perjanjian</h4>
                </div>
                <!-- end panel-heading -->
                
                <!-- Load preview -->
                <?php echo $preview; ?>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->

         <!-- begin col-12 -->
        <div class="col-lg-12">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                    </div>
                    <h4 class="panel-title">Pernyataan</h4>
                </div>
                <!-- end panel-heading -->

                <!-- begin panel-body -->
                <div class="panel-body">
                    <div class="alert alert-warning">
                        Saya sudah membaca, memahami dokumen-dokumen terlampir diatas dan menyetujui untuk menandatangani semua dokumen tersebut.
                    </div>
                </div>
                <!-- end panel-body -->

            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->
        
        <!-- begin col-12 -->
        <div class="col-lg-12">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default"
                            data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                            data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                    <h4 class="panel-title">Tanda Tangan</h4>
                </div>
                <!-- end panel-heading -->

                <!-- begin panel-body -->
                <div class="panel-body">
                    <form name="sign_form" id="sign_form" action="#" accept-charset="utf-8" method="POST">
                        <input type="hidden" name="Recruitment_ID" value="<?php echo $recruitment_id; ?>" />
                        <input type="hidden" name="Template_ID" value="<?php echo $template_id; ?>" />
                        <div class="form-body">
                            <div class="form-group row">
                                <div class="sigPad" id="linear" style="width:100%;">
                                    <ul class="sigNav">
                                        <li class="clearButton"><a href="#clear">Hapus</a></li>
                                    </ul>
                                    <div id="signature-pad" class="sig sigWrapper" style="height:auto;">
                                        <div class="typed"></div>
                                        <canvas class="pad" id="signature"></canvas>
                                        <input type="hidden" name="output" id="output" class="output" />
                                        <script type="text/javascript">
                                            $(function() {
                                                $('#signature').sketch();
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <button type="button" id="btnSave" onClick="confirm()" class="btn btn-sm btn-primary"><span class="fa fa-save"></span> Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end panel-body -->

            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->

    </div>
    <!-- end row -->

</div>

<!-- JQuery for signature -->
<script src="<?php echo base_url(); ?>new_assets/plugins/signature2/jquery.signaturepad.js"></script>
<script src="<?php echo base_url(); ?>new_assets/plugins/signature2/json2.min.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        $('#linear').signaturePad({drawOnly:true, lineTop:200});
        $('#smoothed').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:200});
        $('#smoothed-variableStrokeWidth').signaturePad({drawOnly:true, drawBezierCurves:true, variableStrokeWidth:true, lineTop:200});
    });

    function reload_page() {
        window.location.reload();
    }

    function reload_bucket() {
        window.location.href = "<?php echo site_url(''); ?>";
    }

    //signaturePad = new SignaturePad(canvas);

    function confirm() {
        var output = document.getElementById("output");
        if (output.value === "") {
            alert("Tanda tangan tidak boleh kosong.");
        }
        else {
            var canvas = document.getElementById("signature");
            var dataURL = canvas.toDataURL("image/png");
            document.getElementById('output').value = dataURL;
            var fd = new FormData(document.forms["sign_form"]);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo site_url('signing/submit2'); ?>', true);

            xhr.upload.onprogress = function (e) {
                if (e.lengthComputable) {
                    var percentComplete = (e.loaded / e.total) * 100;
                    console.log(percentComplete + '% uploaded');
                    alert('Tanda tangan tersimpan');                                                                        
                } else {
                    alert('Gagal menyimpan tanda tangan');
                }
            };

            xhr.onload = function () {

            };
            xhr.send(fd);
            reload_bucket();
        }
    };
</script>