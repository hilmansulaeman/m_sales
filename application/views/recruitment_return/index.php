<!-- MAIN CONTENT -->
<div id="ribbon">
    <ol class="breadcrumb">
        <i class="fa fa-home"></i> &nbsp;
        <li><a href="<?php echo site_url(); ?>">Home</a></li>
        <li>Data Pelamar</li>
    </ol>
</div>

<div class="box box-primary">
    <?php if ($this->session->flashdata('message')) { ?>
        <div class="alert alert-warning fade in">
            <button class="close" data-dismiss="alert" id="notif">
                ×
            </button>
            <?php echo $this->session->flashdata('message'); ?>
        </div>
    <?php } ?>
    <div class="box-header with-border">
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">&nbsp;</i>
            </button>
        </div>
        <h3 class="box-title">Data Return </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-12">
                <h1 class="page-title txt-color-blueDark"><i class="fa fa-bank fa-fw "></i>
                    <b>List Data Return</b>
                </h1>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="data-table-customer" class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Recruitment ID</th>
                                <th>Name</th>
                                <th>Product</th>
                                <th>Area</th>
                                <th>Position</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" align="center">Loading data from server</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $("#data-table-customer").DataTable({
            ordering: false,
            //searching:false,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "<?php echo site_url('recruitment_return/get_data') ?>",
                type: 'POST',
                /*data: function ( data ) {
                data.created_date = $('#created_date').val();
            }*/
            },
            initComplete: function() {
                var input = $('#data-table-customer_filter input').unbind(),
                    self = this.api(),
                    searchButton = $('<span id="btnSearch" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></span>')
                    .click(function() {
                        self.search(input.val()).draw();
                    });
                $(document).keypress(function(event) {
                    if (event.which == 13) {
                        searchButton.click();
                    }
                });
                $('#data-table-customer_filter').append(searchButton);
            }
        });
    });
</script>