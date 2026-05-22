<?php
// Proje Yapılandırma Şablonu - Bu dosyayı config.php adıyla kopyalayıp kendinize göre düzenleyin

return [
    // --- SEO ve Meta Veri Ayarları ---
    'site_title' => 'Çağrı Bozkurt | Web Developer & Security Specialist',
    'site_description' => 'Professional Web Developer and Security Specialist from Turkey. Specialized in JavaScript, PHP, Python and Cybersecurity.',
    'site_author' => 'Çağrı Bozkurt',
    'site_theme_color' => '#171717',
    'site_url' => 'https://alanadiniz.com/',
    'site_og_image' => 'assets/images/nysscex_og.jpg',
    'site_icon' => 'assets/icons/nysscex_icon.png',

    // --- Tema Ayarları ---
    'theme_default' => 'dark', // Varsayılan tema: 'dark' (karanlık), 'light' (aydınlık) veya 'system' (sistem tercihi)
    'enable_theme_toggle' => true, // Tema değiştirme butonunu aktif eder/gizler: true veya false

    // --- Profil & Hero Bölümü Ayarları ---
    'profile_name' => 'Çağrı Bozkurt',
    'profile_title' => 'Web Developer & Security Specialist',
    'default_avatar' => 'assets/images/profile.png', // Varsayılan profil resmi yerel yolu

    // --- Entegrasyonlar ---
    'discord_user_id' => 'DISCORD_KULLANICI_ID_YAZIN', // Lanyard durumu, aktiviteleri ve avatarı için kullanılacak Discord ID
    'use_discord_avatar' => true, // Lanyard API kullanarak profil resmini aktif Discord avatarı ile değiştirir: true veya false
    'enable_discord_activity' => true, // Discord aktivitelerini (oyun, kodlama vb.) gösterir/gizler: true veya false
    'enable_spotify_status' => true, // Spotify çalma durumunu (dinlenilen şarkı kartını) gösterir/gizler: true veya false
    'spotify_ambiance' => true, // Spotify'da çalan şarkının kapak resmine göre arka plan rengini dinamik olarak değiştirir: true veya false

    // --- Sosyal Medya / İletişim Kartları ---
    'socials' => [
        'spotify' => 'https://open.spotify.com/user/SPOTIFY_KULLANICI_ID_YAZIN',
        'discord' => 'https://discord.com/users/DISCORD_KULLANICI_ID_YAZIN',
        'github' => 'https://github.com/GITHUB_KULLANICI_ADINIZ_YAZIN',
        'email' => 'iletisim@alanadiniz.com',
    ],

    // --- Öne Çıkarılan Bağlantılar (Discovered Bölümü) ---
    'enable_discover_section' => true,
    'discover_links' => [
        [
            'title' => 'Hostinger',
            'url' => 'https://hostinger.com.tr/?REFERRALCODE=REFERANS_KODUNUZ_YAZIN',
            'logo' => 'https://logowiki.net/uploads/logo/h/hostinger.svg',
            'description' => 'Get reliable Web Hosting with my referral discount!',
            'icon_class' => 'bi bi-hdd-network',
            'color' => '#673DE6',
        ]
    ],

    // --- Teknoloji Marquee (Kayan Liste) Yetenekleri ---
    'skills' => [
        ['icon' => 'devicon-html5-plain', 'name' => 'HTML'],
        ['icon' => 'devicon-css3-plain', 'name' => 'CSS'],
        ['icon' => 'devicon-javascript-plain', 'name' => 'JavaScript'],
        ['icon' => 'devicon-nodejs-plain', 'name' => 'Node.js'],
        ['icon' => 'devicon-python-plain', 'name' => 'Python'],
        ['icon' => 'devicon-php-plain', 'name' => 'PHP'],
        ['icon' => 'devicon-mysql-plain', 'name' => 'MySQL'],
        ['icon' => 'devicon-mongodb-plain', 'name' => 'MongoDB'],
        ['icon' => 'devicon-json-plain', 'name' => 'JSON'],
        ['icon' => 'devicon-bootstrap-plain', 'name' => 'Bootstrap'],
    ]
];
