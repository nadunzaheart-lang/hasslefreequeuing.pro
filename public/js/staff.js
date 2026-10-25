/* =========================================================
   STAFF DASHBOARD JAVASCRIPT
   ========================================================= */


document.addEventListener("DOMContentLoaded", function () {

    updateStaffClock();

    setInterval(updateStaffClock, 1000);

});


/* =========================================================
   CLOCK
   ========================================================= */

function updateStaffClock() {

    const timeElement =
        document.getElementById("staffTime");

    const dateElement =
        document.getElementById("staffDate");


    if (!timeElement || !dateElement) {
        return;
    }


    const now = new Date();


    timeElement.textContent =
        now.toLocaleTimeString("en-US", {
            hour: "2-digit",
            minute: "2-digit"
        });


    dateElement.textContent =
        now.toLocaleDateString("en-US", {
            weekday: "short",
            month: "short",
            day: "numeric",
            year: "numeric"
        });

}


/* =========================================================
   ACTION MESSAGE
   ========================================================= */

function showActionMessage(message) {

    const messageBox =
        document.getElementById("actionMessage");

    const messageText =
        document.getElementById("messageText");


    if (!messageBox || !messageText) {
        return;
    }


    messageText.textContent = message;

    messageBox.classList.add("show");


    setTimeout(function () {

        messageBox.classList.remove("show");

    }, 3000);

}


/* =========================================================
   CALL NEXT
   ========================================================= */

function callNext() {

    showActionMessage(
        "Queue A-106 has been called to Window 01."
    );

}


/* =========================================================
   COMPLETE SERVICE
   ========================================================= */

function completeService() {

    showActionMessage(
        "Transaction A-105 has been completed."
    );

}
