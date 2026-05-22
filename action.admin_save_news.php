<?php
if (!function_exists('cmsms')) {
    exit;
}

if (!$this->CheckPermission('Manage MAS_Bulletin')) {
    return $this->DisplayErrorPage($id, $params, $returnid, $this->Lang('accessdenied'));
}

require_once __DIR__ . '/lib/class.MasBulletinTicker.php';

$pick = static function (array $params, string $suffix, string $default = ''): string {
    if (isset($params[$suffix])) {
        return (string) $params[$suffix];
    }
    foreach ($params as $k => $v) {
        if (is_string($k) && substr($k, -strlen($suffix)) === $suffix) {
            return (string) $v;
        }
    }

    return $default;
};

$breakingOrder = MasBulletinTicker::parseIdOrder($pick($params, 'breaking_article_order', ''));
$liveOrder = MasBulletinTicker::parseIdOrder($pick($params, 'live_article_order', ''));

$this->SetPreference('breaking_article_order', MasBulletinTicker::idsToOrderString($breakingOrder));
$this->SetPreference('live_article_order', MasBulletinTicker::idsToOrderString($liveOrder));

$this->SetPreference(
    'breaking_merge_mode',
    MasBulletinTicker::normalizeMergeMode($pick($params, 'breaking_merge_mode', 'manual_only'))
);
$this->SetPreference(
    'live_merge_mode',
    MasBulletinTicker::normalizeMergeMode($pick($params, 'live_merge_mode', 'manual_only'))
);
$this->SetPreference('breaking_link_news', !empty($params['breaking_link_news']) ? '1' : '0');
$this->SetPreference('breaking_link_external', !empty($params['breaking_link_external']) ? '1' : '0');
$this->SetPreference('live_link_news', !empty($params['live_link_news']) ? '1' : '0');
$this->SetPreference('live_link_external', !empty($params['live_link_external']) ? '1' : '0');

if (function_exists('audit')) {
    audit(0, $this->GetName(), 'MAS_Bulletin news ticker order updated');
}

$this->Redirect($id, 'defaultadmin', $returnid, ['msg' => 'saved', 'activetab' => 'news']);
