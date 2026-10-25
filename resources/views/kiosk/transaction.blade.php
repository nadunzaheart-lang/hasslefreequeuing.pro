<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
    >

    <title>Select Transaction</title>

    <link rel="stylesheet" href="{{ asset('css/kiosk.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

</head>


<body class="kiosk-body">

<main class="kiosk-screen">


    <!-- HEADER -->
    <header class="kiosk-header">

        <div class="kiosk-brand">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="TCC Logo"
                class="kiosk-logo"
            >

            <div>
                <h1>Tagoloan Community College - Registrar</h1>
            </div>

        </div>

        <div class="kiosk-header-actions">

            <div class="step-indicator">
                STEP 2 OF 3
            </div>

            <button
                type="button"
                class="kiosk-fullscreen-toggle"
                id="kioskFullscreenBtn"
                onclick="toggleKioskFullscreen()"
                aria-label="Toggle Fullscreen"
                title="Full Screen"
            >
                <svg class="fullscreen-icon-enter" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                </svg>
                <svg class="fullscreen-icon-exit" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
                </svg>
                <span class="fullscreen-btn-text">Full Screen</span>
            </button>

        </div>

    </header>


    <!-- TRANSACTION -->
    <section class="transaction-screen">

        <div class="form-heading">

            <span class="form-label">
                REGISTRAR SERVICES
            </span>

            <h2>
                What do you need today?
            </h2>

            <p>
                Select all the transactions you need to process.
            </p>

        </div>


        <!-- TRANSACTION FORM -->
      <form
    id="transaction-form"
    action="{{ route('kiosk.transaction.store') }}"
    method="POST"
      >
         @csrf
            <div class="transaction-grid">


                <!-- ENROLLMENT VALIDATION -->
                <label class="transaction-card">

                    <input
                        type="checkbox"
                        name="transactions[]"
                        value="Enrollment Validation"
                        class="transaction-checkbox"
                    >

                    <div class="transaction-icon blue">
                        ✓
                    </div>

                    <div class="transaction-text">

                        <h3>
                            Enrollment Validation
                        </h3>

                        <p>
                            Validate enrollment and registration requirements.
                        </p>

                    </div>

                    <span class="transaction-check">
                        ✓
                    </span>

                    <span class="transaction-arrow">
                        →
                    </span>

                </label>


                <!-- DOCUMENT REQUEST -->
                <label class="transaction-card">

                    <input
                        type="checkbox"
                        name="transactions[]"
                        value="Document Request"
                        class="transaction-checkbox"
                    >

                    <div class="transaction-icon green">
                        ▤
                    </div>

                    <div class="transaction-text">

                        <h3>
                            Document Request
                        </h3>

                        <p>
                            Request school records and official documents.
                        </p>

                    </div>

                    <span class="transaction-check">
                        ✓
                    </span>

                    <span class="transaction-arrow">
                        →
                    </span>

                </label>


                <!-- REGISTRATION CONCERN -->
                <label class="transaction-card">

                    <input
                        type="checkbox"
                        name="transactions[]"
                        value="Registration Concern"
                        class="transaction-checkbox"
                    >

                    <div class="transaction-icon purple">
                        ?
                    </div>

                    <div class="transaction-text">

                        <h3>
                            Registration Concern
                        </h3>

                        <p>
                            Get assistance with registration concerns.
                        </p>

                    </div>

                    <span class="transaction-check">
                        ✓
                    </span>

                    <span class="transaction-arrow">
                        →
                    </span>

                </label>


                <!-- RECORDS / CORRECTION -->
                <label class="transaction-card">

                    <input
                        type="checkbox"
                        name="transactions[]"
                        value="Records Correction"
                        class="transaction-checkbox"
                    >

                    <div class="transaction-icon orange">
                        ✎
                    </div>

                    <div class="transaction-text">

                        <h3>
                            Records / Correction
                        </h3>

                        <p>
                            Request corrections or updates to records.
                        </p>

                    </div>

                    <span class="transaction-check">
                        ✓
                    </span>

                    <span class="transaction-arrow">
                        →
                    </span>

                </label>


                <!-- OTHER CONCERN -->
                <label class="transaction-card">

                    <input
                        type="checkbox"
                        name="transactions[]"
                        value="Other Concern"
                        class="transaction-checkbox"
                    >

                    <div class="transaction-icon gray">
                        ⋯
                    </div>

                    <div class="transaction-text">

                        <h3>
                            Other Concern
                        </h3>

                        <p>
                            For other registrar-related concerns.
                        </p>

                    </div>

                    <span class="transaction-check">
                        ✓
                    </span>

                    <span class="transaction-arrow">
                        →
                    </span>

                </label>


            </div>


            <!-- BOTTOM -->
            <div class="transaction-bottom">

                <a
                    href="{{ route('kiosk.student-info') }}"
                    class="kiosk-back-button"
                >
                    <span class="back-button-arrow" aria-hidden="true">←</span>
                    <span>Back</span>
                </a>

                <button
                    type="submit"
                    id="continue-button"
                    class="kiosk-continue-button"
                    disabled
                >
                    Continue →
                </button>


            </div>

        </form>

    </section>


    <footer class="kiosk-footer">

        <span>
            Select all the services you need.
        </span>

        <span>
            Step 2 of 3
        </span>

    </footer>


</main>


<script>

    const checkboxes = document.querySelectorAll(
        '.transaction-checkbox'
    );

    const cards = document.querySelectorAll(
        '.transaction-card'
    );

    const continueButton = document.getElementById(
        'continue-button'
    );


    function updateSelection() {

        let selected = 0;


        checkboxes.forEach((checkbox, index) => {

            const card = cards[index];

            if (checkbox.checked) {

                selected++;

                card.classList.add('selected');

            } else {

                card.classList.remove('selected');

            }

        });


        /* ENABLE / DISABLE CONTINUE */

        continueButton.disabled = selected === 0;

    }


    /* LISTEN FOR CHECKBOX CHANGES */

    checkboxes.forEach((checkbox) => {

        checkbox.addEventListener(
            'change',
            updateSelection
        );

    });


    /* PREVENT DOUBLE SUBMISSION */

    document
        .getElementById('transaction-form')
        .addEventListener('submit', function () {

            continueButton.disabled = true;

            continueButton.textContent =
                'Processing...';

        });


    /* INITIAL STATE */

    updateSelection();

</script>

<script src="{{ asset('js/kiosk.js') }}"></script>

</body>
</html>
