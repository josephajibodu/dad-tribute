@extends('admin.layout')

@section('title', 'Submitted Tributes')

@section('content')
        <div class="topbar">
            <h1>Submitted Tributes</h1>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="link-button">Sign out</button>
            </form>
        </div>
        <p class="lead">
            Everything visitors have left on the <a href="{{ route('tribute') }}">tribute page</a>, newest first.
            @if (config('tribute.moderate'))
                Messages marked "waiting" show on the page once approved with <code>php artisan tributes:review</code>.
            @endif
        </p>

        <div class="stats">
            <div class="stat">
                <div class="stat__value">{{ $candleCount }}</div>
                <div class="stat__label">Candles lit</div>
            </div>
            <div class="stat">
                <div class="stat__value">{{ $messageCount }}</div>
                <div class="stat__label">With a message</div>
            </div>
            <div class="stat">
                <div class="stat__value">{{ $pendingCount }}</div>
                <div class="stat__label">Waiting for approval</div>
            </div>
        </div>

        @if ($tributes->isEmpty())
            <p class="none">No tributes yet.</p>
        @else
            <div class="filters" role="group" aria-label="Show">
                <button type="button" data-filter="all" aria-pressed="true">All</button>
                <button type="button" data-filter="pending" aria-pressed="false">Waiting</button>
                <button type="button" data-filter="approved" aria-pressed="false">Shown on page</button>
                <button type="button" data-filter="candle" aria-pressed="false">Candle only</button>
            </div>

            @foreach ($tributes as $tribute)
                @php
                    $status = match (true) {
                        ! $tribute->hasMessage() => 'candle',
                        $tribute->approved_at !== null => 'approved',
                        default => 'pending',
                    };
                @endphp
                <article class="tribute" data-status="{{ $status }}">
                    <div class="tribute__head">
                        <span class="tribute__name">{{ $tribute->name }}</span>
                        @if ($tribute->relation)
                            <span class="tribute__relation">{{ $tribute->relation }}</span>
                        @endif
                        @if ($status === 'pending')
                            <span class="badge badge--pending">Waiting</span>
                        @elseif ($status === 'approved')
                            <span class="badge">Shown</span>
                        @endif
                        <time class="tribute__date" datetime="{{ $tribute->created_at->toIso8601String() }}">{{ $tribute->created_at->format('D j M Y, g:ia') }}</time>
                    </div>
                    @if ($tribute->hasMessage())
                        <p class="tribute__message">{{ $tribute->message }}</p>
                    @else
                        <p class="tribute__empty">Lit a candle, no message.</p>
                    @endif
                </article>
            @endforeach
        @endif
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-filter]').forEach((button) => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;

                document.querySelectorAll('[data-filter]').forEach((b) => b.setAttribute('aria-pressed', b === button ? 'true' : 'false'));
                document.querySelectorAll('[data-status]').forEach((tribute) => {
                    tribute.hidden = filter !== 'all' && tribute.dataset.status !== filter;
                });
            });
        });
    </script>
@endpush
