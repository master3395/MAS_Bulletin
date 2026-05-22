<?php
if (!function_exists('cmsms')) {
    exit;
}

$this->CreatePermission('Manage MAS_BreakingLive', 'Manage MAS Breaking and Live homepage tickers');

$this->SetPreference('show_breaking', '1');
$this->SetPreference('show_live', '1');
$this->SetPreference('scroll_breaking', '1');
$this->SetPreference('scroll_live', '1');
$this->SetPreference(
    'breaking_text',
    'Apple announces AI breakthrough • Microsoft unveils new Surface • Google advances quantum computing'
);
$this->SetPreference(
    'live_text',
    'Markets react to AI news • SpaceX launch schedule • Smartphone pre-orders • Semiconductor earnings'
);

$this->SetPreference('breaking_merge_mode', 'manual_only');
$this->SetPreference('live_merge_mode', 'manual_only');
$this->SetPreference('breaking_article_order', '');
$this->SetPreference('live_article_order', '');
$this->SetPreference('bxslider_pause_ms', '10000');
$this->SetPreference('marquee_breaking_s', '48');
$this->SetPreference('marquee_live_s', '56');
$this->SetPreference('breaking_link_news', '1');
$this->SetPreference('breaking_link_external', '0');
$this->SetPreference('live_link_news', '1');
$this->SetPreference('live_link_external', '0');
$this->SetPreference('hidedonationstab', '');
$this->SetPreference('mas_bl_admin_section', 'extensions');

$modRoot = dirname(__FILE__);
$permLib = cms_join_path($modRoot, 'lib', 'mas_bl_fs_normalize.php');
if (is_file($permLib)) {
    require_once $permLib;
    if (function_exists('mas_breakinglive_normalize_module_permissions')) {
        mas_breakinglive_normalize_module_permissions($modRoot);
    }
    if (function_exists('mas_breakinglive_ensure_public_asset_modes')) {
        mas_breakinglive_ensure_public_asset_modes($modRoot);
    }
}

require_once dirname(__DIR__) . '/MAS_Common/lib/mas_admin_ui.php';
Mas_Admin_Ui::ensureIconGif($this);
Mas_Admin_Ui::ensureBanner($this);
if (function_exists('mas_breakinglive_ensure_public_asset_modes')) {
    mas_breakinglive_ensure_public_asset_modes($modRoot);
}

if (function_exists('audit')) {
    audit(0, $this->GetName(), 'MAS_BreakingLive ' . $this->GetVersion() . ' installed');
}
