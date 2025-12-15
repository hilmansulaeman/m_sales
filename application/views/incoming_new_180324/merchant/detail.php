<!-- Basic Styles -->
<!-- <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/font-awesome.min.css"> -->

<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
<!-- <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/smartadmin-production-plugins2.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/smartadmin-production.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/smartadmin-skins.min.css"> -->

<!-- SmartAdmin RTL Support -->
<!-- <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/smartadmin-rtl.min.css"> -->

<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
<!-- <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/demo.min.css"> -->
<input type="hidden" name="section" id="section" value="<?= $section ?>">
<p style="color:green;">
	<b>Periode : <?php echo date('d/m/Y',strtotime($this->session->userdata('date_from'))); ?> s/d <?php echo date('d/m/Y',strtotime($this->session->userdata('date_to'))); ?></b>
</p>
<div class="table-responsive">
  <table id="data-table-spv" class="table table-hover" width="100%">
	<thead>
		<?php if ($section == 'input') { ?>
			<tr>				 											 				
				<th rowspan="2">No</th>
				<th rowspan="2">Nama Sales</th>
				<th rowspan="2">Product</th>
				<th rowspan="2">Total</th>
				<th colspan="2">EDC</th>
				<th colspan="2">QRIS</th>
				<th colspan="2">EDC+QRIS</th>
			</tr>
			<tr>
				<th>Input</th>
				<th>Belum Setor</th>
				<th>Input</th>
				<th>Belum Setor</th>
				<th>Input</th>
				<th>Belum Setor</th>
			</tr>
		<?php }else{ ?>
			<tr>				 											 				
				<th rowspan="2">No</th>
				<th rowspan="2">Nama Sales</th>
				<th rowspan="2">Product</th>
				<th colspan="4">Total</th>
				<th colspan="4">EDC</th>
				<th colspan="4">QRIS</th>
				<th colspan="4">EDC+QRIS</th>
			</tr>
			<tr>
				<th>Received</th>
				<th>Inprocess</th>
				<th>RTS</th>
				<th>Send</th>
				<th>Received</th>
				<th>Inprocess</th>
				<th>RTS</th>
				<th>Send</th>
				<th>Received</th>
				<th>Inprocess</th>
				<th>RTS</th>
				<th>Send</th>
				<th>Received</th>
				<th>Inprocess</th>
				<th>RTS</th>
				<th>Send</th>
			</tr>
		<?php } ?>
	</thead>
	<tbody>
		<tr>
			<td colspan="7">Loading data from server</td>
		</tr>
	</tbody>
</table>					
</div>

<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
<!-- <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/smartadmin-production-plugins2.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/smartadmin-production.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/smartadmin-skins.min.css"> -->
<!-- SmartAdmin RTL Support -->
<!-- <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/smartadmin-rtl.min.css"> -->

<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
<!-- <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>public/style/css/demo.min.css"> -->

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
<!-- <script src="<?php echo base_url(); ?>public/style/js/jquery-3.2.1.min.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/fancybox/dist/jquery.fancybox.min.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/greybox/AJS.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/greybox/AJS_fx.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/greybox/gb_scripts.js"></script> -->


<!-- <script data-pace-options='{ "restartOnRequestAfter": true }' src="<?php echo base_url(); ?>public/style/js/plugin/pace/pace.min.js"></script> -->
<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->



<!--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>-->
<!-- <script src="<?php echo base_url(); ?>public/style/js/libs/211jquery.min.js"></script>
<script src="<?php echo base_url(); ?>public/style/js/libs/1103jquery-ui.min.js"></script>
<script>
    if (!window.jQuery) {
        document.write('<script src="<?php echo base_url(); ?>public/style/js/libs/jquery-2.1.1.min.js"><\/script>');
    }
</script>
<script>
    if (!window.jQuery.ui) {
        document.write('<script src="<?php echo base_url(); ?>public/style/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
    }
</script> -->

<!-- IMPORTANT: APP CONFIG -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/app.config.js"></script> -->

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
<!-- <script src="<?php echo base_url(); ?>public/style/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>  -->

<!-- BOOTSTRAP JS -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/bootstrap/bootstrap.min.js"></script> -->

<!-- CUSTOM NOTIFICATION -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/notification/SmartNotification.min.js"></script> -->

<!-- JARVIS WIDGETS -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/smartwidgets/jarvis.widget.min.js"></script> -->

<!-- EASY PIE CHARTS -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script> -->

<!-- SPARKLINES -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/plugin/sparkline/jquery.sparkline.min.js"></script> -->

<!-- JQUERY VALIDATE -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/plugin/jquery-validate/jquery.validate.min.js"></script> -->

<!-- JQUERY MASKED INPUT -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/plugin/masked-input/jquery.maskedinput.min.js"></script> -->

<!-- JQUERY SELECT2 INPUT -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/plugin/select2/select2.min.js"></script> -->

<!-- JQUERY UI + Bootstrap Slider -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script> -->

<!-- browser msie issue fix -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/plugin/msie-fix/jquery.mb.browser.min.js"></script> -->

<!-- FastClick: For mobile devices -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/plugin/fastclick/fastclick.min.js"></script> -->
<!-- Demo purpose only 
<script src="<?php echo base_url(); ?>public/style/js/demo.min.js"></script>-->

<!-- MAIN APP JS FILE -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/app.min.js"></script> -->

<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
<!-- Voice command : plugin -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/speech/voicecommand.min.js"></script> -->

<!-- SmartChat UI : plugin -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/smart-chat-ui/smart.chat.ui.min.js"></script>
<script src="<?php echo base_url(); ?>public/style/js/smart-chat-ui/smart.chat.manager.min.js"></script> -->

<!-- PAGE RELATED PLUGIN(S) -->
<!-- <script src="<?php echo base_url(); ?>public/style/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>public/style/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo base_url(); ?>public/style/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo base_url(); ?>public/style/js/plugin/datatables/dataTables.bootstrap2.min.js"></script>
<script src="<?php echo base_url(); ?>public/style/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>public/style/js/plugin/bootstrapvalidator/bootstrapValidator.min.js"></script> -->
<script type="text/javascript">
var table;
var section = document.getElementById('section').value;
$(document).ready(function() {
    table = $("#data-table-spv").DataTable({
		ordering: false,
		//searching:false,
		processing: true,
		serverSide: true,
		responsive:true,
		ajax: {
		  url: "<?php echo site_url('incoming/merchant/get_data_spv') ?>/" + section,
		  type:'POST',
		  /*data: function ( data ) {
                data.created_date = $('#created_date').val();
            }*/
		},
		initComplete : function() {
			var input = $('#data-table-spv_filter input').unbind(),
				self = this.api(),
				searchButton = $('<span id="btnSearch" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></span>')
						   .click(function() {
							  self.search(input.val()).draw();
						   });
			    $(document).keypress(function (event) {
					if (event.which == 13) {
						searchButton.click();
					}
				});
			$('#data-table-spv_filter').append(searchButton);
		}
	});
});
</script>