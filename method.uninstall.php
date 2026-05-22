<?php
if (!function_exists('cmsms')) {
    exit;
}

$this->RemovePermission('Manage MAS_BreakingLive');

$this->RemovePreference('show_breaking');
$this->RemovePreference('show_live');
$this->RemovePreference('scroll_breaking');
$this->RemovePreference('scroll_live');
$this->RemovePreference('breaking_text');
$this->RemovePreference('live_text');
$this->RemovePreference('breaking_merge_mode');
$this->RemovePreference('live_merge_mode');
$this->RemovePreference('breaking_article_order');
$this->RemovePreference('live_article_order');
$this->RemovePreference('bxslider_pause_ms');
$this->RemovePreference('marquee_breaking_s');
$this->RemovePreference('marquee_live_s');
$this->RemovePreference('hidedonationstab');
$this->RemovePreference('mas_bl_admin_section');
