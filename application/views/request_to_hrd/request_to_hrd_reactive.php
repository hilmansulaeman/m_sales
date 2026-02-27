<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reactive - M-Sales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>window.SITE_URL = "<?= site_url(); ?>/";</script>    <script src="<?= base_url('assets/js/layout.js') ?>"></script>
    <link href="https://fonts.cdnfonts.com/css/tiktok-sans" rel="stylesheet" />
    <style>
      body {
        font-family: "TikTok Sans", sans-serif;
      }
    </style>
  </head>

  <body>
    <div id="app"></div>

    <script>
      initLayout("Request to HRD", "Reaktif");

      const appContainer = document.querySelector("#app > div > div");
      const main = document.createElement("div");
      main.className = "flex-1 bg-gray-50 flex flex-col";

      main.innerHTML = `
    

            <!-- Content -->
            <div class="flex-1 p-6">
                <!-- Data Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    
                    <!-- Tools Bar -->
                    <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3 w-full md:w-auto flex-1">
                            <!-- Search -->
                            <div class="relative w-full md:w-64">
                                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                                <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-full text-sm focus:outline-none focus:border-blue-500 transition-colors">
                            </div>
                            
                            <!-- Date Range -->
                            <div class="relative w-full md:w-48 hidden md:block">
                                <i data-lucide="calendar" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                                <input type="text" placeholder="Select date range" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-full text-sm focus:outline-none focus:border-blue-500 transition-colors bg-white cursor-pointer" readonly>
                            </div>
                        </div>

                        <!-- Add Data Button -->
                        <button class="flex items-center gap-2 px-4 py-2 bg-[#1E5BBC] text-white rounded-full text-sm font-medium hover:bg-blue-700 transition-colors">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            <span>Add Data</span>
                        </button>
                    </div>

                    <!-- Table Container -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-center border-collapse">
                            <thead>
                                <tr class="bg-[#1E5BBC] text-white">
                                    <th class="px-4 py-3 font-semibold border-r border-blue-400 w-16">No</th>
                                    <th class="px-4 py-3 font-semibold border-r border-blue-400">Effective Date</th>
                                    <th class="px-4 py-3 font-semibold border-r border-blue-400">Date Created</th>
                                    <th class="px-4 py-3 font-semibold border-r border-blue-400">Created By</th>
                                    <th class="px-4 py-3 font-semibold border-r border-blue-400">Data Amount</th>
                                    <th class="px-4 py-3 font-semibold border-r border-blue-400">Complete</th>
                                    <th class="px-4 py-3 font-semibold border-r border-blue-400">Returned</th>
                                    <th class="px-4 py-3 font-semibold border-r border-blue-400">Rejected</th>
                                    <th class="px-4 py-3 font-semibold border-r border-blue-400">Cancelled</th>
                                    <th class="px-4 py-3 font-semibold">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white text-gray-700">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white text-sm text-gray-500 w-full">
                        <div id="showingInfo">Showing 0 to 0 of 0</div>
                        
                        <div class="flex items-center gap-2" id="paginationControls">
                            <!-- Populated by JS -->
                        </div>

                        <div class="flex items-center gap-2">
                            <span>Show</span>
                             <div class="relative">
                                 <select id="rowsPerPageSelect" onchange="handleRowsPerPageChange(this.value)" class="appearance-none border border-gray-200 rounded-lg pl-3 pr-8 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 w-3 h-3 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

      appContainer.appendChild(main);

      // Data & Pagination Logic
      const tableData = [
        {
          id: 1,
          date: "-",
          created: "-",
          by: "-",
          amount: "-",
          complete: "-",
          returned: "-",
          rejected: "-",
          cancelled: "-",
          detail: "-",
        },
        // Mock data
        {
          id: 2,
          date: "2023-10-01",
          created: "2023-09-25",
          by: "Admin A",
          amount: "10",
          complete: "8",
          returned: "0",
          rejected: "1",
          cancelled: "1",
          detail: "View",
        },
        {
          id: 3,
          date: "2023-10-05",
          created: "2023-09-28",
          by: "Admin B",
          amount: "5",
          complete: "5",
          returned: "0",
          rejected: "0",
          cancelled: "0",
          detail: "View",
        },
      ];

      let currentPage = 1;
      let rowsPerPage = 10;

      function renderTable() {
        const tbody = document.querySelector("table tbody");
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const paginatedItems = tableData.slice(start, end);

        if (paginatedItems.length === 0) {
          tbody.innerHTML =
            '<tr><td colspan="10" class="text-center py-4 text-gray-500">No data found</td></tr>';
          return;
        }

        tbody.innerHTML = paginatedItems
          .map(
            (item, index) => `
                <tr class="hover:bg-gray-50 border-b border-gray-100">
                    <td class="px-4 py-4">${start + index + 1}</td>
                    <td class="px-4 py-4 text-gray-900">${item.date}</td>
                    <td class="px-4 py-4 text-gray-900">${item.created}</td>
                    <td class="px-4 py-4 text-gray-900">${item.by}</td>
                    <td class="px-4 py-4 text-gray-900">${item.amount}</td>
                    <td class="px-4 py-4 text-gray-900">${item.complete}</td>
                    <td class="px-4 py-4 text-gray-900">${item.returned}</td>
                    <td class="px-4 py-4 text-gray-900">${item.rejected}</td>
                    <td class="px-4 py-4 text-gray-900">${item.cancelled}</td>
                    <td class="px-4 py-4 text-gray-900">${item.detail}</td>
                </tr>
            `
          )
          .join("");
      }

      function renderPagination() {
        const totalPages = Math.ceil(tableData.length / rowsPerPage);
        const showingInfo = document.getElementById("showingInfo");
        const paginationControls =
          document.getElementById("paginationControls");

        const start =
          tableData.length === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
        const end = Math.min(currentPage * rowsPerPage, tableData.length);
        showingInfo.textContent = `Showing ${start} to ${end} of ${tableData.length}`;

        let controlsHtml = `
                <button onclick="changePage(${
                  currentPage - 1
                })" class="p-1 hover:text-gray-700 text-gray-400 transition-colors ${
          currentPage === 1 ? "opacity-50 cursor-not-allowed" : ""
        }" ${currentPage === 1 ? "disabled" : ""}>
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
            `;

        for (let i = 1; i <= totalPages; i++) {
          if (i === currentPage) {
            controlsHtml += `<span class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 font-medium rounded-lg">${i}</span>`;
          } else {
            controlsHtml += `<button onclick="changePage(${i})" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 text-gray-600 rounded-lg transition-colors">${i}</button>`;
          }
        }

        controlsHtml += `
                <button onclick="changePage(${
                  currentPage + 1
                })" class="p-1 hover:text-gray-700 text-gray-400 transition-colors ${
          currentPage === totalPages || totalPages === 0
            ? "opacity-50 cursor-not-allowed"
            : ""
        }" ${currentPage === totalPages || totalPages === 0 ? "disabled" : ""}>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            `;

        paginationControls.innerHTML = controlsHtml;
        lucide.createIcons();
      }

      window.changePage = function (page) {
        const totalPages = Math.ceil(tableData.length / rowsPerPage);
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        renderTable();
        renderPagination();
      };

      window.handleRowsPerPageChange = function (value) {
        rowsPerPage = parseInt(value);
        currentPage = 1;
        renderTable();
        renderPagination();
      };

      // Initial Render
      renderTable();
      renderPagination();
      lucide.createIcons();
    </script>
  </body>
</html>
