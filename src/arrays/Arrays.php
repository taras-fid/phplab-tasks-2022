<?php

namespace arrays;

class Arrays implements ArraysInterface {

    public function repeatArrayValues(array $input): array
    {
        $res_arr = [];

        foreach ($input as $item) {
            for ($i = 0; $i < $item; $i++) {
                $res_arr[] = $item;
            }
        }

        return $res_arr;
    }

    public function getUniqueValue(array $input): int
    {
        $unique_arr = [];

        foreach ($input as $item) {
            if (!in_array($item, $unique_arr)) {
                $unique_arr[] = $item;
            } else {
                unset($unique_arr[array_search($item, $unique_arr)]);
            }
        }

        if (empty($unique_arr)) {
            return 0;
        }

        return min($unique_arr);
    }

    public function groupByTag(array $input): array
    {
        $res_arr = [];

        foreach ($input as $arr) {
            foreach ($arr['tags'] as $tag) {
                $res_arr[$tag][] = $arr['name'];
                sort($res_arr[$tag]);
            }
        }

        return $res_arr;
    }
}