<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Team Performance - M-Sales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>window.SITE_URL = "<?= site_url(); ?>/";</script>    <script src="<?= base_url('assets/js/layout.js') ?>"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      body {
        font-family: "Inter", sans-serif;
      }
    </style>
  </head>

  <body>
    <div id="app"></div>

    <script>
      initLayout("Team Performance");

      const appContainer = document.querySelector("#app > div > div");
      const main = document.createElement("div");
      main.className = "flex-1 bg-gray-50 flex flex-col";

      main.innerHTML = `
           

            <!-- Blue Banner -->
            <div class="bg-[#3B6EC2] px-6 py-8">
                <div class="max-w-4xl mx-auto text-center">
                    <p class="text-white text-lg mb-4">
                        To check sales performance, press Select Sales, then tap View to see the data
                    </p>
                    <button 
                        id="toggleSalesBtn"
                        onclick="toggleSales()"
                        class="bg-white text-[#3B6EC2] px-8 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors"
                    >
                        Select Sales
                    </button>
                </div>
            </div>

            <!-- Content Area - Initially Hidden or Empty -->
            <div id="contentArea" class="flex-1 bg-[#F5F7FA] hidden">
                 <div class="p-6">
                     <!-- Search and Sort Bar -->
                    <div class="mb-6 flex flex-col md:flex-row items-center gap-4">
                        <div class="relative flex-1 w-full">
                            <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                            <input
                                type="text"
                                id="searchRSM"
                                placeholder="Search"
                                onkeyup="filterRSM()"
                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm text-gray-600 placeholder-gray-400"
                            />
                        </div>
                        <div class="relative w-full md:w-auto">
                            <button id="sortButton" onclick="toggleSortMenu()" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-white border border-gray-200 rounded-full text-sm text-gray-600 hover:bg-gray-50 transition-colors shadow-sm w-full md:w-auto">
                                <i data-lucide="arrow-up-down" class="w-4 h-4"></i>
                                Sort
                            </button>

                            <!-- Sort Dropdown -->
                            <div id="sortMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden transform transition-all duration-200 origin-top-right">
                                <div class="p-4">
                                    <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-3">Sort by</h3>
                                    <div class="space-y-3">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="radio" name="sort" class="w-4 h-4 text-[#1E5BA8] focus:ring-[#1E5BA8] border-gray-300">
                                            <span class="text-gray-700 text-sm group-hover:text-[#1E5BA8] transition-colors">Ascending</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="radio" name="sort" class="w-4 h-4 text-[#1E5BA8] focus:ring-[#1E5BA8] border-gray-300">
                                            <span class="text-gray-700 text-sm group-hover:text-[#1E5BA8] transition-colors">Descending</span>
                                        </label>
                                    </div>
                                    <div class="h-px bg-gray-100 my-3"></div>
                                    <button class="text-[#1E5BA8] text-sm font-medium hover:underline w-full text-left">Reset Sort</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- RSM Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start" id="rsmGrid">
                            <!-- Populated via JS -->
                    </div>
                 </div>
            </div>
             
             <!-- Empty State for spacing -->
             <div id="emptyState" class="flex-1 bg-gray-50"></div>

             <!-- Performance Modal -->
             <div id="performanceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden backdrop-blur-sm transition-opacity duration-300 p-4">
                 <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col overflow-hidden relative transform transition-all scale-100">
                    <div class="p-4 md:p-6 pb-2 flex justify-between items-start shrink-0">
                         <h2 id="modalTitle" class="text-xl font-bold text-[#20406b]"></h2>
                         <button onclick="closePerformanceModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                         </button>
                    </div>

                    <div class="px-4 md:px-6 py-2 mb-6 overflow-y-auto custom-scrollbar">
                         <div class="border border-gray-200 rounded-xl p-4 md:p-6 shadow-sm">
                             <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
                                <span class="text-[#20406b] font-bold text-lg">Period</span>
                                <div class="relative w-full sm:w-auto">
                                    <select class="w-full sm:w-auto appearance-none bg-white border border-gray-200 text-gray-700 py-2 pl-4 pr-10 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm cursor-pointer">
                                        <option>September 2025</option>
                                        <option>August 2025</option>
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
    
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="w-full text-sm text-center border-collapse">
                                    <thead>
                                        <tr class="bg-[#1E5BBC] text-white">
                                             <th class="py-4 px-4 text-left font-medium min-w-[200px] whitespace-nowrap"></th>
                                             <th class="py-4 px-4 font-medium whitespace-nowrap border-l border-blue-400">Credit Card (CC)</th>
                                             <th class="py-4 px-4 font-medium whitespace-nowrap border-l border-blue-400">EDC</th>
                                             <th class="py-4 px-4 font-medium whitespace-nowrap border-l border-blue-400">Smart Cash (SC)</th>
                                             <th class="py-4 px-4 font-medium whitespace-nowrap border-l border-blue-400">Personal Loan (PL)</th>
                                             <th class="py-4 px-4 font-medium whitespace-nowrap border-l border-blue-400">Corporate</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modalTableBody" class="text-gray-600 divide-y divide-gray-100 bg-white">
                                        <!-- Rows generated by JS -->
                                    </tbody>
                                </table>
                             </div>
                         </div>
                    </div>
                 </div>
             </div>
        `;

      appContainer.appendChild(main);

      const rsmData = [
        { id: 1, name: "Ahmad Fadli", totalBSH: 15, achieved: 12 },
        { id: 2, name: "Budi Santoso", totalBSH: 18, achieved: 14 },
        { id: 3, name: "Citra Dewi", totalBSH: 12, achieved: 10 },
        { id: 4, name: "Denny Kurniawan", totalBSH: 20, achieved: 16 },
        { id: 5, name: "Eka Putri", totalBSH: 16, achieved: 13 },
        { id: 6, name: "Fahmi Rahman", totalBSH: 14, achieved: 11 },
      ];

      function renderRSM(data) {
        const grid = document.getElementById("rsmGrid");
        grid.innerHTML = data
          .map(
            (rsm) => `
                <div class="bg-[#F8fafc] rounded-xl overflow-hidden shadow-sm transition-all duration-300 flex flex-col gap-3">
                    <!-- Blue Header Card -->
                    <div class="bg-[#1E5BA8] rounded-xl p-4 flex items-center justify-between shadow-sm">
                        <h3 class="text-sm font-semibold text-white">RSM ${rsm.name}</h3>
                        <button onclick="openPerformanceModal('${rsm.name}')" class="bg-white text-[#1E5BA8] px-4 py-1 rounded-full text-xs font-bold hover:bg-gray-50 transition-colors shadow-sm">View</button>
                    </div>

                    <!-- Input-like ASM Choice Trigger -->
                     <button onclick="toggleASM(${rsm.id})" class="bg-white border border-gray-200 w-full px-4 py-3 rounded-xl text-sm flex items-center justify-between focus:outline-none hover:border-blue-400 transition-colors shadow-sm group">
                        <span class="text-gray-400 font-normal">Choose ASM</span>
                        <i data-lucide="chevron-down" id="chevron-${rsm.id}" class="w-4 h-4 text-[#1E5BA8] transition-transform duration-300 group-hover:text-blue-600"></i>
                    </button>
                    
                    <!-- ASM List Content -->
                    <div id="asm-list-${rsm.id}" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300">
                        <div class="px-4 py-3 border-b border-gray-100">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">List ASM</h4>
                        </div>
                        <div class="divide-y divide-gray-50">
                                <!-- Dummy ASMs -->
                                <div class="px-4 py-3 flex justify-between items-center hover:bg-blue-50/50 group transition-colors cursor-pointer">
                                    <span class="text-xs font-semibold text-gray-700 group-hover:text-[#1E5BA8]">Achmad Reynaldi Mahendra</span>
                                    <button class="text-xs font-bold text-[#1E5BA8] hover:underline">View</button>
                                </div>
                                <div class="px-4 py-3 flex justify-between items-center hover:bg-blue-50/50 group transition-colors cursor-pointer">
                                    <span class="text-xs font-semibold text-gray-700 group-hover:text-[#1E5BA8]">Aji Kornando</span>
                                    <button class="text-xs font-bold text-[#1E5BA8] hover:underline">View</button>
                                </div>
                                <div class="px-4 py-3 flex justify-between items-center hover:bg-blue-50/50 group transition-colors cursor-pointer">
                                    <span class="text-xs font-semibold text-gray-700 group-hover:text-[#1E5BA8]">Dandi Mulya Dian Saputra</span>
                                    <button class="text-xs font-bold text-[#1E5BA8] hover:underline">View</button>
                                </div>
                        </div>
                    </div>
                </div>
             `
          )
          .join("");
        lucide.createIcons();
      }

      window.toggleASM = function (id) {
        const list = document.getElementById(`asm-list-${id}`);
        const chevron = document.getElementById(`chevron-${id}`);

        if (list.classList.contains("hidden")) {
          list.classList.remove("hidden");
          chevron.style.transform = "rotate(180deg)";
        } else {
          list.classList.add("hidden");
          chevron.style.transform = "rotate(0deg)";
        }
      };

      renderRSM(rsmData);

      // Filter Logic
      window.filterRSM = function () {
        const query = document.getElementById("searchRSM").value.toLowerCase();
        const filtered = rsmData.filter((rsm) =>
          rsm.name.toLowerCase().includes(query)
        );
        renderRSM(filtered);
      };

      // Modal Logic
      window.openPerformanceModal = function (rsmName) {
        const modal = document.getElementById("performanceModal");
        const title = document.getElementById("modalTitle");
        const tbody = document.getElementById("modalTableBody");

        title.textContent = `RSM ${rsmName}`;

        // Generate Dummy Data matching the image
        const rows = [
          "Application Input",
          "Incoming",
          "Approved",
          "Cancel",
          "Decline / Reject",
          "App Rate",
          "Book Rate",
          "Run Rate INC",
          "Run Rate APP",
        ];

        tbody.innerHTML = rows
          .map((row, index) => {
            const isRate = row.includes("Rate");
            // Create dummy data that looks intentional (1, 2, 3, 4, 5) per column
            const getVal = (colIndex) => {
              return isRate ? `${colIndex}%` : colIndex;
            };

            return `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 text-left font-medium text-gray-700">${row}</td>
                        <td class="py-4 px-4">${getVal(1)}</td>
                        <td class="py-4 px-4">${getVal(2)}</td>
                        <td class="py-4 px-4">${getVal(3)}</td>
                        <td class="py-4 px-4">${getVal(4)}</td>
                        <td class="py-4 px-4">${getVal(5)}</td>
                    </tr>
                `;
          })
          .join("");

        modal.classList.remove("hidden");
        lucide.createIcons();
      };

      window.closePerformanceModal = function () {
        document.getElementById("performanceModal").classList.add("hidden");
      };

      // Toggle Logic
      window.toggleSales = function () {
        const content = document.getElementById("contentArea");
        const empty = document.getElementById("emptyState");
        const btn = document.getElementById("toggleSalesBtn");
        const isHidden = content.classList.contains("hidden");

        if (isHidden) {
          content.classList.remove("hidden");
          empty.classList.add("hidden");
          btn.innerText = "Hide Sales";
        } else {
          content.classList.add("hidden");
          empty.classList.remove("hidden");
          btn.innerText = "Select Sales";
        }
      };

      // Sort Menu Logic
      window.toggleSortMenu = function () {
        const menu = document.getElementById("sortMenu");
        menu.classList.toggle("hidden");
      };

      // Close sort menu when clicking outside
      document.addEventListener("click", function (event) {
        const menu = document.getElementById("sortMenu");
        const button = document.getElementById("sortButton");

        if (
          !menu.classList.contains("hidden") &&
          !menu.contains(event.target) &&
          !button.contains(event.target)
        ) {
          menu.classList.add("hidden");
        }
      });

      lucide.createIcons();
    </script>
  </body>
</html>
