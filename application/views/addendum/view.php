
<div class="box box-primary">
    <div class="box-header with-border">
        <h4>Preview Perjanjian  <small></small></h4>
        <div class="box-tools pull-right">
        </div>
    </div>
    <div class="box-body">

    <iframe src="<?= $preview; ?>" frameborder="0" width="100%" height="800px"></iframe>
                <!-- end panel-heading -->

                <!-- begin panel-body -->
                <div class="panel-body" style="height: 750px;">
                    <form name="sign_form" id="sign_form" action="#" accept-charset="utf-8" method="POST">
                       
                        <div class="form-body"id="text" >
                            
                                <div class="description" style="font-family:Calibri;font-size:15px; margin-left:25px;" >Silahkan ceklis pernyataan dibawah untuk menunjukkan bahwa Anda telah membaca dan menyetujui isi addendum tersebut.</div>
                                <input type="checkbox" name="check_list[]" alt="Checkbox" value="merah" id="cek"> saya telah menyetujui 
                                <!-- <input type="hidden" name="output" id="output" /> -->
                                
                                    <div class="left">
                                        <!--<button type="button" id="btnSave" onClick="save()" data-action="save-png" class="btn btn-sm btn-primary"><span class="fa fa-save"></span> Simpan</button>-->
                                        <button type="button" id="btnSave" onClick="confirm()"
                                            class="btn btn-sm btn-primary"><span class="fa fa-save"></span> Simpan</button>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end panel-body -->

            </div>
            <!-- end panel -->
        </div>

    </div>
    <div class="box-footer clearfix"></div>
</div>




<script type = "text/javascript">

$(document).ready(function() {



    $.getJSON( "<?php echo site_url('dashboard/get_veryfied') ?>", function( data ) {        
        if(data.is_checked == 1){
            $("#text").hide();
            
        } else {
            $("#text").show();
        }
    });

});

function reload_bucket(){
    window.location.href = "<?php echo site_url('Addendum/index'); ?>";
}

function confirm(){
    // alert("tes");
    var fd = new FormData(document.forms["sign_form"]);
    var xhr = new XMLHttpRequest();
    
    if($('#cek').is(':checked') == true){
        
        xhr.open('post', '<?php echo site_url('Addendum/submit'); ?>');

        xhr.upload.onprogress = function (e) {
            if (e.lengthComputable) {
                var percentComplete = (e.loaded / e.total) * 100;
                console.log(percentComplete + '% uploaded');
                alert('Terima Kasih Anda Sudah Mengisi Addendum ');
            } else {
                alert('Gagal menyimpan tanda tangan');
            }   
        };

        xhr.onload = function(){

        };

        xhr.send(fd);
        reload_bucket();

    }
    else{
        alert("Harap diceklis terlebih dahulu");

    }
   
}
</script>
