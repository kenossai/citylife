<x-app-layout>
    @section('title', 'Quiz: ' . $lesson->title . ' - ' . $course->title)

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
                                    <button type="submit" class="btn-modern btn-primary" id="submitQuizBtn">
                                        <i class="icon-check me-2"></i>Submit Quiz
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Quiz Results Modal -->
                    <div class="modal fade" id="quizResultsModal" tabindex="-1" aria-labelledby="quizResultsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content modern-modal">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="quizResultsModalLabel">Quiz Results</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center" id="quizResultsContent">
                                    <!-- Results will be loaded here -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="continueBtn">Continue</button>
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
        document.addEventListener('DOMContentLoaded', function() {
            const quizForm = document.getElementById('quizForm');
            const submitBtn = document.getElementById('submitQuizBtn');
            const resultsModal = new bootstrap.Modal(document.getElementById('quizResultsModal'));

            quizForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Confirm submission
                if (!confirm('Are you sure you want to submit your quiz? You can retake it later if needed.')) {
                    return;
                }

                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Submitting...';

                // Get form data
                const formData = new FormData(quizForm);

                // Submit via AJAX
                fetch(quizForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showResults(data);
                    } else {
                        alert('Error submitting quiz: ' + (data.message || 'Unknown error'));
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="icon-check me-2"></i>Submit Quiz';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting the quiz. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="icon-check me-2"></i>Submit Quiz';
                });
            });

            function showResults(data) {
                const resultContent = document.getElementById('quizResultsContent');
                const continueBtn = document.getElementById('continueBtn');

                const passed = data.passed;
                const score = Math.round(data.score);

                resultContent.innerHTML = `
                    <div class="quiz-results">
                        <div class="score-display mb-4">
                            <div class="score-circle ${passed ? 'success' : 'warning'} mx-auto mb-3">
                                <span class="score-text">${score}%</span>
                            </div>
                            <h4 class="${passed ? 'text-success' : 'text-warning'}">
                                ${passed ? 'Congratulations! You Passed!' : 'Keep Trying!'}
                            </h4>
                        </div>

                        <div class="score-details mb-4">
                            <p class="mb-2"><strong>Score:</strong> ${data.correct_answers} out of ${data.total_questions} correct</p>
                            <p class="mb-2"><strong>Percentage:</strong> ${score}%</p>
                            <p class="mb-0"><strong>Status:</strong>
                                <span class="badge ${passed ? 'badge-success' : 'badge-warning'}">${passed ? 'Passed' : 'Not Passed'}</span>
                            </p>
                        </div>

                        <div class="next-steps">
                            ${passed
                                ? '<p class="text-success">Great job! You can now proceed to the next lesson.</p>'
                                : '<p class="text-warning">You need 70% or higher to pass. You can retake the quiz anytime.</p>'
                            }
                        </div>
                    </div>
                `;

                continueBtn.onclick = function() {
                    window.location.href = data.redirect_url;
                };

                resultsModal.show();
            }
        });
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
        }
    </style>
</x-app-layout>
