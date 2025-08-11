<header class="main-header sticky-header sticky-header--normal">
    <div class="container-fluid">
        <div class="main-header__inner">
            <div class="main-header__logo">
                <a href="index.html">
                    <img src="assets/images/logo-dark.png" alt="Cleenhearts HTML" width="159">
                </a>
                <button type="button" class="main-header__sidebar-btn sidebar-btn__toggler">
                    <span class="icon-grid"></span>
                </button><!-- /.main-header__sidebar-btn -->
            </div><!-- /.main-header__logo -->
            <div class="main-header__right">
                <nav class="main-header__nav main-menu">
                    <ul class="main-menu__list">


                        <li class="megamenu megamenu-clickable megamenu-clickable--toggler">
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
                                <li><a href="gallery.html">Teaching Series</a></li>
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
