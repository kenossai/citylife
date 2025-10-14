<x-app-layout>
@section('title', 'Baby Dedication Registration')
@section('meta_description', 'Register your baby for dedication at City Life Church. Complete our simple form to begin the registration process.')

<style>
/* Additional styles for the comprehensive baby dedication form */
.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #6b4a9e;
}

.form-section:last-child {
    border-bottom: none;
}

.form-section__title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 1rem;
}

.form-section__text {
    color: #e0d4f7;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    line-height: 1.5;
}

.form-one__label {
    display: block;
    font-weight: 500;
    color: #ffffff;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-one__label.required::after {
    content: ' *';
    color: #ffc107;
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
    color: #ffffff;
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
    color: #ffffff;
    margin-bottom: 1rem;
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

.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.member-check-result {
    margin-top: 0.5rem;
    padding: 0.5rem;
    border-radius: 4px;
    font-size: 0.9rem;
}

.member-found {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.member-not-found {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
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

{{-- Page Header --}}
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/page-header-bg-1-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h3 class="text-white">Family Ministry</h3>
        <h2 class="page-header__title">Baby Dedication Registration</h2>
        <p class="section-header__text">Register your baby for dedication at City Life Church</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span><a href="{{ route('baby-dedication.index') }}">Baby Dedication</a></span></li>
            <li><span>Registration</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>
{{-- End Page Header --}}

{{-- Registration Form Section --}}
<section class="become-volunteer section-space">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-lg-12 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="300ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 300ms; animation-name: fadeInUp;">
                <form action="{{ route('baby-dedication.store') }}" method="POST" class="become-volunteer__form form-one" style="background-color: #260c47">
                    @csrf
                    <h3 class="become-volunteer__form__title text-white">Baby Dedication Registration Form</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong>Please correct the following errors:</strong>
                            <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="become-volunteer__form__inner"
                        @csrf

                        {{-- Baby Information Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">Baby Information</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__label required">Baby's First Name</label>
                                    <input type="text" name="baby_first_name" placeholder="Enter baby's first name" class="form-one__control__input" value="{{ old('baby_first_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Baby's Middle Name</label>
                                    <input type="text" name="baby_middle_name" placeholder="Enter baby's middle name" class="form-one__control__input" value="{{ old('baby_middle_name') }}">
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Baby's Last Name</label>
                                    <input type="text" name="baby_last_name" placeholder="Enter baby's last name" class="form-one__control__input" value="{{ old('baby_last_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Date of Birth</label>
                                    <input type="date" name="baby_date_of_birth" class="form-one__control__input" value="{{ old('baby_date_of_birth') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Gender</label>
                                    <select name="baby_gender" class="form-one__control__input" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('baby_gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('baby_gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Place of Birth</label>
                                    <input type="text" name="baby_place_of_birth" placeholder="e.g., London, UK" class="form-one__control__input" value="{{ old('baby_place_of_birth') }}">
                                </div>

                                <div class="form-one__control form-grid-full">
                                    <label class="form-one__label">Special Notes about Baby</label>
                                    <textarea name="baby_special_notes" rows="3" placeholder="Any special considerations, medical needs, or other information we should know" class="form-one__control__input form-one__control__message">{{ old('baby_special_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Father Information Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">Father's Information</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__label required">First Name</label>
                                    <input type="text" name="father_first_name" placeholder="Enter father's first name" class="form-one__control__input" value="{{ old('father_first_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Last Name</label>
                                    <input type="text" name="father_last_name" placeholder="Enter father's last name" class="form-one__control__input" value="{{ old('father_last_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Email</label>
                                    <input type="email" name="father_email" placeholder="father.email@example.com" class="form-one__control__input" value="{{ old('father_email') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Phone Number</label>
                                    <input type="tel" name="father_phone" placeholder="Enter father's phone number" class="form-one__control__input" value="{{ old('father_phone') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__checkbox">
                                        <input type="checkbox" name="father_is_member" value="1" {{ old('father_is_member') ? 'checked' : '' }} onchange="toggleMembershipField('father')">
                                        Father is a City Life member
                                    </label>
                                </div>

                                <div class="form-one__control" id="father_membership_field" style="display: none;">
                                    <label class="form-one__label">Membership Number</label>
                                    <input type="text" name="father_membership_number" placeholder="Enter membership number" class="form-one__control__input" value="{{ old('father_membership_number') }}" onblur="checkMemberStatus('father', this.value)">
                                    <div id="father_member_result" class="member-check-result" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Mother Information Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">Mother's Information</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__label required">First Name</label>
                                    <input type="text" name="mother_first_name" placeholder="Enter mother's first name" class="form-one__control__input" value="{{ old('mother_first_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Last Name</label>
                                    <input type="text" name="mother_last_name" placeholder="Enter mother's last name" class="form-one__control__input" value="{{ old('mother_last_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Email</label>
                                    <input type="email" name="mother_email" placeholder="mother.email@example.com" class="form-one__control__input" value="{{ old('mother_email') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Phone Number</label>
                                    <input type="tel" name="mother_phone" placeholder="Enter mother's phone number" class="form-one__control__input" value="{{ old('mother_phone') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__checkbox">
                                        <input type="checkbox" name="mother_is_member" value="1" {{ old('mother_is_member') ? 'checked' : '' }} onchange="toggleMembershipField('mother')">
                                        Mother is a City Life member
                                    </label>
                                </div>

                                <div class="form-one__control" id="mother_membership_field" style="display: none;">
                                    <label class="form-one__label">Membership Number</label>
                                    <input type="text" name="mother_membership_number" placeholder="Enter membership number" class="form-one__control__input" value="{{ old('mother_membership_number') }}" onblur="checkMemberStatus('mother', this.value)">
                                    <div id="mother_member_result" class="member-check-result" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Address Information Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">Address & Contact Information</h4>

                            <div class="form-grid">
                                <div class="form-one__control form-grid-full">
                                    <label class="form-one__label required">Home Address</label>
                                    <textarea name="address" rows="3" placeholder="Enter your full home address" class="form-one__control__input form-one__control__message" required>{{ old('address') }}</textarea>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">City</label>
                                    <input type="text" name="city" placeholder="Enter city" class="form-one__control__input" value="{{ old('city') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Postal Code</label>
                                    <input type="text" name="postal_code" placeholder="Enter postal code" class="form-one__control__input" value="{{ old('postal_code') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Country</label>
                                    <input type="text" name="country" placeholder="Enter country" class="form-one__control__input" value="{{ old('country', 'United Kingdom') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Emergency Contact Name</label>
                                    <input type="text" name="emergency_contact_name" placeholder="Enter emergency contact name" class="form-one__control__input" value="{{ old('emergency_contact_name') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Emergency Contact Relationship</label>
                                    <input type="text" name="emergency_contact_relationship" placeholder="e.g., Grandmother, Uncle" class="form-one__control__input" value="{{ old('emergency_contact_relationship') }}" required>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Emergency Contact Phone</label>
                                    <input type="tel" name="emergency_contact_phone" placeholder="Enter emergency contact phone" class="form-one__control__input" value="{{ old('emergency_contact_phone') }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Dedication Preferences Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">Dedication Preferences</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__label">Preferred Dedication Date</label>
                                    <input type="date" name="preferred_dedication_date" class="form-one__control__input" value="{{ old('preferred_dedication_date') }}">
                                    <p class="form-section__text" style="margin-top: 0.5rem; margin-bottom: 0;">Optional - Leave blank for next available date</p>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label required">Preferred Service</label>
                                    <select name="preferred_service" class="form-one__control__input" required>
                                        <option value="">Select a service</option>
                                        <option value="morning" {{ old('preferred_service') == 'morning' ? 'selected' : '' }}>Morning Service</option>
                                        <option value="evening" {{ old('preferred_service') == 'evening' ? 'selected' : '' }}>Evening Service</option>
                                        <option value="either" {{ old('preferred_service') == 'either' ? 'selected' : '' }}>Either Service</option>
                                    </select>
                                </div>

                                <div class="form-one__control form-grid-full">
                                    <label class="form-one__label">Special Requests</label>
                                    <textarea name="special_requests" rows="3" placeholder="Any special requests or considerations for the dedication service" class="form-one__control__input form-one__control__message">{{ old('special_requests') }}</textarea>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__checkbox">
                                        <input type="checkbox" name="photography_consent" value="1" {{ old('photography_consent', '1') ? 'checked' : '' }}>
                                        I consent to photography during the service
                                    </label>
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__checkbox">
                                        <input type="checkbox" name="video_consent" value="1" {{ old('video_consent', '1') ? 'checked' : '' }}>
                                        I consent to video recording during the service
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Church Information Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">Church Information</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__checkbox">
                                        <input type="checkbox" name="regular_attendees" value="1" {{ old('regular_attendees') ? 'checked' : '' }} onchange="toggleAttendanceField()">
                                        We are regular attendees of City Life Church
                                    </label>
                                </div>

                                <div class="form-one__control" id="attendance_field" style="display: none;">
                                    <label class="form-one__label">How long have you been attending?</label>
                                    <input type="text" name="how_long_attending" placeholder="e.g., 2 years, 6 months" class="form-one__control__input" value="{{ old('how_long_attending') }}">
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__label">Previous Church (if applicable)</label>
                                    <input type="text" name="previous_church" placeholder="Name of previous church" class="form-one__control__input" value="{{ old('previous_church') }}">
                                </div>

                                <div class="form-one__control">
                                    <label class="form-one__checkbox">
                                        <input type="checkbox" name="baptized_parents" value="1" {{ old('baptized_parents') ? 'checked' : '' }}>
                                        Both parents have been baptized
                                    </label>
                                </div>

                                <div class="form-one__control form-grid-full">
                                    <label class="form-one__label">Faith Commitment Statement</label>
                                    <textarea name="faith_commitment" rows="4" placeholder="Please share your commitment to raising your child in the Christian faith" class="form-one__control__input form-one__control__message">{{ old('faith_commitment') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Consent & Agreement Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">Consent & Agreement</h4>
                            <p class="form-section__text">
                                <strong>Please review all information carefully before submitting.</strong>
                                You will receive a confirmation email once your registration is submitted.
                                Our pastoral team will contact you within 2-3 business days to schedule your baby's dedication.
                            </p>

                            <div class="form-one__control">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="gdpr_consent" value="1" {{ old('gdpr_consent') ? 'checked' : '' }} required>
                                    <strong>Data Processing Consent *</strong><br>
                                    I consent to City Life Church collecting and processing this information for the purpose of baby dedication arrangements. I understand that my data will be handled according to GDPR regulations and the church's privacy policy.
                                </label>
                            </div>

                            <div class="form-one__control">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="newsletter_consent" value="1" {{ old('newsletter_consent') ? 'checked' : '' }}>
                                    <strong>Newsletter Subscription</strong><br>
                                    I would like to receive family-focused newsletters and updates from City Life Church
                                </label>
                            </div>
                        </div>

                        <div class="become-volunteer__form__bottom form-one__control">
                            <div style="display: flex; gap: 1rem;">
                                <a href="{{ route('baby-dedication.index') }}" class="citylife-btn" style="background-color: #6c757d; border-color: #6c757d;">
                                    <span class="citylife-btn__icon-box">
                                        <span class="citylife-btn__icon-box__inner"><span class="icon-arrow-left"></span></span>
                                    </span>
                                    <span class="citylife-btn__text">Back to Information</span>
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
function toggleMembershipField(parent) {
    const checkbox = document.querySelector(`input[name="${parent}_is_member"]`);
    const field = document.getElementById(`${parent}_membership_field`);

    if (checkbox.checked) {
        field.style.display = 'block';
    } else {
        field.style.display = 'none';
        document.querySelector(`input[name="${parent}_membership_number"]`).value = '';
        document.getElementById(`${parent}_member_result`).style.display = 'none';
    }
}

function toggleAttendanceField() {
    const checkbox = document.querySelector('input[name="regular_attendees"]');
    const field = document.getElementById('attendance_field');

    if (checkbox.checked) {
        field.style.display = 'block';
    } else {
        field.style.display = 'none';
        document.querySelector('input[name="how_long_attending"]').value = '';
    }
}

async function checkMemberStatus(parent, membershipNumber) {
    if (!membershipNumber) {
        document.getElementById(`${parent}_member_result`).style.display = 'none';
        return;
    }

    try {
        const response = await fetch(`{{ route('baby-dedication.check-member') }}?membership_number=${membershipNumber}`);
        const data = await response.json();
        const resultDiv = document.getElementById(`${parent}_member_result`);

        if (data.exists) {
            resultDiv.className = 'member-check-result member-found';
            resultDiv.innerHTML = `✓ Member found: ${data.name}`;

            // Optionally pre-fill email and phone if they match
            if (data.email) {
                document.querySelector(`input[name="${parent}_email"]`).value = data.email;
            }
            if (data.phone) {
                document.querySelector(`input[name="${parent}_phone"]`).value = data.phone;
            }
        } else {
            resultDiv.className = 'member-check-result member-not-found';
            resultDiv.innerHTML = '⚠ Membership number not found. Please check the number or contact the office.';
        }

        resultDiv.style.display = 'block';
    } catch (error) {
        console.error('Error checking member status:', error);
    }
}

// Initialize fields based on old values
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('input[name="father_is_member"]').checked) {
        toggleMembershipField('father');
    }
    if (document.querySelector('input[name="mother_is_member"]').checked) {
        toggleMembershipField('mother');
    }
    if (document.querySelector('input[name="regular_attendees"]').checked) {
        toggleAttendanceField();
    }
});
</script>

</x-app-layout>
