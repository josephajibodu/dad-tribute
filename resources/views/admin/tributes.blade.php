<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Submitted Tributes | Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500&family=Hanken+Grotesk:wght@400;500;600&display=swap">
    <style>
        :root {
            --ink: #0D0C0A;
            --ink-3: #12110D;
            --line: #2A261F;
            --ivory: #EFE7D6;
            --muted: #A39A88;
            --faint: #6F6758;
            --gold: #C8A56A;
            --serif: 'Cormorant Garamond', Georgia, serif;
            --sans: 'Hanken Grotesk', system-ui, -apple-system, 'Segoe UI', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--ink);
            color: var(--ivory);
            font-family: var(--sans);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        main { max-width: 860px; margin: 0 auto; padding: 48px 16px 80px; }

        h1 { font-family: var(--serif); font-weight: 500; font-size: 2.4rem; margin: 0 0 8px; }

        .lead { color: var(--muted); margin: 0 0 32px; }
        .lead a { color: var(--gold); }

        .stats { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; }
        .stat { flex: 1 1 140px; border: 1px solid var(--line); border-radius: 8px; padding: 14px 16px; background: var(--ink-3); }
        .stat__value { font-family: var(--serif); font-size: 2rem; line-height: 1; }
        .stat__label { color: var(--muted); font-size: .85rem; margin-top: 6px; }

        .filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 24px; }
        .filters button {
            font: inherit; font-size: .85rem; cursor: pointer;
            border: 1px solid var(--line); border-radius: 999px; padding: 6px 14px;
            background: transparent; color: var(--muted);
        }
        .filters button[aria-pressed="true"] { border-color: var(--gold); color: var(--gold); }

        .tribute { border-top: 1px solid var(--line); padding: 20px 0; }
        .tribute__head { display: flex; flex-wrap: wrap; align-items: baseline; gap: 4px 12px; }
        .tribute__name { font-weight: 600; }
        .tribute__relation { color: var(--muted); }
        .tribute__date { color: var(--faint); font-size: .85rem; margin-left: auto; }
        .tribute__message { margin: 10px 0 0; white-space: pre-line; overflow-wrap: anywhere; }
        .tribute__empty { margin: 10px 0 0; color: var(--faint); font-style: italic; }

        .badge { font-size: .75rem; letter-spacing: .04em; text-transform: uppercase; border-radius: 4px; padding: 2px 8px; border: 1px solid var(--line); color: var(--muted); }
        .badge--pending { border-color: var(--gold); color: var(--gold); }

        .none { color: var(--muted); border-top: 1px solid var(--line); padding-top: 24px; }
    </style>
</head>
<body>
    <main>
        <h1>Submitted Tributes</h1>
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
    </main>

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
</body>
</html>
