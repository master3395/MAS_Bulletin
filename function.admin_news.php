<?php
if (!function_exists('cmsms')) {
    exit;
}

require_once __DIR__ . '/lib/class.MasBulletinTicker.php';

$breakingMerge = MasBulletinTicker::normalizeMergeMode((string) $this->GetPreference('breaking_merge_mode', 'manual_only'));
$liveMerge = MasBulletinTicker::normalizeMergeMode((string) $this->GetPreference('live_merge_mode', 'manual_only'));

$breakingOrder = MasBulletinTicker::parseIdOrder((string) $this->GetPreference('breaking_article_order', ''));
$liveOrder = MasBulletinTicker::parseIdOrder((string) $this->GetPreference('live_article_order', ''));

$breakingLinkNews = $this->GetPreference('breaking_link_news', '1') === '1';
$breakingLinkExternal = $this->GetPreference('breaking_link_external', '0') === '1';
$liveLinkNews = $this->GetPreference('live_link_news', '1') === '1';
$liveLinkExternal = $this->GetPreference('live_link_external', '0') === '1';

$mergeItems = [
    MasBulletinTicker::MERGE_MANUAL_ONLY => $this->Lang('merge_manual_only'),
    MasBulletinTicker::MERGE_NEWS_ONLY => $this->Lang('merge_news_only'),
    MasBulletinTicker::MERGE_NEWS_FIRST => $this->Lang('merge_news_first'),
    MasBulletinTicker::MERGE_MANUAL_FIRST => $this->Lang('merge_manual_first'),
];

$smarty->assign('mas_bl_news_form_start', $this->CreateFormStart($id, 'admin_save_news', $returnid));
$smarty->assign('mas_bl_news_form_end', $this->CreateFormEnd());
$smarty->assign('mas_bl_news_submit', $this->CreateInputSubmit($id, 'submit', $this->Lang('submit')));

$smarty->assign(
    'mas_bl_breaking_merge_select',
    $this->CreateInputDropdown($id, 'breaking_merge_mode', $mergeItems, -1, $breakingMerge)
);
$smarty->assign(
    'mas_bl_live_merge_select',
    $this->CreateInputDropdown($id, 'live_merge_mode', $mergeItems, -1, $liveMerge)
);

$smarty->assign('mas_bl_breaking_order_value', MasBulletinTicker::idsToOrderString($breakingOrder));
$smarty->assign('mas_bl_live_order_value', MasBulletinTicker::idsToOrderString($liveOrder));

$titlesBreaking = [];
foreach ($breakingOrder as $bid) {
    $titlesBreaking[$bid] = '#' . $bid;
}
$titlesLive = [];
foreach ($liveOrder as $lid) {
    $titlesLive[$lid] = '#' . $lid;
}

$news = cms_utils::get_module('News');
if ($news && ($breakingOrder !== [] || $liveOrder !== [])) {
    $opsPath = cms_join_path($news->GetModulePath(), 'lib', 'class.news_ops.php');
    if (is_file($opsPath)) {
        require_once $opsPath;
        foreach (array_unique(array_merge($breakingOrder, $liveOrder)) as $nid) {
            $a = news_ops::get_article_by_id((int) $nid, true, false);
            $t = is_object($a) ? trim(strip_tags((string) $a->title)) : '';
            if ($t !== '') {
                if (in_array($nid, $breakingOrder, true)) {
                    $titlesBreaking[$nid] = $t;
                }
                if (in_array($nid, $liveOrder, true)) {
                    $titlesLive[$nid] = $t;
                }
            }
        }
    }
}

$smarty->assign('mas_bl_breaking_rows', $breakingOrder);
$smarty->assign('mas_bl_live_rows', $liveOrder);
$smarty->assign('mas_bl_breaking_titles', $titlesBreaking);
$smarty->assign('mas_bl_live_titles', $titlesLive);

$picker = MasBulletinTicker::recentArticlesForPicker(80);
$smarty->assign('mas_bl_picker_articles', $picker);
$smarty->assign('mas_bl_news_installed', $news ? 1 : 0);

$smarty->assign('mas_bl_news_title', $this->Lang('tab_news'));
$smarty->assign('mas_bl_news_intro', $this->Lang('news_intro'));
$smarty->assign('mas_bl_label_merge', $this->Lang('label_merge_mode'));
$smarty->assign('mas_bl_opt_pick', $this->Lang('opt_pick_article'));
$smarty->assign('mas_bl_btn_add', $this->Lang('btn_add_article'));
$smarty->assign('mas_bl_legend_breaking', $this->Lang('label_breaking'));
$smarty->assign('mas_bl_legend_live', $this->Lang('label_live'));
$smarty->assign('mas_bl_legend_links', $this->Lang('legend_links'));
$smarty->assign('mas_bl_help_links_news', $this->Lang('help_links_news'));
$smarty->assign(
    'mas_bl_cb_breaking_link_news',
    $this->CreateInputCheckbox($id, 'breaking_link_news', '1', $breakingLinkNews ? '1' : '0') . ' ' . $this->Lang('label_breaking_link_news')
);
$smarty->assign(
    'mas_bl_cb_breaking_link_external',
    $this->CreateInputCheckbox($id, 'breaking_link_external', '1', $breakingLinkExternal ? '1' : '0') . ' ' . $this->Lang('label_breaking_link_external')
);
$smarty->assign(
    'mas_bl_cb_live_link_news',
    $this->CreateInputCheckbox($id, 'live_link_news', '1', $liveLinkNews ? '1' : '0') . ' ' . $this->Lang('label_live_link_news')
);
$smarty->assign(
    'mas_bl_cb_live_link_external',
    $this->CreateInputCheckbox($id, 'live_link_external', '1', $liveLinkExternal ? '1' : '0') . ' ' . $this->Lang('label_live_link_external')
);

$smarty->assign(
    'mas_bl_hidden_breaking_order',
    $this->CreateInputHidden($id, 'breaking_article_order', MasBulletinTicker::idsToOrderString($breakingOrder))
);
$smarty->assign(
    'mas_bl_hidden_live_order',
    $this->CreateInputHidden($id, 'live_article_order', MasBulletinTicker::idsToOrderString($liveOrder))
);

$smarty->assign('mas_bl_breaking_ids_json', json_encode($breakingOrder, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
$smarty->assign('mas_bl_live_ids_json', json_encode($liveOrder, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
$smarty->assign('mas_bl_breaking_titles_json', json_encode($titlesBreaking, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
$smarty->assign('mas_bl_live_titles_json', json_encode($titlesLive, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

echo $this->ProcessTemplate('admin_news.tpl');
