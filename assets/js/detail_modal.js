// assets/js/detail_modal.js
window.currentDetailTab = 'data-input';

window.closeModalDetail = function () {
    const modal = document.getElementById('modalDetail');
    if (modal) modal.classList.add('hidden');
    document.body.style.overflow = '';
};

window.switchDetailTab = function (tab) {
    window.currentDetailTab = tab;

    // Update UI Tabs
    const btnData = document.getElementById('tabDataInput');
    const btnApp = document.getElementById('tabAppProcessing');

    if (!btnData || !btnApp) return;

    if (tab === 'data-input') {
        btnData.className = "px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 bg-[#2463B4] text-white shadow-sm";
        btnApp.className = "px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-gray-400 hover:text-gray-600";
    } else {
        btnApp.className = "px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 bg-[#2463B4] text-white shadow-sm";
        btnData.className = "px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-gray-400 hover:text-gray-600";
    }

    // Refresh Content
    window.loadDetailContent();
};

window.loadDetailContent = async function () {
    const contentDiv = document.getElementById('modalDetailContent');
    if (!contentDiv) return;

    const nik = window.activeActionData?.nik;
    const position = window.activeActionData?.position;

    // Show loading spinner
    contentDiv.innerHTML = `
      <div class="flex items-center justify-center py-20">
        <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
      </div>`;

    try {
        const response = await fetch(window.SITE_URL + 'application_input/get_detail_content', {
            method: 'POST',
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                nik: nik,
                position: position,
                tab: window.currentDetailTab,
                date_from: window.selectedFrom,
                date_to: window.selectedTo,
                source: window.selectedSource
            })
        });

        const html = await response.text();
        contentDiv.innerHTML = html;
        if (window.lucide) {
            window.lucide.createIcons();
        }
    } catch (err) {
        console.error('Detail load error:', err);
        contentDiv.innerHTML = `<p class="text-center text-red-500 py-10 font-medium">Error loading content.</p>`;
    }
};


