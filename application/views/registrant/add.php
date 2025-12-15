<?php if (! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Add Registrant</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <?php echo form_open('registrant/insert_data_reg');?>
        <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        </div>
                        <div class="panel-body">
                            <div class="row">
								<div class="col-lg-12">
									<div class="form-group">
									 <label>Area : </label>
										<select id="idArea" name="area" class="form-control" onchange="tampillok()">
										<option value="<?php echo str_replace('-', ' ', $this->uri->segment(3)); ?>"><?php echo str_replace('-', ' ', $this->uri->segment(3)); ?></option>
										<?php foreach($levels->result() as $row){ ?>
											<option value="<?php echo $row->Area; ?>"><?php echo $row->Area;?></option>
										<?php } ?>
										</select>
										<span class="help-block"></span>
									</div>
								   <div class="form-group">
									   <label>Lokasi : </label>
										<select id="lokasi" name="location" class="form-control" onchange="tampilTgl()">
										<option value="<?php echo str_replace('-', ' ', $this->uri->segment(4)); ?>"><?php echo str_replace('-', ' ', $this->uri->segment(4)); ?></option>
										<?php foreach($location as $item){ ?>
											<option value="<?php echo $item['location'] ?>"><?php echo $item['location'] ?></option>
										<?php } ?>
										</select>
										<span class="help-block"></span>
									</div>
                                    <div class="form-group">
									<label>Tanggal : </label>
										<select id="tanggal" name="tanggal" class="form-control" onchange="tampilTime()">
											<option value="<?php echo $this->uri->segment(5); ?>"><?php echo $this->uri->segment(5); ?></option>
											<?php foreach($tanggal as $items){ ?>
												<option value="<?php echo $items['available_date'] ?>"><?php echo $items['available_date'] ?></option>
											<?php } ?>
										</select>
										<span class="help-block"></span>
                                    </div>
									<div class="form-group">
									<label>Waktu : </label>
										<select id="waktu" name="waktu" class="form-control">
											<option value=""></option>
											<?php foreach($time as $waktu){ ?>
												<option value="<?php echo $waktu['time'] ?>"><?php echo $waktu['time'] ?></option>
											<?php } ?>
										</select>
										<span class="help-block"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Peserta : </label>
                                        <input type="text" name="nama_kasir" class="form-control"/>
                                    </div>
									<div class="form-group">
                                        <label>Jenis Kelamin : </label>
                                        <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
											<option value=""></option>
											<option value="L">Laki - laki</option>
											<option value="P">Perempuan</option>
										</select>
                                    </div>
									<div class="form-group">
                                        <label>Nomor Telp/HP : </label>
                                        <input type="text" name="tlp_kasir" class="form-control"/>
                                    </div>
									<div class="form-group">
                                        <label>Nama Perusahaan : </label>
                                        <input type="text" name="nama_merchant" class="form-control"/>
                                    </div>
									<div class="form-group">
                                        <label>Jabatan : </label>
                                        <input type="text" name="jabatan" class="form-control"/>
                                    </div>
									<div class="form-group">
                                        <label>MID : </label>
                                        <input type="text" name="mid" class="form-control"/>
                                    </div>
                                    <input type="submit" value="Submit" class="btn btn-primary" />
                                    <?php echo form_close();?>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
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

<script type="text/javascript">
	function tampillok()
	{
		var str = document.getElementById('idArea').value;
		rep = str.split(' ').join('-');
		window.location="<?php echo site_url(); ?>registrant/add/"+rep;
		
	}
	function tampilTgl()
	{
		var str = document.getElementById('idArea').value;
		rep = str.split(' ').join('-');
		
		var str1 = document.getElementById('lokasi').value;
		rep1 = str1.split(' ').join('-');
		window.location.href="<?php echo site_url(); ?>registrant/add/"+rep+"/"+rep1;
	}
	function tampilTime()
	{
		var str = document.getElementById('idArea').value;
		rep = str.split(' ').join('-');
		
		var str1 = document.getElementById('lokasi').value;
		rep1 = str1.split(' ').join('-');
		
		str2 = document.getElementById('tanggal').value;
		window.location.href="<?php echo site_url(); ?>registrant/add/"+rep+"/"+rep1+"/"+str2;
	}
	
</script>


