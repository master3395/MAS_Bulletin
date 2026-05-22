<?php
/**
 * Normalize module tree modes so CMSMS Module Manager can uninstall.
 * Public assets (images, js, css) must be world-readable (0644) for HTTP access.
 *
 * @param string $root Absolute or relative module root directory
 * @return void
 */
function mas_bulletin_normalize_module_permissions($root)
{
    if (!is_string($root) || $root === '') {
        return;
    }
    $root = realpath($root);
    if ($root === false || !is_dir($root)) {
        return;
    }

    $publicExt = array(
        'png' => true,
        'gif' => true,
        'jpg' => true,
        'jpeg' => true,
        'webp' => true,
        'svg' => true,
        'css' => true,
        'js' => true,
        'mjs' => true,
        'woff' => true,
        'woff2' => true,
        'ttf' => true,
        'eot' => true,
        'map' => true,
    );

    $flags = FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS;
    try {
        $dirIt = new RecursiveDirectoryIterator($root, $flags);
        $iter = new RecursiveIteratorIterator($dirIt, RecursiveIteratorIterator::SELF_FIRST);
    } catch (Exception $e) {
        return;
    }

    foreach ($iter as $info) {
        /** @var SplFileInfo $info */
        $path = $info->getPathname();
        if ($info->isLink()) {
            continue;
        }
        if ($info->isDir()) {
            @chmod($path, 0771);
            continue;
        }
        if (!$info->isFile()) {
            continue;
        }
        $ext = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));
        $mode = isset($publicExt[$ext]) ? 0644 : 0660;
        @chmod($path, $mode);
    }
    @chmod($root, 0771);
}

/**
 * Fix modes on files that must be served over HTTP (after upgrade or manual chmod).
 *
 * @param string $root Module root
 * @return void
 */
function mas_bulletin_ensure_public_asset_modes($root)
{
    if (!is_string($root) || $root === '') {
        return;
    }
    $root = realpath($root);
    if ($root === false || !is_dir($root)) {
        return;
    }
    $paths = array(
        $root . '/images/banner.png',
        $root . '/images/icon.gif',
        $root . '/lib/mas_bl_marquee.js',
    );
    foreach ($paths as $path) {
        if (is_file($path)) {
            @chmod($path, 0644);
        }
    }
}
