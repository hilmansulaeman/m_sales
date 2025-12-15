<div id="ribbon">
    <ol class="breadcrumb">
        <i class="fa fa-home"></i> &nbsp;
        <li><a href="<?php echo site_url(); ?>">Home</a></li>
        <li><i class="fa fa-cloud-upload "></i> &nbsp; Incoming</li>
        <li>Pemol</li>
    </ol>    
</div>

<div class="box box-primary">
    <?php if ($this->session->flashdata('message')) { ?>
        <div class="alert alert-warning fade in">
        <button class="close" data-dismiss="alert" id="notif">
            ×
        </button>
        <i class="fa-fw fa fa-check"></i>
        <?php echo $this->session->flashdata('message'); ?>
        </div>
    <?php }?>
    <div class="box-header with-border">
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">&nbsp;</i>
            </button>
        </div>      
        <h3 class="box-title">Summary Pemol <?php echo $position; ?></h3>             
    </div>
    <div class="panel-body">
        <div class="row">
            <span class="pull-right">
                <h6 class="txt-color-blueDark"><i class="fa fa-bank fa-fw "></i> Filter</h6>
                <form id="form_filter" method="post" class="smart-form" novalidate="novalidate">        
                    <table>
                        <tr>                                                                                                                            
                            <td><h5 class="txt-color-blueDark">Periode &nbsp; </h5></td>    
                            <td>
                                <label class="input">   
                                        <input type="date" name="date_from" value="<?php echo date('Y-m-01');?>" data-dateformat='yy-mm-dd'  class="form-control datepicker" required/>
                                <?php echo form_error('date_from'); ?>
                                </label>    
                            </td>                                           
                            <td><h5 class="txt-color-blueDark">&nbsp; S/D &nbsp; </h5></td>                                         
                            <td>
                                <label class="input">                           
                                        <input type="date" name="date_to" value="<?php echo date('Y-m-d');?>" data-dateformat='yy-mm-dd'  class="form-control datepicker" required/>
                                <?php echo form_error('date_to'); ?>
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
        </div>
        <br>

        <?php
            // Variabel-variabel ini digunakan untuk rendering HTML header
            $position_ = $this->session->userdata('position');
            $disallow_position = array('DSR','SPV');
            // if(in_array($position_,$disallow_position)){
            //     $total_dsr_html = ""; // Mengubah nama variabel untuk menghindari kebingungan
            //     $control_html   = ""; // Mengubah nama variabel
            //     $column_count_html = "6"; // Ini adalah jumlah kolom HTML yang terlihat
            // }
            // else{
            //     $total_dsr_html = "<th rowspan='2'>Total DSR <br> <small>(Active)</small></th>
            //                                 <th rowspan='2'>Total DSR <br> <small>(Input)</small></th>";
            //     $control_html   = "<th rowspan='2'>Action</th>";
            //     $column_count_html = "9"; // Ini adalah jumlah kolom HTML yang terlihat
            // }
            if(in_array($position_,$disallow_position)){
                $total_dsr_html = "<th rowspan='2' style='display:none;'></th><th rowspan='2' style='display:none;'></th>";
                $control_html   = "<th rowspan='2' style='display:none;'></th>";
                $column_count_html = "9";
            } else {
                $total_dsr_html = "<th rowspan='2'>Total DSR <br> <small>(Active)</small></th>
                                <th rowspan='2'>Total DSR <br> <small>(Input)</small></th>";
                $control_html   = "<th rowspan='2'>Action</th>";
                $column_count_html = "9";
            }
        ?>
        <?php 
            $disallow_pos = array('DSR','SPB','SPG');

            if (!in_array($position_, $disallow_position)) {
        ?>
            <a href="pemol/export"><button type="button" id="btn-export" class="btn btn-primary" style="padding:5px;"><i class="fa fa-file-excel-o"></i> Export Data</button></a>
        <?php } ?>
        <br><br>
        <div class="table-responsive">
            <table id="data-table-customer" class="table table-hover" width="100%">
                <thead>                                         
                    <tr>                                                                            
                        <th rowspan="2">No</th>
                        <th rowspan="2">Nama Sales</th>
                        <th rowspan="2">Branch</th>
                        <?php echo $total_dsr_html; ?>
                        <th colspan="3" style="text-align:center;">Input</th>
                        <?php echo $control_html; ?>
                    </tr>
                    <tr>
                        <th>BCA Mobile</th>
                        <th>My BCA</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="<?php echo $column_count_html; ?>">Loading data from server</td>
                    </tr>
                </tbody>
            </table>                    
        </div>
    </div>
</div>

<div class="modal modal-message fade" id="modalSPV">
    <div class="modal-dialog" style="width:90%">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" name="names1" id="names1" value="">
                <input type="hidden" name="pos1" id="pos1" value="">
                <input type="hidden" name="sales1" id="sales1" value="">
                <input type="hidden" name="names2" id="names2" value="">
                <input type="hidden" name="names3" id="names3" value="">
                <span id="header-all"></span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form method="post" id="frmspv" class="form-horizontal form-bordered">                                                                  
                    <div id="pop"></div>
                </form> 
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>                   
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var table;

// Ambil posisi pengguna dari session PHP ke JavaScript
var userPosition = "<?php echo $this->session->userdata('position'); ?>";

$(document).ready(function() {
    // Inisialisasi DataTables untuk tabel utama: data-table-customer
    table = $("#data-table-customer").DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "<?php echo site_url('incoming/pemol/get_data') ?>", // URL ini memanggil data untuk tabel utama
            type: 'POST',
        },
        // *** PENTING: Definisikan SEMUA 9 kolom yang dikirim oleh fungsi get_data() PHP ***
        "columns": [
            { "data": null, "orderable": false, "searchable": false }, // Kolom 0: No. (DataTables akan menangani penomoran)
            { "data": "1" }, // Kolom 1: Nama Sales
            { "data": "2" }, // Kolom 2: Branch
            { "data": "3" }, // Kolom 3: DSR Active
            { "data": "4" }, // Kolom 4: DSR Input
            { "data": "5" }, // Kolom 5: Mobile BCA
            { "data": "6" }, // Kolom 6: My BCA
            { "data": "7" }, // Kolom 7: Total
            { "data": "8", "orderable": false, "searchable": false }  // Kolom 8: Action
        ],
        "columnDefs": [
            {
                "targets": [0], // Target kolom "No" untuk menampilkan nomor baris
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }
            // Sembunyikan kolom secara kondisional berdasarkan posisi pengguna (jika DSR/SPV)
            // Pastikan target ini sesuai dengan indeks output PHP (0-indexed)
            <?php if (in_array($this->session->userdata('position'), array('DSR', 'SPV'))) { ?>
                , { "targets": [3, 4], "visible": false } // Sembunyikan DSR Active (indeks 3) dan DSR Input (indeks 4)
                , { "targets": [8], "visible": false }   // Sembunyikan kolom Action (indeks 8) untuk DSR/SPV
            <?php } ?>
        ],
        initComplete: function() {
            var input = $('#data-table-customer_filter input').unbind(),
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
            $('#data-table-customer_filter').append(searchButton);
        }
    });

    // Kode untuk data-table-spv (di dalam modal) akan diinisialisasi ketika modal dibuka
    // dan dihancurkan ketika modal ditutup, untuk mencegah kesalahan re-initialization.
});

// Fungsi view_spv dan fungsi terkait lainnya
function view_spv(sales, pos, name)
{
    $('#modalSPV').modal('show');
    var names1 = $('#names1').val();
    var names2 = $('#names2').val();
    var names3 = $('#names3').val();

    if (names1 == "") {
        $('#names1').val(name);
        $('#pos1').val(pos);
        $('#sales1').val(sales);
        names1 = $('#names1').val();
        $('#header-all').html("<b>"+names1+"</b>");
    }else if(names2 == "" ) {
        $('#names2').val(name);
        names2 = $('#names2').val();
        names1 = $('#names1').val();
        pos1 = $('#pos1').val();
        sales1 = $('#sales1').val();

        $('#header-all').html("<b><a href='javascript:void(0);' onclick='view_spv_click(\""+sales1+"\",\""+pos1+"\",\""+names1+"\")'>"+names1+"</a></b> -> " + names2);
    }else{
        $('#names3').val(name);
        names3 = $('#names3').val();
        names1 = $('#names1').val();
        names2 = $('#names2').val();

        pos1 = $('#pos1').val();
        sales1 = $('#sales1').val();

        $('#header-all').html("<b><a href='javascript:void(0);' onclick='view_spv_click(\""+sales1+"\",\""+pos1+"\",\""+names1+"\")'>"+names1+"</a></b> -> " + names2 + " -> " + names3);
    }

    $.ajax({
        url:"<?php echo site_url('incoming/pemol/detail'); ?>/" + sales +"/"+ pos,
        type:"POST",
        data:$("#frmspv").serialize(),
        success:function(data){ 
            $("#pop").html('');  
            $("#pop").append(data);  
            // Ketika tabel detail dimuat, inisialisasi ulang DataTables-nya
            // Ini adalah tabel di dalam modal Anda: data-table-spv
            // Periksa apakah tabel sudah diinisialisasi sebelum mencoba menghancurkannya
            if ($.fn.DataTable.isDataTable('#data-table-spv')) {
                $('#data-table-spv').DataTable().destroy();
            }
            $("#data-table-spv").DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                responsive:true,
                ajax: {
                    url: "<?php echo site_url('incoming/pemol/get_data_spv') ?>", // URL ini memanggil data untuk tabel detail SPV
                    type:'POST',
                    data: { // Kirim parameter sales dan pos ke fungsi get_data_spv
                        sales: sales,
                        pos: pos,
                        date_from: $('input[name="date_from"]').val(), // Kirim rentang tanggal saat ini
                        date_to: $('input[name="date_to"]').val()     // Kirim rentang tanggal saat ini
                    }
                },
                columns: [ // Definisikan kolom untuk data-table-spv, yang juga 9 kolom.
                    { "data": null, "orderable": false, "searchable": false, "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } }, // Kolom 0: No
                    { "data": "1" }, // Kolom 1: Sales Name
                    { "data": "2" }, // Kolom 2: Branch
                    { "data": "3" }, // Kolom 3: Total DSR (Active)
                    { "data": "4" }, // Kolom 4: Total DSR (Input)
                    { "data": "5" }, // Kolom 5: Mobile BCA
                    { "data": "6" }, // Kolom 6: My BCA
                    { "data": "7" }, // Kolom 7: Total
                    { "data": "8", "orderable": false, "searchable": false }  // Kolom 8: Action
                ],
                // Tidak perlu columnDefs di sini jika semua kolom ini selalu terlihat di modal
                initComplete: function() {
                    var input = $('#data-table-spv_filter input').unbind(),
                        self = this.api(),
                        searchButton = $('<span id="btnSearchSpv" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></span>')
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
        }  
    });
}

$('#modalSPV').on('hidden.bs.modal', function () {
    // Kosongkan field
    $('#names1').val(''); 
    $('#names2').val('');
    $('#names3').val('');
    $('#pos1').val(''); 
    $('#sales1').val(''); 

    // Hancurkan instance DataTables di modal ketika disembunyikan
    // Ini penting untuk mencegah kesalahan re-initialization jika modal dibuka lagi
    if ($.fn.DataTable.isDataTable('#data-table-spv')) {
        $('#data-table-spv').DataTable().destroy();
    }
});

function view_spv_click(sales, pos, names)
{
    // console.log(sales);
    // console.log(pos);
    $('#modalSPV').modal('show');
    $('#header-all').html("<b>"+names+"</b>");
    $('#names2').val("");
    $('#names3').val("");

    // Hancurkan instance DataTables sebelumnya sebelum memuat data baru
    if ($.fn.DataTable.isDataTable('#data-table-spv')) {
        $('#data-table-spv').DataTable().destroy();
    }

    $.ajax({
        url:"<?php echo site_url('incoming/pemol/detail'); ?>/" + sales + "/" + pos,
        type:"POST",
        data:$("#frmspv").serialize(),
        success:function(data){ 
            $("#pop").html('');  
            $("#pop").append(data);
            // Inisialisasi ulang DataTables untuk data-table-spv di dalam modal
            $("#data-table-spv").DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                responsive:true,
                ajax: {
                    url: "<?php echo site_url('incoming/pemol/get_data_spv') ?>",
                    type:'POST',
                    data: {
                        sales: sales,
                        pos: pos,
                        date_from: $('input[name="date_from"]').val(),
                        date_to: $('input[name="date_to"]').val()
                    }
                },
                columns: [
                    { "data": null, "orderable": false, "searchable": false, "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                    { "data": "1" },
                    { "data": "2" },
                    { "data": "3" },
                    { "data": "4" },
                    { "data": "5" },
                    { "data": "6" },
                    { "data": "7" },
                    { "data": "8", "orderable": false, "searchable": false }
                ],
                initComplete: function() {
                    var input = $('#data-table-spv_filter input').unbind(),
                        self = this.api(),
                        searchButton = $('<span id="btnSearchSpv" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-search"></i></span>')
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
        }  
    });
}
</script>