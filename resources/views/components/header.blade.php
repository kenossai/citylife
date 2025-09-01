<header class="main-header sticky-header sticky-header--normal">
    <div class="container-fluid">
        <div class="main-header__inner">
            <div class="main-header__logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="CityLife Church" width="100">
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
                            <a href="{{ route('about') }}">About Us</a>
                        </li>

                        <li class="dropdown">
                            <a href="{{ route('team.index') }}">Leadership</a>
                        </li>
                        <li class="dropdown">
                            <a href="index.html#">Missions</a>
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
                                <li><a href="{{ route('ministries.index') }}">View All Ministries</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="{{ route('courses.index') }}">Courses</a>
                        </li>
                         <li class="dropdown">
                            <a href="index.html#">Media</a>
                            <ul>
                                <li><a href="{{ route('teaching-series.index') }}">Teaching Series</a></li>
                                <li><a href="{{ route('citylife-talktime.index') }}">CityLife TalkTimes</a></li>
                                <li><a href="{{ route('citylife-music.index') }}">CityLife Music</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('events.index') }}">Events</a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}">Contact Us</a>
                        </li>
                        @auth('member')
                        <li class="dropdown">
                            <a href="javascript:void(0)">Learning</a>
                            <ul>
                                <li><a href="{{ route('courses.dashboard') }}">Dashboard</a></li>
                                <li>
                                    <form action="{{ route('member.logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @else
                        <li class="dropdown">
                            <a href="index.html#">Member</a>
                            <ul>
                                <li><a href="{{ route('member.login') }}">Login</a></li>
                                <li><a href="{{ route('member.register') }}">Register</a></li>
                            </ul>
                        </li>
                        @endauth
                    </ul>
                </nav><!-- /.main-header__nav -->
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
