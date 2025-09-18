<x-app-layout>
@section('title', 'Volunteer')
@section('meta_description', 'Join our volunteer team and make a difference in the community. Explore opportunities to serve and connect with others.')

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
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h3 class="text-white">Our Volunteer Opportunities</h3>
        <h2 class="page-header__title">Make a Difference in Your Community</h2>
        <p class="section-header__text">Join our volunteer team and explore opportunities to serve and connect with others.</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Volunteer</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>

<section class="become-volunteer section-space">
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-lg-12 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="300ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 300ms; animation-name: fadeInUp;">
                <form action="{{ route('volunteer.store') }}" method="POST" class="become-volunteer__form form-one">
                    @csrf
                    <h3 class="become-volunteer__form__title">Volunteer Application Form</h3>

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
                        <!-- Application Type Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Your Application</h4>
                            <p class="form-section__text">This application is to volunteer...</p>

                            <div class="form-one__control">
                                <label class="form-one__label">Application Type *</label>
                                <div class="form-one__radio-group">
                                    <label class="form-one__radio">
                                        <input type="radio" name="application_type" value="event_only" required>
                                        For specific events only
                                    </label>
                                    <label class="form-one__radio">
                                        <input type="radio" name="application_type" value="ongoing" required>
                                        To join the team on an ongoing basis
                                    </label>
                                </div>
                            </div><!-- /.form-one__control -->

                            <div class="form-one__control">
                                <label class="form-one__label">Which team would you like to apply for? *</label>
                                <select name="team" class="form-one__control__input" required>
                                    <option value="">Select a team...</option>
                                    <option value="stewarding">Stewarding Team</option>
                                    <option value="worship">Worship Team</option>
                                    <option value="technical">Technical Team</option>
                                    <option value="children">Children's Ministry</option>
                                    <option value="hospitality">Hospitality Team</option>
                                    <option value="prayer">Prayer Team</option>
                                    <option value="technical">Technical Team</option>
                                    <option value="facilities">Facilities Team (DIY)</option>
                                </select>
                            </div><!-- /.form-one__control -->
                        </div>

                        <!-- Personal Details Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Personal Details</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__label">First & Last Name *</label>
                                    <input type="text" name="name" placeholder="Enter your full name" class="form-one__control__input" value="{{ old('name') }}" required>
                                </div><!-- /.form-one__control -->

                                <div class="form-one__control">
                                    <label class="form-one__label">Date of Birth *</label>
                                    <input type="date" name="date_of_birth" class="form-one__control__input" value="{{ old('date_of_birth') }}" required>
                                </div><!-- /.form-one__control -->

                                <div class="form-one__control">
                                    <label class="form-one__label">Sex</label>
                                    <select name="sex" class="form-one__control__input">
                                        <option value="">Select...</option>
                                        <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="prefer_not_to_say" {{ old('sex') == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                    </select>
                                </div><!-- /.form-one__control -->

                                <div class="form-one__control">
                                    <label class="form-one__label">Email *</label>
                                    <input type="email" name="email" placeholder="your.email@example.com" class="form-one__control__input" value="{{ old('email') }}" required>
                                </div><!-- /.form-one__control -->

                                <div class="form-one__control">
                                    <label class="form-one__label">Mobile *</label>
                                    <input type="tel" name="mobile" placeholder="Enter your mobile number" class="form-one__control__input" value="{{ old('mobile') }}" required>
                                </div><!-- /.form-one__control -->

                                <div class="form-one__control form-grid-full">
                                    <label class="form-one__label">Address *</label>
                                    <textarea name="address" rows="3" placeholder="Enter your full address" class="form-one__control__input form-one__control__message" required>{{ old('address') }}</textarea>
                                </div><!-- /.form-one__control -->
                            </div>
                        </div>

                        <!-- Medical & First Aid Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Medical & First Aid</h4>

                            <div class="form-one__control">
                                <label class="form-one__label">Are you a qualified medical professional (e.g., doctor, nurse, paramedic) who could be identified in the event of a medical emergency? *</label>
                                <div class="form-one__radio-group">
                                    <label class="form-one__radio">
                                        <input type="radio" name="medical_professional" value="yes" required>
                                        Yes
                                    </label>
                                    <label class="form-one__radio">
                                        <input type="radio" name="medical_professional" value="no" required>
                                        No
                                    </label>
                                </div>
                            </div><!-- /.form-one__control -->

                            <div class="form-one__control">
                                <label class="form-one__label">Do you hold a valid UK first aid certificate, to be identified as part of the First Aid Team? *</label>
                                <div class="form-one__radio-group">
                                    <label class="form-one__radio">
                                        <input type="radio" name="first_aid_certificate" value="yes" required>
                                        Yes
                                    </label>
                                    <label class="form-one__radio">
                                        <input type="radio" name="first_aid_certificate" value="no" required>
                                        No
                                    </label>
                                </div>
                            </div><!-- /.form-one__control -->
                        </div>

                        <!-- Background Information Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Background Information</h4>

                            <div class="form-one__control">
                                <label class="form-one__label">Which church do you attend? How long have you been there and what ministry are you involved in, in that church? *</label>
                                <textarea name="church_background" rows="4" placeholder="Please provide details about your church involvement..." class="form-one__control__input form-one__control__message" required></textarea>
                            </div><!-- /.form-one__control -->

                            <div class="form-one__control">
                                <label class="form-one__label">Please give brief details of your current employment or other involvements/charity work etc *</label>
                                <textarea name="employment_details" rows="4" placeholder="Please describe your current employment and other activities..." class="form-one__control__input form-one__control__message" required></textarea>
                            </div><!-- /.form-one__control -->

                            <div class="form-one__control">
                                <label class="form-one__label">City Life is accountable to closely guard its team and its spiritual platform; how would you be able to support this? *</label>
                                <textarea name="support_mission" rows="4" placeholder="Please explain how you would support our mission..." class="form-one__control__input form-one__control__message" required></textarea>
                            </div><!-- /.form-one__control -->
                        </div>

                        <!-- Emergency Contact Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Next of kin in case of emergency</h4>

                            <div class="form-grid">
                                <div class="form-one__control">
                                    <label class="form-one__label">Name</label>
                                    <input type="text" name="emergency_contact_name" placeholder="Emergency contact name" class="form-one__control__input">
                                </div><!-- /.form-one__control -->

                                <div class="form-one__control">
                                    <label class="form-one__label">What is your relationship with this person?</label>
                                    <input type="text" name="emergency_contact_relationship" placeholder="Relationship to you" class="form-one__control__input">
                                </div><!-- /.form-one__control -->

                                <div class="form-one__control">
                                    <label class="form-one__label">Phone number</label>
                                    <input type="tel" name="emergency_contact_phone" placeholder="Emergency contact phone" class="form-one__control__input">
                                </div><!-- /.form-one__control -->
                            </div>
                        </div>

                        <!-- Declaration Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Declaration</h4>
                            <p class="form-section__text">City Life is committed to ensuring that all volunteers are safe to work with and around children and young people.</p>

                            <div class="form-one__control">
                                <label class="form-one__label">Are you eligible to work in the UK? *</label>
                                <div class="form-one__radio-group">
                                    <label class="form-one__radio">
                                        <input type="radio" name="eligible_to_work" value="yes" required>
                                        Yes
                                    </label>
                                    <label class="form-one__radio">
                                        <input type="radio" name="eligible_to_work" value="no" required>
                                        No
                                    </label>
                                </div>
                            </div><!-- /.form-one__control -->
                        </div>

                        <!-- Data Protection Section -->
                        <div class="form-section">
                            <h4 class="form-section__title">Data Protection and Processing</h4>
                            <p class="form-section__text">I give express permission for the personal data on this form to be held and processed by City Life. Where I have provided a current DBS Certificate Number, I agree for City Life to use it and my personally identifiable information in order to carry out the required checks. I confirm that the information given on this form is correct and any misleading or falsification of information may be proper cause for rejection.</p>

                            <div class="form-one__control">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="data_processing_consent" value="yes" required>
                                    I accept the above data processing statement *
                                </label>
                            </div><!-- /.form-one__control -->

                            <div class="form-one__control">
                                <label class="form-one__checkbox">
                                    <input type="checkbox" name="data_protection_consent" value="yes" required>
                                    I accept your Data Protection *
                                </label>
                            </div><!-- /.form-one__control -->
                        </div>

                        <div class="become-volunteer__form__bottom form-one__control">
                            <button type="submit" class="citylife-btn citylife-btn--border-base">
                                <span class="citylife-btn__icon-box">
                                    <span class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></span>
                                </span>
                                <span class="citylife-btn__text">Submit</span>
                            </button>
                        </div><!-- /.become-volunteer__form__bottom -->
                    </div><!-- /.become-volunteer__form__inner -->
                </form><!-- /.become-volunteer__form -->
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
</x-app-layout>
