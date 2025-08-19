<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title', 'Quiz: ' . $lesson->title . ' - ' . $course->title); ?>

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
                                fetch(formElement.action, {
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
                                    modal.hide();
                                    alert('An error occurred. Please try again.');
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
                            <a href="<?php echo e(route('home')); ?>" class="breadcrumb-link">
                                <i class="icon-home"></i> Home
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <a href="<?php echo e(route('courses.index')); ?>" class="breadcrumb-link">
                                <i class="icon-book"></i> Courses
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <a href="<?php echo e(route('courses.show', $course->slug)); ?>" class="breadcrumb-link">
                                <i class="icon-graduation-cap"></i> <?php echo e($course->title); ?>

                            </a>
                            <span class="breadcrumb-separator">></span>
                            <a href="<?php echo e(route('courses.lessons', $course->slug)); ?>" class="breadcrumb-link">
                                <i class="icon-list"></i> Lessons
                            </a>
                            <span class="breadcrumb-separator">></span>
                            <span class="breadcrumb-current">Quiz</span>
                        </div>
                        <h3 class="quiz-lesson-title mb-1">Lesson <?php echo e($lesson->lesson_number); ?> Quiz</h3>
                        <h2 class="quiz-main-title mb-2"><?php echo e($lesson->title); ?></h2>
                        <p class="quiz-subtitle"><?php echo e($course->title); ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="quiz-stats">
                        <?php if($progress->quiz_score): ?>
                            <div class="stat-item">
                                <div class="stat-value <?php echo e($progress->quiz_score >= 70 ? 'text-success' : 'text-warning'); ?>">
                                    <?php echo e(round($progress->quiz_score)); ?>%
                                </div>
                                <div class="stat-label">Previous Score</div>
                            </div>
                        <?php endif; ?>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e(count($quizQuestions)); ?></div>
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
                                        <li>This quiz has <?php echo e(count($quizQuestions)); ?> questions</li>
                                        <li>You need 70% or higher to pass</li>
                                        <li>You can retake the quiz if needed</li>
                                        <li>Take your time and read each question carefully</li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <?php if($progress->quiz_score): ?>
                                        <div class="previous-score-card">
                                            <div class="score-header">Previous Score</div>
                                            <div class="score-value <?php echo e($progress->quiz_score >= 70 ? 'success' : 'warning'); ?>">
                                                <?php echo e(round($progress->quiz_score)); ?>%
                                            </div>
                                            <div class="score-meta">
                                                Attempts: <?php echo e($progress->attempts); ?>

                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quiz Form -->
                    <div class="quiz-form-container">
                        <form id="quizForm" action="<?php echo e(route('courses.lesson.quiz.submit', [$course->slug, $lesson->slug])); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <?php $__currentLoopData = $quizQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="modern-question-card">
                                    <div class="question-header">
                                        <div class="question-number">
                                            Question <?php echo e($index + 1); ?>

                                        </div>
                                        <div class="question-progress">
                                            <?php echo e($index + 1); ?> of <?php echo e(count($quizQuestions)); ?>

                                        </div>
                                    </div>
                                    <div class="question-content">
                                        <h6 class="question-text"><?php echo e($question['question']); ?></h6>
                                        <?php if($question['type'] === 'multiple_choice'): ?>
                                            <div class="options-container">
                                                <?php $__currentLoopData = $question['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionIndex => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <label class="option-card" for="question_<?php echo e($index); ?>_option_<?php echo e($optionIndex); ?>">
                                                        <input class="option-input" type="radio"
                                                               name="answers[<?php echo e($index); ?>]"
                                                               value="<?php echo e(chr(65 + $optionIndex)); ?>"
                                                               id="question_<?php echo e($index); ?>_option_<?php echo e($optionIndex); ?>"
                                                               required>
                                                        <div class="option-content">
                                                            <div class="option-letter"><?php echo e(chr(65 + $optionIndex)); ?></div>
                                                            <div class="option-text"><?php echo e($option); ?></div>
                                                        </div>
                                                    </label>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php elseif($question['type'] === 'short_answer'): ?>
                                            <div class="answer-input-container">
                                                <textarea class="modern-textarea"
                                                          name="answers[<?php echo e($index); ?>]"
                                                          rows="3"
                                                          placeholder="Enter your answer here..."
                                                          required></textarea>
                                                <div class="input-help">Provide a brief answer (2-3 sentences).</div>
                                            </div>
                                        <?php elseif($question['type'] === 'essay'): ?>
                                            <div class="answer-input-container">
                                                <textarea class="modern-textarea"
                                                          name="answers[<?php echo e($index); ?>]"
                                                          rows="6"
                                                          placeholder="Write your detailed response here..."
                                                          required></textarea>
                                                <div class="input-help">Provide a detailed response explaining your thoughts and understanding.</div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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

        /* Simple Quiz Results Styles */
        .simple-quiz-results {
            padding: 1.5rem 1rem;
            text-align: center;
            background: #ffffff;
            border-radius: 16px;
            margin: -1rem;
        }

        /* Simple Header */
        .simple-header {
            margin-bottom: 1.5rem;
        }

        .result-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .result-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin: 0;
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
            margin-top: 1.5rem;
        }

        .stat-group {
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            border-radius: 10px;
            padding: 1rem;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-label {
            display: block;
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .stat-value {
            display: block;
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
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
        }        .modal-backdrop {
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
            color: white;
        }

        .close {
            color: white;
            opacity: 0.8;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            text-shadow: none;
        }

        .close:hover {
            color: white;
            opacity: 1;
        }

        .close:focus {
            outline: none;
        }

        .modal-footer {
            background: #f8fafc;
            border: none;
            padding: 1.5rem 2rem;
            justify-content: center;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .btn-secondary {
            background: #6b7280;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .btn-secondary:hover {
            background: #4b5563;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\kenos\Documents\Github\citylife\resources\views/pages/course/quiz.blade.php ENDPATH**/ ?>