<?php
// Yapılandırmayı yükle
if (file_exists('config.php')) {
  $config = require 'config.php';
} else {
  $config = require 'config.example.php';
}
?>
<!doctype html>
<html lang="en" class="min-h-screen<?php echo ($config['theme_default'] === 'dark') ? ' dark' : ''; ?>">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($config['site_title']); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($config['site_description']); ?>">
  <meta name="author" content="<?php echo htmlspecialchars($config['site_author']); ?>">
  <meta name="theme-color" content="<?php echo htmlspecialchars($config['site_theme_color']); ?>">

  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo htmlspecialchars($config['site_url']); ?>">
  <meta property="og:title" content="<?php echo htmlspecialchars($config['site_title']); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($config['site_description']); ?>">
  <meta property="og:image" content="<?php echo htmlspecialchars($config['site_url'] . $config['site_og_image']); ?>">

  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:title" content="<?php echo htmlspecialchars($config['site_title']); ?>">
  <meta property="twitter:description" content="<?php echo htmlspecialchars($config['site_description']); ?>">
  <meta property="twitter:image"
    content="<?php echo htmlspecialchars($config['site_url'] . $config['site_og_image']); ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($config['site_icon']); ?>">
  <link rel="apple-touch-icon" href="<?php echo htmlspecialchars($config['site_icon']); ?>">
  <link rel="manifest" href="assets/js/manifest.378bbf20.json">

  <script>
    window.CONFIG = {
      discordId: <?php echo json_encode($config['discord_user_id']); ?>,
      useDiscordAvatar: <?php echo $config['use_discord_avatar'] ? 'true' : 'false'; ?>,
      defaultAvatar: <?php echo json_encode($config['default_avatar']); ?>,
      spotifyAmbiance: <?php echo $config['spotify_ambiance'] ? 'true' : 'false'; ?>,
      themeDefault: <?php echo json_encode($config['theme_default']); ?>,
      enableThemeToggle: <?php echo $config['enable_theme_toggle'] ? 'true' : 'false'; ?>,
      enableDiscordActivity: <?php echo $config['enable_discord_activity'] ? 'true' : 'false'; ?>,
      enableSpotifyStatus: <?php echo $config['enable_spotify_status'] ? 'true' : 'false'; ?>
    };
  </script>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">

  <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time() ?>">
  <link rel="stylesheet" href="assets/css/custom.css?v=<?php echo time() ?>">
  <script src="assets/js/main.js?v=<?php echo time() ?>" defer></script>
</head>

<body>
  <div id="ambient-background" class="ambient-background"></div>
  <div id="app">
    <div id="__layout">
      <div class="bg-transparent relative">
        <?php if (!empty($config['enable_theme_toggle'])): ?>
        <div class="absolute z-50" style="top: 1.5rem; right: 1.5rem;">
          <a id="theme-toggle-btn" href="javascript:void(0)" onclick="toggleTheme()"
            class="theme-switch backdrop-blur-md" title="Gece/Gündüz Modu">
            <div class="theme-switch-thumb"></div>
            <div class="theme-switch-icons">
              <i class="fa-solid fa-sun theme-switch-icon icon-sun"></i>
              <i class="fa-solid fa-moon theme-switch-icon icon-moon"></i>
            </div>
          </a>
        </div>
        <?php endif; ?>
        <main class="responsive-screen pt-4">
          <div class="space-y-8" data-v-f7f8f3de>
            <header
              class="rounded-md flex flex-col-reverse mt-16 md:mt-24 mb-8 py-4 md:flex-row md:items-center md:justify-between justify-center"
              data-v-f7f8f3de>
              <div class="md:w-8/12" data-v-f7f8f3de>
                <div class="space-y-1" data-v-f7f8f3de>
                  <h1
                    class="font-semibold text-center tracking-widest text-4xl md:text-5xl md:text-left text-black/90 leading-tight dark:text-white/90 whitespace-nowrap"
                    data-v-f7f8f3de><?php echo htmlspecialchars($config['profile_name']); ?>
                  </h1>
                  <p class="text-center md:text-left text-sm text-black/40 dark:text-white/30 font-light"
                    data-v-f7f8f3de>
                    <?php echo htmlspecialchars($config['profile_title']); ?>
                  </p>
                </div>
              </div>
              <div class="relative mx-auto mb-4 md:mb-0 flex-shrink-0" style="width:176px;height:176px;"
                data-v-f7f8f3de>
                <div id="avatar-ring" class="avatar-ring transition-colors duration-500"></div>
                <div smart-image="true" id="avatar-bg"
                  style="position:absolute;inset:10px;border-radius:9999px;background-image:url(<?php echo htmlspecialchars($config['default_avatar']); ?>);background-position:center;background-size:cover;overflow:hidden;"
                  data-v-f7f8f3de>
                  <img id="avatar-img" src="<?php echo htmlspecialchars($config['default_avatar']); ?>" alt="image"
                    loading="lazy" class="invisible">
                </div>
                <div id="avatar-status" class="avatar-status transition-colors duration-500">
                  <div id="avatar-badge"
                    class="avatar-badge flex items-center justify-center transition-all duration-500"></div>
                </div>
              </div>
            </header>

            <section id="technologies" data-v-f7f8f3de>
              <div class="flex flex-col space-y-4" style="margin-top: 3.5rem !important;" data-v-f7f8f3de>
                <section data-v-f7f8f3de>
                  <h5
                    class="text-sm uppercase text-black/50 pb-2 mb-4 border-b border-black/5 dark:text-white/30 dark:border-white/5"
                    data-v-f7f8f3de>Development</h5>
                  <div class="marquee-container" id="tech-marquee">
                    <div class="marquee-content" id="tech-marquee-content">
                      <?php
                      // Kesintisiz kaydırma animasyonu için yetenek etiketlerini bir kez çoğaltıyoruz
                      for ($i = 0; $i < 2; $i++) {
                        foreach ($config['skills'] as $skill) {
                          echo '<div class="tech-tag"><i class="' . htmlspecialchars($skill['icon']) . '"></i><span>' . htmlspecialchars($skill['name']) . '</span></div>';
                        }
                      }
                      ?>
                    </div>
                  </div>
                </section>
              </div>
              <?php if (!empty($config['enable_discord_activity'])): ?>
              <section id="activity-section" class="hidden" data-v-f7f8f3de>
                <div class="flex flex-col space-y-4" style="margin-top: 3.5rem !important;" data-v-f7f8f3de>
                  <section data-v-f7f8f3de>
                    <h5
                      class="text-sm uppercase text-black/50 pb-2 mb-4 border-b border-black/5 dark:text-white/30 dark:border-white/5"
                      data-v-f7f8f3de>Activity</h5>
                    <a href="#" target="_blank" class="flex items-center justify-between rounded-lg card-base group">
                      <div id="activity-widget"
                        class="hidden items-center justify-between w-full h-full gap-3 transition-opacity duration-300">
                        <div
                          class="flex-shrink-0 w-10 h-10 rounded-md overflow-hidden bg-black/10 dark:bg-white/10 shadow-sm relative">
                          <img id="activity-cover"
                            src="https/raw.githubusercontent.com/LeonardSSH/vscord/main/assets/icons/php.png"
                            alt="Editing a PHP file" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-center overflow-hidden" style="gap: 2px;">
                          <div class="w-full overflow-hidden flex items-end">
                            <h3 id="activity-title" class="font-medium text-sm opacity-90 m-0 p-0 leading-none"
                              style="white-space: nowrap; width: max-content;">...</h3>
                          </div>
                          <div class="w-max overflow-hidden flex flex-col gap-1.5 mt-1.5">
                            <p id="activity-artist" class="text-xs opacity-60 font-light m-0 p-0 leading-none"
                              style="white-space: nowrap; width: max-content;">...</p>
                            <div
                              class="h-px w-full bg-gradient-to-r from-black/20 to-transparent dark:from-white/20 dark:to-transparent my-0.5">
                            </div>
                            <p id="activity-artist" class="text-xs opacity-60 font-light m-0 p-0 leading-none"
                              style="white-space: nowrap; width: max-content;">...</p>
                          </div>
                        </div>
                        <div class="flex-shrink-0 flex items-center justify-center opacity-80">
                          <i class="bi bi-escape discord-activity-animate" style="font-size: 1.5rem;"></i>
                        </div>
                      </div>
                    </a>
                  </section>
                </div>
              </section>
              <?php endif; ?>

              <section id="contact" data-v-f7f8f3de>
                <div class="flex flex-col space-y-4" style="margin-top: 3rem !important;" data-v-f7f8f3de>
                  <section data-v-f7f8f3de>
                    <h5
                      class="text-sm uppercase text-black/50 pb-2 mb-4 border-b border-black/5 dark:text-white/30 dark:border-white/5"
                      data-v-f7f8f3de>Contact</h5>
                    <p class="mb-4 text-xs dark:text-white/30 dark:font-light opacity-50" data-v-f7f8f3de>If you have
                      any questions, feel
                      free to contact me.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <a href="<?php echo htmlspecialchars($config['socials']['spotify']); ?>" target="_blank"
                        id="spotify-card" class="block rounded-lg card-base group relative overflow-hidden">

                        <div id="spotify-default"
                          class="flex items-center justify-between w-full transition-opacity duration-300">
                          <div>
                            <h3 class="font-medium text-sm opacity-90">Spotify</h3>
                            <p class="text-xs opacity-40 mt-1 font-light">Listen with me on Spotify!</p>
                          </div>
                          <div
                            class="transition-opacity opacity-70 group-hover:opacity-100 flex items-center justify-center">
                            <i class="bi bi-spotify" style="font-size: 1.5rem;"></i>
                          </div>
                        </div>

                        <div id="spotify-widget"
                          class="hidden items-center justify-between w-full h-full gap-3 transition-opacity duration-300">
                          <div
                            class="flex-shrink-0 w-10 h-10 rounded-md overflow-hidden bg-black/10 dark:bg-white/10 shadow-sm relative">
                            <img id="spotify-cover" src="" alt="Album Art" class="w-full h-full object-cover">
                          </div>
                          <div class="flex-1 min-w-0 flex flex-col justify-center overflow-hidden" style="gap: 2px;">
                            <div class="w-full overflow-hidden flex items-end">
                              <h3 id="spotify-title" class="font-medium text-sm opacity-90 m-0 p-0 leading-none"
                                style="white-space: nowrap; width: max-content;">Listening...</h3>
                            </div>
                            <div class="w-full overflow-hidden flex items-start">
                              <p id="spotify-artist" class="text-xs opacity-60 font-light m-0 p-0 leading-none"
                                style="white-space: nowrap; width: max-content;">Spotify</p>
                            </div>
                          </div>
                          <div class="flex-shrink-0 flex items-center justify-center">
                            <i class="bi bi-spotify spotify-card-animate"
                              style="font-size: 1.5rem; color: #1DB954;"></i>
                          </div>
                        </div>
                        <div id="spotify-progress-container"
                          class="hidden absolute top-0 left-0 w-full h-full pointer-events-none" style="z-index: 20;">
                          <div id="spotify-border-progress" class="absolute inset-0 rounded-lg pointer-events-none"
                            style="--progress: 0%; background: conic-gradient(from 280deg at 50% 50%, #1DB954 var(--progress), transparent 0); padding: 2px; -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); -webkit-mask-composite: xor; mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); mask-composite: exclude; filter: drop-shadow(0 0 6px rgba(29,185,84,0.8));">
                          </div>
                        </div>
                      </a>

                      <a href="<?php echo htmlspecialchars($config['socials']['discord']); ?>" target="_blank"
                        id="discord-card" class="flex items-center justify-between rounded-lg card-base group">
                        <div class="flex-1 min-w-0" style="width: 50px;">
                          <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-medium text-sm opacity-90">Discord</h3>
                            <div id="discord-status-container"
                              class="hidden items-center ml-2 border-l border-black/5 dark:border-white/5 pl-2">
                              <div id="discord-platforms" class="flex gap-2.5 items-center" style="font-size: 0.95rem;">
                              </div>
                            </div>
                          </div>

                          <div id="discord-scroller-parent" class="w-full overflow-hidden">
                            <p id="discord-scroller" class="text-xs opacity-50 font-light"
                              style="white-space: nowrap; width: max-content;">
                              <span id="discord-card-username-text" class="hidden font-medium opacity-80"></span>
                              <span id="discord-card-default-text">Add me on Discord!</span>
                            </p>
                          </div>
                        </div>

                        <div
                          class="transition-opacity opacity-70 group-hover:opacity-100 flex items-center justify-center flex-shrink-0">
                          <i class="bi bi-discord" style="font-size: 1.5rem;"></i>
                        </div>
                      </a>

                      <a href="<?php echo htmlspecialchars($config['socials']['github']); ?>" target="_blank"
                        class="flex items-center justify-between rounded-lg card-base group">
                        <div>
                          <h3 class="font-medium text-sm opacity-90">Github</h3>
                          <p class="text-xs opacity-40 mt-1 font-light">Follow me on GitHub!</p>
                        </div>
                        <div
                          class="transition-opacity opacity-70 group-hover:opacity-100 flex items-center justify-center">
                          <i class="bi bi-github" style="font-size: 1.5rem;"></i>
                        </div>
                      </a>

                      <a href="mailto:<?php echo htmlspecialchars($config['socials']['email']); ?>"
                        class="flex items-center justify-between rounded-lg card-base group">
                        <div>
                          <h3 class="font-medium text-sm opacity-90">Email</h3>
                          <p class="text-xs opacity-40 mt-1 font-light">Send me an email!</p>
                        </div>
                        <div
                          class="transition-opacity opacity-70 group-hover:opacity-100 flex items-center justify-center">
                          <i class="bi bi-envelope-fill" style="font-size: 1.5rem;"></i>
                        </div>
                      </a>
                    </div>
                  </section>
                  <?php if (!empty($config['enable_discover_section']) && !empty($config['discover_links'])): ?>
                    <section class="mt-8" style="margin-top: 3rem !important;" data-v-f7f8f3de>
                      <h5
                        class="text-sm uppercase text-black/50 pb-2 mb-4 border-b border-black/5 dark:text-white/30 dark:border-white/5"
                        data-v-f7f8f3de>Discovered</h5>
                      <div class="grid grid-cols-1 gap-4">
                        <?php foreach ($config['discover_links'] as $link): ?>
                          <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank"
                            class="flex items-center justify-between rounded-lg card-base group relative overflow-hidden"
                            style="border: 1px solid <?php echo htmlspecialchars($link['color']); ?>33;">
                            <div
                              class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none discover-gradient-bg"
                              style="--gradient-light: <?php echo htmlspecialchars($link['color']); ?>0D; --gradient-dark: <?php echo htmlspecialchars($link['color']); ?>1A;">
                            </div>

                            <div class="relative z-10 flex flex-col justify-center min-w-0 pr-2">
                              <div class="flex items-center gap-2">
                                <h3
                                  style="font-size: 0.875rem; font-weight: 500; opacity: 0.9; display: flex; align-items: center; margin: 0;">
                                  <?php if (!empty($link['logo'])): ?>
                                    <img src="<?php echo htmlspecialchars($link['logo']); ?>"
                                      style="height: 14px; width: auto; opacity: 0.8; margin-right: 6px; display: inline-block; vertical-align: middle;"
                                      class="hostinger-logo-render" alt="<?php echo htmlspecialchars($link['title']); ?>">
                                  <?php endif; ?>
                                  <?php echo htmlspecialchars($link['title']); ?>
                                </h3>
                              </div>
                              <p class="text-xs opacity-50 mt-1 font-light truncate">
                                <?php echo htmlspecialchars($link['description']); ?>
                              </p>
                            </div>

                            <div
                              class="transition-opacity opacity-70 group-hover:opacity-100 flex items-center justify-center relative z-10 flex-shrink-0">
                              <i class="<?php echo htmlspecialchars($link['icon_class']); ?>"
                                style="font-size: 1.5rem; color: <?php echo htmlspecialchars($link['color']); ?>;"></i>
                            </div>
                          </a>
                        <?php endforeach; ?>
                      </div>
                    </section>
                  <?php endif; ?>
                </div>
              </section>
              <div class="h-16 md:h-24" data-v-f7f8f3de></div>
          </div>
        </main>
      </div>
    </div>
  </div>
</body>

</html>