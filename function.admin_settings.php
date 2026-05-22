<?php
if (!function_exists('cmsms')) {
    exit;
}

require_once __DIR__ . '/lib/mas_bl_prefs.php';
require_once __DIR__ . '/lib/mas_bl_admin_form.php';

$breaking = mas_bl_pref_string(
    $this,
    'breaking_text',
    'Apple announces AI breakthrough • Microsoft unveils new Surface • Google advances quantum computing'
);
$live = mas_bl_pref_string(
    $this,
    'live_text',
    'Markets react to AI news • SpaceX launch schedule • Smartphone pre-orders • Semiconductor earnings'
);
$showBreaking = mas_bl_pref_is_on($this, 'show_breaking');
$showLive = mas_bl_pref_is_on($this, 'show_live');
$scrollBreaking = mas_bl_pref_enabled($this, 'scroll_breaking', true);
$scrollLive = mas_bl_pref_enabled($this, 'scroll_live', true);
$breakingLinkNews = mas_bl_pref_enabled($this, 'breaking_link_news', true);
$breakingLinkExternal = mas_bl_pref_enabled($this, 'breaking_link_external', false);
$liveLinkNews = mas_bl_pref_enabled($this, 'live_link_news', true);
$liveLinkExternal = mas_bl_pref_enabled($this, 'live_link_external', false);
$bxsliderPause = (int) $this->GetPreference('bxslider_pause_ms', '10000');
if ($bxsliderPause < 2000) {
    $bxsliderPause = 2000;
}
if ($bxsliderPause > 120000) {
    $bxsliderPause = 120000;
}
$marqueeBreaking = (float) $this->GetPreference('marquee_breaking_s', '48');
if ($marqueeBreaking < 8) {
    $marqueeBreaking = 8;
}
if ($marqueeBreaking > 180) {
    $marqueeBreaking = 180;
}
$marqueeLive = (float) $this->GetPreference('marquee_live_s', '56');
if ($marqueeLive < 8) {
    $marqueeLive = 8;
}
if ($marqueeLive > 180) {
    $marqueeLive = 180;
}

$adminSection = $this->GetAdminSection();
$adminSectionItems = [
    lang('main') => 'main',
    lang('content') => 'content',
    lang('layout') => 'layout',
    lang('usersgroups') => 'usersgroups',
    lang('extensions') => 'extensions',
    lang('admin') => 'siteadmin',
    lang('myprefs') => 'myprefs',
];

$smarty->assign('mas_bl_form_start', $this->CreateFormStart($id, 'admin_save', $returnid));
$smarty->assign('mas_bl_form_end', $this->CreateFormEnd());
$smarty->assign('mas_bl_submit', $this->CreateInputSubmit($id, 'submit', $this->Lang('submit')));

$smarty->assign(
    'mas_bl_breaking_area',
    $this->CreateTextArea(false, $id, $breaking, 'breaking_text', '', '', '', '', '100', '8', '', '', 'class="mas-bl-textarea" style="width:99%;max-width:900px"')
);
$smarty->assign(
    'mas_bl_live_area',
    $this->CreateTextArea(false, $id, $live, 'live_text', '', '', '', '', '100', '8', '', '', 'class="mas-bl-textarea" style="width:99%;max-width:900px"')
);

$smarty->assign('mas_bl_cb_show_breaking', mas_bl_render_checkbox($this, $id, 'show_breaking', 'label_show_breaking'));
$smarty->assign('mas_bl_cb_show_live', mas_bl_render_checkbox($this, $id, 'show_live', 'label_show_live'));
$smarty->assign('mas_bl_cb_scroll_breaking', mas_bl_render_checkbox($this, $id, 'scroll_breaking', 'label_scroll_breaking'));
$smarty->assign('mas_bl_cb_scroll_live', mas_bl_render_checkbox($this, $id, 'scroll_live', 'label_scroll_live'));
$smarty->assign('mas_bl_legend_links', $this->Lang('legend_links'));
$smarty->assign('mas_bl_help_links', $this->Lang('help_links'));
$smarty->assign('mas_bl_cb_breaking_link_news', mas_bl_render_checkbox($this, $id, 'breaking_link_news', 'label_breaking_link_news'));
$smarty->assign('mas_bl_cb_breaking_link_external', mas_bl_render_checkbox($this, $id, 'breaking_link_external', 'label_breaking_link_external'));
$smarty->assign('mas_bl_cb_live_link_news', mas_bl_render_checkbox($this, $id, 'live_link_news', 'label_live_link_news'));
$smarty->assign('mas_bl_cb_live_link_external', mas_bl_render_checkbox($this, $id, 'live_link_external', 'label_live_link_external'));

$smarty->assign('mas_bl_title', $this->Lang('settings_title'));
$smarty->assign('mas_bl_hint', $this->Lang('hint_lines'));
$smarty->assign('mas_bl_legend_breaking', $this->Lang('label_breaking'));
$smarty->assign('mas_bl_legend_live', $this->Lang('label_live'));
$smarty->assign('mas_bl_legend_display', $this->Lang('legend_display'));
$smarty->assign('mas_bl_label_bxslider_pause', $this->Lang('label_bxslider_pause'));
$smarty->assign('mas_bl_input_bxslider_pause', $this->CreateInputText($id, 'bxslider_pause_ms', (string) $bxsliderPause, 8, 8));
$smarty->assign('mas_bl_label_marquee_breaking', $this->Lang('label_marquee_breaking'));
$smarty->assign('mas_bl_input_marquee_breaking', $this->CreateInputText($id, 'marquee_breaking_s', (string) $marqueeBreaking, 6, 6));
$smarty->assign('mas_bl_label_marquee_live', $this->Lang('label_marquee_live'));
$smarty->assign('mas_bl_input_marquee_live', $this->CreateInputText($id, 'marquee_live_s', (string) $marqueeLive, 6, 6));

$smarty->assign('mas_bl_legend_admin', $this->Lang('title_admin_section'));
$smarty->assign('mas_bl_help_admin', $this->Lang('help_admin_section'));
$smarty->assign(
    'mas_bl_input_admin_section',
    $this->CreateInputDropdown($id, 'mas_bl_admin_section', $adminSectionItems, -1, $adminSection)
);

$smarty->assign('mas_bl_codemirror_css', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css');
$smarty->assign('mas_bl_codemirror_js', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js');

echo $this->ProcessTemplate('admin_settings.tpl');
