<?php

/*
|--------------------------------------------------------------------------
| Tribute content
|--------------------------------------------------------------------------
|
| Everything the tribute page shows lives here, so the family's words can
| be updated without touching the views. Anything in [square brackets] is
| a placeholder still waiting for the real detail.
|
| Images and audio paths are relative to the public/ folder. If a file is
| missing, the page shows a quiet placeholder instead of a broken image.
|
*/

return [

    'name' => [
        'first' => 'Samson',
        'middle' => 'Toyin',
        'last' => 'Ajibodu',
    ],

    // Shown in small capitals above his name, the way he was formally addressed.
    'honorific' => 'Comrade · Evangelist · JP',

    'born' => '1969',
    'died' => '2026',
    'died_on' => '10 October 2026',
    'roles' => ['Father', 'Town Planner', 'Cooperative Leader', 'Friend to all'],

    'portrait' => 'images/portrait.jpg',

    // What people see when the link is shared (WhatsApp, Facebook, X) and in search results.
    // The card is 1200 x 630; if it is missing, the portrait is shared instead.
    'share_image' => 'images/share-card.jpg',
    'summary' => 'A devoted father, evangelist, town planner and cooperative leader who lived for others. Read his story, share a memory and light a candle in his honour.',

    'scripture' => [
        'reference' => '2 Timothy 4 : 7',
        'text' => 'I have fought a good fight, I have finished my course,',
        'emphasis' => 'I have kept the faith.',
    ],

    'marquee' => ['Devoted father', 'Diligent in his work', 'Faithful servant of God', 'Teacher of his children', 'Builder of community', 'Kind to the young and the old', 'Forever loved'],

    'life' => [
        'heading' => 'A life poured out',
        'heading_emphasis' => 'for others.',
        'intro' => "Daddy lived for other people. He would meet someone else's need before his own, and sometimes even before ours. It was never that he loved his family less. He believed that the good he did for others was ground being prepared for his children, and that kindness sown into people would one day find its way back to them.",
        'chapters' => [
            ['year' => '1969', 'title' => 'Born in Iree', 'body' => 'Born on 19 September 1969 into the Ajibodu family of Iree, in Boripe Local Government Area of Osun State.'],
            ['year' => '1974', 'title' => 'A hunger to learn', 'body' => "From C.A.C. Primary School, Ayepe-Olode, to St Peter's R.C.M. Grammar School, Olode, and then The Polytechnic, Ibadan, where he studied Quantity Surveying."],
            ['year' => '1991', 'title' => 'Public service', 'body' => 'He joined the Osun State Local Government Service at Ife South, Ifetedo, as an Assistant Town Planning Officer. Two years later he was named Best Worker of the Year.'],
            ['year' => '2011', 'title' => 'Lifting others up', 'body' => 'He led the NULGE Cooperative at Ife South, about 580 workers strong. Together they gave loans to members, built a hall at Ifetedo, a guest inn at Olode and residential quarters, and were named Best Cooperative of the Year in 2009. In 2011 he became State President of the Osun State federation.'],
            ['year' => '2012', 'title' => 'Building community', 'body' => 'Beyond the office, he served as General Secretary of the Ife South Community Development Council and Project Coordinator for the Osun State council, and gave his time, money and skill to community associations in Ifetedo.'],
            ['year' => '2019', 'title' => 'Never done growing', 'body' => 'While working, he kept studying: an HND in Quantity Surveying, then an HND and a Postgraduate Diploma in Urban and Regional Planning. He became a registered Town Planner in 2019.'],
            ['year' => 'Home', 'title' => 'Our first teacher', 'body' => 'He taught his own children mathematics and physics at home, and wanted every one of us to go as far in education as we possibly could.'],
            ['year' => 'Faith', 'title' => 'Faith and service', 'body' => "Ordained a lay reader and preacher in 1996 and an Evangelist in 2005, he served The African Church as Gbesinro of St Michael's, Ifetedo, and church warden at St John's, Olode-Ife. He also made the pilgrimage to Jerusalem, earning the title JP."],
            ['year' => '2026', 'title' => 'Called home', 'body' => 'He retired as Head of the Town Planning Division on 15 August 2026, after 35 years of service. Less than two months later, after a brief illness, he went to be with the Lord on Saturday, 10 October 2026, aged 57.'],
        ],
    ],

    // "Who he was": a few things about him, in the family's words.
    'traits' => [
        ['title' => 'A people\'s person', 'body' => 'He was kind to everyone around him: the young and the old, friends, and even those who were not. People came to him for help, and he rarely sent anyone away.'],
        ['title' => 'Diligent at work', 'body' => 'For 35 years he gave his best to public service and to the cooperative movement, and he was honoured for both. At Ife South his colleagues simply called him "Aare".'],
        ['title' => 'A teacher at heart', 'body' => 'He was brilliant, and he loved to pass it on. Many evenings were spent teaching his children, because he believed education was the best gift he could give us.'],
        ['title' => 'The one who held us together', 'body' => 'Though he was the youngest, he became the one the wider family gathered around, the person who kept everyone connected.'],
    ],

    // Shown beneath the traits, from the condolence register.
    'who_quote' => [
        'text' => 'The smile of a person who comes to him for help is always his joy.',
        'name' => 'Chief Akin Ajiboso',
        'relation' => 'Balogun Atayese of Ifetedoland',
    ],

    // 'tall' => true makes a photo take two rows in the gallery.
    // Tall photos are cropped 5:8 and the others 4:3, so each fills its tile.
    'moments' => [
        ['image' => 'images/moments/01.jpg', 'label' => 'Early years', 'caption' => 'Young and full of promise', 'tall' => true],
        ['image' => 'images/moments/02.jpg', 'label' => 'Youth service', 'caption' => 'Serving the nation in Owerri', 'tall' => true],
        ['image' => 'images/moments/03.jpg', 'label' => 'Youth service', 'caption' => 'A corps member'],
        ['image' => 'images/moments/04.jpg', 'label' => 'At work', 'caption' => 'At his desk, pen in hand', 'tall' => true],
        ['image' => 'images/moments/05.jpg', 'label' => 'Family', 'caption' => 'A birthday at home'],
        ['image' => 'images/moments/06.jpg', 'label' => 'The African Church', 'caption' => 'Called to serve', 'tall' => true],
        ['image' => 'images/moments/07.jpg', 'label' => 'The African Church', 'caption' => 'Blessed for the work', 'tall' => true],
        ['image' => 'images/moments/08.jpg', 'label' => 'Holy Land', 'caption' => 'On pilgrimage', 'tall' => true],
        ['image' => 'images/moments/09.jpg', 'label' => 'Jerusalem', 'caption' => 'A pilgrim in the holy city', 'tall' => true],
        ['image' => 'images/moments/10.jpg', 'label' => 'Holy Land', 'caption' => 'By the water', 'tall' => true],
        ['image' => 'images/moments/11.jpg', 'label' => 'NULGE', 'caption' => 'Speaking to his members', 'tall' => true],
        ['image' => 'images/moments/12.jpg', 'label' => 'NULGE Cooperative', 'caption' => 'Celebrating with the members', 'tall' => true],
        ['image' => 'images/moments/13.jpg', 'label' => 'NULGE Cooperative', 'caption' => 'A quiet moment', 'tall' => true],
        ['image' => 'images/moments/14.jpg', 'label' => 'Later years', 'caption' => 'Ever the gentleman', 'tall' => true],
        ['image' => 'images/moments/15.jpg', 'label' => '2025', 'caption' => "Witnessing his son's wedding"],
        ['image' => 'images/moments/17.jpg', 'label' => '2025', 'caption' => 'Father of the groom', 'tall' => true],
        ['image' => 'images/moments/18.jpg', 'label' => '2025', 'caption' => 'Greeting the elders', 'tall' => true],
        // Listed last so it fills the gap the tall photos above leave, keeping the grid's bottom edge even
        ['image' => 'images/moments/16.jpg', 'label' => '2025', 'caption' => 'A proud father'],
    ],

    // Tributes written by the family. Visitors' messages (once approved)
    // appear after these.
    'family_tributes' => [
        ['text' => '[Tribute from Joseph]', 'name' => 'Joseph Ajibodu', 'relation' => 'First son'],
        ['text' => '[Tribute from his wife]', 'name' => '[Name]', 'relation' => 'Wife'],
        ['text' => '[Tribute from Larry]', 'name' => 'Larry Ajibodu', 'relation' => 'Son'],
        ['text' => '[Tribute from a church member or friend]', 'name' => '[Name]', 'relation' => '[Relationship]'],
    ],

    'service' => [
        'burial_day' => 'Saturday',
        'burial_date' => '17 October 2026',
        'time' => '10:00 AM',
        // The funeral service, then the interment
        'venue' => 'Ebenezer African Church',
        'venue_area' => 'Odo Oke, Iree, Osun State',
        'interment' => 'His residence',
        'interment_area' => 'Oja Bale, Igaa One, Off Campus, Iree, Osun State',
        'livestream_url' => null, // e.g. 'https://youtube.com/live/...'
    ],

    /*
    | Music. Files live in public/audio and play in a random order, shuffled
    | afresh for each visit. Tracks whose file is missing are skipped
    | automatically.
    */
    'music' => [
        ['title' => 'It Is Well With My Soul', 'note' => 'Hymn', 'file' => 'audio/it-is-well.mp3'],
        ['title' => 'Amaram Onyewem', 'note' => 'Mercy Chinwo ft. Pastor Jerry Eze', 'file' => 'audio/amaram-onyewem.mp3'],
        ['title' => "Ìjọba Ọ̀run (Mu Mi De'Le)", 'note' => 'Lara George', 'file' => 'audio/ijoba-orun.mp3'],
        ['title' => 'Gone Too Soon', 'note' => 'Michael Jackson', 'file' => 'audio/gone-too-soon.mp3'],
        ['title' => 'Logan Ti Ode', 'note' => 'Tope Alabi', 'file' => 'audio/logan-ti-ode.mp3'],
    ],
    'music_volume' => 0.55,

    // When true, visitors' messages wait for approval
    // (php artisan tributes:review) before showing on the page.
    'moderate' => env('TRIBUTE_MODERATE', true),

    'footer' => 'With love, from his family',
];
