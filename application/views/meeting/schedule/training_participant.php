<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Bucket</a></li>
		<li class="breadcrumb-item active">Schedule</li>
		<li class="breadcrumb-item active">Trainig Participant</li>
	</ol>
	
	<!-- begin page-header -->
	<h1 class="page-header">
		PESERTA TRAINING
		<small>&nbsp;</small>
	</h1>
	<!-- end page-header -->

	<!-- begin row -->
	<div class="row">
		<div col class="col-lg-12 m-b-5">
			<?php if ($this->session->flashdata('message')) { ?>
			<div class="alert alert-success fade show">
			  <span class="close" data-dismiss="alert">×</span>
			  <strong>Info!</strong>
			  <?php echo $this->session->flashdata('message'); ?>
			</div>
			<?php }?>
		</div>
		<div class="col-lg-8">
			<div class="panel panel-inverse" data-sortable-id="ui-widget-1">
				<div class="panel-heading">
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
					<h4 class="panel-title">FASILITAS & KELAS</h4>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>Ruangan</th>
									<th>Jam</th>
									<th>Hari</th>
									<th>Tanggal</th>
									<th>Trainer</th>
								</tr>
							</thead>
							<tbody>
							<?php
								$jadwal = $query_schedule->row();
							?>
								<tr>
									<td><?php echo $jadwal->Room_Name; ?></td>
									<td><?php echo $jadwal->Training_Time; ?></td>
									<td><?php echo $jadwal->Training_Day; ?></td>
									<td><?php echo date('d-M-Y', strtotime($jadwal->Training_Date)); ?></td>
									<td><?php echo $jadwal->Trainer_Name; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="panel panel-success" data-sortable-id="ui-widget-1">
				<div class="panel-heading">
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
					<h4 class="panel-title">MODUL</h4>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>No.</th>
									<th>Modul</th>
								</tr>
							</thead>
							<tbody>
							<?php
								$no_mdl = 0;
								foreach ($query_modul->result() as $mdl) {
							?>
								<tr>
									<td><?php echo ++$no_mdl; ?></td>
									<td><?php echo $mdl->Module_Name; ?></td>
								</tr>
							<?php
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		<!-- begin col-6 -->
		<div class="col-lg-12">
			<!-- begin nav-tabs -->
			<ul class="nav nav-tabs">
				<li class="nav-items">
					<a href="#default-tab-1" data-toggle="tab" class="nav-link active">
						<span class="d-sm-none">Tab 1</span>
						<span class="d-sm-block d-none">Basic Participants</span>
					</a>
				</li>
				<li class="nav-items">
					<a href="#default-tab-2" data-toggle="tab" class="nav-link">
						<span class="d-sm-none">Tab 2</span>
						<span class="d-sm-block d-none">No Assignment</span>
					</a>
				</li>
			</ul>
			<!-- end nav-tabs -->
			<!-- begin tab-content -->
			<div class="tab-content">
				<!-- begin tab-pane -->
				<div class="tab-pane fade active show" id="default-tab-1" >
					<div class="table-responsive">
						<table class="table" id="data-table-default">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>No Hp</th>
									<th>Produk</th>
									<th>Absen Status</th>
									<th>Score</th>
									<th>Result</th>
									<th>Feedback</th>
									<th>No Sunvote</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							<?php
								$no_par = 0;
								foreach ($query_participant->result() as $parti) {
									if($parti->Training_Result == "Tidak Lolos")
									{
										$btn_schedule = "";
									}
									else
									{
										$btn_schedule = "style='display:none;'";
									}
							?>
								<tr>
									<td><?php echo ++$no_par; ?></td>
									<td><?php echo $parti->Name; ?></td>
									<td><?php echo $parti->Mobile_Phone_Number; ?></td>
									<td><?php echo $parti->Product; ?></td>
									<td><?php echo $parti->Absent_Status; ?></td>
									<td><?php echo $parti->Score; ?></td>
									<td><?php echo $parti->Training_Result; ?></td>
									<td><?php echo $parti->Feedback; ?></td>
									<td><?php echo $parti->No_Sunvote; ?></td>
									<td>
										<a href="javascript:;" onclick="getForm('<?php echo $parti->Participant_ID; ?>', '<?php echo $parti->Recruitment_ID; ?>', '<?php echo $parti->Absent_Status ?>', '<?php echo $parti->Score; ?>', '<?php echo $parti->Training_Result; ?>', '<?php echo $parti->Name; ?>')" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
										<a href="javascript:void(0);" <?php echo $btn_schedule; ?> onclick="showSchedule('<?php echo $parti->Participant_ID; ?>')" class="btn btn-warning btn-xs">
											<i class="fa fa-calendar"></i>
										</a>
										<a href="javascript:void(0);" onclick="getFormSunvote('<?php echo $parti->Participant_ID; ?>', '<?php echo $parti->Name; ?>')" class="btn btn-danger btn-xs" title="Update Nomor Sunvote">
											<i class="fa fa-newspaper"></i>
										</a>
									</td>
								</tr>
							<?php
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- end tab-pane -->
				<!-- begin tab-pane -->
				<div class="tab-pane fade" id="default-tab-2">
					<div class="table-responsive">
						<a href="javascript:void(0);" onclick="ShowAddForm();" class="btn btn-primary m-b-5">Add Participant</a>
						<table class="table" id="example2">
							<thead>
								<tr>
									<th>No</th>
									<th>Sales Code</th>
									<th>Name</th>
									<th>Mobile Phone Number</th>
									<th>Product</th>
									<th>Absent Status</th>
									<th>Score</th>
									<th>Training Result</th>
									<th>Feedback</th>
									<th>No Sunvote</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							<?php
								$no_ = 0;
								foreach($query_participant_rest->result() as $row2)
								{
							?>
								<tr>
									<td><?php echo ++$no_; ?></td>
									<td><?php echo $row2->Sales_Code; ?></td>
									<td><?php echo $row2->Name; ?></td>
									<td><?php echo $row2->Mobile_Phone_Number; ?></td>
									<td><?php echo $row2->Product; ?></td>
									<td><?php echo $row2->Absent_Status; ?></td>
									<td><?php echo $row2->Score; ?></td>
									<td><?php echo $row2->Training_Result; ?></td>
									<td><?php echo $row2->Feedback; ?></td>
									<td><?php echo $row2->No_Sunvote; ?></td>
									<td>
										<a href="javascript:;" onclick="getFormRest('<?php echo $row2->Participant_ID_Rest; ?>', '<?php echo $row2->Name ?>')" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
										<a href="javascript:void(0);" onclick="getFormSunvoteRest('<?php echo $row2->Participant_ID_Rest; ?>', '<?php echo $row2->Name ?>')" class="btn btn-danger btn-xs" title="Update Nomor Sunvote">
											<i class="fa fa-newspaper"></i>
										</a>
									</td>
								</tr>
							<?php
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- end tab-content -->
		</div>
		<!-- end col-6 -->
	</div>
</div>
<script type="text/javascript">
	function getFormSunvoteRest(Participant_ID_Rest, Name)
	{
		$('#modalSunvote2').modal('show');
		document.getElementById('Participant_ID_Rest2').value = Participant_ID_Rest;
		document.getElementById('Name3').value = Name;
	}
	
	function getFormRest(Participant_ID_Rest, Name)
	{
		$('#modalEdit2').modal('show');
		document.getElementById('Participant_ID_Rest').value = Participant_ID_Rest;
		document.getElementById('Name2').value = Name;
	}
	
	function ShowAddForm()
	{
		$('#modalAddParticipantNew').modal('show');
	}
	
	function getFormSunvote(Participant_ID, Name)
	{
		$('#modalSunvote').modal('show');
		document.getElementById('Name_Participant2').value = Name;
		document.getElementById('Participant_ID').value = Participant_ID;
	}

	function showSchedule(Participant_ID)
	{
		$('#modalSchedule').modal('show');
		document.getElementById('render_id_participant').value = Participant_ID;
	}

	function lihatLagi(date)
	{
		$('#tbl_'+ date).toggle();
	}

	function getForm(Participant_ID, Recruitment_ID, Absent_Status, Score, Training_Result, Name)
	{
		$('#modalEdit').modal('show');
		document.getElementById('Participant_ID2').value = Participant_ID;
		document.getElementById('Recruitment_ID2').value = Recruitment_ID;
		document.getElementById('Absent_Status').value = Absent_Status;
		document.getElementById('Score').value = Score;
		document.getElementById('Name_Participant2').value = Name;
		document.getElementById('Training_Result').value = Training_Result;
	}

	function UpdateSchedule(Schedule_ID, Training_Date)
	{
		var Participant_ID = document.getElementById('render_id_participant').value;
		location.href = '<?php echo site_url() ?>schedule/reschedule_logs/'+ Schedule_ID +'/'+ Training_Date +'/'+ Participant_ID;
	}
</script>
<!-- Modal Add New Participant-->
<div class="modal fade" id="modalAddParticipantNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Form Add New Participant</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo site_url(); ?>schedule/insert_new_participant/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>">
					<fieldset>
						<input type="hidden" name="Participant_ID" id="Participant_ID">
						<div class="form-group">
							<label class="col-form-label">Sales Code :</label>
							<div class="input-group">
								<input type="text" name="Sales_Code" id="Name_Participant" class="form-control" />
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Name :</label>
							<div class="input-group">
								<input type="text" name="Name" id="Name_Participant" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Mobile Phone Number :</label>
							<div class="input-group">
								<input type="text" name="Mobile_Phone_Number" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-newspaper"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Product :</label>
							<div class="input-group">
								<input type="text" name="Product" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-newspaper"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Assignment Date :</label>
							<div class="input-group">
								<input type="text" name="Assignment_Date" class="form-control" id="datepicker-autoClose"/>
								<span class="input-group-addon"><i class="fa fa-newspaper"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Assignment By :</label>
							<div class="input-group">
								<input type="text" name="Assignment_By" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-newspaper"></i></span>
							</div>
						</div>
						<button type="submit" class="btn btn-sm btn-primary m-r-5 pull-right">Submit</button>
					</fieldset>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Sunvote2 -->
<div class="modal fade" id="modalSunvote2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Form Update Nomor Sunvote</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url(); ?>schedule/update_nosunvote2/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>">
					<fieldset>
						<input type="hidden" name="Participant_ID_Rest2" id="Participant_ID_Rest2">
						<div class="form-group">
							<label class="col-form-label">Name :</label>
							<div class="input-group">
								<input type="text" name="Name" id="Name3" class="form-control" readonly="true" />
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Nomor Sunvote :</label>
							<div class="input-group">
								<input type="text" name="No_Sunvote2" id="No_Sunvote2" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-newspaper"></i></span>
							</div>
						</div>
						<button type="submit" class="btn btn-sm btn-primary m-r-5 pull-right">Update</button>
					</fieldset>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Sunvote -->
<div class="modal fade" id="modalSunvote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Form Update Nomor Sunvote</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url(); ?>schedule/update_nosunvote/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>">
					<fieldset>
						<input type="hidden" name="Participant_ID" id="Participant_ID">
						<div class="form-group">
							<label class="col-form-label">Name :</label>
							<div class="input-group">
								<input type="text" name="Name" id="Name_Participant2" class="form-control" readonly="true" />
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Nomor Sunvote :</label>
							<div class="input-group">
								<input type="text" name="No_Sunvote" id="No_Sunvote2" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-newspaper"></i></span>
							</div>
						</div>
						<button type="submit" class="btn btn-sm btn-primary m-r-5 pull-right">Update</button>
					</fieldset>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Modal Schedule -->
<div class="modal fade" id="modalSchedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Daftar Tanggal Training</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" name="" id="render_id_participant">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>No.</th>
								<th>Tanggal Training</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$no_schedule = 0;
							foreach ($query_daftar_schedule->result() as $val) {
						?>
							<tr>
								<td><?php echo ++$no_schedule; ?></td>
								<td><b><?php echo $val->Training_Day; ?>, <?php echo date('d-M-Y', strtotime($val->Training_Date)); ?></b></td>
								<td>
									<a href="javascript:void(0);" onclick="lihatLagi('<?php echo $val->Training_Date; ?>');" class="btn btn-primary btn-sm">Pilih</a>
								</td>
							</tr>
							<tr style="display: none;" id="tbl_<?php echo $val->Training_Date ?>">
								<td colspan="3">
									<table class="table">
										<thead>
											<tr>
												<th>No</th>
												<th>Ruangan</th>
												<th>Lokasi</th>
												<th>Jam</th>
												<th>Trainer</th>
												<th>Modul</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
										<?php
											$no_det = 0;
											$query_sch_det = $this->schedule_model->getScheduleDetailPertanggal($val->Training_Date);
											foreach ($query_sch_det->result() as $det) {
										?>
											<tr>
												<td><?php echo ++$no_det; ?></td>
												<td><?php echo $det->Room_Name; ?></td>
												<td><?php echo $det->Room_Location; ?></td>
												<td><?php echo $det->Training_Time; ?></td>
												<td><?php echo $det->Trainer_Name; ?></td>
												<td>-</td>
												<td>
													<a href="javascript:void(0);" onclick="UpdateSchedule('<?php echo $det->Schedule_ID ?>', '<?php echo $det->Training_Date; ?>')" class="btn btn-success btn-sm">Pilih</a>
												</td>
											</tr>
										<?php
											}
										?>
										</tbody>
									</table>
								</td>
							</tr>
						<?php
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Form Edit</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo site_url('schedule/update_attendance'); ?>">
					<input type="hidden" name="Schedule_ID" id="Schedule_ID" value="<?php echo $this->uri->segment(3); ?>">
					<input type="hidden" name="Tanggal" id="Tanggal" value="<?php echo $this->uri->segment(4); ?>">
					<input type="hidden" name="Participant_ID" id="Participant_ID2">
					<input type="hidden" name="Recruitment_ID" id="Recruitment_ID2">
					<fieldset>
						<div class="form-group">
							<label class="col-form-label">Name :</label>
							<div class="input-group">
								<input type="text" name="Name" id="Name_Participant2" class="form-control" readonly="true" />
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Absent Status : <span style="color:red; font-size: 11px;"> * Jika Hadir Ketik REGISTERED, Tidak Hadir Kosongkan</span></label>
							<div class="input-group">
								<input type="text" name="Absent_Status" id="Absent_Status" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-check"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Score :</label>
							<div class="input-group">
								<input type="text" name="Score" id="Score" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-address-book"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Training Result : <span style="color:red; font-size: 11px;"> * Pilih Lolos / Tidak Lolos</span></label>
							<div class="input-group">
								<select name="Training_Result" id="Training_Result" class="form-control" >
									<option value="">-- Pilih --</option>
									<option value="Lolos">Lolos</option>
									<option value="Tidak Lolos">Tidak Lolos</option>
								</select>
								<span class="input-group-addon"><i class="fa fa-clipboard"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Feedback : <span style="color:red; font-size: 11px;"> * Pilih Ya / Tidak</span></label>
							<div class="input-group">
								<select name="Feedback" id="Feedback" class="form-control">
									<option value="">-- Pilih --</option>
									<option value="Ya">Ya</option>
									<option value="Tidak">Tidak</option>
								</select>
								<span class="input-group-addon"><i class="fa fa-clipboard"></i></span>
							</div>
						</div>
						<button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
					</fieldset>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Edit2 -->
<div class="modal fade" id="modalEdit2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Form Edit</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url(); ?>schedule/update_attendance2">
					<input type="hidden" name="Schedule_ID" id="Schedule_ID" value="<?php echo $this->uri->segment(3); ?>">
					<input type="hidden" name="Tanggal" id="Tanggal" value="<?php echo $this->uri->segment(4); ?>">
					<input type="hidden" name="Participant_ID_Rest" id="Participant_ID_Rest">
					<fieldset>
						<div class="form-group">
							<label class="col-form-label">Name :</label>
							<div class="input-group">
								<input type="text" name="Name" id="Name2" class="form-control" readonly="true" />
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Absent Status : <span style="color:red; font-size: 11px;"> * Jika Hadir Ketik REGISTERED, Tidak Hadir Kosongkan</span></label>
							<div class="input-group">
								<input type="text" name="Absent_Status2" id="Absent_status2" value="" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-check"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Score :</label>
							<div class="input-group">
								<input type="text" name="Score2" id="Score2" class="form-control"/>
								<span class="input-group-addon"><i class="fa fa-address-book"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Training Result : <span style="color:red; font-size: 11px;"> * Pilih Lolos / Tidak Lolos</span></label>
							<div class="input-group">
								<select name="Training_Result2" id="Training_Result2" class="form-control" >
									<option value="">-- Pilih --</option>
									<option value="Lolos">Lolos</option>
									<option value="Tidak Lolos">Tidak Lolos</option>
								</select>
								<span class="input-group-addon"><i class="fa fa-clipboard"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label">Feedback : <span style="color:red; font-size: 11px;"> * Pilih Ya / Tidak</span></label>
							<div class="input-group">
								<select name="Feedback2" id="Feedback2" class="form-control">
									<option value="">-- Pilih --</option>
									<option value="Ya">Ya</option>
									<option value="Tidak">Tidak</option>
								</select>
								<span class="input-group-addon"><i class="fa fa-clipboard"></i></span>
							</div>
						</div>
						<button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
					</fieldset>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->