document.addEventListener("DOMContentLoaded", function () {

    /*
    |--------------------------------------------------------------------------
    | CURRENT DATE & TIME
    |--------------------------------------------------------------------------
    */

    function updateDateTime() {

        const now = new Date();

        const dateElement = document.getElementById("currentDate");
        const timeElement = document.getElementById("currentTime");

        if (!dateElement || !timeElement) {
            return;
        }

        const dateOptions = {
            weekday: "short",
            month: "short",
            day: "numeric",
            year: "numeric"
        };

        const timeOptions = {
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit"
        };

        dateElement.textContent =
            now.toLocaleDateString("en-US", dateOptions);

        timeElement.textContent =
            now.toLocaleTimeString("en-US", timeOptions);
    }

    updateDateTime();

    setInterval(updateDateTime, 1000);


    /*
    |--------------------------------------------------------------------------
    | MOBILE SIDEBAR
    |--------------------------------------------------------------------------
    */

    const menuButton = document.getElementById("menuButton");
    const sidebar = document.getElementById("sidebar");

    if (menuButton && sidebar) {

        menuButton.addEventListener("click", function () {

            sidebar.classList.toggle("open");

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE SIDEBAR WHEN NAV ITEM IS CLICKED
    |--------------------------------------------------------------------------
    */

    const navItems = document.querySelectorAll(".nav-item");

    navItems.forEach(function (item) {

        item.addEventListener("click", function () {

            if (window.innerWidth <= 900) {
                sidebar.classList.remove("open");
            }

        });

    });

});
