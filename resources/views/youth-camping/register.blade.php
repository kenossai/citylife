<x-app-layout>
    <x-slot name="title">Register for {{ $youthCamping->name }}</x-slot>

<style>
/* Additional styles for the comprehensive volunteer form */
.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e5e5e5;
}

.form-section:last-child {
    border-bottom: none;
}

.form-section__title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
}

.form-section__text {
    color: #666;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    line-height: 1.5;
}

.form-one__label {
    display: block;
    font-weight: 500;
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-one__radio-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-one__radio {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 0.95rem;
}

.form-one__radio input[type="radio"] {
    margin-right: 0.5rem;
}

.form-one__checkbox {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    font-size: 0.95rem;
    line-height: 1.4;
}

.form-one__checkbox input[type="checkbox"] {
    margin-right: 0.5rem;
    margin-top: 0.15rem;
}

.form-one__control select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.95rem;
    background-color: white;
}

.form-one__control textarea {
    min-height: 100px;
    resize: vertical;
}

/* Grid Layout Styles */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.form-grid-2 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.form-grid-full {
    grid-column: 1 / -1;
}

@media (max-width: 768px) {
    .form-section__title {
        font-size: 1.1rem;
    }

    .form-one__radio-group {
        gap: 0.75rem;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .form-grid-2 {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
</style>

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h3 class="text-white">Youth Ministry</h3>
        <h2 class="page-header__title">Register Your Child</h2>
        <p class="section-header__text">{{ $youthCamping->name }} - {{ $youthCamping->start_date->format('M j') }} - {{ $youthCamping->end_date->format('M j, Y') }}</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span><a href="{{ route('youth-camping.index') }}">Youth Camping</a></span></li>
            <li><span><a href="{{ route('youth-camping.show', $youthCamping) }}">{{ $youthCamping->name }}</a></span></li>
            <li><span>Registration</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>

<section class="become-volunteer section-space">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-lg-12 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="300ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 300ms; animation-name: fadeInUp;">
                <form action="{{ route('youth-camping.register.submit', $youthCamping) }}" method="POST" class="become-volunteer__form form-one">
                    @csrf
                    <h3 class="become-volunteer__form__title">Youth Camping Registration Form</h3>

                    @if(session('success'))
                        <div class="alert alert-success mb-4" style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger mb-4" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                            <ul style="margin: 0; padding-left: 1rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="become-volunteer__form__inner">
                        <!-- Camp Information Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Camp Information</h4>
                            <div class="form-grid-2">
                                <div>
                                    <strong>Event:</strong> {{ $youthCamping->name }}
                                </div>
                                <div>
                                    <strong>Dates:</strong> {{ $youthCamping->start_date->format('M j') }} - {{ $youthCamping->end_date->format('M j, Y') }}
                                </div>
                                <div>
                                    <strong>Location:</strong> {{ $youthCamping->location }}
                                </div>
                                <div>
                                    <strong>Cost:</strong> ${{ number_format($youthCamping->cost, 2) }}
                                </div>
                            </div>
                        </div>

                        <!-- Child Information Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Child Information</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__label">Child's First Name *</label>
                                    <input type="text" name="child_first_name" placeholder="Enter child's first name" class="form-one__control__input" value="{{ old('child_first_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Child's Last Name *</label>
                                    <input type="text" name="child_last_name" placeholder="Enter child's last name" class="form-one__control__input" value="{{ old('child_last_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Child's Date of Birth *</label>
                                    <input type="date" name="child_date_of_birth" class="form-one__control__input" value="{{ old('child_date_of_birth') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Child's Gender</label>
                                    <select name="child_gender" class="form-one__control__input">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('child_gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('child_gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Grade/School</label>
                                    <input type="text" name="child_grade_school" placeholder="e.g., Grade 7 - Central School" class="form-one__control__input" value="{{ old('child_grade_school') }}">
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">T-Shirt Size</label>
                                    <select name="child_t_shirt_size" class="form-one__control__input">
                                        <option value="">Select Size</option>
                                        <option value="XS" {{ old('child_t_shirt_size') == 'XS' ? 'selected' : '' }}>Extra Small</option>
                                        <option value="S" {{ old('child_t_shirt_size') == 'S' ? 'selected' : '' }}>Small</option>
                                        <option value="M" {{ old('child_t_shirt_size') == 'M' ? 'selected' : '' }}>Medium</option>
                                        <option value="L" {{ old('child_t_shirt_size') == 'L' ? 'selected' : '' }}>Large</option>
                                        <option value="XL" {{ old('child_t_shirt_size') == 'XL' ? 'selected' : '' }}>Extra Large</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Parent/Guardian Information Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Parent/Guardian Information</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__label">Your First Name *</label>
                                    <input type="text" name="parent_first_name" placeholder="Enter your first name" class="form-one__control__input" value="{{ old('parent_first_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Your Last Name *</label>
                                    <input type="text" name="parent_last_name" placeholder="Enter your last name" class="form-one__control__input" value="{{ old('parent_last_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Your Email Address *</label>
                                    <input type="email" name="parent_email" placeholder="your.email@example.com" class="form-one__control__input" value="{{ old('parent_email') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Your Phone Number *</label>
                                    <input type="tel" name="parent_phone" placeholder="Enter your phone number" class="form-one__control__input" value="{{ old('parent_phone') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Relationship to Child *</label>
                                    <select name="parent_relationship" class="form-one__control__input" required>
                                        <option value="">Select Relationship</option>
                                        <option value="mother" {{ old('parent_relationship') == 'mother' ? 'selected' : '' }}>Mother</option>
                                        <option value="father" {{ old('parent_relationship') == 'father' ? 'selected' : '' }}>Father</option>
                                        <option value="guardian" {{ old('parent_relationship') == 'guardian' ? 'selected' : '' }}>Guardian</option>
                                        <option value="other" {{ old('parent_relationship') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Contact & Address Information Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Contact & Address Information</h4>

                            <div class="form-grid">
                                <div class="form-one__control form-grid-full">
                                    <label class="form-one__label">Home Address *</label>
                                    <textarea name="home_address" rows="3" placeholder="Enter your full home address" class="form-one__control__input form-one__control__message" required>{{ old('home_address') }}</textarea>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">City *</label>
                                    <input type="text" name="city" placeholder="Enter city" class="form-one__control__input" value="{{ old('city') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Postal Code *</label>
                                    <input type="text" name="postal_code" placeholder="Enter postal code" class="form-one__control__input" value="{{ old('postal_code') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Home Phone</label>
                                    <input type="tel" name="home_phone" placeholder="Enter home phone" class="form-one__control__input" value="{{ old('home_phone') }}">
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Work Phone</label>
                                    <input type="tel" name="work_phone" placeholder="Enter work phone" class="form-one__control__input" value="{{ old('work_phone') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Emergency Contact</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__label">Emergency Contact Name *</label>
                                    <input type="text" name="emergency_contact_name" placeholder="Enter emergency contact name" class="form-one__control__input" value="{{ old('emergency_contact_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Emergency Contact Phone *</label>
                                    <input type="tel" name="emergency_contact_phone" placeholder="Enter emergency contact phone" class="form-one__control__input" value="{{ old('emergency_contact_phone') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Relationship to Child *</label>
                                    <input type="text" name="emergency_contact_relationship" placeholder="e.g., Father, Grandmother, Family Friend" class="form-one__control__input" value="{{ old('emergency_contact_relationship') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Information Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Medical Information</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__label">Medical Conditions</label>
                                    <textarea name="medical_conditions" rows="3" placeholder="List any medical conditions (separate with commas)" class="form-one__control__input form-one__control__message">{{ old('medical_conditions') }}</textarea>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Current Medications</label>
                                    <textarea name="medications" rows="3" placeholder="List any medications (separate with commas)" class="form-one__control__input form-one__control__message">{{ old('medications') }}</textarea>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Allergies</label>
                                    <textarea name="allergies" rows="3" placeholder="List any allergies (separate with commas)" class="form-one__control__input form-one__control__message">{{ old('allergies') }}</textarea>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Dietary Requirements</label>
                                    <textarea name="dietary_requirements" rows="3" placeholder="List any dietary requirements (separate with commas)" class="form-one__control__input form-one__control__message">{{ old('dietary_requirements') }}</textarea>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Swimming Ability</label>
                                    <select name="swimming_ability" class="form-one__control__input">
                                        <option value="">Select Swimming Ability</option>
                                        <option value="non_swimmer" {{ old('swimming_ability') == 'non_swimmer' ? 'selected' : '' }}>Non-swimmer</option>
                                        <option value="beginner" {{ old('swimming_ability') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate" {{ old('swimming_ability') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                        <option value="advanced" {{ old('swimming_ability') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    </select>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Doctor Name</label>
                                    <input type="text" name="doctor_name" placeholder="Enter doctor's name" class="form-one__control__input" value="{{ old('doctor_name') }}">
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Doctor Phone</label>
                                    <input type="tel" name="doctor_phone" placeholder="Enter doctor's phone" class="form-one__control__input" value="{{ old('doctor_phone') }}">
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Health Card Number</label>
                                    <input type="text" name="health_card_number" placeholder="Enter health card number" class="form-one__control__input" value="{{ old('health_card_number') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Consent & Permissions Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Consent & Permissions</h4>

                            <div class="form-one__control">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="consent_photo_video" value="1" {{ old('consent_photo_video') ? 'checked' : '' }} required>
                                    <strong>Photo/Video Consent *</strong><br>
                                    I consent to my child being photographed/videoed during camp activities for promotional and memory-keeping purposes.
                                </label>
                            </div>

                            <div class="form-one__control">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="consent_medical_treatment" value="1" {{ old('consent_medical_treatment') ? 'checked' : '' }} required>
                                    <strong>Medical Treatment Consent *</strong><br>
                                    I consent to emergency medical treatment if required and understand that every effort will be made to contact me first.
                                </label>
                            </div>

                            <div class="form-one__control">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="consent_activities" value="1" {{ old('consent_activities') ? 'checked' : '' }} required>
                                    <strong>Activities Consent *</strong><br>
                                    I consent to my child participating in all camp activities including outdoor activities, sports, and group activities.
                                </label>
                            </div>

                            <div class="form-one__control">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="consent_pickup_authorized_persons" value="1" {{ old('consent_pickup_authorized_persons') ? 'checked' : '' }} onchange="togglePickupPersons()">
                                    <strong>Pickup Authorization</strong><br>
                                    I authorize only the persons listed below to pick up my child from camp.
                                </label>
                            </div>

                            <div id="pickup_persons_section" style="margin-left: 1.5rem; {{ old('consent_pickup_authorized_persons') ? '' : 'display: none;' }}">
                                <div class="form-one__control">
                                    <label class="form-one__label">Authorized Pickup Persons</label>
                                    <textarea name="pickup_authorized_persons" rows="2" placeholder="List names of people authorized to pick up your child (separate with commas)" class="form-one__control__input form-one__control__message">{{ old('pickup_authorized_persons') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Additional Information</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__label">Special Needs</label>
                                    <textarea name="special_needs" rows="3" placeholder="Please describe any special needs or accommodations required for your child" class="form-one__control__input form-one__control__message">{{ old('special_needs') }}</textarea>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Additional Notes</label>
                                    <textarea name="additional_notes" rows="3" placeholder="Any additional information you'd like us to know about your child" class="form-one__control__input form-one__control__message">{{ old('additional_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Declaration Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Declaration</h4>
                            <p class="form-section__text">
                                <strong>Please review all information carefully before submitting.</strong>
                                You will receive a confirmation email once your registration is submitted.
                                Payment instructions will be included in the confirmation email.
                            </p>

                            <div class="form-one__control">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="declaration_consent" value="yes" required>
                                    I confirm that the information provided is accurate and complete, and I agree to the terms and conditions of the youth camping program. *
                                </label>
                            </div>
                        </div>

                        <div class="become-volunteer__form__bottom form-one__control">
                            <div style="display: flex; gap: 1rem;">
                                <a href="{{ route('youth-camping.show', $youthCamping) }}" class="citylife-btn" style="background-color: #6c757d; border-color: #6c757d;">
                                    <span class="citylife-btn__icon-box">
                                        <span class="citylife-btn__icon-box__inner"><span class="icon-arrow-left"></span></span>
                                    </span>
                                    <span class="citylife-btn__text">Cancel</span>
                                </a>
                                <button type="submit" class="citylife-btn citylife-btn--border-base">
                                    <span class="citylife-btn__icon-box">
                                        <span class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></span>
                                    </span>
                                    <span class="citylife-btn__text">Submit Registration</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
function togglePickupPersons() {
    const checkbox = document.getElementById('consent_pickup_authorized_persons') || document.querySelector('input[name="consent_pickup_authorized_persons"]');
    const section = document.getElementById('pickup_persons_section');

    if (checkbox && section) {
        if (checkbox.checked) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePickupPersons();
});
</script>

</x-app-layout>
