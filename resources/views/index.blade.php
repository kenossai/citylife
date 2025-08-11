<x-app-layout>
    @section('title', 'Home')
    <x-hero-banner :banners="$banners" />
    <x-about />
    <x-becoming />
    <x-courses />
    <x-events :events="$events" />
    <x-support />
    <x-volunteer />
</x-app-layout>
