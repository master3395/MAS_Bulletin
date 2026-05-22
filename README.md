# MAS_BreakingLive

CMS Made Simple module for homepage **Breaking** and **Live** ticker bars (admin-managed, optional marquee scroll, optional News integration).

## Requirements

- CMS Made Simple 2.2.10 or newer
- PHP 7.4 or newer
- Optional: core **News** module for article-driven tickers
- Optional: **MAS_Common** (shared Help/About UI and donations tab; install from the CMSMS Forge if you use other MAS modules)

## Install

1. Copy this folder to `modules/MAS_BreakingLive/` in your CMSMS installation.
2. In **Extensions → Module Manager**, install **MAS Breaking and Live**.
3. Grant **Manage MAS_BreakingLive** to the appropriate admin group.
4. Place `{MAS_BreakingLive}` early in your homepage template (for example MarqueePageHome) so bxSlider pause is applied before init.

## Usage

- **Extensions → MAS Breaking and Live**: Settings (manual lines, show/hide, scroll, timing), News sources (ordered articles, merge modes), Help, About, Changelog.
- Frontend tag: `{MAS_BreakingLive}` outputs the red Breaking bar and blue Live bar and sets `window.__NT_BX_SLIDER_PAUSE_MS__` for the home news slider.

## Forge

Published on the [CMS Made Simple Forge](https://dev.cmsmadesimple.org/) as **MAS_BreakingLive** (when released).

## Author

- **master3395** — [News Targeted](https://newstargeted.com/contact/)

## License

MIT — see [LICENSE](LICENSE).
