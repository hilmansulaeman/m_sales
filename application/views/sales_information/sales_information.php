<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sales Information - M-Sales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>window.SITE_URL = "<?= site_url(); ?>/";</script>    <script src="<?= base_url('assets/js/layout.js') ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <style>
        body {
            font-family: "Inter", sans-serif;
        }
    </style>
</head>

<body>
    <div id="app"></div>

    <script>
        initLayout("Sales Information");

        const appContainer = document.querySelector("#app > div > div");
        const main = document.createElement("div");
        main.className = "flex-1 bg-gray-50 flex flex-col";

        main.innerHTML = `
            

            <!-- Content -->
            <div class="flex-1 p-6">
                <div class="bg-white rounded-lg shadow-sm">
                    <!-- Search and Filter Bar -->
                    <div class="p-4 border-b border-gray-200 flex items-center gap-4">
                        <div class="relative flex-1 max-w-md">
                             <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                            <input
                                type="text"
                                placeholder="Search"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div class="relative">
                            <button id="filterBtn" onclick="toggleFilterMenu()" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i data-lucide="filter" class="w-4 h-4"></i>
                                Filter
                            </button>

                            <!-- Filter Dropdown -->
                            <div id="filterMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden transform transition-all duration-200 origin-top-right">
                                <div class="p-4 border-b border-gray-100">
                                    <h3 class="text-gray-500 text-sm">Filter by</h3>
                                </div>
                                <div class="max-h-[400px] overflow-y-auto">
                                    <!-- Product Section -->
                                    <div class="p-4 border-b border-gray-100">
                                        <div class="flex items-center justify-between mb-3">
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] font-medium text-sm">Product</span>
                                            </label>
                                            <button onclick="toggleFilterSection('product')" class="focus:outline-none p-1 hover:bg-gray-100 rounded-full transition-colors">
                                                <i id="chevron-product" data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-200"></i>
                                            </button>
                                        </div>
                                        <div id="filter-product" class="pl-7 space-y-3 hidden">
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">CC Reguler</span>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">CC Corporate</span>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">Merchant</span>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">PEMOL</span>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">Personal Loan</span>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">Smart Cash</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Channel Section -->
                                    <div class="p-4 border-b border-gray-100">
                                        <div class="flex items-center justify-between mb-3">
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] font-medium text-sm">Channel</span>
                                            </label>
                                            <button onclick="toggleFilterSection('channel')" class="focus:outline-none p-1 hover:bg-gray-100 rounded-full transition-colors">
                                                <i id="chevron-channel" data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-200"></i>
                                            </button>
                                        </div>
                                        <div id="filter-channel" class="pl-7 space-y-3 hidden">
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">Exhibition</span>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">PEMOL</span>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">Sales Merchant</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Level Section -->
                                    <div class="p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] font-medium text-sm">Level</span>
                                            </label>
                                            <button onclick="toggleFilterSection('level')" class="focus:outline-none p-1 hover:bg-gray-100 rounded-full transition-colors">
                                                <i id="chevron-level" data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-200"></i>
                                            </button>
                                        </div>
                                        <div id="filter-level" class="pl-7 space-y-3 hidden">
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">Junior</span>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">Mobile Sales</span>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">Sales Merchant</span>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" class="w-4 h-4 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                                <span class="text-[#20406b] text-sm">Senior</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 border-t border-gray-200">
                                    <button class="text-[#3B6EC2] text-sm font-medium hover:underline w-full text-left">Remove Filter</button>
                                </div>
                            </div>
                        </div>
                        <div class="relative">
                            <button id="sortBtn" onclick="toggleSortMenu()" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i data-lucide="arrow-up-down" class="w-4 h-4"></i>
                                Sort
                            </button>

                            <!-- Sort Dropdown -->
                            <div id="sortMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden transform transition-all duration-200 origin-top-right">
                                <div class="p-5">
                                    <h3 class="text-gray-500 text-lg mb-4">Sort by</h3>
                                    <div class="space-y-4">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="w-5 h-5 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                            <span class="text-[#20406b] font-medium text-base">Active Status</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="w-5 h-5 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                            <span class="text-[#20406b] font-medium text-base">Terminate Status</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="w-5 h-5 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                            <span class="text-[#20406b] font-medium text-base">Newest First</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" class="w-5 h-5 border-2 border-gray-300 rounded text-[#3B6EC2] focus:ring-0 cursor-pointer accent-[#3B6EC2]">
                                            <span class="text-[#20406b] font-medium text-base">Oldest First</span>
                                        </label>
                                    </div>
                                    <div class="h-px bg-gray-200 my-4"></div>
                                    <button class="text-[#3B6EC2] font-semibold text-lg hover:underline w-full text-left">Remove Sort</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-[#3B6EC2] text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">No</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Photo</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Sales Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Sales Code</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Branch</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Position</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Product</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Channel</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Level</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">SPV Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" class="divide-y divide-gray-200">
                                <!-- Populated via JS -->
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

        const salesData = [
            {
                id: 1,
                photo:
                    "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop",
                salesName: "Joice Aprila Dharmawansyah",
                salesCode: "KT135848",
                branch: "Yogyakarta",
                position: "ASM",
                product: "Sales Merchant",
                channel: "Sales Merchant",
                level: "Sales Merchant",
                spvName: "Faqih Chandra Rusidi",
                status: "Active",
            },
            {
                id: 2,
                photo:
                    "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop",
                salesName: "Tjahyono Herlambang",
                salesCode: "KT135848",
                branch: "Palembang",
                position: "ASM",
                product: "Credit Card",
                channel: "Telemarketing",
                level: "Mobile Sales",
                spvName: "Rian Hardiyanto",
                status: "Terminate",
            },
        ];


        let currentPage = 1;
        let rowsPerPage = 10;

        function renderTable() {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedItems = salesData.slice(start, end);
            const tbody = document.getElementById("tableBody");

            if (paginatedItems.length === 0) {
                tbody.innerHTML = '<tr><td colspan="11" class="text-center py-4 text-gray-500">No data found</td></tr>';
                return;
            }

            tbody.innerHTML = paginatedItems.map((sales, index) => `
            <tr class="hover:bg-gray-50 border-b border-gray-100">
                <td class="px-4 py-3 text-sm text-gray-700">${start + index + 1}</td>
                <td class="px-4 py-3">
                    <img src="${sales.photo}" alt="${sales.salesName}" class="w-10 h-10 rounded-full object-cover"/>
                </td>
                <td class="px-4 py-3 text-sm text-gray-700">${sales.salesName}</td>
                <td class="px-4 py-3 text-sm text-gray-700">${sales.salesCode}</td>
                <td class="px-4 py-3 text-sm text-gray-700">${sales.branch}</td>
                <td class="px-4 py-3 text-sm text-gray-700">${sales.position}</td>
                <td class="px-4 py-3 text-sm text-gray-700">${sales.product}</td>
                <td class="px-4 py-3 text-sm text-gray-700">${sales.channel}</td>
                <td class="px-4 py-3 text-sm text-gray-700">${sales.level}</td>
                <td class="px-4 py-3 text-sm text-gray-700">${sales.spvName}</td>
                <td class="px-4 py-3">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium ${sales.status === "Active" ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700"}">
                        ${sales.status}
                    </span>
                </td>
            </tr>
            `).join("");
        }

        function renderPagination() {
            const totalPages = Math.ceil(salesData.length / rowsPerPage);
            const showingInfo = document.getElementById("showingInfo");
            const paginationControls = document.getElementById("paginationControls");

            const start = salesData.length === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
            const end = Math.min(currentPage * rowsPerPage, salesData.length);
            showingInfo.textContent = `Showing ${start} to ${end} of ${salesData.length}`;

            let controlsHtml = `
                <button onclick="changePage(${currentPage - 1})" class="p-1 hover:text-gray-700 text-gray-400 transition-colors ${currentPage === 1 ? "opacity-50 cursor-not-allowed" : ""}" ${currentPage === 1 ? "disabled" : ""}>
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
                <button onclick="changePage(${currentPage + 1})" class="p-1 hover:text-gray-700 text-gray-400 transition-colors ${currentPage === totalPages || totalPages === 0 ? "opacity-50 cursor-not-allowed" : ""}" ${currentPage === totalPages || totalPages === 0 ? "disabled" : ""}>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            `;

            paginationControls.innerHTML = controlsHtml;
            lucide.createIcons();
        }

        window.changePage = function (page) {
            const totalPages = Math.ceil(salesData.length / rowsPerPage);
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

        // Filter Menu Logic
        window.toggleFilterMenu = function () {
            const menu = document.getElementById("filterMenu");
            menu.classList.toggle("hidden");
        };

        // Close filter menu when clicking outside
        document.addEventListener("click", function (event) {
            const menu = document.getElementById("filterMenu");
            const button = document.getElementById("filterBtn");
            const isClickInside =
                menu.contains(event.target) || button.contains(event.target);

            if (!menu.classList.contains("hidden") && !isClickInside) {
                menu.classList.add("hidden");
            }
        });
        // Filter Section Toggle Logic
        window.toggleFilterSection = function (id) {
            const list = document.getElementById(`filter-${id}`);
            const chevron = document.getElementById(`chevron-${id}`);

            if (list.classList.contains("hidden")) {
                list.classList.remove("hidden");
                chevron.style.transform = "rotate(180deg)";
            } else {
                list.classList.add("hidden");
                chevron.style.transform = "rotate(0deg)";
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
            const button = document.getElementById("sortBtn");
            const isClickInside =
                menu.contains(event.target) || button.contains(event.target);

            if (!menu.classList.contains("hidden") && !isClickInside) {
                menu.classList.add("hidden");
            }
        });
    </script>
</body>

</html>