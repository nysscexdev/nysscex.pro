(function () {
  var STORAGE_KEY = 'color-mode';
  var defaultMode = window.CONFIG?.themeDefault || 'system';
  var enableToggle = window.CONFIG?.enableThemeToggle !== false;

  var savedPref = enableToggle ? ((window.localStorage && window.localStorage.getItem(STORAGE_KEY)) || defaultMode) : defaultMode;
  function getColorScheme() {
    return (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
  }
  var currentValue = savedPref === 'system' ? getColorScheme() : savedPref;
  var html = document.documentElement;
  if (currentValue === 'dark') { html.classList.add('dark'); }
  else { html.classList.remove('dark'); }
  window.__NUXT_COLOR_MODE__ = {
    preference: savedPref,
    value: currentValue,
    unknown: false,
    forced: false,
    getColorScheme: getColorScheme,
    addClass: function (mode) {
      if (mode === 'dark') { html.classList.add('dark'); }
      else { html.classList.remove('dark'); }
    },
    removeClass: function (mode) {
      if (mode === 'dark') { html.classList.remove('dark'); }
      else { html.classList.add('dark'); }
    }
  };
})();

function toggleTheme() {
  if (window.CONFIG?.enableThemeToggle === false) return;
  const STORAGE_KEY = 'color-mode';
  var html = document.documentElement;
  var isDark = html.classList.contains('dark');
  var newMode = isDark ? 'light' : 'dark';

  if (isDark) {
    html.classList.remove('dark');
  } else {
    html.classList.add('dark');
  }

  try { window.localStorage.setItem(STORAGE_KEY, newMode); } catch (e) { }

  if (window.__NUXT_COLOR_MODE__) {
    window.__NUXT_COLOR_MODE__.preference = newMode;
    window.__NUXT_COLOR_MODE__.value = newMode;
  }
}

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

async function getAmbientColors(imgUrl) {
  return new Promise(resolve => {
    const img = new Image();
    img.crossOrigin = "Anonymous";
    img.src = imgUrl;
    img.onload = () => {
      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d', { willReadFrequently: true });
      canvas.width = 50; canvas.height = 50;
      ctx.drawImage(img, 0, 0, 50, 50);

      const getPixel = (x, y) => {
        const d = ctx.getImageData(x, y, 1, 1).data;
        return `rgb(${d[0]}, ${d[1]}, ${d[2]})`;
      };

      const colors = [getPixel(5, 5), getPixel(45, 45), getPixel(25, 25)];
      resolve(colors);
    }
    img.onerror = () => resolve(null);
  });
}

async function updateAmbiance(url) {
  const ambientBg = document.getElementById('ambient-background');
  if (!ambientBg) return;

  if (window.CONFIG?.spotifyAmbiance === false) {
    ambientBg.style.opacity = '0';
    return;
  }

  if (!url) {
    ambientBg.style.opacity = '0';
    return;
  }

  const colors = await getAmbientColors(url);
  if (!colors) return;

  const isDark = document.documentElement.classList.contains('dark');
  const opacity = isDark ? '0.10' : '0.15';

  ambientBg.style.background = `
    radial-gradient(at 15% 0%, ${colors[0]} 0px, transparent 65%),
    radial-gradient(at 85% 0%, ${colors[1]} 0px, transparent 65%),
    radial-gradient(at 50% 15%, ${colors[2]} 0px, transparent 60%),
    radial-gradient(at 50% -10%, ${colors[0]} 0px, transparent 70%)
  `.trim();
  ambientBg.style.opacity = opacity;
}

const originalToggleTheme = toggleTheme;
toggleTheme = function () {
  originalToggleTheme();
  const spotifyCover = document.getElementById('spotify-cover');
  if (spotifyCover && spotifyCover.src && !spotifyCover.src.includes('window.location.host')) {
    updateAmbiance(spotifyCover.src);
  }
};

async function updateDiscordStatus() {
  try {
    const discordId = window.CONFIG?.discordId || '191282958768799744';
    const url = `https://api.lanyard.rest/v1/users/${discordId}`;
    const response = await $.ajax({
      url: url,
      method: 'GET',
      dataType: 'json'
    });
    const { data, success } = response;
    if (!success || !data) return;

    const activitySection = document.getElementById('activity-section');
    const activityWidget = document.getElementById('activity-widget');
    const activities = (window.CONFIG?.enableDiscordActivity !== false) ? (data.activities || []).filter(a => a.name !== 'Spotify' && a.id !== 'spotify:1' && a.type !== 4) : [];

    const currentStateStr = JSON.stringify(activities.map(a => ({
      n: a.name, d: a.details, s: a.state, i: a.application_id, ai: a.assets?.large_image
    })));

    if (window.forceFirstIconRender === undefined) window.forceFirstIconRender = true;

    if (activities.length > 0 && activitySection && activityWidget) {
      if (window.lastActivityState === currentStateStr && !window.forceFirstIconRender) {
      } else {
        window.lastActivityState = currentStateStr;
        window.forceFirstIconRender = false;

        activitySection.classList.remove('hidden');
        activitySection.style.display = 'block';
        activityWidget.classList.remove('hidden');
        activityWidget.style.display = 'flex';
        activityWidget.classList.add('flex-col', 'gap-4');
        activityWidget.innerHTML = '';

        activities.forEach((activity, index) => {
          const row = document.createElement('div');
          row.className = 'flex items-center justify-between w-full h-full gap-3';

          const getIconUrl = (appId, assetId) => {
            if (!assetId) return null;
            if (assetId.startsWith('mp:external/')) return assetId.split('https/')[1] ? 'https://' + assetId.split('https/')[1] : null;
            return `https://cdn.discordapp.com/app-assets/${appId}/${assetId}.png`;
          };

          const getElapsed = (start) => {
            const elapsed = Math.floor((Date.now() - start) / 1000);
            const h = Math.floor(elapsed / 3600);
            const m = Math.floor((elapsed % 3600) / 60);
            const s = elapsed % 60;
            if (h > 0) {
              return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            }
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
          };

          // --- Dinamik Yedek Resim Oluşturucu (UI Avatars) ---
          const fallbackIcon = `https://ui-avatars.com/api/?name=${encodeURIComponent((activity.name || 'A').charAt(0))}&background=random&color=fff&size=128&bold=true`;
          let coverUrl = (activity.name && activity.name.toLowerCase() === 'valorant')
            ? 'https://cdn.discordapp.com/app-icons/700136079562375258/e55fc8259df1548328f977d302779ab7.png?size=160'
            : (activity.assets && activity.assets.large_image ? getIconUrl(activity.application_id, activity.assets.large_image) : null);

          if (!coverUrl && activity.application_id) coverUrl = `https://cdn.discordapp.com/app-icons/${activity.application_id}/${activity.application_id}.png`;
          const finalUrl = coverUrl || fallbackIcon;

          // Geçen Süre Mantığı (Render anında hesaplanır)
          let durationHtml = '';
          if (activity.timestamps && activity.timestamps.start) {
            const start = activity.timestamps.start;
            durationHtml = `<span class="activity-timer ml-1 text-green-500 font-mono font-medium" data-start="${start}">${getElapsed(start)}</span>`;
          }

          // --- Kaydırma Desteği ile İçeriğe Duyarlı Alt Satırlar ---
          let subLinesHtml = '';
          const createScrollerLine = (id, content) => `
            <div class="w-full overflow-hidden">
              <p id="${id}" class="text-xs opacity-60 font-light m-0 p-0 leading-none" style="white-space: nowrap; width: max-content;">${content}</p>
            </div>`;

          if (activity.state) {
            const detailsPart = activity.details ? createScrollerLine(`activity-details-${index}`, activity.details) : '';
            const dividerPart = activity.details ? `<div class="h-px w-full bg-gradient-to-r from-black/20 to-transparent dark:from-white/20 dark:to-transparent my-0.5"></div>` : '';
            const statePart = createScrollerLine(`activity-state-${index}`, `${activity.state} ${durationHtml}`);
            subLinesHtml = detailsPart + dividerPart + statePart;
          } else if (activity.details) {
            subLinesHtml = createScrollerLine(`activity-details-${index}`, `${activity.details} ${durationHtml}`);
          } else if (durationHtml) {
            subLinesHtml = createScrollerLine(`activity-timer-line-${index}`, `${durationHtml}`);
          }

          const largeText = activity.assets && activity.assets.large_text ? activity.assets.large_text : (activity.name || '');
          const smallIconUrl = activity.assets && activity.assets.small_image ? getIconUrl(activity.application_id, activity.assets.small_image) : null;
          const smallText = activity.assets && activity.assets.small_text ? activity.assets.small_text : '';

          row.innerHTML = `
            <div class="relative flex-shrink-0 w-10 h-10 mr-1">
              <!-- Yuvarlatılmış Büyük Resim -->
              <div class="w-11 h-11 rounded-md overflow-hidden shadow-sm bg-black/10 dark:bg-white/10">
                <img src="${finalUrl}" onerror="this.src='${fallbackIcon}'" class="w-11 h-11 object-cover">
              </div>
              <!-- Büyük Resim için ipucu katmanı -->
              <div class="absolute inset-0 z-10" data-tooltip="${largeText}"></div>

              ${smallIconUrl ? `
                <div class="absolute w-5 h-5 rounded-full bg-body dark:bg-[#171717] shadow-sm border-[3px] border-white dark:border-[#171717] flex items-center justify-center z-20" style="right: -5px; bottom: -6px;" data-tooltip="${smallText}">
                  <img src="${smallIconUrl}" class="w-full h-full rounded-full object-cover">
                </div>
              ` : ''}
            </div>
            <div class="flex-1 min-w-0 flex flex-col justify-center overflow-hidden" style="gap: 2px;">
              <div class="w-full overflow-hidden flex items-end">
                <h3 id="activity-title-${index}" class="font-medium text-sm opacity-90 m-0 p-0 leading-none" style="white-space: nowrap; width: max-content;">${activity.name || ''}</h3>
              </div>
              <div class="w-full overflow-hidden flex flex-col gap-1.5 mt-1.5">${subLinesHtml}</div>
            </div>
            <div class="flex-shrink-0 flex items-center justify-center opacity-80">
              <i class="bi bi-escape discord-activity-animate" style="font-size: 1.5rem;"></i>
            </div>
          `;

          activityWidget.appendChild(row);

          // Her satır için kaydırma çubuklarını yönet
          setTimeout(() => {
            const scrollerIds = [`activity-title-${index}`, `activity-details-${index}`, `activity-state-${index}`, `activity-timer-line-${index}`];
            scrollerIds.forEach(id => {
              const el = document.getElementById(id);
              if (!el) return;
              const parent = el.parentElement;
              // Ekran genişliği <= 400 ve karakter uzunluğu > 22 ise ya da içerik taşıyorsa kaydırmaya zorla
              if (el.scrollWidth > parent.clientWidth + 1 || (window.innerWidth <= 400 && el.textContent.length > 22)) {
                el.style.setProperty('--container-width', parent.clientWidth + 'px');
                el.classList.add('spotify-scroller');
                parent.style.maskImage = 'linear-gradient(to right, black 85%, transparent 100%)';
                parent.style.webkitMaskImage = 'linear-gradient(to right, black 85%, transparent 100%)';
              } else {
                el.classList.remove('spotify-scroller');
                parent.style.maskImage = '';
                parent.style.webkitMaskImage = '';
              }
            });
          }, 100);

          if (index > 0) {
            const div = document.createElement('div');
            div.className = 'w-full h-px bg-black/5 dark:bg-white/5 opacity-50 my-1';
            activityWidget.appendChild(div);
          }
          activityWidget.appendChild(row);
        });
      }
    } else if (activitySection) {
      window.lastActivityState = null;
      window.forceFirstIconRender = true;
      activitySection.style.display = 'none';
    }

    if (!window.activityTimerInterval) {
      window.activityTimerInterval = setInterval(() => {
        document.querySelectorAll('.activity-timer').forEach(timer => {
          const start = parseInt(timer.getAttribute('data-start'));
          const elapsed = Math.floor((Date.now() - start) / 1000);
          const h = Math.floor(elapsed / 3600);
          const m = Math.floor((elapsed % 3600) / 60);
          const s = elapsed % 60;
          if (h > 0) {
            timer.textContent = `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
          } else {
            timer.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
          }
        });
      }, 1000);
    }

    const avatarBg = document.getElementById('avatar-bg');
    const avatarImg = document.getElementById('avatar-img');
    if (window.CONFIG?.useDiscordAvatar !== false && data.discord_user && data.discord_user.avatar) {
      const extension = data.discord_user.avatar.startsWith('a_') ? 'gif' : 'png';
      const avatarUrl = `https://cdn.discordapp.com/avatars/${data.discord_user.id}/${data.discord_user.avatar}.${extension}?size=512`;
      if (avatarBg) avatarBg.style.backgroundImage = `url(${avatarUrl})`;
      if (avatarImg) avatarImg.src = avatarUrl;
    }

    const avatarRing = document.getElementById('avatar-ring');
    const avatarBadge = document.getElementById('avatar-badge');
    const avatarStatus = document.getElementById('avatar-status');

    // Renkleri ve güncel durum rengini tanımla
    const colors = { online: '#3ba55c', idle: '#faa61a', dnd: '#ed4245', offline: '#747f8d' };
    const statusColor = colors[data.discord_status] || colors.offline;

    if (avatarBadge && avatarRing && avatarStatus) {
      const discordStatusContainer = document.getElementById('discord-status-container');
      const discordPlatforms = document.getElementById('discord-platforms');
      const discordUsernameText = document.getElementById('discord-card-username-text');
      const discordDefaultText = document.getElementById('discord-card-default-text');

      if (data.discord_user) {
        if (discordStatusContainer) {
          discordStatusContainer.classList.remove('hidden');
          discordStatusContainer.classList.add('flex');
        }
        if (discordUsernameText) {
          const du = data.discord_user;
          const displayName = du.global_name || du.display_name;
          const newText = (displayName && displayName !== du.username) ? `${displayName} (@${du.username}), ` : `@${du.username}, `;

          if (discordUsernameText.textContent !== newText) {
            discordUsernameText.textContent = newText;
          }

          // Kaydırma taşmasını hassas bir şekilde kontrol et
          const discordScroller = document.getElementById('discord-scroller');
          if (discordScroller) {
            const parent = discordScroller.parentElement;
            // Ekran genişliği <= 400 ve karakter uzunluğu > 22 ise ya da içerik taşıyorsa kaydırmaya zorla
            if (discordScroller.scrollWidth > parent.clientWidth + 1 || (window.innerWidth <= 400 && discordScroller.textContent.length > 22)) {
              discordScroller.style.setProperty('--container-width', parent.clientWidth + 'px');
              discordScroller.classList.add('spotify-scroller');
              parent.style.maskImage = 'linear-gradient(to right, black 85%, transparent 100%)';
              parent.style.webkitMaskImage = 'linear-gradient(to right, black 85%, transparent 100%)';
            } else {
              discordScroller.classList.remove('spotify-scroller');
              parent.style.maskImage = '';
              parent.style.webkitMaskImage = '';
            }
          }
          discordUsernameText.classList.remove('hidden');
        }
        if (discordDefaultText) discordDefaultText.textContent = 'add me on Discord!';
        if (discordPlatforms) {
          discordPlatforms.style.color = statusColor;
          let phtml = '';
          if (data.active_on_discord_desktop) phtml += '<span data-tooltip="Masaüstü"><i class="bi bi-display"></i></span>';
          if (data.active_on_discord_mobile) phtml += '<span data-tooltip="Mobil"><i class="bi bi-phone"></i></span>';
          if (data.active_on_discord_web) phtml += '<span data-tooltip="Web"><i class="bi bi-browser-chrome"></i></span>';

          if (discordPlatforms.innerHTML !== phtml) {
            discordPlatforms.innerHTML = phtml;
          }
        }
      }
    }

    const spotifyWidget = document.getElementById('spotify-widget');
    const spotifyDefault = document.getElementById('spotify-default');
    const spotifyProgressContainer = document.getElementById('spotify-progress-container');

    if (data.listening_to_spotify && data.spotify && window.CONFIG?.enableSpotifyStatus !== false) {
      if (avatarRing && avatarStatus && avatarBadge) {
        const spotifyGreen = '#1DB954';
        const borderGreen = '#10b981';
        avatarRing.style.borderColor = borderGreen;
        avatarStatus.style.borderColor = borderGreen;
        avatarBadge.style.backgroundColor = 'transparent';
        avatarBadge.classList.add('spotify-avatar-animate');
        avatarBadge.setAttribute('data-tooltip', `Listening to ${data.spotify.song} - ${data.spotify.artist}`);
        avatarBadge.innerHTML = `<i class="fa-brands fa-spotify" style="color: ${spotifyGreen}; font-size: 1rem; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;"></i>`;
      }

      if (spotifyWidget && spotifyDefault) {
        spotifyDefault.classList.add('hidden');
        spotifyWidget.classList.remove('hidden');
        spotifyWidget.classList.add('flex');
        if (spotifyProgressContainer) spotifyProgressContainer.classList.remove('hidden');

        const spotifyTitle = document.getElementById('spotify-title');
        const spotifyArtist = document.getElementById('spotify-artist');
        const spotifyCover = document.getElementById('spotify-cover');

        if (spotifyTitle && spotifyTitle.textContent !== data.spotify.song) {
          spotifyTitle.textContent = data.spotify.song;
          if (spotifyArtist) spotifyArtist.textContent = data.spotify.artist;
          if (spotifyCover) {
            spotifyCover.src = data.spotify.album_art_url;
            updateAmbiance(data.spotify.album_art_url);
          }
          setTimeout(() => {
            [spotifyTitle, spotifyArtist].forEach(el => {
              if (!el) return;
              const parent = el.parentElement;
              if (el.scrollWidth > parent.clientWidth + 1 || (window.innerWidth <= 400 && el.textContent.length > 22)) {
                el.style.setProperty('--container-width', parent.clientWidth + 'px');
                el.classList.add('spotify-scroller');
                parent.style.maskImage = 'linear-gradient(to right, black 85%, transparent 100%)';
                parent.style.webkitMaskImage = 'linear-gradient(to right, black 85%, transparent 100%)';
              } else {
                el.classList.remove('spotify-scroller');
                parent.style.maskImage = '';
                parent.style.webkitMaskImage = '';
              }
            });
          }, 100);
        }

        if (data.spotify.timestamps) {
          window.spotifyStart = data.spotify.timestamps.start;
          window.spotifyEnd = data.spotify.timestamps.end;
          if (!window.spotifyProgressLoop) {
            window.spotifyProgressLoop = true;
            window.currentVisualWidth = null;
            const updateProg = () => {
              if (!window.spotifyStart || !window.spotifyEnd) return;
              const target = Math.min(100, Math.max(0, ((Date.now() - window.spotifyStart) / (window.spotifyEnd - window.spotifyStart)) * 100));
              if (window.currentVisualWidth === null) window.currentVisualWidth = target;
              const diff = target - window.currentVisualWidth;
              if (Math.abs(diff) > 0.5) window.currentVisualWidth += Math.sign(diff) * 0.8;
              else window.currentVisualWidth = target;
              const borderProg = document.getElementById('spotify-border-progress');
              if (borderProg) borderProg.style.setProperty('--progress', window.currentVisualWidth + '%');
              requestAnimationFrame(updateProg);
            };
            requestAnimationFrame(updateProg);
          }
        }
      }
    } else {
      if (avatarRing && avatarStatus && avatarBadge) {
        avatarRing.style.borderColor = '';
        avatarStatus.style.borderColor = '';
        avatarBadge.style.backgroundColor = statusColor;
        avatarBadge.classList.remove('spotify-avatar-animate');
        const statusMap = {
          online: 'Çevrimiçi',
          idle: 'Boşta',
          dnd: 'Rahatsız Etme',
          offline: 'Çevrimdışı'
        };
        const statusName = statusMap[data.discord_status] || 'Çevrimdışı';
        avatarBadge.setAttribute('data-tooltip', statusName);
        avatarBadge.innerHTML = '';
      }
      if (spotifyWidget && spotifyDefault) {
        spotifyWidget.classList.add('hidden');
        spotifyDefault.classList.remove('hidden');
        spotifyDefault.classList.add('flex');
        if (spotifyProgressContainer) spotifyProgressContainer.classList.add('hidden');
        window.spotifyStart = null;
        window.spotifyEnd = null;
        updateAmbiance(null);
      }
    }
  } catch (error) { console.error('Discord error:', error); }
}

function updateAllScrollers() {
  const scrollers = document.querySelectorAll('#discord-scroller, #spotify-title, #spotify-artist, [id^="activity-title-"], [id^="activity-line-"], [id^="activity-state-"], [id^="activity-timer-line-"]');
  scrollers.forEach(el => {
    if (!el) return;
    const parent = el.parentElement;
    if (!parent) return;
    if (el.scrollWidth > parent.clientWidth + 1 || (window.innerWidth <= 400 && el.textContent.length > 22)) {
      el.style.setProperty('--container-width', parent.clientWidth + 'px');
      el.classList.add('spotify-scroller');
      parent.style.maskImage = 'linear-gradient(to right, black 85%, transparent 100%)';
      parent.style.webkitMaskImage = 'linear-gradient(to right, black 85%, transparent 100%)';
    } else {
      el.classList.remove('spotify-scroller');
      parent.style.maskImage = '';
      parent.style.webkitMaskImage = '';
    }
  });
}

window.addEventListener('resize', updateAllScrollers);

document.addEventListener('DOMContentLoaded', function () {
  scrollToTop();
  updateDiscordStatus();
  setInterval(updateDiscordStatus, 1000);
});

