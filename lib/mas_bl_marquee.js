/**
 * MAS_Bulletin: JS marquee (Edge-safe). Loop copy when text overflows viewport.
 */
(function () {
  'use strict';

  var MIN_CONTENT_PX = 280;

  function parseDurationSec(track, outer) {
    var raw = '';
    if (track && track.dataset && track.dataset.marqueeDuration) {
      raw = track.dataset.marqueeDuration + 's';
    } else if (outer && outer.dataset && outer.dataset.marqueeDuration) {
      raw = outer.dataset.marqueeDuration + 's';
    }
    var n = parseFloat(raw);
    if (!isFinite(n) || n < 4) {
      n = 48;
    }
    if (n > 300) {
      n = 300;
    }
    return n;
  }

  function pinSingleLine(el) {
    if (!el || !el.style) {
      return;
    }
    el.style.display = 'inline-block';
    el.style.whiteSpace = 'nowrap';
    el.style.maxWidth = 'none';
    el.style.wordWrap = 'normal';
    el.style.overflowWrap = 'normal';
  }

  function getOuter(track) {
    return track ? track.closest('.mas-bl-marquee-outer') : null;
  }

  function scrollEnabledByAdmin(outer) {
    return !!(outer && outer.getAttribute('data-mas-bl-scroll') === '1');
  }

  function removeLoopCopy(track) {
    var gap = track.querySelector('.mas-bl-gap');
    if (gap && gap.parentNode) {
      gap.parentNode.removeChild(gap);
    }
  }

  function viewportWidth(outer) {
    var rect = outer.getBoundingClientRect();
    if (rect.width > 8) {
      return rect.width;
    }
    var row = outer.closest('.nt-news-v2-breaking-content, .nt-news-v2-live-content');
    if (row) {
      var rowRect = row.getBoundingClientRect();
      var label = row.querySelector('.nt-news-v2-breaking-label, .nt-news-v2-live-label');
      if (label) {
        return Math.max(0, rowRect.width - label.getBoundingClientRect().width - 24);
      }
      return rowRect.width;
    }
    return outer.clientWidth || 0;
  }

  function contentScrollWidth(track) {
    var first = track.querySelector('.mas-bl-marquee-content');
    if (!first) {
      return track.scrollWidth || 0;
    }
    pinSingleLine(track);
    pinSingleLine(first);
    return Math.max(first.scrollWidth || 0, first.offsetWidth || 0);
  }

  function needsScroll(track) {
    var outer = getOuter(track);
    if (!outer) {
      return false;
    }
    removeLoopCopy(track);
    pinSingleLine(track);

    var trackW = contentScrollWidth(track);
    if (trackW < 8) {
      return false;
    }

    var viewW = viewportWidth(outer);

    if (scrollEnabledByAdmin(outer) && trackW >= MIN_CONTENT_PX) {
      if (viewW < 8) {
        return true;
      }
      if (trackW > viewW + 4) {
        return true;
      }
      var row = outer.closest('.nt-news-v2-breaking-content, .nt-news-v2-live-content');
      if (row) {
        var rowW = row.getBoundingClientRect().width;
        if (rowW > 0 && trackW > rowW * 0.42) {
          return true;
        }
      }
    }

    if (viewW < 8) {
      return false;
    }
    return trackW > viewW + 4;
  }

  function applyTransform(track, pct) {
    var tx = 'translate3d(' + (-50 * pct) + '%,0,0)';
    track.style.setProperty('transform', tx, 'important');
    track.style.setProperty('-webkit-transform', tx, 'important');
  }

  function stopJsMarquee(track) {
    track.classList.remove('mas-bl-marquee-js');
    track.dataset.masBlJsRunning = '0';
    track.style.removeProperty('animation');
    track.style.removeProperty('-webkit-animation');
    track.style.removeProperty('transform');
    track.style.removeProperty('-webkit-transform');
  }

  function setStaticMode(track) {
    var outer = getOuter(track);
    removeLoopCopy(track);
    stopJsMarquee(track);
    track.classList.add('mas-bl-marquee-static');
    if (outer) {
      outer.classList.remove('mas-bl-marquee-active');
      outer.classList.add('mas-bl-marquee-fits');
    }
  }

  function setScrollMode(track) {
    var outer = getOuter(track);
    track.classList.remove('mas-bl-marquee-static');
    if (outer) {
      outer.classList.add('mas-bl-marquee-active');
      outer.classList.remove('mas-bl-marquee-fits');
    }
  }

  function ensureLoopCopy(track) {
    var first = track.querySelector('.mas-bl-marquee-content');
    if (!first) {
      return false;
    }

    removeLoopCopy(track);

    if (!needsScroll(track)) {
      setStaticMode(track);
      return false;
    }

    setScrollMode(track);

    if (track.querySelector('.mas-bl-gap')) {
      return true;
    }

    pinSingleLine(track);
    pinSingleLine(first);
    var gap = first.cloneNode(true);
    gap.classList.add('mas-bl-gap');
    gap.classList.remove('mas-bl-marquee-content');
    gap.setAttribute('aria-hidden', 'true');
    pinSingleLine(gap);
    track.appendChild(gap);
    return true;
  }

  function startJsMarquee(track) {
    if (track.dataset.masBlJsRunning === '1') {
      return;
    }
    track.dataset.masBlJsRunning = '1';
    track.classList.add('mas-bl-marquee-js');
    track.style.setProperty('animation', 'none', 'important');
    track.style.setProperty('-webkit-animation', 'none', 'important');

    var outer = getOuter(track);
    var durationSec = parseDurationSec(track, outer);
    var startTs = null;
    var paused = false;

    function pause() {
      paused = true;
    }
    function resume() {
      paused = false;
    }

    track.addEventListener('mouseenter', pause);
    track.addEventListener('mouseleave', resume);
    track.addEventListener('focusin', pause);
    track.addEventListener('focusout', resume);

    function step(ts) {
      if (!paused) {
        if (startTs === null) {
          startTs = ts;
        }
        var elapsed = (ts - startTs) / 1000;
        var pct = (elapsed % durationSec) / durationSec;
        applyTransform(track, pct);
      } else {
        startTs = null;
      }
      window.requestAnimationFrame(step);
    }

    window.requestAnimationFrame(step);
  }

  function initTrack(track) {
    if (!track) {
      return;
    }

    stopJsMarquee(track);

    if (!ensureLoopCopy(track)) {
      return;
    }

    startJsMarquee(track);
  }

  var resizeObserved = false;

  function attachResizeObservers() {
    if (resizeObserved || typeof ResizeObserver !== 'function') {
      return;
    }
    var outers = document.querySelectorAll('.mas-bl-marquee-outer');
    if (!outers.length) {
      return;
    }
    resizeObserved = true;
    outers.forEach(function (outer) {
      try {
        var ro = new ResizeObserver(function () {
          clearTimeout(resizeTimer);
          resizeTimer = setTimeout(initAll, 100);
        });
        ro.observe(outer);
      } catch (eRo) {
        /* ignore */
      }
    });
  }

  function initAll() {
    var tracks = document.querySelectorAll('.mas-bl-marquee-outer .mas-bl-marquee-track');
    var i;
    for (i = 0; i < tracks.length; i++) {
      initTrack(tracks[i]);
    }
    attachResizeObservers();
  }

  function scheduleInit() {
    window.requestAnimationFrame(function () {
      window.requestAnimationFrame(initAll);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', scheduleInit);
  } else {
    scheduleInit();
  }

  window.addEventListener('load', scheduleInit);

  var resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(initAll, 160);
  });

  var delays = [80, 200, 500, 1200, 2500];
  var d;
  for (d = 0; d < delays.length; d++) {
    setTimeout(initAll, delays[d]);
  }

})();
