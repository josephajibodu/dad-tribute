# In Loving Memory - Samson Toyin Ajibodu (1969 - 2026)

A tribute website for Dad, built with Laravel.

## Run it

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate        # creates the tables in database/database.sqlite
php artisan serve          # or: herd link dad-tribute  ->  http://dad-tribute.test
```

No npm build is needed: the page uses plain CSS and JS from `public/`.

## Where things live

| What | Where |
| --- | --- |
| All words on the page (name, verse, life chapters, tributes, service details) | `config/tribute.php` |
| Portrait | `public/images/portrait.jpg` |
| Gallery photos | `public/images/moments/01.jpg` ... `07.jpg` (captions in config) |
| Music | `public/audio/*.mp3`, listed under `music` in config |
| Page markup | `resources/views/tribute.blade.php` |
| Styles / animations | `public/css/tribute.css` |
| Music player, reveals, candle form | `public/js/tribute.js` |

Anything still in `[square brackets]` in the config is a placeholder. A photo or song
whose file is missing is skipped or shown as a quiet placeholder, so nothing breaks
while you fill things in.

## Music

The page opens with a short welcome screen: **Enter with music** or **Enter quietly**.
Browsers block sound until the visitor clicks, so this screen is what makes the music
start reliably. The floating player (bottom left) has **Next** and **Mute**; music fades
in and out, pauses when the tab is hidden, and moves to the next song when one ends.

The playlist is Ìjọba Ọ̀run (Lara George), It Is Well With My Soul, and Amaram Onyewem
(Mercy Chinwo ft. Pastor Jerry Eze). To add or swap a song, drop the mp3 in `public/audio/` and add a line to
`music` in `config/tribute.php`. Use a recording you have permission to use (a choir,
family recording, or royalty-free version of a public-domain hymn).

## Candles and messages

Visitors can light a candle and leave a memory. Each one is saved in the `tributes`
table. Messages wait for approval before they appear on the page:

```bash
php artisan tributes:review              # read each message: approve / remove / skip
php artisan tributes:review --approve-all
```

Set `TRIBUTE_MODERATE=false` in `.env` to show messages immediately.
The form has a honeypot field and is rate-limited to 6 posts per minute per visitor.
