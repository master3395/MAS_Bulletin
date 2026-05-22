<?php
if (!function_exists('cmsms')) {
    exit;
}

if (!$this->VisibleToAdminUser()) {
    return $this->DisplayErrorPage($id, $params, $returnid, $this->Lang('accessdenied'));
}

if (isset($params['hidedonationssubmit']) && $this->CheckPermission('Manage MAS_Bulletin')) {
    $this->SetPreference('hidedonationstab', $this->GetVersion());
}

if (!empty($params['msg'])) {
    echo $this->ShowMessage($this->Lang((string) $params['msg']));
}
if (!empty($params['err'])) {
    echo $this->ShowErrors($this->Lang((string) $params['err']));
}

require_once dirname(__DIR__) . '/MAS_Common/lib/mas_admin_ui.php';
require_once __DIR__ . '/lib/mas_bl_fs_normalize.php';

Mas_Admin_Ui::ensureIconGif($this);
Mas_Admin_Ui::ensureBanner($this);
if (function_exists('mas_bulletin_ensure_public_asset_modes')) {
    mas_bulletin_ensure_public_asset_modes(__DIR__);
}

$smarty = cmsms()->GetSmarty();
$smarty->assign('mas_bl_mod', $this);
$smarty->assign('mod', $this);
Mas_Admin_Ui::assignBranding($this, $smarty);
$smarty->assign('mas_bl_changelog_html', $this->GetChangeLog());

$activetab = isset($params['activetab']) ? (string) $params['activetab'] : 'settings';

echo $this->StartTabHeaders();
if ($this->CheckPermission('Manage MAS_Bulletin')) {
    echo $this->SetTabHeader('settings', $this->Lang('tab_settings'), $activetab === 'settings');
    echo $this->SetTabHeader('news', $this->Lang('tab_news'), $activetab === 'news');
}
echo $this->SetTabHeader('help', $this->Lang('tab_help'), $activetab === 'help');
echo $this->SetTabHeader('about', $this->Lang('tab_about'), $activetab === 'about');
echo $this->SetTabHeader('changelog', $this->Lang('tab_changelog'), $activetab === 'changelog');
if ($this->ShowDonationsTab()) {
    echo $this->SetTabHeader('donations', $this->Lang('tab_donations'), $activetab === 'donations');
}
echo $this->EndTabHeaders();

echo $this->StartTabContent();

if ($this->CheckPermission('Manage MAS_Bulletin')) {
    echo $this->StartTab('settings', $params);
    include __DIR__ . '/function.admin_settings.php';
    echo $this->EndTab();

    echo $this->StartTab('news', $params);
    include __DIR__ . '/function.admin_news.php';
    echo $this->EndTab();
}

echo $this->StartTab('help', $params);
echo $this->ProcessTemplate('admin_help.tpl');
echo $this->EndTab();

echo $this->StartTab('about', $params);
echo $this->ProcessTemplate('admin_about.tpl');
echo $this->EndTab();

echo $this->StartTab('changelog', $params);
echo $this->ProcessTemplate('admin_changelog.tpl');
echo $this->EndTab();

if ($this->ShowDonationsTab()) {
    echo $this->StartTab('donations', $params);
    include __DIR__ . '/function.donations.php';
    echo $this->EndTab();
}

echo $this->EndTabContent();
