<?php
if (!function_exists('cmsms')) {
    exit;
}

require_once __DIR__ . '/lib/mas_bl_prefs.php';
require_once __DIR__ . '/lib/mas_bl_admin_form.php';
require_once __DIR__ . '/lib/class.MasBreakingLiveTicker.php';
require_once __DIR__ . '/lib/class.MasBreakingLiveFormatter.php';

$smarty = cmsms()->GetSmarty();

$showBreaking = mas_bl_pref_is_on($this, 'show_breaking');
$showLive = mas_bl_pref_is_on($this, 'show_live');
$scrollBreaking = mas_bl_pref_enabled($this, 'scroll_breaking', true);
$scrollLive = mas_bl_pref_enabled($this, 'scroll_live', true);

$breakingMerge = MasBreakingLiveTicker::normalizeMergeMode((string) $this->GetPreference('breaking_merge_mode', 'manual_only'));
$liveMerge = MasBreakingLiveTicker::normalizeMergeMode((string) $this->GetPreference('live_merge_mode', 'manual_only'));

$breakingIds = MasBreakingLiveTicker::parseIdOrder((string) $this->GetPreference('breaking_article_order', ''));
$liveIds = MasBreakingLiveTicker::parseIdOrder((string) $this->GetPreference('live_article_order', ''));

$breakingLinkNews = mas_bl_pref_enabled($this, 'breaking_link_news', true);
$breakingLinkExternal = mas_bl_pref_enabled($this, 'breaking_link_external', false);
$liveLinkNews = mas_bl_pref_enabled($this, 'live_link_news', true);
$liveLinkExternal = mas_bl_pref_enabled($this, 'live_link_external', false);

$breakingHtml = MasBreakingLiveFormatter::buildLineHtml(
    $breakingMerge,
    mas_bl_pref_string(
        $this,
        'breaking_text',
        'Apple announces AI breakthrough • Microsoft unveils new Surface • Google advances quantum computing'
    ),
    $breakingIds,
    $breakingLinkNews,
    $breakingLinkExternal
);
$liveHtml = MasBreakingLiveFormatter::buildLineHtml(
    $liveMerge,
    mas_bl_pref_string(
        $this,
        'live_text',
        'Markets react to AI news • SpaceX launch schedule • Smartphone pre-orders • Semiconductor earnings'
    ),
    $liveIds,
    $liveLinkNews,
    $liveLinkExternal
);

$breaking = MasBreakingLiveFormatter::truncateHtml($breakingHtml, 4000);
$live = MasBreakingLiveFormatter::truncateHtml($liveHtml, 4000);

$pauseMs = (int) $this->GetPreference('bxslider_pause_ms', '10000');
if ($pauseMs < 2000) {
    $pauseMs = 2000;
}
if ($pauseMs > 120000) {
    $pauseMs = 120000;
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

$smarty->assign('mas_bl_show_breaking', $showBreaking);
$smarty->assign('mas_bl_show_live', $showLive);
$smarty->assign('mas_bl_scroll_breaking', $scrollBreaking);
$smarty->assign('mas_bl_scroll_live', $scrollLive);
$smarty->assign('mas_bl_breaking', $breaking);
$smarty->assign('mas_bl_live', $live);
$smarty->assign('mas_bl_bxslider_pause_ms', $pauseMs);
$smarty->assign('mas_bl_marquee_breaking_s', $marqueeBreaking);
$smarty->assign('mas_bl_marquee_live_s', $marqueeLive);
$smarty->assign('mas_bl_marquee_breaking_s_fmt', sprintf('%.2f', $marqueeBreaking));
$smarty->assign('mas_bl_marquee_live_s_fmt', sprintf('%.2f', $marqueeLive));

echo $this->ProcessTemplate('ticker.tpl');
