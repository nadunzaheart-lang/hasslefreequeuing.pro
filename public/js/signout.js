/*
|--------------------------------------------------------------------------
| SIGN OUT CONFIRMATION MODAL  (public/js/signout.js)
|--------------------------------------------------------------------------
| Shared by the admin layout and the staff dashboard.
|
| - Clicking "Sign out" in the sidebar opens the modal
| - "Cancel", the Esc key, or clicking the dark area closes it
| - "Sign out" inside the modal is a normal link to the existing logout
|   route, so the logout itself is handled exactly as before
|
| The modal uses the browser's built-in <dialog> element, which already
| handles Esc, keyboard focus trapping, and returning focus to the button.
|--------------------------------------------------------------------------
*/

document.addEventListener("DOMContentLoaded", function () {

    const dialog = document.getElementById("signoutDialog");

    if (!dialog) {
        return;
    }

    const openButtons = document.querySelectorAll("[data-signout-open]");
    const closeButtons = dialog.querySelectorAll("[data-signout-close]");
    const confirmLink = dialog.querySelector(".signout-btn--confirm");


    /*
    |--------------------------------------------------------------------------
    | OPEN
    |--------------------------------------------------------------------------
    */

    function openDialog() {

        if (typeof dialog.showModal === "function") {

            dialog.showModal();

            document.documentElement.classList.add("signout-open");

            return;
        }

        // Very old browsers without <dialog>: fall back to the built-in prompt
        if (confirmLink && window.confirm("Are you sure you want to sign out?")) {
            window.location.href = confirmLink.href;
        }

    }

    openButtons.forEach(function (button) {
        button.addEventListener("click", openDialog);
    });


    /*
    |--------------------------------------------------------------------------
    | CLOSE
    |--------------------------------------------------------------------------
    */

    function closeDialog() {

        if (dialog.open) {
            dialog.close();
        }

    }

    closeButtons.forEach(function (button) {
        button.addEventListener("click", closeDialog);
    });

    // Click on the dark area outside the box
    dialog.addEventListener("click", function (event) {

        if (event.target === dialog) {
            closeDialog();
        }

    });

    // Runs for every way of closing (Cancel, Esc, backdrop click)
    dialog.addEventListener("close", function () {

        document.documentElement.classList.remove("signout-open");

    });

});
