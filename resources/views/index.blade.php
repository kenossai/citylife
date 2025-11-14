<x-app-layout>
    @section('title', 'Home')
    @section('meta_description', 'Welcome to CityLife Church. Join us for worship, community, and spiritual growth. Discover our events, courses, and volunteer opportunities.')

    <!-- Welcome Audio -->
    <audio id="welcome-audio" preload="auto" autoplay loop>
        <source src="{{ asset('assets/audio/welcome-sound.mp3') }}" type="audio/mpeg">
        <source src="{{ asset('assets/audio/welcome-sound.ogg') }}" type="audio/ogg">
        Your browser does not support the audio element.
    </audio>

    <!-- Audio Control Button -->
    <div id="audio-control" class="audio-control">
        <button id="audio-toggle" class="audio-toggle" title="Toggle Welcome Sound">
            <i class="fas fa-volume-up" id="audio-icon"></i>
        </button>
    </div>

    <x-hero-banner :banners="$banners" />
    <x-about :aboutPage="$aboutPage" />
    <x-becoming :section="$section" />
    {{-- <x-courses /> --}}
    <x-events :events="$events" />
    {{-- <x-support /> --}}
    <x-volunteer />
    <!-- Welcome Sound Styles -->
    <style>
        .audio-control {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            background: rgba(124, 58, 237, 0.9);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .audio-control:hover {
            background: rgba(124, 58, 237, 1);
            transform: scale(1.1);
        }

        .audio-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            transition: color 0.3s ease;
        }

        .audio-toggle:hover {
            color: #f0f9ff;
        }

        .audio-toggle.muted {
            opacity: 0.6;
        }

        .audio-control.hidden {
            opacity: 0;
            pointer-events: none;
        }

        /* Welcome notification */
        .welcome-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
            z-index: 1001;
            font-size: 14px;
            font-weight: 500;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.5s ease;
        }

        .welcome-notification.show {
            opacity: 1;
            transform: translateX(0);
        }

        .welcome-notification .close-btn {
            margin-left: 10px;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            opacity: 0.8;
        }

        .welcome-notification .close-btn:hover {
            opacity: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .audio-control {
                bottom: 15px;
                left: 15px;
                width: 45px;
                height: 45px;
            }

            .audio-toggle {
                font-size: 16px;
            }

            .welcome-notification {
                top: 15px;
                right: 15px;
                padding: 10px 16px;
                font-size: 13px;
            }
        }
    </style>

    <!-- Welcome Sound JavaScript -->
    <script>
        // Start trying to play audio immediately
        let audio = null;
        let audioReady = false;
        let hasPlayed = false;

        // Multiple autoplay strategies
        function initializeAudio() {
            audio = document.getElementById('welcome-audio');
            const audioToggle = document.getElementById('audio-toggle');
            const audioIcon = document.getElementById('audio-icon');
            const audioControl = document.getElementById('audio-control');

            // Check if user has previously disabled sound
            const soundEnabled = localStorage.getItem('welcomeSoundEnabled') !== 'false';
            hasPlayed = sessionStorage.getItem('welcomeSoundPlayed') === 'true';

            // Set initial state
            if (!soundEnabled) {
                audioIcon.className = 'fas fa-volume-mute';
                audioToggle.classList.add('muted');
                audio.muted = true;
                return;
            }

            // Set audio properties for autoplay
            audio.volume = 0.4; // Slightly higher volume for immediate playback
            audio.muted = false; // Unmute for actual playback
            audioReady = true;

            // Strategy 1: Immediate autoplay attempt
            tryAutoplay();

            // Strategy 2: Try again after DOM is fully loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', tryAutoplay);
            }

            // Strategy 3: Try after window load (all resources loaded)
            window.addEventListener('load', tryAutoplay);

            // Strategy 4: Try on first user interaction (backup)
            const userInteractionEvents = ['click', 'touchstart', 'scroll', 'keydown', 'mousemove'];
            const onFirstInteraction = function() {
                if (!hasPlayed && soundEnabled) {
                    tryAutoplay();
                }
                // Remove all listeners after first interaction
                userInteractionEvents.forEach(event => {
                    document.removeEventListener(event, onFirstInteraction);
                });
            };

            userInteractionEvents.forEach(event => {
                document.addEventListener(event, onFirstInteraction, { once: true });
            });

            // Audio control toggle
            audioToggle.addEventListener('click', function() {
                const currentlyEnabled = localStorage.getItem('welcomeSoundEnabled') !== 'false';

                if (currentlyEnabled) {
                    // Disable sound
                    localStorage.setItem('welcomeSoundEnabled', 'false');
                    audioIcon.className = 'fas fa-volume-mute';
                    audioToggle.classList.add('muted');
                    audio.pause();
                    audio.muted = true;
                } else {
                    // Enable sound
                    localStorage.setItem('welcomeSoundEnabled', 'true');
                    audioIcon.className = 'fas fa-volume-up';
                    audioToggle.classList.remove('muted');
                    audio.muted = false;
                    // Immediately try to play when re-enabled
                    sessionStorage.removeItem('welcomeSoundPlayed');
                    hasPlayed = false;
                    tryAutoplay();
                }
            });

            // Audio control hover effects
            audioControl.addEventListener('mouseenter', function() {
                this.style.opacity = '1';
            });

            audioControl.addEventListener('mouseleave', function() {
                setTimeout(() => {
                    if (!this.matches(':hover')) {
                        this.style.opacity = '0.8';
                    }
                }, 2000);
            });
        }

        // Aggressive autoplay function
        function tryAutoplay() {
            if (!audioReady || !audio || hasPlayed) return;

            const soundEnabled = localStorage.getItem('welcomeSoundEnabled') !== 'false';
            if (!soundEnabled) return;

            // Multiple autoplay attempts
            const playAttempts = [
                // Attempt 1: Direct play
                () => audio.play(),

                // Attempt 2: Reset and play
                () => {
                    audio.currentTime = 0;
                    audio.load();
                    return audio.play();
                },

                // Attempt 3: Clone and play (bypass some restrictions)
                () => {
                    const clonedAudio = audio.cloneNode();
                    clonedAudio.volume = audio.volume;
                    return clonedAudio.play();
                }
            ];

            let attemptIndex = 0;

            function attemptPlay() {
                if (attemptIndex >= playAttempts.length || hasPlayed) return;

                const attempt = playAttempts[attemptIndex];
                const playPromise = attempt();

                if (playPromise && typeof playPromise.then === 'function') {
                    playPromise
                        .then(() => {
                            console.log(`✅ Welcome sound played successfully (attempt ${attemptIndex + 1})`);
                            hasPlayed = true;
                            sessionStorage.setItem('welcomeSoundPlayed', 'true');
                            showWelcomeNotification();
                        })
                        .catch(error => {
                            console.log(`❌ Autoplay attempt ${attemptIndex + 1} failed:`, error);
                            attemptIndex++;

                            // Try next attempt after short delay
                            setTimeout(attemptPlay, 100);
                        });
                } else {
                    // Fallback for browsers that don't return a promise
                    setTimeout(() => {
                        if (!audio.paused) {
                            console.log(`✅ Welcome sound played successfully (attempt ${attemptIndex + 1})`);
                            hasPlayed = true;
                            sessionStorage.setItem('welcomeSoundPlayed', 'true');
                            showWelcomeNotification();
                        } else {
                            attemptIndex++;
                            setTimeout(attemptPlay, 100);
                        }
                    }, 100);
                }
            }

            attemptPlay();
        }

        // Function to show welcome notification
        function showWelcomeNotification(message = 'Welcome to CityLife Church! 🎵') {
            // Check if notification already exists
            if (document.querySelector('.welcome-notification')) return;

            const notification = document.createElement('div');
            notification.className = 'welcome-notification';
            notification.innerHTML = `
                ${message}
                <button class="close-btn" onclick="this.parentElement.remove()">×</button>
            `;

            document.body.appendChild(notification);

            // Show notification
            setTimeout(() => notification.classList.add('show'), 100);

            // Auto-hide after 4 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 500);
                }
            }, 4000);
        }

        // Initialize as early as possible
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeAudio);
        } else {
            initializeAudio();
        }

        // Also try when the page is fully loaded
        window.addEventListener('load', function() {
            setTimeout(tryAutoplay, 200);
        });

        // Additional attempt after a short delay (for slower connections)
        setTimeout(function() {
            if (!hasPlayed) {
                tryAutoplay();
            }
        }, 500);
    </script>
</x-app-layout>
