<x-app-layout>
    @section('title', 'Quiz: ' . $lesson->title . ' - ' . $course->title)

    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <div class="container">
            <h3 class="text-white">Lesson {{ $lesson->lesson_number }} Quiz</h3>
            <h2 class="page-header__title">{{ $lesson->title }}</h2>
            <p class="section-header__text">{{ $course->title }}</p>
            <ul class="cleenhearts-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><i class="icon-book"></i> <a href="{{ route('courses.index') }}">Courses</a></li>
                <li><i class="icon-graduation-cap"></i> <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a></li>
                <li><i class="icon-list"></i> <a href="{{ route('courses.lessons', $course->slug) }}">Lessons</a></li>
                <li><i class="icon-file-text"></i> <a href="{{ route('courses.lesson.show', [$course->slug, $lesson->slug]) }}">{{ $lesson->title }}</a></li>
                <li><span>Quiz</span></li>
            </ul>
        </div>
    </section>

    <section class="section-space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Quiz Instructions -->
                    <div class="quiz-instructions mb-5">
                        <div class="alert alert-info">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="alert-heading mb-2">
                                        <i class="icon-info-circle me-2"></i>Quiz Instructions
                                    </h5>
                                    <ul class="mb-0">
                                        <li>This quiz has {{ count($quizQuestions) }} questions</li>
                                        <li>You need 70% or higher to pass</li>
                                        <li>You can retake the quiz if needed</li>
                                        <li>Take your time and read each question carefully</li>
                                    </ul>
                                </div>
                                <div class="col-md-4 text-end">
                                    @if($progress->quiz_score)
                                        <div class="previous-score">
                                            <h6>Previous Score:</h6>
                                            <span class="badge {{ $progress->quiz_score >= 70 ? 'bg-success' : 'bg-warning' }} p-2" style="font-size: 16px;">
                                                {{ round($progress->quiz_score) }}%
                                            </span>
                                            <div class="text-muted small mt-1">
                                                Attempts: {{ $progress->attempts }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quiz Form -->
                    <div class="quiz-container">
                        <form id="quizForm" action="{{ route('courses.lesson.quiz.submit', [$course->slug, $lesson->slug]) }}" method="POST">
                            @csrf

                            @foreach($quizQuestions as $index => $question)
                                <div class="question-card mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="question-header mb-3">
                                                <h6 class="question-number mb-2">
                                                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                                    Question {{ $index + 1 }} of {{ count($quizQuestions) }}
                                                </h6>
                                                <h5 class="question-text">{{ $question['question'] }}</h5>
                                            </div>

                                            <div class="question-content">
                                                @if($question['type'] === 'multiple_choice')
                                                    <div class="multiple-choice-options">
                                                        @foreach($question['options'] as $optionIndex => $option)
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="radio"
                                                                       name="answers[{{ $index }}]"
                                                                       value="{{ chr(65 + $optionIndex) }}"
                                                                       id="question_{{ $index }}_option_{{ $optionIndex }}"
                                                                       required>
                                                                <label class="form-check-label" for="question_{{ $index }}_option_{{ $optionIndex }}">
                                                                    {{ $option }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($question['type'] === 'short_answer')
                                                    <div class="short-answer">
                                                        <textarea class="form-control"
                                                                  name="answers[{{ $index }}]"
                                                                  rows="3"
                                                                  placeholder="Enter your answer here..."
                                                                  required></textarea>
                                                        <small class="form-text text-muted">Provide a brief answer (2-3 sentences).</small>
                                                    </div>
                                                @elseif($question['type'] === 'essay')
                                                    <div class="essay-answer">
                                                        <textarea class="form-control"
                                                                  name="answers[{{ $index }}]"
                                                                  rows="6"
                                                                  placeholder="Write your detailed response here..."
                                                                  required></textarea>
                                                        <small class="form-text text-muted">Provide a detailed response explaining your thoughts and understanding.</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Submit Section -->
                            <div class="quiz-submit mt-5">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Ready to Submit?</h6>
                                        <p class="text-muted mb-4">Please review your answers before submitting. You can retake the quiz if needed.</p>

                                        <div class="submit-actions">
                                            <button type="button" class="btn btn-outline-secondary me-3" onclick="window.history.back()">
                                                <i class="icon-arrow-left"></i> Back to Lesson
                                            </button>
                                            <button type="submit" class="btn btn-success" id="submitQuizBtn">
                                                <i class="icon-check"></i> Submit Quiz
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Quiz Results Modal -->
                    <div class="modal fade" id="quizResultsModal" tabindex="-1" aria-labelledby="quizResultsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
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
                        submitBtn.innerHTML = '<i class="icon-check"></i> Submit Quiz';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting the quiz. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="icon-check"></i> Submit Quiz';
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
                            <div class="score-circle ${passed ? 'success' : 'warning'} mx-auto mb-3" style="width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: ${passed ? '#d4edda' : '#fff3cd'}; border: 4px solid ${passed ? '#28a745' : '#ffc107'};">
                                <span style="font-size: 28px; font-weight: bold; color: ${passed ? '#155724' : '#856404'};">${score}%</span>
                            </div>
                            <h4 class="${passed ? 'text-success' : 'text-warning'}">
                                ${passed ? 'Congratulations! You Passed!' : 'Keep Trying!'}
                            </h4>
                        </div>

                        <div class="score-details mb-4">
                            <p class="mb-2"><strong>Score:</strong> ${data.correct_answers} out of ${data.total_questions} correct</p>
                            <p class="mb-2"><strong>Percentage:</strong> ${score}%</p>
                            <p class="mb-0"><strong>Status:</strong>
                                <span class="badge ${passed ? 'bg-success' : 'bg-warning'}">${passed ? 'Passed' : 'Not Passed'}</span>
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
        .question-card {
            transition: all 0.3s ease;
        }

        .question-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .question-number .badge {
            font-size: 14px;
        }

        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }

        .form-check-label {
            font-size: 15px;
            line-height: 1.6;
            cursor: pointer;
        }

        .form-check {
            padding: 8px 12px;
            border-radius: 6px;
            transition: background-color 0.2s ease;
        }

        .form-check:hover {
            background-color: #f8f9fa;
        }

        .quiz-submit .card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .previous-score {
            text-align: center;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
    </style>
</x-app-layout>
