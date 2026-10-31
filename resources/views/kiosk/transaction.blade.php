<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Select Transaction</title>
    <link rel="stylesheet" href="{{ asset('css/kiosk.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        .kiosk-screen { height: 100vh; display: flex; flex-direction: column; }
        .transaction-screen { flex-grow: 1; overflow-y: auto; display: flex; flex-direction: column; padding: 20px; }
    </style>
</head>
<body class="kiosk-body">

<main class="kiosk-screen">
    <header class="kiosk-header">
        <div class="kiosk-brand">
            <img src="{{ asset('images/logo.png') }}" alt="TCC Logo" class="kiosk-logo">
            <div><h1>Tagoloan Community College - Registrar</h1></div>
        </div>
        <div class="kiosk-header-actions">
            <div class="step-indicator">STEP 2 OF 3</div>
            <!-- FULLSCREEN BUTTON REMOVED -->
        </div>
    </header>

    <section class="transaction-screen">
        <div class="form-heading transaction-heading" style="text-align: left; margin-bottom: 20px;">
            <span class="form-label">REGISTRAR SERVICES</span>
            <h2>What do you need today?</h2>
            <p>Select all the transactions you need to process.</p>
        </div>

        <form id="transaction-form" class="transaction-form" action="{{ route('kiosk.transaction.store') }}" method="POST">
            @csrf
            <div class="transaction-grid">
                <label class="transaction-card">
                    <input type="checkbox" name="transactions[]" value="Enrollment Validation" class="transaction-checkbox">
                    <div class="transaction-icon blue">✓</div>
                    <div class="transaction-text"><h3>Enrollment Validation</h3><p>Validate enrollment and registration requirements.</p></div>
                    <span class="transaction-check">✓</span><span class="transaction-arrow">→</span>
                </label>
                <label class="transaction-card">
                    <input type="checkbox" name="transactions[]" value="Document Request" class="transaction-checkbox">
                    <div class="transaction-icon green">▤</div>
                    <div class="transaction-text"><h3>Document Request</h3><p>Request school records and official documents.</p></div>
                    <span class="transaction-check">✓</span><span class="transaction-arrow">→</span>
                </label>
                <label class="transaction-card">
                    <input type="checkbox" name="transactions[]" value="Registration Concern" class="transaction-checkbox">
                    <div class="transaction-icon purple">?</div>
                    <div class="transaction-text"><h3>Registration Concern</h3><p>Get assistance with registration concerns.</p></div>
                    <span class="transaction-check">✓</span><span class="transaction-arrow">→</span>
                </label>
                <label class="transaction-card">
                    <input type="checkbox" name="transactions[]" value="Records Correction" class="transaction-checkbox">
                    <div class="transaction-icon orange">✎</div>
                    <div class="transaction-text"><h3>Records / Correction</h3><p>Request corrections or updates to records.</p></div>
                    <span class="transaction-check">✓</span><span class="transaction-arrow">→</span>
                </label>
                <label class="transaction-card">
                    <input type="checkbox" name="transactions[]" value="Other Concern" class="transaction-checkbox">
                    <div class="transaction-icon gray">⋯</div>
                    <div class="transaction-text"><h3>Other Concern</h3><p>For other registrar-related concerns.</p></div>
                    <span class="transaction-check">✓</span><span class="transaction-arrow">→</span>
                </label>

                <!-- Shown only when "Other Concern" is selected -->
                <div class="other-concern-panel" id="other-concern-panel" hidden>
                    <label for="other-concern-input" class="other-concern-label">What service do you need?</label>
                    <input type="text" id="other-concern-input" class="other-concern-input" maxlength="100" autocomplete="off" placeholder="Type your specific concern here...">
                </div>
            </div>

            <div class="transaction-bottom">
                <a href="{{ route('kiosk.student-info') }}" class="kiosk-back-button">
                    <span class="back-button-arrow" aria-hidden="true">←</span><span>Back</span>
                </a>
                <button type="submit" id="continue-button" class="kiosk-continue-button" disabled>Continue →</button>
            </div>
        </form>
    </section>

    <footer class="kiosk-footer">
        <span>Select all the services you need.</span>
        <span>Step 2 of 3</span>
    </footer>
</main>

<script>
    const checkboxes = document.querySelectorAll('.transaction-checkbox');
    const cards = document.querySelectorAll('.transaction-card');
    const continueButton = document.getElementById('continue-button');

    // "Other Concern" free-text field
    const otherCheckbox = document.querySelector('.transaction-checkbox[value="Other Concern"]');
    const otherPanel = document.getElementById('other-concern-panel');
    const otherInput = document.getElementById('other-concern-input');

    // Sends the typed text along with the existing transactions[] value,
    // e.g. "Other Concern: Request for diploma replacement"
    function syncOtherValue() {
        const text = otherInput.value.trim();
        otherCheckbox.value = text ? 'Other Concern: ' + text : 'Other Concern';
    }

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

        const otherChecked = otherCheckbox.checked;
        otherPanel.hidden = !otherChecked;
        syncOtherValue();

        // If "Other Concern" is chosen, the user must describe it first
        const otherMissing = otherChecked && otherInput.value.trim() === '';
        continueButton.disabled = selected === 0 || otherMissing;
    }

    otherCheckbox.addEventListener('change', () => {
        if (otherCheckbox.checked) otherInput.focus();
    });
    otherInput.addEventListener('input', updateSelection);

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', updateSelection);
    });

    document.getElementById('transaction-form').addEventListener('submit', function () {
        continueButton.disabled = true;
        continueButton.textContent = 'Processing...';
    });

    updateSelection();
</script>
</body>
</html>
