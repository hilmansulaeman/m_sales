<link href="<?php echo base_url(); ?>assets/plugins/signature/signature_pad.css" rel="stylesheet">

<div class="box box-primary">
    <div class="box-header with-border">
        <h4>Preview Perjanjian  <small></small></h4>
        <div class="box-tools pull-right">
        </div>
    </div>
    <div class="box-body">
        <iframe src="<?= $preview; ?>" frameborder="0" width="100%" height="800px"></iframe>
        <div class="panel-body" style="height: 750px;" id="signature2">
            <form name="sign_form" id="sign_form" action="#" accept-charset="utf-8" method="POST">
                <div class="form-body">
                    <div id="signature-pad" class="m-signature-pad">
                        <div class="m-signature-pad--body">
                            <canvas id="signature"></canvas>
                        </div>
                        <input type="hidden" name="output" id="output" />
                        <input type="hidden" name="adendum_id" id="adendum_id" value="<?= $id ?>">
                        
                        <div class="m-signature-pad--footer">
                            <div class="form-body"id="text" > 
                                <input type="checkbox" name="check_list[]" alt="Checkbox" value="merah" id="cek"> saya telah menyetujui 
                            </div>
                            <div class="left">
                                <button type="button" id="btnSave" onClick="confirm()" class="btn btn-sm btn-primary"><span class="fa fa-save"></span> Simpan</button>
                            </div>
                            <div class="right">
                                <button type="button" class="btn btn-sm btn-danger" data-action="clear"><span class="fas fa-eraser"></span> Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/plugins/signature/signature_pad.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/signature/app.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $(function () {
            $('#signature').sketch();
        });

        $.getJSON( "<?php echo site_url('dashboard/get_veryfied') ?>", function( data ) {
            if(data.is_checked == 1){
                $("#signature2").hide();
            } else {
                $("#signature2").show();
            }
        });

    });
    function reload_page() {
        window.location.reload();
    }

    function reload_bucket() {
        window.location.href = "<?php echo site_url(); ?>";
    }

    signaturePad = new SignaturePad(canvas);

    function confirm() {
        // var fd = new FormData(document.forms["sign_form"]);
        // var xhr = new XMLHttpRequest();

        if (signaturePad.isEmpty()) {
            alert("Tanda tangan tidak boleh kosong.");
        }else if($('#cek').is(':checked') == false){
            alert("Harap diceklis terlebih dahulu")
            
        } else {
            var canvas = document.getElementById("signature");
            var dataURL = canvas.toDataURL("image/png");
            document.getElementById('output').value = dataURL;
            var fd = new FormData(document.forms["sign_form"]);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo site_url('addendum/submit'); ?>', true);

            xhr.upload.onprogress = function (e) {
                if (e.lengthComputable) {
                    var percentComplete = (e.loaded / e.total) * 100;
                    console.log(percentComplete + '% uploaded');
                    alert('Terima Kasih Anda Sudah Mentandatangani Addendum ');
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

<style>
    .m-signature-pad {
        position: absolute;
        width: 96%;
        height: 700px;
        border: 2px solid #e8e8e8;
        background-color: #fff;
        box-shadow: 0 1px 4px rgb(0 0 0 / 27%), 0 0 40px rgb(0 0 0 / 8%) inset;
        border-radius: 4px;
    }

    @media only screen and (max-width: 1024px) {
        .m-signature-pad {
        top:900px;
        left:0;
        right:0;
        width: 110%;
        height: 500px;
        min-width: 250px;
        min-height: 140px;
        margin :5%;
    }
    }
</style>