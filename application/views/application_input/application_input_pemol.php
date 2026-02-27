<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Application Input Pemol - M-Sales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>window.SITE_URL = "<?= site_url(); ?>/";
    </script>
    <script src="<?= base_url('assets/js/layout.js') ?>"></script>
    <!-- jQuery (dibutuhkan DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      body { font-family: "Inter", sans-serif; }

      /* Override DataTables default style agar sesuai dengan Tailwind */
      .dataTables_wrapper .dataTables_length,
      .dataTables_wrapper .dataTables_filter,
      .dataTables_wrapper .dataTables_info,
      .dataTables_wrapper .dataTables_paginate { display: none !important; }

      #mainTable thead tr th {
        background-color: #1E5BA8;
        color: white;
        font-size: 0.8125rem;
        font-weight: 500;
        padding: 10px 14px;
        text-align: center;
        border-right: 1px solid rgba(255,255,255,0.2);
        white-space: nowrap;
      }
      #mainTable thead tr th:last-child { border-right: none; }
      #mainTable tbody tr td {
        font-size: 0.8125rem;
        color: #374151;
        padding: 10px 14px;
        border-bottom: 1px solid #F3F4F6;
        text-align: center;
        white-space: nowrap;
      }
      #mainTable tbody tr td:nth-child(2) { text-align: left; font-weight: 500; }
      #mainTable tbody tr td:nth-child(3) { text-align: left; }
      #mainTable tbody tr:hover td { background-color: #F9FAFB; }

      /* Flatpickr date range */
      .flatpickr-calendar { font-family: "Inter", sans-serif; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); border: 1px solid #E5E7EB; }
      .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background: #1E5BA8 !important; border-color: #1E5BA8 !important; }
      .flatpickr-day.inRange { background: #DBEAFE !important; border-color: #DBEAFE !important; box-shadow: -5px 0 0 #DBEAFE, 5px 0 0 #DBEAFE !important; color: #1E40AF; }

      /* Loading spinner */
      .table-loading { position: relative; min-height: 200px; }
      .spinner-overlay {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex; align-items: center; justify-content: center;
        z-index: 10; border-radius: 8px;
      }
      .spinner {
        width: 36px; height: 36px;
        border: 3px solid #E5E7EB;
        border-top-color: #1E5BA8;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
      }
      @keyframes spin { to { transform: rotate(360deg); } }

      /* Modal */
      .modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.5);
        z-index: 1000; display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.2s;
      }
      .modal-overlay.active { opacity: 1; }
      .modal-box {
        background: white; border-radius: 16px; width: 95%; max-width: 900px;
        max-height: 90vh; overflow-y: auto;
        transform: scale(0.95); transition: transform 0.2s;
      }
      .modal-overlay.active .modal-box { transform: scale(1); }
    </style>
  </head>
  <body>
    <div id="app"></div>

    <!-- Downline / Detail Modal -->
    <div id="detailModal" class="modal-overlay">
      <div class="modal-box">
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
          <div>
            <h3 class="text-base font-semibold text-gray-800" id="modalTitle">Detail</h3>
            <p class="text-xs text-gray-500 mt-0.5" id="modalSubtitle"></p>
          </div>
          <button onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors text-gray-500">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>
        <!-- Modal Table -->
        <div class="p-5">
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm table-loading" id="modalTableWrapper">
            <div class="spinner-overlay hidden" id="modalSpinner"><div class="spinner"></div></div>
            <div class="overflow-x-auto">
              <table id="detailTable" class="w-full">
                <thead id="detailTableHead"></thead>
                <tbody id="detailTableBody" class="divide-y divide-gray-200"></tbody>
              </table>
            </div>
            <div class="px-5 py-3 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-500">
              <div id="modalShowingInfo">Showing 0 to 0 of 0</div>
              <div class="flex items-center gap-2" id="modalPaginationControls"></div>
              <div class="flex items-center gap-2">
                <span>Show</span>
                <div class="relative">
                  <select id="modalRowsPerPage" onchange="handleModalRowsChange(this.value)" class="appearance-none border border-gray-200 rounded-lg pl-3 pr-7 py-1.5 bg-white text-gray-700 focus:outline-none text-sm">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                  </select>
                  <i data-lucide="chevron-down" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 w-3 h-3 pointer-events-none"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      // ======================== INIT LAYOUT ========================
      initLayout("Application Input", "Pemol", "Application Input - Pemol");
      const appContainer = document.querySelector("#app > div > div");
      const main = document.createElement("div");
      main.className = "flex-1 bg-gray-50 flex flex-col";

      // ======================== PHP DATA PASSED TO JS ========================
      const IS_LEADER   = <?= json_encode($is_leader) ?>;
      const USER_POS    = "<?= $user_position ?>";
      const DATE_FROM   = "<?= $date_from ?>";
      const DATE_TO     = "<?= $date_to ?>";
      const SITE_URL    = window.SITE_URL;

      // Format date display (YYYY-MM-DD -> DD/MM/YYYY)
      function fmtDate(d) {
        const p = d.split('-');
        return p[2]+'/'+p[1]+'/'+p[0];
      }
      const dateDisplayInit = fmtDate(DATE_FROM) + ' - ' + fmtDate(DATE_TO);

      // ======================== BUILD MAIN UI ========================
      main.innerHTML = `
        <!-- Header Bar -->
        <div class="p-6 pb-0">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <p class="text-sm text-gray-500 mt-0.5">Data periode: <span id="activePeriod" class="font-medium text-blue-600">${dateDisplayInit}</span></p>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="p-6">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <!-- Search & Filter Bar -->
            <div class="mb-5">
              <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-3 flex-1 w-full lg:w-auto items-center">
                  <!-- Search -->
                  <div class="relative w-full sm:w-64">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama / kode sales..."
                      class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-full text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"
                      oninput="handleSearch(this.value)" />
                  </div>
                  <!-- Date Range Picker (Flatpickr) -->
                  <div class="relative w-full sm:w-72">
                    <i data-lucide="calendar" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 z-10 pointer-events-none"></i>
                    <input type="text" id="dateRangeInput" placeholder="Pilih rentang tanggal"
                      value="${dateDisplayInit}" readonly
                      class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-full text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer placeholder-gray-400" />
                  </div>
                  <!-- Apply Button -->
                  <button id="applyFilterBtn" onclick="applyFilter()"
                    class="w-full sm:w-auto bg-[#1E5BA8] text-white px-5 py-2.5 rounded-full text-sm font-medium hover:bg-[#15468a] transition-colors flex items-center gap-2 shadow-sm">
                    <i data-lucide="search" class="w-4 h-4"></i> Terapkan
                  </button>
                </div>
                <!-- Export -->
                <a href="${SITE_URL}application_input/pemol_export" target="_blank"
                  class="w-full sm:w-auto bg-emerald-600 text-white px-5 py-2.5 rounded-full text-sm font-medium hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2 shadow-sm">
                  <i data-lucide="download" class="w-4 h-4"></i> Export Excel
                </a>
              </div>
              <!-- Validation message -->
              <div id="filterError" class="hidden mt-2 text-sm text-red-600 flex items-center gap-1.5">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <span id="filterErrorMsg"></span>
              </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
              <div class="overflow-x-auto">
                <div class="table-loading" id="mainTableWrapper">
                  <div class="spinner-overlay" id="mainSpinner"><div class="spinner"></div></div>
                  <table id="mainTable" class="w-full" style="width:100%">
                    <thead id="mainTableHead"></thead>
                    <tbody id="mainTableBody"></tbody>
                  </table>
                </div>
              </div>
              <!-- Pagination -->
              <div class="px-5 py-3 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white text-sm text-gray-500 w-full">
                <div id="showingInfo">Showing 0 to 0 of 0</div>
                <div class="flex items-center gap-2" id="paginationControls"></div>
                <div class="flex items-center gap-2">
                  <span>Show</span>
                  <div class="relative">
                    <select id="rowsPerPageSelect" onchange="handleRowsPerPageChange(this.value)"
                      class="appearance-none border border-gray-200 rounded-lg pl-3 pr-7 py-1.5 bg-white text-gray-700 focus:outline-none text-sm">
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 w-3 h-3 pointer-events-none"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
      appContainer.appendChild(main);
      lucide.createIcons();

      // ======================== STATE ========================
      let allData        = [];
      let filteredData   = [];
      let currentPage    = 1;
      let rowsPerPage    = 10;
      let searchQuery    = '';
      let selectedFrom   = DATE_FROM;
      let selectedTo     = DATE_TO;

      // ======================== HEADER BUILD ========================
      function buildMainHeader() {
        const head = document.getElementById('mainTableHead');
        if (IS_LEADER) {
          head.innerHTML = `
            <tr>
              <th>No</th>
              <th style="text-align:left">Nama Sales</th>
              <th style="text-align:left">Branch</th>
              <th>DSR Active</th>
              <th>DSR Input</th>
              <th>Mobile BCA</th>
              <th>myBCA</th>
              <th>Total Input</th>
              <th style="border-right:none">Aksi</th>
            </tr>`;
        } else {
          head.innerHTML = `
            <tr>
              <th>No</th>
              <th style="text-align:left">Nama Sales</th>
              <th style="text-align:left">Branch</th>
              <th>Mobile BCA</th>
              <th>myBCA</th>
              <th style="border-right:none">Total Input</th>
            </tr>`;
        }
      }
      buildMainHeader();

      // ======================== LOAD DATA (AJAX) ========================
      function loadData() {
        const spinner = document.getElementById('mainSpinner');
        spinner.classList.remove('hidden');

        // Kita gunakan endpoint filter_data untuk set session dulu
        $.ajax({
          url: SITE_URL + 'application_input/pemol_filter',
          type: 'POST',
          data: { date_from: selectedFrom, date_to: selectedTo },
          dataType: 'json',
          success: function(resp) {
            if (resp.status === false) {
              const errEl = document.getElementById('filterError');
              const errMsg = document.getElementById('filterErrorMsg');
              errEl.classList.remove('hidden');
              errMsg.textContent = resp.error_string[0];
              spinner.classList.add('hidden');
              lucide.createIcons();
              return;
            }
            // Setelah session di-set, ambil data via get_data
            fetchTableData(spinner);
          },
          error: function() {
            spinner.classList.add('hidden');
            fetchTableData(spinner);
          }
        });
      }

      function fetchTableData(spinner) {
        $.ajax({
          url: SITE_URL + 'application_input/pemol_get_data',
          type: 'POST',
          data: {
            draw: 1,
            start: 0,
            length: -1,     // ambil semua untuk paginasi client-side
            'search[value]': ''
          },
          dataType: 'json',
          success: function(resp) {
            spinner.classList.add('hidden');
            allData = resp.data || [];
            searchQuery = '';
            const searchEl = document.getElementById('searchInput');
            if (searchEl) searchEl.value = '';
            applySearch();
          },
          error: function() {
            spinner.classList.add('hidden');
            allData = [];
            applySearch();
          }
        });
      }

      // ======================== APPLY FILTER (DATE) ========================
      window.applyFilter = function() {
        const errEl = document.getElementById('filterError');
        errEl.classList.add('hidden');
        loadData();
      };

      // ======================== SEARCH ========================
      window.handleSearch = function(query) {
        searchQuery = query.toLowerCase();
        applySearch();
      };

      function applySearch() {
        // Filter berdasarkan kolom ke-2 (index 1 = nama sales)
        if (searchQuery) {
          filteredData = allData.filter(row => {
            const cell = row[1] ? row[1].toString().toLowerCase() : '';
            return cell.includes(searchQuery);
          });
        } else {
          filteredData = [...allData];
        }
        currentPage = 1;
        renderTable();
        renderPagination();
      }

      // ======================== RENDER TABLE ========================
      function renderTable() {
        const tbody = document.getElementById('mainTableBody');
        const start = (currentPage - 1) * rowsPerPage;
        const end   = start + rowsPerPage;
        const pageData = filteredData.slice(start, end);

        if (pageData.length === 0) {
          const colCount = IS_LEADER ? 9 : 6;
          tbody.innerHTML = `<tr><td colspan="${colCount}" style="padding:2rem;text-align:center;color:#9CA3AF">Tidak ada data ditemukan</td></tr>`;
          document.getElementById('showingInfo').textContent = 'Showing 0 to 0 of 0';
          return;
        }

        tbody.innerHTML = pageData.map((row, idx) => {
          const cells = row.map((cell, ci) => {
            let align = 'center';
            if (ci === 1 || ci === 2) align = 'left';
            return `<td style="padding:10px 14px;font-size:0.8125rem;color:#374151;text-align:${align};white-space:nowrap;border-bottom:1px solid #F3F4F6">${cell}</td>`;
          }).join('');
          return `<tr style="background:white" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">${cells}</tr>`;
        }).join('');

        const showing_start = start + 1;
        const showing_end   = Math.min(end, filteredData.length);
        document.getElementById('showingInfo').textContent = `Showing ${showing_start} to ${showing_end} of ${filteredData.length}`;
        lucide.createIcons();
      }

      // ======================== PAGINATION ========================
      function renderPagination() {
        const totalPages = Math.ceil(filteredData.length / rowsPerPage);
        const controls   = document.getElementById('paginationControls');

        let html = `<button onclick="changePage(${currentPage-1})" class="p-1 text-gray-400 hover:text-gray-700 ${currentPage===1?'opacity-50 cursor-not-allowed':''}" ${currentPage===1?'disabled':''}>
          <i data-lucide="chevron-left" class="w-4 h-4"></i></button>`;

        for (let i = 1; i <= totalPages; i++) {
          if (i === currentPage) {
            html += `<span class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 font-medium rounded-lg text-sm">${i}</span>`;
          } else {
            html += `<button onclick="changePage(${i})" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 text-gray-600 rounded-lg transition-colors text-sm">${i}</button>`;
          }
        }

        html += `<button onclick="changePage(${currentPage+1})" class="p-1 text-gray-400 hover:text-gray-700 ${(currentPage===totalPages||totalPages===0)?'opacity-50 cursor-not-allowed':''}" ${(currentPage===totalPages||totalPages===0)?'disabled':''}>
          <i data-lucide="chevron-right" class="w-4 h-4"></i></button>`;

        controls.innerHTML = html;
        lucide.createIcons();
      }

      window.changePage = function(page) {
        const totalPages = Math.ceil(filteredData.length / rowsPerPage);
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        renderTable();
        renderPagination();
      };

      window.handleRowsPerPageChange = function(val) {
        rowsPerPage = parseInt(val);
        currentPage = 1;
        renderTable();
        renderPagination();
      };

      // ======================== DETAIL MODAL (view_spv) ========================
      let modalData      = [];
      let modalPage      = 1;
      let modalRows      = 5;
      let modalFiltered  = [];

      window.view_spv = function(sales, position, name) {
        // Simpan ke session lalu load data detail via get_data_spv
        document.getElementById('modalTitle').textContent   = 'Detail: ' + name;
        document.getElementById('modalSubtitle').textContent = '(' + position + ') — ' + sales;

        // Build detail table header
        document.getElementById('detailTableHead').innerHTML = `
          <tr>
            <th style="background:#1E5BA8;color:white;padding:10px 14px;text-align:center;font-size:0.8125rem;font-weight:500;border-right:1px solid rgba(255,255,255,0.2)">No</th>
            <th style="background:#1E5BA8;color:white;padding:10px 14px;text-align:left;font-size:0.8125rem;font-weight:500;border-right:1px solid rgba(255,255,255,0.2)">Nama Sales</th>
            <th style="background:#1E5BA8;color:white;padding:10px 14px;text-align:left;font-size:0.8125rem;font-weight:500;border-right:1px solid rgba(255,255,255,0.2)">Branch</th>
            <th style="background:#1E5BA8;color:white;padding:10px 14px;text-align:center;font-size:0.8125rem;font-weight:500;border-right:1px solid rgba(255,255,255,0.2)">DSR Active</th>
            <th style="background:#1E5BA8;color:white;padding:10px 14px;text-align:center;font-size:0.8125rem;font-weight:500;border-right:1px solid rgba(255,255,255,0.2)">DSR Input</th>
            <th style="background:#1E5BA8;color:white;padding:10px 14px;text-align:center;font-size:0.8125rem;font-weight:500;border-right:1px solid rgba(255,255,255,0.2)">Mobile BCA</th>
            <th style="background:#1E5BA8;color:white;padding:10px 14px;text-align:center;font-size:0.8125rem;font-weight:500;border-right:1px solid rgba(255,255,255,0.2)">myBCA</th>
            <th style="background:#1E5BA8;color:white;padding:10px 14px;text-align:center;font-size:0.8125rem;font-weight:500">Total</th>
          </tr>`;

        // Show modal
        openModal();

        // Fetch detail
        document.getElementById('modalSpinner').classList.remove('hidden');
        document.getElementById('detailTableBody').innerHTML = '';

        $.ajax({
          url: SITE_URL + 'application_input/pemol_detail/' + sales + '/' + position,
          type: 'GET',
          success: function() {
            // Setelah detail session ter-set, ambil data
            $.ajax({
              url: SITE_URL + 'application_input/pemol_get_data_spv',
              type: 'POST',
              data: {
                draw: 1,
                start: 0,
                length: -1,
                'search[value]': ''
              },
              dataType: 'json',
              success: function(resp) {
                document.getElementById('modalSpinner').classList.add('hidden');
                modalData      = resp.data || [];
                modalFiltered  = [...modalData];
                modalPage      = 1;
                renderModalTable();
                renderModalPagination();
              },
              error: function() {
                document.getElementById('modalSpinner').classList.add('hidden');
                document.getElementById('detailTableBody').innerHTML = '<tr><td colspan="8" style="padding:2rem;text-align:center;color:#9CA3AF">Gagal memuat data</td></tr>';
              }
            });
          },
          error: function() {
            // Fallback: langsung fetch get_data_spv
            $.ajax({
              url: SITE_URL + 'application_input/pemol_get_data_spv',
              type: 'POST',
              data: { draw: 1, start: 0, length: -1, 'search[value]': '' },
              dataType: 'json',
              success: function(resp) {
                document.getElementById('modalSpinner').classList.add('hidden');
                modalData     = resp.data || [];
                modalFiltered = [...modalData];
                modalPage     = 1;
                renderModalTable();
                renderModalPagination();
              },
              error: function() {
                document.getElementById('modalSpinner').classList.add('hidden');
              }
            });
          }
        });
      };

      function renderModalTable() {
        const tbody = document.getElementById('detailTableBody');
        const start = (modalPage - 1) * modalRows;
        const end   = start + modalRows;
        const page  = modalFiltered.slice(start, end);

        if (page.length === 0) {
          tbody.innerHTML = '<tr><td colspan="8" style="padding:2rem;text-align:center;color:#9CA3AF">Tidak ada data</td></tr>';
          document.getElementById('modalShowingInfo').textContent = 'Showing 0 to 0 of 0';
          return;
        }

        tbody.innerHTML = page.map(row => {
          const cells = row.map((cell, ci) => {
            let align = 'center';
            if (ci === 1 || ci === 2) align = 'left';
            return `<td style="padding:9px 14px;font-size:0.8rem;color:#374151;text-align:${align};white-space:nowrap;border-bottom:1px solid #F3F4F6">${cell}</td>`;
          }).join('');
          return `<tr style="background:white" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">${cells}</tr>`;
        }).join('');

        const s = start + 1;
        const e = Math.min(end, modalFiltered.length);
        document.getElementById('modalShowingInfo').textContent = `Showing ${s} to ${e} of ${modalFiltered.length}`;
        lucide.createIcons();
      }

      function renderModalPagination() {
        const totalPages = Math.ceil(modalFiltered.length / modalRows);
        const controls   = document.getElementById('modalPaginationControls');
        let html = `<button onclick="changeModalPage(${modalPage-1})" class="p-1 text-gray-400 hover:text-gray-700 ${modalPage===1?'opacity-50 cursor-not-allowed':''}" ${modalPage===1?'disabled':''}>
          <i data-lucide="chevron-left" class="w-4 h-4"></i></button>`;
        for (let i = 1; i <= Math.min(totalPages, 5); i++) {
          if (i === modalPage) {
            html += `<span class="w-7 h-7 flex items-center justify-center bg-blue-100 text-blue-600 font-medium rounded-lg text-xs">${i}</span>`;
          } else {
            html += `<button onclick="changeModalPage(${i})" class="w-7 h-7 flex items-center justify-center hover:bg-gray-100 text-gray-600 rounded-lg text-xs">${i}</button>`;
          }
        }
        html += `<button onclick="changeModalPage(${modalPage+1})" class="p-1 text-gray-400 hover:text-gray-700 ${(modalPage===totalPages||totalPages===0)?'opacity-50 cursor-not-allowed':''}" ${(modalPage===totalPages||totalPages===0)?'disabled':''}>
          <i data-lucide="chevron-right" class="w-4 h-4"></i></button>`;
        controls.innerHTML = html;
        lucide.createIcons();
      }

      window.changeModalPage = function(p) {
        const totalPages = Math.ceil(modalFiltered.length / modalRows);
        if (p < 1 || p > totalPages) return;
        modalPage = p;
        renderModalTable();
        renderModalPagination();
      };

      window.handleModalRowsChange = function(val) {
        modalRows = parseInt(val);
        modalPage = 1;
        renderModalTable();
        renderModalPagination();
      };

      function openModal() {
        const overlay = document.getElementById('detailModal');
        overlay.style.display = 'flex';
        setTimeout(() => overlay.classList.add('active'), 10);
        document.body.style.overflow = 'hidden';
      }

      window.closeModal = function() {
        const overlay = document.getElementById('detailModal');
        overlay.classList.remove('active');
        setTimeout(() => { overlay.style.display = 'none'; }, 200);
        document.body.style.overflow = '';
      };

      document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) window.closeModal();
      });

      // ======================== FLATPICKR DATE RANGE ========================
      // Load Flatpickr dynamically
      const fpCss = document.createElement('link');
      fpCss.rel = 'stylesheet';
      fpCss.href = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css';
      document.head.appendChild(fpCss);

      const fpScript = document.createElement('script');
      fpScript.src = 'https://cdn.jsdelivr.net/npm/flatpickr';
      fpScript.onload = function() {
        flatpickr("#dateRangeInput", {
          mode: "range",
          dateFormat: "d/m/Y",
          defaultDate: [DATE_FROM, DATE_TO],
          locale: {
            rangeSeparator: ' - ',
          },
          onClose: function(selectedDates) {
            if (selectedDates.length === 2) {
              const fmt = d => {
                const y = d.getFullYear();
                const m = String(d.getMonth()+1).padStart(2,'0');
                const day = String(d.getDate()).padStart(2,'0');
                return y+'-'+m+'-'+day;
              };
              selectedFrom = fmt(selectedDates[0]);
              selectedTo   = fmt(selectedDates[1]);
              document.getElementById('activePeriod').textContent =
                fmtDate(selectedFrom) + ' - ' + fmtDate(selectedTo);
            }
          }
        });
      };
      document.head.appendChild(fpScript);

      // ======================== INITIAL LOAD ========================
      loadData();
    </script>
  </body>
</html>
