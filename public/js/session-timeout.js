// Session timeout handler for admin panel
(function () {
    "use strict";

    // Get session lifetime from Laravel config (in minutes)
    const sessionLifetime = parseInt(
        document.querySelector('meta[name="session-lifetime"]')?.content ||
            "120",
    );
    const sessionTimeout = sessionLifetime * 60 * 1000; // Convert to milliseconds
    const warningTime = 15 * 1000; // Show warning 15 seconds before timeout

    let activityTimer;
    let warningTimer;
    let countdownInterval;

    // Track user activity
    const activityEvents = [
        "mousedown",
        "mousemove",
        "keypress",
        "scroll",
        "touchstart",
        "click",
    ];

    function resetActivityTimer() {
        // Clear existing timers
        clearTimeout(activityTimer);
        clearTimeout(warningTimer);
        clearInterval(countdownInterval);

        // Don't set timers if we're already on the lock screen
        if (window.location.pathname.includes("/lock-screen")) {
            return;
        }

        // Set warning timer (5 minutes before timeout)
        warningTimer = setTimeout(() => {
            showTimeoutWarning();
        }, sessionTimeout - warningTime);

        // Set activity timer
        activityTimer = setTimeout(() => {
            lockScreen();
        }, sessionTimeout);

        // Update last activity time in session
        updateLastActivity();
    }

    function updateLastActivity() {
        // Send a ping to update session activity time
        if (!window.location.pathname.includes("/lock-screen")) {
            fetch("/admin/ping", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        document.querySelector('meta[name="csrf-token"]')
                            ?.content || "",
                },
                credentials: "same-origin",
            }).catch(() => {
                // Silent fail - will be caught by next activity or timeout
            });
        }
    }

    function showTimeoutWarning() {
        let remainingSeconds = Math.floor(warningTime / 1000);

        // Create a proper Filament notification manually
        const notificationId = "session-timeout-warning-" + Date.now();
        const notification = document.createElement("div");
        notification.id = notificationId;
        notification.className =
            "fi-no pointer-events-auto invisible translate-x-full overflow-hidden transition duration-300";
        notification.setAttribute("role", "alert");

        notification.innerHTML = `
            <div class="fi-no-notification flex w-80 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex w-full gap-3 p-4">
                    <div class="flex items-start gap-3">
                        <div class="fi-no-icon-ctn flex h-6 w-6 items-center justify-center rounded-full">
                            <svg class="fi-no-icon h-5 w-5 text-warning-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="grid flex-1">
                            <div class="fi-no-title text-sm font-medium text-gray-950 dark:text-white">
                                Session Timeout Warning
                            </div>
                            <div class="fi-no-body mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Your session will lock in <span id="timeout-countdown-${notificationId}" class="font-semibold text-warning-600 dark:text-warning-400">${remainingSeconds}</span> seconds due to inactivity.
                            </div>
                            <div class="fi-no-actions mt-3 flex gap-3">
                                <button type="button" class="fi-link text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400" onclick="document.getElementById('${notificationId}').remove(); sessionTimeoutHandler.resetActivity();">
                                    Stay Active
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="fi-no-close-btn -m-1.5 flex h-7 w-7 items-center justify-center rounded-md text-gray-400 transition hover:bg-gray-50 hover:text-gray-500 dark:hover:bg-white/5" onclick="document.getElementById('${notificationId}').remove(); clearInterval(window.sessionCountdownInterval);">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            </div>
        `;

        // Find or create notifications container
        let container = document.querySelector(".fi-no-notifications");
        if (!container) {
            container = document.createElement("div");
            container.className =
                "fi-no-notifications pointer-events-none fixed inset-0 top-0 z-50 mx-auto flex max-h-screen w-screen flex-col items-end justify-start gap-3 p-4";
            document.body.appendChild(container);
        }

        // Add notification
        container.appendChild(notification);

        // Trigger animation
        setTimeout(() => {
            notification.classList.remove("invisible", "translate-x-full");
        }, 10);

        // Update countdown
        const countdownElement = document.getElementById(
            `timeout-countdown-${notificationId}`,
        );
        window.sessionCountdownInterval = setInterval(() => {
            remainingSeconds--;
            if (countdownElement) {
                countdownElement.textContent = remainingSeconds;
            }
            if (remainingSeconds <= 0) {
                clearInterval(window.sessionCountdownInterval);
            }
        }, 1000);

        countdownInterval = window.sessionCountdownInterval;
    }

    function dismissWarning() {
        const notifications = document.querySelectorAll(
            '[id^="session-timeout-warning-"]',
        );
        notifications.forEach((n) => n.remove());
        clearInterval(countdownInterval);
        if (window.sessionCountdownInterval) {
            clearInterval(window.sessionCountdownInterval);
        }
    }

    // Expose resetActivityTimer for inline onclick handlers
    window.sessionTimeoutHandler = {
        resetActivity: function () {
            dismissWarning();
            resetActivityTimer();
        },
    };

    function lockScreen() {
        dismissWarning();

        // Set lock screen flag
        fetch("/admin/lock", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
            },
            credentials: "same-origin",
        })
            .then(() => {
                // Redirect to lock screen
                window.location.href = "/admin/lock-screen";
            })
            .catch(() => {
                // If request fails, redirect anyway
                window.location.href = "/admin/lock-screen";
            });
    }

    function checkSessionStatus() {
        // Periodically check if session is still valid
        fetch("/admin/session-check", {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
            },
            credentials: "same-origin",
        })
            .then((response) => {
                if (!response.ok || response.status === 419) {
                    // Session expired or invalid
                    lockScreen();
                }
            })
            .catch(() => {
                // Network error, don't lock automatically
            });
    }

    // Initialize on page load
    function init() {
        // Don't initialize on lock screen page
        if (
            window.location.pathname.includes("/lock-screen") ||
            window.location.pathname.includes("/login")
        ) {
            return;
        }

        // Start activity timer
        resetActivityTimer();

        // Add activity listeners
        activityEvents.forEach((event) => {
            document.addEventListener(event, resetActivityTimer, {
                passive: true,
            });
        });

        // Check session status every 2 minutes
        setInterval(checkSessionStatus, 2 * 60 * 1000);
    }

    // Initialize when DOM is ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
