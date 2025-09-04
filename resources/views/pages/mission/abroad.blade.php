<x-app-layout>
@section('title', 'Missions Abroad - CityLife Church')
@section('description', 'Explore our international mission work in India and Democratic Republic of Congo, supporting children, education, and community development worldwide.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="page-header__title">Missions Abroad</h2>
        <p class="page-header__text">Reaching the world with God's love and hope</p>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('missions.index') }}">Missions</a></li>
            <li><span>Abroad</span></li>
        </ul>
    </div>
</section>

<!-- Biblical Foundation Section -->
<section class="about-one section-space">
    <div class="container">
        <div class="row gutter-y-50 align-items-center">
            <div class="col-lg-6">
                <div class="about-one__content">
                    <div class="section-title text-start">
                        <h6 class="section-title__tagline">Mark 16:15</h6>
                        <h3 class="section-title__title">"Go into all the world and preach the gospel..."</h3>
                        <p class="about-one__content__text">
                            "And He said to them, 'Go into all the world and preach the gospel to every creature.'"
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/backgrounds/world-missions.jpg') }}" alt="World Missions" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Statement -->
<section class="about-two section-space-two">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title text-center">
                    <h3 class="section-title__title">City Life is affiliated with projects in India and the Democratic Republic of the Congo.</h3>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- India Missions -->
<section class="about-one section-space" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="section-title text-center">
                    <h6 class="section-title__tagline">India</h6>
                    <h3 class="section-title__title">Transforming Lives in India</h3>
                </div>
            </div>
        </div>

        <!-- The John Project -->
        <div class="row gutter-y-50 mb-5">
            <div class="col-lg-6">
                <div class="about-one__content">
                    <h4 class="about-one__content__title">The John Project</h4>
                    <p class="about-one__content__text">
                        The John Foundation was established in 2007 with the goal of transforming lives and bringing hope.
                        What began with two kids in June 2007 has since grown tremendously.
                    </p>
                    <div class="about-one__content__list">
                        <p><strong>Today we run:</strong></p>
                        <ul>
                            <li>24 children's homes caring for 262 kids</li>
                            <li>23 tuition centres helping 690 children</li>
                            <li>Supporting 650 children of widows and single mothers</li>
                            <li>John's Academy School educating close to 300 kids</li>
                            <li>Employable Skills Training Program (8000+ trained)</li>
                            <li>Asha Restoration Homes for young girls (200+ helped)</li>
                            <li>Two homes for HIV/AIDS children and young girls</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/missions/john-project.jpg') }}" alt="The John Project" class="img-fluid">
                </div>
            </div>
        </div>

        <!-- Shalom Project -->
        <div class="row gutter-y-50">
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/missions/shalom-project.jpg') }}" alt="Shalom Project" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__content">
                    <h4 class="about-one__content__title">Shalom Project</h4>
                    <p class="about-one__content__text">
                        Mayapuri Slum is one of the largest slums in India and is situated in the capital city of New Delhi.
                        The Shalom Project aims to feed children living in the slum and provide them with an education that will help them towards a better future.
                    </p>
                    <p class="about-one__content__text">
                        <strong>The Shalom Project is led by Pastor Das.</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Democratic Republic of Congo -->
<section class="about-one section-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="section-title text-center">
                    <h6 class="section-title__tagline">Democratic Republic of the Congo</h6>
                    <h3 class="section-title__title">Building Self-Sufficient Communities</h3>
                </div>
            </div>
        </div>

        <div class="row gutter-y-50">
            <div class="col-lg-6">
                <div class="about-one__content">
                    <p class="about-one__content__text">
                        Our representatives for this project are City Life members Jacques and Liliane Kalenga, who are originally from the DRC.
                        We are affiliated with "La Vie Abondante" Church in the Kasai Oriental province in Mbuji Mayi town.
                    </p>
                    <p class="about-one__content__text">
                        <strong>La Vie Abondante is run by Senior Pastor Kabundji Tshitenda Hilaire and his wife Georgette.</strong>
                    </p>
                    <p class="about-one__content__text">
                        The objective of this project is to make the local community self-sufficient. We aim to achieve this by helping them to acquire their own land for farming and building a church, a health centre and a school.
                    </p>
                    <div class="about-one__content__update">
                        <h5>DRC Mission Latest Update</h5>
                        <p>The City Life DRC mission project recently purchased a generator for La Vie Abondante so they can now stop hiring electricity from other sources.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-one__image">
                    <img src="{{ asset('assets/images/missions/drc-project.jpg') }}" alt="DRC Mission" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- India Mission 2024 -->
<section class="testimonials-one section-space" style="background-image: url('{{ asset('assets/images/backgrounds/testimonial-bg-1-1.jpg') }}');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-title text-center">
                    <h6 class="section-title__tagline">Recent Mission Trip</h6>
                    <h3 class="section-title__title">India Mission 2024</h3>
                    <p class="section-title__text">
                        Our recent mission to India was an unforgettable experience, filled with opportunities to minister and equip individuals in crucial roles.
                    </p>
                </div>

                <div class="testimonials-one__content">
                    <p class="testimonials-one__content__text">
                        Throughout our journey, we engaged in a variety of enriching activities. From leadership training sessions and vibrant music workshops
                        to inspiring preaching engagements, we felt a deep connection with the communities we served.
                        It was particularly touching to reconnect with friends and colleagues we met nearly 18 years ago!
                    </p>

                    <p class="testimonials-one__content__text">
                        One of the highlights was visiting the third campus of the John Foundation, complete with residential facilities, schools,
                        an auditorium, and even a supermarket! The vision and dedication that has gone into developing this space were nothing short of jaw-dropping.
                    </p>

                    <p class="testimonials-one__content__text">
                        Your generous gifts and prayers have had a profound impact, enabling us to uplift thousands of children and help them escape life on the streets.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Support Section -->
<section class="donate-page section-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title text-center">
                    <h3 class="section-title__title">Support Our Mission Work</h3>
                    <p class="section-title__text">
                        We have dedicated supporters who contribute £10.00 each month to this project.
                        If you would like to support this project specifically, please contact us.
                    </p>
                </div>
            </div>
        </div>

        <div class="row gutter-y-30 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="donate-card text-center">
                    <div class="donate-card__icon">
                        <span class="icon-donate"></span>
                    </div>
                    <h3 class="donate-card__title">Monthly Support</h3>
                    <p class="donate-card__text">Join our dedicated supporters with regular monthly contributions</p>
                    <div class="donate-card__actions">
                        <a href="{{ route('giving.index') }}" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-donate"></span></div>
                            </div>
                            <span class="citylife-btn__text">Give Monthly</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="donate-card text-center">
                    <div class="donate-card__icon">
                        <span class="icon-user"></span>
                    </div>
                    <h3 class="donate-card__title">Join WhatsApp Group</h3>
                    <p class="donate-card__text">Get updates and connect with other supporters</p>
                    <div class="donate-card__actions">
                        <a href="mailto:admin1@citylifecc.com?subject=Mission WhatsApp Group" class="citylife-btn">
                            <div class="citylife-btn__icon-box">
                                <div class="citylife-btn__icon-box__inner"><span class="icon-duble-arrow"></span></div>
                            </div>
                            <span class="citylife-btn__text">Contact Admin</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gratitude Section -->
<section class="contact-one__bottom-cta section-space-two" style="background-image: url('{{ asset('assets/images/backgrounds/help-donate-bg-1-1.jpg') }}');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-one__bottom-cta__content text-center">
                    <h3 class="contact-one__bottom-cta__title">Thank You For Your Support!</h3>
                    <p class="contact-one__bottom-cta__text">
                        Once again, thank you for being an essential part of this journey. Your support makes all the difference!
                        <br><em>Merci pour votre soutien!</em>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

</x-app-layout>
