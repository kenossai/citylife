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

            // Validate that all questions are answered
            const formElements = form.querySelectorAll('input[required], textarea[required]');
            let allAnswered = true;
            formElements.forEach(element => {
                if (element.type === 'radio') {
                    const name = element.name;
                    const checked = form.querySelector(`input[name="${name}"]:checked`);
                    if (!checked) allAnswered = false;
                } else if (!element.value.trim()) {
                    allAnswered = false;
                }
            });

            if (!allAnswered) {
                alert('Please answer all questions before submitting.');
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
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
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
                alert('An error occurred while submitting the quiz.\n\nError: ' + error.message + '\n\nPlease check the console for details and ensure you are logged in.');
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

                    // Force the enhanced modal version
                    console.log('Forcing enhanced modal submission');

                    // Wait a bit for all scripts to load, then use enhanced version
                    setTimeout(function() {
                        if (typeof processQuizSubmission === 'function') {
                            console.log('Using processQuizSubmission');
                            processQuizSubmission();
                        } else {
                            console.log('processQuizSubmission not available, calling it directly');

                            // Call processQuizSubmission directly since it should be defined in the main script
                            const formElement = document.getElementById('quizForm');
                            const buttonElement = document.getElementById('submitQuizBtn');
                            const modalElement = document.getElementById('quizResultsModal');

                            if (formElement && buttonElement && modalElement) {
                                // Confirm submission
                                if (!confirm('Are you sure you want to submit your quiz? You can retake it later if needed.')) {
                                    return false;
                                }

                                // Disable submit button and show loading
                                buttonElement.disabled = true;
                                buttonElement.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Submitting...';

                                // Initialize modal
                                let modal;
                                if (typeof $ !== 'undefined' && $.fn.modal) {
                                    modal = {
                                        show: function() { $(modalElement).modal('show'); },
                                        hide: function() { $(modalElement).modal('hide'); }
                                    };
                                } else {
                                    modal = {
                                        show: function() {
                                            modalElement.style.display = 'block';
                                            modalElement.classList.add('show');
                                            document.body.classList.add('modal-open');
                                        },
                                        hide: function() {
                                            modalElement.style.display = 'none';
                                            modalElement.classList.remove('show');
                                            document.body.classList.remove('modal-open');
                                        }
                                    };
                                }

                                // Show modal immediately
                                modal.show();

                                // Submit form via AJAX
                                const formData = new FormData(formElement);

                                console.log('Form action:', formElement.action);
                                console.log('Form method:', formElement.method);
                                console.log('Submitting quiz to:', formElement.action);
                                console.log('Form data:', Array.from(formData.entries()));
                                console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));

                                fetch(formElement.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json'
                                    },
                                    credentials: 'same-origin'  // Include cookies for session
                                })
                                .then(response => {
                                    console.log('Response status:', response.status);
                                    console.log('Response ok:', response.ok);
                                    console.log('Response headers:', response.headers);

                                    if (!response.ok) {
                                        return response.text().then(text => {
                                            console.log('Error response text:', text);
                                            throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
                                        });
                                    }

                                    // Clone the response to read it twice
                                    const clonedResponse = response.clone();

                                    // Try to parse as JSON first
                                    return response.json().catch(err => {
                                        // If JSON parsing fails, get the text to see what was returned
                                        return clonedResponse.text().then(text => {
                                            console.log('Response is not JSON. First 500 chars:', text.substring(0, 500));
                                            console.log('Full response starts with:', text.substring(0, 100));

                                            // Check if it's an HTML page (likely a redirect or error page)
                                            if (text.trim().startsWith('<!DOCTYPE') || text.trim().startsWith('<html')) {
                                                throw new Error('Server returned HTML instead of JSON. This usually means:\n1. CSRF token mismatch\n2. Authentication failed\n3. Server error occurred\n\nPlease check if you are still logged in.');
                                            }

                                            throw new Error('Invalid response format: ' + err.message);
                                        });
                                    });
                                })
                                .then(data => {
                                    console.log('Response data:', data);
                                    if (data.success) {
                                        setTimeout(() => {
                                            showResults(data, modal);
                                        }, 1200);
                                    } else {
                                        modal.hide();
                                        alert('Error: ' + (data.error || 'Unknown error'));
                                        buttonElement.disabled = false;
                                        buttonElement.innerHTML = '<i class="icon-check me-2"></i>Submit Quiz';
                                    }
                                })
                                .catch(error => {
                                    console.error('Submission error details:', error);
                                    modal.hide();
                                    alert('Submission Error:\n\n' + error.message + '\n\nPlease check:\n1. Are you logged in?\n2. Check browser console for details');
                                    buttonElement.disabled = false;
                                    buttonElement.innerHTML = '<i class="icon-check me-2"></i>Submit Quiz';
                                });
                            } else {
                                console.log('Required elements not found, using basic submission');
                                submitQuizNow();
                            }
                        }
                    }, 100);
                    return false;
                };
            } else {
                console.log('Head script: Submit button not found yet');
            }
        });

        // Beautiful results function
        function showResults(data, modal) {
            console.log('showResults called with data:', data);
            const resultContent = document.getElementById('quizResultsContent');
            const continueBtn = document.getElementById('continueBtn');

            if (!resultContent || !continueBtn) {
                alert(`Quiz completed!\nScore: ${Math.round(data.score)}%\n${data.passed ? 'Passed!' : 'Needs improvement'}`);
                window.location.href = data.redirect_url;
                return;
            }

            const passed = data.passed;
            const score = Math.round(data.score);

            resultContent.innerHTML = `
                <div class="simple-quiz-results">
                    <!-- Clean Header -->
                    <div class="simple-header">
                        <h4 class="result-title">${passed ? 'Well done!' : 'Keep Learning!'}</h4>
                        <p class="result-subtitle">Duration: ${data.duration || '5 min'}</p>
                    </div>

                    <!-- Score Circle -->
                    <div class="score-display">
                        <div class="score-circle ${passed ? 'success' : 'warning'}">
                            <span class="score-text">${score}%</span>
                        </div>
                    </div>

                    <!-- Simple Stats -->
                    <div class="quiz-stats-simple">
                        <div class="stat-group">
                            <div class="stat-item">
                                <span class="stat-label">Correct</span>
                                <span class="stat-value">${data.correct_answers}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Partially correct</span>
                                <span class="stat-value">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Incorrect</span>
                                <span class="stat-value">${data.total_questions - data.correct_answers}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            continueBtn.innerHTML = passed
                ? '<i class="icon-arrow-right me-2"></i>Continue to Next Lesson'
                : '<i class="icon-refresh-ccw me-2"></i>Review & Retry';

            continueBtn.className = passed
                ? 'btn btn-success btn-lg'
                : 'btn btn-warning btn-lg';

            continueBtn.onclick = function() {
                window.location.href = data.redirect_url;
            };

            // Add close button functionality
            const closeButtons = document.querySelectorAll('#quizResultsModal [data-dismiss="modal"], #quizResultsModal .close');
            closeButtons.forEach(button => {
                button.onclick = function() {
                    if (modal && modal.hide) {
                        modal.hide();
                    } else {
                        document.getElementById('quizResultsModal').style.display = 'none';
                    }
                };
            });

            // Trigger animations
            setTimeout(() => {
                const animElements = document.querySelectorAll('.result-animation, .score-circle');
                animElements.forEach(el => el.classList.add('animate'));
            }, 100);
        }

        // Make it globally available
        window.showResults = showResults;
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
                                <div class="stat-value {{ $progress->quiz_score >= 70 ? 'text-success' : 'text-white' }}">
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

                    <!-- Quiz Results Modal -->
                    <div class="modal fade" id="quizResultsModal" tabindex="-1" role="dialog" aria-labelledby="quizResultsModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="quizResultsModalTitle">
                                        <i class="icon-award me-2"></i>Quiz Results
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
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
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
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



    <style>
        /* Beautiful Quiz Page Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Quiz Header */
        .quiz-header {
            background: linear-gradient(135deg, #430056 0%, #5a0070 100%);
            color: white;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .quiz-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>');
            opacity: 0.3;
        }

        .quiz-header .container {
            position: relative;
            z-index: 1;
        }

        .breadcrumb-modern {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            flex-wrap: wrap;
        }

        .breadcrumb-link {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            transition: color 0.3s;
        }

        .breadcrumb-link:hover {
            color: white;
        }

        .breadcrumb-separator {
            color: rgba(255, 255, 255, 0.5);
        }

        .breadcrumb-current {
            color: white;
            font-weight: 500;
        }

        .quiz-lesson-title {
            font-size: 1rem;
            font-weight: 500;
            color: white;
            opacity: 0.9;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .quiz-main-title {
            font-size: 2.5rem;
            color: white;
            font-weight: 700;
            margin: 0.5rem 0;
            line-height: 1.2;
        }

        .quiz-subtitle {
            color: rgba(255, 255, 255, 0.85);
            opacity: 0.85;
            margin: 0;
            font-size: 1.1rem;
        }

        .quiz-stats {
            display: flex;
            gap: 2rem;
            justify-content: flex-end;
            align-items: center;
        }

        .stat-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            display: block;
            color: #fbbf24;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-top: 0.25rem;
        }

        /* Instructions Card */
        .quiz-instructions-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #f0f0f0;
        }

        .quiz-instructions-card .card-header {
            background: linear-gradient(135deg, #430056, #5a0070);
            border: none;
            padding: 1.25rem 1.75rem;
        }

        .quiz-instructions-card .card-header h5 {
            font-weight: 700;
            margin: 0;
            color: white;
            font-size: 1.125rem;
        }

        .quiz-instructions-card .card-body {
            padding: 2rem;
        }

        .instructions-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .instructions-list li {
            padding: 0.75rem 0;
            position: relative;
            padding-left: 2rem;
            font-size: 1rem;
            color: #374151;
        }

        .instructions-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #10b981;
            font-weight: bold;
            font-size: 1.125rem;
            width: 24px;
            height: 24px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .previous-score-card {
            background-color: #30013f;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            border: 2px solid #5a0067;
        }

        .score-header {
            font-size: 0.875rem;
            color: #fff;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .score-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .score-value.success {
            color: #10b981;
        }

        .score-value.warning {
            color: #ffbf00;
        }

        .score-meta {
            font-size: 0.875rem;
            color: #fff;
            font-weight: 500;
        }

        /* Question Cards */
        .modern-question-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .modern-question-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(139, 92, 246, 0.15);
            border-color: #8b5cf6;
        }

        .question-header {
            background: linear-gradient(135deg, #430056, #5a0070);
            border: none;
            padding: 1.25rem 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .question-number {
            font-size: 1rem;
            font-weight: 700;
            color: white;
        }

        .question-progress {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.85);
            background: rgba(255, 255, 255, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
        }

        .question-content {
            padding: 2rem;
        }

        .question-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 1.75rem;
            line-height: 1.5;
        }

        /* Option Cards */
        .options-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .option-card {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            margin: 0;
        }

        .option-card:hover {
            background: #f3f4f6;
            border-color: #430056;
            transform: translateX(5px);
        }

        .option-input {
            display: none;
        }

        .option-input:checked + .option-content {
            background: rgba(67, 0, 86, 0.1);
        }

        .option-input:checked + .option-content .option-letter {
            background: linear-gradient(135deg, #430056, #5a0070);
            color: white;
            box-shadow: 0 4px 15px rgba(67, 0, 86, 0.4);
        }

        .option-card:has(.option-input:checked) {
            background: rgba(67, 0, 86, 0.05);
            border-color: #430056;
            box-shadow: 0 4px 15px rgba(67, 0, 86, 0.2);
        }

        .option-content {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            width: 100%;
            padding: 0.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .option-letter {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .option-text {
            flex: 1;
            font-size: 1rem;
            color: #1a202c;
            font-weight: 500;
        }

        /* Text Input */
        .answer-input-container {
            margin-top: 1rem;
        }

        .modern-textarea {
            width: 100%;
            padding: 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 1rem;
            resize: vertical;
            transition: all 0.3s ease;
            font-family: inherit;
            line-height: 1.6;
        }

        .modern-textarea:focus {
            outline: none;
            border-color: #430056;
            box-shadow: 0 0 0 4px rgba(67, 0, 86, 0.1);
        }

        .input-help {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.75rem;
            padding-left: 0.5rem;
        }

        /* Submit Card */
        .quiz-submit-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            text-align: center;
            margin-bottom: 3rem;
            border: 3px solid #10b981;
            position: relative;
            overflow: hidden;
        }

        .quiz-submit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .submit-header h6 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.75rem;
        }

        .submit-header p {
            color: #6b7280;
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        .submit-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Modern Buttons */
        .btn-modern {
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-modern.btn-primary {
            background: linear-gradient(135deg, #430056, #5a0070);
            color: white;
            box-shadow: 0 6px 20px rgba(67, 0, 86, 0.4);
        }

        .btn-modern.btn-primary:hover {
            background: linear-gradient(135deg, #5a0070, #6d0087);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(67, 0, 86, 0.5);
        }

        .btn-modern.btn-outline {
            background: white;
            border: 2px solid #e5e7eb;
            color: #6b7280;
        }

        .btn-modern.btn-outline:hover {
            background: #f9fafb;
            border-color: #430056;
            color: #430056;
            transform: translateY(-2px);
        }

        /* Modal */
        .modern-modal .modal-content {
            border-radius: 24px;
            border: none;
            box-shadow: 0 25px 50px rgba(67, 0, 86, 0.3);
            overflow: hidden;
        }

        .score-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 6px solid;
            position: relative;
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .score-circle.success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(16, 185, 129, 0.2));
            border-color: #22c55e;
            box-shadow: 0 0 30px rgba(34, 197, 94, 0.3);
        }

        .score-circle.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(251, 191, 36, 0.2));
            border-color: #580170;
            box-shadow: 0 0 30px rgba(232, 177, 246, 0.3);
        }

        .score-text {
            font-size: 2.5rem;
            font-weight: 800;
            animation: fadeIn 0.8s ease-out 0.3s both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .score-circle.success .score-text {
            color: #22c55e;
            text-shadow: 0 2px 10px rgba(34, 197, 94, 0.3);
        }

        .score-circle.warning .score-text {
            color: #f59e0b;
            text-shadow: 0 2px 10px rgba(245, 158, 11, 0.3);
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

        /* Simple Quiz Results Styles */
        .simple-quiz-results {
            padding: 2rem 1.5rem;
            text-align: center;
            background: linear-gradient(135deg, #fafbfc 0%, #ffffff 100%);
            border-radius: 20px;
            margin: -1rem;
            position: relative;
            overflow: hidden;
        }

        .simple-quiz-results::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #430056, #5a0070, #430056);
            background-size: 200% 100%;
            animation: gradientShift 3s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Simple Header */
        .simple-header {
            margin-bottom: 2rem;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result-title {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #430056, #5a0070);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .result-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
            font-weight: 500;
        }

        /* Simple Score Display */
        .score-display {
            margin: 1.5rem 0;
            display: flex;
            justify-content: center;
        }

        .score-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: #f8fafc;
            border: 6px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .score-circle.success {
            border-color: #22c55e;
            background: #f0fdf4;
        }

        .score-circle.warning {
            border-color: #f59e0b;
            background: #fffbeb;
        }

        .score-text {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
        }

        /* Simple Quiz Stats */
        .quiz-stats-simple {
            margin-top: 2rem;
            animation: fadeIn 0.8s ease-out 0.5s both;
        }

        .stat-group {
            display: flex;
            justify-content: space-between;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-radius: 16px;
            padding: 1.5rem 1rem;
            box-shadow: 0 4px 15px rgba(67, 0, 86, 0.08);
            border: 1px solid rgba(67, 0, 86, 0.1);
        }

        .stat-item {
            text-align: center;
            flex: 1;
            position: relative;
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-2px);
        }

        .stat-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 40px;
            background: linear-gradient(180deg, transparent, rgba(67, 0, 86, 0.2), transparent);
        }

        .stat-label {
            display: block;
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #430056, #5a0070);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            min-height: calc(100vh - 2rem);
            justify-content: center;
            margin: 1rem auto;
        }

        .modal-dialog {
            position: relative;
            width: auto;
            max-width: 450px;
            margin: 1rem auto;
            pointer-events: none;
        }

        .modal-lg {
            max-width: 450px;
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
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.5);
        }

        .modal-body {
            position: relative;
            flex: 1 1 auto;
            padding: 1rem;
        }

        .modal-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            padding: 0.75rem;
            border-top: 1px solid #dee2e6;
            border-bottom-right-radius: calc(0.3rem - 1px);
            border-bottom-left-radius: calc(0.3rem - 1px);
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
            background: linear-gradient(135deg, #430056, #5a0070);
            color: white;
            border: none;
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .modal-title {
            font-weight: 800;
            font-size: 1.5rem;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .close {
            color: white;
            opacity: 0.9;
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1;
            text-shadow: none;
            transition: opacity 0.3s;
        }

        .close:hover {
            color: white;
            opacity: 1;
        }

        .close:focus {
            outline: none;
        }

        .modal-footer {
            background: linear-gradient(135deg, #fafbfc, #f8fafc);
            border: none;
            padding: 2rem 2.5rem;
            justify-content: center;
            gap: 1rem;
            border-top: 1px solid rgba(67, 0, 86, 0.1);
        }

        .btn-warning {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            color: white;
            font-weight: 700;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-size: 1.125rem;
            box-shadow: 0 8px 25px rgba(251, 191, 36, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-warning::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-warning:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(251, 191, 36, 0.5);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            color: white;
            font-weight: 700;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-size: 1.125rem;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-success::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-success:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.5);
        }

        .btn-secondary {
            background: #6b7280;
            border: none;
            color: white;
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1rem;
        }

        .btn-secondary:hover {
            background: #4b5563;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .modal-dialog {
                max-width: 400px;
                margin: 1rem;
            }

            .modal-lg {
                max-width: 400px;
            }
        }

        @media (max-width: 768px) {
            .modal-dialog {
                max-width: 90%;
                margin: 0.5rem auto;
            }

            .modal-lg {
                max-width: 90%;
            }

            .modal-dialog-centered {
                min-height: calc(100vh - 1rem);
                margin: 0.5rem auto;
            }

            .quiz-main-title {
                font-size: 1.875rem;
            }

            .quiz-stats {
                justify-content: center;
                margin-top: 1.5rem;
                gap: 1rem;
            }

            .stat-item {
                padding: 0.75rem 1rem;
            }

            .submit-actions {
                flex-direction: column;
            }

            .btn-modern {
                width: 100%;
            }

            .modern-question-card {
                margin-bottom: 1.5rem;
            }

            .question-content {
                padding: 1.5rem;
            }

            .quiz-submit-card {
                padding: 2rem;
            }

            .quiz-instructions-card .card-body {
                padding: 1.5rem;
            }

            .simple-quiz-results {
                padding: 1.5rem 1rem;
            }

            .result-title {
                font-size: 1.25rem;
            }

            .score-circle {
                width: 80px;
                height: 80px;
            }

            .score-text {
                font-size: 1.25rem;
            }

            .stat-group {
                flex-direction: column;
                gap: 1rem;
            }

            .stat-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                border-bottom: 1px solid #e5e7eb;
            }

            .stat-item:last-child {
                border-bottom: none;
            }
        }

        @media (max-width: 576px) {
            .modal-dialog {
                max-width: 95%;
                margin: 0.25rem auto;
            }

            .modal-dialog-centered {
                min-height: calc(100vh - 0.5rem);
                margin: 0.25rem auto;
            }
        }
    </style>
</x-app-layout>
