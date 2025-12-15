<?php
    $ex = explode('-', $tanggal1);
    $tgl1 = $ex[2];
    $bln = $ex[1];
    $thn = $ex[0];
    
    $ex2 = explode('-', $tanggal2);
    $tgl2 = $ex2[2];
    $bulan = array(
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    );
    $periode = $tgl1.' - '.$tgl2.' / '.$bulan[$bln].' / '.$thn;
?>



    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <a href="<?php echo site_url('request/exit_form/add'); ?>" class="btn btn-header btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-plus"></i></span> Add Request</a>
        <a href="<?= site_url('request/exit_form') ?>" class="btn btn-info">Refresh <i class="fa fa-refresh"></i></a>
        <a href="javascript:void(0);" onclick="Filter()" class="btn btn-warning">Filter <i class="fa fa-filter"></i></a>
		    <?php echo $this->session->flashdata('message'); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Data Request</li>
        <li class="active">Exit Form</li>
      </ol>
    </section>



    <!-- Main content -->
    <section class="content">

      <div class="row">

        <div class="col-xs-12">
    
          <div class="alert alert-info alert-dismissible">
            <h4><i class="icon fa fa-info"></i> Periode : <?php echo $periode; ?></h4>
          </div>
          
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Request Exit Form</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="data-request" class="table table-responsive table-bordered table-striped">
                <thead>
	                <tr>
                        <th width="1%"></th>
                        <th>Efective Date</th>
	                    <th>Created Date</th>
                        <th>Created By</th>
                        <th width="12%">Jumlah Data</th>
                        <th width="9%">Approve</th>
                        <th width="9%">Reject</th>
                        <th width="9%">Cancel</th>
                        <th width="10%">Action</th>
	                </tr>
                </thead>

                <tbody>
	                <tr>
                        <td colspan="8">Loading data from server</td>      
	                </tr>
            	</tbody>
              </table>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>





<script type="text/javascript">
  var table;
  $(document).ready(function() {
      table = $("#data-request").DataTable({
          ordering: false,
          //searching:false,
          processing: true,
          serverSide: true,
          responsive:true,
          ajax: {
            url: "<?php echo site_url('request/exit_form/get_data_exit_filter') ?>",
            type:'POST',
            data: function ( data ) {
                  data.startDate = $('#startDate').val();
              }
          },
          initComplete : function() {
              var input = $('#data-request_filter input').unbind(),
                  self = this.api(),
                  searchButton = $('<span id="btnSearch" class="btn btn-sm btn-default active"><i class="fa fa-search"></i></span>')
                            .click(function() {
                                self.search(input.val()).draw();
                            });
                  $(document).keypress(function (event) {
                      if (event.which == 13) {
                          searchButton.click();
                      }
                  });
              $('#data-request_filter').append(searchButton);
          }
      });

      $('#filter').on('click', function () {
          table.ajax.reload();
		$('#modalFilter').modal('hide');
      });
  });

  
	
	function Filter()
	{
		//filter_incoming
		$('#modalFilter').modal('show');
	}
	
	function datedif(tgl1, tgl2){
		// varibel miliday sebagai pembagi untuk menghasilkan hari
		var miliday = 24 * 60 * 60 * 1000;
		//buat object Date
		var tanggal1 = new Date(tgl1);
		var tanggal2 = new Date(tgl2);
		// Date.parse akan menghasilkan nilai bernilai integer dalam bentuk milisecond
		var tglPertama = Date.parse(tanggal1);
		var tglKedua = Date.parse(tanggal2);
		var selisih = (tglKedua - tglPertama) / miliday;
		return selisih;
	}
	
	function submit_filter()
	{
		var tgl1 = document.getElementById('dt_startDate').value;
		var tgl2 = document.getElementById('dt_endDate').value;
		var days = datedif(tgl1,tgl2);
		if(tgl1 == "" || tgl2 ==  ""){
			document.getElementById('alert1').style.display= 'none';
			document.getElementById('alert2').style.display= 'block';
			document.getElementById('alert3').style.display= 'none';
		}
		else if(days > 31){
		    document.getElementById('alert1').style.display= 'none';
			document.getElementById('alert2').style.display= 'none';
			document.getElementById('alert3').style.display= 'block';
		}
		else{
			window.location='<?php echo base_url() ?>request/exit_form/filter_request/'+ tgl1 +'/'+ tgl2;
		}
		
	}


</script>





<!-- Modal Add -->
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Filter Form</h4>
            </div>
			<div class="modal-body">
				<!-- <form method="post" action="<?php echo site_url(); ?>insentif/filter_performance"> -->
					<div class="form-group">		 
						<div class="input-group">
							<label>Start Date </label>
						</div>	
						<div class="input-group">
							<label class="input-group-btn" for="dt_startDate">
								<span class="btn btn-default">
									<span class="fa fa-calendar"></span>
								</span>
							</label>
							<input type="text" name="start_date" readonly id="dt_startDate" class="form-control tanggal" value="<?php echo $this->input->post('start_date'); ?>" autocomplete="off"/>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label>End Date</label>
						</div>
						<div class="input-group">
							<label class="input-group-btn" for="dt_endDate">
								<span class="btn btn-default">
									<span class="fa fa-calendar"></span>
								</span>
							</label>
							<input type="text" name="end_date" readonly id="dt_endDate" class="form-control tanggal" value="<?php echo $this->input->post('end_date'); ?>" autocomplete="off"/>
						</div>			 
					</div>
					<!--<input type="submit" class="btn btn-primary btn-sm" value="Go"/>-->
					<button type="button" id="filter" class="btn btn-primary btn-sm">Go</button>
				<!-- </form> -->
				<br>
				<br>
				<div class="alert alert-info alert-dismissible" style="display:block;" id="alert1">
					<h5><i class="icon fa fa-info"></i> Tanggal Tidak Boleh Kosong. <br> 
					    <i class="icon fa fa-info"></i> Range Tanggal maksimal 31 hari
					</h5>
				</div>
				<div class="alert alert-danger alert-dismissible" style="display:none;" id="alert2">
					<h5><i class="icon fa fa-info"></i> Tanggal Kosong. Isi Tanggal Dengan Benar!</h5>
				</div>
				<div class="alert alert-danger alert-dismissible" style="display:none;" id="alert3">
					<h5><i class="icon fa fa-info"></i> Maaf, range tanggal maksimal 31 hari</h5>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->