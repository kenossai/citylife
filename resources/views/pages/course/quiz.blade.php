<x-app-layout>
    @section('title', 'Quiz: ' . $lesson->title . ' - ' . $course->title)

    <!-- Critical JavaScript - Must be available immediately -->
    <script>
        function submitQuizNow() {
            console.log('Submit quiz function called');

            // Get elements
            const form = document.getElementById('quizForm');
            const button = document.getElementById('submitQuizBtn');

            if (!form || !button) {
                alert('Quiz elements not found. Please refresh the page.');
                return false;
            }

            // Confirm submission
            if (!confirm('Are you sure you want to submit your quiz? You can retake it later if needed.')) {
                return false;
            }

            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Submitting...';

            // Submit via AJAX for JSON response
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Quiz submitted successfully!\n\nScore: ${Math.round(data.score)}%\nCorrect: ${data.correct_answers}/${data.total_questions}\n${data.passed ? '✅ Passed!' : '❌ Needs improvement'}`);
                    window.location.href = data.redirect_url;
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                    button.disabled = false;
                    button.innerHTML = '<i class="icon-check me-2"></i>Submit Quiz';
                }
            })
            .catch(error => {
                console.error('Submission error:', error);
                alert('An error occurred while submitting the quiz. Please try again.');
                button.disabled = false;
                button.innerHTML = '<i class="icon-check me-2"></i>Submit Quiz';
            });

            return false;
        }

        // Make it globally available immediately
        window.submitQuizNow = submitQuizNow;

        // Add immediate event binding when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Head script: DOM loaded, looking for submit button');
            const btn = document.getElementById('submitQuizBtn');
            if (btn) {
                console.log('Head script: Found submit button, adding onclick');
                btn.onclick = function(e) {
                    console.log('Head script: Button clicked!');
                    e.preventDefault();

                    // Try to use the enhanced modal version if available
                    if (typeof processQuizSubmission === 'function') {
                        console.log('Using enhanced modal submission');
                        processQuizSubmission();
                    } else {
                        console.log('Enhanced submission not available, using basic version');
                        submitQuizNow();
                    }
                    return false;
                };
            } else {
                console.log('Head script: Submit button not found yet');
            }
        });
    </script>

    <!-- Modern Quiz Header -->
    <div class="quiz-header">
        <div class="container">
            <div class="row align-items-center py-4">
                <div class="col-md-8">
                    <div class="quiz-info">
                        <div class="breadcrumb-modern mb-2">
                            <a href="{{ route('home') }}" class="breadcrumb-link">
                                <i class="icon-home"></i> Home
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <a href="{{ route('courses.index') }}" class="breadcrumb-link">
                                <i class="icon-book"></i> Courses
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <a href="{{ route('courses.show', $course->slug) }}" class="breadcrumb-link">
                                <i class="icon-graduation-cap"></i> {{ $course->title }}
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <a href="{{ route('courses.lessons', $course->slug) }}" class="breadcrumb-link">
                                <i class="icon-list"></i> Lessons
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <span class="breadcrumb-current">Quiz</span>
                        </div>
                        <h3 class="quiz-lesson-title mb-1">Lesson {{ $lesson->lesson_number }} Quiz</h3>
                        <h2 class="quiz-main-title mb-2">{{ $lesson->title }}</h2>
                        <p class="quiz-subtitle">{{ $course->title }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="quiz-stats">
                        @if($progress->quiz_score)
                            <div class="stat-item">
                                <div class="stat-value {{ $progress->quiz_score >= 70 ? 'text-success' : 'text-warning' }}">
                                    {{ round($progress->quiz_score) }}%
                                </div>
                                <div class="stat-label">Previous Score</div>
                            </div>
                        @endif
                        <div class="stat-item">
                            <div class="stat-value">{{ count($quizQuestions) }}</div>
                            <div class="stat-label">Questions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="quiz-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Quiz Instructions Card -->
                    <div class="quiz-instructions-card mb-4">
                        <div class="card-header">
                            <h5><i class="icon-info-circle me-2"></i>Quiz Instructions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <ul class="instructions-list">
                                        <li>This quiz has {{ count($quizQuestions) }} questions</li>
                                        <li>You need 70% or higher to pass</li>
                                        <li>You can retake the quiz if needed</li>
                                        <li>Take your time and read each question carefully</li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    @if($progress->quiz_score)
                                        <div class="previous-score-card">
                                            <div class="score-header">Previous Score</div>
                                            <div class="score-value {{ $progress->quiz_score >= 70 ? 'success' : 'warning' }}">
                                                {{ round($progress->quiz_score) }}%
                                            </div>
                                            <div class="score-meta">
                                                Attempts: {{ $progress->attempts }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quiz Form -->
                    <div class="quiz-form-container">
                        <form id="quizForm" action="{{ route('courses.lesson.quiz.submit', [$course->slug, $lesson->slug]) }}" method="POST">
                            @csrf

                            @foreach($quizQuestions as $index => $question)
                                <div class="modern-question-card">
                                    <div class="question-header">
                                        <div class="question-number">
                                            Question {{ $index + 1 }}
                                        </div>
                                        <div class="question-progress">
                                            {{ $index + 1 }} of {{ count($quizQuestions) }}
                                        </div>
                                    </div>
                                    <div class="question-content">
                                        <h6 class="question-text">{{ $question['question'] }}</h6>
                                        @if($question['type'] === 'multiple_choice')
                                            <div class="options-container">
                                                @foreach($question['options'] as $optionIndex => $option)
                                                    <label class="option-card" for="question_{{ $index }}_option_{{ $optionIndex }}">
                                                        <input class="option-input" type="radio"
                                                               name="answers[{{ $index }}]"
                                                               value="{{ chr(65 + $optionIndex) }}"
                                                               id="question_{{ $index }}_option_{{ $optionIndex }}"
                                                               required>
                                                        <div class="option-content">
                                                            <div class="option-letter">{{ chr(65 + $optionIndex) }}</div>
                                                            <div class="option-text">{{ $option }}</div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @elseif($question['type'] === 'short_answer')
                                            <div class="answer-input-container">
                                                <textarea class="modern-textarea"
                                                          name="answers[{{ $index }}]"
                                                          rows="3"
                                                          placeholder="Enter your answer here..."
                                                          required></textarea>
                                                <div class="input-help">Provide a brief answer (2-3 sentences).</div>
                                            </div>
                                        @elseif($question['type'] === 'essay')
                                            <div class="answer-input-container">
                                                <textarea class="modern-textarea"
                                                          name="answers[{{ $index }}]"
                                                          rows="6"
                                                          placeholder="Write your detailed response here..."
                                                          required></textarea>
                                                <div class="input-help">Provide a detailed response explaining your thoughts and understanding.</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <!-- Submit Section -->
                            <div class="quiz-submit-card">
                                <div class="submit-header">
                                    <h6>Ready to Submit?</h6>
                                    <p>Please review your answers before submitting. You can retake the quiz if needed.</p>
                                </div>
                                <div class="submit-actions">
                                    <button type="button" class="btn-modern btn-outline" onclick="window.history.back()">
                                        <i class="icon-arrow-left me-2"></i>Back to Lesson
                                    </button>
                                    <button type="button" class="btn-modern btn-primary" id="submitQuizBtn">
                                        <i class="icon-check me-2"></i>Submit Quiz
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Beautiful Quiz Results Modal -->
                    <div class="modal fade" id="quizResultsModal" tabindex="-1" aria-labelledby="quizResultsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content modern-modal">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="quizResultsModalLabel">
                                        <i class="icon-award me-2"></i>Quiz Results
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="quizResultsContent">
                                    <!-- Beautiful results will be loaded here -->
                                    <div class="loading-results text-center py-5">
                                        <div class="spinner-border text-primary mb-3" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="text-muted">Calculating your results...</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="icon-x me-2"></i>Close
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg" id="continueBtn">
                                        <i class="icon-arrow-right me-2"></i>Continue
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>

        // Now do authentication check
        console.log('Quiz page loaded');
        console.log('Auth check URL: /auth-debug');

        // Fetch auth status for debugging
        fetch('/auth-debug')
            .then(response => response.json())
            .then(data => {
                console.log('Authentication status:', data);
                if (!data.member_guard_check && !data.session_user_email && !data.session_member_id) {
                    console.warn('⚠️ User appears to be logged out!');
                }
            })
            .catch(error => console.error('Auth check failed:', error));

        // Global variables
        let quizForm, submitBtn, resultsModalElement, resultsModal;

        function initializeQuizForm() {
            console.log('Initializing quiz form...');

            quizForm = document.getElementById('quizForm');
            submitBtn = document.getElementById('submitQuizBtn');
            resultsModalElement = document.getElementById('quizResultsModal');

            console.log('Elements found:', {
                quizForm: !!quizForm,
                submitBtn: !!submitBtn,
                resultsModalElement: !!resultsModalElement
            });

            if (!quizForm || !submitBtn || !resultsModalElement) {
                console.error('Required elements not found');
                return false;
            }

            // Initialize Bootstrap modal
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                resultsModal = new bootstrap.Modal(resultsModalElement);
                console.log('Bootstrap modal initialized');
            } else {
                console.log('Using fallback modal - Bootstrap not available');
                resultsModal = {
                    show: function() {
                        console.log('Showing fallback modal');
                        resultsModalElement.style.display = 'block';
                        resultsModalElement.classList.add('show');
                        resultsModalElement.style.zIndex = '1055';
                        document.body.classList.add('modal-open');

                        // Create backdrop
                        const existingBackdrop = document.getElementById('modal-backdrop');
                        if (existingBackdrop) existingBackdrop.remove();

                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        backdrop.id = 'modal-backdrop';
                        backdrop.style.zIndex = '1050';
                        document.body.appendChild(backdrop);

                        // Ensure modal is properly positioned
                        setTimeout(function() {
                            resultsModalElement.style.paddingLeft = '0px';
                            resultsModalElement.style.paddingRight = '0px';
                        }, 100);
                    },
                    hide: function() {
                        console.log('Hiding fallback modal');
                        resultsModalElement.style.display = 'none';
                        resultsModalElement.classList.remove('show');
                        document.body.classList.remove('modal-open');

                        const backdrop = document.getElementById('modal-backdrop');
                        if (backdrop) backdrop.remove();
                    }
                };
            }

            // Only prevent form default submission, don't add conflicting click handlers
            quizForm.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Form submit event prevented');
                return false;
            });

            console.log('Quiz form initialized successfully');
            return true;
        }

        function handleQuizSubmission(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('Submit button clicked');
            return processQuizSubmission();
        }

        // Global function that can be called directly
        window.handleQuizSubmissionDirect = function(e) {
            console.log('Direct submission handler called');
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            return processQuizSubmission();
        };

        function processQuizSubmission() {
            console.log('Processing quiz submission...');

            // Make sure we have the elements
            if (!quizForm) quizForm = document.getElementById('quizForm');
            if (!submitBtn) submitBtn = document.getElementById('submitQuizBtn');
            if (!resultsModalElement) resultsModalElement = document.getElementById('quizResultsModal');

            if (!quizForm || !submitBtn || !resultsModalElement) {
                alert('Quiz form elements not found. Please refresh the page.');
                return false;
            }

            // Initialize modal if not done
            if (!resultsModal) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    resultsModal = new bootstrap.Modal(resultsModalElement);
                } else {
                    resultsModal = {
                        show: function() {
                            resultsModalElement.style.display = 'block';
                            resultsModalElement.classList.add('show');
                            document.body.classList.add('modal-open');
                        },
                        hide: function() {
                            resultsModalElement.style.display = 'none';
                            resultsModalElement.classList.remove('show');
                            document.body.classList.remove('modal-open');
                        }
                    };
                }
            }

            // Confirm submission
            if (!confirm('Are you sure you want to submit your quiz? You can retake it later if needed.')) {
                return false;
            }

            console.log('User confirmed submission');

            // Disable submit button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Submitting...';

            // Get form data
            const formData = new FormData(quizForm);

            // Debug form data
            console.log('Form data entries:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            // Show modal with loading state immediately
            resultsModal.show();

            // Submit via AJAX
            fetch(quizForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response received:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Quiz response data:', data);

                if (data.success) {
                    setTimeout(() => {
                        showResults(data);
                    }, 1200);
                } else {
                    resultsModal.hide();
                    alert('Error submitting quiz: ' + (data.error || data.message || 'Unknown error'));
                    resetSubmitButton();
                }
            })
            .catch(error => {
                console.error('Quiz submission error:', error);
                resultsModal.hide();
                alert('An error occurred while submitting the quiz. Please try again.');
                resetSubmitButton();
            });

            return true;
        }

        function resetSubmitButton() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="icon-check me-2"></i>Submit Quiz';
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing quiz...');
            initializeQuizForm();

            // Add click event listener to submit button for modal functionality
            const submitBtn = document.getElementById('submitQuizBtn');
            if (submitBtn) {
                console.log('Adding click event listener to submit button');
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Submit button clicked - processing with modal');

                    // Check if we have the enhanced modal functionality available
                    if (typeof processQuizSubmission === 'function') {
                        processQuizSubmission();
                    } else {
                        // Fallback to basic submission
                        console.log('Using fallback submission');
                        submitQuizNow();
                    }
                });
            } else {
                console.error('Submit button not found during initialization');
            }
        });

        // Additional immediate event listener setup
        setTimeout(function() {
            const submitBtn = document.getElementById('submitQuizBtn');
            if (submitBtn && !submitBtn.hasAttribute('data-listener-added')) {
                console.log('Adding backup click listener');
                submitBtn.setAttribute('data-listener-added', 'true');
                submitBtn.onclick = function(e) {
                    e.preventDefault();
                    console.log('Backup onclick handler triggered');
                    if (typeof processQuizSubmission === 'function') {
                        processQuizSubmission();
                    } else {
                        submitQuizNow();
                    }
                    return false;
                };
            }
        }, 500);

        // Backup initialization in case DOMContentLoaded already fired
        if (document.readyState === 'loading') {
            console.log('Document still loading...');
        } else {
            console.log('Document already loaded, initializing immediately...');
            setTimeout(initializeQuizForm, 100);
        }

        // Additional fallback - try to initialize every second for up to 10 seconds
        let initAttempts = 0;
        const maxAttempts = 10;
        const initInterval = setInterval(function() {
            initAttempts++;
            console.log(`Initialization attempt ${initAttempts}`);

            if (initializeQuizForm() || initAttempts >= maxAttempts) {
                clearInterval(initInterval);
                if (initAttempts >= maxAttempts) {
                    console.error('Failed to initialize quiz form after maximum attempts');
                }
            }
        }, 1000);

            function showResults(data) {
                console.log('showResults called with data:', data);
                const resultContent = document.getElementById('quizResultsContent');
                const continueBtn = document.getElementById('continueBtn');

                console.log('Result elements:', {
                    resultContent: !!resultContent,
                    continueBtn: !!continueBtn
                });

                const passed = data.passed;
                const score = Math.round(data.score);

                resultContent.innerHTML = `
                    <div class="beautiful-quiz-results">
                        <!-- Animated Result Header -->
                        <div class="result-header mb-4">
                            <div class="result-animation">
                                ${passed
                                    ? '<div class="success-animation"><i class="icon-check-circle result-icon success-icon"></i></div>'
                                    : '<div class="retry-animation"><i class="icon-info-circle result-icon retry-icon"></i></div>'
                                }
                            </div>
                            <h3 class="result-title ${passed ? 'text-success' : 'text-warning'} mb-2">
                                ${passed ? '🎉 Excellent Work!' : '📚 Keep Learning!'}
                            </h3>
                            <p class="result-subtitle mb-0">
                                ${passed
                                    ? 'You\'ve successfully completed this quiz!'
                                    : 'Learning is a journey. You\'re making progress!'
                                }
                            </p>
                        </div>

                        <!-- Beautiful Score Display -->
                        <div class="score-showcase mb-4">
                            <div class="score-circle-container">
                                <div class="score-circle ${passed ? 'success' : 'warning'}">
                                    <div class="score-inner">
                                        <span class="score-percentage">${score}%</span>
                                        <div class="score-ring">
                                            <svg class="progress-ring" width="120" height="120">
                                                <circle class="progress-ring-bg" cx="60" cy="60" r="50"></circle>
                                                <circle class="progress-ring-fill ${passed ? 'success-ring' : 'warning-ring'}"
                                                        cx="60" cy="60" r="50"
                                                        style="stroke-dasharray: ${2 * Math.PI * 50}; stroke-dashoffset: ${2 * Math.PI * 50 * (1 - score/100)}">
                                                </circle>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Results -->
                        <div class="results-details mb-4">
                            <div class="row">
                                <div class="col-4">
                                    <div class="detail-card">
                                        <div class="detail-icon">
                                            <i class="icon-check-square"></i>
                                        </div>
                                        <div class="detail-value">${data.correct_answers}</div>
                                        <div class="detail-label">Correct</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="detail-card">
                                        <div class="detail-icon">
                                            <i class="icon-x-square"></i>
                                        </div>
                                        <div class="detail-value">${data.total_questions - data.correct_answers}</div>
                                        <div class="detail-label">Incorrect</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="detail-card">
                                        <div class="detail-icon">
                                            <i class="icon-list"></i>
                                        </div>
                                        <div class="detail-value">${data.total_questions}</div>
                                        <div class="detail-label">Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="status-section mb-4">
                            <div class="status-badge ${passed ? 'status-passed' : 'status-retry'}">
                                <i class="${passed ? 'icon-award' : 'icon-refresh-ccw'} me-2"></i>
                                ${passed ? 'Quiz Passed!' : 'Needs Improvement'}
                            </div>
                        </div>

                        <!-- Next Steps -->
                        <div class="next-steps-card">
                            <div class="next-steps-header">
                                <i class="${passed ? 'icon-arrow-right' : 'icon-book-open'} me-2"></i>
                                What's Next?
                            </div>
                            <div class="next-steps-content">
                                ${passed
                                    ? '<p class="mb-0">🚀 Great job! You can now continue to the next lesson and keep building your knowledge.</p>'
                                    : '<p class="mb-0">💪 Don\'t worry! Review the lesson material and try again. You need 70% or higher to pass.</p>'
                                }
                            </div>
                        </div>
                    </div>
                `;

                continueBtn.innerHTML = passed
                    ? '<i class="icon-arrow-right me-2"></i>Continue to Next Lesson'
                    : '<i class="icon-refresh-ccw me-2"></i>Review & Retry';

                continueBtn.className = passed
                    ? 'btn btn-success btn-lg'
                    : 'btn btn-primary btn-lg';

                continueBtn.onclick = function() {
                    window.location.href = data.redirect_url;
                };

                // Add close button functionality
                const closeButtons = document.querySelectorAll('#quizResultsModal [data-bs-dismiss="modal"], #quizResultsModal .btn-close');
                closeButtons.forEach(button => {
                    button.onclick = function() {
                        resultsModal.hide();
                    };
                });

                resultsModal.show();

                // Trigger animations
                setTimeout(() => {
                    document.querySelector('.result-animation').classList.add('animate');
                    document.querySelector('.score-circle').classList.add('animate');
                }, 100);
            }
    </script>
    @endpush

    <style>
        /* Modern Quiz Page Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Quiz Header */
        .quiz-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-bottom: 2rem;
        }

        .breadcrumb-modern {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .breadcrumb-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .breadcrumb-link:hover {
            color: white;
        }

        .breadcrumb-separator {
            color: rgba(255, 255, 255, 0.6);
        }

        .breadcrumb-current {
            color: white;
            font-weight: 500;
        }

        .quiz-lesson-title {
            font-size: 1.125rem;
            font-weight: 500;
            opacity: 0.9;
            margin: 0;
        }

        .quiz-main-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .quiz-subtitle {
            opacity: 0.8;
            margin: 0;
        }

        .quiz-stats {
            display: flex;
            gap: 2rem;
            justify-content: flex-end;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.8;
        }

        /* Instructions Card */
        .quiz-instructions-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .quiz-instructions-card .card-header {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem;
        }

        .quiz-instructions-card .card-header h5 {
            font-weight: 600;
            margin: 0;
            color: #1f2937;
        }

        .quiz-instructions-card .card-body {
            padding: 1.5rem;
        }

        .instructions-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .instructions-list li {
            padding: 0.5rem 0;
            position: relative;
            padding-left: 1.5rem;
        }

        .instructions-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #22c55e;
            font-weight: bold;
        }

        .previous-score-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }

        .score-header {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .score-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .score-value.success {
            color: #22c55e;
        }

        .score-value.warning {
            color: #f59e0b;
        }

        .score-meta {
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* Question Cards */
        .modern-question-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .modern-question-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .question-header {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .question-number {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
        }

        .question-progress {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .question-content {
            padding: 1.5rem;
        }

        .question-text {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }

        /* Option Cards */
        .options-container {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .option-card {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            margin: 0;
        }

        .option-card:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .option-input {
            display: none;
        }

        .option-input:checked + .option-content {
            background: rgba(79, 172, 254, 0.1);
        }

        .option-input:checked + .option-content .option-letter {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .option-card:has(.option-input:checked) {
            background: rgba(79, 172, 254, 0.05);
            border-color: #4facfe;
        }

        .option-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            width: 100%;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .option-letter {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .option-text {
            flex: 1;
            font-size: 0.875rem;
            color: #1f2937;
        }

        /* Text Input */
        .answer-input-container {
            margin-top: 1rem;
        }

        .modern-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.875rem;
            resize: vertical;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .modern-textarea:focus {
            outline: none;
            border-color: #4facfe;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        .input-help {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }

        /* Submit Card */
        .quiz-submit-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
            border: 2px solid #22c55e;
        }

        .submit-header h6 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .submit-header p {
            color: #6b7280;
            margin-bottom: 2rem;
        }

        .submit-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        /* Modern Buttons */
        .btn-modern {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-modern.btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-modern.btn-primary:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            transform: translateY(-1px);
        }

        .btn-modern.btn-outline {
            background: transparent;
            border: 2px solid #e5e7eb;
            color: #6b7280;
        }

        .btn-modern.btn-outline:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #374151;
        }

        /* Modal */
        .modern-modal .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 4px solid;
        }

        .score-circle.success {
            background: rgba(34, 197, 94, 0.1);
            border-color: #22c55e;
        }

        .score-circle.warning {
            background: rgba(245, 158, 11, 0.1);
            border-color: #f59e0b;
        }

        .score-text {
            font-size: 2rem;
            font-weight: 700;
        }

        .score-circle.success .score-text {
            color: #22c55e;
        }

        .score-circle.warning .score-text {
            color: #f59e0b;
        }

        .badge-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Beautiful Quiz Results Styles */
        .beautiful-quiz-results {
            padding: 2rem 1rem;
            text-align: center;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 20px;
            margin: -1rem;
        }

        .result-header {
            position: relative;
        }

        .result-animation {
            margin-bottom: 1rem;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .result-animation.animate {
            opacity: 1;
            transform: scale(1);
        }

        .result-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .success-icon {
            color: #22c55e;
            filter: drop-shadow(0 4px 8px rgba(34, 197, 94, 0.3));
            animation: successPulse 2s infinite;
        }

        .retry-icon {
            color: #f59e0b;
            filter: drop-shadow(0 4px 8px rgba(245, 158, 11, 0.3));
            animation: retryBounce 2s infinite;
        }

        @keyframes successPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes retryBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .result-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .result-subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 0;
        }

        /* Beautiful Score Circle */
        .score-showcase {
            position: relative;
            display: flex;
            justify-content: center;
            margin: 2rem 0;
        }

        .score-circle-container {
            position: relative;
        }

        .score-circle {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: white;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transform: scale(0.8);
            opacity: 0;
            transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .score-circle.animate {
            transform: scale(1);
            opacity: 1;
        }

        .score-circle.success {
            border: 4px solid #22c55e;
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        }

        .score-circle.warning {
            border: 4px solid #f59e0b;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }

        .score-inner {
            text-align: center;
            z-index: 2;
            position: relative;
        }

        .score-percentage {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1f2937;
            display: block;
        }

        .score-ring {
            position: absolute;
            top: -6px;
            left: -6px;
            width: 172px;
            height: 172px;
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-bg {
            fill: none;
            stroke: #e5e7eb;
            stroke-width: 6;
        }

        .progress-ring-fill {
            fill: none;
            stroke-width: 6;
            stroke-linecap: round;
            transition: stroke-dashoffset 1.5s ease-in-out;
        }

        .success-ring {
            stroke: #22c55e;
        }

        .warning-ring {
            stroke: #f59e0b;
        }

        /* Detail Cards */
        .results-details {
            margin: 2rem 0;
        }

        .detail-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem 1rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            border: 1px solid #f3f4f6;
        }

        .detail-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .detail-icon {
            font-size: 1.5rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .detail-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .detail-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        /* Status Badge */
        .status-section {
            display: flex;
            justify-content: center;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .status-passed {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
        }

        .status-retry {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        /* Next Steps Card */
        .next-steps-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #f3f4f6;
        }

        .next-steps-header {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .next-steps-content {
            color: #6b7280;
            line-height: 1.6;
        }

        /* Modal Enhancements */
        .modal-lg .modal-content.modern-modal {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        /* Fallback modal styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1055;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
        }

        .modal.show {
            display: block !important;
        }

        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
            justify-content: center;
        }

        .modal-dialog {
            position: relative;
            width: auto;
            margin: 0.5rem;
            pointer-events: none;
        }

        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 0.3rem;
            outline: 0;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-backdrop.fade {
            opacity: 0;
        }

        .modal-backdrop.show {
            opacity: 0.5;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1.5rem 2rem;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .modal-footer {
            background: #f8fafc;
            border: none;
            padding: 1.5rem 2rem;
            justify-content: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .quiz-stats {
                justify-content: center;
                margin-top: 1rem;
            }

            .submit-actions {
                flex-direction: column;
            }

            .modern-question-card {
                margin-bottom: 1.5rem;
            }

            .quiz-submit-card {
                padding: 1.5rem;
            }

            .beautiful-quiz-results {
                padding: 1.5rem 1rem;
            }

            .result-title {
                font-size: 1.5rem;
            }

            .score-circle {
                width: 120px;
                height: 120px;
            }

            .score-percentage {
                font-size: 2rem;
            }

            .score-ring {
                width: 132px;
                height: 132px;
                top: -6px;
                left: -6px;
            }

            .results-details .row > .col-4 {
                margin-bottom: 1rem;
            }
        }
    </style>
</x-app-layout>
