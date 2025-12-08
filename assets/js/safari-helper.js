/* 
   BROWSER CONSISTENCY HELPER (JS)
   -----------------------------------------
   Memastikan layout konsisten di:
   - Safari (Desktop & Mobile iOS)
   - Chrome / Edge
   - WebView (ChatGPT Atlas, dll)
   
   Dependencies: 
   - assets/css/browser-consistency.css (Optional but recommended)
*/

(function (window, document) {
    'use strict';

    // Namespace
    window.BrowserConsistency = window.BrowserConsistency || {};

    // ==============================
    // 1. ADVANCED BROWSER DETECTION
    // ==============================
    var ua = navigator.userAgent.toLowerCase();
    var docEl = document.documentElement;
    var body = document.body;

    var isIOS = /iphone|ipad|ipod/.test(ua);
    var isAndroid = /android/.test(ua);
    var isEdge = /edg\//.test(ua);
    var isChrome = /chrome|crios/.test(ua) && !isEdge;
    var isFirefox = /firefox|fxios/.test(ua);
    var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent); // Robust Safari detect

    // Detection for specific WebViews or ChatGPT Atlas
    // Atlas often looks like standard iOS WebView (AppleWebKit + Mobile) but might contain specific strings if customized
    // We treat generic WebViews as "Atlas-compatible" targets.
    var isWebview = (function () {
        var rules = [
            'wv',                   // Common Android WebView
            'neva',                 // LG WebOS
            'chatgpt',              // OpenAI Apps
            'atlas'                 // Explicit Atlas
        ];
        // iOS WebView check: has window.webkit but maybe standalone
        var isIOSWebview = isIOS && (!!window.webkit && !!window.webkit.messageHandlers);
        // User agent string matches
        var isUAMatch = rules.some(function (r) { return ua.indexOf(r) > -1; });

        return isIOSWebview || isUAMatch;
    })();

    function applyBodyClasses() {
        var classes = [];
        if (isIOS) classes.push('is-ios');
        if (isAndroid) classes.push('is-android');
        if (isEdge) classes.push('is-edge');
        if (isChrome) classes.push('is-chrome');
        if (isSafari) classes.push('is-safari');
        if (isFirefox) classes.push('is-firefox');
        if (isWebview) {
            classes.push('is-webview');
            classes.push('is-atlas'); // Assumption for consistency
        }

        // Add classes to body
        classes.forEach(function (cls) {
            document.body.classList.add(cls);
        });
    }

    // ==============================
    // 2. VIEWPORT HEIGHT FIX (--app-height)
    // ==============================
    // Memperbaiki masalah 100vh di Safari Mobile & Chrome Mobile (Address bar)
    function setAppHeight() {
        var vh = window.innerHeight;
        docEl.style.setProperty('--app-height', vh + 'px');

        // Debug
        // console.log('App Height updated:', vh);
    }

    // ==============================
    // 3. SCROLL LOCKING HELPER
    // ==============================
    var scrollLockState = {
        active: false,
        scrollTop: 0
    };

    function lockScroll(enable) {
        if (enable) {
            if (scrollLockState.active) return;
            scrollLockState.scrollTop = window.pageYOffset || docEl.scrollTop;
            scrollLockState.active = true;

            body.classList.add('lock-scroll');
            body.style.top = '-' + scrollLockState.scrollTop + 'px';
        } else {
            if (!scrollLockState.active) return;
            body.classList.remove('lock-scroll');
            body.style.top = '';
            window.scrollTo(0, scrollLockState.scrollTop);
            scrollLockState.active = false;
        }
    }

    // ==============================
    // 4. FLOATING ELEMENT POSITIONER
    // ==============================
    /**
     * Menghitung posisi element relatif terhadap viewport agar tidak terpotong.
     * @param {HTMLElement} triggerEl - Element pemicu (tombol/icon)
     * @param {HTMLElement} targetEl - Element yang akan muncul (popover/modal)
     * @param {Object} options - { placement: 'bottom', gap: 5, collision: 'flip' }
     */
    function positionFloatingElement(triggerEl, targetEl, options) {
        if (!triggerEl || !targetEl) return;

        var opts = options || {};
        var placement = opts.placement || 'bottom';
        var gap = opts.gap || 5;

        var rect = triggerEl.getBoundingClientRect();
        var targetRect = targetEl.getBoundingClientRect();

        var top, left;

        // Basic positioning logic
        switch (placement) {
            case 'top':
                top = rect.top - targetRect.height - gap;
                left = rect.left + (rect.width / 2) - (targetRect.width / 2);
                break;
            case 'bottom': // center
                top = rect.bottom + gap;
                left = rect.left + (rect.width / 2) - (targetRect.width / 2);
                break;
            case 'bottom-start': // left align
                top = rect.bottom + gap;
                left = rect.left;
                break;
            case 'bottom-end': // right align
                top = rect.bottom + gap;
                left = rect.right - targetRect.width;
                break;
            case 'left':
                top = rect.top + (rect.height / 2) - (targetRect.height / 2);
                left = rect.left - targetRect.width - gap;
                break;
            case 'right':
                top = rect.top + (rect.height / 2) - (targetRect.height / 2);
                left = rect.right + gap;
                break;
            default: // default to bottom-start if unknown
                top = rect.bottom + gap;
                left = rect.left;
        }

        // Viewport correction (Collision detection - simple clamp)
        var vw = Math.max(docEl.clientWidth || 0, window.innerWidth || 0);
        var vh = Math.max(docEl.clientHeight || 0, window.innerHeight || 0);

        // Clamp Left/Right
        if (left < 5) left = 5;
        if (left + targetRect.width > vw - 5) {
            left = vw - targetRect.width - 5;
        }

        // Clamp Top/Bottom (Flip strategy could be better but clamp is safer for simple dropdowns)
        if (top < 5) top = 5;

        // Apply (Fixed position is usually safest for modals/popovers to avoid parent overflow clipping)
        targetEl.style.position = 'fixed';
        targetEl.style.top = top + 'px';
        targetEl.style.left = left + 'px';
        targetEl.style.zIndex = '9999'; // Ensure top
    }

    // ==============================
    // 5. Z-INDEX FIX FOR SAFARI
    // ==============================
    // Safari kadang punya masalah stacking context jika parent punya transform/opacity.
    // Helper ini memindahkan element ke body jika perlu.
    function movePayloadToBody(element) {
        if (element && element.parentNode !== document.body) {
            document.body.appendChild(element);
        }
    }


    // ==============================
    // INITIALIZATION
    // ==============================
    function init() {
        applyBodyClasses();
        setAppHeight();

        // Listeners for dynamic sizing
        window.addEventListener('resize', setAppHeight);
        window.addEventListener('orientationchange', function () {
            setTimeout(setAppHeight, 100);
        });

        console.log('BrowserConsistency loaded. Environment:', {
            isIOS, isSafari, isWebview, isAndroid, isChrome
        });
    }

    // Expose API
    window.BrowserConsistency = {
        isSafari: isSafari,
        isIOS: isIOS,
        isWebview: isWebview,
        lockScroll: lockScroll,
        positionFloatingElement: positionFloatingElement,
        appendToBody: movePayloadToBody,
        refreshLayout: setAppHeight
    };

    // Run Init when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})(window, document);
