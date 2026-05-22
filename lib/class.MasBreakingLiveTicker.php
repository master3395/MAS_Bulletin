<?php
/**
 * Merge manual ticker text with News article titles (order preserved).
 */
declare(strict_types=1);

final class MasBreakingLiveTicker
{
    public const MERGE_MANUAL_ONLY = 'manual_only';
    public const MERGE_NEWS_ONLY = 'news_only';
    public const MERGE_NEWS_FIRST = 'news_first';
    public const MERGE_MANUAL_FIRST = 'manual_first';

    /**
     * @return list<int>
     */
    public static function parseIdOrder(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return [];
        }
        $out = [];
        foreach (preg_split('/[\s,]+/', $raw) as $p) {
            $n = (int) $p;
            if ($n > 0) {
                $out[] = $n;
            }
        }
        $out = array_values(array_unique($out));

        return array_slice($out, 0, 20);
    }

    /**
     * @param list<int> $ids
     */
    public static function idsToOrderString(array $ids): string
    {
        $ids = array_values(array_filter(array_map('intval', $ids), static function ($v) {
            return $v > 0;
        }));

        return implode(',', array_slice(array_unique($ids), 0, 20));
    }

    public static function mergeLine(string $mergeMode, string $manual, array $orderedIds): string
    {
        $manual = trim($manual);
        $newsLine = self::newsTitlesJoined($orderedIds);
        switch ($mergeMode) {
            case self::MERGE_NEWS_ONLY:
                return $newsLine !== '' ? $newsLine : $manual;
            case self::MERGE_NEWS_FIRST:
                if ($newsLine !== '' && $manual !== '') {
                    return $newsLine . ' • ' . $manual;
                }
                return $newsLine !== '' ? $newsLine : $manual;
            case self::MERGE_MANUAL_FIRST:
                if ($manual !== '' && $newsLine !== '') {
                    return $manual . ' • ' . $newsLine;
                }
                return $manual !== '' ? $manual : $newsLine;
            case self::MERGE_MANUAL_ONLY:
            default:
                return $manual;
        }
    }

    /**
     * @param list<int> $orderedIds
     */
    public static function newsTitlesJoined(array $orderedIds): string
    {
        if ($orderedIds === []) {
            return '';
        }
        $news = cms_utils::get_module('News');
        if (!$news) {
            return '';
        }
        $opsPath = cms_join_path($news->GetModulePath(), 'lib', 'class.news_ops.php');
        if (!is_file($opsPath)) {
            return '';
        }
        require_once $opsPath;
        $parts = [];
        foreach ($orderedIds as $nid) {
            $article = news_ops::get_article_by_id((int) $nid, true, false);
            if (!is_object($article)) {
                continue;
            }
            $t = trim(strip_tags((string) $article->title));
            if ($t !== '') {
                $parts[] = $t;
            }
        }

        return implode(' • ', $parts);
    }

    /**
     * @return list<array{id:int,title:string}>
     */
    public static function recentArticlesForPicker(int $limit = 80): array
    {
        $limit = max(5, min(120, $limit));
        $db = CmsApp::get_instance()->GetDb();
        $sql = 'SELECT news_id, news_title FROM ' . CMS_DB_PREFIX . 'module_news '
            . "WHERE status = 'published' ORDER BY news_date DESC LIMIT " . (int) $limit;
        $rows = $db->GetArray($sql);
        if (!is_array($rows)) {
            return [];
        }
        $out = [];
        foreach ($rows as $row) {
            $id = isset($row['news_id']) ? (int) $row['news_id'] : 0;
            if ($id <= 0) {
                continue;
            }
            $title = isset($row['news_title']) ? trim((string) $row['news_title']) : '';
            $out[] = ['id' => $id, 'title' => $title !== '' ? $title : ('#' . $id)];
        }

        return $out;
    }

    public static function normalizeMergeMode(string $v): string
    {
        $allowed = [
            self::MERGE_MANUAL_ONLY,
            self::MERGE_NEWS_ONLY,
            self::MERGE_NEWS_FIRST,
            self::MERGE_MANUAL_FIRST,
        ];
        $v = trim($v);

        return in_array($v, $allowed, true) ? $v : self::MERGE_MANUAL_ONLY;
    }
}
