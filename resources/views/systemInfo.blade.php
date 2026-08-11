<x-layouts.default.app>
    <x-slot name="title">System Info #{{ $feedItem->id }}</x-slot>

    @php
        $formatJson = static fn ($value) => json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $embeddingDimensions = is_array($feedItem->embedding?->embedding_qwen3_8b_1536)
            ? count($feedItem->embedding->embedding_qwen3_8b_1536)
            : null;
    @endphp

    <div class="container py-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
            <div>
                <div class="text-secondary small mb-1">System info / Feed item #{{ $feedItem->id }}</div>
                <h1 class="h3 mb-2">{{ $feedItem->title }}</h1>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge text-bg-secondary">source: {{ $feedItem->feedSource?->name() ?? 'n/a' }}</span>
                    <span class="badge text-bg-secondary">lang: {{ $feedItem->language ?? 'n/a' }}</span>
                    <span class="badge text-bg-{{ $feedItem->is_category_checked ? 'success' : 'warning' }}">
                        category checked: {{ $feedItem->is_category_checked ? 'yes' : 'no' }}
                    </span>
                    <span class="badge text-bg-{{ $feedItem->is_similarity_checked ? 'success' : 'warning' }}">
                        similarity checked: {{ $feedItem->is_similarity_checked ? 'yes' : 'no' }}
                    </span>
                    <span class="badge text-bg-{{ $feedItem->needs_category_check ? 'danger' : 'success' }}">
                        manual category check: {{ $feedItem->needs_category_check ? 'needed' : 'no' }}
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-start gap-2">
                <a href="{{ route('home', ['lang' => app()->getLocale()]) }}" class="btn btn-outline-secondary btn-sm">Home</a>
                <a href="{{ $feedItem->url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">Open item</a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-secondary small">Embedding</div>
                        <div class="fs-5 fw-semibold">{{ $embeddingDimensions ? $embeddingDimensions.' dims' : 'missing' }}</div>
                        <div class="text-secondary small">{{ $feedItem->embedding?->model ?? 'no model' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-secondary small">Cluster</div>
                        <div class="fs-5 fw-semibold">#{{ $feedItem->feed_item_cluster_id ?? 'n/a' }}</div>
                        <div class="text-secondary small">score: {{ $feedItem->similarity_score !== null ? number_format($feedItem->similarity_score, 4) : 'n/a' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-secondary small">Classification</div>
                        <div class="fs-5 fw-semibold">{{ $feedItem->globalCategory?->name ?? 'n/a' }}</div>
                        <div class="text-secondary small">AI log: {{ $feedItem->ai_request_log_id ? '#'.$feedItem->ai_request_log_id : 'n/a' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-secondary small">Dates</div>
                        <div class="fs-6 fw-semibold">{{ $feedItem->published_at?->format('Y-m-d H:i') ?? 'not published' }}</div>
                        <div class="text-secondary small">fetched: {{ $feedItem->fetched_at?->format('Y-m-d H:i') ?? 'n/a' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <section class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h2 class="h5 mb-0">Nearest by embedding</h2>
                        <span class="text-secondary small">{{ $embeddingMatches->count() }} matches</span>
                    </div>

                    @if($embeddingError)
                        <div class="alert alert-warning mb-0">
                            Nearest search unavailable: {{ $embeddingError }}
                        </div>
                    @elseif($embeddingMatches->isEmpty())
                        <div class="alert alert-secondary mb-0">
                            No embedding matches found.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Item</th>
                                        <th scope="col">Embedding score</th>
                                        <th scope="col">Cluster score</th>
                                        <th scope="col">Cluster</th>
                                        <th scope="col">Category</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($embeddingMatches as $match)
                                        <tr>
                                            <td>{{ $match->id }}</td>
                                            <td>
                                                <a href="{{ route('system.info', ['itemId' => $match->id]) }}" class="fw-semibold text-decoration-none">
                                                    {{ $match->title }}
                                                </a>
                                                <div class="small text-secondary">{{ $match->custom_title ?? $match->source_title ?? 'unknown source' }}</div>
                                            </td>
                                            <td>{{ number_format((float) $match->embedding_similarity, 4) }}</td>
                                            <td>{{ $match->cluster_similarity_score !== null ? number_format((float) $match->cluster_similarity_score, 4) : 'n/a' }}</td>
                                            <td>
                                                {{ $match->feed_item_cluster_id ? '#'.$match->feed_item_cluster_id : 'n/a' }}
                                                @if($match->is_cluster_main)
                                                    <span class="badge text-bg-info ms-1">main</span>
                                                @endif
                                            </td>
                                            <td>{{ $match->global_category_name ?? 'n/a' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>

                <section class="mb-4">
                    <h2 class="h5 mb-2">Classification prompt</h2>
                    <pre class="bg-dark text-light p-3 rounded small text-wrap white-space-pre-wrap">{{ $aiRequestLog?->prompt ?? 'No prompt stored.' }}</pre>
                </section>

                <section class="mb-4">
                    <h2 class="h5 mb-2">AI answer</h2>
                    <pre class="bg-dark text-light p-3 rounded small text-wrap white-space-pre-wrap">{{ $aiRequestLog?->response ?? $aiRequestLog?->error_message ?? 'No response stored.' }}</pre>
                </section>
            </div>

            <div class="col-lg-4">
                <section class="mb-4">
                    <h2 class="h5 mb-2">Item details</h2>
                    <div class="list-group small">
                        <div class="list-group-item d-flex justify-content-between gap-3">
                            <span class="text-secondary">GUID</span>
                            <span class="text-break text-end">{{ $feedItem->guid ?? 'n/a' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between gap-3">
                            <span class="text-secondary">Author</span>
                            <span class="text-break text-end">{{ $feedItem->author ?? 'n/a' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between gap-3">
                            <span class="text-secondary">Checksum</span>
                            <span class="text-break text-end">{{ $feedItem->checksum ?? 'n/a' }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between gap-3">
                            <span class="text-secondary">Cluster main</span>
                            <span>{{ $feedItem->is_cluster_main ? 'yes' : 'no' }}</span>
                        </div>
                    </div>
                </section>

                <section class="mb-4">
                    <h2 class="h5 mb-2">Categories</h2>
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="text-secondary small mb-1">Global categories</div>
                                @forelse($feedItem->globalCategories as $category)
                                    <span class="badge text-bg-primary me-1 mb-1">{{ $category->name }}</span>
                                @empty
                                    <span class="text-secondary small">none</span>
                                @endforelse
                            </div>
                            <div>
                                <div class="text-secondary small mb-1">RSS categories</div>
                                @forelse($feedItem->categories as $category)
                                    <span class="badge text-bg-secondary me-1 mb-1">{{ $category->label ?? $category->term }}</span>
                                @empty
                                    <span class="text-secondary small">none</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h2 class="h5 mb-0">Cluster items</h2>
                        <span class="text-secondary small">{{ $clusterItems->count() }}</span>
                    </div>
                    <div class="list-group small">
                        @forelse($clusterItems as $clusterItem)
                            <a href="{{ route('system.info', ['itemId' => $clusterItem->id]) }}" class="list-group-item list-group-item-action">
                                <div class="fw-semibold">{{ $clusterItem->title }}</div>
                                <div class="text-secondary">
                                    score: {{ $clusterItem->similarity_score !== null ? number_format($clusterItem->similarity_score, 4) : 'n/a' }}
                                </div>
                            </a>
                        @empty
                            <div class="list-group-item text-secondary">No other items in cluster.</div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        <section class="mb-4">
            <h2 class="h5 mb-2">AI request log</h2>
            @if($aiRequestLog)
                <div class="table-responsive mb-3">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th scope="row">Status</th>
                                <td>{{ $aiRequestLog->status }}</td>
                                <th scope="row">Task</th>
                                <td>{{ $aiRequestLog->task ?? 'n/a' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Provider</th>
                                <td>{{ $aiRequestLog->provider?->name ?? 'n/a' }}</td>
                                <th scope="row">Model</th>
                                <td>{{ $aiRequestLog->model?->name ?? $aiRequestLog->model?->provider_model_id ?? 'n/a' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tokens input</th>
                                <td>{{ $aiRequestLog->tokens_input ?? 'n/a' }}</td>
                                <th scope="row">Tokens output</th>
                                <td>{{ $aiRequestLog->tokens_output ?? 'n/a' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Tokens total</th>
                                <td>{{ $aiRequestLog->tokens_total ?? 'n/a' }}</td>
                                <th scope="row">Cost</th>
                                <td>{{ $aiRequestLog->cost ?? 'n/a' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="accordion" id="aiLogAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#messagesPanel">
                                Messages
                            </button>
                        </h3>
                        <div id="messagesPanel" class="accordion-collapse collapse show" data-bs-parent="#aiLogAccordion">
                            <div class="accordion-body">
                                <pre class="bg-light border rounded p-3 small text-wrap white-space-pre-wrap">{{ $formatJson($aiRequestLog->messages) }}</pre>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#requestPayloadPanel">
                                Request payload
                            </button>
                        </h3>
                        <div id="requestPayloadPanel" class="accordion-collapse collapse" data-bs-parent="#aiLogAccordion">
                            <div class="accordion-body">
                                <pre class="bg-light border rounded p-3 small text-wrap white-space-pre-wrap">{{ $formatJson($aiRequestLog->request_payload) }}</pre>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#responsePayloadPanel">
                                Response payload
                            </button>
                        </h3>
                        <div id="responsePayloadPanel" class="accordion-collapse collapse" data-bs-parent="#aiLogAccordion">
                            <div class="accordion-body">
                                <pre class="bg-light border rounded p-3 small text-wrap white-space-pre-wrap">{{ $formatJson($aiRequestLog->response_payload) }}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-secondary mb-0">No AI request log linked to this item.</div>
            @endif
        </section>
    </div>
</x-layouts.default.app>
