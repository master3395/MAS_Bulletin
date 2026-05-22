# MAS_Bulletin changelog

## 1.4.0 (22.05.2026)

- **Module renamed:** `MAS_BreakingLive` (16 characters) is now **`MAS_Bulletin`** (12 characters) for hosts with a 15-character module name limit. Plugin tag is `{MAS_Bulletin}`. Permission is **Manage MAS_Bulletin**. Install or upgrade runs `lib/mas_bulletin_migrate.php` to copy site preferences and group permissions from the legacy module name.

## 1.3.0 (20.05.2026)

- **Show / scroll checkboxes did not stick on Save:** Each checkbox now posts a hidden `0` plus `1` when checked (CMS field names `m1_*`). Save reads params reliably; display uses explicit `=== '1'`. Fixes enable/disable not applying in admin or on the homepage.

## 1.2.9 (20.05.2026)

- **Tickers still visible after disabling in admin:** Settings were saved (`show_breaking` / `show_live` = 0) but LiteSpeed/CMS HTML cache could still serve old markup. Saving settings now clears CMS caches. Frontend defaults for show flags treat only explicit `1` as on.

## 1.2.8 (20.05.2026)

- **Edge still static on wide screens:** Ticker row uses CSS grid (`label | 1fr`) so the viewport width is measured correctly; when admin scroll is on and the line is long enough, scroll is forced even if flex previously expanded to full text width. JS uses `setProperty(..., 'important')` for transforms. **`mas_bl_marquee.js?v=20260525`**, **`nt-news-v2.css?v=20260525`**.

## 1.2.7 (20.05.2026)

- **Ticker static in Microsoft Edge:** Marquee always uses JavaScript `translate3d` when text overflows (Edge often blocks CSS `@keyframes` while `animation-name` still appears active). Viewport width uses `getBoundingClientRect`; init retries after layout. **`mas_bl_marquee.js?v=20260524`**.

## 1.2.6 (20.05.2026)

- **Ticker did not scroll:** Marquee viewport used `flex: 1` but grew with content, so overflow was never detected. Viewport now uses `flex: 1 1 0`, `width: 0`, `min-width: 0`; scroll uses `track.scrollWidth` vs `outer.clientWidth`. **`nt-news-v2.css?v=20260523`**, **`mas_bl_marquee.js?v=20260523`**.

## 1.2.5 (20.05.2026)

- **Duplicate ticker text side by side:** The loop copy for seamless scroll is only added when the line is wider than the bar. Short text shows once (no repeated headline). **`mas_bl_marquee.js?v=20260522c`**.

## 1.2.4 (20.05.2026)

- **Ticker “two lines” on homepage:** Marquee text no longer wraps to a second row. Track uses inline-block + `white-space: nowrap` (with `#main` overrides); loop clone stays on one line. Cache bust **`nt-news-v2.css?v=20260522`**, **`mas_bl_marquee.js?v=20260522b`**.

## 1.2.3 (20.05.2026)

- **Homepage tickers missing after upgrade:** Empty `show_breaking` / `show_live` (and related) prefs in the database were treated as off (`'' === '1'`). New `lib/mas_bl_prefs.php` treats empty values as enabled defaults; upgrade seeds blank prefs and default ticker text.
- **Public assets:** Upgrade still normalizes `images/` and `lib/*.js` to **0644** so `banner.png` and `mas_bl_marquee.js` are not HTTP 403.

## 1.2.2 (20.05.2026)

- **Permissions:** `mas_bl_fs_normalize.php` sets web-readable **0644** on public assets (was **0660**, caused 403 on CSS/JS/banner).

## 1.2.0 (20.05.2026)

- **Ticker links:** Settings and News sources tabs: enable/disable links to News article URLs and to `https://` URLs (or simple `<a href="https://...">` tags) in manual Breaking and Live lines.
- Formatter: `lib/class.MasBulletinFormatter.php` builds safe HTML for the frontend ticker.

## 1.1.3 (20.05.2026)

- Marquee scroll: JavaScript fallback when CSS animation is blocked (common in Edge when Windows **Animation effects** is off / `prefers-reduced-motion: reduce`).
- CSS: `nowrap` on ticker rows when scroll is enabled; `translate3d` keyframes; pause on hover/focus.
- Script `lib/mas_bl_marquee.js` loaded when breaking or live scroll is on.

## 1.1.2 (05.05.2026)

- Admin About tab: use ASCII hyphen between friendly name and version (no em dash in template).

## 1.1.1 (03.05.2026)

- After install or upgrade, normalize module directory modes (**771** on folders, **660** on files) so CMSMS **Module Manager** can delete the tree when the PHP user shares **group** access like other MAS modules (for example **MAS_CSR**). Fixes **Cannot Remove** when files were **644** and the web user is not the file owner.

## 1.1.0 (03.05.2026)

- Admin tabs: Settings, News sources, Help, About, Changelog, Donations (hide donations stores current version like MAS_CSR).
- News module: ordered article IDs for breaking and live lines, merge modes (manual only, news only, news first, manual first).
- Home hero: bxSlider pause (ms) from module preference, exposed before slider init via window.__NT_BX_SLIDER_PAUSE_MS__.
- Marquee: configurable animation duration (seconds) for breaking and live tracks.
- Admin section preference (Extensions menu placement) like MAS_CSR.
- CodeMirror on manual ticker textareas in Settings.
- GetChangeLog reads this file for the Extensions changelog link and in-admin Changelog tab.

## 1.0.0

- Initial release: manual breaking and live lines, optional marquee animation.
