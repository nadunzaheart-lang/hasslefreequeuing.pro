/* =========================================================
   REGISTRAR QUEUING SYSTEM - KIOSK CONTROLLER
   Full Screen Manager & Seamless Flow Navigation
   ========================================================= */

(function () {
    'use strict';

    // ---------------------------------------------------------
    // 1. FULLSCREEN API ABSTRACTION
    // ---------------------------------------------------------
    function isKioskFullscreenActive() {
        return !!(
            document.fullscreenElement ||
            document.webkitFullscreenElement ||
            document.mozFullScreenElement ||
            document.msFullscreenElement
        );
    }

    function requestKioskFullscreen() {
        const docEl = document.documentElement;
        const requestFn =
            docEl.requestFullscreen ||
            docEl.webkitRequestFullscreen ||
            docEl.mozRequestFullScreen ||
            docEl.msRequestFullscreen;

        if (requestFn) {
            return requestFn.call(docEl);
        }
        return Promise.reject(new Error('Fullscreen API not supported'));
    }

    function exitKioskFullscreen() {
        const exitFn =
            document.exitFullscreen ||
            document.webkitExitFullscreen ||
            document.mozCancelFullScreen ||
            document.msExitFullscreen;

        if (exitFn) {
            return exitFn.call(document);
        }
        return Promise.reject(new Error('Fullscreen API not supported'));
    }

    window.toggleKioskFullscreen = function toggleKioskFullscreen() {
        if (!isKioskFullscreenActive()) {
            requestKioskFullscreen()
                .then(function () {
                    localStorage.setItem('kiosk_fullscreen', 'true');
                    updateFullscreenButtons(true);
                })
                .catch(function (err) {
                    console.warn('Unable to enter fullscreen mode:', err);
                });
        } else {
            exitKioskFullscreen()
                .then(function () {
                    localStorage.setItem('kiosk_fullscreen', 'false');
                    updateFullscreenButtons(false);
                })
                .catch(function (err) {
                    console.warn('Unable to exit fullscreen mode:', err);
                });
        }
    };

    function updateFullscreenButtons(isFullscreen) {
        if (document.body) {
            document.body.classList.toggle('is-kiosk-fullscreen', isFullscreen);
        }
        const buttons = document.querySelectorAll('.kiosk-fullscreen-toggle');
        buttons.forEach(function (btn) {
            const enterIcon = btn.querySelector('.fullscreen-icon-enter');
            const exitIcon = btn.querySelector('.fullscreen-icon-exit');
            const textSpan = btn.querySelector('.fullscreen-btn-text');

            if (isFullscreen) {
                btn.classList.add('is-fullscreen');
                btn.setAttribute('title', 'Exit Full Screen (Esc)');
                btn.setAttribute('aria-label', 'Exit Full Screen');
                if (enterIcon) enterIcon.style.display = 'none';
                if (exitIcon) exitIcon.style.display = 'block';
                if (textSpan) textSpan.textContent = 'Exit';
            } else {
                btn.classList.remove('is-fullscreen');
                btn.setAttribute('title', 'Full Screen');
                btn.setAttribute('aria-label', 'Full Screen');
                if (enterIcon) enterIcon.style.display = 'block';
                if (exitIcon) exitIcon.style.display = 'none';
                if (textSpan) textSpan.textContent = 'Full Screen';
            }
        });
    }

    // Standard fullscreen change event listeners
    [
        'fullscreenchange',
        'webkitfullscreenchange',
        'mozfullscreenchange',
        'MSFullscreenChange'
    ].forEach(function (evt) {
        document.addEventListener(evt, function () {
            const active = isKioskFullscreenActive();
            localStorage.setItem('kiosk_fullscreen', active ? 'true' : 'false');
            updateFullscreenButtons(active);
        });
    });

    // Auto-resume fullscreen on interaction if intended
    function setupFullscreenPersistence() {
        const shouldBeFullscreen = localStorage.getItem('kiosk_fullscreen') === 'true';

        if (shouldBeFullscreen && !isKioskFullscreenActive()) {
            var resumeHandler = function () {
                if (localStorage.getItem('kiosk_fullscreen') === 'true' && !isKioskFullscreenActive()) {
                    requestKioskFullscreen()
                        .then(function () {
                            updateFullscreenButtons(true);
                        })
                        .catch(function () {});
                }
            };

            document.addEventListener('click', resumeHandler, { once: true, capture: true });
            document.addEventListener('touchstart', resumeHandler, { once: true, capture: true, passive: true });
        }
    }

    // ---------------------------------------------------------
    // 2. KIOSK CLOCK (INDEX PAGE)
    // ---------------------------------------------------------
    var kioskClockInterval = null;

    function initKioskClock() {
        if (kioskClockInterval) {
            clearInterval(kioskClockInterval);
            kioskClockInterval = null;
        }

        var timeEl = document.getElementById('kioskTime');
        var dateEl = document.getElementById('kioskDate');

        if (!timeEl || !dateEl) {
            return;
        }

        function updateClock() {
            var now = new Date();
            timeEl.textContent = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });

            dateEl.textContent = now.toLocaleDateString('en-US', {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });
        }

        updateClock();
        kioskClockInterval = setInterval(updateClock, 1000);
    }

    // ---------------------------------------------------------
    // 3. TRANSACTION CARD SELECTION (TRANSACTION PAGE)
    // ---------------------------------------------------------
    function initTransactionCards() {
        var form = document.getElementById('transaction-form');
        var continueBtn = document.getElementById('continue-button');
        var checkboxes = document.querySelectorAll('.transaction-checkbox');
        var cards = document.querySelectorAll('.transaction-card');

        if (!form || !continueBtn || !checkboxes.length) {
            return;
        }

        if (form.dataset.kioskBound) {
            return;
        }
        form.dataset.kioskBound = 'true';

        function updateSelection() {
            var selectedCount = 0;

            checkboxes.forEach(function (checkbox, idx) {
                var card = cards[idx];
                if (checkbox.checked) {
                    selectedCount++;
                    if (card) card.classList.add('selected');
                } else {
                    if (card) card.classList.remove('selected');
                }
            });

            continueBtn.disabled = selectedCount === 0;
        }

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateSelection);
        });

        updateSelection();
    }

    // ---------------------------------------------------------
    // 4. SEAMLESS KIOSK NAVIGATION (MAINTAINS FULLSCREEN)
    // ---------------------------------------------------------
    function isKioskInternalUrl(urlString) {
        try {
            var target = new URL(urlString, window.location.origin);
            return (
                target.origin === window.location.origin &&
                target.pathname.startsWith('/kiosk')
            );
        } catch (e) {
            return false;
        }
    }

    var isNavigating = false;

    function navigateKioskPage(targetUrl, pushToHistory) {
        if (typeof pushToHistory === 'undefined') {
            pushToHistory = true;
        }

        if (isNavigating) return;
        isNavigating = true;

        fetch(targetUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (res) {
                if (!res.ok) {
                    throw new Error('Navigation failed with status: ' + res.status);
                }
                return res.text();
            })
            .then(function (html) {
                var parser = new DOMParser();
                var doc = parser.parseFromString(html, 'text/html');

                var newScreen = doc.querySelector('.kiosk-screen');
                var currentScreen = document.querySelector('.kiosk-screen');

                if (newScreen && currentScreen) {
                    currentScreen.replaceWith(newScreen);
                    document.title = doc.title || document.title;

                    if (pushToHistory) {
                        history.pushState({ kioskUrl: targetUrl }, '', targetUrl);
                    }

                    window.scrollTo(0, 0);
                    initCurrentKioskPage();
                } else {
                    window.location.href = targetUrl;
                }
            })
            .catch(function (err) {
                console.warn('Seamless navigation fell back to standard load:', err);
                window.location.href = targetUrl;
            })
            .finally(function () {
                isNavigating = false;
            });
    }

    // Intercept kiosk links
    document.addEventListener('click', function (event) {
        var link = event.target.closest('a');
        if (!link || !link.href) return;

        var hrefAttr = link.getAttribute('href');
        if (!hrefAttr || hrefAttr === '#' || hrefAttr.startsWith('#')) {
            return;
        }

        // Skip new tab or download links
        if (link.target === '_blank' || link.hasAttribute('download')) {
            return;
        }

        if (isKioskInternalUrl(link.href)) {
            // If clicking link to current exact URL without hash, avoid redundant fetch
            if (link.href === window.location.href) {
                event.preventDefault();
                return;
            }
            event.preventDefault();
            navigateKioskPage(link.href, true);
        }
    });

    // Intercept kiosk GET forms
    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!form || !form.action || !isKioskInternalUrl(form.action)) {
            return;
        }

        var method = (form.method || 'GET').toUpperCase();
        if (method !== 'GET') {
            return;
        }

        if (form.checkValidity && !form.checkValidity()) {
            return;
        }

        event.preventDefault();

        var formData = new FormData(form);
        var params = new URLSearchParams();

        for (var pair of formData.entries()) {
            params.append(pair[0], pair[1]);
        }

        var query = params.toString();
        var separator = form.action.indexOf('?') !== -1 ? '&' : '?';
        var finalUrl = form.action + (query ? separator + query : '');

        navigateKioskPage(finalUrl, true);
    });

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function () {
        if (isKioskInternalUrl(window.location.href)) {
            navigateKioskPage(window.location.href, false);
        }
    });

    // ---------------------------------------------------------
    // 5. PAGE INITIALIZATION
    // ---------------------------------------------------------
    function initCurrentKioskPage() {
        initKioskClock();
        initTransactionCards();
        updateFullscreenButtons(isKioskFullscreenActive());
        setupFullscreenPersistence();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCurrentKioskPage);
    } else {
        initCurrentKioskPage();
    }
})();
