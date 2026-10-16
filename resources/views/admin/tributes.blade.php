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
                Messages marked "waiting" show on the page once you approve them.
            @endif
        </p>

        <div class="stats">
            <div class="stat">
                <div class="stat__value" data-count="candles">{{ $counts['candles'] }}</div>
                <div class="stat__label">Candles lit</div>
            </div>
            <div class="stat">
                <div class="stat__value" data-count="messages">{{ $counts['messages'] }}</div>
                <div class="stat__label">With a message</div>
            </div>
            <div class="stat">
                <div class="stat__value" data-count="pending">{{ $counts['pending'] }}</div>
                <div class="stat__label">Waiting for approval</div>
            </div>
        </div>

        <p class="flash" data-flash role="status">{{ session('status') }}</p>

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
                @include('admin.tribute', ['tribute' => $tribute])
            @endforeach

            <p class="none" data-filter-empty hidden>Nothing here.</p>
        @endif
@endsection

@push('scripts')
    <script>
        (function () {
            var filter = new URLSearchParams(location.search).get('show') || 'all';
            var flash = document.querySelector('[data-flash]');

            function matches(tribute) {
                return filter === 'all' || tribute.dataset.status === filter;
            }

            function applyFilter() {
                var shown = 0;
                document.querySelectorAll('[data-filter]').forEach(function (b) {
                    b.setAttribute('aria-pressed', b.dataset.filter === filter ? 'true' : 'false');
                });
                document.querySelectorAll('[data-status]').forEach(function (tribute) {
                    tribute.hidden = !matches(tribute);
                    if (!tribute.hidden) shown++;
                });
                var empty = document.querySelector('[data-filter-empty]');
                if (empty) empty.hidden = shown > 0;
            }

            document.querySelectorAll('[data-filter]').forEach(function (button) {
                button.addEventListener('click', function () {
                    filter = button.dataset.filter;
                    var url = new URL(location.href);
                    if (filter === 'all') url.searchParams.delete('show'); else url.searchParams.set('show', filter);
                    history.replaceState(null, '', url);
                    applyFilter();
                });
            });

            if (!document.querySelector('[data-filter="' + filter + '"]')) filter = 'all';
            applyFilter();

            // Approve, hide and remove in place, so the list keeps its scroll position
            document.addEventListener('submit', function (e) {
                var form = e.target.closest('[data-action]');
                if (!form || !window.fetch) return;
                e.preventDefault();

                if (form.dataset.confirm && !confirm(form.dataset.confirm)) return;

                var tribute = form.closest('[data-status]');
                tribute.querySelectorAll('button').forEach(function (b) { b.disabled = true; });

                fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: new FormData(form),
                    credentials: 'same-origin'
                }).then(function (res) {
                    if (res.status === 419 || res.status === 401) throw new Error('This page has been open a while. Please refresh and try again.');
                    if (!res.ok) throw new Error('That did not work. Please try again.');
                    return res.json();
                }).then(function (data) {
                    Object.keys(data.counts).forEach(function (key) {
                        var el = document.querySelector('[data-count="' + key + '"]');
                        if (el) el.textContent = data.counts[key];
                    });
                    flash.textContent = data.status;

                    var updated = null;
                    if (data.html) {
                        var holder = document.createElement('div');
                        holder.innerHTML = data.html.trim();
                        updated = holder.firstElementChild;
                    }

                    // Let a tribute that no longer fits the filter show its new state for a moment, then fade it away
                    if (updated && matches(updated)) {
                        tribute.replaceWith(updated);
                        return;
                    }
                    if (updated) {
                        tribute.replaceWith(updated);
                        tribute = updated;
                    }
                    tribute.classList.add('is-leaving');
                    setTimeout(function () {
                        if (updated) { applyFilter(); tribute.classList.remove('is-leaving'); } else { tribute.remove(); applyFilter(); }
                    }, 900);
                }).catch(function (err) {
                    flash.textContent = err.message;
                    tribute.querySelectorAll('button').forEach(function (b) { b.disabled = false; });
                });
            });
        })();
    </script>
@endpush
