<x-app-layout>
@section('title', $music->title . ' - CityLife Music')
@section('description', $music->description ?: 'Experience ' . $music->title . ' - inspiring worship music from CityLife Church.')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="text-white">{{ $music->title }}</h2>
        <h2 class="page-header__title">CityLife Music</h2>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('citylife-music.index') }}">CityLife Music</a></li>
            <li><span>{{ $music->title }}</span></li>
        </ul>
    </div>
</section>

<section class="product-details section-space">
    <div class="container">
        <div class="row gutter-y-60">
            <div class="col-lg-6">
                <div class="product-details__img">
                    <img src="{{ $music->image_url }}" alt="{{ $music->title }}" class="img-fluid">
                    @if($music->is_featured)
                        <div class="product-details__featured-badge">
                            <span class="badge bg-primary">Featured</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="product-details__content">
                    <h3 class="product-details__title">{{ $music->title }}</h3>

                    <div class="product-details__meta mb-4">
                        @if($music->artist)
                            <div class="mb-2">
                                <strong>Artist:</strong> {{ $music->artist }}
                            </div>
                        @endif
                        @if($music->album)
                            <div class="mb-2">
                                <strong>Album:</strong> {{ $music->album }}
                            </div>
                        @endif
                        @if($music->genre)
                            <div class="mb-2">
                                <strong>Genre:</strong> {{ $music->genre }}
                            </div>
                        @endif
                    </div>

                    @if($music->description)
                        <div class="product-details__text">
                            <p>{{ $music->description }}</p>
                        </div>
                    @endif

                    <div class="product-details__streaming-links">
                        <h5 class="mb-3">Listen Now</h5>
                        <div class="streaming-platforms">
                            @if($music->spotify_url)
                                <a href="{{ $music->spotify_url }}" target="_blank" class="streaming-link spotify">
                                    <i class="fab fa-spotify"></i>
                                    <span>Listen on Spotify</span>
                                </a>
                            @endif

                            @if($music->apple_music_url)
                                <a href="{{ $music->apple_music_url }}" target="_blank" class="streaming-link apple-music">
                                    <i class="fab fa-apple"></i>
                                    <span>Listen on Apple Music</span>
                                </a>
                            @endif

                            @if($music->youtube_url)
                                <a href="{{ $music->youtube_url }}" target="_blank" class="streaming-link youtube">
                                    <i class="fab fa-youtube"></i>
                                    <span>Watch on YouTube</span>
                                </a>
                            @endif

                            @if(!$music->spotify_url && !$music->apple_music_url && !$music->youtube_url)
                                <p class="text-muted">Streaming links will be available soon.</p>
                            @endif
                        </div>
                    </div>

                    <div class="product-details__actions mt-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <a href="{{ route('citylife-music.index') }}" class="citylife-btn citylife-btn--border w-100">
                                    <div class="citylife-btn__icon-box">
                                        <div class="citylife-btn__icon-box__inner"><span class="icon-arrow-left"></span></div>
                                    </div>
                                    <span class="citylife-btn__text">Back to Music</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                @if($music->artist)
                                    <a href="{{ route('citylife-music.index', ['artist' => $music->artist]) }}" class="citylife-btn w-100">
                                        <div class="citylife-btn__icon-box">
                                            <div class="citylife-btn__icon-box__inner"><span class="icon-user"></span></div>
                                        </div>
                                        <span class="citylife-btn__text">More by {{ $music->artist }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Share Section -->
                    <div class="product-details__share mt-4">
                        <h6>Share this song:</h6>
                        <div class="social-share">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                               target="_blank" class="share-link facebook" title="Share on Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($music->title . ' - CityLife Music') }}"
                               target="_blank" class="share-link twitter" title="Share on Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($music->title . ' - ' . request()->fullUrl()) }}"
                               target="_blank" class="share-link whatsapp" title="Share on WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="mailto:?subject={{ urlencode($music->title . ' - CityLife Music') }}&body={{ urlencode('Check out this inspiring worship song: ' . request()->fullUrl()) }}"
                               class="share-link email" title="Share via Email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($relatedMusic->isNotEmpty())
            <div class="related-products section-space-top">
                <div class="section-header text-center">
                    <h3 class="section-header__title">Related Music</h3>
                    <p class="section-header__text">Discover more inspiring worship music</p>
                </div>

                <div class="row gutter-y-30">
                    @foreach($relatedMusic as $related)
                        <div class="col-sm-6 col-lg-3">
                            <div class="product-item">
                                <a href="{{ route('citylife-music.show', $related->slug) }}" class="product-item__img">
                                    <img src="{{ $related->image_url }}" alt="{{ $related->title }}">
                                    @if($related->is_featured)
                                        <div class="product-item__featured-badge">
                                            <span class="badge bg-primary">Featured</span>
                                        </div>
                                    @endif
                                    <div class="product-item__play-overlay">
                                        <span class="icon-play"></span>
                                    </div>
                                </a>
                                <div class="product-item__content">
                                    <h5 class="product-item__title">
                                        <a href="{{ route('citylife-music.show', $related->slug) }}">{{ $related->title }}</a>
                                    </h5>
                                    @if($related->artist)
                                        <p class="product-item__artist">{{ $related->artist }}</p>
                                    @endif
                                    <a href="{{ route('citylife-music.show', $related->slug) }}" class="citylife-btn citylife-btn--border">
                                        <div class="citylife-btn__icon-box">
                                            <div class="citylife-btn__icon-box__inner"><span class="icon-play"></span></div>
                                        </div>
                                        <span class="citylife-btn__text">Listen</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<style>
.product-details__img {
    position: relative;
    text-align: center;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.product-details__featured-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 2;
}

.product-details__meta {
    font-size: 1rem;
    line-height: 1.6;
}

.product-details__meta div {
    color: #666;
}

.product-details__meta strong {
    color: #333;
}

.streaming-platforms {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.streaming-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.streaming-link i {
    font-size: 1.5rem;
    margin-right: 12px;
    width: 24px;
}

.streaming-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    text-decoration: none;
}

.streaming-link.spotify {
    background: linear-gradient(135deg, #1DB954, #1ed760);
    color: white;
}

.streaming-link.spotify:hover {
    background: linear-gradient(135deg, #1ed760, #1DB954);
    color: white;
}

.streaming-link.apple-music {
    background: linear-gradient(135deg, #fc3c44, #ff6b6b);
    color: white;
}

.streaming-link.apple-music:hover {
    background: linear-gradient(135deg, #ff6b6b, #fc3c44);
    color: white;
}

.streaming-link.youtube {
    background: linear-gradient(135deg, #FF0000, #ff4444);
    color: white;
}

.streaming-link.youtube:hover {
    background: linear-gradient(135deg, #ff4444, #FF0000);
    color: white;
}

.social-share {
    display: flex;
    gap: 0.75rem;
    margin-top: 0.5rem;
}

.share-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.share-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    text-decoration: none;
    color: white;
}

.share-link.facebook {
    background: #3b5998;
}

.share-link.twitter {
    background: #1da1f2;
}

.share-link.whatsapp {
    background: #25d366;
}

.share-link.email {
    background: #6c757d;
}

.product-item__featured-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
}

.product-item__play-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.7);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-item__img:hover .product-item__play-overlay {
    opacity: 1;
}

.product-item__img {
    position: relative;
    display: block;
    overflow: hidden;
}

.product-item__artist {
    font-size: 0.875rem;
    color: #666;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .streaming-platforms {
        gap: 0.75rem;
    }

    .streaming-link {
        padding: 10px 16px;
        font-size: 0.9rem;
    }

    .streaming-link i {
        font-size: 1.2rem;
        margin-right: 10px;
    }

    .social-share {
        justify-content: center;
    }

    .product-details__actions .row > div {
        margin-bottom: 1rem;
    }
}
</style>
</x-app-layout>
