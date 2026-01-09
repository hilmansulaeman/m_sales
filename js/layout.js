
const menuItems = [
    { icon: 'layout-dashboard', label: 'Dashboard', hasSubmenu: false, url: 'dashboard.html' },
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
            { label: 'Smart Cash (SC)', url: '#' },
            { label: 'Personal Loan (PL)', url: '#' }
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
            { label: 'Personal Loan (PL)', url: 'data_decision/data_decision_pl.html' }
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
    { icon: 'file-plus', label: 'Data Addendum', hasSubmenu: false, url: '#' },
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
];

function renderSidebar(activeParentInput = '', activeSubmenuInput = '') {
    // Try to auto-detect active items from URL if not provided
    let activeParent = activeParentInput;
    let activeSubmenu = activeSubmenuInput;

    const currentFilename = window.location.pathname.split('/').pop();

    // Helper to check if current user matches a menu URL
    const isCurrentPage = (url) => {
        if (!url || url === '#') return false;
        return url.endsWith(currentFilename);
    };

    if (!activeParent) {
        // Find matching item
        for (const item of menuItems) {
            if (isCurrentPage(item.url)) {
                activeParent = item.label;
                break;
            }
            if (item.hasSubmenu && item.submenu) {
                const subMatch = item.submenu.find(sub => isCurrentPage(sub.url));
                if (subMatch) {
                    activeParent = item.label;
                    activeSubmenu = subMatch.label;
                    break;
                }
            }
        }
    } else if (activeParent && !activeSubmenu) {
        // If parent is provided but submenu isn't, try to find matching submenu for current URL within that parent
        const parentItem = menuItems.find(item => item.label === activeParent);
        if (parentItem && parentItem.hasSubmenu && parentItem.submenu) {
            const subMatch = parentItem.submenu.find(sub => isCurrentPage(sub.url));
            if (subMatch) {
                activeSubmenu = subMatch.label;
            }
        }
    }

    const sidebar = document.createElement('aside');
    sidebar.className = `fixed lg:sticky top-0 left-0 h-screen w-[260px] bg-[#3B6EC2] text-white flex flex-col overflow-y-auto transform transition-transform duration-300 z-50 -translate-x-full lg:translate-x-0`;
    sidebar.id = 'sidebar';

    // Mobile Close Button & Header
    sidebar.innerHTML = `
        <div class="px-5 py-6 border-b border-white/10 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i data-lucide="menu" class="w-6 h-6"></i>
                <div class="text-xl font-semibold">Dashboard</div>
            </div>
            <button onclick="toggleSidebar()" class="lg:hidden text-white hover:bg-white/10 rounded-lg p-1">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <div class="px-5 py-4 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center overflow-hidden flex-shrink-0">
                    <img src="https://placehold.co/100" alt="User" class="w-full h-full object-cover"/>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm truncate">Budi Dharma</p>
                    <p class="text-xs text-white/70">BSH</p>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 flex-shrink-0"></i>
            </div>
        </div>
        <nav class="flex-1 py-2" id="sidebar-nav"></nav>
    `;

    const nav = sidebar.querySelector('#sidebar-nav');

    // Helper to adjust URL based on current depth
    const resolveUrl = (url) => {
        if (!url || url === '#') return '#';
        const path = window.location.pathname;
        const isLevel1 = path.includes('/data_decision/') || path.includes('/application_input/') || path.includes('/incoming/') || path.includes('/application_check/');

        if (isLevel1) {
            return '../' + url;
        }
        return url;
    };

    menuItems.forEach(item => {
        const div = document.createElement('div');
        const isParentActive = activeParent === item.label;
        const itemId = item.label.replace(/\s+/g, '-').toLowerCase();

        let submenuHtml = '';
        if (item.hasSubmenu && item.submenu) {
            const shouldExpand = isParentActive;

            submenuHtml = `
                <div id="submenu-${itemId}" class="bg-white/5 ${shouldExpand ? '' : 'hidden'}">
                    ${item.submenu.map(subItem => {
                const isSubActive = activeSubmenu === subItem.label;
                return `
                        <a href="${resolveUrl(subItem.url)}" class="block w-full pl-14 pr-5 py-2.5 hover:bg-white/10 transition-colors text-left text-sm text-white ${isSubActive ? 'text-yellow-300 font-medium' : ''}">
                            ${subItem.label}
                        </a>
                    `}).join('')}
                </div>
            `;
        }

        if (item.hasSubmenu) {
            div.innerHTML = `
                <button onclick="toggleSubmenu('${itemId}')" class="w-full flex items-center gap-3 px-5 py-3 hover:bg-white/10 transition-colors text-left group ${isParentActive ? 'bg-white/10 border-l-4 border-white' : ''}">
                    <i data-lucide="${item.icon}" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="flex-1 text-sm text-white">${item.label}</span>
                    <i data-lucide="chevron-down" id="arrow-${itemId}" class="w-4 h-4 flex-shrink-0 transition-transform text-white ${isParentActive ? 'rotate-180' : ''}"></i>
                </button>
                ${submenuHtml}
            `;
        } else {
            div.innerHTML = `
                <a href="${resolveUrl(item.url)}" class="w-full flex items-center gap-3 px-5 py-3 hover:bg-white/10 transition-colors text-left group ${isParentActive ? 'bg-white/10 border-l-4 border-white' : ''}">
                    <i data-lucide="${item.icon}" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="flex-1 text-sm text-white">${item.label}</span>
                </a>
            `;
        }

        nav.appendChild(div);
    });

    return sidebar;
}

function renderHeader() {
    const header = document.createElement('header');
    header.className = "bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-40";
    header.innerHTML = `
        
    `;
    return header;
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        if (overlay) overlay.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
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

// Helper to inject layout
function initLayout(activeParent, activeSubmenu) {
    const app = document.getElementById('app');

    // Sidebar Overlay
    let overlay = document.getElementById('sidebar-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'sidebar-overlay';
        overlay.className = 'fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity duration-300';
        overlay.onclick = toggleSidebar;
        document.body.appendChild(overlay);
    }

    const sidebar = renderSidebar(activeParent, activeSubmenu);
    const mainContent = document.createElement('div');
    mainContent.className = "flex-1 min-w-0 flex flex-col min-h-screen";

    const header = renderHeader();
    mainContent.appendChild(header);

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
