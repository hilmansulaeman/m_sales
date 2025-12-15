<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
    .status-available {
        color: #2FC332;
    }

    .status-not-available {
        color: #D60202;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
    function check_merchant() {
        var name = escape($("#gerai_merchant").val()); //Get the value in the name textbox

        $.ajax({
            url: '<?php echo site_url(); ?>ajax/get_merchant/' + name,
            type: 'post',
            dataType: 'html',

            beforeSend: function() {
                $("#check_merchant").html('<img src="<?php echo base_url(); ?>public/images/loader.gif" align="absmiddle">&nbsp;Checking...');
            },
            success: function(result) {
                $('#check_merchant').html(result);
            }
        });
    }

    function initialize_map()
{
    alert('success');
    var myOptions = {
	      zoom: 4,
	      mapTypeControl: true,
	      mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
	      navigationControl: true,
	      navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
	      mapTypeId: google.maps.MapTypeId.ROADMAP      
	    }	
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}
function initialize()
{
	if(geo_position_js.init())
	{
		document.getElementById('current').innerHTML="Receiving...";
		geo_position_js.getCurrentPosition(show_position,function(){document.getElementById('current').innerHTML="Couldn't get location"},{enableHighAccuracy:true});
	}
	else
	{
		document.getElementById('current').innerHTML="Functionality not available";
	}
}

function show_position()
{
	document.getElementById('current').innerHTML="latitude="+p.coords.latitude.toFixed(2)+" longitude="+p.coords.longitude.toFixed(2);
	var pos=new google.maps.LatLng(p.coords.latitude,p.coords.longitude);
	map.setCenter(pos);
	map.setZoom(14);

	var infowindow = new google.maps.InfoWindow({
	    content: "<strong>yes</strong>"
	});

	var marker = new google.maps.Marker({
	    position: pos,
	    map: map,
	    title:"You are here"
	});

	google.maps.event.addListener(marker, 'click', function() {
	  infowindow.open(map,marker);
	});
	
}
    function check_hp() {
        var name = escape($("#hp_penanggung_jawab").val()); //Get the value in the name textbox

        $.ajax({
            url: '<?php echo site_url(); ?>ajax/get_hp/' + name,
            type: 'post',
            dataType: 'html',

            beforeSend: function() {
                $("#check_hp").html('<img src="<?php echo base_url(); ?>public/images/loader.gif" align="absmiddle">&nbsp;Checking...');
            },
            success: function(result) {
                $('#check_hp').html(result);
            }
        });
    }

    function check_email() {
        var name = escape($("#email_penanggung_jawab").val()); //Get the value in the name textbox

        $.ajax({
            url: '<?php echo site_url(); ?>ajax/get_email/' + name,
            type: 'post',
            dataType: 'html',

            beforeSend: function() {
                $("#check_email").html('<img src="<?php echo base_url(); ?>public/images/loader.gif" align="absmiddle">&nbsp;Checking...');
            },
            success: function(result) {
                $('#check_email').html(result);
            }
        });
    }

    function check_wilayah() {
        var name = escape($("#wilayah").val()); //Get the value in the name textbox

        $.ajax({
            url: '<?php echo site_url(); ?>ajax/get_wilayah/' + name,
            type: 'post',
            dataType: 'html',
            beforeSend: function() {
                $("#check_wilayah").html('<img src="<?php echo base_url(); ?>public/images/loader.gif" align="absmiddle">&nbsp;Checking...');
            },
            success: function(result) {
                $('#check_wilayah').html(result);
            }
        });
    }
</script>

<script src="<?php echo base_url(); ?>public/jquery/js/jquery-1.7.js"></script>
<script src="<?php echo base_url(); ?>public/jquery/js/jquery.form.js"></script>

<!-- script JQuery untuk load gambar pada bagian preview -->
<script type="text/javascript">
    $(function() {
        if(geo_position_js.init()){
			geo_position_js.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true,options:5000});
		}
		else{
			alert("Functionality not available");
		}

		function success_callback(p)
		{
			alert('lat='+p.coords.latitude.toFixed(2)+';lon='+p.coords.longitude.toFixed(2));
		}
		
		function error_callback(p)
		{
			alert('error='+p.message);
        function success_callback(p)
		{
			alert('lat='+p.coords.latitude.toFixed(2)+';lon='+p.coords.longitude.toFixed(2));
		}
   
        $("#file").change(function() {
            $("#message").empty(); // To remove the previous error message
            var file = this.files[0];
            var imagefile = file.type;

            var imageDate  = file.lastModifiedDate;
            const months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
            var imageDate_ = imageDate.getDate() + "-" + months[imageDate.getMonth()] + "-" + imageDate.getFullYear();

            var match = ["image/jpeg", "image/png", "image/jpg"];

            if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                $('#previewing').attr('src', 'noimage.png');
                $("#message").html("<p id='error'>Please Select A valid Image File</p>" + "<h4>Note</h4>" + "<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                return false;
            } else {
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
                $('#dateImage').html("<p><h5><b> Tanggal Foto : " + imageDate_ + "</b></h5></p>");
            }
        });
    });


    function imageIsLoaded(e) {
        $("#file").css("color", "green");
        $('#image_preview').css("display", "block");
        $('#previewing').attr('src', e.target.result);
        $('#previewing').attr('width', '230px');
        $('#previewing').attr('height', '250px');
    }
</script>

<style>
	#title {background-color:#e22640;padding:5px;}
	#current {font-size:10pt;padding:5px;}	
</style>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <?php
                $kategori = $this->uri->segment(2);
                if ($kategori == 'absen_masuk') {
                ?>
                    <h1 class="page-header">Absen Masuk</h1>
                <?php } else if ($kategori == 'request') { ?>
                    <h1 class="page-header">Long Shift Request</h1>
                <?php } else if ($kategori == 'izin') { ?>
                    <h1 class="page-header">Absen Izin</h1>
                <?php } else if ($kategori == 'off') { ?>
                    <h1 class="page-header">Absen Off</h1>
                <?php } else if ($kategori == 'sakit') { ?>
                    <h1 class="page-header">Absen Sakit</h1>
                <?php } else { ?>
                    <h1 class="page-header">Absen Pulang</h1>
                <?php } ?>
            </div>
            
        </div>
   
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Field <span style="color:#FF0000">*</span> harus diisi
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                              <form action="<?= base_url('absen/do_upload/');?>" method="post" enctype="multipart/form-data">
                                    <input type="text" name="sales_code" hidden value="<?php echo $this->session->userdata('username'); ?>">

                                    <div class="form-group">
                                        <div id="image_preview"><img id="previewing" src="<?php echo base_url(); ?>images/noimage.png"></div>
                                        <div>
                                            <label>Upload foto selfie : <span style="color:#FF0000">*</span></label>
                                            <br>
                                            <input type="file" name="files" required>
                                        </div>
                                        <div id="message"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Isi Keterangan di bawah ini !</label>
                                        <textarea class='form-control' name='keterangan' required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="Submit" class="btn btn-primary" />
                                    </div>
                                </form>

                             
                </div>
                                
                          
                        </div>
                        <!-- /.row (nested) -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->