<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title') | Admin</title>
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
        .tribute { transition: opacity .6s ease .3s; }
        .tribute.is-leaving { opacity: 0; }

        .tribute__actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px; }
        .tribute__actions form { margin: 0; }
        .tribute__actions form:last-child { margin-left: auto; }
        .action {
            font: inherit; font-size: .85rem; cursor: pointer; min-height: 36px;
            border: 1px solid var(--line); border-radius: 6px; padding: 6px 14px;
            background: transparent; color: var(--muted);
        }
        .action:hover { border-color: var(--muted); color: var(--ivory); }
        .action--primary { border-color: var(--gold); background: var(--gold); color: var(--ink); font-weight: 600; }
        .action--primary:hover { border-color: #E4C98F; background: #E4C98F; color: var(--ink); }
        .action--danger:hover { border-color: #E58C7A; color: #E58C7A; }
        .action:disabled { opacity: .5; cursor: progress; }

        .flash { min-height: 1.5em; margin: 0 0 16px; color: var(--gold); font-size: .9rem; }

        .badge { font-size: .75rem; letter-spacing: .04em; text-transform: uppercase; border-radius: 4px; padding: 2px 8px; border: 1px solid var(--line); color: var(--muted); }
        .badge--pending { border-color: var(--gold); color: var(--gold); }

        .none { color: var(--muted); border-top: 1px solid var(--line); padding-top: 24px; }

        .topbar { display: flex; justify-content: space-between; align-items: baseline; gap: 16px; }
        .link-button { font: inherit; font-size: .85rem; background: none; border: 0; padding: 0; color: var(--muted); cursor: pointer; text-decoration: underline; }
        .link-button:hover { color: var(--gold); }

        .sign-in { max-width: 360px; margin: 12vh auto 0; }
        .sign-in label { display: block; color: var(--muted); font-size: .85rem; margin-bottom: 6px; }
        .sign-in input {
            width: 100%; font: inherit; color: var(--ivory); background: var(--ink-3);
            border: 1px solid var(--line); border-radius: 6px; padding: 10px 12px;
        }
        .sign-in input:focus { outline: 2px solid var(--gold); outline-offset: 1px; }
        .sign-in button[type="submit"] {
            margin-top: 16px; width: 100%; font: inherit; font-weight: 600; cursor: pointer;
            border: 0; border-radius: 6px; padding: 10px 12px; background: var(--gold); color: var(--ink);
        }
        .error { color: #E58C7A; font-size: .9rem; margin: 8px 0 0; }
    </style>
</head>
<body>
    <main>
@yield('content')
    </main>
@stack('scripts')
</body>
</html>
