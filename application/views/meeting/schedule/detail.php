<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Bucket</a></li>
		<li class="breadcrumb-item">Schedule</li>
		<li class="breadcrumb-item active">Detail Data</li>
	</ol>
	
	<!-- begin page-header -->
	<h1 class="page-header">Detail Schedule <small>&nbsp;</small></h1>
	<!-- end page-header -->
	
	<!-- begin row -->
	<div class="row">
		<div class="col-lg-12">
			<?php if ($this->session->flashdata('message')) { ?>
			<div class="alert alert-success fade show">
			  <span class="close" data-dismiss="alert">×</span>
			  <strong>Success!</strong>
			  <?php echo $this->session->flashdata('message'); ?>
			</div>
			<?php }?>
		</div>
		
		<div class="col-lg-12">
			<!-- begin btn-group -->
			<div class="btn-group m-b-20">
				<a href="javascript:;" class="btn btn-white btn-white-without-border"><i class="fa fa-caret-right"></i></a>
				<a href="javascript:void(0);" onclick="ShowSchedule2();" class="btn btn-success btn-primary-without-border"><i class="fa fa-plus"></i></a>
			</div>
			<!-- end btn-group -->
		</div>
		<?php
			foreach($query_schedule->result() as $sch)
			{
				$arr_condition = $this->schedule_model->getParticipant($sch->Schedule_ID);
				if($arr_condition->num_rows() > 0)
				{
					$btn = "primary";
				}
				else
				{
					$btn = "warning";
				}
		?>
			<div class="col-lg-4">
				<div class="panel panel-inverse" data-sortable-id="ui-widget-1">
					<div class="panel-heading">
						<div class="panel-heading-btn">
							<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
							<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
						</div>
						<h4 class="panel-title"><?php echo $sch->Room_Name; ?></h4>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-hover">
								<tr>
									<td><b>Jam</b></td>
									<td><b>:</b></td>
									<td><?php echo $sch->Training_Time; ?></td>
								</tr>
								<tr>
									<td><b>Hari</b></td>
									<td><b>:</b></td>
									<td><?php echo $sch->Training_Day; ?></td>
								</tr>
								<tr>
									<td><b>Tanggal</b></td>
									<td><b>:</b></td>
									<td><?php echo date('d-M-Y', strtotime($sch->Training_Date)); ?></td>
								</tr>
								<tr>
									<td><b>Trainer</b></td>
									<td><b>:</b></td>
									<td><?php echo $sch->Trainer_Name; ?></td>
								</tr>
								<tr>
									<td><b>Lokasi</b></td>
									<td><b>:</b></td>
									<td><?php echo $sch->Room_Location; ?></td>
								</tr>
								<tr>
									<td><b>Quota</b></td>
									<td><b>:</b></td>
									<td><?php echo $sch->Quota; ?></td>
								</tr>
								<tr>
									<td><b>Participants</b></td>
									<td><b>:</b></td>
									<td><?php echo $sch->total_participant; ?></td>
								</tr>
								<tr>
									<td><b>Registered</b></td>
									<td><b>:</b></td>
									<td><?php echo $sch->regristred_participant; ?></td>
								</tr>
								<tr>
									<td>
										<a href="<?php echo site_url(); ?>schedule/training_participant/<?php echo $sch->Schedule_ID; ?>/<?php echo $this->uri->segment(3); ?>" class="btn btn-<?php echo $btn; ?> btn-sm"><i class="fa fa-eye"></i> Detail</a>
									</td>
									<td></td>
									<td></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		<?php
			}
		?>
	</div>
</div>

<script>
	function ShowSchedule()
	{
		$('#modalSchedule').modal('show');
	}

	function ShowSchedule2()
	{
		$('#modalSchedule2').modal('show');
	}

	function ShowPenilaian()
	{
		$('#modalPenilaian').modal('show');
	}
</script>

<!-- Modal Schedule2 -->
<div class="modal fade" id="modalSchedule2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-calendar"></i> Form Input Fasilitas & Kelas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url(); ?>schedule/save/<?php echo $this->uri->segment(3); ?>">
					<fieldset>
						<div class="form-group">
							<label>Training Date :</label>
							<input type="text" name="training_date" class="form-control" value="<?php echo $this->uri->segment(3); ?>" readonly/>
						</div>
						<div class="form-group">
							<label>Training Time :</label>
							<div class="input-group date" id="datetimepicker2">
								<input type="text" name="training_time" class="form-control" />
								<span class="input-group-addon">
								<i class="fa fa-clock"></i>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label>Trainer Name :</label>
							<select class="form-control form-control" name="trainer_code">
								<option value="">Choose</option>
								<?php
									foreach($query_trainer->result() as $tr)
									{
								?>
									<option value="<?php echo $tr->nik; ?>"><?php echo $tr->name; ?></option>
								<?php
									}
								?>
							</select>
						</div>

						<div class="panel panel-primary" data-sortable-id="ui-widget-1">
						  <div class="panel-heading">
						    <div class="panel-heading-btn">
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
						    </div>
						    <h4 class="panel-title">RUANGAN</h4>
						  </div>
						  <div class="panel-body">
						    <table class="table">
								<thead>
									<tr>
										<th>No.</th>
										<th>Room Name</th>
										<th>Locations</th>
										<th>Quota</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$no_room = 0;
									foreach($query_room->result() as $rooms)
									{
								?>
									<tr>
										<td><?php echo ++$no_room; ?></td>
										<td><?php echo $rooms->Room_Name; ?></td>
										<td><?php echo $rooms->Room_Location; ?></td>
										<td><?php echo $rooms->Quota; ?></td>
										<td>
											<div class="radio radio-css">
											  <input type="radio" name="room" id="cssRadio<?php echo $no_room; ?>" value="<?php echo $rooms->Room_ID; ?>"/>
											  <label for="cssRadio<?php echo $no_room; ?>">Pilih</label>
											</div>
										</td>
									</tr>
								<?php
									}
								?>
								</tbody>
							</table>
						  </div>
						</div>

						<div class="panel panel-success" data-sortable-id="ui-widget-1">
						  <div class="panel-heading">
						    <div class="panel-heading-btn">
						      	<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
						    </div>
						    <h4 class="panel-title">MODUL</h4>
						  </div>
						  <div class="panel-body">
						    <table class="table">
								<thead>
									<tr>
										<th>No</th>
										<th>Modules</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$no_modul = 0;
									foreach($query_modul->result() as $moduls)
									{
								?>
									<tr>
										<td><?php echo ++$no_modul; ?></td>
										<td><?php echo $moduls->Module_Name; ?></td>
										<td>
											<div class="checkbox checkbox-css">
											  <input type="checkbox" name="module[]" value="<?php echo $moduls->Module_ID; ?>" id="cssCheckbox<?php echo $no_modul; ?>"/>
											  <label for="cssCheckbox<?php echo $no_modul; ?>">Pilih</label>
											</div>
										</td>
									</tr>
								<?php
									}
								?>
								</tbody>
							</table>
						  </div>
						</div>
					</fieldset>
					<p class="text-right m-b-0">
						<a href="javascript:;" class="btn btn-white m-r-5">Cancel</a>
						<button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
					</p>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Schedule -->
<div class="modal fade" id="modalSchedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-calendar"></i> Form Input Fasilitas & Kelas</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url(); ?>schedule/save/<?php echo $this->uri->segment(3); ?>">
					<ul class="nav nav-tabs">
						<li class="nav-items">
							<a href="#default-tab-1" data-toggle="tab" class="nav-link active">
								<span class="d-sm-none">Tab 1</span>
								<span class="d-sm-block d-none">1. Schedule</span>
							</a>
						</li>
						<li class="nav-items">
							<a href="#default-tab-2" data-toggle="tab" class="nav-link">
								<span class="d-sm-none">Tab 2</span>
								<span class="d-sm-block d-none">2. Room</span>
							</a>
						</li>
						<li class="nav-items">
							<a href="#default-tab-3" data-toggle="tab" class="nav-link">
								<span class="d-sm-none">Tab 3</span>
								<span class="d-sm-block d-none">3. Modules</span>
							</a>
						</li>
					</ul>
					<!-- end nav-tabs -->
					<!-- begin tab-content -->
					<div class="tab-content">
						<!-- begin tab-pane -->
						<div class="tab-pane fade active show" id="default-tab-1">
							<fieldset>
								<div class="form-group">
									<label>Training Date :</label>
									<input type="text" name="training_date" class="form-control" value="<?php echo $this->uri->segment(3); ?>" readonly/>
								</div>
								<div class="form-group">
									<label>Training Time :</label>
									<input type="text" name="training_time" class="form-control" placeholder="Example : 10:30" />
								</div>
								<div class="form-group">
									<label>Trainer Name :</label>
									<select class="form-control form-control" name="trainer_code">
										<option value="">Choose</option>
										<?php
											foreach($query_trainer->result() as $tr)
											{
										?>
											<option value="<?php echo $tr->nik; ?>"><?php echo $tr->name; ?></option>
										<?php
											}
										?>
									</select>
								</div>
							</fieldset>
						</div>
						<!-- end tab-pane -->
						<!-- begin tab-pane -->
						<div class="tab-pane fade" id="default-tab-2">
							
						</div>
						<!-- end tab-pane -->
						<!-- begin tab-pane -->
						<div class="tab-pane fade" id="default-tab-3">
							
							<p class="text-right m-b-0">
								<a href="javascript:;" class="btn btn-white m-r-5">Cancel</a>
								<button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
							</p>
						</div>
						<!-- end tab-pane -->
					</div>
					<!-- end tab-content -->
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Modal Schedule -->
<div class="modal fade" id="modalPenilaian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-calendar-check"></i> Form Input Absen & Score</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form>
					<fieldset>
						<div class="form-group">
							<label>Absent :</label>
							<select class="form-control form-control">
								<option>-- Choose --</option>
								<option>Absent 1</option>
								<option>Absent 2</option>
							</select>
						</div>
						<div class="form-group">
							<label>Score :</label>
							<input type="text" class="form-control" id="datepicker-autoClose"/>
						</div>
						<a href="javascript:;" class="btn btn-primary pull-right">Submit</a>
					</fieldset>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->