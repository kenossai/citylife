<x-app-layout>
@section('title', 'My Profile - CityLife Church')

<style>
    .profile-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        padding: 60px 0;
    }

    .profile-container {
        background: white;
        border-radius: 25px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 1400px;
        margin: 0 auto;
    }

    .profile-sidebar {
        background: #fff;
        padding: 50px 35px;
        border-right: 1px solid #dee2e6;
        min-height: 700px;
    }

    .profile-avatar-box {
        text-align: center;
        margin-bottom: 35px;
    }

    .profile-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e9ecef;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-size: 36px;
        color: #0d0324;
    }

    .profile-user-name {
        font-size: 22px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 5px;
    }

    .profile-user-email {
        font-size: 14px;
        color: #718096;
    }

    .profile-section-label {
        font-size: 13px;
        font-weight: 700;
        color: #1a202c;
        margin: 35px 0 18px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .profile-nav-list {
        list-style: none;
        padding: 0;
        margin: 0 0 25px 0;
    }

    .profile-nav-list li {
        margin-bottom: 8px;
    }

    .profile-nav-link {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        color: #4a5568;
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.25s ease;
        font-size: 15px;
        font-weight: 500;
    }

    .profile-nav-link i {
        margin-right: 14px;
        font-size: 17px;
        width: 22px;
        text-align: center;
    }

    .profile-nav-link:hover {
        background: rgba(72, 1, 119, 0.1);
        color: #620180;
    }

    .profile-nav-link.active {
        background: #1a202c;
        color: white;
    }

    .profile-yellow-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 16px 28px;
        background: #fbbf24;
        color: #1a202c;
        border: none;
        border-radius: 60px;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.3s ease;
        margin-bottom: 12px;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(251, 191, 36, 0.3);
    }

    .profile-yellow-btn i {
        margin-right: 10px;
        font-size: 17px;
    }

    .profile-yellow-btn:hover {
        background: #f59e0b;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
        color: #1a202c;
    }

    .profile-yellow-btn.outline {
        background: white;
        border: 2px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .profile-yellow-btn.outline:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-3px);
    }

    .profile-main-content {
        /* background-color:  #e4e4e4eb; */
        padding: 50px 55px;
        min-height: 700px;
        border-radius: 20px;
    }

    .profile-input {
        width: 100%;
        padding: 18px 28px;
        border: none;
        border-radius: 60px;
        background: #dddddd;
        color: #1a202c;
        font-size: 15px;
        margin-bottom: 22px;
        transition: all 0.25s ease;
    }

    .profile-input::placeholder {
        color: #c5c7c9;
    }

    .profile-input:focus {
        outline: none;
        background: white;
        box-shadow: 0 0 0 4px rgba(251, 190, 36, 0.973);
    }

    textarea.profile-input {
        border-radius: 30px;
        resize: vertical;
        min-height: 110px;
    }

    .profile-section-heading {
        color: rgb(41, 41, 41);
        font-size: 20px;
        font-weight: 700;
        margin: 35px 0 22px 0;
    }

    .content-section {
        display: none;
    }

    .content-section.active {
        display: block;
    }

    .profile-save-btn {
        background: #fbbf24;
        color: #1a202c;
        border: none;
        padding: 18px 50px;
        border-radius: 60px;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s ease;
        margin-top: 25px;
        box-shadow: 0 5px 20px rgba(251, 191, 36, 0.4);
    }

    .profile-save-btn:hover {
        background: #f59e0b;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(251, 191, 36, 0.5);
    }

    .pref-checkbox-box {
        background: rgba(255,255,255,0.08);
        padding: 18px 24px;
        border-radius: 18px;
        margin-bottom: 18px;
        border: 1px solid rgba(75, 2, 97, 0.279);
    }

    .pref-checkbox-box label {
        color: rgb(53, 0, 59);
        margin-bottom: 0;
        cursor: pointer;
    }

    .pref-checkbox-box strong {
        font-weight: 600;
    }

    .pref-checkbox-box small {
        color: rgba(56, 1, 74, 0.7);
    }

    .form-check-input:checked {
        background-color: #400061;
        border-color: #4e047965;
    }

    .invalid-feedback {
        color: #fbbf24;
        font-size: 13px;
        margin-top: -18px;
        margin-bottom: 15px;
        margin-left: 28px;
    }

    .alert {
        border-radius: 18px;
        margin-bottom: 25px;
    }
</style>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong><i class="fas fa-check-circle"></i> Success!</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="profile-wrapper">
    <div class="container">
        <div class="row profile-container g-0">
            <!-- Left Sidebar -->
            <div class="col-lg-4">
                <div class="profile-sidebar">
                    <div class="profile-avatar-box">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="profile-user-name">{{ $member->first_name }} {{ $member->last_name }}</h3>
                        <p class="profile-user-email">{{ $member->email }}</p>
                    </div>

                    <div>
                        <h4 class="profile-section-label">Manage Account</h4>
                        <ul class="profile-nav-list">
                            <li>
                                <a href="#" class="profile-nav-link active" data-section="profile">
                                    <i class="fas fa-user"></i> Profile Information
                                </a>
                            </li>
                            <li>
                                <a href="#" class="profile-nav-link" data-section="password">
                                    <i class="fas fa-lock"></i> Change Password
                                </a>
                            </li>
                            <li>
                                <a href="#" class="profile-nav-link" data-section="preferences">
                                    <i class="fas fa-cog"></i> Preferences
                                </a>
                            </li>
                        </ul>

                        <h4 class="profile-section-label">My Learning</h4>
                        <a href="{{ route('courses.dashboard') }}" class="profile-yellow-btn">
                            <i class="fas fa-book"></i> View My Courses
                        </a>

                        <form method="POST" action="{{ route('member.logout') }}">
                            @csrf
                            <button type="submit" class="profile-yellow-btn outline">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="col-lg-8">
                <div class="profile-main-content">
                    <!-- Profile Information Section -->
                    <div class="content-section active" id="profile-section">
                        <form method="POST" action="{{ route('member.profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="first_name" placeholder="Jerry"
                                        class="profile-input @error('first_name') is-invalid @enderror"
                                        value="{{ old('first_name', $member->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="last_name" placeholder="Anyim"
                                        class="profile-input @error('last_name') is-invalid @enderror"
                                        value="{{ old('last_name', $member->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="email" name="email" placeholder="jerry_anyim@ymail.com"
                                        class="profile-input @error('email') is-invalid @enderror"
                                        value="{{ old('email', $member->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input type="tel" name="phone" placeholder="Phone"
                                        class="profile-input @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $member->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <input type="date" name="date_of_birth" placeholder="dd/mm/yyyy"
                                class="profile-input @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth', $member->date_of_birth?->format('Y-m-d')) }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <textarea name="address" placeholder="Address"
                                class="profile-input @error('address') is-invalid @enderror"
                                rows="3">{{ old('address', $member->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="city" placeholder="City"
                                        class="profile-input @error('city') is-invalid @enderror"
                                        value="{{ old('city', $member->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="postcode" placeholder="Postcode"
                                        class="profile-input @error('postcode') is-invalid @enderror"
                                        value="{{ old('postcode', $member->postcode) }}">
                                    @error('postcode')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="country" placeholder="United Kingdom"
                                        class="profile-input @error('country') is-invalid @enderror"
                                        value="{{ old('country', $member->country) }}">
                                    @error('country')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <h5 class="profile-section-heading">Emergency Contact</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="emergency_contact_name" placeholder="Emergency Contact Name"
                                        class="profile-input @error('emergency_contact_name') is-invalid @enderror"
                                        value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}">
                                    @error('emergency_contact_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input type="tel" name="emergency_contact_phone" placeholder="Emergency Contact Phone"
                                        class="profile-input @error('emergency_contact_phone') is-invalid @enderror"
                                        value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}">
                                    @error('emergency_contact_phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="emergency_contact_relationship" placeholder="Relationship"
                                        class="profile-input @error('emergency_contact_relationship') is-invalid @enderror"
                                        value="{{ old('emergency_contact_relationship', $member->emergency_contact_relationship) }}">
                                    @error('emergency_contact_relationship')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="profile-save-btn">
                                Update Profile
                            </button>
                        </form>
                    </div>

                    <!-- Change Password Section -->
                    <div class="content-section" id="password-section">
                        <form method="POST" action="{{ route('member.profile.password') }}">
                            @csrf
                            @method('PUT')

                            <input type="password" name="current_password" placeholder="Current Password *"
                                class="profile-input @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="password" name="password" placeholder="New Password *"
                                        class="profile-input @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input type="password" name="password_confirmation" placeholder="Confirm New Password *"
                                        class="profile-input" required>
                                </div>
                            </div>

                            <button type="submit" class="profile-save-btn">
                                Update Password
                            </button>
                        </form>
                    </div>

                    <!-- Preferences Section -->
                    <div class="content-section" id="preferences-section">
                        <form method="POST" action="{{ route('member.profile.preferences') }}">
                            @csrf
                            @method('PUT')

                            <p style="color: rgba(255,255,255,0.8); margin-bottom: 25px;">
                                Choose how you'd like to receive updates from us:
                            </p>

                            <div class="pref-checkbox-box">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="receive_newsletter"
                                        name="receive_newsletter" value="1" {{ $member->receive_newsletter ? 'checked' : '' }}>
                                    <label class="form-check-label" for="receive_newsletter">
                                        <strong>Newsletter</strong><br>
                                        <small>Receive our monthly newsletter with church updates and events</small>
                                    </label>
                                </div>
                            </div>

                            <div class="pref-checkbox-box">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="receive_event_updates"
                                        name="receive_event_updates" value="1" {{ $member->receive_event_updates ? 'checked' : '' }}>
                                    <label class="form-check-label" for="receive_event_updates">
                                        <strong>Event Updates</strong><br>
                                        <small>Get notified about upcoming church events and activities</small>
                                    </label>
                                </div>
                            </div>

                            <div class="pref-checkbox-box">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="receive_sms"
                                        name="receive_sms" value="1" {{ $member->receive_sms ? 'checked' : '' }}>
                                    <label class="form-check-label" for="receive_sms">
                                        <strong>SMS Notifications</strong><br>
                                        <small>Receive important updates via SMS</small>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="profile-save-btn">
                                Save Preferences
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab Navigation
    document.querySelectorAll('.profile-nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active class from all links
            document.querySelectorAll('.profile-nav-link').forEach(l => l.classList.remove('active'));

            // Add active class to clicked link
            this.classList.add('active');

            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });

            // Show selected section
            const sectionId = this.getAttribute('data-section') + '-section';
            document.getElementById(sectionId).classList.add('active');
        });
    });
</script>

</x-app-layout>
