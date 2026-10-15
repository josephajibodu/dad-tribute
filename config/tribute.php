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
    // appear after these. The card shows the 'excerpt'; when there is one,
    // a "Read more" link opens the full 'text' (Markdown) in a popup.
    'family_tributes' => [
        [
            'name' => 'Joseph Ajibodu',
            'relation' => 'First son',
            'title' => 'A Tribute to My Dad',
            'excerpt' => "My dad really wanted me to become a doctor. As I watched him go through his illness, I found myself wondering whether things might have been different if I had. Perhaps I would have been able to help him in ways I couldn't.",
            'text' => <<<'TEXT'
                There are some memories that stay with you for a lifetime, and there are people whose impact on your life can never truly be separated from who you become. For me, my dad was that person.

                I may not remember everything from my childhood clearly, but there is one picture that has remained in my head for as long as I can remember: my dad carrying me on his neck as he took me to school on foot. I don't know how that memory stayed with me, especially from when I was so young, but it did. And today, it means more to me than ever.

                I remember when I went to write my Common Entrance examination at Homaj Group of Schools, Itanla, Ondo. My dad, his friend, and I went there on a motorcycle. They stayed with me until I finished the examination before bringing me back home. It might seem like an ordinary memory to someone else, but it meant a lot to me. That was a school where some parents brought their children in cars, yet I was simply happy to be going to a good school. My dad was doing everything he could to give me access to a good education, and I could feel that.

                One thing I will always remember about my dad was how seriously he took my education. Whenever I needed something for school, whether it was books, materials for practicals, or anything else related to my learning, he would look for it. Even when he didn't have it readily available, he would find a way. My education was important to him, and he made sure I knew it through his actions.

                I remember how, at the beginning of a new term in secondary school, I was often already halfway through the term's Mathematics syllabus. This was because my dad would teach me at home, even with his busy work schedule. When I got to senior secondary school and started studying Physics, he taught me that too.

                Looking back, I realise that he was doing much more than helping me pass examinations. He was building a foundation that would stay with me for the rest of my life.

                But perhaps one of the greatest gifts my dad gave me was not just what he taught me directly, but the environment he created for me to learn.

                There were always books at home. When I was bored and had nothing to do, I could walk up to the bookshelf, pick up a book, and start reading. There were books covering different subjects and areas of knowledge, and I read many of them. I didn't always have to be told to study. Sometimes, I was simply curious about something, and there was a book nearby that could help me explore it.

                I have come to realise that my dad was nurturing my curiosity, even in ways he might not have fully realised himself. He made knowledge accessible to me. He gave me the opportunity to explore ideas beyond what I was taught in school.

                In fact, I started graphic design because of a book on graphic design that my dad had at home. With the computer he bought for us, I began practising what I learnt from that book until I became good at it. That same curiosity, that desire to understand how things work and to teach myself new skills, eventually led me into software engineering.

                Today, I am a civil engineer and a software engineer. When I look at my journey, I can trace so many parts of it back to my dad: the books he kept at home, the computer he provided, the lessons he taught me, and the freedom he gave me to explore my interests.

                So much of who I am today is connected to the opportunities he created for me.

                Recently, I was speaking with a friend who is a teacher, and she mentioned how some of the best-performing students in her class often have at least one parent who actively supports their learning at home. I immediately agreed with her because I had lived that experience. I was one of those children whose father took the time to teach, guide, and prepare them for what lay ahead.

                That conversation reminded me of how much my dad invested in me, and it made me appreciate him even more.

                It has also influenced the kind of father I hope to become. I have started building the habit of buying and keeping books, not just for myself, but for my children someday. I want them to grow up in an environment where books are accessible, where learning is encouraged, and where curiosity is allowed to flourish.

                I didn't develop that desire randomly. I learnt it from my dad. I want to pass on to my children the kind of gift he gave me.

                One thing I have also been thinking about, especially during the last days of his illness, is how much he wanted me to study Medicine. My dad really wanted me to become a doctor. I was good at Biology and other subjects related to the medical sciences, but deep down, I enjoyed Mathematics and Physics more. I wanted to pursue what I genuinely loved, so I chose a different path.

                As I watched him go through his illness and saw how things unfolded, I found myself wondering whether things might have been different if I had become a doctor. Perhaps having a doctor in the family would have made some things easier for him. Perhaps I would have been able to understand more of what was happening and help him in ways I couldn't.

                Those thoughts have crossed my mind, and I won't pretend they haven't. But I also know that I made the best decision I could with what I understood and what I wanted for my life at the time. And God knows best.

                What I know for certain is that, regardless of the career path I chose, my dad played a huge role in helping me become the person I am today. His investment in my education, his encouragement, and the opportunities he gave me will always remain part of my story.

                Dad, I wish I had more time with you. I wish there were more opportunities to sit with you, talk with you, and show you just how much of what you poured into me has become a part of my life.

                I will miss you. I already miss you, and I know there will be countless moments in the years ahead when something happens and I wish I could share it with you.

                But I will never forget you, because there is no version of who I am today that is completely separate from you. Your influence is in the way I think, the way I learn, my curiosity, my career, and even the kind of father I hope to become.

                Thank you for carrying me to school. Thank you for teaching me Mathematics and Physics. Thank you for searching for the things I needed for my education. Thank you for the books, the computer, and the opportunities to discover what I could become. Thank you for believing that my education was worth your time, effort, and sacrifice.

                Thank you for being my father.

                Your impact on my life will live on in me, in the things I build, in the knowledge I continue to seek, and in the lessons I will one day pass on to my own children.

                I love you, Dad. We all love you.

                **You may be gone, but everything you invested in me lives on.**

                Rest well, Dad. ❤️
                TEXT,
        ],
        [
            'name' => 'Larry Ajibodu',
            'relation' => 'Son',
            'excerpt' => 'You were a good father. Not just to us your children, but to many. 57 years feels so short, Dad. Sunset at noon. Gone too soon.',
            'text' => <<<'TEXT'
                You were a good father.
                Not just to us your children, but to many.
                A philanthropist with an open hand, you gave even when it was not convenient.
                You believed so much in education that through it, destinies can change. You sponsored, you advised, you pushed us to be the best.

                As our father, you loved us without condition.
                57 years feels so short, Dad. Sunset at Noon. Gone too soon.
                But with heavy hearts, we are grateful to God for a purposeful life lived.
                Rest well, Dad. Your legacy lives on in us.
                TEXT,
        ],
        [
            'name' => 'Love Ajibodu',
            'relation' => 'Son',
            'title' => 'A TRIBUTE TO MY DEAR FATHER ❤️🕊️',
            'excerpt' => 'My father, my hero, my first teacher, and a man whose kindness touched so many lives. Sleep well, my king. I love you, Dad, today, tomorrow, and always.',
            'text' => <<<'TEXT'
                My father, my hero, my first teacher, and a man whose kindness touched so many lives.

                Dad, words cannot explain the pain in my heart since you left us. It is still so difficult to believe that you are gone. I never imagined that I would have to write a tribute to you instead of telling you how much you mean to me. My heart is heavy, and the pain of losing you is deeper than words can express.

                You were not just a father to me. You were a loving, caring, and kind hearted man who treated people with love and respect. Your kindness was not limited to your family alone. You showed love to everyone around you, and you had a beautiful way of making people feel valued. Anyone who came across you could see the goodness in your heart.

                Your departure has left a space in my heart that nobody else can fill. I may have to learn how to live without your physical presence, but I will never stop loving you or carrying your memory with me.

                You came into this world, you touched lives with kindness, and you left a legacy of love. You will forever be remembered Dad

                Sleep well, my king. Until we meet again, may your gentle soul rest in perfect peace. I love you, Dad, today, tomorrow, and always. ❤️🕊️
                TEXT,
        ],
        [
            'name' => 'Comr. Samuel Aderanti',
            'relation' => 'NULGE State Welfare Officer, Osun State',
            'title' => 'TRIBUTE TO COM. EVANG. SAMSON TOYIN AJIBODU',
            'excerpt' => 'So soon, boss! We cannot question God. Your lifetime was an exemplary one in terms of service to humanity, and your legacy as a Cooperator will be difficult for anyone to fill.',
            'text' => <<<'TEXT'
                So soon, boss! We cannot question God. Alapa dupe!

                A Cooperator per excellence, Com. Evang. Samson Toyin Ajibodu, joined the Local Government Service in the year 1991 and retired meritoriously on 15th August, 2026.

                Less than 2 months after you dropped the pen from the Local Government Service of Osun State, the news of your sudden death through a brief illness came to us as a rude shock.

                Your lifetime was an exemplary one in terms of service to humanity, and your legacy as a Cooperator will be difficult for anyone to fill. Amongst your positions are:

                1. President, NULGE Cooperative, Ife South LG
                2. State President, NULGE Cooperative Society, Osun State
                3. Vice President, Ife South / Ifetedo (Latiri) Cooperative
                4. Warden, St. John's African Church, Olode, etc.

                Your death is painful and hard to believe. You were humble and friendly indeed.

                Sun re o! Com. Evang. Samson Toyin Ajibodu.

                Rest in perfect peace.
                TEXT,
        ],
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
