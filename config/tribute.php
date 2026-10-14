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

    'born' => '1969',
    'died' => '2026',
    'died_on' => '10 October 2026',
    'roles' => ['Father', 'Town Planner', 'Cooperative Leader', 'Friend to all'],

    'portrait' => 'images/portrait.jpg',

    'scripture' => [
        'reference' => '2 Timothy 4 : 7',
        'text' => 'I have fought a good fight, I have finished my course,',
        'emphasis' => 'I have kept the faith.',
    ],

    'marquee' => ['Devoted father', 'Diligent in his work', 'Teacher of his children', 'Kind to the young and the old', 'Forever loved'],

    'life' => [
        'heading' => 'A life poured out',
        'heading_emphasis' => 'for others.',
        'intro' => "Daddy lived for other people. He would meet someone else's need before his own, and sometimes even before ours. It was never that he loved his family less. He believed that the good he did for others was ground being prepared for his children, and that kindness sown into people would one day find its way back to them.",
        'chapters' => [
            ['year' => '1969', 'title' => 'Born in Iree', 'body' => 'Born on 19 September 1969 into the Ajibodu family of Iree, in Boripe Local Government Area of Osun State.'],
            ['year' => '1974', 'title' => 'A hunger to learn', 'body' => "From C.A.C. Primary School, Ayepe-Olode, to St Peter's R.C.M. Grammar School, Olode, and then The Polytechnic, Ibadan, where he studied Quantity Surveying."],
            ['year' => '1991', 'title' => 'Public service', 'body' => 'He joined the Osun State Local Government Service at Ife South, Ifetedo, as an Assistant Town Planning Officer. Two years later he was named Best Worker of the Year.'],
            ['year' => '2011', 'title' => 'Lifting others up', 'body' => 'As President of the NULGE Cooperative Society, Ife South, and later State President of the Osun State NULGE Cooperative Federation, he helped hundreds of workers save, borrow and build. His leadership was recognised with state awards.'],
            ['year' => '2019', 'title' => 'Never done growing', 'body' => 'While working, he kept studying: an HND in Quantity Surveying, then an HND and a Postgraduate Diploma in Urban and Regional Planning. He became a registered Town Planner in 2019.'],
            ['year' => 'Home', 'title' => 'Our first teacher', 'body' => 'He taught his own children mathematics and physics at home, and wanted every one of us to go as far in education as we possibly could.'],
            ['year' => 'Faith', 'title' => 'Service in the church', 'body' => 'A man of quiet faith, he served The African Church for many years as a lay reader, preacher and church warden.'],
            ['year' => '2026', 'title' => 'Called home', 'body' => 'He retired as Head of the Town Planning Division in August 2026 after 35 years of service, and went to be with the Lord on Saturday, 10 October 2026.'],
        ],
    ],

    // "Who he was": a few things about him, in the family's words.
    'traits' => [
        ['title' => 'A people\'s person', 'body' => 'He was kind to everyone around him: the young and the old, friends, and even those who were not. People came to him for help, and he rarely sent anyone away.'],
        ['title' => 'Diligent at work', 'body' => 'For 35 years he gave his best to public service and to the cooperative movement, and he was honoured for both.'],
        ['title' => 'A teacher at heart', 'body' => 'He was brilliant, and he loved to pass it on. Many evenings were spent teaching his children, because he believed education was the best gift he could give us.'],
        ['title' => 'The one who held us together', 'body' => 'Though he was the youngest, he became the one the wider family gathered around, the person who kept everyone connected.'],
    ],

    // 'tall' => true makes a photo take two rows in the gallery.
    'moments' => [
        ['image' => 'images/moments/01.jpg', 'label' => '[Year]', 'caption' => '[Young Samson]', 'tall' => true],
        ['image' => 'images/moments/02.jpg', 'label' => '[Year]', 'caption' => '[Wedding day]'],
        ['image' => 'images/moments/03.jpg', 'label' => '[Year]', 'caption' => '[With the children]'],
        ['image' => 'images/moments/04.jpg', 'label' => '[Year]', 'caption' => '[Preaching]', 'tall' => true],
        ['image' => 'images/moments/05.jpg', 'label' => '[Year]', 'caption' => '[Family gathering]'],
        ['image' => 'images/moments/06.jpg', 'label' => '[Year]', 'caption' => '[A favourite smile]'],
        ['image' => 'images/moments/07.jpg', 'label' => '[Year]', 'caption' => '[Last photo together]'],
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
        'time' => '[Time]',
        'venue' => '[Venue name]',
        'venue_area' => '[Town, State]',
        'livestream_url' => null, // e.g. 'https://youtube.com/live/...'
    ],

    /*
    | Music. Files live in public/audio and play in this order. Tracks whose
    | file is missing are skipped automatically.
    */
    'music' => [
        ['title' => 'It Is Well With My Soul', 'note' => 'Hymn', 'file' => 'audio/it-is-well.mp3'],
        ['title' => 'Amaram Onyewem', 'note' => 'Mercy Chinwo ft. Pastor Jerry Eze', 'file' => 'audio/amaram-onyewem.mp3'],
        ['title' => "Ìjọba Ọ̀run (Mu Mi De'Le)", 'note' => 'Lara George', 'file' => 'audio/ijoba-orun.mp3'],
    ],
    'music_volume' => 0.55,

    // When true, visitors' messages wait for approval
    // (php artisan tributes:review) before showing on the page.
    'moderate' => env('TRIBUTE_MODERATE', true),

    'footer' => 'With love, from his family',
];
