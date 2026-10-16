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

        // Songs play in a random order, shuffled for each visit and again
        // whenever the list runs out, so each one plays before any repeats.
        var order = shuffle(-1);
        var position = 0;
        var muted = true;
        var fadeFrame = null;

        var muteBtn = el.querySelector('[data-mute]');
        var nextBtn = el.querySelector('[data-next]');
        var titleEl = el.querySelector('[data-title]');
        var statusEl = el.querySelector('[data-status]');
        var eq = el.querySelector('.eq');

        function shuffle(avoidFirst) {
            var list = tracks.map(function (track, i) { return i; });
            for (var i = list.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var swap = list[i];
                list[i] = list[j];
                list[j] = swap;
            }
            // Never start a new round with the song that just finished.
            if (list.length > 1 && list[0] === avoidFirst) list.push(list.shift());
            return list;
        }

        function load(p) {
            if (p >= order.length) {
                order = shuffle(order[order.length - 1]);
                p = 0;
            }
            position = p;
            audio.src = tracks[order[position]].src;
            titleEl.textContent = tracks[order[position]].title;
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
                    load(position + 1);
                    if (!wasMuted) {
                        audio.play();
                        fadeTo(target, 1600);
                    }
                });
            });
        }

        audio.addEventListener('ended', function () {
            load(position + 1);
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
       His life: chapters that run sideways, with a counter and arrows
       ------------------------------------------------------------------ */

    var chapterList = document.querySelector('[data-chapters]');
    var chapterNav = document.querySelector('[data-chapters-nav]');
    if (chapterList && chapterNav && chapterList.scrollWidth > chapterList.clientWidth + 4) {
        var chapters = chapterList.children;
        var currentEl = chapterNav.querySelector('[data-chapter-current]');
        var progressEl = chapterNav.querySelector('[data-chapter-progress]');
        var prevBtn = chapterNav.querySelector('[data-chapter-prev]');
        var nextBtn = chapterNav.querySelector('[data-chapter-next]');
        var touched = false;

        chapterNav.hidden = false;
        chapterNav.classList.add('is-fresh');

        var chapterStep = function () {
            return chapters[1] ? chapters[1].offsetLeft - chapters[0].offsetLeft : chapterList.clientWidth;
        };

        var update = function () {
            var max = chapterList.scrollWidth - chapterList.clientWidth;
            var index = chapterList.scrollLeft >= max - 4
                ? chapters.length - 1
                : Math.round(chapterList.scrollLeft / chapterStep());
            currentEl.textContent = (index < 9 ? '0' : '') + (index + 1);
            progressEl.style.width = ((index + 1) / chapters.length * 100) + '%';
            prevBtn.disabled = chapterList.scrollLeft <= 4;
            nextBtn.disabled = chapterList.scrollLeft >= max - 4;
        };

        // Once the visitor moves the row themselves, they've found it: stop beckoning
        var settle = function () {
            if (touched) return;
            touched = true;
            chapterNav.classList.remove('is-fresh');
            chapterList.classList.remove('is-nudging');
        };

        var scrollFrame = null;
        chapterList.addEventListener('scroll', function () {
            if (chapterList.scrollLeft > 4) settle();
            if (scrollFrame) return;
            scrollFrame = requestAnimationFrame(function () { scrollFrame = null; update(); });
        }, { passive: true });
        window.addEventListener('resize', update);

        var stepChapters = function (direction) {
            settle();
            chapterList.scrollBy({ left: direction * chapterStep(), behavior: reduceMotion ? 'auto' : 'smooth' });
        };
        prevBtn.addEventListener('click', function () { stepChapters(-1); });
        nextBtn.addEventListener('click', function () { stepChapters(1); });

        // Lean the row to the left once, the first time most of it is on screen, to show it moves
        if (!reduceMotion && 'IntersectionObserver' in window) {
            var nudgeWatch = new IntersectionObserver(function (entries) {
                if (!entries[0].isIntersecting) return;
                nudgeWatch.disconnect();
                setTimeout(function () {
                    if (!touched) chapterList.classList.add('is-nudging');
                }, 900);
            }, { threshold: 0.6 });
            nudgeWatch.observe(chapterList);
            chapterList.addEventListener('animationend', function () { chapterList.classList.remove('is-nudging'); });
        }

        update();
    }

    /* ------------------------------------------------------------------
       Visitors' messages: one row that drifts by and waits for the reader
       ------------------------------------------------------------------ */

    // Offer "Read more" on any preview the four-line limit has cut short
    function markClamped() {
        document.querySelectorAll('[data-clamp]').forEach(function (text) {
            var more = text.parentNode.querySelector('[data-reader-open]');
            if (more && more.hidden && text.offsetParent && text.scrollHeight > text.clientHeight + 2) more.hidden = false;
        });
    }
    markClamped();
    window.addEventListener('resize', markClamped);
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(markClamped);

    var messageRow = document.querySelector('[data-message-row]');
    var track = messageRow && messageRow.querySelector('[data-message-track]');
    if (track && track.scrollWidth > track.clientWidth + 4) {
        var cards = Array.prototype.slice.call(track.children);
        var controls = document.querySelector('[data-message-controls]');
        var looping = !reduceMotion;
        var speed = 26; // pixels a second: slow enough to read as it passes
        var position = 0, loopWidth = 0, lastFrame = null, holdUntil = 0;
        var hovering = false, focused = false, touching = false, onScreen = true;

        // Hold the drift for a few seconds after the visitor moves the row themselves
        var hold = function (ms) { holdUntil = Math.max(holdUntil, performance.now() + ms); };

        // A second copy follows the first so the row can loop without a gap
        if (looping) {
            cards.forEach(function (card) {
                var copy = card.cloneNode(true);
                copy.setAttribute('aria-hidden', 'true');
                copy.removeAttribute('data-message');
                copy.querySelectorAll('button').forEach(function (b) { b.tabIndex = -1; });
                track.appendChild(copy);
            });
        }

        var measure = function () { loopWidth = looping ? track.children[cards.length].offsetLeft - cards[0].offsetLeft : 0; };
        measure();
        window.addEventListener('resize', measure);

        // Jump back to the first copy once the second has fully scrolled into place,
        // or forward to the second when a step back would run past the start
        var wrap = function (backBy) {
            if (!loopWidth) return;
            if (track.scrollLeft >= loopWidth) track.scrollLeft -= loopWidth;
            else if (backBy && track.scrollLeft < backBy) track.scrollLeft += loopWidth;
            position = track.scrollLeft;
        };

        // Arrows: one card at a time, for anyone who wants to move faster than the drift
        if (controls) {
            controls.hidden = false;
            var step = function (direction) {
                hold(6000);
                var distance = cards[0].offsetWidth + (parseFloat(getComputedStyle(track).columnGap) || 0);
                wrap(direction < 0 ? distance : 0);
                track.scrollBy({ left: direction * distance, behavior: reduceMotion ? 'auto' : 'smooth' });
            };
            controls.querySelector('[data-message-prev]').addEventListener('click', function () { step(-1); });
            controls.querySelector('[data-message-next]').addEventListener('click', function () { step(1); });
        }

        // Click and drag with a mouse; touch screens and trackpads already scroll the row natively
        var drag = null;
        track.addEventListener('pointerdown', function (e) {
            if (e.pointerType !== 'mouse' || e.button !== 0) return;
            drag = { x: e.clientX, scroll: track.scrollLeft, moved: false };
        });
        window.addEventListener('pointermove', function (e) {
            if (!drag) return;
            var dx = e.clientX - drag.x;
            if (!drag.moved && Math.abs(dx) < 6) return;
            drag.moved = true;
            track.classList.add('is-dragging');
            track.scrollLeft = drag.scroll - dx;
            if (loopWidth && track.scrollLeft >= loopWidth) { drag.scroll -= loopWidth; track.scrollLeft -= loopWidth; }
            if (loopWidth && track.scrollLeft <= 0 && dx > 0) { drag.scroll += loopWidth; track.scrollLeft += loopWidth; }
            hold(4000);
        });
        window.addEventListener('pointerup', function () {
            if (!drag) return;
            if (drag.moved) {
                // A drag that ends over "Read more" shouldn't open it
                track.addEventListener('click', function swallow(e) { e.stopPropagation(); e.preventDefault(); }, { capture: true, once: true });
                setTimeout(function () { track.classList.remove('is-dragging'); }, 0);
            }
            drag = null;
        });
        track.addEventListener('dragstart', function (e) { e.preventDefault(); });

        // Wait while the visitor points at it, tabs through it, or swipes it themselves
        messageRow.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') hovering = true; });
        messageRow.addEventListener('pointerleave', function (e) { if (e.pointerType === 'mouse') hovering = false; });
        // Only keyboard focus holds it still, so a clicked "Read more" doesn't keep it paused after the reader closes
        track.addEventListener('focusin', function (e) {
            try { focused = e.target.matches(':focus-visible'); } catch (err) { focused = true; }
        });
        track.addEventListener('focusout', function () { focused = false; });
        track.addEventListener('touchstart', function () { touching = true; }, { passive: true });
        track.addEventListener('touchend', function () { touching = false; hold(3000); }, { passive: true });
        // A trackpad swipe or shift-scroll counts as the visitor taking over too
        track.addEventListener('wheel', function (e) { if (Math.abs(e.deltaX) > Math.abs(e.deltaY) || e.shiftKey) hold(3000); }, { passive: true });

        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) { onScreen = entries[0].isIntersecting; }).observe(messageRow);
        }

        if (looping) {
            var drift = function (now) {
                var seconds = lastFrame === null ? 0 : Math.min(now - lastFrame, 250) / 1000;
                lastFrame = now;
                // body.reader-open: a message is open in the reader
                var paused = hovering || focused || touching || drag || !onScreen || now < holdUntil || body.classList.contains('reader-open');
                if (paused) {
                    // Leave the row wherever the visitor put it, and carry on from there
                    position = track.scrollLeft;
                    // ...but not mid-glide, which a jump would cut short
                    if (!drag && now >= holdUntil && track.scrollLeft >= loopWidth) wrap(0);
                } else {
                    position += speed * seconds;
                    if (position >= loopWidth) position -= loopWidth;
                    track.scrollLeft = position;
                }
                window.requestAnimationFrame(drift);
            };
            window.requestAnimationFrame(drift);
        }
    }

    /* ------------------------------------------------------------------
       Tribute reader: long tributes open in full in a popup
       ------------------------------------------------------------------ */

    var reader = document.querySelector('[data-reader]');
    if (reader && typeof reader.showModal === 'function') {
        var readerBody = reader.querySelector('[data-reader-body]');
        var readerBy = reader.querySelector('[data-reader-by]');
        var readerOpener = null;

        document.querySelectorAll('[data-reader-open]').forEach(function (button) {
            button.addEventListener('click', function () {
                var tribute = button.closest('.tribute');
                readerOpener = button;
                readerBody.replaceChildren(tribute.querySelector('[data-reader-content]').content.cloneNode(true));
                readerBy.innerHTML = tribute.querySelector('.tribute__by').innerHTML;
                reader.setAttribute('aria-label', 'Tribute from ' + tribute.querySelector('.tribute__name').textContent);
                reader.showModal();
                reader.scrollTop = 0;
                body.classList.add('reader-open');
            });
        });
        reader.querySelector('[data-reader-close]').addEventListener('click', function () { reader.close(); });
        // A tap on the dark space either side of the text closes it too
        reader.addEventListener('click', function (e) {
            if (e.target === reader) reader.close();
        });
        reader.addEventListener('close', function () {
            body.classList.remove('reader-open');
            if (readerOpener) { try { readerOpener.focus({ preventScroll: true }); } catch (err) {} }
        });
    } else if (reader) {
        // Without <dialog> support the full text is out of reach, so drop the links
        document.querySelectorAll('[data-reader-open]').forEach(function (button) { button.remove(); });
    }

    /* ------------------------------------------------------------------
       Photo viewer
       ------------------------------------------------------------------ */

    var viewer = document.querySelector('[data-lightbox]');
    var photos = Array.prototype.filter.call(document.querySelectorAll('.gallery .photo'), function (fig) {
        return fig.querySelector('[data-lightbox-open]');
    });
    if (viewer && photos.length) {
        var viewerImg = viewer.querySelector('[data-lightbox-img]');
        var viewerLabel = viewer.querySelector('[data-lightbox-label]');
        var viewerCaption = viewer.querySelector('[data-lightbox-caption]');
        var viewerCount = viewer.querySelector('[data-lightbox-count]');
        var viewerClose = viewer.querySelector('[data-lightbox-close]');
        var viewerButtons = viewer.querySelectorAll('button');
        var current = 0;
        var opener = null;
        var touchX = null;
        var isOpen = function () { return viewer.classList.contains('is-visible'); };

        function showPhoto(i) {
            current = (i + photos.length) % photos.length;
            var fig = photos[current];
            var img = fig.querySelector('img');
            viewerImg.src = img.currentSrc || img.src;
            viewerImg.alt = img.alt;
            viewerLabel.textContent = fig.querySelector('.photo__label').textContent;
            viewerCaption.textContent = fig.querySelector('.photo__caption').textContent;
            viewerCount.textContent = (current + 1) + ' / ' + photos.length;
        }
        function openViewer(i) {
            opener = document.activeElement;
            showPhoto(i);
            viewer.classList.add('is-visible');
            viewer.setAttribute('aria-hidden', 'false');
            body.classList.add('lightbox-open');
            viewerClose.focus();
        }
        function closeViewer() {
            if (!isOpen()) return;
            viewer.classList.remove('is-visible');
            viewer.setAttribute('aria-hidden', 'true');
            body.classList.remove('lightbox-open');
            if (opener) { try { opener.focus(); } catch (err) {} }
        }

        photos.forEach(function (fig, i) {
            fig.querySelector('[data-lightbox-open]').addEventListener('click', function () { openViewer(i); });
        });
        viewerClose.addEventListener('click', closeViewer);
        viewer.querySelector('[data-lightbox-prev]').addEventListener('click', function () { showPhoto(current - 1); });
        viewer.querySelector('[data-lightbox-next]').addEventListener('click', function () { showPhoto(current + 1); });
        viewer.addEventListener('click', function (e) { if (e.target === viewer) closeViewer(); });

        document.addEventListener('keydown', function (e) {
            if (!isOpen()) return;
            if (e.key === 'Escape') closeViewer();
            else if (e.key === 'ArrowLeft') showPhoto(current - 1);
            else if (e.key === 'ArrowRight') showPhoto(current + 1);
            else if (e.key === 'Tab') {
                // Keep keyboard focus on the viewer's own buttons while it is open
                var first = viewerButtons[0];
                var last = viewerButtons[viewerButtons.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            }
        });

        // Swipe left or right to move between photos on touch screens
        viewer.addEventListener('touchstart', function (e) { touchX = e.touches[0].clientX; }, { passive: true });
        viewer.addEventListener('touchend', function (e) {
            if (touchX === null) return;
            var dx = e.changedTouches[0].clientX - touchX;
            touchX = null;
            if (Math.abs(dx) > 50) showPhoto(current + (dx < 0 ? 1 : -1));
        });
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

        /* The "moment" overlay greets small screens, where the candle can't sit beside the form */
        var moment = document.querySelector('[data-candle-moment]');
        var momentCount = moment && moment.querySelector('[data-moment-count]');
        var momentNote = moment && moment.querySelector('[data-moment-note]');
        var momentClose = moment && moment.querySelector('[data-moment-close]');
        var narrow = function () { return window.matchMedia('(max-width: 899px)').matches; };
        var momentTimer = null;

        function showMoment(note, countText) {
            if (!moment) return;
            if (momentCount) momentCount.textContent = countText || '';
            if (momentNote) momentNote.textContent = note || '';
            clearTimeout(momentTimer);
            moment.classList.remove('is-burning');
            moment.classList.add('is-visible');
            moment.setAttribute('aria-hidden', 'false');
            document.body.classList.add('moment-open');
            if (momentClose) { try { momentClose.focus(); } catch (err) {} }
            // Let the overlay fade in around an unlit candle, then light it so the visitor sees it catch
            momentTimer = setTimeout(function () { moment.classList.add('is-burning'); }, reduceMotion ? 0 : 1000);
        }
        function hideMoment() {
            if (!moment) return;
            clearTimeout(momentTimer);
            moment.classList.remove('is-visible');
            moment.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('moment-open');
            // Put the flame out only after the overlay has faded, ready to light again next time
            momentTimer = setTimeout(function () { moment.classList.remove('is-burning'); }, 1000);
        }
        if (momentClose) momentClose.addEventListener('click', hideMoment);
        if (moment) moment.addEventListener('click', function (e) { if (e.target === moment) hideMoment(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') hideMoment(); });

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
                var countText = '';
                if (r.data.candles) {
                    countText = r.data.candles + (r.data.candles === 1 ? ' candle' : ' candles') + ' lit in his memory.';
                    if (countEl) countEl.textContent = countText;
                }
                if (narrow()) showMoment(r.data.message, countText);
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
