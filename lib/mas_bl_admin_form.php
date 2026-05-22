<?php
/**
 * Admin form helpers for MAS_BreakingLive (checkbox save + display).
 */
if (!function_exists('cmsms')) {
    exit;
}

/**
 * @param CMSModule $mod
 */
function mas_bl_pref_is_on($mod, $name)
{
    return (string) $mod->GetPreference($name, '0') === '1';
}

/**
 * Hidden 0 + checkbox 1 so unchecked values are posted to admin_save.
 *
 * @param CMSModule $mod
 */
function mas_bl_render_checkbox($mod, $id, $name, $labelLangKey)
{
    $id = (string) $id;
    $name = (string) $name;
    $checked = mas_bl_pref_is_on($mod, $name);
    $hidden = '<input type="hidden" name="' . cms_htmlentities($id . $name) . '" value="0" />' . "\n";
    $box = $mod->CreateInputCheckbox($id, $name, '1', $checked ? '1' : '0');

    return $hidden . $box . ' ' . $mod->Lang($labelLangKey);
}

/**
 * @param CMSModule $mod
 * @param array<string, mixed> $params
 */
function mas_bl_save_checkbox($mod, $id, array $params, $name)
{
    $raw = mas_bl_read_request_param($params, $id, $name);
    $on = ($raw === '1' || $raw === 1 || $raw === true || $raw === 'on' || $raw === 'yes');
    if (is_array($raw)) {
        $on = in_array('1', $raw, true) || in_array(1, $raw, true);
    }
    $mod->SetPreference($name, $on ? '1' : '0');
}

/**
 * @param array<string, mixed> $params
 * @return mixed
 */
function mas_bl_read_request_param(array $params, $id, $name)
{
    if (array_key_exists($name, $params)) {
        return $params[$name];
    }
    $prefixed = (string) $id . (string) $name;
    if (array_key_exists($prefixed, $params)) {
        return $params[$prefixed];
    }
    if (isset($_REQUEST[$prefixed])) {
        return $_REQUEST[$prefixed];
    }
    foreach ($params as $key => $value) {
        if (is_string($key) && substr($key, -strlen($name)) === $name) {
            return $value;
        }
    }

    return null;
}
