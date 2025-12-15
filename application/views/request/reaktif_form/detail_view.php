<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo $this->session->flashdata('message'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reaktif Form</li>
    </ol>
    <br><a href="<?php echo site_url(); ?>request/reaktif_form" class="btn btn-info">
        << Kembali</a>
</section>



<!-- Main content -->
<section class="content">

    <div class="row">

        <div class="col-md-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Detail Data</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="data-request" class="table table-responsive table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th>Kode Sales</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Level</th>
                                <th>Catatan HRD</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
$no = 1;
foreach ($request_user->result() as $r) {
    ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $r->Sales_Code; ?></td>
                                <td><?php echo $r->Sales_Name; ?></td>
                                <td><?php echo $r->Position; ?></td>
                                <td><?php echo $r->Level; ?></td>
                                <td><?php echo $r->HRD_Note; ?></td>
                                <td>
                                    <?php
if ($category == 'returned') {
        ?>
                                    <a onclick="cancelFunc(<?=$r->Request_ID;?>, <?=$r->Request_User_ID;?>)"
                                        class="btn btn-warning btn-icon btn-circle btn-sm" title="Upload Bukti">
                                        <i class="fa fa-fw fa-upload"></i></a>
                                    <?php }?>
                                </td>
                            </tr>
                            <?php }?>
                            <!-- end foreach-->
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
function cancelFunc(request_id, request_user_id) {
    $("#modalUpload").modal('show');
    $('#request_id').val(request_id);
    $('#request_user_id').val(request_user_id);
    $("#area_msg").hide();
}
$(document).ready(function() {
    $('#formModal').submit(function(e) {
        e.preventDefault();
        let site_url = '<?=site_url()?>';
        var alert_cls;

        // let data_form = new FormData(this);
        var form_data = new FormData($(this)[0]);

        // console.log(form_data)
        $.ajax({
            url: site_url + 'request/reaktif_form/upload_doc',
            type: "POST",
            data: new FormData(this),
            data: form_data,
            dataType: "JSON",
            // data: form.serialize(),
            processData: false,
            contentType: false,
            success: function(data) {
                // alert(site_url)
                // console.log(data);

                if (data.status == true) {
                    // window.location.reload();
                    alert_cls = "alert-success";
                    $("#area_msg").addClass(alert_cls);
                    $("#info_msg").html("Upload is Success");
                    $("#area_msg").show();
                    setTimeout(function() {
                        window.location.href = site_url + `request/reaktif_form`;

                    }, 2000);
                } else {
                    // alert_cls = "alert-warning";
                    $("#area_msg").addClass(alert_cls);
                    $("#info_msg").html(data.info_msg);
                    $("#area_msg").show();
                }
            },
            error: function(e) {
                // console.log(e);
                alert_cls = "alert-warning";
                $("#area_msg").addClass(alert_cls);
                $("#info_msg").html("'Maaf, anda tidak diizinkan mengakses proses ini'");
                $("#area_msg").show();

            }
        })

    })
})
</script>

<!-- Begin Modal Cancel -->
<div id="modalUpload" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Silahkan Upload Bukti</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formModal" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="area_msg" class="alert alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-warning"></i> Informasi </h4>
                        <span id="info_msg">

                        </span>

                    </div>

                    <div class="box-body">
                        <div class="form-group" hidden>
                            <label for="request_user_id" class="form-label">Request ID <code>*</code></label>
                            <input type="text" name="request_id" id="request_id" class="form-control">
                            <input type="text" name="request_user_id" id="request_user_id" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">File input</label>
                            <input type="file" id="exampleInputFile" name="upl_file">

                            <p class="help-block" style="color:red">Format JPG, JPEG, PNG | max size 5 MB !</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Cancel -->