<x-base-layout>
    <div class="page-wrapper">
    <x-lighthouse />
        <x-header />

        {{ $slot }}

        <x-newsletter />
        <x-footer />
    </div>
    <x-mobile-menu />
    <x-search />
    {{-- Scroll to top --}}
    <a href="index.html#" data-target="html" class="scroll-to-target scroll-to-top">
        <span class="scroll-to-top__text">back top</span>
        <span class="scroll-to-top__wrapper"><span class="scroll-to-top__inner"></span></span>
    </a>
    {{-- End Scroll to top --}}
    <x-sidebar />
</x-base-layout>
