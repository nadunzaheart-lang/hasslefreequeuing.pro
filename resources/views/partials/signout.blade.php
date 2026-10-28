{{--
    SIGN OUT (button + confirmation modal)
    resources/views/partials/signout.blade.php

    Used by BOTH the admin layout (layouts/app.blade.php) and the staff
    dashboard (staff/dashboard.blade.php), so the button, icon and modal
    look and behave exactly the same on both pages.

    Include it ONCE per page:   @include('partials.signout')
    Needs:  public/css/signout.css  and  public/js/signout.js
--}}

<!-- SIGN OUT BUTTON -->
<button type="button" class="signout-trigger" data-signout-open aria-haspopup="dialog">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
        <polyline points="16 17 21 12 16 7"></polyline>
        <line x1="21" y1="12" x2="9" y2="12"></line>
    </svg>
    <span>Sign out</span>
</button>

<!-- CONFIRMATION MODAL -->
<dialog class="signout-dialog" id="signoutDialog" aria-labelledby="signoutTitle" aria-describedby="signoutDesc">
    <div class="signout-dialog__body">
        <div class="signout-dialog__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </div>
        <h2 id="signoutTitle">Sign out?</h2>
        <p id="signoutDesc">You will need to log in again to continue using the Registrar Queuing System.</p>
    </div>

    <div class="signout-dialog__actions">
        <!-- Cancel is first and has autofocus, so the safe choice is the default -->
        <button type="button" class="signout-btn signout-btn--cancel" data-signout-close autofocus>Cancel</button>

        <!-- Same logout link the system already used before, only now it is behind a confirmation -->
        <a href="{{ route('logout') }}" class="signout-btn signout-btn--confirm">Sign out</a>
    </div>
</dialog>
