<?php
// $date_from = $this->input->post('date_from');
// $date_to = $this->input->post('date_to');

// if($date_from !='' && $date_to != ''){
// 	$date_from = $this->input->post('date_from');
// 	$date_to = $this->input->post('date_to');
// }else{
// 	$date_from = date('Y-m-01');
// 	$date_to = date('Y-m-d');
// }

$start_date = date('Y-m') . '-01';
$end_date = date('Y-m-d');

?>

<!-- MAIN CONTENT -->
<div id="ribbon">
    <ol class="breadcrumb">
        <i class="fa fa-home"></i> &nbsp;
        <li><a href="<?php echo site_url(); ?>">Home</a></li>
        <li><i class="fa fa-link "></i> &nbsp; Meeting</li>
        <li>Schedule</li>
    </ol>
</div>

<?php if ($this->session->flashdata('message')) { ?>
    <div class="alert alert-success fade in">
        <button class="close" data-dismiss="alert" id="notif">
            ×
        </button>
        <i class="fa-fw fa fa-check"></i>
        <?php echo $this->session->flashdata('message'); ?>
    </div>
<?php } ?>

<div id="content" class="box box-primary">

    <div class="box-header with-border">
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">&nbsp;</i>
            </button>
        </div>
        <h3 class="box-title">Schedule</h3>
    </div>

    <div class="box-header">
        <div class="btn-group m-b-5">
            <a href="<?php echo site_url('meeting/schedule/add_new') ?>" class="btn btn-default" style="padding:5px;"><i class="fa fa-plus"></i> Tambah Schedule</a>
        </div>
    </div>
    <div class="panel-body">
        <!-- begin row -->
        <div class="row">
            <div class="col-sm-12">
                <span class="pull-right">
                    <form id="form_filter" method="post" class="smart-form" novalidate="novalidate">
                        <table>
                            <tr>
                                <td>
                                    <h5 class="txt-color-blueDark">Periode &nbsp; </h5>
                                </td>
                                <td>
                                    <label class="input">
                                        <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>" data-dateformat='yy-mm-dd' class="form-control datepicker" required />
                                        <?php echo form_error('start_date'); ?>
                                    </label>
                                </td>
                                <td>
                                    <h5 class="txt-color-blueDark">&nbsp; S/D &nbsp; </h5>
                                </td>
                                <td>
                                    <label class="input">
                                        <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>" data-dateformat='yy-mm-dd' class="form-control datepicker" required />
                                        <?php echo form_error('end_date'); ?>
                                    </label>
                                </td>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td>
                                    <button type="button" id="btn-filter" class="btn btn-success" onclick="filter_data()" style="padding:5px;"><span class="fa fa-filter"></span> Go</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </span>
                <br />
                <br />
                <table id="data-schedule" class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Meeting</th>
							<th>Hari</th>
                            <th>Tema / Pembahasan</th>
                            
                            <th>Jenis Meeting</th>
                            <th>Lokasi</th>
                            <th>Link</th>
                            <th>Status</th>
                            <th>Action</th>
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
    var table;
    $(document).ready(function() {
        table = $("#data-schedule").DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "<?php echo site_url('meeting/schedule/get_dataschedule') ?>",
                type: 'POST',
                data: function(data) {
                    data.start_date = $('#start_date').val();
                    data.end_date = $('#end_date').val();
                }
            },
            initComplete: function() {
                var input = $('#data-schedule_filter input').unbind(),
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
                $('#data-schedule_filter').append(searchButton);
            }
        });
    });

    function filter_data() {
        table.draw();
    }
</script>