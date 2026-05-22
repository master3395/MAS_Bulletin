<?php
if (!function_exists('cmsms')) {
    exit;
}

$from = isset($oldversion) ? (string) $oldversion : '0';

$migrateLib = cms_join_path(dirname(__FILE__), 'lib', 'mas_bulletin_migrate.php');
if (is_file($migrateLib)) {
    require_once $migrateLib;
    if (function_exists('mas_bulletin_migrate_from_breaking_live')) {
        mas_bulletin_migrate_from_breaking_live($this);
    }
}

if (version_compare($from, '1.2.3', '<')) {
    foreach (['scroll_breaking', 'scroll_live'] as $pref) {
        if (trim((string) $this->GetPreference($pref, '')) === '') {
            $this->SetPreference($pref, '1');
        }
    }
    foreach (['show_breaking', 'show_live'] as $pref) {
        if (trim((string) $this->GetPreference($pref, '')) === '') {
            $this->SetPreference($pref, '1');
        }
    }
    if (trim((string) $this->GetPreference('breaking_text', '')) === '') {
        $this->SetPreference(
            'breaking_text',
            'Apple announces AI breakthrough • Microsoft unveils new Surface • Google advances quantum computing'
        );
    }
    if (trim((string) $this->GetPreference('live_text', '')) === '') {
        $this->SetPreference(
            'live_text',
            'Markets react to AI news • SpaceX launch schedule • Smartphone pre-orders • Semiconductor earnings'
        );
    }
}

if (version_compare($from, '1.2.0', '<')) {
    if ($this->GetPreference('breaking_link_news', '') === '') {
        $this->SetPreference('breaking_link_news', '1');
    }
    if ($this->GetPreference('breaking_link_external', '') === '') {
        $this->SetPreference('breaking_link_external', '0');
    }
    if ($this->GetPreference('live_link_news', '') === '') {
        $this->SetPreference('live_link_news', '1');
    }
    if ($this->GetPreference('live_link_external', '') === '') {
        $this->SetPreference('live_link_external', '0');
    }
}

if (version_compare($from, '1.1.0', '<')) {
    $this->SetPreference('breaking_merge_mode', 'manual_only');
    $this->SetPreference('live_merge_mode', 'manual_only');
    $this->SetPreference('breaking_article_order', '');
    $this->SetPreference('live_article_order', '');
    $this->SetPreference('bxslider_pause_ms', '10000');
    $this->SetPreference('marquee_breaking_s', '48');
    $this->SetPreference('marquee_live_s', '56');
    $sec = (string) $this->GetPreference('mas_bl_admin_section', '');
    if ($sec === '') {
        $this->SetPreference('mas_bl_admin_section', 'extensions');
    }
}

$modRoot = dirname(__FILE__);
$permLib = cms_join_path($modRoot, 'lib', 'mas_bl_fs_normalize.php');
if (is_file($permLib)) {
    require_once $permLib;
    if (function_exists('mas_bulletin_normalize_module_permissions')) {
        mas_bulletin_normalize_module_permissions($modRoot);
    }
    if (function_exists('mas_bulletin_ensure_public_asset_modes')) {
        mas_bulletin_ensure_public_asset_modes($modRoot);
    }
}

if (function_exists('audit')) {
    audit(0, $this->GetName(), 'MAS_Bulletin upgraded to ' . $this->GetVersion() . ' (renamed from MAS_BreakingLive)');
}
