// Session management helper for maintaining login state
(function () {
    "use strict";

    // Check if we're in an iframe (like VS Code Simple Browser)
    const isInIframe = window !== window.top;

    // Function to ensure session cookies are properly set
    function ensureSessionCookie() {
        // Get CSRF token from meta tag
        const token = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        if (token && isInIframe) {
            // For iframe contexts, we need to be more aggressive about session management
            fetch("/test-session", {
                method: "GET",
                credentials: "include",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
            }).catch((err) => {
                console.log("Session check failed:", err);
            });
        }
    }

    // Function to check authentication status
    function checkAuthStatus() {
        fetch("/debug-session", {
            method: "GET",
            credentials: "include",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                console.log("Auth Status Debug:", data);

                // Log session data to see what keys exist
                if (data.session_data) {
                    console.log(
                        "Session keys:",
                        Object.keys(data.session_data)
                    );

                    // Look for any login_member keys
                    const memberKeys = Object.keys(data.session_data).filter(
                        (key) => key.startsWith("login_member")
                    );
                    console.log("Member login keys found:", memberKeys);
                }

                // Check for the specific issue: session data exists but auth check fails
                // Look for any login_member key dynamically
                const memberKeys = Object.keys(data.session_data || {}).filter(
                    (key) => key.startsWith("login_member")
                );
                const hasSessionData =
                    memberKeys.length > 0 && data.session_data[memberKeys[0]];
                const isAuthenticated = data.member_auth_check;

                console.log("🔐 Auth Analysis:", {
                    hasSessionData: !!hasSessionData,
                    isAuthenticated: isAuthenticated,
                    memberKeys: memberKeys,
                    currentUrl: window.location.href,
                    sessionId: data.session_id,
                });

                if (hasSessionData && !isAuthenticated) {
                    console.log(
                        "⚠️ Auth inconsistency detected (normal - middleware will fix):",
                        {
                            message:
                                "Session has member ID but Auth::guard temporarily false",
                            member_id: data.session_data[memberKeys[0]],
                            session_key: memberKeys[0],
                            session_id: data.session_id,
                            current_url: window.location.href,
                            note: "This is expected and gets fixed by middleware",
                        }
                    );
                } else if (hasSessionData && isAuthenticated) {
                    console.log("✅ Authentication working correctly");
                } else if (!hasSessionData && !isAuthenticated) {
                    console.log("ℹ️ User not logged in (normal)");
                } else if (!hasSessionData && isAuthenticated) {
                    console.warn(
                        "⚠️ Auth system says authenticated but no session data found"
                    );
                }
            })
            .catch((err) => {
                console.log("Auth check failed:", err);
            });
    }

    // Initialize session management when page loads
    document.addEventListener("DOMContentLoaded", function () {
        ensureSessionCookie();

        // Check auth status in debug mode
        if (
            window.location.hostname === "127.0.0.1" ||
            window.location.hostname === "localhost"
        ) {
            setTimeout(checkAuthStatus, 1000);
        }
    });

    // Handle navigation to ensure session persistence
    document.addEventListener("click", function (e) {
        const link = e.target.closest("a");
        if (link && link.href && link.href.startsWith(window.location.origin)) {
            ensureSessionCookie();
        }
    });
})();
