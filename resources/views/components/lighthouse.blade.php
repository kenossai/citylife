@php
    $churchDetails = \App\Models\AboutPage::getActiveChurchDetails();
@endphp

<div class="topbar-one">
    <div class="container-fluid">
        <div class="topbar-one__inner">
            <ul class="list-unstyled topbar-one__info">
                @if($churchDetails && $churchDetails->email_address)
                <li class="topbar-one__info__item">
                    <span class="topbar-one__info__icon icon-paper-plane"></span>
                    <a href="mailto:{{ $churchDetails->email_address }}">{{ $churchDetails->email_address }}</a>
                </li>
                @endif
                @if($churchDetails && $churchDetails->address)
                <li class="topbar-one__info__item">
                    <span class="topbar-one__info__icon icon-location"></span>
                    {{ $churchDetails->address }}
                </li>
                @endif
            </ul><!-- /.list-unstyled topbar-one__info -->
            <div class="topbar-one__right">
                <div class="social-link topbar-one__social">
                    @if($churchDetails && $churchDetails->social_media_links)
                        @foreach($churchDetails->social_media_links as $platform => $url)
                            @if($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener">
                                    @switch(strtolower($platform))
                                        @case('facebook')
                                            <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                            <span class="sr-only">Facebook</span>
                                            @break
                                        @case('youtube')
                                            <i class="fab fa-youtube" aria-hidden="true"></i>
                                            <span class="sr-only">Youtube</span>
                                            @break
                                        @case('instagram')
                                            <i class="fab fa-instagram" aria-hidden="true"></i>
                                            <span class="sr-only">Instagram</span>
                                            @break
                                        @default
                                            <i class="fab fa-{{ strtolower($platform) }}" aria-hidden="true"></i>
                                            <span class="sr-only">{{ ucfirst($platform) }}</span>
                                    @endswitch
                                </a>
                            @endif
                        @endforeach
                    @else
                        {{-- Fallback social links if no data in database --}}
                        <a href="https://facebook.com">
                            <i class="fab fa-facebook-f" aria-hidden="true"></i>
                            <span class="sr-only">Facebook</span>
                        </a>
                        <a href="https://youtube.com" aria-hidden="true">
                            <i class="fab fa-youtube"></i>
                            <span class="sr-only">Youtube</span>
                        </a>
                    @endif
                </div><!-- /.topbar-one__social -->
            </div><!-- /.topbar-one__right -->
        </div><!-- /.topbar-one__inner -->
    </div><!-- /.container -->
</div>
