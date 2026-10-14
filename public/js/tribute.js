(function () {
    'use strict';

    var config = window.TRIBUTE || { tracks: [], volume: 0.55 };
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var body = document.body;

    function store(key, value) {
        try {
            if (value === undefined) return window.localStorage.getItem(key);
            window.localStorage.setItem(key, value);
        } catch (e) { /* storage blocked: fine */ }
        return null;
    }

    /* ------------------------------------------------------------------
       Music player
       ------------------------------------------------------------------ */

    var Player = (function () {
        var el = document.getElementById('player');
        var tracks = config.tracks || [];
        if (!el || !tracks.length) {
            return { start: function () {}, reveal: function () {} };
        }

        var target = Math.max(0, Math.min(1, config.volume || 0.55));
        var audio = new Audio();
        audio.preload = 'auto';
        audio.volume = 0;

        var index = 0;
        var muted = true;
        var fadeFrame = null;

        var muteBtn = el.querySelector('[data-mute]');
        var nextBtn = el.querySelector('[data-next]');
        var titleEl = el.querySelector('[data-title]');
        var statusEl = el.querySelector('[data-status]');
        var eq = el.querySelector('.eq');

        function load(i) {
            index = (i + tracks.length) % tracks.length;
            audio.src = tracks[index].src;
            titleEl.textContent = tracks[index].title;
        }

        function fadeTo(to, ms, done) {
            if (fadeFrame) cancelAnimationFrame(fadeFrame);
            var from = audio.volume;
            var startTime = null;
            function step(ts) {
                if (startTime === null) startTime = ts;
                var p = Math.min(1, (ts - startTime) / ms);
                var eased = p < 0.5 ? 2 * p * p : 1 - Math.pow(-2 * p + 2, 2) / 2;
                audio.volume = Math.max(0, Math.min(1, from + (to - from) * eased));
                if (p < 1) {
                    fadeFrame = requestAnimationFrame(step);
                } else {
                    fadeFrame = null;
                    if (done) done();
                }
            }
            fadeFrame = requestAnimationFrame(step);
        }

        function render() {
            el.classList.toggle('is-playing', !muted);
            muteBtn.classList.toggle('is-muted', muted);
            muteBtn.setAttribute('aria-pressed', muted ? 'true' : 'false');
            muteBtn.setAttribute('aria-label', muted ? 'Turn music on' : 'Mute music');
            statusEl.textContent = muted ? 'Music off' : 'Now playing';
            eq.classList.toggle('is-off', muted);
        }

        function play() {
            muted = false;
            render();
            var attempt = audio.play();
            if (attempt && attempt.catch) {
                attempt.catch(function () {
                    // Browser refused (no user gesture yet): show as off.
                    muted = true;
                    render();
                });
            }
            fadeTo(target, 2400);
            store('tribute-music', 'on');
        }

        function mute() {
            muted = true;
            render();
            fadeTo(0, 700, function () { if (muted) audio.pause(); });
            store('tribute-music', 'off');
        }

        muteBtn.addEventListener('click', function () {
            if (muted) play(); else mute();
        });

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                var wasMuted = muted;
                fadeTo(0, 500, function () {
                    load(index + 1);
                    if (!wasMuted) {
                        audio.play();
                        fadeTo(target, 1600);
                    }
                });
            });
        }

        audio.addEventListener('ended', function () {
            load(index + 1);
            if (!muted) {
                audio.volume = 0;
                audio.play();
                fadeTo(target, 2000);
            }
        });

        // Quieter when the visitor switches tabs, back up when they return.
        document.addEventListener('visibilitychange', function () {
            if (muted) return;
            if (document.hidden) fadeTo(0, 600, function () { audio.pause(); });
            else { audio.play(); fadeTo(target, 1500); }
        });

        load(0);
        render();

        return {
            start: function (withMusic) { if (withMusic) play(); },
            reveal: function () { el.classList.remove('is-hidden'); }
        };
    })();

    /* ------------------------------------------------------------------
       Entry gate
       ------------------------------------------------------------------ */

    var gate = document.getElementById('gate');

    function enter(withMusic) {
        // Remember that they've entered (and how) so we don't gate them again.
        store('tribute-entered', withMusic ? 'music' : 'quiet');
        Player.start(withMusic);
        body.classList.add('entered');
        body.classList.remove('gate-open');
        if (gate) {
            gate.classList.add('is-leaving');
            setTimeout(function () { gate.remove(); }, 1200);
        }
        setTimeout(Player.reveal, reduceMotion ? 0 : 900);
    }

    var entered = store('tribute-entered');

    if (gate && !entered) {
        // First visit: show the gate and wait for their choice.
        body.classList.add('gate-open');
        var preferred = gate.querySelector(store('tribute-music') === 'off' ? '[data-enter="quiet"]' : '[data-enter]');
        if (preferred) preferred.focus({ preventScroll: true });
        gate.querySelectorAll('[data-enter]').forEach(function (btn) {
            btn.addEventListener('click', function () { enter(btn.getAttribute('data-enter') === 'music'); });
        });
    } else {
        // Returning visitor (or no-JS candle fallback): skip the gate, honour their
        // remembered music choice. Autoplay may be blocked without a gesture, in
        // which case the player simply shows as off for them to turn on.
        if (gate) gate.remove();
        body.classList.add('entered');
        var musicPref = store('tribute-music');
        var wantMusic = musicPref ? musicPref === 'on' : entered === 'music';
        if (wantMusic) {
            // Try to resume straight away. Browsers block autoplay without a fresh
            // gesture, so also arm the music to start on the visitor's first
            // interaction (move, tap, scroll or key) — it begins the moment they do.
            Player.start(true);
            var gestures = ['pointerdown', 'keydown', 'touchstart', 'scroll'];
            var resume = function () {
                Player.start(true);
                gestures.forEach(function (evt) { window.removeEventListener(evt, resume); });
            };
            gestures.forEach(function (evt) {
                window.addEventListener(evt, resume, { passive: true });
            });
        }
        Player.reveal();
    }

    /* ------------------------------------------------------------------
       Scroll reveals
       ------------------------------------------------------------------ */

    var revealables = document.querySelectorAll('.reveal, .grow-line');
    if (reduceMotion || !('IntersectionObserver' in window)) {
        revealables.forEach(function (n) { n.classList.add('in'); });
    } else {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    io.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -12% 0px', threshold: 0.08 });
        revealables.forEach(function (n) { io.observe(n); });
    }

    /* ------------------------------------------------------------------
       Light a candle
       ------------------------------------------------------------------ */

    var form = document.querySelector('[data-candle-form]');
    var section = document.querySelector('[data-candle]');
    if (form && window.fetch) {
        var button = form.querySelector('[data-candle-button]');
        var label = button.querySelector('[data-label]');
        var messageEl = form.querySelector('[data-form-message]');
        var countEl = document.querySelector('[data-candle-count]');
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            messageEl.classList.remove('is-error');

            var name = form.elements.name.value.trim();
            if (!name) {
                messageEl.textContent = 'Please add your name so the family knows who lit this candle.';
                messageEl.classList.add('is-error');
                form.elements.name.focus();
                return;
            }

            button.disabled = true;
            label.textContent = 'Lighting...';

            fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                body: new FormData(form),
                credentials: 'same-origin'
            }).then(function (res) {
                return res.json().then(function (data) { return { ok: res.ok, status: res.status, data: data }; });
            }).then(function (r) {
                if (!r.ok) {
                    var msg = (r.data && r.data.message) || 'Something went wrong. Please try again.';
                    if (r.status === 429) msg = 'Thank you. Please wait a minute before lighting another candle.';
                    if (r.status === 419) msg = 'This page has been open a while. Please refresh and try again.';
                    throw new Error(msg);
                }
                section.classList.add('is-lit');
                label.textContent = 'Your candle is lit';
                messageEl.textContent = r.data.message;
                if (countEl && r.data.candles) {
                    countEl.textContent = r.data.candles + (r.data.candles === 1 ? ' candle' : ' candles') + ' lit in his memory.';
                }
                form.reset();
                setTimeout(function () {
                    button.disabled = false;
                    label.textContent = 'Light another candle';
                }, 4000);
            }).catch(function (err) {
                messageEl.textContent = err.message;
                messageEl.classList.add('is-error');
                button.disabled = false;
                label.textContent = 'Light a candle';
            });
        });
    }
})();
