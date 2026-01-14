<x-app-layout>
@section('title', 'Contact Us - CityLife Church')
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h2 class="page-header__title">Contact Us</h2>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Contact Us</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>
<section class="contact-one section-space @@extraClassName">
    <div class="container">
        <div class="row gutter-y-30">
            <div class="col-lg-6 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
                <div class="contact-one__map">
                    <div class="google-map contact-one__google__map">
                        <iframe title="template google map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2379.4758042928543!2d-1.476495623265822!3d53.38842807230161!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4879787db7d3f30f%3A0x7289b9a4fe83cb8d!2sCityLife%20International%20Church.!5e0!3m2!1sen!2suk!4v1754923489732!5m2!1sen!2suk"  class="map__contact-one__google__map" allowfullscreen=""></iframe>
                    </div>
                    <!-- /.google-map -->
                    <div class="contact-one__info">
                        <div class="contact-one__info__item">
                            <div class="contact-one__info__icon">
                                <span class="icon-location"></span>
                            </div><!-- /.contact-one__info__icon -->
                            <div class="contact-one__info__content">
                                <h4 class="contact-one__info__title">Church Address</h4>
                                <address class="contact-one__info__text">
                                    @if($contactInfo)
                                        {{ $contactInfo->address }}<br>
                                        {{ $contactInfo->city }}, {{ $contactInfo->postal_code }}, {{ $contactInfo->country }}
                                    @else
                                        1 South Parade Shalesmoor
                                        <br>Sheffield, S3 8SS, United Kingdom
                                    @endif
                                </address>
                            </div><!-- /.contact-one__info__content -->
                        </div><!-- /.contact-one__info__item -->
                        <div class="contact-one__info__item">
                            <div class="contact-one__info__icon">
                                <span class="icon-phone"></span>
                            </div><!-- /.contact-one__info__icon -->
                            <div class="contact-one__info__content">
                                <h4 class="contact-one__info__title">Church Office</h4>
                                <a href="tel:{{ $contactInfo?->phone ?? '0114 272 8243' }}" class="contact-one__info__text contact-one__info__text--link">{{ $contactInfo?->phone ?? '0114 272 8243' }}</a>
                            </div><!-- /.contact-one__info__content -->
                        </div><!-- /.contact-one__info__item -->
                        <div class="contact-one__info__item">
                            <div class="contact-one__info__icon">
                                <span class="icon-envelope"></span>
                            </div><!-- /.contact-one__info__icon -->
                            <div class="contact-one__info__content">
                                <h4 class="contact-one__info__title">Email Address</h4>
                                <a href="mailto:{{ $contactInfo?->email ?? 'admin1@citylifecc.com' }}" class="contact-one__info__text contact-one__info__text--link">{{ $contactInfo?->email ?? 'admin1@citylifecc.com' }}</a>
                                <br>
                                <a href="mailto:admin2@citylifecc.com" class="contact-one__info__text contact-one__info__text--link">admin2@citylifecc.com</a>
                            </div><!-- /.contact-one__info__content -->
                        </div><!-- /.contact-one__info__item -->
                        <div class="contact-one__info__item">
                            <div class="contact-one__info__icon">
                                <span class="icon-clock"></span>
                            </div><!-- /.contact-one__info__icon -->
                            <div class="contact-one__info__content">
                                <h4 class="contact-one__info__title">Office Hours</h4>
                                <p class="contact-one__info__text">
                                    @if($contactInfo && $contactInfo->office_hours)
                                        {{ $contactInfo->office_hours }}
                                    @else
                                        10.00 am - 3 pm Mon to Wed
                                    @endif
                                </p>
                            </div><!-- /.contact-one__info__content -->
                        </div><!-- /.contact-one__info__item -->
                    </div><!-- /.contact-one__info -->
                </div><!-- /.contact-one__map -->
            </div><!-- /.col-lg-6 -->
                        <div class="col-lg-6 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="200ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 200ms; animation-name: fadeInUp;">
                <div class="contact-one__form">
                    <div class="contact-one__form__bg" style="background-image: url('{{ asset('assets/images/backgrounds/become-volunteer-bg-1-1.png') }}');"></div><!-- /.contact-one__form__bg -->
                    <h2 class="contact-one__title">Write to Us</h2>

                    @if(session('success'))
                    <div class="alert alert-success" style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <strong>Success!</strong> {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <strong>Please correct the following errors:</strong>
                        <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif                    <form class="contact-one__form__inner form-one wow fadeInUp animated" data-wow-duration="1500ms" action="{{ route('contact.submit') }}" method="POST" style="visibility: visible; animation-duration: 1500ms; animation-name: fadeInUp;">
                        @csrf

                        {{-- Anti-spam: Honeypot fields (hidden from real users) --}}
                        <input type="text" name="website" style="display:none !important" tabindex="-1" autocomplete="off">
                        <input type="text" name="url" style="display:none !important" tabindex="-1" autocomplete="off">

                        {{-- Anti-spam: Timestamp field for time-based validation --}}
                        <input type="hidden" name="form_time" value="{{ time() }}">

                        <div class="row gutter-y-20">
                            <div class="col-12">
                                <div class="form-one__control">
                                    <input type="text" name="name" id="name" placeholder="Enter your full name" class="form-one__control__input" value="{{ old('name') }}" required>
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="col-12">
                                <div class="form-one__control">
                                    <input type="email" name="email" id="email" placeholder="Your email address" class="form-one__control__input" value="{{ old('email') }}" required>
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="col-12">
                                <div class="form-one__control">
                                    <input type="tel" name="phone" id="phone" placeholder="Phone number (optional)" class="form-one__control__input" value="{{ old('phone') }}">
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="col-12">
                                <div class="form-one__control">
                                    <select name="subject" class="form-one__control__input dropdown bootstrap-select" required>
                                        <option value="">Select a subject</option>
                                        <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                        <option value="Prayer Request" {{ old('subject') == 'Prayer Request' ? 'selected' : '' }}>Prayer Request</option>
                                        <option value="Baby Dedication" {{ old('subject') == 'Baby Dedication' ? 'selected' : '' }}>Baby Dedication</option>
                                        <option value="Event Information" {{ old('subject') == 'Event Information' ? 'selected' : '' }}>Event Information</option>
                                        <option value="Pastoral Care" {{ old('subject') == 'Pastoral Care' ? 'selected' : '' }}>Pastoral Care</option>
                                        <option value="Membership" {{ old('subject') == 'Membership' ? 'selected' : '' }}>Membership</option>
                                        <option value="Donations" {{ old('subject') == 'Donations' ? 'selected' : '' }}>Donations</option>
                                        <option value="Technical Support" {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                                        <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="col-12">
                                <div class="form-one__control">
                                    <textarea name="message" id="message" cols="30" rows="10" placeholder="Share your message with us..." class="form-one__control__input form-one__control__message" required>{{ old('message') }}</textarea>
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="col-12">
                                <div class="form-one__control">
                                    <div class="gdpr-consent" style="margin-bottom: 20px;">
                                        <label style="display: flex; align-items: flex-start; font-size: 14px; line-height: 1.4; color: #666;">
                                            <input type="checkbox" name="gdpr_consent" value="1" style="margin-right: 10px; margin-top: 2px;" required {{ old('gdpr_consent') ? 'checked' : '' }}>
                                            <span>I consent to CityLife Church collecting and processing my personal data in accordance with the
                                            <a href="#" style="color: #007bff; text-decoration: underline;" onclick="showGdprModal(); return false;">General Data Protection Regulation (GDPR)</a>.
                                            Your data will only be used to respond to your inquiry and will not be shared with third parties without your explicit consent.</span>
                                        </label>
                                    </div>
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                            <div class="col-12">
                                <div class="contact-one__btn-box form-one__control">
                                    <button type="submit" class="citylife-btn">
                                        <span class="citylife-btn__icon-box">
                                            <span class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></span>
                                        </span>
                                        <span class="citylife-btn__text">Send Message</span>
                                    </button>
                                </div><!-- /.form-one__control -->
                            </div><!-- /.col-12 -->
                        </div><!-- /.row -->
                    </form><!-- /.contact-one__form__inner -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>

<!-- GDPR Modal -->
<div id="gdprModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 5% auto; padding: 30px; border-radius: 10px; width: 80%; max-width: 800px; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
            <h3 style="margin: 0; color: #333;">Data Protection & Privacy Policy (GDPR)</h3>
            <span onclick="closeGdprModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>

        <div style="line-height: 1.6; color: #555;">
            <h4>Data Controller</h4>
            <p>CityLife Church, 123 Faith Street, CityLife Community, Springfield, CA 90210, USA</p>

            <h4>Purpose of Data Processing</h4>
            <p>We collect and process your personal data to:</p>
            <ul>
                <li>Respond to your inquiries and prayer requests</li>
                <li>Provide pastoral care and support</li>
                <li>Send information about church events and services (only with consent)</li>
                <li>Maintain church records for active members</li>
            </ul>

            <h4>Legal Basis</h4>
            <p>We process your data based on:</p>
            <ul>
                <li>Your explicit consent (Article 6(1)(a) GDPR)</li>
                <li>Legitimate interests for pastoral care (Article 6(1)(f) GDPR)</li>
                <li>Performance of religious duties (Article 9(2)(d) GDPR)</li>
            </ul>

            <h4>Data Retention</h4>
            <p>Contact form submissions are retained for 2 years unless you request earlier deletion. Member data is retained while you remain an active member and for 7 years after membership ends for legal and pastoral purposes.</p>

            <h4>Your Rights</h4>
            <p>Under GDPR, you have the right to:</p>
            <ul>
                <li>Access your personal data</li>
                <li>Rectify inaccurate data</li>
                <li>Erase your data (right to be forgotten)</li>
                <li>Restrict processing</li>
                <li>Data portability</li>
                <li>Object to processing</li>
                <li>Withdraw consent at any time</li>
            </ul>

            <h4>Contact Our Data Protection Officer</h4>
            <p>Email: privacy@citylifechurch.org<br>
            Phone: (555) 123-4567<br>
            Address: CityLife Church, 123 Faith Street, Springfield, CA 90210</p>

            <h4>Complaints</h4>
            <p>You have the right to lodge a complaint with your local data protection authority if you believe we have not handled your data in accordance with GDPR.</p>
        </div>

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <button onclick="closeGdprModal()" style="background-color: #007bff; color: white; padding: 10px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Close</button>
        </div>
    </div>
</div>

<script>
function showGdprModal() {
    document.getElementById('gdprModal').style.display = 'block';
}

function closeGdprModal() {
    document.getElementById('gdprModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    var modal = document.getElementById('gdprModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

</x-app-layout>
