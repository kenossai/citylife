<x-app-layout>
    @section('title', 'Courses - ' . $course->title)
<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <!-- /.page-header__bg -->
    <div class="container">
        <h3 class="text-white">Our Courses</h3>
        <h2 class="page-header__title">{{ $course->title }}</h2>
        <p class="section-header__text">{{ $course->description }}</p>
        <ul class="cleenhearts-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><i class="icon-book"></i> <a href="{{ route('courses.index') }}">Courses List</a></li>
            <li><span>{{ $course->title }}</span></li>
        </ul><!-- /.thm-breadcrumb list-unstyled -->
    </div><!-- /.container -->
</section>
</x-app-layout>
