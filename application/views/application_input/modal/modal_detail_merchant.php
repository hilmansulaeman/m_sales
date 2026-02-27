<!-- Modal View Detail Merchant -->
<div id="modalDetail" class="hidden fixed inset-0 z-[10000] overflow-y-auto">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModalDetail()"></div>

  <!-- Modal Content -->
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="relative w-full max-w-4xl bg-white rounded-[32px] shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
      
      <!-- Modal Header -->
      <div class="px-8 pt-8 pb-4 flex items-center justify-between bg-white">
        <h2 id="modalDetailTitle" class="text-3xl font-bold text-[#1E293B]">Detail</h2>
        <button onclick="closeModalDetail()" class="p-2 hover:bg-gray-100 rounded-full transition-colors text-gray-400 hover:text-gray-600">
          <i data-lucide="x" class="w-8 h-8"></i>
        </button>
      </div>

      <!-- Tabs Area -->
      <div class="px-8 mb-8">
        <div class="inline-flex bg-gray-100/50 p-1 rounded-2xl">
          <button id="tabDataInput" onclick="switchDetailTab('data-input')" class="px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 bg-[#2463B4] text-white shadow-sm">
            Data Input
          </button>
          <button id="tabAppProcessing" onclick="switchDetailTab('app-processing')" class="px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-gray-400 hover:text-gray-600">
            App Processing
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="px-8 pb-10">
        <!-- Content Area -->
        <div id="modalDetailContent">
             <!-- Skeleton Loading / Initial State -->
             <div class="flex items-center justify-center py-20">
                <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
             </div>
        </div>
      </div>
    </div>
  </div>
</div>
