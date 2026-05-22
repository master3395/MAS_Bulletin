<?php
if (!function_exists('cmsms')) {
    exit;
}

if (!$this->VisibleToAdminUser()) {
    return;
}

require_once dirname(__DIR__) . '/MAS_Common/lib/mas_admin_ui.php';
Mas_Admin_Ui::renderDonationsTab($this, $id, $returnid, 'defaultadmin', 'defaultadmin');
