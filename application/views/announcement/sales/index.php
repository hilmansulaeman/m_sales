<!-- MAIN CONTENT -->
<div id="ribbon">
    <ol class="breadcrumb">
        <i class="fa fa-home"></i> &nbsp;
        <li><a href="<?php echo site_url(); ?>">Home</a></li>
        <li><i class="fa fa-link "></i> &nbsp; Announcement Sales</li>

    </ol>
</div>

<div id="content" class="box box-primary">
    <?php if ($this->session->flashdata('message')) { ?>
        <div class="alert alert-success fade in">
            <button class="close" data-dismiss="alert" id="notif">
                ×
            </button>
            <i class="fa-fw fa fa-check"></i>
            <?php echo $this->session->flashdata('message'); ?>
        </div>
    <?php } ?>
    <div class="box-header with-border">
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">&nbsp;</i>
            </button>
        </div>
        <h3 class="box-title">List Pengumuman</h3>
    </div>

    <div class="box-header">
        <div class="btn-group m-b-5">
            <a href="<?php echo site_url() ?>announcement/sales/add" class="btn btn-default" style="padding:5px;"><i class="fa fa-plus"></i> Tambah Pengumuman</a>
        </div>
    </div>
    <div class="panel-body">
        <!-- begin row -->
        <div class="row">
            <div class="col-lg-12">
                <table id="data-location" class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kategori</th>
                            <th>Isi</th>
                            <th>Published</th>
                            <th>Created Date</th>
                            <th>Created By</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6">Loading data from server</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var tableLocation;
    $(document).ready(function() {
        tableLocation = $("#data-location").DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "<?php echo site_url('announcement/sales/get_data_announcement') ?>",
                type: 'POST',
            },
            initComplete: function() {
                var input = $('#data-location_filter input').unbind(),
                    self = this.api(),
                    searchButton = $('<button id="btnSearch" class="btn btn-default active"><i class="fa fa-search"></i></button>')
                    .click(function() {
                        self.search(input.val()).draw();
                    });
                $(document).keypress(function(event) {
                    if (event.which == 13) {
                        searchButton.click();
                    }
                });
                $('#data-location_filter').append(searchButton);
            }
        });
    });
</script>

<script>
    function AddNew() {
        $('#modalAdd').modal('show');
    }

    function ShowDetail(id) {
        $('#modalDetail').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>meeting/location/getDetail/' + id,
            type: 'POST',
            data: $('#frmsave').serialize(),
            success: function(data) {
                $("#pop").html('');
                $("#pop").append(data);
            }
        });
    }

    function ShowEdit(id) {
        $('#modalEdit').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>meeting/location/getEdit/' + id,
            type: 'POST',
            data: $('#frmsave').serialize(),
            success: function(data) {
                $("#pop2").html('');
                $("#pop2").append(data);
            }
        });
    }
</script>

<!-- Modal Add -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Form Input</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo base_url(); ?>meeting/location/save">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-form-label">Nama Lokasi : <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="location_name" class="form-control" required />
                                <span class="input-group-addon"><i class="fa fa-building"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Alamat Lokasi : <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="location_address" class="form-control" required />
                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Kota : <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="location_city" class="form-control" required />
                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                            </div>
                        </div>
                        <!-- <div class="form-group">
							<label class="col-form-label">Kuota : <span class="text-danger">*</span></label>
							<div class="input-group">
								<input type="text" name="quota" oninput="this.value=this.value.replace(/[^0-9]/g,'')" class="form-control" required />
								<span class="input-group-addon"><i class="fa fa-users"></i></span>
							</div>
						</div> -->
                        <button type="submit" class="btn btn-sm btn-primary m-r-5">Save</button>
                    </fieldset>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-eye"></i> Detail Data</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <div id="pop">
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
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil-alt"></i> Form Ubah Data</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <div id="pop2">
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->