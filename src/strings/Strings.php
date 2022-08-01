<?php

namespace strings;

class Strings implements  StringsInterface {

    public function snakeCaseToCamelCase(string $input): string
    {
        $snake = '_';
        $camel = '';
        return str_replace($snake, $camel, lcfirst(ucwords($input, '_')));
    }

    public function mirrorMultibyteString(string $input): string
    {
        $str_arr = explode(' ', $input);
        $r = '';
        foreach ($str_arr as $item) {
            $r_in_for = '';
            for ($i = mb_strlen($item); $i>=0; $i--) {
                $r_in_for .= mb_substr($item, $i, 1);
            }
            $r .= $r_in_for . ' ';
        }
        return mb_substr($r, 0, -1);
    }

    public function getBrandName(string $noun): string
    {
        if ($noun[0] === $noun[strlen($noun) - 1]) {
             return ucfirst(substr($noun, 0, -1) . $noun);
        }
        else {
            return 'The ' . ucfirst($noun);
        }
    }
}