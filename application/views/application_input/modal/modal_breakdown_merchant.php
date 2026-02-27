<!-- Modal Breakdown Merchant (Level 3) -->
<div id="modalBreakdown" class="hidden fixed inset-0 z-[10001] overflow-y-auto">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModalBreakdown()"></div>

  <!-- Modal Content -->
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-5xl bg-white rounded-[40px] shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
      
      <!-- Modal Header -->
      <div class="px-10 pt-10 pb-6 flex items-center justify-between bg-white sticky top-0 z-10">
        <h2 id="modalBreakdownTitle" class="text-3xl font-bold text-[#1E293B]">Breakdown Detail</h2>
        <button onclick="closeModalBreakdown()" class="p-2 hover:bg-gray-100 rounded-full transition-colors text-gray-400 hover:text-gray-600">
          <i data-lucide="x" class="w-8 h-8"></i>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="px-10 pb-10">
        <!-- Search Pill -->
        <div class="relative w-full mb-8">
            <i data-lucide="search" class="absolute left-6 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
            <input type="text" id="breakdownSearchInput" placeholder="Search" class="w-full pl-14 pr-6 py-4 border border-gray-100 bg-gray-50/30 rounded-full text-base outline-none focus:border-blue-500 focus:bg-white transition-all shadow-sm" oninput="debounceBreakdownSearch(this.value)"/>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto rounded-[24px] border border-gray-100 shadow-sm relative sticky-table-container mb-8">
            <table class="w-full text-base text-left border-collapse">
                <thead class="bg-[#2463B4] text-white">
                    <tr>
                        <th class="px-6 py-5 font-bold text-center border-r border-white/10 w-16">No</th>
                        <th class="px-6 py-5 font-bold border-r border-white/10">Merchant Name</th>
                        <th class="px-6 py-5 font-bold border-r border-white/10">Owner Name</th>
                        <th class="px-6 py-5 font-bold border-r border-white/10">Sales Code</th>
                        <th class="px-6 py-5 font-bold">Sales Name</th>
                    </tr>
                </thead>
                <tbody id="breakdownTableBody" class="divide-y divide-gray-50">
                    <!-- Data loaded via AJAX -->
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                                <p class="text-gray-400 font-medium">Loading data breakdown...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 px-2">
            <div id="breakdownShowingInfo" class="text-sm text-gray-500 font-medium font-inter">Showing 0 to 0 of 0</div>
            <div class="flex items-center gap-1" id="breakdownPaginationControls"></div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-400 font-medium">Show</span>
                <div class="relative">
                    <select id="breakdownRowsPerPage" onchange="handleBreakdownRowsPerPageChange(this.value)" class="appearance-none border border-gray-100 rounded-full pl-5 pr-12 py-2 text-sm font-bold text-gray-700 outline-none focus:ring-4 focus:ring-blue-50 bg-white shadow-sm cursor-pointer transition-all hover:border-gray-300">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    window.openBreakdown = function(status, part, title) {
        const modal = document.getElementById('modalBreakdown');
        if (!modal) return;

        // Set Params
        window.breakdownActiveParams = {
            nik: window.activeActionData?.nik,
            position: window.activeActionData?.position,
            status: status,
            part: part
        };

        // UI Reset
        document.getElementById('modalBreakdownTitle').innerText = title;
        document.getElementById('breakdownSearchInput').value = "";
        window.breakdownSearch = "";
        
        // Open
        modal.classList.remove('hidden');
        
        // Load
        window.loadBreakdownTable(1);
    };

    // State for breakdown modal
    window.breakdownCurrentPage = 1;
    window.breakdownRowsPerPage = 10;
    window.breakdownSearch = "";
    window.breakdownTotalRecords = 0;
    window.breakdownSearchTimeout = null;
    
    // Params for API
    window.breakdownActiveParams = null;

    window.closeModalBreakdown = function() {
        document.getElementById('modalBreakdown').classList.add('hidden');
        // Do NOT restore overflow yet because modal detail (L2) might still be open
    };

    window.debounceBreakdownSearch = function(val) {
        clearTimeout(window.breakdownSearchTimeout);
        window.breakdownSearchTimeout = setTimeout(() => {
            window.breakdownSearch = val;
            loadBreakdownTable(1);
        }, 500);
    };

    window.handleBreakdownRowsPerPageChange = function(val) {
        window.breakdownRowsPerPage = parseInt(val);
        loadBreakdownTable(1);
    };

    window.loadBreakdownTable = async function(page = 1) {
        window.breakdownCurrentPage = page;
        const tbody = document.getElementById('breakdownTableBody');
        
        // Loading State
        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-20 text-center"><div class="flex flex-col items-center gap-3"><div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div><p class="text-gray-400 font-medium">Loading data breakdown...</p></div></td></tr>`;

        try {
            const response = await fetch(window.SITE_URL + 'application_input/get_breakdown_data', {
                method: 'POST',
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({
                    nik: window.breakdownActiveParams.nik,
                    position: window.breakdownActiveParams.position,
                    status: window.breakdownActiveParams.status,
                    part: window.breakdownActiveParams.part,
                    date_from: window.selectedFrom,
                    date_to: window.selectedTo,
                    source: window.selectedSource,
                    search: window.breakdownSearch,
                    start: (window.breakdownCurrentPage - 1) * window.breakdownRowsPerPage,
                    length: window.breakdownRowsPerPage
                })
            });

            const res = await response.json();
            window.breakdownTotalRecords = res.recordsFiltered;
            
            if (!res.data || res.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">No data found</td></tr>`;
            } else {
                tbody.innerHTML = res.data.map(row => {
                    return `
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-5 text-center text-gray-400 font-medium border-r border-gray-50/50">${row[0]}</td>
                            <td class="px-6 py-5 font-bold text-[#1E293B] border-r border-gray-50/50">${row[1]}</td>
                            <td class="px-6 py-5 font-medium text-gray-600 border-r border-gray-50/50">${row[2]}</td>
                            <td class="px-6 py-5 text-[#1E5BA8] font-bold border-r border-gray-50/50">${row[3]}</td>
                            <td class="px-6 py-5 font-medium text-gray-600">${row[4]}</td>
                        </tr>
                    `;
                }).join("");
            }

            renderBreakdownPagination();
            if (window.lucide) window.lucide.createIcons();
        } catch (err) {
            console.error('Breakdown error:', err);
            tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-16 text-center text-red-500 font-medium">Error loading breakdown data</td></tr>`;
        }
    };

    function renderBreakdownPagination() {
        const totalPages = Math.ceil(window.breakdownTotalRecords / window.breakdownRowsPerPage);
        const container = document.getElementById("breakdownPaginationControls");
        const startIdx = window.breakdownTotalRecords === 0 ? 0 : (window.breakdownCurrentPage - 1) * window.breakdownRowsPerPage + 1;
        const endIdx = Math.min(window.breakdownCurrentPage * window.breakdownRowsPerPage, window.breakdownTotalRecords);
        
        document.getElementById("breakdownShowingInfo").innerText = `Showing ${startIdx} to ${endIdx} of ${window.breakdownTotalRecords}`;

        if (totalPages === 0) {
            container.innerHTML = "";
            return;
        }

        let html = `
          <button onclick="loadBreakdownTable(${window.breakdownCurrentPage - 1})" class="p-2 text-gray-300 hover:text-gray-600 disabled:opacity-20" ${window.breakdownCurrentPage === 1 ? 'disabled' : ''}>
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
          </button>
        `;

        const maxVisible = 5;
        let startPage = Math.max(1, window.breakdownCurrentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        if (endPage - startPage + 1 < maxVisible) startPage = Math.max(1, endPage - maxVisible + 1);

        for (let i = startPage; i <= endPage; i++) {
            html += `
                <button onclick="loadBreakdownTable(${i})" class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all ${i === window.breakdownCurrentPage ? 'bg-blue-50 text-[#1E5BA8] ring-1 ring-blue-100 shadow-sm' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-50'}">
                    ${i}
                </button>
            `;
        }

        html += `
          <button onclick="loadBreakdownTable(${window.breakdownCurrentPage + 1})" class="p-2 text-gray-300 hover:text-gray-600 disabled:opacity-20" ${window.breakdownCurrentPage === totalPages ? 'disabled' : ''}>
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
          </button>
        `;

        container.innerHTML = html;
        if (window.lucide) window.lucide.createIcons();
    }
</script>
