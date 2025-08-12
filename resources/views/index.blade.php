<x-app-layout>
    @section('title', 'Home')
    <x-hero-banner :banners="$banners" />
    <x-about :aboutPage="$aboutPage" />
    <x-becoming :section="$section" />
    <x-courses />
    <x-events :events="$events" />
    <x-support />
    <x-volunteer />
</x-app-layout>
