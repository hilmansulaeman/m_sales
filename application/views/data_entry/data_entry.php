<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Entry - M-Sales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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
  <body class="bg-gray-50">
    <div id="app">
      <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:p-8"
      >
        <h2 class="text-2xl font-bold text-[#1E5BA8] mb-6">Form E-Branch</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
          <!-- Left Column: Inputs -->
          <div class="space-y-6">
            <!-- Customer Name -->
            <div>
              <label class="block text-base font-bold text-slate-700 mb-2">
                <span class="text-red-500">*</span>Customer name
              </label>
              <input
                type="text"
                placeholder="Input customer name"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400 text-sm"
              />
            </div>

            <!-- Source Code -->
            <div>
              <label class="block text-base font-bold text-slate-700 mb-2">
                <span class="text-red-500">*</span>Source code
              </label>
              <div class="relative">
                <!-- Custom Dropdown Trigger -->
                <button
                  type="button"
                  onclick="toggleSourceCodeMenu()"
                  id="sourceCodeTrigger"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg flex items-center justify-between focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-gray-500 text-sm cursor-pointer text-left"
                >
                  <span id="sourceCodeLabel">Input source code</span>
                  <input
                    type="hidden"
                    name="source_code"
                    id="sourceCodeInput"
                  />
                  <i
                    data-lucide="chevron-down"
                    class="w-4 h-4 text-gray-400"
                  ></i>
                </button>

                <!-- Custom Dropdown Menu -->
                <div
                  id="sourceCodeMenu"
                  class="hidden absolute top-full left-0 mt-2 w-full bg-white rounded-xl shadow-xl border border-gray-100 z-50 p-2 animate-in fade-in zoom-in-95 duration-200"
                >
                  <div class="space-y-1">
                    <button
                      type="button"
                      onclick="selectSourceCode('Web ACCO with kirbal')"
                      class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5BA8] rounded-lg font-medium transition-colors"
                    >
                      Web ACCO with kirbal
                    </button>
                    <button
                      type="button"
                      onclick="selectSourceCode('Web ACCO without kirbal')"
                      class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5BA8] rounded-lg font-medium transition-colors"
                    >
                      Web ACCO without kirbal
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Event -->
            <div>
              <label class="block text-base font-bold text-slate-700 mb-2">
                <span class="text-red-500">*</span>Event
              </label>
              <div class="relative">
                <!-- Custom Dropdown Trigger -->
                <button
                  type="button"
                  onclick="toggleEventMenu()"
                  id="eventTrigger"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg flex items-center justify-between focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-gray-500 text-sm cursor-pointer text-left"
                >
                  <span id="eventLabel">Input event</span>
                  <input type="hidden" name="event" id="eventInput" />
                  <i
                    data-lucide="chevron-down"
                    class="w-4 h-4 text-gray-400"
                  ></i>
                </button>

                <!-- Custom Dropdown Menu -->
                <div
                  id="eventMenu"
                  class="hidden absolute top-full left-0 mt-2 w-full bg-white rounded-xl shadow-xl border border-gray-100 z-50 p-2 animate-in fade-in zoom-in-95 duration-200"
                >
                  <div class="space-y-1">
                    <button
                      type="button"
                      onclick="selectEvent('Event 1')"
                      class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5BA8] rounded-lg font-medium transition-colors"
                    >
                      Event 1
                    </button>
                    <button
                      type="button"
                      onclick="selectEvent('Event 2')"
                      class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5BA8] rounded-lg font-medium transition-colors"
                    >
                      Event 2
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Reference Number -->
            <div>
              <label class="block text-base font-bold text-slate-700 mb-2">
                <span class="text-red-500">*</span>Reference number
              </label>
              <input
                type="text"
                placeholder="e.g. A012345678"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400 text-sm"
              />
            </div>

            <!-- Reference Code -->
            <div>
              <label class="block text-base font-bold text-slate-700 mb-2">
                <span class="text-red-500">*</span>Reference code
              </label>
              <div class="relative">
                <!-- Custom Dropdown Trigger -->
                <button
                  type="button"
                  onclick="toggleRefCodeMenu()"
                  id="refCodeTrigger"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg flex items-center justify-between focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-gray-500 text-sm cursor-pointer text-left"
                >
                  <span id="refCodeLabel">Input reference code</span>
                  <input
                    type="hidden"
                    name="reference_code"
                    id="refCodeInput"
                  />
                  <i
                    data-lucide="chevron-down"
                    class="w-4 h-4 text-gray-400"
                  ></i>
                </button>

                <!-- Custom Dropdown Menu -->
                <div
                  id="refCodeMenu"
                  class="hidden absolute top-full left-0 mt-2 w-full bg-white rounded-xl shadow-xl border border-gray-100 z-50 p-2 animate-in fade-in zoom-in-95 duration-200"
                >
                  <div class="space-y-1">
                    <button
                      type="button"
                      onclick="selectRefCode('Web ACCO with kirbal')"
                      class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5BA8] rounded-lg font-medium transition-colors"
                    >
                      Web ACCO with kirbal
                    </button>
                    <button
                      type="button"
                      onclick="selectRefCode('Web ACCO without kirbal')"
                      class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1E5BA8] rounded-lg font-medium transition-colors"
                    >
                      Web ACCO without kirbal
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column: Media Upload -->
          <div class="flex flex-col">
            <label class="block text-base font-bold text-slate-700 mb-2">
              <span class="text-red-500">*</span>Media
            </label>
            <p class="text-sm text-gray-400 mb-3">
              Upload ACCO photo with JPG, JPEG or PNG format, max size 5MB
            </p>

            <div
              class="w-full h-64 lg:h-auto lg:flex-1 border-2 border-dashed border-gray-300 rounded-xl relative group overflow-hidden hover:border-blue-400 hover:bg-blue-50/10 transition-all cursor-pointer bg-gray-50/50"
            >
              <input
                type="file"
                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                accept=".jpg,.jpeg,.png"
              />
              <div
                class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center"
              >
                <p class="text-gray-400 text-sm mb-2">
                  Drag and drop or click to upload
                </p>
                <span class="text-[#1E5BA8] font-bold text-lg hover:underline"
                  >Select file</span
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-8 lg:mt-10 flex justify-center">
          <button
            class="w-full lg:w-auto bg-[#1E5BA8] text-white px-6 py-3.5 rounded-full font-bold text-base hover:bg-[#154a8a] transition-colors shadow-sm lg:min-w-[200px]"
          >
            Submit
          </button>
        </div>
      </div>
    </div>

    <script>window.SITE_URL = "<?= site_url(); ?>/";</script>    <script src="<?= base_url('assets/js/layout.js') ?>"></script>
    <script>
      // Initialize Layout
      // Initialize Layout
      initLayout("Data Entry");

      function toggleSourceCodeMenu() {
        const menu = document.getElementById("sourceCodeMenu");
        menu.classList.toggle("hidden");
      }

      function selectSourceCode(value) {
        // Update input hidden
        document.getElementById("sourceCodeInput").value = value;

        // Update Label
        const label = document.getElementById("sourceCodeLabel");
        label.textContent = value;
        label.classList.remove("text-gray-500");
        label.classList.add("text-gray-900");

        // Close Menu
        document.getElementById("sourceCodeMenu").classList.add("hidden");
      }

      function toggleEventMenu() {
        const menu = document.getElementById("eventMenu");
        menu.classList.toggle("hidden");
      }

      function selectEvent(value) {
        // Update input hidden
        document.getElementById("eventInput").value = value;

        // Update Label
        const label = document.getElementById("eventLabel");
        label.textContent = value;
        label.classList.remove("text-gray-500");
        label.classList.add("text-gray-900");

        // Close Menu
        document.getElementById("eventMenu").classList.add("hidden");
      }

      function toggleRefCodeMenu() {
        const menu = document.getElementById("refCodeMenu");
        menu.classList.toggle("hidden");
      }

      function selectRefCode(value) {
        // Update input hidden
        document.getElementById("refCodeInput").value = value;

        // Update Label
        const label = document.getElementById("refCodeLabel");
        label.textContent = value;
        label.classList.remove("text-gray-500");
        label.classList.add("text-gray-900");

        // Close Menu
        document.getElementById("refCodeMenu").classList.add("hidden");
      }

      // Close dropdown when clicking outside
      document.addEventListener("click", function (event) {
        // Source Code Menu
        const sourceMenu = document.getElementById("sourceCodeMenu");
        const sourceTrigger = document.getElementById("sourceCodeTrigger");

        if (sourceMenu && !sourceMenu.classList.contains("hidden")) {
          if (
            !sourceMenu.contains(event.target) &&
            !sourceTrigger.contains(event.target)
          ) {
            sourceMenu.classList.add("hidden");
          }
        }

        // Event Menu
        const eventMenu = document.getElementById("eventMenu");
        const eventTrigger = document.getElementById("eventTrigger");

        if (eventMenu && !eventMenu.classList.contains("hidden")) {
          if (
            !eventMenu.contains(event.target) &&
            !eventTrigger.contains(event.target)
          ) {
            eventMenu.classList.add("hidden");
          }
        }

        // Ref Code Menu
        const refMenu = document.getElementById("refCodeMenu");
        const refTrigger = document.getElementById("refCodeTrigger");

        if (refMenu && !refMenu.classList.contains("hidden")) {
          if (
            !refMenu.contains(event.target) &&
            !refTrigger.contains(event.target)
          ) {
            refMenu.classList.add("hidden");
          }
        }
      });
    </script>
  </body>
</html>
