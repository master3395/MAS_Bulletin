<?php
if (!function_exists('cmsms')) {
    exit;
}

require_once __DIR__ . '/lib/mas_bl_prefs.php';
require_once __DIR__ . '/lib/mas_bl_admin_form.php';

if (!$this->CheckPermission('Manage MAS_Bulletin')) {
    return $this->DisplayErrorPage($id, $params, $returnid, $this->Lang('accessdenied'));
}

$enc = 'UTF-8';
$config = cmsms()->GetConfig();
if (!empty($config['default_encoding'])) {
    $enc = (string) $config['default_encoding'];
}

$trunc = static function ($str, $max, $encoding) {
    $str = (string) $str;
    if (function_exists('mb_substr')) {
        return mb_substr($str, 0, $max, $encoding);
    }

    return substr($str, 0, $max);
};

$breaking = isset($params['breaking_text']) ? $trunc($params['breaking_text'], 2000, $enc) : '';
$live = isset($params['live_text']) ? $trunc($params['live_text'], 2000, $enc) : '';

$this->SetPreference('breaking_text', $breaking);
$this->SetPreference('live_text', $live);

mas_bl_save_checkbox($this, $id, $params, 'show_breaking');
mas_bl_save_checkbox($this, $id, $params, 'show_live');
mas_bl_save_checkbox($this, $id, $params, 'scroll_breaking');
mas_bl_save_checkbox($this, $id, $params, 'scroll_live');
mas_bl_save_checkbox($this, $id, $params, 'breaking_link_news');
mas_bl_save_checkbox($this, $id, $params, 'breaking_link_external');
mas_bl_save_checkbox($this, $id, $params, 'live_link_news');
mas_bl_save_checkbox($this, $id, $params, 'live_link_external');

$pause = isset($params['bxslider_pause_ms']) ? (int) $params['bxslider_pause_ms'] : 10000;
if ($pause < 2000) {
    $pause = 2000;
}
if ($pause > 120000) {
    $pause = 120000;
}
$this->SetPreference('bxslider_pause_ms', (string) $pause);

$mb = isset($params['marquee_breaking_s']) ? (float) $params['marquee_breaking_s'] : 48.0;
if ($mb < 8) {
    $mb = 8;
}
if ($mb > 180) {
    $mb = 180;
}
$this->SetPreference('marquee_breaking_s', (string) $mb);

$ml = isset($params['marquee_live_s']) ? (float) $params['marquee_live_s'] : 56.0;
if ($ml < 8) {
    $ml = 8;
}
if ($ml > 180) {
    $ml = 180;
}
$this->SetPreference('marquee_live_s', (string) $ml);

$allowed = $this->GetAllowedAdminSectionKeys();
$sec = isset($params['mas_bl_admin_section']) ? (string) $params['mas_bl_admin_section'] : 'extensions';
if (!in_array($sec, $allowed, true)) {
    $sec = 'extensions';
}
$this->SetPreference('mas_bl_admin_section', $sec);

mas_bl_clear_site_caches();

if (function_exists('audit')) {
    audit(
        0,
        $this->GetName(),
        'prefs saved show_breaking=' . $this->GetPreference('show_breaking', '0')
            . ' show_live=' . $this->GetPreference('show_live', '0')
    );
}

$this->Redirect($id, 'defaultadmin', $returnid, ['msg' => 'saved', 'activetab' => 'settings']);
