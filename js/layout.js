
const menuItems = [
    { icon: 'layout-dashboard', label: 'Dashboard', hasSubmenu: false },
    { icon: 'file-text', label: 'Application Input', hasSubmenu: true, submenu: ['Pemol', 'Merchant', 'CC Reguler', 'Mobile Sales', 'Corporate', 'Smart Cash (SC)', 'Personal Loan (PL)'] },
    { icon: 'database', label: 'Data Decision', hasSubmenu: true, submenu: ['PEMOL', 'Merchant', 'Credit Card (CC)', 'Corporate', 'Smart Cash (SC)', 'Personal Loan (PL)'] },
    { icon: 'inbox', label: 'Incoming', hasSubmenu: true, submenu: ['Mobile Sales', 'Pemol', 'TM CC', 'TM SC'] },
    { icon: 'check-square', label: 'Application Check', hasSubmenu: true, submenu: ['Pemol', 'Merchant', 'Credit Card (CC)', 'Corporate', 'Smart Cash (SC)', 'Personal Loan (PL)'] },
    { icon: 'trending-up', label: 'My Performance', hasSubmenu: false },
    { icon: 'file-plus', label: 'Data Addendum', hasSubmenu: false },
    { icon: 'users', label: 'Team Performance', hasSubmenu: false },
    { icon: 'info', label: 'Sales Information', hasSubmenu: false },
    { icon: 'user-circle', label: 'Candidate Info', hasSubmenu: true, submenu: ['Candidate Details', 'Approval', 'History'] },
    { icon: 'send', label: 'Request to HRD', hasSubmenu: true, submenu: ['Exit', 'Restruct', 'Level', 'Reaktif'] },
    { icon: 'check-circle', label: 'Approval', hasSubmenu: true, submenu: ['Restruct', 'Reaktif', 'Promotion'] },
    { icon: 'map-pin', label: 'Check Postal Code', hasSubmenu: false },
    { icon: 'copy', label: 'Duplicate Check', hasSubmenu: false },
    { icon: 'monitor', label: 'Monitoring', hasSubmenu: false },
];

function renderSidebar(activeItem = 'Dashboard') {
    const sidebar = document.createElement('aside');
    sidebar.className = `fixed lg:sticky top-0 left-0 h-screen w-[260px] bg-[#3B6EC2] text-white flex flex-col overflow-y-auto transform transition-transform duration-300 z-50 -translate-x-full lg:translate-x-0`;
    sidebar.id = 'sidebar';

    // Mobile Close Button
    sidebar.innerHTML = `
        <button onclick="toggleSidebar()" class="lg:hidden absolute top-4 right-4 text-white">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
        <div class="px-5 py-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <i data-lucide="menu" class="w-6 h-6"></i>
                <div class="text-xl font-semibold">Dashboard</div>
            </div>
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

    menuItems.forEach(item => {
        const div = document.createElement('div');
        const isActive = activeItem === item.label;
        const isExpanded = false; // Default closed

        // Simple sanitization for IDs
        const itemId = item.label.replace(/\s+/g, '-').toLowerCase();

        let submenuHtml = '';
        if (item.hasSubmenu && item.submenu) {
            submenuHtml = `
                <div id="submenu-${itemId}" class="bg-white/5 hidden">
                    ${item.submenu.map(subItem => `
                        <button class="w-full pl-14 pr-5 py-2.5 hover:bg-white/10 transition-colors text-left text-sm text-white">
                            ${subItem}
                        </button>
                    `).join('')}
                </div>
            `;
        }

        div.innerHTML = `
            <button onclick="toggleSubmenu('${itemId}')" class="w-full flex items-center gap-3 px-5 py-3 hover:bg-white/10 transition-colors text-left group ${isActive ? 'bg-white/10 border-l-4 border-white' : ''}">
                <i data-lucide="${item.icon}" class="w-5 h-5 flex-shrink-0"></i>
                <span class="flex-1 text-sm text-white">${item.label}</span>
                ${item.hasSubmenu ? `<i data-lucide="chevron-down" id="arrow-${itemId}" class="w-4 h-4 flex-shrink-0 transition-transform text-white"></i>` : ''}
            </button>
            ${submenuHtml}
        `;
        nav.appendChild(div);
    });

    return sidebar;
}

function renderHeader() {
    const header = document.createElement('header');
    header.className = "bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-40";
    header.innerHTML = `
        <div class="flex items-center gap-4">
             <button onclick="toggleSidebar()" class="lg:hidden text-gray-600">
                <i data-lucide="menu" class="w-6 h-6"></i>
             </button>
            <img src="https://placehold.co/150x40?text=M-Sales+Logo" alt="M-Sales Logo" class="h-10" />
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <div class="text-sm font-medium text-gray-900">Budi Dharma</div>
                <div class="text-xs text-gray-500">BSH</div>
            </div>
            <div class="w-10 h-10 bg-[#3B6EC2] rounded-full flex items-center justify-center text-white font-medium">
                BD
            </div>
            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-600"></i>
        </div>
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
function initLayout(activeItem) {
    const app = document.getElementById('app');

    // Sidebar Overlay
    const overlay = document.createElement('div');
    overlay.id = 'sidebar-overlay';
    overlay.className = 'fixed inset-0 bg-black/50 z-40 hidden lg:hidden';
    overlay.onclick = toggleSidebar;
    document.body.appendChild(overlay);

    const sidebar = renderSidebar(activeItem);
    const mainContent = document.createElement('div');
    mainContent.className = "flex-1 min-w-0 flex flex-col min-h-screen";

    // Move existing content into mainContent
    while (app.firstChild) {
        mainContent.appendChild(app.firstChild);
    }

    const wrapper = document.createElement('div');
    wrapper.className = "flex min-h-screen bg-gray-50";

    wrapper.appendChild(sidebar);

    // Header is top of main content
    const header = renderHeader();
    mainContent.insertBefore(header, mainContent.firstChild);

    wrapper.appendChild(mainContent);

    app.innerHTML = '';
    app.appendChild(wrapper);

    lucide.createIcons();
}
