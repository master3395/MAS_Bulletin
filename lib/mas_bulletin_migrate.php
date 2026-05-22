<?php
/**
 * Migrate site prefs and group permissions from legacy MAS_BreakingLive.
 *
 * @package MAS_Bulletin
 */

if (!function_exists('cmsms')) {
    exit;
}

/**
 * @return bool True if legacy MAS_BreakingLive data was found and migrated.
 */
function mas_bulletin_migrate_from_breaking_live(CMSModule $mod)
{
    $legacyModule = 'MAS_BreakingLive';
    $legacyPerm = 'Manage MAS_BreakingLive';
    $newPerm = 'Manage MAS_Bulletin';
    $prefix = $legacyModule . '_mapi_pref_';
    $migrated = false;

    if (!function_exists('cms_siteprefs') || !method_exists('cms_siteprefs', 'list_by_prefix')) {
        return false;
    }

    $prefs = cms_siteprefs::list_by_prefix($prefix);
    if (is_array($prefs) && count($prefs) > 0) {
        foreach ($prefs as $fullKey => $value) {
            if (!is_string($fullKey) || strpos($fullKey, $prefix) !== 0) {
                continue;
            }
            $name = substr($fullKey, strlen($prefix));
            if ($name === '' || $name === false) {
                continue;
            }
            if (trim((string) $mod->GetPreference($name, '')) === '') {
                $mod->SetPreference($name, $value);
            }
            $migrated = true;
        }
    }

    $mod->CreatePermission($newPerm, 'Manage MAS Bulletin homepage tickers');

    if (class_exists('CmsPermission') && class_exists('GroupOperations')) {
        $oldPermId = (int) CmsPermission::get_perm_id($legacyPerm);
        if ($oldPermId > 0) {
            $db = cmsms()->GetDb();
            $groups = $db->GetCol(
                'SELECT group_id FROM ' . CMS_DB_PREFIX . 'group_perms WHERE permission_id = ?',
                array($oldPermId)
            );
            if (is_array($groups) && count($groups) > 0) {
                $gops = GroupOperations::get_instance();
                foreach ($groups as $groupId) {
                    $gid = (int) $groupId;
                    if ($gid > 1) {
                        $gops->GrantPermission($gid, $newPerm);
                    }
                }
                $migrated = true;
            }
        }
    }

    return $migrated;
}
