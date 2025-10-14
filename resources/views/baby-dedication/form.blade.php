<x-app-layout>
@section('title', 'Baby Dedication Registration')
@section('meta_description', 'Register your baby for dedication at City Life Church. Complete our simple form to begin the registration process.')

<style>
/* Form styling */
.form-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #2c5aa0;
}

.form-section__title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c5aa0;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.form-section__title i {
    margin-right: 0.5rem;
    font-size: 1.4rem;
}

.form-one__label {
    display: block;
    font-weight: 500;
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-one__label.required::after {
    content: ' *';
    color: #dc3545;
}

.form-one__input,
.form-one__select,
.form-one__textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.95rem;
    transition: border-color 0.3s;
}

.form-one__input:focus,
.form-one__select:focus,
.form-one__textarea:focus {
    outline: none;
    border-color: #2c5aa0;
    box-shadow: 0 0 0 2px rgba(44, 90, 160, 0.1);
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
    margin-bottom: 1rem;
}

.form-one__checkbox input[type="checkbox"] {
    margin-right: 0.5rem;
    margin-top: 0.2rem;
}

.alert {
    padding: 0.75rem 1rem;
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
</style>

{{-- Page Header --}}
<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ asset('assets/images/backgrounds/page-header-bg.jpg') }});"></div>
    <div class="page-header__shape-one float-bob-x"></div>
    <div class="page-header__shape-two float-bob-y"></div>
    <div class="container">
        <div class="page-header__inner">
            <h2>Baby Dedication Registration</h2>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><span class="sep">.</span></li>
                    <li><a href="{{ route('baby-dedication.index') }}">Baby Dedication</a></li>
                    <li><span class="sep">.</span></li>
                    <li>Registration</li>
                </ul>
            </div>
        </div>
    </div>
</section>
{{-- End Page Header --}}

{{-- Registration Form Section --}}
<section class="contact-one section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="contact-one__content">
                    <div class="sec-title">
                        <div class="sec-title__img">
                            <img src="{{ asset('assets/images/shapes/sec-title-s-1.png') }}" alt="">
                        </div>
                        <h6 class="sec-title__tagline">Registration Form</h6>
                        <h3 class="sec-title__title">Register Your Baby for Dedication</h3>
                    </div>
                    <p class="contact-one__content__text">
                        Please complete all sections of this form. Our pastoral team will review your application and contact you within 2-3 business days to schedule your baby's dedication.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Please correct the following errors:</strong>
                            <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('baby-dedication.store') }}" method="POST" class="form-one contact-one__form">
                        @csrf

                        {{-- Baby Information Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">
                                <i class="fas fa-baby"></i>
                                Baby Information
                            </h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Baby's First Name</label>
                                        <input type="text" name="baby_first_name" class="form-one__input"
                                               value="{{ old('baby_first_name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label">Baby's Middle Name</label>
                                        <input type="text" name="baby_middle_name" class="form-one__input"
                                               value="{{ old('baby_middle_name') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Baby's Last Name</label>
                                        <input type="text" name="baby_last_name" class="form-one__input"
                                               value="{{ old('baby_last_name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Date of Birth</label>
                                        <input type="date" name="baby_date_of_birth" class="form-one__input"
                                               value="{{ old('baby_date_of_birth') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Gender</label>
                                        <div class="form-one__radio-group">
                                            <label class="form-one__radio">
                                                <input type="radio" name="baby_gender" value="male"
                                                       {{ old('baby_gender') == 'male' ? 'checked' : '' }} required>
                                                Male
                                            </label>
                                            <label class="form-one__radio">
                                                <input type="radio" name="baby_gender" value="female"
                                                       {{ old('baby_gender') == 'female' ? 'checked' : '' }} required>
                                                Female
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label">Place of Birth</label>
                                        <input type="text" name="baby_place_of_birth" class="form-one__input"
                                               value="{{ old('baby_place_of_birth') }}" placeholder="e.g., London, UK">
                                    </div>
                                </div>
                            </div>

                            <div class="form-one__group">
                                <label class="form-one__label">Special Notes about Baby</label>
                                <textarea name="baby_special_notes" class="form-one__textarea" rows="3"
                                          placeholder="Any special considerations, medical needs, or other information we should know">{{ old('baby_special_notes') }}</textarea>
                            </div>
                        </div>

                        {{-- Father Information Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">
                                <i class="fas fa-male"></i>
                                Father's Information
                            </h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">First Name</label>
                                        <input type="text" name="father_first_name" class="form-one__input"
                                               value="{{ old('father_first_name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Last Name</label>
                                        <input type="text" name="father_last_name" class="form-one__input"
                                               value="{{ old('father_last_name') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Email</label>
                                        <input type="email" name="father_email" class="form-one__input"
                                               value="{{ old('father_email') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Phone Number</label>
                                        <input type="tel" name="father_phone" class="form-one__input"
                                               value="{{ old('father_phone') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__checkbox">
                                            <input type="checkbox" name="father_is_member" value="1"
                                                   {{ old('father_is_member') ? 'checked' : '' }}
                                                   onchange="toggleMembershipField('father')">
                                            Father is a City Life member
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6" id="father_membership_field" style="display: none;">
                                    <div class="form-one__group">
                                        <label class="form-one__label">Membership Number</label>
                                        <input type="text" name="father_membership_number" class="form-one__input"
                                               value="{{ old('father_membership_number') }}"
                                               onblur="checkMemberStatus('father', this.value)">
                                        <div id="father_member_result" class="member-check-result" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Mother Information Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">
                                <i class="fas fa-female"></i>
                                Mother's Information
                            </h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">First Name</label>
                                        <input type="text" name="mother_first_name" class="form-one__input"
                                               value="{{ old('mother_first_name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Last Name</label>
                                        <input type="text" name="mother_last_name" class="form-one__input"
                                               value="{{ old('mother_last_name') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Email</label>
                                        <input type="email" name="mother_email" class="form-one__input"
                                               value="{{ old('mother_email') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Phone Number</label>
                                        <input type="tel" name="mother_phone" class="form-one__input"
                                               value="{{ old('mother_phone') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__checkbox">
                                            <input type="checkbox" name="mother_is_member" value="1"
                                                   {{ old('mother_is_member') ? 'checked' : '' }}
                                                   onchange="toggleMembershipField('mother')">
                                            Mother is a City Life member
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6" id="mother_membership_field" style="display: none;">
                                    <div class="form-one__group">
                                        <label class="form-one__label">Membership Number</label>
                                        <input type="text" name="mother_membership_number" class="form-one__input"
                                               value="{{ old('mother_membership_number') }}"
                                               onblur="checkMemberStatus('mother', this.value)">
                                        <div id="mother_member_result" class="member-check-result" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Address Information Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">
                                <i class="fas fa-home"></i>
                                Address Information
                            </h4>

                            <div class="form-one__group">
                                <label class="form-one__label required">Address</label>
                                <textarea name="address" class="form-one__textarea" rows="2"
                                          placeholder="Street address" required>{{ old('address') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">City</label>
                                        <input type="text" name="city" class="form-one__input"
                                               value="{{ old('city') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Postal Code</label>
                                        <input type="text" name="postal_code" class="form-one__input"
                                               value="{{ old('postal_code') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Country</label>
                                        <input type="text" name="country" class="form-one__input"
                                               value="{{ old('country', 'United Kingdom') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Dedication Details Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">
                                <i class="fas fa-calendar"></i>
                                Dedication Preferences
                            </h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label">Preferred Dedication Date</label>
                                        <input type="date" name="preferred_dedication_date" class="form-one__input"
                                               value="{{ old('preferred_dedication_date') }}">
                                        <small style="color: #666; font-size: 0.85rem;">Optional - Leave blank for next available date</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Preferred Service</label>
                                        <select name="preferred_service" class="form-one__select" required>
                                            <option value="">Select a service</option>
                                            <option value="morning" {{ old('preferred_service') == 'morning' ? 'selected' : '' }}>Morning Service</option>
                                            <option value="evening" {{ old('preferred_service') == 'evening' ? 'selected' : '' }}>Evening Service</option>
                                            <option value="either" {{ old('preferred_service') == 'either' ? 'selected' : '' }}>Either Service</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-one__group">
                                <label class="form-one__label">Special Requests</label>
                                <textarea name="special_requests" class="form-one__textarea" rows="3"
                                          placeholder="Any special requests or considerations for the dedication service">{{ old('special_requests') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__checkbox">
                                            <input type="checkbox" name="photography_consent" value="1"
                                                   {{ old('photography_consent', '1') ? 'checked' : '' }}>
                                            I consent to photography during the service
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-one__group">
                                        <label class="form-one__checkbox">
                                            <input type="checkbox" name="video_consent" value="1"
                                                   {{ old('video_consent', '1') ? 'checked' : '' }}>
                                            I consent to video recording during the service
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Church Information Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">
                                <i class="fas fa-church"></i>
                                Church Information
                            </h4>

                            <div class="form-one__group">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="regular_attendees" value="1"
                                           {{ old('regular_attendees') ? 'checked' : '' }}
                                           onchange="toggleAttendanceField()">
                                    We are regular attendees of City Life Church
                                </label>
                            </div>

                            <div id="attendance_field" style="display: none;">
                                <div class="form-one__group">
                                    <label class="form-one__label">How long have you been attending?</label>
                                    <input type="text" name="how_long_attending" class="form-one__input"
                                           value="{{ old('how_long_attending') }}" placeholder="e.g., 2 years, 6 months">
                                </div>
                            </div>

                            <div class="form-one__group">
                                <label class="form-one__label">Previous Church (if applicable)</label>
                                <input type="text" name="previous_church" class="form-one__input"
                                       value="{{ old('previous_church') }}" placeholder="Name of previous church">
                            </div>

                            <div class="form-one__group">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="baptized_parents" value="1"
                                           {{ old('baptized_parents') ? 'checked' : '' }}>
                                    Both parents have been baptized
                                </label>
                            </div>

                            <div class="form-one__group">
                                <label class="form-one__label">Faith Commitment Statement</label>
                                <textarea name="faith_commitment" class="form-one__textarea" rows="4"
                                          placeholder="Please share your commitment to raising your child in the Christian faith">{{ old('faith_commitment') }}</textarea>
                            </div>
                        </div>

                        {{-- Emergency Contact Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">
                                <i class="fas fa-phone"></i>
                                Emergency Contact
                            </h4>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Name</label>
                                        <input type="text" name="emergency_contact_name" class="form-one__input"
                                               value="{{ old('emergency_contact_name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Relationship</label>
                                        <input type="text" name="emergency_contact_relationship" class="form-one__input"
                                               value="{{ old('emergency_contact_relationship') }}"
                                               placeholder="e.g., Grandmother, Uncle" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-one__group">
                                        <label class="form-one__label required">Phone Number</label>
                                        <input type="tel" name="emergency_contact_phone" class="form-one__input"
                                               value="{{ old('emergency_contact_phone') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Consent Section --}}
                        <div class="form-section">
                            <h4 class="form-section__title">
                                <i class="fas fa-check-circle"></i>
                                Consent & Agreement
                            </h4>

                            <div class="form-one__group">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="gdpr_consent" value="1"
                                           {{ old('gdpr_consent') ? 'checked' : '' }} required>
                                    <strong>I consent to City Life Church collecting and processing this information for the purpose of baby dedication arrangements. I understand that my data will be handled according to GDPR regulations and the church's privacy policy.</strong>
                                </label>
                            </div>

                            <div class="form-one__group">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="newsletter_consent" value="1"
                                           {{ old('newsletter_consent') ? 'checked' : '' }}>
                                    I would like to receive family-focused newsletters and updates from City Life Church
                                </label>
                            </div>
                        </div>

                        <div class="form-one__btn-box text-center">
                            <button type="submit" class="tolak-btn tolak-btn--base">
                                <b>Submit Registration</b><span></span>
                            </button>
                        </div>
                    </form>
                </div>
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
