/**
 * KONFIGURASI MENU
 */
const menuItems = [
    { icon: 'layout-dashboard', label: 'Dashboard', hasSubmenu: false, url: 'dashboard.html' },
    { icon: 'file-input', label: 'Data Entry', hasSubmenu: false, url: 'data_entry/data_entry.html' },
    {
        icon: 'file-text',
        label: 'Application Input',
        hasSubmenu: true,
        submenu: [
            { label: 'Pemol', url: 'application_input/application_input_pemol.html' },
            { label: 'Merchant', url: 'application_input/application_input_merchant.html' },
            { label: 'CC Reguler', url: 'application_input/application_input_cc_reguler.html' },
            { label: 'Mobile Sales', url: 'application_input/application_input_cc_ms.html' },
            { label: 'Corporate', url: 'application_input/application_input_corporate.html' },
            { label: 'Smart Cash (SC)', url: 'application_input/application_input_sc.html' },
            { label: 'Personal Loan (PL)', url: 'application_input/application_input_pl.html' },
            { label: 'Data Decision Credit Card', url: 'application_input/application_input_cc_dsr.html' }
        ]
    },
    {
        icon: 'database',
        label: 'Data Decision',
        hasSubmenu: true,
        submenu: [
            { label: 'PEMOL', url: 'data_decision/data_decision_pemol.html' },
            { label: 'Merchant', url: 'data_decision/data_decision_merchant.html' },
            { label: 'Credit Card (CC)', url: 'data_decision/data_decision_cc.html' },
            { label: 'Corporate', url: 'data_decision/data_decision_corporate.html' },
            { label: 'Smart Cash (SC)', url: 'data_decision/data_decision_sc.html' },
            { label: 'Personal Loan (PL)', url: 'data_decision/data_decision_pl.html' },
            { label: 'Pemol_DSR', url:'data_decision/data_decision_pemol_dsr.html'}
        ]
    },
    {
        icon: 'inbox',
        label: 'Incoming',
        hasSubmenu: true,
        submenu: [
            { label: 'Mobile Sales', url: 'incoming/incoming_mobile_sales.html' },
            { label: 'Pemol', url: 'incoming/incoming_pemol.html' },
            { label: 'TM CC', url: 'incoming/incoming_tm_cc.html' },
            { label: 'TM SC', url: 'incoming/incoming_tm_sc.html' }
        ]
    },
    {
        icon: 'check-square',
        label: 'Application Check',
        hasSubmenu: true,
        submenu: [
            { label: 'Pemol', url: 'application_check/application_check_pemol.html' },
            { label: 'Merchant', url: 'application_check/application_check_merchant.html' },
            { label: 'Credit Card (CC)', url: 'application_check/application_check_cc.html' },
            { label: 'Corporate', url: 'application_check/application_check_corporate.html' },
            { label: 'Smart Cash (SC)', url: 'application_check/application_check_sc.html' },
            { label: 'Personal Loan (PL)', url: 'application_check/application_check_pl.html' }
        ]
    },
    { icon: 'trending-up', label: 'My Performance', hasSubmenu: false, url: 'my_performance/my_performance.html' },
    { icon: 'file-plus', label: 'Data Addendum', hasSubmenu: false, url: 'data_addendum/data_addendum.html' },
    { icon: 'users', label: 'Team Performance', hasSubmenu: false, url: 'team_performance/team_performance.html' },
    { icon: 'info', label: 'Sales Information', hasSubmenu: false, url: 'sales_information/sales_information.html' },
    {
        icon: 'user-circle',
        label: 'Candidate Info',
        hasSubmenu: true,
        submenu: [
            { label: 'Candidate Details', url: 'candidate_info/candidate_details.html' },
            { label: 'Approval', url: 'candidate_info/approval.html' },
            { label: 'History', url: 'candidate_info/history.html' }
        ]
    },
    {
        icon: 'send',
        label: 'Request to HRD',
        hasSubmenu: true,
        submenu: [
            { label: 'Exit', url: 'request_to_hrd/request_to_hrd.html' },
            { label: 'Restruct', url: 'request_to_hrd/request_to_hrd_restruct.html' },
            { label: 'Level', url: 'request_to_hrd/request_to_hrd_level.html' },
            { label: 'Reaktif', url: 'request_to_hrd/request_to_hrd_reactive.html' }
        ]
    },
    {
        icon: 'check-circle',
        label: 'Approval',
        hasSubmenu: true,
        submenu: [
            { label: 'Restruct', url: 'Approval/approval_restruct.html' },
            { label: 'Reaktif', url: 'Approval/approval_reaktif.html' },
            { label: 'Promotion', url: 'Approval/approval_promotion.html' }
        ]
    },
    { icon: 'map-pin', label: 'Check Postal Code', hasSubmenu: false, url: 'check_postal_code/check_postal_code.html' },
    { icon: 'copy', label: 'Duplicate Check', hasSubmenu: false, url: 'duplicate_check/duplicate_check.html' },
    { icon: 'monitor', label: 'Monitoring', hasSubmenu: false, url: 'monitoring/monitoring.html' },
    { icon: 'file-text', label: 'Slip Incentive', hasSubmenu: false, url: 'slip_incentive/slip_incentive.html' },
];

/**
 * LOGIKA TOGGLE & INTERAKSI
 */
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const body = document.body;

    if (sidebar.classList.contains('-translate-x-full')) {
        // Buka Sidebar
        sidebar.classList.remove('-translate-x-full');
        if (overlay) {
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.add('opacity-100'), 10);
        }
        body.style.overflow = 'hidden'; // Lock scroll pada mobile
    } else {
        // Tutup Sidebar
        sidebar.classList.add('-translate-x-full');
        if (overlay) {
            overlay.classList.remove('opacity-100');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
        body.style.overflow = ''; // Unlock scroll
    }
}

function closeSidebarMobile() {
    if (window.innerWidth < 1024) { // Jika layar di bawah ukuran desktop (lg)
        toggleSidebar();
    }
}

function toggleSubmenu(itemId) {
    const submenu = document.getElementById(`submenu-${itemId}`);
    const arrow = document.getElementById(`arrow-${itemId}`);
    if (submenu) {
        submenu.classList.toggle('hidden');
        if (arrow) arrow.classList.toggle('rotate-180');
    }
}

function toggleUserProfile(event) {
    if(event) event.stopPropagation();
    const popup = document.getElementById('user-profile-popup');
    const arrow = document.getElementById('user-profile-arrow');
    
    if(popup) {
        popup.classList.toggle('hidden');
        if(!popup.classList.contains('hidden')) {
            // Close other menus if needed
        }
    }
    
    if(arrow) {
        arrow.classList.toggle('rotate-90');
    }
}

// Close profile popup when clicking outside
document.addEventListener('click', function(event) {
    const popup = document.getElementById('user-profile-popup');
    const trigger = document.querySelector('[onclick="toggleUserProfile(event)"]');
    
    if(popup && !popup.classList.contains('hidden')) {
        if(!popup.contains(event.target) && (!trigger || !trigger.contains(event.target))) {
            popup.classList.add('hidden');
            const arrow = document.getElementById('user-profile-arrow');
            if(arrow) arrow.classList.remove('rotate-90');
        }
    }
});

/**
 * RENDER COMPONENTS
 */
function renderSidebar(activeParent, activeSubmenu) {
    const sidebar = document.createElement('aside');
    // Penyesuaian: Removed overflow-y-auto from container, added to nav for better popup handling
    sidebar.className = `fixed lg:sticky top-0 left-0 h-screen w-full sm:w-[300px] lg:w-[260px] bg-[#1E5BA8] text-white flex flex-col transform transition-transform duration-300 z-50 -translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none`;
    sidebar.id = 'sidebar';

    sidebar.innerHTML = `
        <div class="px-5 py-6 border-b border-white/10 flex items-center justify-between flex-shrink-0">
            <div class="text-xl font-semibold">Dashboard</div>
            <button onclick="toggleSidebar()" class="lg:hidden text-white/70 p-1">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <!-- User Profile Section -->
        <div class="px-5 py-4 border-b border-white/10 relative flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center overflow-hidden flex-shrink-0 border-2 border-white">
                     <img src="https://picsum.photos/seed/budi/100/100" alt="User" class="w-full h-full object-cover"/>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm truncate">Budi Dharma</p>
                    <p class="text-xs text-white/70">BSH</p>
                </div>
                 <button onclick="toggleUserProfile(event)" class="text-white/70 hover:text-white transition-colors p-1">
                    <i data-lucide="chevron-right" id="user-profile-arrow" class="w-5 h-5 transition-transform duration-200"></i>
                </button>
            </div>

            <!-- Profile Popup Card -->
            <div id="user-profile-popup" class="hidden absolute left-4 right-4 top-full mt-2 bg-white rounded-2xl shadow-xl p-5 z-[100] text-gray-800 animate-in fade-in zoom-in-95 duration-200 origin-top border border-blue-100">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full bg-orange-100 mb-3 overflow-hidden border-4 border-white shadow-sm ring-1 ring-gray-100">
                         <img src="https://picsum.photos/seed/budi/200/200" alt="User" class="w-full h-full object-cover"/>
                    </div>
                    <h3 class="text-lg font-bold text-[#1E293B] mb-0.5">Budi Dharma</h3>
                    <p class="text-sm text-gray-500 mb-4 tracking-wide">K1002059 <span class="mx-1 text-gray-300">|</span> BSH</p>
                    
                    <div class="flex items-center justify-center gap-4 w-full mb-6">
                        <div class="flex flex-col items-center px-4">
                             <span class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">RSM</span>
                             <span class="bg-blue-50 text-[#1E5BA8] text-sm font-bold px-4 py-1 rounded-full">15</span>
                        </div>
                         <div class="flex flex-col items-center px-4 border-l border-gray-100">
                             <span class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">ASM</span>
                             <span class="bg-blue-50 text-[#1E5BA8] text-sm font-bold px-4 py-1 rounded-full">105</span>
                        </div>
                    </div>

                    <div class="w-full border-t border-gray-100 pt-4">
                        <a href="#" onclick="window.location.href=(window.SITE_URL || '') + 'auth'" class="flex items-center justify-center gap-2 text-[#1E5BA8] font-bold text-sm hover:underline group">
                            Log out 
                            <i data-lucide="log-out" class="w-4 h-4 group-hover:translate-x-0.5 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <nav class="flex-1 py-2 overflow-y-auto custom-scrollbar" id="sidebar-nav"></nav>
    <style>
        /* Custom Scrollbar Styling to hide default bar but keep functionality */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }
        /* Hide scrollbar for Firefox */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) rgba(255, 255, 255, 0.05);
        }
    </style>
    `;

    const nav = sidebar.querySelector('#sidebar-nav');

    // Helper URL Resolver
    const resolveUrl = (url) => {
        if (!url || url === '#') return '#';
        let ciUrl = url.replace('.html', '');
        return (window.SITE_URL || '') + ciUrl;
    };

    menuItems.forEach(item => {
        const div = document.createElement('div');
        const isParentActive = activeParent === item.label;
        const itemId = item.label.replace(/\s+/g, '-').toLowerCase();

        if (item.hasSubmenu) {
            const shouldExpand = isParentActive;
            div.innerHTML = `
                <button onclick="toggleSubmenu('${itemId}')" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-colors text-left w-[calc(100%-16px)] ${isParentActive ? 'bg-white text-[#1E5BA8] font-medium shadow-sm' : 'text-white hover:bg-white/10'}">
                    <i data-lucide="${item.icon}" class="w-5 h-5 flex-shrink-0 ${isParentActive ? 'text-[#1E5BA8]' : ''}"></i>
                    <span class="flex-1 text-sm font-medium">${item.label}</span>
                    <i data-lucide="chevron-down" id="arrow-${itemId}" class="w-4 h-4 transition-transform ${shouldExpand ? 'rotate-180' : ''} ${isParentActive ? 'text-[#1E5BA8]' : ''}"></i>
                </button>
                <div id="submenu-${itemId}" class="${shouldExpand ? '' : 'hidden'} ml-7 pl-4 border-l border-white/20 my-2 space-y-1">
                    ${item.submenu.map(subItem => {
                const isSubActive = activeSubmenu === subItem.label;
                return `
                        <a href="${resolveUrl(subItem.url)}" onclick="closeSidebarMobile()" class="block w-full px-4 py-2 rounded-lg transition-colors text-left text-sm ${isSubActive ? 'bg-transparent text-white font-bold' : 'text-white/80 hover:text-white hover:bg-white/5'}">
                            ${subItem.label}
                        </a>`;
            }).join('')}
                </div>
            `;
        } else {
            const isActive = isParentActive;
            div.innerHTML = `
                <a href="${resolveUrl(item.url)}" onclick="closeSidebarMobile()" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-colors text-left ${isActive ? 'bg-white text-[#1E5BA8] font-medium shadow-sm' : 'text-white hover:bg-white/10'}">
                    <i data-lucide="${item.icon}" class="w-5 h-5 flex-shrink-0 ${isActive ? 'text-[#1E5BA8]' : ''}"></i>
                    <span class="flex-1 text-sm font-medium">${item.label}</span>
                </a>
            `;
        }
        nav.appendChild(div);
    });

    return sidebar;
}

function renderHeader(activeParent, activeSubmenu) {
    const header = document.createElement('header');
    header.className = "bg-white border-b border-gray-200 px-4 lg:px-6 h-16 flex items-center gap-3 sticky top-0 z-40";

    const displayTitle = activeSubmenu || activeParent || 'Dashboard';

    header.innerHTML = `
        <button onclick="toggleSidebar()" class="lg:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <h1 class="text-lg lg:text-2xl font-semibold text-[#1E5BA8] truncate">
            ${displayTitle}
        </h1>
    `;
    return header;
}

/**
 * INITIALIZATION
 */
function initLayout(activeParentInput = '', activeSubmenuInput = '', customTitle = '') {
    const app = document.getElementById('app');
    if (!app) return;

    // Implement "Smart Zoom" for Layout Consistency
    // This ensures that on smaller laptop screens (1366x768, 1440x900), the content isn't too cramped (80% scale).
    // On larger confirmed desktop monitors (1600px+), it uses standard 100% scale.
    const zoomStyle = document.createElement('style');
    zoomStyle.innerHTML = `
        @media (min-width: 1024px) and (max-width: 1599px) {
            body { zoom: 100%; }
        }
        @media (min-width: 1600px) {
            body { zoom: 100%; }
        }
    `;
    document.head.appendChild(zoomStyle);

    // Detect Active Page based on URL (Priority for Sidebar Highlighting)
    let detectedParent = '';
    let detectedSubmenu = '';
    const currentFilename = window.location.pathname.split('/').pop();

    for (const item of menuItems) {
        if (item.url && item.url.endsWith(currentFilename)) {
            detectedParent = item.label;
            break;
        }
        if (item.hasSubmenu && item.submenu) {
            const subMatch = item.submenu.find(sub => sub.url.endsWith(currentFilename));
            if (subMatch) {
                detectedParent = item.label;
                detectedSubmenu = subMatch.label;
                break;
            }
        }
    }

    // Determine final active state for Sidebar
    // (Prefer detected URL-based state to ensure side menu is correct)
    const activeParent = detectedParent || activeParentInput;
    const activeSubmenu = detectedSubmenu || activeSubmenuInput;

    // Determine Header Title
    // Priority: customTitle > detectedSubmenu > activeSubmenuInput > detectedParent > activeParentInput > 'Dashboard'
    // If URL detection found a submenu, we usually want that as the header title.
    let displayTitle = customTitle;
    if (!displayTitle) {
        if (detectedSubmenu) displayTitle = detectedSubmenu;
        else if (activeSubmenuInput) displayTitle = activeSubmenuInput;
        else if (detectedParent) displayTitle = detectedParent;
        else if (activeParentInput) displayTitle = activeParentInput;
        else displayTitle = 'Dashboard';
    }

    // Sidebar Overlay
    let overlay = document.getElementById('sidebar-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'sidebar-overlay';
        overlay.className = 'fixed inset-0 bg-black/50 z-40 hidden opacity-0 transition-opacity duration-300 lg:hidden';
        overlay.onclick = toggleSidebar;
        document.body.appendChild(overlay);
    }

    // Compose Layout
    const sidebar = renderSidebar(activeParent, activeSubmenu);
    const header = renderHeader(activeParent, displayTitle);

    const mainContent = document.createElement('div');
    mainContent.className = "flex-1 min-w-0 flex flex-col min-h-screen";
    mainContent.appendChild(header);

    // Placeholder for page content - Only create if there's initial content to preserve
    const hasInitialContent = app.firstChild;
    if (hasInitialContent) {
        const contentBody = document.createElement('main');
        contentBody.id = 'content-body';
        contentBody.className = 'p-4 lg:p-8';
        while (app.firstChild) contentBody.appendChild(app.firstChild);
        mainContent.appendChild(contentBody);
    }

    const wrapper = document.createElement('div');
    wrapper.className = "flex min-h-screen bg-gray-50";
    wrapper.appendChild(sidebar);
    wrapper.appendChild(mainContent);

    app.innerHTML = '';
    app.appendChild(wrapper);

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}