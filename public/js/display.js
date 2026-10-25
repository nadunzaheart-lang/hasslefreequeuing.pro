/* =========================================
   PUBLIC DISPLAY CLOCK
========================================= */

function updateClock() {

    const now = new Date();

    const time = now.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });

    const date = now.toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    });

    document.getElementById('current-time').textContent = time;
    document.getElementById('current-date').textContent = date;
}

updateClock();

setInterval(updateClock, 1000);


/* =========================================
   SPEAKER ANNOUNCEMENT
========================================= */

function announceQueue(queueNumber, windowNumber) {

    if (!('speechSynthesis' in window)) {
        console.log('Speech synthesis is not supported.');
        return;
    }

    const message =
        `Queue number ${queueNumber}, please proceed to window ${windowNumber}.`;

    const speech = new SpeechSynthesisUtterance(message);

    speech.rate = 0.85;
    speech.pitch = 1;
    speech.volume = 1;

    window.speechSynthesis.cancel();

    window.speechSynthesis.speak(speech);
}


/* =========================================
   DEMO ANNOUNCEMENT
========================================= */

setTimeout(() => {

    announceQueue('A-105', '01');

}, 3000);

/* =========================================
   FULL SCREEN MODE
========================================= */

function toggleFullscreen() {

    if (!document.fullscreenElement) {

        document.documentElement.requestFullscreen()
            .then(() => {
                console.log('Public Display entered fullscreen mode.');
            })
            .catch((error) => {
                console.error(
                    'Unable to enter fullscreen mode:',
                    error
                );
            });

    } else {

        document.exitFullscreen()
            .then(() => {
                console.log('Public Display exited fullscreen mode.');
            })
            .catch((error) => {
                console.error(
                    'Unable to exit fullscreen mode:',
                    error
                );
            });
    }
}


/* Update button text when fullscreen state changes */

document.addEventListener('fullscreenchange', () => {

    const button = document.getElementById('fullscreen-btn');

    if (!button) {
        return;
    }

    if (document.fullscreenElement) {

        button.textContent = '⛶ EXIT FULL SCREEN';

    } else {

        button.textContent = '⛶ FULL SCREEN';

    }

});
