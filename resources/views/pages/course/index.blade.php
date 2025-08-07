<x-app-layout>
    @section('title', 'Courses')
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h3 class="text-white">Our Courses</h3>
        <h2 class="page-header__title">Learn and Grow in Faith</h2>
        <p class="section-header__text">Join our comprehensive courses designed to strengthen your faith, develop leadership skills, and grow in your walk with Christ.</p>
        <ul class="cleenhearts-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><span>Courses</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>

<section class="donations-page section-space">
    <div class="container">

        <div id="courses-container" class="row gutter-y-30">
            @include('pages.course.partials.course-cards', ['courses' => $courses])
        </div><!-- /.row -->

        <!-- Loading indicator -->
        <div id="loading-indicator" class="text-center" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Loading more courses...</p>
        </div>

        <!-- No more courses message -->
        <div id="no-more-courses" class="text-center" style="display: none;">
            <p class="text-muted">No more courses to load.</p>
        </div>
    </div><!-- /.container -->
</section>

@push('scripts')
<script>
$(document).ready(function() {
    let currentPage = {{ $courses->currentPage() }};
    let hasMorePages = {{ $courses->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;

    function loadMoreCourses() {
        if (isLoading || !hasMorePages) return;

        isLoading = true;
        $('#loading-indicator').show();

        $.ajax({
            url: '{{ route("courses.index") }}',
            type: 'GET',
            data: {
                page: currentPage + 1
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.html) {
                    $('#courses-container').append(response.html);
                    currentPage++;
                    hasMorePages = response.has_more;

                    // Trigger WOW animations for new elements
                    if (typeof WOW !== 'undefined') {
                        new WOW().init();
                    }
                }

                if (!hasMorePages) {
                    $('#no-more-courses').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading more courses:', error);
            },
            complete: function() {
                isLoading = false;
                $('#loading-indicator').hide();
            }
        });
    }

    // Infinite scroll detection
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadMoreCourses();
        }
    });

    // Optional: Load more button (fallback)
    $('<button id="load-more-btn" class="btn btn-primary mt-4" style="display: none;">Load More Courses</button>')
        .appendTo('#courses-container')
        .click(function() {
            loadMoreCourses();
        });

    // Show load more button if infinite scroll fails
    setTimeout(function() {
        if (hasMorePages && !isLoading) {
            $('#load-more-btn').show();
        }
    }, 5000);
});
</script>
@endpush

<style>
.course-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.course-card__meta {
    margin-bottom: 15px;
}

.course-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 14px;
    color: #666;
}

.course-meta__item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.course-meta__item i {
    font-size: 12px;
}

.course-card__description {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
    line-height: 1.5;
}

.course-card__details {
    margin-bottom: 20px;
}

.course-instructor,
.course-schedule {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #555;
    margin-bottom: 5px;
}

.course-card__actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}

.course-status {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 12px;
}

.course-status--open {
    background-color: #d4edda;
    color: #155724;
}

.course-status--closed {
    background-color: #f8d7da;
    color: #721c24;
}

#loading-indicator {
    padding: 40px 0;
}

.spinner-border {
    width: 2rem;
    height: 2rem;
}
</style>
</x-app-layout>
