<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Student Information</title>
    <link rel="stylesheet" href="{{ asset('css/kiosk.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        .kiosk-screen { height: 100vh; display: flex; flex-direction: column; }
        .form-screen { flex-grow: 1; overflow-y: auto; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px; }
        .form-heading { width: 100%; max-width: 800px; text-align: left; }

        /* Input validation feedback (red = invalid, green = valid) */
        .form-group input.is-invalid,
        .form-group input.is-invalid:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.20);
        }
        .form-group input.is-valid,
        .form-group input.is-valid:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.20);
        }
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
            <div class="step-indicator">STEP 1 OF 3</div>
            <!-- FULLSCREEN BUTTON REMOVED -->
        </div>
    </header>

    <section class="form-screen">
        <div class="form-heading">
            <span class="form-label">STUDENT INFORMATION</span>
            <h2>Tell us about yourself</h2>
            <p>Please enter your information correctly.</p>
        </div>

        <form action="{{ route('kiosk.student-info.store') }}" method="POST" class="student-form" style="width: 100%; max-width: 800px;">
            @csrf
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" id="student_id" name="student_id" placeholder="Enter your Student ID" autocomplete="off" inputmode="numeric" maxlength="8" pattern="[0-9]{8}" title="Student ID must be exactly 8 digits" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" placeholder="Enter first name" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Enter last name" autocomplete="off" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="course">Course / Program</label>
                    <select id="course" name="course" required>
                        <option value="">Select your course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="year_level">Year Level</label>
                    <select id="year_level" name="year_level" required>
                        <option value="">Select year</option>
                        <option>1st Year</option>
                        <option>2nd Year</option>
                        <option>3rd Year</option>
                        <option>4th Year</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('kiosk.index') }}" class="kiosk-back-button">← Back</a>
                <button type="submit" class="kiosk-primary-button">
                    <span>CONTINUE</span>
                    <strong>→</strong>
                </button>
            </div>
        </form>
    </section>

    <footer class="kiosk-footer">
        <span>Your information is used for queue processing only.</span>
        <span>Step 1 of 3</span>
    </footer>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var studentId = document.getElementById('student_id');
    var firstName = document.getElementById('first_name');
    var lastName  = document.getElementById('last_name');

    function setState(input, state) {
        input.classList.remove('is-valid', 'is-invalid');
        if (state) input.classList.add(state);
    }

    // sanitize: strips disallowed characters
    // isValid:  true when the value is acceptable (turns green)
    function attachValidation(input, sanitize, isValid) {
        var timer = null;

        function refresh() {
            if (input.value === '') { setState(input, null); }
            else if (isValid(input.value)) { setState(input, 'is-valid'); }
            else { setState(input, null); }
        }

        input.addEventListener('input', function () {
            var before = input.value;
            var cleaned = sanitize(before);

            if (cleaned !== before) {
                // keep the caret where the user was typing
                var caret = input.selectionStart;
                var removedBeforeCaret = before.slice(0, caret).length - sanitize(before.slice(0, caret)).length;
                input.value = cleaned;
                try { input.setSelectionRange(caret - removedBeforeCaret, caret - removedBeforeCaret); } catch (e) {}

                // blocked character: flash red, then show the real state
                setState(input, 'is-invalid');
                clearTimeout(timer);
                timer = setTimeout(refresh, 1200);
            } else {
                clearTimeout(timer);
                refresh();
            }
        });

        // leaving the field with incomplete / invalid info -> red
        input.addEventListener('blur', function () {
            clearTimeout(timer);
            if (input.value === '') { setState(input, null); }
            else if (isValid(input.value)) { setState(input, 'is-valid'); }
            else { setState(input, 'is-invalid'); }
        });
    }

    // Student ID: digits only, exactly 8
    attachValidation(
        studentId,
        function (v) { return v.replace(/\D/g, '').slice(0, 8); },
        function (v) { return /^[0-9]{8}$/.test(v); }
    );

    // First / Last name: letters and spaces only (spaces allowed for multiple names)
    function sanitizeName(v) { return v.replace(/[^\p{L}\p{M} ]/gu, ''); }
    function isValidName(v) { return v.trim() !== '' && /\p{L}/u.test(v); }

    attachValidation(firstName, sanitizeName, isValidName);
    attachValidation(lastName,  sanitizeName, isValidName);
});
</script>
</body>
</html>
