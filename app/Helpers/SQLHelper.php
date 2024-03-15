<?php

namespace App\Helpers;

class SQLHelper
{
    // untuk menyimpan function-function SQL yang akan terpakai
    public static function getTableNames($sql)
    {
        $sql = str_replace("\\n", '', $sql);
        $pattern = '/\bFROM\s+((\w+)\s*(?:,\s*(\w+)\s*)*)/i';
        $tableNames = array();

        if (preg_match($pattern, $sql, $matches)) {
            $tableNames = preg_split('/\s*,\s*/', $matches[1]);
        }

        return $tableNames;
    }

    public static function findSQLAggregationKeywords($input)
    {
        $pattern = '/\b(SUM|COUNT|AVG|MAX|MIN)\b/i';
        preg_match_all($pattern, $input, $matches);
        return $matches[0];
    }

    public static function splitSubquery($sql)
    {
        preg_match("/^(.*) (WHERE .*) (=|>|<|>=|<=|<>|LIKE|IN|NOT IN)\s*\((.*)\)$/", $sql, $matches);
        $outer_query = $matches[1] . " " . $matches[2] . " " . $matches[3];
        $inner_query = $matches[4];

        return array("outer" => $outer_query, "inner" => $inner_query);
    }

    public static function getWhereAndOrderByClauseColumns($sql)
    {
        $columns = array();

        // Extract columns from WHERE clause
        $wherePattern = '/\bWHERE\s+(.*)/i';
        preg_match($wherePattern, $sql, $whereMatches);

        if (count($whereMatches) > 0) {
            $whereClause = $whereMatches[1];
            $pattern = '/\b(\w+)\s*(=|<|>|<=|>=|<>|LIKE|IN|NOT\s+IN|BETWEEN)\s*([^,]*)/i';

            preg_match_all($pattern, $whereClause, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                $columns[] = $match[1];
            }
        }

        // Extract columns from ORDER BY clause (excluding any LIMIT statement)
        $orderPattern = '/\bORDER BY\s+(?:(?!LIMIT\s+\d).*?)(\w+)\s*(ASC|DESC)?\s*,?/i';
        preg_match_all($orderPattern, $sql, $orderMatches, PREG_SET_ORDER);

        foreach ($orderMatches as $match) {
            $columns[] = $match[1];
        }

        return $columns;
    }

    public static function findUnionOrCross(string $sql): string
    {
        preg_match('/\b(UNION|CROSS)\b/i', $sql, $matches); // case insensitive search for UNION or CROSS
        return $matches[1] ?? ''; // return the first match, or empty string if no match
    }

    public static function splitUnion(string $sql): array
    {
        $pattern = '/^(.*?)\b(UNION(?:\s+ALL)?)\b(.*)$/is'; // case insensitive search for UNION or UNION ALL
        preg_match($pattern, $sql, $matches); // find the first UNION or UNION ALL in the query
        return [ // return the right and left queries
            'left' => trim($matches[1] ?? ''), // remove any leading or trailing whitespace in match[1] is the left query
            'right' => trim($matches[3] ?? ''), // remove any leading or trailing whitespace in match[3] is the right query
        ];
    }
}
