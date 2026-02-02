<header class="main-header sticky-header sticky-header--normal">
    <div class="container-fluid">
        <div class="main-header__inner" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="main-header__logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="CityLife Church" width="100">
                </a>
                <button type="button" class="main-header__sidebar-btn sidebar-btn__toggler">
                    <span class="icon-grid"></span>
                </button><!-- /.main-header__sidebar-btn -->
            </div><!-- /.main-header__logo -->

            <nav class="main-header__nav main-menu" style="flex: 1; display: flex; justify-content: center;">
                <ul class="main-menu__list">
                    <li>
                        <a href="/">Home</a>
                    </li>

                    <li>
                        <a href="{{ route('about') }}">About Us</a>
                    </li>

                    <li class="dropdown">
                        <a href="{{ route('missions.index') }}">Missions</a>
                    </li>

                    <li class="dropdown">
                        <a href="{{ route('ministries.index') }}">Ministries</a>
                        <ul>
                            @php
                                $headerMinistries = \App\Models\Ministry::active()
                                    ->orderBy('sort_order')
                                    ->orderBy('name')
                                    ->take(6)
                                    ->get();
                            @endphp
                            @foreach($headerMinistries as $ministry)
                                <li><a href="{{ route('ministries.show', $ministry->slug) }}">{{ $ministry->name }}</a></li>
                            @endforeach
                            <li><hr class="dropdown-divider"></li>
                            {{-- <li><a href="{{ route('baby-dedication.index') }}">Baby Dedication</a></li> --}}
                            {{-- <li><a href="{{ route('volunteer.index') }}">Volunteer</a></li> --}}
                            <li><a href="{{ route('ministries.index') }}">View All Ministries</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#">Media</a>
                        <ul>
                            <li><a href="{{ route('teaching-series.index') }}">Teaching Series</a></li>
                            <li><a href="{{ route('citylife-talktime.index') }}">CityLife TalkTimes</a></li>
                            <li><a href="{{ route('citylife-music.index') }}">CityLife Music</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#">Resources</a>
                        <ul>
                            <li><a href="{{ route('courses.index') }}">Courses</a></li>
                            <li><a href="{{ route('books.index') }}">Books</a></li>
                            <li><a href="{{ route('bible-school-international.about') }}">Bible School</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('events.index') }}">Events</a>
                    </li>

                    <li>
                        <a href="{{ route('contact') }}">Contact Us</a>
                    </li>

                    @auth('member')
                    <li>
                        <a href="{{ route('courses.dashboard') }}">My Dashboard</a>
                    </li>
                    @endauth
                </ul>
            </nav><!-- /.main-header__nav -->

            <div class="main-header__right">
                <div class="mobile-nav__btn mobile-nav__toggler">
                    <span></span>
                    <span></span>
                    <span></span>
                </div><!-- /.mobile-nav__toggler -->

                <div class="main-header__cart"></div><!-- /.main-header__cart -->
                <a href="{{ route('giving.index') }}" class="citylife-btn main-header__btn">
                    <div class="citylife-btn__icon-box">
                        <div class="citylife-btn__icon-box__inner"><span class="icon-donate"></span></div>
                    </div>
                    <span class="citylife-btn__text">Your Giving</span>
                </a><!-- /.thm-btn main-header__btn -->
            </div><!-- /.main-header__right -->
        </div><!-- /.main-header__inner -->
    </div><!-- /.container -->
</header>
