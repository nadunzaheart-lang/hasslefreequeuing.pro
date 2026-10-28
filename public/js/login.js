/*
|--------------------------------------------------------------------------
| LOGIN PAGE SCRIPT  (public/js/login.js)
|--------------------------------------------------------------------------
| 1. Show / hide password
| 2. Caps Lock warning
| 3. Loading state on the Log in button (prevents double submit)
| 4. Green success state shown briefly before redirecting after a good login
|--------------------------------------------------------------------------
*/

document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("loginForm");
    const passwordInput = document.getElementById("password");
    const toggleButton = document.getElementById("togglePassword");
    const capsHint = document.getElementById("capsHint");
    const loginButton = document.getElementById("loginButton");

    if (!form || !passwordInput) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | 1. SHOW / HIDE PASSWORD
    |--------------------------------------------------------------------------
    */

    if (toggleButton) {

        toggleButton.addEventListener("click", function () {

            const isHidden = passwordInput.type === "password";

            passwordInput.type = isHidden ? "text" : "password";

            toggleButton.setAttribute("aria-pressed", String(isHidden));
            toggleButton.setAttribute(
                "aria-label",
                isHidden ? "Hide password" : "Show password"
            );

            // Keep typing where the user left off
            passwordInput.focus();

        });

    }


    /*
    |--------------------------------------------------------------------------
    | 2. CAPS LOCK WARNING
    |--------------------------------------------------------------------------
    */

    function checkCapsLock(event) {

        if (!capsHint || typeof event.getModifierState !== "function") {
            return;
        }

        capsHint.hidden = !event.getModifierState("CapsLock");

    }

    passwordInput.addEventListener("keydown", checkCapsLock);
    passwordInput.addEventListener("keyup", checkCapsLock);

    passwordInput.addEventListener("blur", function () {

        if (capsHint) {
            capsHint.hidden = true;
        }

    });


    /*
    |--------------------------------------------------------------------------
    | 3. LOADING STATE / PREVENT DOUBLE SUBMIT
    |--------------------------------------------------------------------------
    | The browser only fires "submit" after its own validation passes
    | (required, valid email), so the spinner never shows on an invalid form.
    */

    let isSubmitting = false;

    function setLoading(loading) {

        isSubmitting = loading;

        if (!loginButton) {
            return;
        }

        loginButton.classList.toggle("is-loading", loading);
        loginButton.setAttribute("aria-busy", String(loading));

        const text = loginButton.querySelector(".btn-text");

        if (text) {
            text.textContent = loading ? "Logging in..." : "Log in";
        }

    }

    form.addEventListener("submit", function (event) {

        if (isSubmitting) {
            // Second click / Enter press while the first request is running
            event.preventDefault();
            return;
        }

        setLoading(true);

        // Send the login in the background so we can show the result
        // (green success or field errors) before leaving the page.
        // Old browsers without fetch just submit the normal way.
        if (window.fetch && window.FormData && window.URL && window.DOMParser) {
            event.preventDefault();
            sendLogin();
        }

    });

    /*
    |--------------------------------------------------------------------------
    | 4. SUCCESS STATE + FIELD ERRORS (no backend changes)
    |--------------------------------------------------------------------------
    | The form is sent with fetch(). The server answers with a redirect:
    |   - to the login page again  -> wrong email / password -> show errors
    |   - anywhere else            -> login worked -> show green, then go there
    */

    const emailInput = document.getElementById("email");
    const REDIRECT_DELAY = 900; // milliseconds the green state stays visible

    function sendLogin() {

        const loginPath = new URL(form.action, window.location.href).pathname;

        fetch(form.action, {
            method: "POST",
            body: new FormData(form),
            credentials: "same-origin",
            headers: { "Accept": "text/html" }
        })
        .then(function (response) {

            const finalPath = new URL(response.url).pathname;

            // Logged in: Laravel redirected us away from the login page
            if (response.redirected && finalPath !== loginPath) {
                showSuccess(response.url);
                return;
            }

            // Not logged in: Laravel sent us back to the login page with errors
            if (!response.ok) {
                throw new Error("Unexpected response");
            }

            return response.text().then(function (html) {
                showFieldErrors(html);
                setLoading(false);
            });

        })
        .catch(function () {
            // Anything unexpected (network problem, expired page, server error):
            // fall back to the normal form submit so the browser handles it.
            form.submit();
        });

    }

    function showSuccess(destination) {

        // Remove any old errors and turn the fields green
        form.querySelectorAll(".field").forEach(function (field) {

            field.classList.remove("has-error");
            field.classList.add("is-valid");

            const oldError = field.querySelector(".field-error");

            if (oldError) {
                oldError.remove();
            }

            const input = field.querySelector("input");

            if (input) {
                input.removeAttribute("aria-invalid");
                input.removeAttribute("aria-describedby");
            }

        });

        if (capsHint) {
            capsHint.hidden = true;
        }

        if (loginButton) {
            loginButton.classList.add("is-success");

            const text = loginButton.querySelector(".btn-text");

            if (text) {
                text.textContent = "Login successful! Redirecting...";
            }
        }

        // Keep the button locked while the green state is showing
        setTimeout(function () {
            window.location.assign(destination);
        }, REDIRECT_DELAY);

    }

    function showFieldErrors(html) {

        // Read the errors Laravel put in the returned login page
        const doc = new DOMParser().parseFromString(html, "text/html");

        if (!doc.getElementById("loginForm")) {
            throw new Error("Not the login page");
        }

        ["email", "password"].forEach(function (name) {

            const input = document.getElementById(name);
            const newInput = doc.getElementById(name);

            if (!input || !newInput) {
                return;
            }

            const field = input.closest(".field");
            const newField = newInput.closest(".field");
            const newError = newField ? newField.querySelector(".field-error") : null;

            // Clear the old error first
            const oldError = field.querySelector(".field-error");

            if (oldError) {
                oldError.remove();
            }

            field.classList.remove("has-error");
            input.removeAttribute("aria-invalid");
            input.removeAttribute("aria-describedby");

            // Then show the new one under this field
            if (newError) {
                field.classList.add("has-error");
                input.setAttribute("aria-invalid", "true");
                input.setAttribute("aria-describedby", newError.id);
                field.appendChild(document.importNode(newError, true));
            }

        });

        // Same behaviour as a normal page reload: password is cleared,
        // and the cursor goes to the field that has the problem.
        const focusPassword = doc.getElementById("password").hasAttribute("autofocus");

        passwordInput.value = "";
        (focusPassword ? passwordInput : emailInput).focus();

    }

    function resetSuccess() {

        form.querySelectorAll(".field").forEach(function (field) {
            field.classList.remove("is-valid");
        });

        if (loginButton) {
            loginButton.classList.remove("is-success");
        }

    }


    // If the user presses the browser Back button, the page can come back from
    // cache still showing "Logging in...". Reset it.
    window.addEventListener("pageshow", function (event) {

        if (event.persisted) {
            setLoading(false);
            resetSuccess();
        }

    });

});
