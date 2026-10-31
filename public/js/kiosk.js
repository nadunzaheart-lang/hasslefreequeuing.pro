// Save the fullscreen request to the browser's memory
document.addEventListener('DOMContentLoaded', () => {
    const isFullscreen = localStorage.getItem('kiosk_fullscreen') === 'true';
    
    // If we were in fullscreen, try to force it back on page load
    if (isFullscreen && !document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch((err) => {
            // Browsers block this silently on page reload, so we just log it
            console.log("Auto-fullscreen blocked by browser security:", err);
        });
    }
    
    updateFullscreenIcon();
});

function toggleKioskFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().then(() => {
            localStorage.setItem('kiosk_fullscreen', 'true');
        }).catch((err) => {
            console.log(`Error attempting to enable fullscreen: ${err.message}`);
        });
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
            localStorage.removeItem('kiosk_fullscreen');
        }
    }
}

document.addEventListener('fullscreenchange', updateFullscreenIcon);

function updateFullscreenIcon() {
    const btn = document.getElementById('kioskFullscreenBtn');
    if (!btn) return;

    if (document.fullscreenElement) {
        btn.classList.add('is-fullscreen');
    } else {
        btn.classList.remove('is-fullscreen');
    }
}