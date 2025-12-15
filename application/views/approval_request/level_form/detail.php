<?php
$uri3 = $this->uri->segment(3);
$request_id = $this->uri->segment(4);

$q = $this->db->get_where('internal_sms.data_request_user', array('Request_ID' => $request_id))->row();
$username = $this->session->userdata('sl_code');
$checker = $q->Checker;
$approval = $q->Approval;
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        FORM PROMOSI
        <?php echo $this->session->flashdata('message'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Promosi Form</li>
    </ol>
</section>



<!-- Main content -->
<section class="content">

    <div class="row">

        <div class="col-md-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Permintaan Form Promosi</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data-request" class="table table-responsive table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="1%"><input type="checkbox" onclick="toggle(this);" /></th>
                                    <th>Kode Sales</th>
                                    <th>Nama Sales</th>
                                    <th>Posisi</th>
                                    <th>Level</th>
                                    <th>Keterangan</th>
                                    <th>Dibuat Oleh</th>
                                    <th width="5%">Persetujuan RSM</th>
                                    <th width="5%">Persetujuan BSH</th>
                                    <th width="5%">Persetujuan GM</th>
                                    <th width="10%">Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <form action="<?= site_url(); ?>approval_request/level_form/approve/<?= $request_id ?>" method="POST" onsubmit="return validate(this);">
                                <tbody>
                                    <?php

                                    $no = 1;
                                    foreach ($request_user->result() as $r) {
                                        // var_dump('test');
                                        // die;
                                        global $statusRSM,
                                            $statusBSH,
                                            $statusGM;

                                        $request_id      = $r->Request_ID;
                                        $request_user_id = $r->Request_User_ID;

                                    ?>
                                        <tr>
                                            <?php
                                            $q = $this->db->get_where('internal_sms.data_request_approval', array('Sales_Code' => $username, 'Request_User_ID' => $request_user_id))->row();
                                            if ($q->Status == '0') {
                                            ?>
                                                <td><input type="checkbox" name="request_user_id[]" value="<?php echo $r->Request_User_ID; ?>"></td>
                                            <?php } else { ?>
                                                <td>&nbsp;</td>
                                            <?php } ?>

                                            <td><?php echo $r->Sales_Code; ?></td>
                                            <td><?php echo $r->Sales_Name; ?></td>
                                            <td><?php echo $r->Position; ?></td>
                                            <td><?php echo $r->Level; ?></td>
                                            <td><?php echo $r->Note; ?></td>
                                            <td>
                                                <?php echo $r->Created_Name; ?>

                                                <input type="hidden" name="regnoID[]" value="<?php echo $r->Regno_ID; ?>">
                                                <!-- <input type="hidden" name="employee_id[]" value="<?php echo $r->Employee_ID; ?>"> -->
                                                <input type="hidden" name="sales_code[]" value="<?php echo $r->Sales_Code; ?>">
                                                <input type="hidden" name="name[]" value="<?php echo $r->Sales_Name; ?>">
                                                <input type="hidden" name="effective_date[]" value="<?php echo $r->Efective_Date; ?>">
                                                <input type="hidden" name="reason[]" value="<?php echo $r->Reason; ?>">
                                                <input type="hidden" name="note[]" value="<?php echo $r->Note; ?>">
                                            </td>
                                            <td>
                                                <?php

                                                $queryStatus = $this->db->get_where('data_request_approval', array('Request_User_ID' => $request_user_id))->result();

                                                foreach ($queryStatus as $key => $value) {
                                                    $outStr = "";

                                                    if ($value->Status == 1) {
                                                        $outStr = "Disetujui";
                                                    } elseif ($value->Status == 0) {
                                                        $outStr = "Belum Disetujui";
                                                    }
                                                    if ($value->Position == 'RSM') {
                                                        $statusRSM = $outStr;
                                                    } elseif ($value->Position == 'BSH') {
                                                        $statusBSH = $outStr;
                                                    } elseif ($value->Position == 'GM') {
                                                        $statusGM = $outStr;
                                                    } else {
                                                        $statusGM = "Belum Disetujui";
                                                    }
                                                }
                                                ?>
                                                <?= $statusRSM; ?>
                                            </td>
                                            <td>
                                                <?= $statusBSH; ?>
                                            </td>
                                            <td>
                                                <?= $statusGM; ?>
                                            </td>
                                            <td>
                                                <?php
                                                // $cek = $this->db->query("SELECT * FROM data_request_approval WHERE Request_User_ID = '$request_user_id' ORDER BY Request_Approval_ID ASC LIMIT 1 ")->row();

                                                $this->db->select('*');
                                                $this->db->from('data_request_approval');
                                                $this->db->where('Request_User_ID', $request_user_id);
                                                $this->db->order_by('Request_Approval_ID', 'DESC');
                                                $this->db->limit(1);
                                                $cek = $this->db->get()->row();

                                                $approval = $cek->Sales_Name;
                                                $status = $cek->Status;
                                                if ($status == '0') {
                                                    $label = 'Data Baru ';
                                                    $approval_name = '';
                                                } else {
                                                    $label = 'Disetujui Oleh <br>';
                                                    $approval_name = $r->Approval_Name;
                                                }
                                                ?>
                                                <label class="label label-xs label-info"><?= $label ?><?= $approval_name ?></label>
                                            </td>
                                            <?php
                                                $productArr = array('PEMOL', 'CC', 'SC', 'PL', 'Sales Merchant');
                                                if (in_array($r->Product, $productArr)) {
                                            ?>
                                                <td>
                                                    <a href="javascript:void(0);" onclick="view_performance('<?= $r->Sales_Code ?>','<?= $r->Sales_Name ?>', '<?= $r->Product ?>', '<?= $r->Position ?>')"><span class="btn btn-xs btn-warning"><i class="fa fa-clipboard" title="Performance"></i></span></a>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <!-- end foreach-->
                                    <?php }
                                    ?>

                                </tbody>
                        </table>
                    </div>
                    <br>
                    <?php 
                        $q_cek = $this->db->get_where('internal_sms.data_request_approval', array('Sales_Code' => $username, 'Status' => 0))->num_rows();
                        if ($q_cek > 0) {
                    ?>
                        <input type="submit" class="btn btn-success" name="approve" value="Setujui" onclick="return confirm('Yakin setujui data ini?')">
                        <input type="submit" class="btn btn-danger" formaction="<?php echo site_url(); ?>approval_request/level_form/reject/<?= $request_id ?>" name="reject" value="Tolak" onclick="return confirm('Yakin tolak data ini?')">
                    <?php } else { ?>
                        <a href="<?= site_url() ?>approval_request/level_form" class="btn btn-primary">Back</a>
                    <?php } ?>

                    <?php echo form_close(); ?>

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
    function validate() {
        var checked = false;
        var elements = document.getElementsByName("request_user_id[]");
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].checked) {
                checked = true;
            }
        }
        if (!checked) {
            alert('Silahkan ceklis data !');
        }
        return checked;
    }

    function toggle(source) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }

    function view_performance(sales_code, sales_name, product, position)
    {
        $("#pop").html('');
        $("#header-name").html('');
        $("#product-title").html('');
        $('#modalPerformance').modal('show');
        $.ajax({
        url:"<?php echo site_url('approval_request/level_form/performance_popup'); ?>/" + sales_code + "/" + product + "/" + position,
        type:"POST",
        data:$("#frmPerformance").serialize(),
        success:function(data){ 
            $("#pop").html('');  
            $("#pop").append(data);  
            $("#header-name").html(sales_name);
            $("#product-title").html(product);
        } 
        });
    }
</script>

<!-- Modal Performance -->
<div class="modal modal-message fade" id="modalPerformance">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="header-name" style="display:inline-block"></h4> - <p id="product-title" style="display:inline-block"></p>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form method="post" id="frmPerformance" class="form-horizontal form-bordered">					 				 								
					<div id="pop"></div>
				</form>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>					
			</div>
		</div>
	</div>
</div>