<x-app-layout>
    @section('title', 'Home')
    @section('meta_description', 'Welcome to CityLife Church. Join us for worship, community, and spiritual growth. Discover our events, courses, and volunteer opportunities.')
    <x-hero-banner :banners="$banners" />
    <x-live-stream-widget />
    <x-about :aboutPage="$aboutPage" />
    <x-becoming :section="$section" />
    {{-- <x-courses /> --}}
    <x-events :events="$events" />
    {{-- <x-support /> --}}
    <x-volunteer />
</x-app-layout>
