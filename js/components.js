
function renderCircularProgress(percentage, label, size = 100, strokeWidth = 8) {
    const radius = (size - strokeWidth) / 2;
    const circumference = 2 * Math.PI * radius;
    const offset = circumference - (percentage / 100) * circumference;

    return `
    <div class="flex flex-col items-center gap-2">
      <div class="relative" style="width: ${size}px; height: ${size}px;">
        <svg width="${size}" height="${size}" class="transform -rotate-90">
          <circle
            cx="${size / 2}"
            cy="${size / 2}"
            r="${radius}"
            fill="none"
            stroke="#E5E7EB"
            stroke-width="${strokeWidth}"
          ></circle>
          <circle
            cx="${size / 2}"
            cy="${size / 2}"
            r="${radius}"
            fill="none"
            stroke="#1E5BA8"
            stroke-width="${strokeWidth}"
            stroke-dasharray="${circumference}"
            stroke-dashoffset="${offset}"
            stroke-linecap="round"
            class="transition-all duration-500"
          ></circle>
        </svg>
        <div class="absolute inset-0 flex items-center justify-center">
          <span class="text-xl font-bold text-[#1E5BA8]">${percentage}%</span>
        </div>
      </div>
      <p class="text-xs text-gray-600 text-center">${label}</p>
    </div>
    `;
}

function renderMetricCard(value, label, sublabel, type = 'primary') {
    const textColor = type === 'success' ? 'text-[#00BCD4]' : 'text-[#1E5BA8]';
    return `
    <div class="bg-white rounded-lg p-4 flex flex-col items-center justify-center min-h-[100px] border border-gray-200">
      <p class="text-xs text-gray-600 text-center mb-1">${label}</p>
      <p class="text-4xl font-bold ${textColor}">
        ${value}
      </p>
      ${sublabel ? `<p class="text-xs text-gray-500 mt-1">${sublabel}</p>` : ''}
    </div>
    `;
}

function renderPerformanceSection(title, metrics, barData, bgColor = '#1E5BA8') {
    const maxValue = Math.max(...barData.map(d => d.value));
    const month = 'December 2025';

    const metricsHtml = metrics.map(metric => {
        if (metric.type === 'circular') {
            return `
            <div class="bg-white rounded-lg p-3 flex items-center justify-center">
                ${renderCircularProgress(metric.value || 0, metric.label, 80, 6)}
            </div>`;
        } else {
            return `
            <div>
                ${renderMetricCard(metric.cardValue || '', metric.label, metric.sublabel, metric.cardType)}
            </div>`;
        }
    }).join('');

    const barsHtml = barData.map(item => {
        const heightPercentage = (item.value / maxValue) * 100;
        let barContent = '';

        if (item.segments && item.segments.length > 0) {
            barContent = item.segments.map((segment, segIndex) => {
                const segmentHeight = (segment.value / item.value) * 100;
                const radiusStyle = segIndex === item.segments.length - 1 ? 'border-top-left-radius: 8px; border-top-right-radius: 8px;' : '';
                return `
                <div 
                    class="w-full flex items-center justify-center relative"
                    style="height: ${segmentHeight}%; background-color: ${segment.color}; ${radiusStyle}"
                >
                    <span class="text-white text-xs font-semibold">${segment.value}</span>
                </div>`;
            }).join('');

            barContent = `<div class="w-full max-w-[60px] flex flex-col-reverse" style="height: ${heightPercentage}%; min-height: 40px">${barContent}</div>`;
        } else {
            barContent = `
            <div 
                class="w-full max-w-[60px] rounded-t-lg transition-all duration-500 flex items-start justify-center pt-3"
                style="height: ${heightPercentage}%; background-color: ${bgColor}; min-height: 40px"
            >
                <span class="text-white font-semibold text-sm">${item.value}</span>
            </div>`;
        }

        return `
        <div class="flex-1 flex flex-col items-center gap-2">
            <div class="relative w-full flex items-end justify-center h-48">
                ${barContent}
            </div>
            <div class="text-center">
                <p class="text-xs text-gray-700 leading-tight">${item.label}</p>
                 ${item.sublabel ? `<p class="text-xs text-gray-500 leading-tight">${item.sublabel}</p>` : ''}
            </div>
        </div>`;
    }).join('');

    return `
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
      <!-- Header with metrics -->
      <div class="p-4 lg:p-6" style="background: linear-gradient(135deg, ${bgColor} 0%, ${bgColor}dd 100%)">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-white font-semibold text-lg">${title}</h3>
          <button class="flex items-center gap-2 bg-white rounded-full px-4 py-1.5 text-sm">
            ${month}
            <i data-lucide="chevron-down" class="w-4 h-4"></i>
          </button>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
          ${metricsHtml}
        </div>
      </div>

      <!-- Bar Chart -->
      <div class="p-4 lg:p-6 bg-gray-50">
        <div class="flex items-center justify-between mb-4">
          <h4 class="font-semibold text-gray-800">${title}</h4>
          <button class="flex items-center gap-2 border border-gray-300 rounded px-3 py-1 text-sm">
            ${month}
            <i data-lucide="chevron-down" class="w-4 h-4"></i>
          </button>
        </div>

        <div class="flex items-end justify-between gap-2 lg:gap-4 h-64">
          ${barsHtml}
        </div>
      </div>
    </div>
    `;
}
