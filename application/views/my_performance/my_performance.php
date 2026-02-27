<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Performance - M-Sales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>window.SITE_URL = "<?= site_url(); ?>/";</script>   <script src="<?= base_url('assets/js/layout.js') ?>"></script>
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
      initLayout("My Performance");

      const appContainer = document.querySelector("#app > div > div");
      const main = document.createElement("div");
      main.className = "flex-1 bg-gray-50 p-6";

      main.innerHTML = `
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <!-- Header Filter Section -->
            <div class="p-6 border-b border-gray-200 flex items-center gap-4">
                <label class="text-[#1E5BA8] font-bold text-lg">Period</label>
                <div class="relative">
                    <button onclick="toggleDateMenu(event)" class="appearance-none border border-gray-300 rounded-lg px-4 py-2 bg-white text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px] flex items-center justify-between">
                        <span id="selectedDate">December 2025</span>
                        <i data-lucide="chevron-down" class="text-gray-400 w-4 h-4 ml-2"></i>
                    </button>
                    
                    <div id="dateMenu" class="hidden absolute top-full left-0 mt-2 w-full bg-white rounded-xl shadow-xl border border-gray-100 z-50 p-2 animate-in fade-in zoom-in-95 duration-200">
                        <div class="space-y-1">
                            <button onclick="selectDate('December 2025')" class="w-full text-left px-3 py-2 text-sm text-[#1E5BA8] bg-blue-50 rounded-lg font-medium">December 2025</button>
                            <button onclick="selectDate('November 2025')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors">November 2025</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto rounded-b-lg">
                <table class="w-full text-center border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-[#1E5BA8] text-white">
                            <!-- First Column (No longer sticky) -->
                            <th class="px-6 py-4 border-r border-[#4E7CC9] border-b border-[#1E5BA8] w-48 bg-[#1E5BA8]"></th>
                            
                            <!-- Headers -->
                            <th class="px-6 py-4 text-sm font-semibold border-r border-[#4E7CC9] border-b border-[#1E5BA8] last:border-r-0 whitespace-nowrap min-w-[150px]">Credit Card (CC)</th>
                            <th class="px-6 py-4 text-sm font-semibold border-r border-[#4E7CC9] border-b border-[#1E5BA8] last:border-r-0 whitespace-nowrap min-w-[150px]">EDC</th>
                            <th class="px-6 py-4 text-sm font-semibold border-r border-[#4E7CC9] border-b border-[#1E5BA8] last:border-r-0 whitespace-nowrap min-w-[150px]">Smart Cash (SC)</th>
                            <th class="px-6 py-4 text-sm font-semibold border-r border-[#4E7CC9] border-b border-[#1E5BA8] last:border-r-0 whitespace-nowrap min-w-[150px]">Personal Loan (PL)</th>
                            <th class="px-6 py-4 text-sm font-semibold border-b border-[#1E5BA8] whitespace-nowrap min-w-[150px]">Corporate</th>
                        </tr>
                    </thead>
                    <tbody id="performanceBody">
                        <!-- Rows -->
                    </tbody>
                </table>
            </div>
        </div>
    `;

      appContainer.appendChild(main);

      // Row Data Definition
      const rows = [
        { label: "Application Input", isPercent: false },
        { label: "Incoming", isPercent: false },
        { label: "Approved", isPercent: false },
        { label: "Cancel", isPercent: false },
        { label: "Decline / Reject", isPercent: false },
        { label: "App Rate", isPercent: true },
        { label: "Book Rate", isPercent: true },
        { label: "Run Rate INC", isPercent: false },
        { label: "Run Rate APP", isPercent: false },
      ];

      const generateRowData = (isPercentage) => {
        // Generates 1, 2, 3, 4, 5 pattern
        return [1, 2, 3, 4, 5].map((n) => (isPercentage ? `${n}%` : n));
      };

      const tbody = document.getElementById("performanceBody");

      rows.forEach((row, index) => {
        const tr = document.createElement("tr");
        const bgClass = index % 2 === 0 ? "bg-white" : "bg-gray-50";

        const cellsData = generateRowData(row.isPercent);

        // First Column (No longer sticky)
        let html = `
            <td class="px-6 py-4 text-left text-sm text-gray-700 font-medium border-r border-gray-200 border-b border-gray-200 whitespace-nowrap ${bgClass}">${row.label}</td>
        `;

        // Data Columns
        cellsData.forEach((val, i) => {
          const isLast = i === cellsData.length - 1;
          html += `<td class="px-6 py-4 text-sm text-gray-600 ${
            isLast ? "" : "border-r"
          } border-b border-gray-200 whitespace-nowrap ${bgClass}">${val}</td>`;
        });

        tr.innerHTML = html;
        tbody.appendChild(tr);
      });

      lucide.createIcons();

      // Custom Dropdown Logic
      function toggleDateMenu(event) {
        event.stopPropagation();
        const dateMenu = document.getElementById("dateMenu");
        dateMenu.classList.toggle("hidden");
      }

      function selectDate(date) {
        document.getElementById("selectedDate").innerText = date;

        // Update the menu items to highlight selected
        const menu = document.getElementById("dateMenu");
        const buttons = menu.querySelectorAll("button");
        buttons.forEach((btn) => {
          if (btn.innerText === date) {
            btn.className =
              "w-full text-left px-3 py-2 text-sm text-[#1E5BA8] bg-blue-50 rounded-lg font-medium";
          } else {
            btn.className =
              "w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-colors";
          }
        });

        // Close menu
        document.getElementById("dateMenu").classList.add("hidden");
      }

      // Close dropdown when clicking outside
      document.addEventListener("click", function (event) {
        const dateMenu = document.getElementById("dateMenu");
        const toggleButton = document.querySelector(
          '[onclick="toggleDateMenu(event)"]'
        );

        if (dateMenu && !dateMenu.classList.contains("hidden")) {
          if (
            !dateMenu.contains(event.target) &&
            !toggleButton.contains(event.target)
          ) {
            dateMenu.classList.add("hidden");
          }
        }
      });
    </script>
  </body>
</html>
