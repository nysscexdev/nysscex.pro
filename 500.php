<?php
// Yapılandırmayı yükle
if (file_exists('config.php')) {
  $config = require 'config.php';
} else {
  $config = require 'config.example.php';
}
?>
<!doctype html>
<html lang="tr" class="min-h-screen<?php echo ($config['theme_default'] === 'dark') ? ' dark' : ''; ?>">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>500 - Sunucu Hatası</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($config['site_icon']); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time() ?>">
  <link rel="stylesheet" href="assets/css/custom.css?v=<?php echo time() ?>">
  
  <script>
    window.CONFIG = {
      themeDefault: <?php echo json_encode($config['theme_default']); ?>,
      enableThemeToggle: <?php echo $config['enable_theme_toggle'] ? 'true' : 'false'; ?>
    };
  </script>
  <script src="assets/js/main.js?v=<?php echo time() ?>" defer></script>
</head>

<body class="bg-body text-black/90 dark:text-white/90 min-h-screen">
  <!-- Minimalist Theme Toggle Option -->
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

  <div class="error-page-container">
    <div class="error-page-header">
      <h1 class="error-page-title">An error occured</h1>
      <p class="error-page-subtitle">Here are the details:</p>
    </div>

    <!-- Main Card base -->
    <div class="error-detail-card card-base border border-black/5 dark:border-white/5">
      
      <!-- Graphic Box Column -->
      <div class="error-graphic-col">
        <div class="error-graphic-code">500</div>
        <div class="error-graphic-label">Server Error</div>
      </div>

      <!-- Info Column -->
      <div class="error-info-col">
        <div class="error-info-group">
          <span class="error-info-label">Title</span>
          <h2 class="error-info-value">Internal Server Error</h2>
        </div>

        <div class="error-info-group">
          <span class="error-info-label">Description</span>
          <p class="error-info-value-desc">The server encountered an internal error or misconfiguration.</p>
        </div>

        <div class="error-info-group">
          <span class="error-info-label">Details</span>
          <pre class="error-json-block"><code>{
  "message": "Internal Server Error",
  "statusCode": 500,
  "statusMessage": "Internal Server Error",
  "data": {
    "path": "<?php echo htmlspecialchars(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); ?>"
  }
}</code></pre>
        </div>
      </div>
    </div>

    <!-- Actions Below the Card -->
    <div class="error-page-actions">
      <a href="javascript:history.back()" class="btn-secondary">
        <i class="fa-solid fa-chevron-left"></i> Go Back
      </a>
      <a href="javascript:window.location.reload()" class="btn-secondary">
        <i class="fa-solid fa-rotate-right"></i> Refresh Page
      </a>
    </div>
  </div>
</body>

</html>
