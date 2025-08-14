<header class="main-header sticky-header sticky-header--normal">
    <div class="container-fluid">
        <div class="main-header__inner">
            <div class="main-header__logo">
                <a href="index.html">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="CityLife HTML" width="100">
                </a>
                <button type="button" class="main-header__sidebar-btn sidebar-btn__toggler">
                    <span class="icon-grid"></span>
                </button><!-- /.main-header__sidebar-btn -->
            </div><!-- /.main-header__logo -->
            <div class="main-header__right">
                <nav class="main-header__nav main-menu">
                    <ul class="main-menu__list">


                        <li class="">
                            <a href="/">Home</a>
                        </li>


                        <li>
                            <a href="about-citylife">About Us</a>
                        </li>

                        <li class="dropdown">
                            <a href="index.html#">Missions</a>
                        </li>

                        <li class="dropdown">
                            <a href="index.html#">Ministries</a>
                            <ul>
                                <li><a href="volunteer.html">City Life Kids</a></li>
                                <li><a href="volunteer-carousel.html">Youth & Young Adults Ministry</a></li>
                                <li><a href="volunteer-details.html">Prayer Ministry</a></li>
                                <li><a href="become-a-volunteer.html">Women's Ministry</a></li>
                                <li><a href="pricing.html">Men's Ministry</a></li>
                                <li><a href="pricing-carousel.html">Worship Ministry</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="{{ route('courses.index') }}">Courses</a>
                            <ul>
                                <li><a href="gallery.html">Bible School Int'l</a></li>
                                <li><a href="gallery.html">Christian Development</a></li>
                                <li><a href="gallery-grid.html">Living with Significance</a></li>
                                <li><a href="gallery-filter.html">Dating without mating</a></li>
                                <li><a href="gallery-carousel.html">Theological teachings</a></li>
                            </ul>
                        </li>
                         <li class="dropdown">
                            <a href="index.html#">Media Centre</a>
                            <ul>
                                <li><a href="{{ route('teaching-series.index') }}">Teaching Series</a></li>
                                <li><a href="gallery.html">CityLife TalkTimes</a></li>
                                <li><a href="gallery-grid.html">CityLife Music</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('events.index') }}">Events</a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}">Contact Us</a>
                        </li>
                    </ul>
                </nav><!-- /.main-header__nav -->
                <div class="mobile-nav__btn mobile-nav__toggler">
                    <span></span>
                    <span></span>
                    <span></span>
                </div><!-- /.mobile-nav__toggler -->

                <!-- Member Authentication Section -->
                @auth('member')
                    <div class="main-header__user me-3">
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="icon-user me-2"></i>{{ Auth::guard('member')->user()->first_name }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('courses.dashboard') }}">
                                    <i class="icon-dashboard me-2"></i>My Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('member.logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="icon-logout me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="main-header__auth me-3">
                        <a href="{{ route('member.login') }}" class="btn btn-outline-primary me-2">
                            <i class="icon-user me-1"></i>Login
                        </a>
                        <a href="{{ route('member.register') }}" class="btn btn-primary">
                            <i class="icon-user-plus me-1"></i>Register
                        </a>
                    </div>
                @endauth

                <div class="main-header__cart"></div><!-- /.main-header__cart -->
                <a href="donate.html" class="cleenhearts-btn main-header__btn">
                    <div class="cleenhearts-btn__icon-box">
                        <div class="cleenhearts-btn__icon-box__inner"><span class="icon-donate"></span></div>
                    </div>
                    <span class="cleenhearts-btn__text">Your Giving</span>
                </a><!-- /.thm-btn main-header__btn -->
            </div><!-- /.main-header__right -->
        </div><!-- /.main-header__inner -->
    </div><!-- /.container -->
</header>
