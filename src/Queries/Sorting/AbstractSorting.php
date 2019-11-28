<?php

namespace CodeHappy\DataLayer\Queries\Sorting;

use CodeHappy\DataLayer\Queries\AbstractQuery;

abstract class AbstractSorting extends AbstractQuery
{
    /**
     * @const string
     */
    protected const ORIENTATIONS = [
        'ASC',
        'DESC',
    ];

    /**
     * @const string
     */
    protected const DEFAULT_ORIENTATION = 'ASC';

    /**
     * Normalize the sort fields
     *
     * @param array $params
     * @return array
     */
    public function normalize(array $params): array
    {
        if (
            count($params) === 2 &&
            is_string(end($params)) === true &&
            is_int(array_keys($params)[0]) === true &&
            in_array(strtoupper(end($params)), self::ORIENTATIONS) === true
        ) {
            return [
                $params[0] => $params[1],
            ];
        }

        $normalized = [];
        foreach ($params as $key => $param) {
            if (is_int($key) === true && is_string($param) === true) {
                $result = explode(' ', $param);
                if (count($result) === 1) {
                    $normalized[$param] = self::DEFAULT_ORIENTATION;
                    continue;
                }
                if (in_array($result[1], self::ORIENTATIONS) === true) {
                    $normalized[$result[0]] = $result[1];
                    continue;
                }
            }
            if (is_array($param) === true) {
                $normalized = array_merge($normalized, $this->normalize($param));
                continue;
            }
            if (in_array($param, self::ORIENTATIONS) === true) {
                $normalized[$key] = $param;
            }
        }
        return $normalized;
    }
}
