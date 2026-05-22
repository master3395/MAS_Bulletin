<?php
/**
 * Build safe HTML for Breaking / Live ticker lines (optional links).
 */
declare(strict_types=1);

if (!defined('CMS_VERSION')) {
    exit('Direct access not allowed');
}

require_once __DIR__ . '/class.MasBreakingLiveTicker.php';

final class MasBreakingLiveFormatter
{
    private const SEP_PLAIN = ' • ';

    /**
     * @param list<int> $articleIds
     */
    public static function buildLineHtml(
        string $mergeMode,
        string $manual,
        array $articleIds,
        bool $linkNews,
        bool $linkExternal
    ): string {
        $manualParts = self::formatManualParts($manual, $linkExternal);
        $newsParts = self::formatNewsParts($articleIds, $linkNews);

        $parts = self::mergeParts($mergeMode, $manualParts, $newsParts, trim($manual) !== '');

        if ($parts === []) {
            return '';
        }

        return implode(self::separatorHtml(), $parts);
    }

    /**
     * @return list<string> HTML fragments
     */
    private static function formatManualParts(string $manual, bool $linkExternal): array
    {
        $manual = trim($manual);
        if ($manual === '') {
            return [];
        }

        $chunks = preg_split('/\s*•\s*/u', $manual) ?: [];
        $out = [];
        foreach ($chunks as $chunk) {
            $chunk = trim((string) $chunk);
            if ($chunk === '') {
                continue;
            }
            if ($linkExternal) {
                $out[] = self::formatManualChunkWithLinks($chunk);
            } else {
                $out[] = htmlspecialchars(strip_tags($chunk), ENT_QUOTES, 'UTF-8');
            }
        }

        return $out;
    }

    private static function formatManualChunkWithLinks(string $chunk): string
    {
        if (preg_match('/<a\s/i', $chunk)) {
            return self::sanitizeAnchorHtml($chunk);
        }

        return self::linkifyPlainText(strip_tags($chunk));
    }

    /**
     * Allow only simple anchor tags in manual ticker text.
     */
    private static function sanitizeAnchorHtml(string $html): string
    {
        if (!preg_match_all(
            '/<a\s+[^>]*href\s*=\s*("|\')([^"\']+)\1[^>]*>(.*?)<\/a>/is',
            $html,
            $matches,
            PREG_SET_ORDER
        )) {
            return self::linkifyPlainText(strip_tags($html));
        }

        $out = '';
        $pos = 0;
        foreach ($matches as $m) {
            $start = strpos($html, $m[0], $pos);
            if ($start === false) {
                continue;
            }
            if ($start > $pos) {
                $out .= self::linkifyPlainText(strip_tags(substr($html, $pos, $start - $pos)));
            }
            $href = self::normalizeHref((string) $m[2]);
            $label = strip_tags((string) $m[3]);
            if ($href !== '' && $label !== '') {
                $out .= self::anchor($href, $label);
            } else {
                $out .= htmlspecialchars($label !== '' ? $label : strip_tags($m[0]), ENT_QUOTES, 'UTF-8');
            }
            $pos = $start + strlen($m[0]);
        }
        if ($pos < strlen($html)) {
            $out .= self::linkifyPlainText(strip_tags(substr($html, $pos)));
        }

        return $out !== '' ? $out : htmlspecialchars(strip_tags($html), ENT_QUOTES, 'UTF-8');
    }

    private static function linkifyPlainText(string $text): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }

        $pattern = '#\bhttps?://[^\s<>"\'•]+#iu';
        if (!preg_match($pattern, $text)) {
            return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        }

        $out = '';
        $offset = 0;
        if (preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $match) {
                $start = (int) $match[1];
                $url = (string) $match[0];
                if ($start > $offset) {
                    $out .= htmlspecialchars(substr($text, $offset, $start - $offset), ENT_QUOTES, 'UTF-8');
                }
                $href = self::normalizeHref($url);
                $out .= $href !== '' ? self::anchor($href, $url) : htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
                $offset = $start + strlen($url);
            }
        }
        if ($offset < strlen($text)) {
            $out .= htmlspecialchars(substr($text, $offset), ENT_QUOTES, 'UTF-8');
        }

        return $out;
    }

    /**
     * @param list<int> $articleIds
     * @return list<string>
     */
    private static function formatNewsParts(array $articleIds, bool $linkNews): array
    {
        if ($articleIds === []) {
            return [];
        }

        $news = cms_utils::get_module('News');
        if (!$news) {
            return [];
        }

        $opsPath = cms_join_path($news->GetModulePath(), 'lib', 'class.news_ops.php');
        if (!is_file($opsPath)) {
            return [];
        }
        require_once $opsPath;

        $out = [];
        foreach ($articleIds as $nid) {
            $article = news_ops::get_article_by_id((int) $nid, true, false);
            if (!is_object($article)) {
                continue;
            }
            $title = trim(strip_tags((string) $article->title));
            if ($title === '') {
                continue;
            }
            if ($linkNews) {
                $url = self::resolveArticlePublicUrl($article, (int) $nid);
                if ($url !== '') {
                    $out[] = self::anchor($url, $title);
                    continue;
                }
            }
            $out[] = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        }

        return $out;
    }

    /**
     * @param list<string> $manualParts
     * @param list<string> $newsParts
     * @return list<string>
     */
    private static function mergeParts(string $mergeMode, array $manualParts, array $newsParts, bool $hasManual): array
    {
        switch ($mergeMode) {
            case MasBreakingLiveTicker::MERGE_NEWS_ONLY:
                if ($newsParts !== []) {
                    return $newsParts;
                }

                return $manualParts;
            case MasBreakingLiveTicker::MERGE_NEWS_FIRST:
                if ($newsParts !== [] && $manualParts !== []) {
                    return array_merge($newsParts, $manualParts);
                }

                return $newsParts !== [] ? $newsParts : $manualParts;
            case MasBreakingLiveTicker::MERGE_MANUAL_FIRST:
                if ($manualParts !== [] && $newsParts !== []) {
                    return array_merge($manualParts, $newsParts);
                }

                return $manualParts !== [] ? $manualParts : $newsParts;
            case MasBreakingLiveTicker::MERGE_MANUAL_ONLY:
            default:
                return $manualParts;
        }
    }

    private static function separatorHtml(): string
    {
        return '<span class="mas-bl-sep" aria-hidden="true"> • </span>';
    }

    private static function anchor(string $href, string $label): string
    {
        return '<a class="mas-bl-ticker-link" href="'
            . htmlspecialchars($href, ENT_QUOTES, 'UTF-8')
            . '" rel="noopener noreferrer">'
            . htmlspecialchars($label, ENT_QUOTES, 'UTF-8')
            . '</a>';
    }

    /**
     * @param object $article news_article
     */
    private static function resolveArticlePublicUrl($article, int $articleId): string
    {
        try {
            $canonical = (string) $article->canonical;
            if ($canonical !== '') {
                $config = cmsms()->GetConfig();
                $root = rtrim((string) ($config['root_url'] ?? ''), '/');
                if ($canonical[0] === '/') {
                    $canonical = $root . $canonical;
                }
                $href = self::normalizeHref($canonical);
                if ($href !== '') {
                    return $href;
                }
            }
        } catch (Exception $e) {
            // fall through
        }

        $news = cms_utils::get_module('News');
        if ($news && $articleId > 0) {
            try {
                $returnId = (int) $news->GetPreference('detail_returnid', 0);
                if ($returnId < 1) {
                    $returnId = (int) cmsms()->GetContentOperations()->GetDefaultContent();
                }
                $link = $news->CreateLink(
                    'm1',
                    'detail',
                    $returnId,
                    '',
                    ['articleid' => $articleId],
                    '',
                    true,
                    false,
                    '',
                    false,
                    true
                );
                if (is_string($link) && $link !== '') {
                    $href = self::normalizeHref($link);
                    if ($href !== '') {
                        return $href;
                    }
                }
            } catch (Exception $e) {
                // fall through
            }
        }

        $config = cmsms()->GetConfig();
        $root = rtrim((string) ($config['root_url'] ?? ''), '/');

        return self::normalizeHref($root . '/news/' . $articleId);
    }

    private static function normalizeHref(string $url): string
    {
        $url = trim(html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($url === '') {
            return '';
        }
        if (!preg_match('#^https?://#i', $url)) {
            return '';
        }
        $filtered = filter_var($url, FILTER_VALIDATE_URL);

        return is_string($filtered) ? $filtered : '';
    }

    /**
     * Cap rendered HTML length (approximate) for ticker output.
     */
    public static function truncateHtml(string $html, int $maxLen = 4000): string
    {
        if ($maxLen < 200) {
            $maxLen = 200;
        }
        if (strlen($html) <= $maxLen) {
            return $html;
        }
        $plain = trim(strip_tags($html));
        if (function_exists('mb_substr')) {
            $plain = mb_substr($plain, 0, $maxLen, 'UTF-8');
        } else {
            $plain = substr($plain, 0, $maxLen);
        }

        return htmlspecialchars($plain, ENT_QUOTES, 'UTF-8') . '…';
    }
}
