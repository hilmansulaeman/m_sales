<?php $level = $this->session->userdata('level'); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Data Registrant</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
	<a href="<?php echo site_url(); ?>registrant/add"><button class="btn btn-primary">Input Data</button></a>
    <a href="<?php echo site_url(); ?>export/request"><button class="btn btn-primary">Export</button></a>
    <br /><br />
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Data Registrant
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th align="center">No</b></th>
                                    <th>Nama Peserta</th>
                                    <th>Nomor KTP</th>
                                    <th>Nomor Telp</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; ?>
                            <?php foreach($query->result() as $rows){ ?>
                                <tr class="odd gradeX">
                                    <td align="center"><?php echo ++$i; ?></td>
                                    <td><b><?php echo $rows->nama_kasir; ?></b>
                                    	<small><p>Nama Merchant : <?php echo $rows->nama_merchant; ?><br />
                                        Alamat Merchant : <?php echo $rows->alamat_merchant; ?></p></small>
                                    </td>
                                    <td><?php echo $rows->ktp; ?></td>
                                    <td><?php echo $rows->tlp_kasir; ?></td>
                                    <td><?php echo $rows->email; ?></td>
                                    <td><?php echo $rows->status_schedule; ?></td>
                                    <td align="center">
                                    <a href="<?php echo site_url()."registrant/detail/".$rows->id; ?>" title="Detail Data"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <?php if($level == '1' || $level == '2'){ ?>
                                    <a href="<?php echo site_url()."registrant/delete/".$rows->id; ?>" onClick="return confirm('Delete this data?')" title='Delete Data'><span class="glyphicon glyphicon-remove"></span></a>
                                    <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

<script>
function muncul()
{
	$('#modal_form_1').modal('show');
}
function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
	url = "<?php echo site_url('registrant/insert_data_reg')?>";

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
				alert('Success');
                location.reload();
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
</script>


<!--modal dialog-->
<div class="modal fade" id="modal_form_1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Input Data</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
						<div class="form-group">
							<?php
								echo "<div align=right class=col-md-3>";
								echo form_label('Area');
								echo "</div>";
								echo "<div class=col-md-9>";
								foreach($levels->result() as $row)
								{
									$array_level[$row->id] = $row->Area;
								}
								echo form_dropdown('area',$array_level,$row->Area,'class="form-control"'); 
							?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Lokasi</label>
							<div class="col-md-9">
								<input type="text" name="location" placeholder="Location" value="<?php echo $rows->Location; ?>" class="form-control"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Tanggal training</label>
							<div class="col-md-9">
								<input type="text" name="available_date" placeholder="click to show datepicker" id="calendar" class="form-control" value="<?php echo $rows->tgl_training; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Waktu Training</label>
							<div class="col-md-9">
								<select class="form-control" id="sel1" name="time">
								<?php $time = $rows->time; $choose1=""; $choose2=""; $choose3=""; $choose4=""; $choose5=""; if($time == "09.00 - 11.00") { $choose1="selected";}elseif($time== "10.00 - 12.00"){ $choose2="selected"; }elseif($time=="13.00 - 15.00"){ $choose3="selected"; }elseif($time=="14.00 - 16.00"){ $choose4="selected"; }elseif($time=="16.00 - 18.00"){ $choose5=="selected";} ?>
									<option value="09.00 - 11.00" <?php echo $choose1 ?>>09.00 - 11.00</option>
									<option value="10.00 - 12.00" <?php echo $choose2 ?>>10.00 - 12.00</option>
									<option value="13.00 - 15.00" <?php echo $choose3 ?>>13.00 - 15.00</option>
									<option value="14.00 - 16.00" <?php echo $choose4 ?>>14.00 - 16.00</option>
									<option value="16.00 - 18.00" <?php echo $choose5 ?>>16.00 - 18.00</option>
								 </select>
							 </div>
						</div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Peserta</label>
                            <div class="col-md-9">
                                <input name="nama_kasir" id="nama_kasir" placeholder="Nama Peserta" class="form-control" type="text">
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
                            <label class="control-label col-md-3">Nama Perusahaan</label>
                            <div class="col-md-9">
                                <input name="nama_merchant" placeholder="Nama Perusahaan" class="form-control" type="text">
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
                <button type="button" id="btnSave" onClick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->