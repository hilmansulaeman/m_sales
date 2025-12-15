<?php if (! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $rows = $request_data->row(); ?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Detail Data</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <?php //echo form_open('');?>
        <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data Peserta
                        </div>
                        <div class="panel-body">
                        	<p align="right"><a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_peserta(<?php echo $rows->id; ?>)"><i class="glyphicon glyphicon-pencil"></i> Edit</a></p>
                        	<table class="table table-striped">
                            <tr>
                                <td><label>Nama Peserta</label></td>
                                <td><?php echo $rows->nama_kasir; ?></td>
                            </tr>
                            <tr>
                                <td><label>Nomor KTP</label></td>
                                <td><?php echo $rows->ktp; ?></td>
                            </tr>
                            <tr>
                                <td><label>Tanggal Lahir</label></td>
                                <td><?php echo date ('d/m/Y', strtotime ($rows->tgl_lahir)); ?></td>
                            </tr>
							<tr>
                                <td><label>Jenis Kelamin</label></td>
                                <td><?php $jk = ""; if($rows->jenis_kelamin == "L"){ $jk = "Laki - laki";}elseif($rows->jenis_kelamin == "P"){ $jk="Perempuan"; } echo $jk; ?></td>
                            </tr>
                            <tr>
                                <td><label>Alamat Rumah</label></td>
                                <td><?php echo $rows->alamat_rumah; ?></td>
                            </tr>
                            <tr>
                                <td><label>Telp Peserta</label></td>
                                <td><?php echo $rows->tlp_kasir; ?></td>
                            </tr>
                            <tr>
                                <td><label>Email Peserta</label></td>
                                <td><?php echo $rows->email; ?></td>
                            </tr>
                            <tr>
                                <td><label>Facebook</label></td>
                                <td><?php echo $rows->facebook; ?></td>
                            </tr>
                            <tr>
                                <td><label>Pin BB</label></td>
                                <td><?php echo $rows->pin_bb; ?></td>
                            </tr>
                            </table>                                   
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data Merchant
                        </div>
                        <div class="panel-body">
                            <p align="right"><a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_merchant(<?php echo $rows->id; ?>)"><i class="glyphicon glyphicon-pencil"></i> Edit</a></p>
                        	<table class="table table-striped">
                            <tr>
                                <td><label>Nama Merchant</label></td>
                                <td><?php echo $rows->nama_merchant; ?></td>
                            </tr>
                            <tr>
                                <td><label>Alamat Merchant</label></td>
                                <td><?php echo $rows->alamat_merchant; ?></td>
                            </tr>
                            <tr>
                                <td><label>MID</label></td>
                                <td><?php echo $rows->mid; ?></td>
                            </tr>
                            </table>
                                    
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data Training
                        </div>
                        <div class="panel-body">
                            <p align="right"><a class="btn btn-sm btn-primary" href="<?php echo site_url() ?>registrant/list_schedule/<?php echo $rows->id; ?>/<?php echo $rows->schedule_id; ?>" title="Edit"><i class="glyphicon glyphicon-pencil"></i> Edit</a></p>
                        	<table class="table table-striped">
                            <tr>
                                <td><label>Lokasi Training</label></td>
                                <td><?php echo $rows->Location; ?></td>
                            </tr>
                            <tr>
                                <td><label>Kota</label></td>
                                <td><?php echo $rows->Area; ?></td>
                            </tr>
                            <tr>
                                <td><label>Tanggal Training</label></td>
                                <td><?php echo date ('d/m/Y', strtotime ($rows->tgl_training)); ?></td>
                            </tr>
                            <tr>
                                <td><label>Waktu Training</label></td>
                                <td><?php echo $rows->waktu_training; ?></td>
                            </tr>
                            </table>
                                    
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Status Registrasi
                        </div>
                        <div class="panel-body">
                            <p align="right"><a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_status(<?php echo $rows->id; ?>)"><i class="glyphicon glyphicon-pencil"></i> Edit</a></p>
                            <table class="table table-striped">
                            <tr>
                                <td><label>Status Registrasi</label></td>
                                <td><?php echo $rows->status_schedule; ?></td>
                            </tr>
                            </table>
                                    
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                </div>
                <!-- /.col-lg-6 -->
                <hr>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Kehadiran
                        </div>
                        <div class="panel-body">
                            <p align="right"><a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_absen(<?php echo $rows->id; ?>)"><i class="glyphicon glyphicon-pencil"></i> Edit</a></p>
                            <table class="table table-striped">
                            <tr>
                                <td><label>Status Kehadiran</label></td>
                                <td><?php echo $rows->status_kehadiran; ?></td>
                            </tr>
                            </table>
                                    
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                </div>
                <!-- /.col-lg-6 -->
                
            </div>
            <!-- /.row -->
            
            <button class="btn btn-primary" onclick="history.go(-1);">Back</button>
            
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<script type="text/javascript">
function reload_page()
{
    window.location.reload(); 
}

function edit_peserta(id)
{
    save_method = 'update_peserta';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('registrant/get_data/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="nama_kasir"]').val(data.nama_kasir);
			//$(document.getElementById("nama_kasir")).value=data.nama_kasir;
			$('[name="ktp"]').val(data.ktp);
			$('[name="tgl_lahir"]').val(data.tgl_lahir);
			$('[name="jenis_kelamin"]').val(data.jenis_kelamin);
            //$('[name="tgl_lahir"]').datepicker('update',data.tgl_lahir);
            $('[name="alamat_rumah"]').val(data.alamat_rumah);
            $('[name="tlp_kasir"]').val(data.tlp_kasir);
			$('[name="email"]').val(data.email);
			$('[name="facebook"]').val(data.facebook);
			$('[name="pin_bb"]').val(data.pin_bb);
            $('#modal_form_1').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Update Data'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function edit_merchant(id)
{
    save_method = 'update_merchant';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('registrant/get_data/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
			$('[name="nama_merchant"]').val(data.nama_merchant);
			$('[name="alamat_merchant"]').val(data.alamat_merchant);
			$('[name="mid"]').val(data.mid);
            $('#modal_form_merchant').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Update Data'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function edit_status(id)
{
    save_method = 'update_status';
    $('#form_status')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('registrant/get_data/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
			$('[name="status_schedule"]').val(data.status_schedule);
            $('#modal_form_status').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Update Data'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function edit_absen(id)
{
    save_method = 'update_absen';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('registrant/get_data/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="status_kehadiran"]').val(data.status_kehadiran);
            $('#modal_form_absen').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Update Data'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
	url = "<?php echo site_url('registrant/update_peserta')?>";

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_1').modal('hide');
                reload_page();
            }

            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function save_merchant()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
	url = "<?php echo site_url('registrant/update_merchant')?>";

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_merchant').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_merchant').modal('hide');
                reload_page();
            }

            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function save_status()
{
    $('#SaveStatus').text('saving...'); //change button text
    $('#SaveStatus').attr('disabled',true); //set button disable 
    var url;
	url = "<?php echo site_url('registrant/update_status')?>";

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_status').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_status').modal('hide');
                reload_page();
            }

            $('#SaveStatus').text('save'); //change button text
            $('#SaveStatus').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error update data');
            $('#SaveStatus').text('save'); //change button text
            $('#SaveStatus').attr('disabled',false); //set button enable 

        }
    });
}

function save_absen()
{
    $('#SaveAbsen').text('saving...'); //change button text
    $('#SaveAbsen').attr('disabled',true); //set button disable 
    var url;
	url = "<?php echo site_url('registrant/update_absen')?>";

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_absen').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_3').modal('hide');
                reload_page();
            }

            $('#SaveAbsen').text('save'); //change button text
            $('#SaveAbsen').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error update data');
            $('#SaveAbsen').text('save'); //change button text
            $('#SaveAbsen').attr('disabled',false); //set button enable 

        }
    });
}
</script>

<!-- Bootstrap modal for update data kasir-->
<div class="modal fade" id="modal_form_1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Update Data</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Peserta</label>
                            <div class="col-md-9">
                                <input name="nama_kasir" id="nama_kasir" placeholder="Nama Peserta" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nomor KTP</label>
                            <div class="col-md-9">
                                <input name="ktp" placeholder="Nomor KTP" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tanggal Lahir</label>
                            <div class="col-md-9">
                                <input name="tgl_lahir" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">Jenis Kelamin</label>
                            <div class="col-md-9">
								<select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
									<option value=""></option>
									<option value="L">Laki - laki</option>
									<option value="P">Perempuan</option>
								</select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Alamat Rumah</label>
                            <div class="col-md-9">
                                <textarea name="alamat_rumah" placeholder="Alamat Rumah" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nomor Telp/HP</label>
                            <div class="col-md-9">
                                <input name="tlp_kasir" placeholder="Nomor Telp/HP" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Email</label>
                            <div class="col-md-9">
                                <input name="email" placeholder="Email" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Facebook</label>
                            <div class="col-md-9">
                                <input name="facebook" placeholder="Facebook" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Pin BB</label>
                            <div class="col-md-9">
                                <input name="pin_bb" placeholder="Pin BB" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onClick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal for update absen-->
<div class="modal fade" id="modal_form_merchant" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Update Data</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_merchant" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Merchant</label>
                            <div class="col-md-9">
                                <input name="nama_merchant" placeholder="Nama Merchant" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Alamat Merchant</label>
                            <div class="col-md-9">
                                <textarea name="alamat_merchant" placeholder="Alamat Merchant" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">MID</label>
                            <div class="col-md-9">
                                <input name="mid" placeholder="MID" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onClick="save_merchant()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal for update register status-->
<div class="modal fade" id="modal_form_status" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Update Data</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_status" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Status Registrasi</label>
                            <div class="col-md-9">
                                <select name="status_schedule" class="form-control">
                                    <option value="">--Pilih Status--</option>
                                    <option value="Register">Register</option>
                                    <option value="Cancel">Cancel</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="SaveStatus" onClick="save_status()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal for update absen-->
<div class="modal fade" id="modal_form_absen" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Update Data</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_absen" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Status Kehadiran</label>
                            <div class="col-md-9">
                                <select name="status_kehadiran" class="form-control">
                                    <option value="">--Pilih Status--</option>
                                    <option value="Hadir">Hadir</option>
                                    <option value="Tidak">Tidak Hadir</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="SaveAbsen" onClick="save_absen()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->