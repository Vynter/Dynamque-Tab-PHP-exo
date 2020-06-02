<?php

namespace App;

class TableHelper
{
    const SORT_KEY = 'sort';
    const DIR_KEY = 'dir';

    public static function sort($sortKey, $label, $data)
    {
        $sort = $data[self::SORT_KEY] ?? null; // utilisation des constant
        $direction = $data[self::DIR_KEY] ?? null;
        $icon = "";
        if ($sort === $sortKey) {
            if ($direction === 'asc') { // format plus optomal : $icon = $direction ==='asc' ? '^' : 'v';
                $icon = '^';
            } else {
                $icon = 'v';
            }
        }
        $url = URLHelper::withParams($data, [
            'sort' => $sortKey,
            'dir' => $direction === 'asc' && ($sort === $sortKey) ? 'desc' : 'asc'
        ]);
        return <<<HTML
        <a href="?$url">$label $icon</a>
        HTML;
    }
}