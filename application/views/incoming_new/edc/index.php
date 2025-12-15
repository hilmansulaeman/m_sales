
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li class="breadcrumb-item"><a href="javascript:;">Tables</a></li>
				<li class="breadcrumb-item"><a href="javascript:;">Merchant</a></li>
			</ol>
            <!-- end breadcrumb -->
            <div class='row'>
                <!-- begin page-header -->
                <div class="col-lg-3">
                    <h1 class="page-header">
					<a href="<?= site_url('incoming/edc/get_status/'.$this->uri->segment(5).'/'.$this->uri->segment(6)) ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                    </h1>
                </div>

                <!-- notif -->
                    <?php echo $this->session->flashdata('message'); ?>
                <!-- end notif -->
            </div>
			<!-- begin row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- begin panel -->
					<div class="panel panel-inverse">
						<!-- begin panel-heading -->
						<div class="panel-heading">
							<h4 class="panel-title">
								<?php
									$uri_4_ = $this->uri->segment(4);
									$uri_4 = str_replace('_', ' ', $uri_4_);
									echo "<b>DATA ".strtoupper($uri_4)."</b>";
								;?>
							</h4>
						</div>
						<!-- end panel-heading -->
						<!-- begin panel-body -->
						<div class="panel-body">
							<table id="data-incoming" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th width="1%">No</th>
										<th>Data</th>
										<!--<th>Nama Merchant</th>
										<th>Nama OWner</th>
										<th>No. Handphone</th>
										<th>No. Rekening</th>
										<th>MID Type</th>-->
									</tr>
								</thead>
								<tbody>
									<tr>
                                         <td colspan="11">Loading data from server</td>       
                                    </tr>
								 
								</tbody>
							</table>
							
						</div>
						<!-- end panel-body -->
					</div>
					<!-- end panel -->
				</div>
				<!-- end col-10 -->

			</div>
			<!-- end row -->

<script type="text/javascript">
var table;
$(document).ready(function() {
    table = $("#data-incoming").DataTable({
        ordering: false,
        //searching:false,
        processing: true,
        serverSide: true,
        responsive:true,
        ajax: {
          url: "<?php echo site_url('incoming/edc/get_data_incoming') ?>",
          type:'POST',
          /*data: function ( data ) {
                data.created_date = $('#created_date').val();
            }*/
        },
        initComplete : function() {
            var input = $('#data-incoming_filter input').unbind(),
                self = this.api(),
                searchButton = $('<span id="btnSearch" class="btn btn-default active"><i class="fa fa-search"></i></span>')
                           .click(function() {
                              self.search(input.val()).draw();
                           });
                $(document).keypress(function (event) {
                    if (event.which == 13) {
                        searchButton.click();
                    }
                });
            $('#data-incoming_filter').append(searchButton);
        }
    });
});
</script>