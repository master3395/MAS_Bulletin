# MAS_Bulletin

CMS Made Simple module for homepage **Breaking** and **Live** ticker bars (admin-managed, optional marquee scroll, optional News integration).

Renamed from **MAS_BreakingLive** (16 characters) to **MAS_Bulletin** (12 characters) for hosts with a 15-character module name limit.

## Requirements

- CMS Made Simple 2.2.10 or newer
- PHP 7.4 or newer
- Optional: core **News** module for article-driven tickers
- Optional: **MAS_Common** (shared Help/About UI and donations tab)

## Install

1. Copy this folder to `modules/MAS_Bulletin/` in your CMSMS installation, or install from **`dist/MAS_Bulletin-1.4.0.xml`** via Module Manager.
2. In **Extensions → Module Manager**, install **MAS Bulletin**.
3. Grant **Manage MAS_Bulletin** to the appropriate admin group.
4. Place `{MAS_Bulletin}` early in your homepage template (for example MarqueePageHome).

## Forge packages

Pre-built releases in **`dist/`**:

- `MAS_Bulletin-1.4.0.xml` (CMSMS module export)
- `MAS_Bulletin-1.4.0.zip`
- `MAS_Bulletin-1.4.0.tar.gz`

## Migration from MAS_BreakingLive

Install **MAS_Bulletin** on a site that had **MAS_BreakingLive**: `lib/mas_bulletin_migrate.php` copies site preferences and group permissions. Then uninstall the legacy module.

## Author

- **master3395** — [News Targeted](https://newstargeted.com/contact/)

## License

MIT — see [LICENSE](LICENSE).
