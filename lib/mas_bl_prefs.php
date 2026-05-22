<?php
/**
 * MAS_BreakingLive preference helpers (empty DB values treated as defaults).
 */
if (!function_exists('cmsms')) {
    exit;
}

/**
 * @param CMSModule $mod
 */
function mas_bl_pref_enabled($mod, $name, $defaultOn = true)
{
    $v = trim((string) $mod->GetPreference($name, ''));
    if ($v === '1') {
        return true;
    }
    if ($v === '0') {
        return false;
    }
    if ($v === '') {
        return $defaultOn;
    }

    return $defaultOn;
}

/**
 * @param CMSModule $mod
 */
function mas_bl_pref_string($mod, $name, $default = '')
{
    $v = (string) $mod->GetPreference($name, $default);
    if ($v === '' && $default !== '') {
        return (string) $default;
    }

    return $v;
}

/**
 * Clear CMS template caches so homepage ticker prefs apply immediately.
 */
function mas_bl_clear_site_caches()
{
    if (!function_exists('cmsms')) {
        return;
    }
    try {
        if (class_exists('CmsTemplateCache')) {
            CmsTemplateCache::clear_cache();
        }
        CmsApp::get_instance()->clear_cached_files(0);
    } catch (Exception $e) {
        /* ignore */
    }
}
