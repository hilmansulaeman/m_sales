<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Candidate Details - M-Sales</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>window.SITE_URL = "<?= site_url(); ?>/";</script>
  <script src="<?= base_url('assets/js/layout.js') ?>"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: "Inter", sans-serif;
    }
  </style>
</head>

<body>
  <div id="app"></div>

  <script>
    initLayout("Candidate Info"); // Set parent menu active

    const appContainer = document.querySelector("#app > div > div");
    const main = document.createElement("div");
    main.className = "flex-1 bg-gray-50 flex flex-col";

    main.innerHTML = `
            

            <!-- Content -->
            <div class="flex-1 p-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Search and Action Bar -->
                    <div class="p-4 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="relative w-full md:w-80">
                            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                            <input
                                type="text"
                                placeholder="Search"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <button onclick="openInputDataModal()" class="flex items-center gap-2 px-4 py-2 bg-[#1E5BBC] text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                            <i data-lucide="file-plus" class="w-4 h-4"></i>
                            Input Data
                        </button>
                    </div>

                    <!-- Table Container -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead>
                                <tr class="bg-[#1E5BBC] text-white border-b border-blue-600">
                                    <th rowspan="2" class="px-4 py-3 font-semibold text-center border-r border-blue-400 w-16">No</th>
                                    <th colspan="2" class="px-4 py-3 font-semibold text-center border-r border-blue-400">Candidate</th>
                                    <th rowspan="2" class="px-4 py-3 font-semibold text-center border-r border-blue-400">Area</th>
                                    <th rowspan="2" class="px-4 py-3 font-semibold text-center border-r border-blue-400">Product</th>
                                    <th rowspan="2" class="px-4 py-3 font-semibold text-center border-r border-blue-400">Position</th>
                                    <th rowspan="2" class="px-4 py-3 font-semibold text-center border-r border-blue-400">Level</th>
                                    <th colspan="2" class="px-4 py-3 font-semibold text-center border-r border-blue-400">Upliner</th>
                                    <th rowspan="2" class="px-4 py-3 font-semibold text-center border-r border-blue-400">Status</th>
                                    <th rowspan="2" class="px-4 py-3 font-semibold text-center">Notes Return</th>
                                </tr>
                                <tr class="bg-[#1E5BBC] text-white">
                                    <th class="px-4 py-2 font-medium text-center border-r border-t border-blue-400 text-xs">Name</th>
                                    <th class="px-4 py-2 font-medium text-center border-r border-t border-blue-400 text-xs">ID</th>
                                    <th class="px-4 py-2 font-medium text-center border-r border-t border-blue-400 text-xs">Name</th>
                                    <th class="px-4 py-2 font-medium text-center border-r border-t border-blue-400 text-xs">NIK</th>
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

            <!-- Input Data Modal -->
            <div id="inputDataModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden backdrop-blur-sm p-4 transition-all duration-300 opacity-0">
                <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl transform scale-95 transition-all duration-300" id="modalContent">
                    <div class="flex items-center justify-between p-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-[#20406b]">Input data</h2>
                        <button onclick="closeInputDataModal()" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <div class="p-8 space-y-6">
                        <!-- Candidate ID -->
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-[#20406b]">Candidate ID</label>
                            <input
                                type="text"
                                placeholder="Add candidate ID"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400 bg-white"
                            >
                        </div>

                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-[#20406b]">Area<span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <button
                                        id="dropdownTrigger-area"
                                        onclick="toggleDropdown(event, 'area')"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <span id="selected-area">Select an area</span>
                                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div id="dropdown-area" class="hidden absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-lg border border-gray-100 z-50 overflow-hidden">
                                        <div class="p-2 border-b border-gray-100">
                                            <input
                                                type="text"
                                                id="search-area"
                                                oninput="filterList('area')"
                                                placeholder="Search area"
                                                class="w-full px-3 py-2 bg-gray-50 border border-transparent rounded-md text-sm focus:bg-white focus:border-gray-200 focus:outline-none focus:ring-0 placeholder-gray-400"
                                            >
                                        </div>
                                        <div class="max-h-60 overflow-y-auto custom-scrollbar" id="list-area">
                                            <!-- Areas populated by JS -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-[#20406b]">Product<span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <button
                                        id="dropdownTrigger-product"
                                        onclick="toggleDropdown(event, 'product')"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <span id="selected-product">Select product</span>
                                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div id="dropdown-product" class="hidden absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-lg border border-gray-100 z-50 overflow-hidden">
                                        <div class="p-2 border-b border-gray-100">
                                            <input
                                                type="text"
                                                id="search-product"
                                                oninput="filterList('product')"
                                                placeholder="Search product"
                                                class="w-full px-3 py-2 bg-gray-50 border border-transparent rounded-md text-sm focus:bg-white focus:border-gray-200 focus:outline-none focus:ring-0 placeholder-gray-400"
                                            >
                                        </div>
                                        <div class="max-h-60 overflow-y-auto custom-scrollbar" id="list-product">
                                            <!-- Items populated by JS -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-[#20406b]">Position<span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <button
                                        id="dropdownTrigger-position"
                                        onclick="toggleDropdown(event, 'position')"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <span id="selected-position">Select position</span>
                                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div id="dropdown-position" class="hidden absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-lg border border-gray-100 z-50 overflow-hidden">
                                        <div class="p-2 border-b border-gray-100">
                                            <input
                                                type="text"
                                                id="search-position"
                                                oninput="filterList('position')"
                                                placeholder="Search position"
                                                class="w-full px-3 py-2 bg-gray-50 border border-transparent rounded-md text-sm focus:bg-white focus:border-gray-200 focus:outline-none focus:ring-0 placeholder-gray-400"
                                            >
                                        </div>
                                        <div class="max-h-60 overflow-y-auto custom-scrollbar" id="list-position">
                                            <!-- Items populated by JS -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-[#20406b]">Level<span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <button
                                        id="dropdownTrigger-level"
                                        onclick="toggleDropdown(event, 'level')"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <span id="selected-level">Select level</span>
                                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div id="dropdown-level" class="hidden absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-lg border border-gray-100 z-50 overflow-hidden">
                                        <div class="p-2 border-b border-gray-100">
                                            <input
                                                type="text"
                                                id="search-level"
                                                oninput="filterList('level')"
                                                placeholder="Search level"
                                                class="w-full px-3 py-2 bg-gray-50 border border-transparent rounded-md text-sm focus:bg-white focus:border-gray-200 focus:outline-none focus:ring-0 placeholder-gray-400"
                                            >
                                        </div>
                                        <div class="max-h-60 overflow-y-auto custom-scrollbar" id="list-level">
                                            <!-- Items populated by JS -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upliner -->
                        <div class="space-y-1.5" id="upliner-section">
                             <!-- Default Single View -->
                             <div id="upliner-default-view">
                                 <label class="block text-sm font-semibold text-[#20406b]">Upliner<span class="text-red-500">*</span></label>
                                 <div class="relative">
                                    <button 
                                        id="dropdownTrigger-upliner"
                                        onclick="toggleDropdown(event, 'upliner')" 
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <span id="selected-upliner">Select upliner</span>
                                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                                    </button>
    
                                    <!-- Dropdown Menu -->
                                    <div id="dropdown-upliner" class="hidden absolute bottom-full left-0 right-0 mb-1 bg-white rounded-lg shadow-lg border border-gray-100 z-50">
                                        <div class="p-2 border-b border-gray-100">
                                            <input 
                                                type="text" 
                                                id="search-upliner"
                                                oninput="filterList('upliner')"
                                                placeholder="Search upliner" 
                                                class="w-full px-3 py-2 bg-gray-50 border border-transparent rounded-md text-sm focus:bg-white focus:border-gray-200 focus:outline-none focus:ring-0 placeholder-gray-400"
                                            >
                                        </div>
                                        <div class="max-h-60 overflow-y-auto custom-scrollbar" id="list-upliner">
                                            <!-- Items populated by JS -->
                                        </div>
                                    </div>
                                </div>
                             </div>

                             <!-- Detail View (Hidden) -->
                             <div id="upliner-detail-view" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- ASM Field -->
                                <div class="space-y-1.5 cursor-pointer" onclick="resetUpliner()">
                                     <label class="block text-sm font-semibold text-[#20406b]">ASM Name</label>
                                     <div class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-lg text-sm flex items-center gap-3">
                                         <span id="detail-asm-code" class="bg-gray-200 text-gray-600 px-2 py-0.5 rounded text-xs font-medium"></span>
                                         <span id="detail-asm-name" class="text-[#20406b] font-medium"></span>
                                     </div>
                                </div>
                                <!-- RSM Field -->
                                <div class="space-y-1.5 cursor-pointer" onclick="resetUpliner()">
                                     <label class="block text-sm font-semibold text-[#20406b]">RSM Name</label>
                                     <div class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-lg text-sm flex items-center gap-3">
                                         <span id="detail-rsm-code" class="bg-gray-200 text-gray-600 px-2 py-0.5 rounded text-xs font-medium"></span>
                                         <span id="detail-rsm-name" class="text-[#20406b] font-medium"></span>
                                     </div>
                                </div>
                             </div>
                        </div>

                        <!-- Spacing at bottom -->
                         <div class="pb-2"></div>
                    </div>
                </div>
            </div>
        `;

    appContainer.appendChild(main);

    // Data & Pagination Logic
    const tableData = [
      {
        id: 1,
        name: "Mandra Pradipta Cahyani",
        nik: "K1104148",
        area: "Tulungagung",
        product: "Sales Merchant",
        position: "Mobile Sales",
        level: "Sales Merchant",
        uplinerName: "Faqih Chandra Rusidi",
        uplinerNik: "K1139333",
        status: "Verify",
        statusClass: "bg-purple-100 text-purple-700 border-purple-200",
        notes: "Nyoba ngeretum lagi dari ASM"
      },
      {
        id: 2,
        name: "Asdiki",
        nik: "K1104148",
        area: "Tulungagung",
        product: "Credit Card",
        position: "Mobile Sales",
        level: "Junior",
        uplinerName: "Rian Maulana Saputra",
        uplinerNik: "K1139333",
        status: "Approved SPV",
        statusClass: "bg-blue-100 text-blue-700 border-blue-200",
        notes: "-"
      },
      // More mock data for pagination
      { id: 3, name: "Budi Santoso", nik: "K1122334", area: "Jakarta", product: "Credit Card", position: "Staff", level: "Middle", uplinerName: "Siti Aminah", uplinerNik: "K1122555", status: "Rejected", statusClass: "bg-red-100 text-red-700 border-red-200", notes: "Document incomplete" },
      { id: 4, name: "Citra Lestari", nik: "K1133445", area: "Bandung", product: "PEMOL", position: "Supervisor", level: "Senior", uplinerName: "Agus Salim", uplinerNik: "K1133666", status: "Verify", statusClass: "bg-purple-100 text-purple-700 border-purple-200", notes: "Pending review" },
      { id: 5, name: "Dewi Putri", nik: "K1144556", area: "Surabaya", product: "Merchant", position: "Manager", level: "Expert", uplinerName: "Joko Widodo", uplinerNik: "K1144777", status: "Approved SPV", statusClass: "bg-blue-100 text-blue-700 border-blue-200", notes: "-" },
      { id: 6, name: "Eko Prasetyo", nik: "K1155667", area: "Medan", product: "Personal Loan", position: "Staff", level: "Junior", uplinerName: "Rina Sari", uplinerNik: "K1155888", status: "Verify", statusClass: "bg-purple-100 text-purple-700 border-purple-200", notes: "Check references" },
    ];

    let currentPage = 1;
    let rowsPerPage = 10;

    function renderTable() {
      const tbody = document.querySelector("table tbody");
      const start = (currentPage - 1) * rowsPerPage;
      const end = start + rowsPerPage;
      const paginatedItems = tableData.slice(start, end);

      if (paginatedItems.length === 0) {
        tbody.innerHTML = '<tr><td colspan="11" class="text-center py-4 text-gray-500">No data found</td></tr>';
        return;
      }

      tbody.innerHTML = paginatedItems.map((item, index) => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-4 text-center">${start + index + 1}</td>
                <td class="px-4 py-4">${item.name}</td>
                <td class="px-4 py-4 text-center">${item.nik}</td>
                <td class="px-4 py-4">${item.area}</td>
                <td class="px-4 py-4">${item.product}</td>
                <td class="px-4 py-4">${item.position}</td>
                <td class="px-4 py-4">${item.level}</td>
                <td class="px-4 py-4">${item.uplinerName}</td>
                <td class="px-4 py-4 text-center">${item.uplinerNik}</td>
                <td class="px-4 py-4 text-center">
                    <span class="inline-block px-4 py-1 rounded-full text-xs font-medium border ${item.statusClass}">
                        ${item.status}
                    </span>
                </td>
                <td class="px-4 py-4 text-sm text-gray-500 w-48 truncate" title="${item.notes}">${item.notes}</td>
            </tr>
        `).join("");
    }

    function renderPagination() {
      const totalPages = Math.ceil(tableData.length / rowsPerPage);
      const showingInfo = document.getElementById("showingInfo");
      const paginationControls = document.getElementById("paginationControls");

      const start = tableData.length === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
      const end = Math.min(currentPage * rowsPerPage, tableData.length);
      showingInfo.textContent = `Showing ${start} to ${end} of ${tableData.length}`;

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

    // Modal Logic
    function openInputDataModal() {
      const modal = document.getElementById("inputDataModal");
      const content = document.getElementById("modalContent");

      document.body.style.overflow = "hidden"; // Lock body scroll
      modal.classList.remove("hidden");

      // Small delay to allow display block to apply before opacity transition
      setTimeout(() => {
        modal.classList.remove("opacity-0");
        content.classList.remove("scale-95");
        content.classList.add("scale-100");
      }, 10);
    }

    function closeInputDataModal() {
      const modal = document.getElementById("inputDataModal");
      const content = document.getElementById("modalContent");

      document.body.style.overflow = ""; // Restore body scroll
      modal.classList.add("opacity-0");
      content.classList.remove("scale-100");
      content.classList.add("scale-95");

      setTimeout(() => {
        modal.classList.add("hidden");
      }, 300); // Match transition duration
    }

    // Close on backdrop click
    document
      .getElementById("inputDataModal")
      .addEventListener("click", function (e) {
        if (e.target === this) {
          closeInputDataModal();
        }
      });
    // Generic Dropdown Logic
    const dropdownData = {
      area: [
        "Ambon",
        "Balikpapan",
        "Banda Aceh",
        "Bandung",
        "Banjarmasin",
        "Banyuwangi",
        "Batam",
        "Bekasi",
        "Bengkulu",
        "Bogor",
        "Cirebon",
        "Denpasar",
        "Depok",
        "Jakarta",
        "Jambi",
        "Jayapura",
        "Kediri",
        "Kendari",
        "Kupang",
        "Lampung",
        "Makassar",
        "Malang",
        "Manado",
        "Mataram",
        "Medan",
        "Padang",
        "Palembang",
        "Palu",
        "Pekanbaru",
        "Pontianak",
        "Samarinda",
        "Semarang",
        "Solo",
        "Surabaya",
        "Tangerang",
        "Tasikmalaya",
        "Yogyakarta",
      ],
      product: [
        "CC Reguler",
        "CC Corporate",
        "Merchant",
        "PEMOL",
        "Personal Loan",
        "Smart Cash",
      ],
      position: ["ASM", "Head", "Manager", "Staff", "Supervisor"],
      level: [
        "Junior",
        "Middle",
        "Senior",
        "Expert",
        "Mobile Sales",
        "Sales Merchant",
      ],
      upliner: [
        "RSM Ahmad",
        "RSM Asep Syaefuddin Nugraha",
        "RSM Asep Wiguna",
        "RSM Budi Setiawan",
        "RSM Bustanil Aripin",
        "RSM Catherine Brigitta",
        "RSM Dede Sudrajat",
      ],
    };

    const uplinerSubData = {
      "RSM Asep Syaefuddin Nugraha": [
        "RSM Asep Syaefuddin Nugraha",
        "ASM Arya Rangga Dwipayana",
        "ASM Asep Wiguna",
        "ASM Asih",
        "ASM Bayu Aji Pamungkas",
        "ASM Desy Anggasari",
        "ASM Dian Novita Sari",
      ],
    };

    const uplinerCodes = {
      "RSM Asep Syaefuddin Nugraha": "K1002059",
      "ASM Arya Rangga Dwipayana": "K1002060",
      "ASM Asep Wiguna": "K1002061",
      "ASM Asih": "K1002062",
      "ASM Bayu Aji Pamungkas": "K1002063",
      "ASM Desy Anggasari": "K1002064",
      "ASM Dian Novita Sari": "K1002065",
      "RSM Ahmad": "K1002066",
      "RSM Asep Wiguna": "K1002067",
      "RSM Budi Setiawan": "K1002068",
      "RSM Bustanil Aripin": "K1002069",
      "RSM Catherine Brigitta": "K1002070",
      "RSM Dede Sudrajat": "K1002071",
    };

    let currentUplinerMode = "main";

    function renderList(type, filterText = "") {
      const list = document.getElementById(`list-${type}`);
      if (!list) return;

      const data = dropdownData[type] || [];
      const filtered = data.filter((item) =>
        item.toLowerCase().includes(filterText.toLowerCase())
      );

      list.innerHTML = filtered
        .map((item, index) => {
          if (type === "upliner") {
            // Add ID for positioning sub-menu
            const itemId = `upliner-item-${index}`;
            return `
                    <div id="${itemId}" onclick="selectItem('${type}', '${item}', '${itemId}')" class="px-3 py-2 text-sm text-[#20406b] hover:bg-gray-50 cursor-pointer transition-colors flex justify-between items-center group relative">
                        <span>${item}</span>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 group-hover:text-[#20406b] transition-colors"></i>
                    </div>
                `;
          }
          return `
              <div onclick="selectItem('${type}', '${item}')" class="px-3 py-2 text-sm text-[#20406b] hover:bg-gray-50 cursor-pointer transition-colors">
                  ${item}
              </div>
            `;
        })
        .join("");

      if (type === "upliner") {
        lucide.createIcons();
      }
    }

    window.toggleDropdown = function (event, type) {
      event.stopPropagation();
      const dropdown = document.getElementById(`dropdown-${type}`);

      // Close all other dropdowns
      document.querySelectorAll('[id^="dropdown-"]').forEach((el) => {
        if (el.id !== `dropdown-${type}`) el.classList.add("hidden");
      });

      if (type === "upliner") {
        currentUplinerMode = "main";
        const searchInput = document.getElementById("search-upliner");
        if (searchInput) {
          searchInput.value = "";
          searchInput.placeholder = "Search upliner";
        }
      }

      // Remove any existing sub-dropdowns when toggling
      const existingSub = document.getElementById("upliner-sub-dropdown");
      if (existingSub) existingSub.remove();

      if (dropdown.classList.contains("hidden")) {
        renderList(type);
        dropdown.classList.remove("hidden");
      } else {
        dropdown.classList.add("hidden");
        // Also remove sub-dropdown if main dropdown is hidden
        const sub = document.getElementById("upliner-sub-dropdown");
        if (sub) sub.remove();
      }
    };

    window.filterList = function (type) {
      const text = document.getElementById(`search-${type}`).value;
      renderList(type, text);
    };

    window.selectItem = function (
      type,
      value,
      elementId = null,
      parentValue = null
    ) {
      // Handle Sub-modal logic for Upliner
      if (type === "upliner" && currentUplinerMode === "main") {
        // Check if this is a sub-item selection (has parentValue)
        if (parentValue) {
          // Populate Detail View
          document.getElementById("detail-asm-name").innerText = value;
          document.getElementById("detail-asm-code").innerText =
            uplinerCodes[value] || "N/A";
          document.getElementById("detail-rsm-name").innerText = parentValue;
          document.getElementById("detail-rsm-code").innerText =
            uplinerCodes[parentValue] || "N/A";

          // Toggle Views
          document
            .getElementById("upliner-default-view")
            .classList.add("hidden");
          document
            .getElementById("upliner-detail-view")
            .classList.remove("hidden");

          // Clean up sub dropdown
          const sub = document.getElementById("upliner-sub-dropdown");
          if (sub) sub.remove();

          // Close main dropdown
          document.getElementById(`dropdown-${type}`).classList.add("hidden");
          return;
        }

        // Remove any open sub-dropdowns first
        const existingSub = document.getElementById("upliner-sub-dropdown");
        if (existingSub) existingSub.remove();

        const subItems = uplinerSubData[value];
        if (subItems) {
          const parentItem = document.getElementById(elementId);
          if (!parentItem) return;

          // Create sub-dropdown container
          const subDropdown = document.createElement("div");
          subDropdown.id = "upliner-sub-dropdown";
          subDropdown.className =
            "fixed bg-white rounded-lg shadow-xl border border-gray-100 z-[60] overflow-hidden w-64";

          // Calculate Position relative to viewport since we are appending to body
          const rect = parentItem.getBoundingClientRect();

          // Replicate the previous visual alignment (slightly moved up)
          subDropdown.style.top = `${rect.top - 80}px`;
          subDropdown.style.left = `${rect.right + 10}px`;

          // Generate List
          subDropdown.innerHTML = `
                <div class="max-h-60 overflow-y-auto custom-scrollbar">
                    ${subItems
              .map(
                (item) => `
                        <div onclick="selectItem('${type}', '${item}', null, '${value}')" class="px-3 py-2 text-sm text-[#20406b] hover:bg-gray-50 cursor-pointer transition-colors">
                            ${item}
                        </div>
                    `
              )
              .join("")}
                </div>
            `;

          document.body.appendChild(subDropdown);
          return;
          return;
        }
      }

      // Final Selection Logic
      const display = document.getElementById(`selected-${type}`);

      if (type === "upliner") {
        const code = uplinerCodes[value] || "N/A";
        display.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs font-medium">${code}</span>
                    <span class="text-[#20406b]">${value}</span>
                </div>
            `;
        display.classList.remove("text-gray-500");
        // Ensure parent button aligns items correctly
        const trigger = document.getElementById(`dropdownTrigger-${type}`);
        if (trigger) {
          // Keep flex layout but ensure content doesn't overflow
          trigger.classList.remove("text-gray-500");
        }
      } else {
        display.innerText = value;
        display.classList.remove("text-gray-500");
        display.classList.add("text-[#20406b]");
      }

      document.getElementById(`dropdown-${type}`).classList.add("hidden");

      // Cleanup sub-dropdown if exists
      const sub = document.getElementById("upliner-sub-dropdown");
      if (sub) sub.remove();
    };

    window.resetUpliner = function () {
      document.getElementById("upliner-detail-view").classList.add("hidden");
      document
        .getElementById("upliner-default-view")
        .classList.remove("hidden");

      // Reset state
      currentUplinerMode = "main";
      const display = document.getElementById("selected-upliner");
      display.innerText = "Select upliner";
      display.classList.add("text-gray-500");
      display.classList.remove("text-[#20406b]");

      // Ensure dropdown is closed
      document.getElementById("dropdown-upliner").classList.add("hidden");
    };

    // Close dropdowns when clicking outside
    document.addEventListener("click", function (e) {
      // Handle main dropdowns
      document.querySelectorAll('[id^="dropdown-"]').forEach((dropdown) => {
        const type = dropdown.id.replace("dropdown-", "");
        const trigger = document.getElementById(`dropdownTrigger-${type}`);
        const sub = document.getElementById("upliner-sub-dropdown");

        if (!dropdown.classList.contains("hidden")) {
          const clickedInsideMain = dropdown.contains(e.target);
          const clickedInsideTrigger = trigger.contains(e.target);
          const clickedInsideSub = sub && sub.contains(e.target);

          if (
            !clickedInsideMain &&
            !clickedInsideTrigger &&
            !clickedInsideSub
          ) {
            dropdown.classList.add("hidden");
            if (sub) sub.remove();
          }
        }
      });

      // Handle standalone sub-dropdown if main is somehow closed or just clicking outside everything
      const sub = document.getElementById("upliner-sub-dropdown");
      if (sub && !sub.contains(e.target)) {
        // We allow clicking the parent trigger/list to handled by logic above, but for general clicks:
        // logic above handles most cases.
      }
    });

    // Add custom scrollbar style
    const style = document.createElement("style");
    style.textContent = `
          .custom-scrollbar::-webkit-scrollbar {
              width: 6px;
          }
          .custom-scrollbar::-webkit-scrollbar-track {
              background: #f1f1f1;
              border-radius: 4px;
          }
          .custom-scrollbar::-webkit-scrollbar-thumb {
              background: #888;
              border-radius: 4px;
          }
          .custom-scrollbar::-webkit-scrollbar-thumb:hover {
              background: #555;
          }
      `;
    document.head.appendChild(style);
  </script>
</body>

</html>