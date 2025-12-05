<x-app-layout>
@section('title', 'Missions - CityLife Church')
@section('description', 'Discover our mission work both at home and abroad, serving communities locally and internationally through various outreach programs.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="page-header__title">Our Missions</h2>
        <p class="page-header__text text-white">Serving God by serving others, both near and far</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Missions</span></li>
        </ul>
    </div>
</section>

<!-- Mission Vision Section -->
<section class="help-donate-one section-space-top">
    <div class="help-donate-one__bg citylife-jarallax" data-jarallax data-speed="0.3" data-imgPosition="50% -100%" style="background-image: url({{ asset('assets/images/backgrounds/Girls-Home.jpg') }});"></div>
    <div class="container"></div>
</section>

<!-- Mission Types -->
<section class="donations-one donations-carousel section-space-bottom">
   <div class="container">
    <div class="donations-one__row row gutter-y-30">
         <div class="col-xl-6 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="00ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInUp;">
            <div class="donation-information">
               <div class="donation-information__bg" style="background-image: url(assets/images/resources/donation-information-bg-11.jpg)"></div>
               <!-- /.donation-information__bg -->
               <div class="donation-information__content">
                  <h3 class="donation-information__title">Mission At Home</h3>
                  <!-- /.donation-information__title -->
                  <p class="donation-information__text">Supporting our local community through food packages, school uniform drives, and the City Life Kids & Families Foundation. We believe in caring for those closest to us first.</p>
                  <!-- /.donation-information__text -->
                  <div class="donate-card__features">
                        <ul class="donation-information__list">
                            <li><i class="fa fa-check text-success"></i><span> Food & Toiletries Packages</span></li>
                            <li><i class="fa fa-check text-success"></i><span> Pre-Loved School Uniform Events</span></li>
                            <li><i class="fa fa-check text-success"></i><span> Kids & Families Foundation</span></li>
                            <li><i class="fa fa-check text-success"></i><span> Community Support Programs</span></li>
                        </ul>
                    </div>
                  <!-- /.donation-information__list -->
                  <a href="{{ route('missions.home') }}" class="citylife-btn citylife-btn--border">
                     <div class="citylife-btn__icon-box">
                        <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                     </div>
                     <span class="citylife-btn__text">Learn more</span>
                  </a>
               </div>
               <!-- /.donation-information__content -->
            </div>
            <!-- /.donation-information -->
         </div>
         <!-- /.col-xl-6 -->
         <div class="col-xl-6 wow fadeInUp animated" data-wow-duration="1500ms" data-wow-delay="200ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 200ms; animation-name: fadeInUp;">
            <div class="gift-card">
               <div class="gift-card__bg" style="background-image: url(assets/images/resources/gift-bg-1-1.jpg)"></div>
               <!-- /.gift-card__bg -->
               <div class="gift-card__content">
                  <h3 class="gift-card__title">Mission Abroad</h3>
                  <!-- /.gift-card__title -->
                  <p class="gift-card__text">Partnering with projects in India and the Democratic Republic of Congo to transform communities, educate children, and provide hope for the future.</p>
                  <!-- /.gift-card__text -->
                  <div class="donate-card__features">
                        <ul class="donation-information__list">
                            <li><i class="fa fa-check text-success"></i><span>The John Project (India)</span></li>
                            <br>
                            <li><i class="fa fa-check text-success"></i><span>Shalom Project (New Delhi)</span></li>
                            <li><i class="fa fa-check text-success"></i><span>DRC Community Development</span></li>
                            <li><i class="fa fa-check text-success"></i><span>Education & Healthcare</span></li>
                        </ul>
                    </div>
                  <!-- /.gift-card__amount -->
                  <a href="{{ route('missions.abroad') }}" class="citylife-btn citylife-btn--border">
                     <div class="citylife-btn__icon-box">
                        <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                     </div>
                     <span class="citylife-btn__text">Learn more</span>
                  </a>
               </div>
               <!-- /.gift-card__content -->
            </div>
            <!-- /.gift-card -->
         </div>
         <!-- /.col-xl-6 -->
    </div>
    <div class="row justify-content-center " style="margin-top: 100px;">
            <div class="col-lg-8">
                <div class="section-title text-center">
                    <h6 class="section-title__tagline">Featured</h6>
                    <h3 class="section-title__title">Current Mission Projects</h3>
                </div>
            </div>
        </div>
        @if ($missions->count() > 0)
        <div class="donations-one__carousel citylife-owl__carousel citylife-owl__carousel--basic-nav owl-theme owl-carousel" data-owl-options='{
           "items": 3,
           "margin": 30,
           "smartSpeed": 700,
           "loop": true,
           "autoplay": true,
           "autoplayTimeout": 6000,
           "autoplayHoverPause": true,
           "nav": true,
           "dots": false,
           "center": false,
           "stagePadding": 0,
           "navText": ["<span class=\"icon-arrow-left\"></span>","<span class=\"icon-arrow-right\"></span>"],
           "responsive":{
               "0":{
                   "items": 1,
                   "margin": 20
               },
               "576":{
                   "items": 1,
                   "margin": 30
               },
               "768":{
                   "items": 2,
                   "margin": 30
               },
               "992":{
                   "items": 2,
                   "margin": 30
               },
               "1200":{
                   "items": 3,
                   "margin": 30
               }
           }
        }'>
           @foreach ($missions as $mission)
           <div class="item">
              <div class="donation-card">
                 <a href="{{ route('missions.show', $mission->slug) }}" class="donation-card__image">
                    @if($mission->featured_image)
                    <img src="{{ Storage::url('' . $mission->featured_image) }}" alt="{{ $mission->title }}">
                    @else
                    <div class="donation-card__placeholder" style="height: 250px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #666;">
                        <span>Mission Image</span>
                    </div>
                    @endif
                    <div class="donation-card__category">{{ ucfirst($mission->mission_type) }}</div>
                 </a>
                 <div class="donation-card__content">
                    <h3 class="donation-card__title">
                       <a href="{{ route('missions.show', $mission->slug) }}">{{ $mission->title }}</a>
                    </h3>
                    {{-- <p class="donation-card__text">{{ Str::limit($mission->description, 100) }}</p> --}}
                    <a href="{{ route('missions.show', $mission->slug) }}" class="donation-card__btn citylife-btn citylife-btn--border-base">
                       <div class="citylife-btn__icon-box">
                          <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                       </div>
                       <span class="citylife-btn__text">Learn More</span>
                    </a>
                 </div>
              </div>
           </div>
           @endforeach
        </div>
        @endif
      <!-- /.donations-one__carousel -->

      <!-- /.row -->
   </div>
   <!-- /.container -->
</section>

<!-- Get Involved -->
<section class="contact-one__bottom-cta section-space-two" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); padding: 60px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-one__bottom-cta__content text-center">
                    <h3 class="contact-one__bottom-cta__title text-white">Join Us in Making a Difference</h3>
                    <p class="contact-one__bottom-cta__text">
                        Whether through prayer, giving, or volunteering, there are many ways you can be part of our mission to serve others in Jesus' name.
                    </p>
                    <div class="contact-one__bottom-cta__btn">
                        <a href="{{ route('giving.index') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-donate"></span></div>
                            </div>
                            <span class="citylife-btn__text">Support Missions</span>
                        </a>
                        <a href="{{ route('contact') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Get Involved</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.mission-stats {
    display: flex;
    justify-content: space-between;
    margin: 20px 0;
}

.mission-stat {
    text-align: center;
    flex: 1;
}

.mission-stat__number {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--citylife-base);
    line-height: 1.2;
}

.mission-stat__label {
    display: block;
    font-size: 0.8rem;
    color: #666;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .mission-stats {
        flex-direction: column;
        gap: 15px;
    }

    .mission-stat__number {
        font-size: 1.3rem;
    }
}
</style>

</x-app-layout>
