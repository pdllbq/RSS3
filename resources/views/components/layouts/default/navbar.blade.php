<nav class="navbar navbar-dark app-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home', ['lang' => app()->getLocale()]) }}">{{ config('app.name') }}</a>
    </div>
</nav>