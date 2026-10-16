@php
    $fullName = $t['name']['first'].' '.$t['name']['middle'].' '.$t['name']['last'];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $fullName }} ({{ $t['born'] }} - {{ $t['died'] }}) | In Loving Memory</title>
    <meta name="description" content="{{ $t['summary'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('tribute') }}">
    <meta property="og:title" content="In Loving Memory of {{ $fullName }} ({{ $t['born'] }} – {{ $t['died'] }})">
    <meta property="og:description" content="{{ $t['summary'] }}">
    @if ($shareImage)
        <meta property="og:image" content="{{ $shareImage['url'] }}">
        <meta property="og:image:width" content="{{ $shareImage['width'] }}">
        <meta property="og:image:height" content="{{ $shareImage['height'] }}">
        <meta property="og:image:alt" content="{{ $fullName }}, {{ $t['born'] }} – {{ $t['died'] }}. In loving memory.">
        <meta name="twitter:card" content="summary_large_image">
    @else
        <meta name="twitter:card" content="summary">
    @endif
    <meta name="theme-color" content="#0D0C0A">
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}" sizes="16x16 32x32 48x48">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v={{ filemtime(public_path('apple-touch-icon.png')) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Hanken+Grotesk:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="{{ asset('css/tribute.css') }}?v={{ filemtime(public_path('css/tribute.css')) }}">
    {{-- If this visitor has entered before, skip the gate before it ever paints. --}}
    <script>
        try {
            if (localStorage.getItem('tribute-entered')) {
                document.documentElement.classList.add('has-entered');
            }
        } catch (e) { /* storage blocked: the gate shows as normal */ }
    </script>
</head>
<body class="{{ session('lit') ? 'entered no-gate' : '' }}">

    {{-- ENTRY GATE: a click here is what lets the browser start the music --}}
    @unless (session('lit'))
        <div class="gate" id="gate" role="dialog" aria-modal="true" aria-labelledby="gate-name">
            <div class="glow" aria-hidden="true"></div>
            <div class="candle candle--small" aria-hidden="true">
                <div class="flame"></div>
                <div class="wick"></div>
                <div class="wax"></div>
            </div>
            <p class="eyebrow">In Loving Memory</p>
            <p class="gate__name" id="gate-name">{{ $fullName }}</p>
            @if ($tracks->isNotEmpty())
                <p class="gate__note">This page plays gentle music. You can turn it off at any time.</p>
                <div class="gate__actions">
                    <button type="button" class="btn btn--solid" data-enter="music">Enter with music</button>
                    <button type="button" class="btn btn--ghost" data-enter="quiet">Enter quietly</button>
                </div>
            @else
                <div class="gate__actions">
                    <button type="button" class="btn btn--solid" data-enter="quiet">Enter</button>
                </div>
            @endif
        </div>
    @endunless

    <div class="page" id="top">

        <header class="site-header">
            <a href="#top" class="monogram" aria-label="Back to top">{{ mb_substr($t['name']['first'], 0, 1) }} · {{ mb_substr($t['name']['middle'], 0, 1) }} · {{ mb_substr($t['name']['last'], 0, 1) }}</a>
            <nav aria-label="Sections" class="site-nav">
                <a href="#life">His Life</a>
                <a href="#who">Who He Was</a>
                <a href="#moments">Moments</a>
                <a href="#tributes">Tributes</a>
                <a href="#service">Service</a>
                <a href="#candle" class="is-accent">Light a Candle</a>
            </nav>
        </header>

        {{-- HERO --}}
        <section class="hero">
            <div class="glow" aria-hidden="true"></div>
            <p class="eyebrow fade" style="--d:.2s">In Loving Memory</p>

            <div class="portrait fade" style="--d:.5s">
                @if ($portrait)
                    <img src="{{ $portrait }}" alt="Portrait of {{ $fullName }}">
                @else
                    <span class="placeholder-label">[Dad's portrait]</span>
                @endif
            </div>

            @if (! empty($t['honorific']))
                <p class="hero__honorific fade" style="--d:.75s">{{ $t['honorific'] }}</p>
            @endif
            <h1 class="hero__name">
                <span class="line"><span class="hw" style="--d:.9s">{{ $t['name']['first'] }}</span></span>
                <span class="line"><span class="hw is-accent is-italic" style="--d:1.15s">{{ $t['name']['middle'] }}</span></span>
                <span class="line"><span class="hw" style="--d:1.4s">{{ $t['name']['last'] }}</span></span>
            </h1>

            <div class="hero__dates fade" style="--d:2s">
                <span>{{ $t['born'] }}</span>
                <span class="rule" aria-hidden="true"></span>
                <span>{{ $t['died'] }}</span>
            </div>
            <p class="hero__roles fade" style="--d:2.2s">{{ implode(' · ', $t['roles']) }}</p>

            <div class="scroll-cue" aria-hidden="true"><div></div></div>
        </section>

        {{-- SCRIPTURE --}}
        <section class="scripture">
            <p class="eyebrow reveal">{{ $t['scripture']['reference'] }}</p>
            <blockquote class="reveal">
                {{ $t['scripture']['text'] }} <em>{{ $t['scripture']['emphasis'] }}</em>
            </blockquote>
        </section>

        {{-- MARQUEE --}}
        <div class="marquee" aria-hidden="true">
            <div class="marquee__track">
                @for ($i = 0; $i < 2; $i++)
                    @foreach ($t['marquee'] as $word)
                        <span>{{ $word }}</span><span class="star">✦</span>
                    @endforeach
                @endfor
            </div>
        </div>

        {{-- HIS LIFE --}}
        <section id="life" class="life">
            <div class="container split">
                <div class="reveal">
                    <p class="eyebrow">01 · His Life</p>
                    <h2 class="display">{{ $t['life']['heading'] }} <em>{{ $t['life']['heading_emphasis'] }}</em></h2>
                </div>
                <p class="lead reveal">{{ $t['life']['intro'] }}</p>
            </div>
            <div class="container"><div class="grow-line" aria-hidden="true"></div></div>

            <ol class="chapters" tabindex="0" aria-label="Chapters of his life">
                @foreach ($t['life']['chapters'] as $chapter)
                    <li class="chapter reveal" style="--d:{{ $loop->index * 0.08 }}s">
                        <span class="chapter__year">{{ $chapter['year'] }}</span>
                        <h3>{{ $chapter['title'] }}</h3>
                        <p>{{ $chapter['body'] }}</p>
                    </li>
                @endforeach
            </ol>
            <p class="hint container">Scroll sideways through the chapters →</p>
        </section>

        {{-- WHO HE WAS --}}
        <section id="who" class="who">
            <div class="container">
                <div class="section-head reveal">
                    <div>
                        <p class="eyebrow">02 · Who He Was</p>
                        <h2 class="display">Kind to <em>everyone.</em></h2>
                    </div>
                </div>
                <div class="traits">
                    @foreach ($t['traits'] ?? [] as $trait)
                        <article class="trait reveal" style="--d:{{ $loop->index * 0.1 }}s">
                            <span class="trait__num" aria-hidden="true">{{ sprintf('%02d', $loop->iteration) }}</span>
                            <h3>{{ $trait['title'] }}</h3>
                            <p>{{ $trait['body'] }}</p>
                        </article>
                    @endforeach
                </div>
                @if (! empty($t['who_quote']))
                    <figure class="who-quote reveal">
                        <blockquote>“{{ $t['who_quote']['text'] }}”</blockquote>
                        <figcaption>
                            <span class="tribute__name">{{ $t['who_quote']['name'] }}</span>
                            <span class="tribute__relation">{{ $t['who_quote']['relation'] }}</span>
                        </figcaption>
                    </figure>
                @endif
            </div>
        </section>

        {{-- MOMENTS --}}
        <section id="moments" class="moments">
            <div class="container">
                <div class="section-head reveal">
                    <div>
                        <p class="eyebrow">03 · Moments</p>
                        <h2 class="display">Kept in <em>light.</em></h2>
                    </div>
                    <p class="muted">Photographs from across the years.</p>
                </div>
                <div class="gallery">
                    @foreach ($moments as $m)
                        <figure class="photo reveal {{ ! empty($m['tall']) ? 'photo--tall' : '' }}" style="--d:{{ ($loop->index % 3) * 0.08 }}s">
                            @if ($m['src'])
                                <img src="{{ $m['src'] }}" alt="{{ $m['caption'] }}" loading="lazy" decoding="async">
                                <button type="button" class="photo__open" data-lightbox-open aria-label="View photo: {{ $m['caption'] }}"></button>
                            @endif
                            <figcaption>
                                <span class="photo__label">{{ $m['label'] }}</span>
                                <span class="photo__caption">{{ $m['caption'] }}</span>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- TRIBUTES --}}
        <section id="tributes" class="tributes">
            <div class="container">
                <div class="reveal section-head">
                    <div>
                        <p class="eyebrow">04 · Tributes</p>
                        <h2 class="display">Words from <em>those he loved.</em></h2>
                    </div>
                </div>
                <div class="tribute-grid">
                    @foreach ($familyTributes as $tr)
                        <article class="tribute reveal">
                            <span class="quote-mark" aria-hidden="true">“</span>
                            <p class="tribute__text">{{ $tr['excerpt'] ?? $tr['text'] }}</p>
                            @if ($tr['full'])
                                <button type="button" class="tribute__more" data-reader-open>Read the full tribute</button>
                                <template data-reader-content>
                                    @if (! empty($tr['title']))
                                        <h3 class="reader__title">{{ $tr['title'] }}</h3>
                                    @endif
                                    <div class="reader__text">{!! $tr['full'] !!}</div>
                                </template>
                            @endif
                            <div class="tribute__by">
                                <span class="tribute__name">{{ $tr['name'] }}</span>
                                <span class="tribute__relation">{{ $tr['relation'] }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- SERVICE --}}
        <section id="service" class="service">
            <div class="container split">
                <div class="reveal">
                    <p class="eyebrow eyebrow--dark">05 · Laid to Rest</p>
                    <h2 class="display">Called home on <em>{{ $t['died_on'] }}.</em></h2>
                </div>
                <dl class="service__details reveal">
                    <div>
                        <dt>Burial</dt>
                        <dd>{{ $t['service']['burial_day'] }}<br>{{ $t['service']['burial_date'] }}</dd>
                    </div>
                    <div>
                        <dt>Time</dt>
                        <dd>{{ $t['service']['time'] }}</dd>
                    </div>
                    <div>
                        <dt>Funeral service</dt>
                        <dd>{{ $t['service']['venue'] }}<span class="service__area">{{ $t['service']['venue_area'] }}</span></dd>
                    </div>
                    @if (! empty($t['service']['interment']))
                        <div>
                            <dt>Interment</dt>
                            <dd>{{ $t['service']['interment'] }}<span class="service__area">{{ $t['service']['interment_area'] }}</span></dd>
                        </div>
                    @endif
                    @if ($t['service']['livestream_url'])
                        <div>
                            <dt>Livestream</dt>
                            <dd><a class="pill-link" href="{{ $t['service']['livestream_url'] }}" target="_blank" rel="noopener">Watch the service</a></dd>
                        </div>
                    @endif
                </dl>
            </div>
        </section>

        {{-- LIGHT A CANDLE --}}
        <section id="candle" class="candle-section {{ session('lit') ? 'is-lit' : '' }}" data-candle>
            <div class="candle-stage reveal">
                <div class="glow glow--candle" aria-hidden="true"></div>
                <div class="candle" aria-hidden="true">
                    <div class="flame"></div>
                    <div class="wick"></div>
                    <div class="wax"></div>
                </div>
                <p class="muted candle-count" data-candle-count aria-live="polite">
                    @if ($candleCount > 0)
                        {{ number_format($candleCount) }} {{ Str::plural('candle', $candleCount) }} lit in his memory.
                    @else
                        Every candle lit here stays burning in his memory.
                    @endif
                </p>
            </div>

            <div class="candle-panel">
                <div class="reveal">
                    <p class="eyebrow">06 · Remember Him</p>
                    <h2 class="display">Light a candle, <em>leave a word.</em></h2>
                    <p class="candle-lead muted">Fill in your name, share a memory if you have one, and watch his candle glow beside you.</p>
                    @if ($visitorTributes->isNotEmpty())
                        <a href="#messages" class="candle-read">Read what others have written</a>
                    @endif
                </div>

                <form class="candle-form reveal" method="POST" action="{{ route('tributes.store') }}" data-candle-form novalidate>
                @csrf
                <label class="field">
                    <span>Your name</span>
                    <input type="text" name="name" maxlength="80" required autocomplete="name" value="{{ old('name') }}" placeholder="e.g. Adebayo">
                </label>
                <label class="field">
                    <span>How you knew him</span>
                    <input type="text" name="relation" maxlength="120" value="{{ old('relation') }}" placeholder="Church member, friend, family...">
                </label>
                <label class="field field--wide">
                    <span>A memory or a prayer <em>(optional)</em></span>
                    <textarea name="message" rows="4" maxlength="2000" placeholder="Share something you remember about him">{{ old('message') }}</textarea>
                </label>
                <div class="hp" aria-hidden="true">
                    <label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                <p class="form-message" data-form-message role="status">
                    @if (session('lit'))
                        {{ session('lit') }}
                    @elseif ($errors->any())
                        {{ $errors->first() }}
                    @endif
                </p>

                <div class="form-actions">
                    <button type="submit" class="btn btn--candle" data-candle-button>
                        <span data-label>{{ session('lit') ? 'Your candle is lit' : 'Light a candle' }}</span>
                    </button>
                </div>
                </form>
            </div>
        </section>

        {{-- MESSAGES LEFT BY VISITORS (just after the form where they are written) --}}
        @if ($visitorTributes->isNotEmpty())
            <section id="messages" class="messages" data-messages>
                <div class="container">
                    <div class="section-head reveal">
                        <div>
                            <p class="eyebrow">From friends &amp; visitors</p>
                            <h2 class="messages__title">Words left <em>by candlelight.</em></h2>
                        </div>
                        <p class="muted">{{ $visitorTributes->count() === 1 ? 'A message' : number_format($visitorTributes->count()).' messages' }} from those who lit a candle in his memory.</p>
                    </div>
                    <div class="tribute-grid">
                        @foreach ($visitorTributes as $tr)
                            @php($excerpt = Str::words($tr->message, 40, '…'))
                            <article class="tribute tribute--visitor reveal" data-message>
                                <span class="quote-mark" aria-hidden="true">“</span>
                                <p class="tribute__text" data-clamp>{{ $excerpt }}</p>
                                {{-- Shown when the words were cut short here, or when the page cuts the preview to four lines --}}
                                <button type="button" class="tribute__more" data-reader-open @if ($excerpt === $tr->message) hidden @endif>Read more</button>
                                <template data-reader-content>
                                    <p class="reader__text reader__text--plain">{{ $tr->message }}</p>
                                </template>
                                <div class="tribute__by">
                                    <span class="tribute__name">{{ $tr->name }}</span>
                                    @if ($tr->relation)
                                        <span class="tribute__relation">{{ $tr->relation }}</span>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="messages__more">
                        <button type="button" class="btn btn--ghost" data-messages-more hidden>Read more messages</button>
                    </div>
                </div>
            </section>
        @endif

        {{-- The lighting moment (shown on small screens, where the candle can't sit beside the form) --}}
        <div class="candle-moment" data-candle-moment aria-hidden="true" role="dialog" aria-label="Your candle">
            <div class="candle-moment__inner">
                <div class="glow glow--moment" aria-hidden="true"></div>
                <div class="candle candle--moment" aria-hidden="true">
                    <div class="flame"></div>
                    <div class="wick"></div>
                    <div class="wax"></div>
                </div>
                <p class="candle-moment__count" data-moment-count></p>
                <p class="candle-moment__note" data-moment-note></p>
                <button type="button" class="btn btn--candle" data-moment-close>
                    <span>Light another candle</span>
                </button>
            </div>
        </div>

        <footer class="site-footer">
            <p class="display display--small">Forever in <em>our hearts.</em></p>
            <p class="footer-note">{{ $t['footer'] }} · {{ $t['died'] }}</p>
        </footer>
    </div>

    {{-- TRIBUTE READER (opens when "Read the full tribute" is tapped) --}}
    <dialog class="reader" data-reader aria-label="Tribute">
        <button type="button" class="icon-btn reader__close" data-reader-close aria-label="Close tribute">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12"/><path d="M18 6L6 18"/></svg>
        </button>
        <div class="reader__inner">
            <span class="quote-mark" aria-hidden="true">“</span>
            <div data-reader-body></div>
            <div class="tribute__by" data-reader-by></div>
        </div>
    </dialog>

    {{-- PHOTO VIEWER (opens when a gallery photo is tapped) --}}
    @if ($moments->whereNotNull('src')->isNotEmpty())
        <div class="lightbox" data-lightbox aria-hidden="true" role="dialog" aria-modal="true" aria-label="Photo viewer">
            <button type="button" class="icon-btn lightbox__close" data-lightbox-close aria-label="Close photo">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12"/><path d="M18 6L6 18"/></svg>
            </button>
            <figure class="lightbox__figure">
                <img data-lightbox-img src="data:," alt="">
                <figcaption>
                    <span class="photo__label" data-lightbox-label></span>
                    <span class="photo__caption" data-lightbox-caption></span>
                </figcaption>
            </figure>
            <div class="lightbox__nav">
                <button type="button" class="icon-btn" data-lightbox-prev aria-label="Previous photo">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg>
                </button>
                <span class="lightbox__count" data-lightbox-count></span>
                <button type="button" class="icon-btn" data-lightbox-next aria-label="Next photo">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    @endif

    {{-- MUSIC PLAYER --}}
    @if ($tracks->isNotEmpty())
        <div class="player is-hidden" id="player" role="region" aria-label="Music player">
            <div class="eq is-off" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
            <div class="player__meta">
                <span class="player__status" data-status>Music off</span>
                <span class="player__title" data-title>{{ $tracks[0]['title'] }}</span>
            </div>
            @if ($tracks->count() > 1)
                <button type="button" class="icon-btn" data-next aria-label="Next song">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 5l10 7-10 7z"/><path d="M19 5v14"/></svg>
                </button>
            @endif
            <button type="button" class="icon-btn icon-btn--mute is-muted" data-mute aria-pressed="true" aria-label="Turn music on">
                <svg class="icon-on" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 9v6h4l5 4V5L8 9z"/><path d="M16.5 8.5a5 5 0 010 7"/><path d="M19.5 5.5a9 9 0 010 13"/></svg>
                <svg class="icon-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 9v6h4l5 4V5L8 9z"/><path d="M17 9l5 6"/><path d="M22 9l-5 6"/></svg>
            </button>
        </div>
    @endif

    <script>
        window.TRIBUTE = {
            tracks: @json($tracks),
            volume: {{ (float) $t['music_volume'] }},
        };
    </script>
    <script src="{{ asset('js/tribute.js') }}?v={{ filemtime(public_path('js/tribute.js')) }}" defer></script>
</body>
</html>
