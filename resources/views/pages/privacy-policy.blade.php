<x-app-layout>
    @section('title', 'Privacy Policy - City Life Church')
    @section('description', 'City Life Church Privacy Policy and Data Protection Information')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
        <div class="container">
            <h2 class="page-header__title">Privacy Policy</h2>
            <ul class="citylife-breadcrumb list-unstyled">
                <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
                <li><span>Privacy Policy</span></li>
            </ul>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="privacy-policy py-5">
    <div class="container">
        <div class="row">
            <div class="col-xl-10 offset-xl-1">
                <h6 class="mb-2">Our Privacy Policy</h6>
                <div style="background: #fff;">

                    <p><strong>City Life Church</strong> is committed to protecting your personal information and being transparent about what information we hold about you.</p>

                    <p>We are committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified, you can be assured that it will only be used in accordance with this privacy statement.</p>

                    <p><strong>1. What we collect</strong></p>

                    <p>We may collect the following information:</p>

                    <p>Name and contact information including email address and phone number<br>
                    Demographic information such as postcode and date of birth<br>
                    Other information relevant to customer surveys and/or offers<br>
                    Information about your attendance at church events<br>
                    Gift Aid declarations</p>

                    <p><strong>2. What we do with the information we gather</strong></p>

                    <p>We require this information to understand your needs and provide you with a better service, and in particular for the following reasons:</p>

                    <p>Internal record keeping<br>
                    We may use the information to improve our products and services<br>
                    We may periodically send promotional emails about new courses, events, or other information which we think you may find interesting using the email address which you have provided<br>
                    From time to time, we may also use your information to contact you for feedback purposes<br>
                    We may use the information to customize the website according to your interests</p>

                    <p><strong>3. Security</strong></p>

                    <p>We are committed to ensuring that your information is secure. In order to prevent unauthorized access or disclosure, we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.</p>

                    <p><strong>4. How we use cookies</strong></p>
                    <p>A cookie is a small file which asks permission to be placed on your computer's hard drive. Once you agree, the file is added and the cookie helps analyze web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences.</p>

                    <p>We use traffic log cookies to identify which pages are being used. This helps us analyze data about web page traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system.</p>

                    <p>Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us.</p>

                    <p>You can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website.</p>

                    <p><strong>5. Links to other websites</strong></p>

                    <p>Our website may contain links to other websites of interest. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.</p>

                    <p><strong>6. Controlling your personal information</strong></p>
                    <p>You may choose to restrict the collection or use of your personal information in the following ways:</p>

                    <p>Whenever you are asked to fill in a form on the website, look for the box that you can click to indicate that you do not want the information to be used by anybody for direct marketing purposes<br>
                    If you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by writing to or emailing us at <a href="mailto:info@citylifecc.com">info@citylifecc.com</a></p>

                    <p>We will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so. We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell us that you wish this to happen.</p>

                    <p>You may request details of personal information which we hold about you under the Data Protection Act 1998. A small fee will be payable. If you would like a copy of the information held on you please write to CityLife Church, 1 South Parade, Sheffield S3 8SS.</p>

                    <p>If you believe that any information we are holding on you is incorrect or incomplete, please write to or email us as soon as possible, at the above address. We will promptly correct any information found to be incorrect.</p>

                    <p><strong>Contact</strong></p>

                    <p>For enquiries send an email to: <a href="mailto:info@citylifechurch.org">info@citylifechurch.org</a></p>

                    <p style="margin-top: 30px; font-size: 13px; color: #666;">Last Updated: <span id="date"></span></p>

                </div>
                <div class="contact-section p-4 bg-light rounded">
                            <h4 class="mb-3">Exercise Your Rights</h4>
                            <p>To request access, correction, or deletion of your data, or if you have any privacy concerns:</p>
                            <div class="contact-options">
                                <p><i class="icon-mail"></i> <strong>Email:</strong> <a href="mailto:privacy@citylifecc.com">privacy@citylifecc.com</a></p>
                                <p><i class="icon-phone"></i> <strong>Phone:</strong> <a href="tel:01142728243">0114 272 8243</a></p>
                                <p><i class="icon-location"></i> <strong>Address:</strong> City Life Church, Kelham Island, Sheffield</p>
                            </div>
                            <small class="text-muted">
                                We will respond to your request within 30 days as required by GDPR.
                            </small>
                        </div>
            </div>
        </div>
    </div>
</section>

<script>
  const now = new Date();

  const month = now.toLocaleString('default', { month: 'long' });
  const year = now.getFullYear();

  document.getElementById('date').textContent = month + ' ' + year;
</script>
</x-app-layout>
