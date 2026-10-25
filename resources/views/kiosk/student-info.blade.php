<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Student Information</title>
    <link rel="stylesheet" href="{{ asset('css/kiosk.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
</head>
<body class="kiosk-body">

<main class="kiosk-screen">
    <header class="kiosk-header">
        <div class="kiosk-brand">
            <img src="{{ asset('images/logo.png') }}" alt="TCC Logo" class="kiosk-logo">
            <div>
                <h1>Tagoloan Community College - Registrar</h1>
            </div>
        </div>
        <div class="kiosk-header-actions">
            <div class="step-indicator">STEP 1 OF 3</div>
            <button type="button" class="kiosk-fullscreen-toggle" id="kioskFullscreenBtn" onclick="toggleKioskFullscreen()" aria-label="Toggle Fullscreen" title="Full Screen">
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

    <section class="form-screen">
        <div class="form-heading">
            <span class="form-label">STUDENT INFORMATION</span>
            <h2>Tell us about yourself</h2>
            <p>Please enter your information correctly.</p>
        </div>

        <form action="{{ route('kiosk.student-info.store') }}" method="POST" class="student-form">
            @csrf
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" id="student_id" name="student_id" placeholder="Enter your Student ID" autocomplete="off" required>
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

<script src="{{ asset('js/kiosk.js') }}"></script>
</body>
</html>