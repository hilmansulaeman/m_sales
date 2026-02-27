<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Application Input Merchant - M-Sales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>window.SITE_URL = "<?= site_url(); ?>/";</script>
    <script src="<?= base_url('assets/js/layout.js') ?>"></script>
  </head>

  <body class="bg-gray-50">
    <div id="app"></div>

    <!-- Modal Layouts (Outside Script) -->
    <?php $this->load->view('application_input/modal/modal_downline_merchant'); ?>
<?php $this->load->view('application_input/modal/modal_detail_merchant'); ?>
<?php $this->load->view('application_input/modal/modal_breakdown_merchant'); ?>

<script src="<?php echo base_url('assets/js/detail_modal.js?v=' . time()); ?>"></script>


    <script>
      // PHP Variables
      const userPosition = "<?= $user_position ?>";
      const isLeader = <?= $is_leader ? 'true' : 'false' ?>;
      const initialDateFrom = "<?= $date_from ?>";
      const initialDateTo = "<?= $date_to ?>";
      const initialSource = "<?= $source ?>";
      const tablePositionLabel = "<?= $table_position ?> Name";

      // State
      let currentPage = 1;
      let rowsPerPage = 10;
      let searchQuery = "";
      window.selectedFrom = initialDateFrom;
      window.selectedTo = initialDateTo;
      window.selectedSource = initialSource;
      let totalRecords = 0;
      let searchTimeout = null;

      initLayout("Application Input - Merchant");

      const appContainer = document.querySelector("#app > div > div");
      const main = document.createElement("div");
      main.className = "flex-1 bg-gray-50 flex flex-col";

      main.innerHTML = `
        <div class="flex-1 p-6">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <!-- Header Section -->
           
            <!-- Filters & Export Bar (Sesuai Gambar) -->
            <div class="flex flex-col lg:flex-row gap-4 mb-8 items-center justify-between">
              <div class="flex flex-col sm:flex-row gap-4 items-center w-full lg:w-auto">
                <!-- Search Pill -->
                <div class="relative w-full sm:w-64">
                  <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                  <input type="text" id="searchInput" placeholder="Search" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-full text-sm outline-none focus:border-blue-500 transition-colors" oninput="debounceSearch(this.value)"/>
                </div>

                <!-- Source Pilot Style (Sesuai Gambar) -->
                <div class="relative w-full sm:w-auto flex items-center h-[42px]">
                  <div class="flex items-center border border-gray-200 rounded-full overflow-hidden h-full shadow-sm">
                    <div class="bg-[#2463B4] text-white px-5 h-full flex items-center text-sm font-medium">
                      Source
                    </div>
                    <div class="relative bg-white h-full">
                      <select id="sourceSelect" onchange="handleSourceChange(this.value)" class="appearance-none bg-transparent pl-4 pr-10 h-full text-sm text-gray-600 outline-none cursor-pointer font-medium">
                        <option value="all">All product</option>
                        <option value="BCA">BCA</option>
                        <option value="Mobile">Mobile</option>
                      </select>
                      <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    </div>
                  </div>
                </div>

                <!-- Date Range Pill -->
                <div class="relative w-full sm:w-64 group">
                  <i data-lucide="calendar" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none z-10"></i>
                  <input type="text" id="dateRangeInput" readonly class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-full text-sm bg-white cursor-pointer group-hover:border-blue-400 transition-colors outline-none" placeholder="Select date range"/>
                </div>
                
                <button onclick="loadData(1)" class="bg-gray-50 text-gray-400 p-2.5 rounded-full hover:bg-gray-100 transition-colors border border-gray-200" title="Refresh Data">
                  <i data-lucide="refresh-cw" id="refreshIcon" class="w-4 h-4"></i>
                </button>
              </div>

              <!-- Export Data Button (Sesuai Gambar) -->
              <button onclick="handleExport()" class="w-full lg:w-auto bg-[#2463B4] text-white px-6 py-2.5 rounded-full text-sm font-medium hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 shadow-sm">
                <i data-lucide="share" class="w-4 h-4 translate-x-[-2px]"></i>
                Export data
              </button>
            </div>


            <!-- Table Section -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm relative sticky-table-container">
              <table class="w-full text-sm text-left">
                <thead class="bg-[#1E5BA8] text-white">
                  <tr>
                    <th class="px-4 py-4 text-center font-medium w-12" rowspan="2">No</th>
                    <th class="px-4 py-4 font-medium" rowspan="2">${tablePositionLabel}</th>
                    <th class="px-4 py-4 font-medium" rowspan="2">Code</th>
                    <th class="px-4 py-4 font-medium" rowspan="2">Branch</th>
                    <th class="px-4 py-4 text-center font-medium" rowspan="2">Active<br>DSR</th>
                    <th class="px-4 py-4 text-center font-medium" rowspan="2">Input<br>System</th>
                    <th class="px-4 py-4 text-center font-medium" rowspan="2">Received<br>App</th>
                    <th class="px-4 py-4 text-center font-medium" rowspan="2">Inprocess</th>
                    <th class="px-4 py-4 text-center font-medium" rowspan="2">RTS</th>
                    <th class="px-4 py-4 text-center font-medium border-l border-white/20" colspan="3">Send To BCA</th>
                    <th class="px-4 py-4 text-center font-medium border-l border-white/20" rowspan="2">Action</th>
                  </tr>
                  <tr>
                    <th class="px-4 py-2 text-center text-[11px] uppercase tracking-wider border-l border-white/20">Success</th>
                    <th class="px-4 py-2 text-center text-[11px] uppercase tracking-wider">Pending</th>
                    <th class="px-4 py-2 text-center text-[11px] uppercase tracking-wider">Cancel</th>
                  </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-100">
                  <tr><td colspan="15" class="py-20 text-center"><div class="flex flex-col items-center gap-3"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><span class="text-gray-500">Loading initial data...</span></div></td></tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination Section -->
            <div class="px-2 py-6 flex flex-col sm:flex-row items-center justify-between gap-6 mt-4 border-t border-gray-50">
              <div id="showingInfo" class="text-sm text-gray-500 font-medium order-2 sm:order-1">Showing 0 to 0 of 0</div>
              
              <div class="flex items-center gap-1 order-1 sm:order-2" id="paginationControls"></div>
              
              <div class="flex items-center gap-3 order-3 sm:order-3">
                <span class="text-sm text-gray-400 font-medium">Show</span>
                <div class="relative">
                  <select onchange="handleRowsPerPageChange(this.value)" class="appearance-none border border-gray-200 rounded-full pl-4 pr-10 py-1.5 text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-blue-100 bg-white shadow-sm cursor-pointer transition-all hover:border-gray-300">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                  </select>
                  <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Menu Popup (Floating) -->
        <div id="actionMenu" class="hidden fixed bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 py-2 w-48 z-[9999] animate-in fade-in zoom-in-95 duration-150 origin-top-right">
          <button onclick="handleViewDownline()" class="w-full text-left px-5 py-3 text-[#1E293B] hover:bg-blue-50 transition-colors flex items-center gap-3 group">
            <span class="text-[15px] font-medium">View downline</span>
          </button>
          <button onclick="handleViewDetail()" class="w-full text-left px-5 py-3 text-[#1E293B] hover:bg-blue-50 transition-colors flex items-center gap-3 group">
            <span class="text-[15px] font-medium">View detail</span>
          </button>
        </div>
      `;


      appContainer.appendChild(main);

      const app = document.getElementById("app");
      lucide.createIcons();

      // --- Initialization ---

      // Flatpickr for Date Range
      flatpickr("#dateRangeInput", {
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: [selectedFrom, selectedTo],
        onClose: function(selectedDates, dateStr) {
          if (selectedDates.length === 2) {
            selectedFrom = flatpickr.formatDate(selectedDates[0], "Y-m-d");
            selectedTo = flatpickr.formatDate(selectedDates[1], "Y-m-d");
            loadData();
          }
        }
      });

      // Load initial data
      loadData(1);

      // --- Core Functions ---

      async function loadData(page = 1) {
        currentPage = page;
        const start = (currentPage - 1) * rowsPerPage;
        
        const refreshIcon = document.getElementById('refreshIcon');
        if(refreshIcon) refreshIcon.classList.add('animate-spin');
        
        const activePeriod = document.getElementById('activePeriod');
        if(activePeriod) activePeriod.innerText = formatDateLabel(selectedFrom, selectedTo);
        
        const tableBody = document.getElementById('tableBody');
        if(tableBody) tableBody.style.opacity = '0.5';

        try {
          // Fetch Table Data (Kirim filter langsung di sini)
          const response = await $.ajax({
            url: SITE_URL + 'application_input/merchant_get_data',
            type: 'POST',
            data: { 
              draw: 1, 
              start: start, 
              length: rowsPerPage, 
              'search[value]': searchQuery,
              date_from: selectedFrom,
              date_to: selectedTo,
              source: selectedSource
            },
            dataType: 'json'
          });

          renderTable(response.data || []);
          totalRecords = response.recordsFiltered;
          renderPagination();
          
        } catch (error) {
          console.error("Load data error:", error);
          document.getElementById('tableBody').innerHTML = `<tr><td colspan="15" class="py-10 text-center text-red-500">Failed to load data. Please try again.</td></tr>`;
        } finally {
          if(refreshIcon) refreshIcon.classList.remove('animate-spin');
          const tableBodyResult = document.getElementById('tableBody');
          if(tableBodyResult) tableBodyResult.style.opacity = '1';
        }
      }

      function renderTable(data) {
        const tbody = document.getElementById("tableBody");

        if (data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="15" class="py-20 text-center text-gray-500 italic">No data matches your criteria</td></tr>`;
          return;
        }

        tbody.innerHTML = data.map(row => {
          return `
            <tr class="hover:bg-blue-50/30 transition-colors group">
              <td class="px-4 py-3.5 text-center text-gray-600 font-medium">${row[0]}</td>
              <td class="px-4 py-3.5 font-semibold text-gray-800">${row[1]}</td>
              <td class="px-4 py-3.5 text-gray-700">${row[2]}</td>
              <td class="px-4 py-3.5 text-gray-700">${row[3]}</td>
              <td class="px-4 py-3.5 text-center">${row[4]}</td>
              <td class="px-4 py-3.5 text-center">${row[5]}</td>
              <td class="px-4 py-3.5 text-center">${row[6]}</td>
              <td class="px-4 py-3.5 text-center">${row[7]}</td>
              <td class="px-4 py-3.5 text-center">${row[8]}</td>
              <td class="px-4 py-3.5 text-center border-l border-gray-100 font-bold text-green-600">${row[9]}</td>
              <td class="px-4 py-3.5 text-center text-yellow-600 font-bold">${row[10]}</td>
              <td class="px-4 py-3.5 text-center text-red-600 font-bold">${row[11]}</td>
              <td class="px-4 py-3.5 text-center border-l border-gray-100">
                ${row[12] || '<span class="text-xs text-gray-400 italic">N/A</span>'}
              </td>
            </tr>
          `;
        }).join("");

        lucide.createIcons();
      }

      function renderPagination() {
        const totalPages = Math.ceil(totalRecords / rowsPerPage);
        const container = document.getElementById("paginationControls");
        const startIdx = totalRecords === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
        const endIdx = Math.min(currentPage * rowsPerPage, totalRecords);
        
        document.getElementById("showingInfo").innerText = `Showing ${startIdx} to ${endIdx} of ${totalRecords} entries`;

        if (totalPages <= 1 && totalRecords > 0) {
            // Show only one page if total is small
            container.innerHTML = `
                <div class="flex items-center gap-1">
                    <button class="p-2 text-gray-300 cursor-not-allowed"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
                    <button class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-blue-50 text-[#1E5BA8]">1</button>
                    <button class="p-2 text-gray-300 cursor-not-allowed"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
                </div>
            `;
            lucide.createIcons();
            return;
        }

        if (totalPages === 0) {
            container.innerHTML = "";
            return;
        }

        let html = `
          <button onclick="changePage(${currentPage - 1})" class="p-2 text-gray-400 hover:text-gray-600 transition-colors disabled:opacity-20 disabled:cursor-not-allowed" ${currentPage === 1 ? 'disabled' : ''}>
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
          </button>
        `;

        const maxVisible = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        if (endPage - startPage + 1 < maxVisible) startPage = Math.max(1, endPage - maxVisible + 1);

        for (let i = startPage; i <= endPage; i++) {
          html += `
            <button onclick="changePage(${i})" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all ${i === currentPage ? 'bg-blue-50 text-[#1E5BA8] ring-1 ring-blue-100 shadow-sm' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-50'}">
              ${i}
            </button>
          `;
        }

        html += `
          <button onclick="changePage(${currentPage + 1})" class="p-2 text-gray-400 hover:text-gray-600 transition-colors disabled:opacity-20 disabled:cursor-not-allowed" ${currentPage === totalPages ? 'disabled' : ''}>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
          </button>
        `;

        container.innerHTML = html;
        lucide.createIcons();
      }

      // --- Helpers & Event Handlers ---

      window.activeActionData = null;

      window.showActionMenu = function(event, element) {
        event.stopPropagation();
        const menu = document.getElementById('actionMenu');
        const rect = element.getBoundingClientRect();
        
        activeActionData = {
          nik: element.getAttribute('data-nik'),
          name: element.getAttribute('data-name'),
          position: element.getAttribute('data-position')
        };

        // Posisi menu (di bawah tombol, geser ke kiri agar tidak terpotong)
        menu.style.top = (rect.bottom + window.scrollY + 5) + 'px';
        menu.style.left = (rect.right - 192) + 'px'; // 192px is w-48
        
        menu.classList.remove('hidden');
      };

      window.handleViewDownline = function() {
        if(!activeActionData) return;
        
        // Setup Modal State
        currentDownlineNik = activeActionData.nik;
        currentDownlinePosition = activeActionData.position;
        
        // Update Modal UI Labels
        document.getElementById('modalDownlineTitle').innerText = activeActionData.name;
        
        let childLabel = 'Downline Name';
        if (activeActionData.position == 'BSH') childLabel = 'RSM Name';
        else if (activeActionData.position == 'RSM') childLabel = 'ASM Name';
        else if (activeActionData.position == 'ASM') childLabel = 'SPV Name';
        else if (activeActionData.position == 'SPV') childLabel = 'DSR Name';
        document.getElementById('modalDownlineColName').innerText = childLabel;
        
        // Sync Date Label from Main
        const mainDate = document.getElementById('dateRangeInput')?.value || '';
        document.getElementById('modalDateRangeDisplay').innerText = mainDate;

        // Open Modal
        const modal = document.getElementById('modalDownline');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Load Data
        loadModalData(1);

        document.getElementById('actionMenu').classList.add('hidden');
      };

      window.handleViewDetail = function() {
        if(!activeActionData) return;
        
        // Open modal
        const modal = document.getElementById('modalDetail');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Set modal title
        document.getElementById('modalDetailTitle').innerText = activeActionData.name;

        // Reset tab to default
        window.currentDetailTab = 'data-input';
        
        // Initial Load Content
        loadDetailContent();

        // Hide action menu
        document.getElementById('actionMenu').classList.add('hidden');
      };

      // Close menu on click outside
      document.addEventListener('click', function() {
        const menu = document.getElementById('actionMenu');
        if(menu) menu.classList.add('hidden');
      });

      window.debounceSearch = function(val) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
          searchQuery = val.toLowerCase();
          loadData(1);
        }, 500);
      };

      window.handleSourceChange = function(val) {
        selectedSource = val;
        loadData(1);
      };

      window.handleRowsPerPageChange = function(val) {
        rowsPerPage = parseInt(val);
        loadData(1);
      };

      window.changePage = function(p) {
        const totalPages = Math.ceil(totalRecords / rowsPerPage);
        if (p < 1 || p > totalPages) return;
        loadData(p);
      };

      window.handleExport = function() {
        window.location.href = SITE_URL + 'application_input/merchant_export';
      };

      // Close Detail Modal
      window.closeModalDetail = function() {
        document.getElementById('modalDetail').classList.add('hidden');
        document.body.style.overflow = '';
      };

      function formatDateLabel(from, to) {
        const opts = { day: 'numeric', month: 'short', year: 'numeric' };
        return `${new Date(from).toLocaleDateString('id-ID', opts)} sd ${new Date(to).toLocaleDateString('id-ID', opts)}`;
      }

    </script>
  </body>
</html>
