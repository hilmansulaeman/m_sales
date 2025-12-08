<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PEMOL — M-Sales</title>

  <link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/sidebar.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/components.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/cc-card.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/navbar.css'); ?>">

  <style>
    /* Minimal page styles for PEMOL table card (kept inline per request) */
    body { font-family: 'TikTok Sans', Inter, system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; background:#f4f6f8; margin:0; }
    /* keep page-content consistent with dashboard: margin-left only, page-main handles padding */
    .page-content { margin-left: 280px; padding-left: 32px; }
    .pemol-card { width:100%; max-width:1400px; background: #fff; border-radius:24px; outline:1px solid #D9D9D9; display:flex; flex-direction:column; overflow:hidden; }
    .pemol-card .card-top { display:flex; justify-content:space-between; align-items:center; padding:24px 32px; gap:16px; }
    .search-pill, .date-pill { display:flex; align-items:center; gap:12px; background:white; border-radius:100px; padding:14px 24px; outline:1px solid #D9D9D9; }
    .export-btn { background: #1E62B3; color:#fff; padding:14px 24px; border-radius:100px; font-weight:600; }
    .pemol-header { background:#1E62B3; color:#fff; display:flex; align-items:center; gap:0; }
    .pemol-header > div { padding:8px 12px; border-bottom:1px solid #D9D9D9; display:flex; align-items:center; justify-content:center; }
    .pemol-row { display:flex; align-items:center; gap:0; border-bottom:1px solid #D9D9D9; height:64px; }
    .pemol-row .cell { padding:8px 12px; display:flex; align-items:center; }
    .pemol-row .cell.flex { flex:1; }
    .pemol-row .cell.center { justify-content:center; }
    .pemol-row .cell.small { width:40px; text-align:center; }
    .pemol-row .text-muted { color:#29415C; }
    .table-actions { display:flex; align-items:center; justify-content:center; }
    .pagination { display:flex; align-items:center; gap:8px; padding:12px 32px; }
  </style>
</head>
<body>
  <?php // load sidebar partial view (application/views/sidebar.php) ?>
  <?php $this->load->view('sidebar'); ?>

  <div class="page-content">
    <header class="ms-navbar"><div class="ms-title">PEMOL</div></header>

    <main class="page-main">
      <div class="pemol-card">
        <div class="card-top">
          <div style="display:flex;align-items:center;gap:16px;">
            <div class="search-pill">
              <div style="width:24px;height:24px;background:#D9D9D9;border-radius:4px"></div>
              <div style="color:#A9A9A9;font-size:18px;">Search</div>
            </div>
            <div class="date-pill">
              <div style="width:24px;height:24px;background:#D9D9D9;border-radius:4px"></div>
              <div style="color:#A9A9A9;font-size:18px;width:236px;">Select date range</div>
            </div>
          </div>
          <div class="export-btn">Export data</div>
        </div>

        <!-- Header row -->
        <div class="pemol-header">
          <div style="width:40px;text-align:center;font-weight:600;font-size:20px;">No</div>
          <div style="flex:1;text-align:center;font-weight:600;font-size:20px;border-left:1px solid #D9D9D9;">RSM Name</div>
          <div style="width:124px;text-align:center;font-weight:600;font-size:20px;border-left:1px solid #D9D9D9;">NIK Sales</div>
          <div style="width:180px;text-align:center;font-weight:600;font-size:20px;border-left:1px solid #D9D9D9;">Branch</div>
          <div style="width:268px;text-align:center;border-left:1px solid #D9D9D9;">
            <div style="font-weight:600;font-size:20px;color:white;">Total DSR</div>
            <div style="display:flex;">
              <div style="width:134px;text-align:center;border-right:1px solid #D9D9D9;padding:8px;">Active</div>
              <div style="width:134px;text-align:center;padding:8px;">Input</div>
            </div>
          </div>
          <div style="width:402px;text-align:center;border-left:1px solid #D9D9D9;">
            <div style="font-weight:600;font-size:20px;color:white;">Input</div>
            <div style="display:flex;">
              <div style="padding:8px;border-right:1px solid #D9D9D9;">BCA Mobile</div>
              <div style="padding:8px;border-right:1px solid #D9D9D9;width:110px;text-align:center;">My BCA</div>
              <div style="padding:8px;border-right:1px solid #D9D9D9;width:110px;text-align:center;">Total</div>
            </div>
          </div>
          <div style="width:120px;text-align:center;font-weight:600;font-size:20px;border-left:1px solid #D9D9D9;">Action</div>
        </div>

        <!-- Sample rows (static for now) -->
        <div class="pemol-row">
          <div class="cell small"><div style="color:#29415C">1</div></div>
          <div class="cell" style="width:419px;"><div style="color:#29415C">Ramaldo Oktama</div></div>
          <div class="cell" style="width:124px;"><div style="color:#29415C">K1104148</div></div>
          <div class="cell" style="width:180px;"><div style="color:#29415C">Jakarta</div></div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">46</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">44</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">26</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">935</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;border-right:1px solid #D9D9D9;">961</div>
          <div class="cell flex table-actions"><div style="width:24px;height:24px;background:#1E62B3;border-radius:4px"></div></div>
        </div>

        <div class="pemol-row">
          <div class="cell small"><div style="color:#29415C">2</div></div>
          <div class="cell" style="width:419px;"><div style="color:#29415C">Asep Syaefudin Nugraha</div></div>
          <div class="cell" style="width:124px;"><div style="color:#29415C">K1200215</div></div>
          <div class="cell" style="width:180px;"><div style="color:#29415C">Bandung</div></div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">144</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">0</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">0</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">0</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;border-right:1px solid #D9D9D9;">0</div>
          <div class="cell flex table-actions"><div style="width:24px;height:24px;background:#1E62B3;border-radius:4px"></div></div>
        </div>

        <div class="pemol-row">
          <div class="cell small"><div style="color:#29415C">3</div></div>
          <div class="cell" style="width:419px;"><div style="color:#29415C">Kiwamudin</div></div>
          <div class="cell" style="width:124px;"><div style="color:#29415C">K1102242</div></div>
          <div class="cell" style="width:180px;"><div style="color:#29415C">Bandung</div></div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">185</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">0</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">0</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;">0</div>
          <div class="cell" style="width:134px;text-align:center;color:#29415C;border-right:1px solid #D9D9D9;">0</div>
          <div class="cell flex table-actions"><div style="width:24px;height:24px;background:#1E62B3;border-radius:4px"></div></div>
        </div>

        <div class="pagination">
          <div style="color:#55586B">Showing 1 to 3 of 3</div>
          <div style="flex:1;display:flex;justify-content:center;align-items:center;gap:8px;">
            <button aria-label="Prev">◀</button>
            <div style="width:36px;height:32px;background:#E9F2FF;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#1E62B3;font-weight:600;">1</div>
            <button aria-label="Next">▶</button>
          </div>
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="color:#55586B">Show</div>
            <div style="padding:4px 8px;border-radius:12px;border:1px solid #D9D9D9;">10 ▾</div>
          </div>
        </div>

      </div>
    </main>
  </div>

  <?php // No fetch() here — sidebar is loaded server-side above ?>

</body>
</html>
