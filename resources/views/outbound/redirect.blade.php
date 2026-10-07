<x-layouts.default.app>
    <x-slot name="title">{{ $feedItem->title }} - Переход на источник</x-slot>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card bg-dark text-light border-secondary shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <span class="badge bg-primary px-3 py-2 fs-6">Переход к источнику</span>
                        </div>

                        <h4 class="card-title mb-3">
                            {{ $feedItem->title }}
                        </h4>

                        @if ($feedItem->feedSource)
                            <p class="text-secondary small mb-3">
                                Источник: <strong>{{ $feedItem->feedSource->name() }}</strong>
                            </p>
                        @endif

                        @if ($feedItem->image_url)
                            <div class="mb-3">
                                <img src="{{ $feedItem->image_url }}" alt="{{ $feedItem->title }}" class="img-fluid rounded" style="max-height: 240px; object-fit: cover;">
                            </div>
                        @endif

                        @if ($feedItem->description)
                            <p class="card-text text-muted small mb-4 text-start">
                                {{ Str::limit($feedItem->description, 200) }}
                            </p>
                        @endif

                        <div class="d-flex flex-column gap-2 mt-4">
                            <a href="{{ $feedItem->url }}" id="redirect-link" class="btn btn-primary btn-lg" rel="noopener noreferrer">
                                Перейти на сайт источника &rarr;
                            </a>
                            <a href="javascript:history.back()" class="btn btn-link text-decoration-none text-secondary btn-sm">
                                Вернуться назад
                            </a>
                        </div>

                        <div class="mt-3 text-secondary small" id="countdown-text">
                            Автоматический переход через <span id="countdown">3</span> сек...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            let seconds = 3;
            const targetUrl = @json($feedItem->url);
            const countdownEl = document.getElementById('countdown');
            const interval = setInterval(function() {
                seconds--;
                if (countdownEl) {
                    countdownEl.textContent = seconds;
                }
                if (seconds <= 0) {
                    clearInterval(interval);
                    window.location.href = targetUrl;
                }
            }, 1000);
        })();
    </script>
</x-layouts.default.app>
