<!-- Modal View Downline Merchant -->
<div id="modalDownline" class="hidden fixed inset-0 z-[10000] overflow-y-auto">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

  <!-- Modal Content -->
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-6xl bg-white rounded-[32px] shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
      
      <!-- Modal Header -->
      <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10">
        <h2 id="modalDownlineTitle" class="text-2xl font-bold text-[#1E293B]">Downline Name</h2>
        <button onclick="closeModalDownline()" class="p-2 hover:bg-gray-100 rounded-full transition-colors text-gray-400 hover:text-gray-600">
          <i data-lucide="x" class="w-6 h-6"></i>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-8">
        <!-- Filters Area -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6 items-center">
            <!-- Search Pill -->
            <div class="relative w-full sm:w-64">
              <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
              <input type="text" id="modalSearchInput" placeholder="Search" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-full text-sm outline-none focus:border-blue-500 transition-colors" oninput="debounceModalSearch(this.value)"/>
            </div>

            <!-- Date Display (Static for now/follow main) -->
            <div class="flex items-center gap-2 px-5 py-2.5 border border-gray-200 rounded-full bg-gray-50/50">
                <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                <span id="modalDateRangeDisplay" class="text-sm font-medium text-gray-600">1 Jan, 2025 - 2 Feb, 2025</span>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-sm relative sticky-table-container mb-6">
          <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-[#2463B4] text-white">
              <tr>
                <th rowspan="2" class="px-4 py-4 font-semibold text-center border-r border-white/10 w-12">No</th>
                <th rowspan="2" id="modalDownlineColName" class="px-4 py-4 font-semibold border-r border-white/10">ASM Name</th>
                <th rowspan="2" class="px-4 py-4 font-semibold border-r border-white/10">NIK Sales</th>
                <th rowspan="2" class="px-4 py-4 font-semibold border-r border-white/10 text-center">Branch</th>
                <th colspan="2" class="px-4 py-2 font-semibold text-center border-b border-white/10 border-r border-white/10">Total DSR</th>
                <th colspan="3" class="px-4 py-2 font-semibold text-center border-b border-white/10">Input</th>
                <th rowspan="2" class="px-4 py-4 font-semibold text-center border-l border-white/10">Action</th>
              </tr>
              <tr>
                <th class="px-4 py-2 font-semibold text-center border-r border-white/10 text-[11px] uppercase tracking-wider">Active</th>
                <th class="px-4 py-2 font-semibold text-center border-r border-white/10 text-[11px] uppercase tracking-wider">Input</th>
                <th class="px-4 py-2 font-semibold text-center border-r border-white/10 text-[11px] uppercase tracking-wider">BCA Mobile</th>
                <th class="px-4 py-2 font-semibold text-center border-r border-white/10 text-[11px] uppercase tracking-wider">My BCA</th>
                <th class="px-4 py-2 font-semibold text-center text-[11px] uppercase tracking-wider">Total</th>
              </tr>
            </thead>
            <tbody id="modalTableBody" class="divide-y divide-gray-50">
              <!-- Data will be loaded via AJAX -->
              <tr id="modalLoadingRow">
                <td colspan="10" class="px-4 py-12 text-center text-gray-400">
                  <div class="flex flex-col items-center gap-3">
                         <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                         <p class="font-medium">Loading data...</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination Section (Modal) -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
            <div id="modalShowingInfo" class="text-sm text-gray-500 font-medium">Showing 0 to 0 of 0</div>
            <div class="flex items-center gap-1" id="modalPaginationControls"></div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-400 font-medium">Show</span>
                <div class="relative">
                    <select id="modalRowsPerPage" onchange="handleModalRowsPerPageChange(this.value)" class="appearance-none border border-gray-200 rounded-full pl-4 pr-10 py-1.5 text-sm font-semibold text-gray-700 outline-none focus:ring-2 focus:ring-blue-100 bg-white shadow-sm cursor-pointer transition-all hover:border-gray-300">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                </div>
            </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  let modalCurrentPage = 1;
  let modalRowsPerPage = 10;
  let modalTotalRecords = 0;
  let modalSearchTimeout = null;
  let currentDownlineNik = null;
  let currentDownlinePosition = null;

  window.closeModalDownline = function() {
    document.getElementById('modalDownline').classList.add('hidden');
    document.body.style.overflow = '';
  };

  window.handleModalRowsPerPageChange = function(val) {
    modalRowsPerPage = parseInt(val);
    loadModalData(1);
  };

  window.debounceModalSearch = function(val) {
    clearTimeout(modalSearchTimeout);
    modalSearchTimeout = setTimeout(() => {
      loadModalData(1);
    }, 500);
  };

  window.loadModalData = async function(page = 1) {
    modalCurrentPage = page;
    const tbody = document.getElementById("modalTableBody");
    const search = document.getElementById("modalSearchInput").value;
    
    // Show skeleton/loading
    tbody.innerHTML = `
      <tr>
        <td colspan="10" class="px-4 py-12 text-center text-gray-400">
          <div class="flex flex-col items-center gap-3">
                 <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                 <p class="font-medium">Loading data...</p>
          </div>
        </td>
      </tr>
    `;

    try {
      const response = await fetch("<?= site_url('application_merchant/get_downline_data') ?>", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
          nik: currentDownlineNik,
          position: currentDownlinePosition,
          draw: 1,
          start: (modalCurrentPage - 1) * modalRowsPerPage,
          length: modalRowsPerPage,
          search: search,
          date_from: selectedFrom,
          date_to: selectedTo,
          source: selectedSource
        })
      });

      const res = await response.json();
      modalTotalRecords = parseInt(res.recordsFiltered);
      
      if (res.data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="10" class="px-4 py-12 text-center text-gray-400 font-medium">No results found for "${search}"</td></tr>`;
      } else {
        tbody.innerHTML = res.data.map(row => {
          return `
            <tr class="hover:bg-blue-50/30 transition-colors group">
              <td class="px-4 py-4 text-center text-gray-500 font-medium">${row[0]}</td>
              <td class="px-4 py-4 font-bold text-[#1E293B]">${row[1]}</td>
              <td class="px-4 py-4 font-medium text-gray-600">${row[2]}</td>
              <td class="px-4 py-4 text-center text-gray-600">${row[3]}</td>
              <td class="px-4 py-4 text-center">${row[4]}</td>
              <td class="px-4 py-4 text-center">${row[5]}</td>
              <td class="px-4 py-4 text-center border-l border-gray-50">${row[6]}</td>
              <td class="px-4 py-4 text-center">${row[7]}</td>
              <td class="px-4 py-4 text-center font-bold text-[#1E5BA8]">${row[8]}</td>
              <td class="px-4 py-4 text-center">
                ${row[9] || '<span class="text-xs text-gray-300">N/A</span>'}
              </td>
            </tr>
          `;
        }).join("");
      }
      
      renderModalPagination();
      lucide.createIcons();
    } catch (error) {
      tbody.innerHTML = `<tr><td colspan="10" class="px-4 py-12 text-center text-red-500 font-medium">Error loading data. Please try again.</td></tr>`;
    }
  };

  window.loadModalDataDeep = function(event, element) {
    event.preventDefault();
    const nik = element.getAttribute('data-nik');
    const name = element.getAttribute('data-name');
    const position = element.getAttribute('data-position');
    
    // Update State
    currentDownlineNik = nik;
    currentDownlinePosition = position;
    
    // Update UI
    document.getElementById('modalDownlineTitle').innerText = name;
    
    let childLabel = 'Downline Name';
    if (position == 'BSH') childLabel = 'RSM Name';
    else if (position == 'RSM') childLabel = 'ASM Name';
    else if (position == 'ASM') childLabel = 'SPV Name';
    else if (position == 'SPV') childLabel = 'DSR Name';
    document.getElementById('modalDownlineColName').innerText = childLabel;
    
    // Load
    loadModalData(1);
  };

  function renderModalPagination() {
    const totalPages = Math.ceil(modalTotalRecords / modalRowsPerPage);
    const container = document.getElementById("modalPaginationControls");
    const startIdx = modalTotalRecords === 0 ? 0 : (modalCurrentPage - 1) * modalRowsPerPage + 1;
    const endIdx = Math.min(modalCurrentPage * modalRowsPerPage, modalTotalRecords);
    
    document.getElementById("modalShowingInfo").innerText = `Showing ${startIdx} to ${endIdx} of ${modalTotalRecords}`;

    if (totalPages <= 1 && modalTotalRecords > 0) {
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
      <button onclick="loadModalData(${modalCurrentPage - 1})" class="p-2 text-gray-400 hover:text-gray-600 transition-colors disabled:opacity-20 disabled:cursor-not-allowed" ${modalCurrentPage === 1 ? 'disabled' : ''}>
        <i data-lucide="chevron-left" class="w-4 h-4"></i>
      </button>
    `;

    const maxVisible = 5;
    let startPage = Math.max(1, modalCurrentPage - Math.floor(maxVisible / 2));
    let endPage = Math.min(totalPages, startPage + maxVisible - 1);
    if (endPage - startPage + 1 < maxVisible) startPage = Math.max(1, endPage - maxVisible + 1);

    for (let i = startPage; i <= endPage; i++) {
      html += `
        <button onclick="loadModalData(${i})" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all ${i === modalCurrentPage ? 'bg-blue-50 text-[#1E5BA8] ring-1 ring-blue-100 shadow-sm' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-50'}">
          ${i}
        </button>
      `;
    }

    html += `
      <button onclick="loadModalData(${modalCurrentPage + 1})" class="p-2 text-gray-400 hover:text-gray-600 transition-colors disabled:opacity-20 disabled:cursor-not-allowed" ${modalCurrentPage === totalPages ? 'disabled' : ''}>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
      </button>
    `;

    container.innerHTML = html;
    lucide.createIcons();
  }
</script>
