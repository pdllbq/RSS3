<nav class="navbar navbar-dark app-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home', ['lang' => app()->getLocale()]) }}">{{ config('app.name') }}</a>

        <div class="language-switcher" aria-label="Language selection">
            @foreach(config('app.supported_locales', ['ru', 'lv']) as $locale)
                <a
                    class="language-switcher__link @if(app()->getLocale() === $locale) language-switcher__link--active @endif"
                    href="{{ route('home', ['lang' => $locale]) }}"
                    hreflang="{{ $locale }}"
                    aria-current="{{ app()->getLocale() === $locale ? 'page' : 'false' }}"
                >
                    {{ strtoupper($locale) }}
                </a>
            @endforeach
        </div>
    </div>
</nav>
